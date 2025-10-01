<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Ordonnance;
use App\Models\Medicament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Rediriger vers le dashboard approprié selon le rôle
        if ($user->hasRole('Admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('Doctor')) {
            return $this->doctorDashboard();
        } elseif ($user->hasRole('Secretary')) {
            return $this->secretaryDashboard();
        } else {
            // Par défaut, dashboard patient
            return $this->patientDashboard();
        }
    }

    public function adminDashboard()
    {
        // Statistiques de base
        $totalUsers = User::count();
        $totalDoctors = User::role('Doctor')->count();
        $totalPatients = User::role('Patient')->count();
        $totalSecretaries = User::role('Secretary')->count();
        $totalAppointments = Appointment::count();
        $totalPrescriptions = Ordonnance::count();
        
        // Revenus du mois
        $totalRevenue = (float) (Appointment::whereMonth('appointments.created_at', now()->month)
            ->whereYear('appointments.created_at', now()->year)
            ->where('appointments.status', 'confirmed')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price') ?? 0);
        
        // Gestion des lits (simulation)
        $totalBeds = 50; // À remplacer par une vraie table
        $occupiedBeds = Appointment::where('appointments.status', 'confirmed')
            ->whereDate('appointments.appointment_date', now()->toDateString())
            ->count();
        $availableBeds = $totalBeds - $occupiedBeds;
        
        // Médicaments en rupture de stock
        $lowStockMedicines = Medicament::where('disponible', false)->count();
        
        // Taux de croissance mensuel
        $lastMonthUsers = User::whereMonth('users.created_at', now()->subMonth()->month)
            ->whereYear('users.created_at', now()->subMonth()->year)
            ->count();
        $growthRate = $lastMonthUsers > 0 ? 
            round((($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1) : 0;

        $data = [
            'totalUsers' => $totalUsers,
            'totalDoctors' => $totalDoctors,
            'totalPatients' => $totalPatients,
            'totalSecretaries' => $totalSecretaries,
            'totalAppointments' => $totalAppointments,
            'totalPrescriptions' => $totalPrescriptions,
            'totalServices' => Service::count(),
            'totalArticles' => Article::count(),
            'totalRevenue' => number_format($totalRevenue, 0, ',', ' '),
            'totalBeds' => $totalBeds,
            'availableBeds' => $availableBeds,
            'lowStockMedicines' => $lowStockMedicines,
            'growthRate' => $growthRate,
            'activeUsers' => User::where('email_verified_at', '!=', null)->count(),
            'confirmedAppointments' => Appointment::where('status', 'confirmed')->count(),
            'pendingAppointments' => Appointment::where('status', 'pending')->count(),
            'recentActivity' => $this->getRecentActivity(),
        ];

        return view('admin.dashboard', $data);
    }

    public function accounting()
    {
        // Statistiques comptables
        $monthlyRevenue = (float) (Appointment::whereMonth('appointments.created_at', now()->month)
            ->whereYear('appointments.created_at', now()->year)
            ->where('appointments.status', 'confirmed')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price') ?? 0);
        
        $totalRevenue = (float) (Appointment::where('appointments.status', 'confirmed')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price') ?? 0);
        
        $pendingPayments = (float) (Appointment::where('appointments.status', 'pending')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price') ?? 0);
        
        $monthlyAppointments = Appointment::whereMonth('appointments.created_at', now()->month)
            ->whereYear('appointments.created_at', now()->year)
            ->count();
        
        return view('admin.accounting.index', compact('monthlyRevenue', 'totalRevenue', 'pendingPayments', 'monthlyAppointments'));
    }

    public function bedsManagement()
    {
        // Simulation de gestion des lits
        $totalBeds = 50;
        $occupiedBeds = Appointment::where('appointments.status', 'confirmed')
            ->whereDate('appointments.appointment_date', now()->toDateString())
            ->count();
        $availableBeds = $totalBeds - $occupiedBeds;
        
        $beds = collect(range(1, $totalBeds))->map(function ($bedNumber) use ($occupiedBeds) {
            return [
                'number' => $bedNumber,
                'status' => $bedNumber <= $occupiedBeds ? 'occupied' : 'available',
                'patient' => $bedNumber <= $occupiedBeds ? 'Patient ' . $bedNumber : null,
            ];
        });
        
        return view('admin.beds.index', compact('beds', 'totalBeds', 'occupiedBeds', 'availableBeds'));
    }

    public function pharmacyStock()
    {
        $medicaments = Medicament::orderBy('nom')->paginate(20);
        $totalMedicaments = Medicament::count();
        $availableMedicaments = Medicament::where('disponible', true)->count();
        $lowStockMedicines = Medicament::where('disponible', false)->count();
        
        return view('admin.pharmacy.index', compact('medicaments', 'totalMedicaments', 'availableMedicaments', 'lowStockMedicines'));
    }

    public function prescriptionsManagement()
    {
        $prescriptions = \App\Models\Prescription::with('service')
            ->orderBy('name')
            ->paginate(20);
        
        $totalPrescriptions = \App\Models\Prescription::count();
        $totalServices = \App\Models\Service::count();
        
        // Regrouper par service
        $prescriptionsByService = \App\Models\Prescription::with('service')
            ->get()
            ->groupBy('service.name');
        
        return view('admin.prescriptions.index', compact('prescriptions', 'totalPrescriptions', 'totalServices', 'prescriptionsByService'));
    }

    public function ordonnancesManagement()
    {
        $ordonnances = Ordonnance::with(['patient', 'medecin', 'medicaments'])
            ->orderBy('date_prescription', 'desc')
            ->paginate(20);
        
        $totalOrdonnances = Ordonnance::count();
        $activeOrdonnances = Ordonnance::where('statut', 'active')->count();
        $expiredOrdonnances = Ordonnance::where('statut', 'expiree')->count();
        $thisMonthOrdonnances = Ordonnance::whereMonth('date_prescription', now()->month)
            ->whereYear('date_prescription', now()->year)
            ->count();
        
        return view('admin.ordonnances.index', compact('ordonnances', 'totalOrdonnances', 'activeOrdonnances', 'expiredOrdonnances', 'thisMonthOrdonnances'));
    }

    public function doctorDashboard()
    {
        $doctor = Auth::user();

        // Statistiques de base
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
            ->count();
        
        $totalPatients = Appointment::where('doctor_id', $doctor->id)
                ->distinct('user_id')
            ->count();
        
        $pendingAppointments = Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'pending')
            ->count();
        
        $confirmedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'confirmed')
                ->whereDate('appointment_date', today())
            ->count();
        
        // Statistiques hebdomadaires
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $weekAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereBetween('appointment_date', [$weekStart, $weekEnd])
            ->count();
        
        // Statistiques mensuelles
        $monthAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->count();
        
        // Revenus du mois pour le docteur
        $monthlyRevenue = (float) (Appointment::where('doctor_id', $doctor->id)
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->where('status', 'confirmed')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price') ?? 0);
        
        // Prescriptions et examens
        $totalPrescriptions = \App\Models\Ordonnance::where('medecin_id', $doctor->id)->count();
        $totalExams = \App\Models\Exam::count(); // Tous les examens disponibles
        
        // Patients avec consultations récentes
        $recentPatients = User::whereHas('appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->latest()
            ->take(5)
            ->get();
        
        // Rendez-vous d'aujourd'hui avec plus de détails
        $todayAppointmentsList = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->with(['user', 'service'])
            ->orderBy('appointment_time')
            ->get();
        
        // Prochains rendez-vous (pas seulement aujourd'hui)
        $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', '>=', today())
            ->where('status', 'confirmed')
            ->with(['user', 'service'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();
        
        // Graphiques - données pour les 7 derniers jours
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', $date)
                ->count();
            $chartData[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('D'),
                'count' => $count
            ];
        }
        
        // Répartition par service
        $servicesDistribution = Appointment::where('doctor_id', $doctor->id)
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('count(*) as count'))
            ->groupBy('services.name')
            ->get();
        
        // Notifications et alertes
        $urgentAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->whereDate('appointment_date', '<=', now()->addDays(1))
            ->count();
        
        $overdueAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->whereDate('appointment_date', '<', today())
            ->count();

        $data = [
            'todayAppointments' => $todayAppointments,
            'totalPatients' => $totalPatients,
            'pendingAppointments' => $pendingAppointments,
            'confirmedAppointments' => $confirmedAppointments,
            'weekAppointments' => $weekAppointments,
            'monthAppointments' => $monthAppointments,
            'totalPrescriptions' => $totalPrescriptions,
            'totalExams' => $totalExams,
            'monthlyRevenue' => $monthlyRevenue,
            'todayAppointmentsList' => $todayAppointmentsList,
            'upcomingAppointments' => $upcomingAppointments,
            'recentPatients' => $recentPatients,
            'chartData' => $chartData,
            'servicesDistribution' => $servicesDistribution,
            'urgentAppointments' => $urgentAppointments,
            'overdueAppointments' => $overdueAppointments,
        ];

        return view('doctor.dashboard', $data);
    }

    public function secretaryDashboard()
    {
        $data = [
            'todayAppointments' => Appointment::whereDate('appointment_date', today())->count(),
            'newPatients' => User::whereDate('created_at', today())->count(),
            'pendingAppointments' => Appointment::where('status', 'pending')->count(),
            'callsToday' => 0, // À implémenter selon votre modèle
            'todaySchedule' => Appointment::whereDate('appointment_date', today())
                ->with(['user', 'service', 'doctor.grade'])
                ->orderBy('appointment_time')
                ->get(),
            'recentCalls' => collect([]), // À implémenter selon votre modèle
        ];

        return view('secretary.dashboard', $data);
    }

    public function patientDashboard()
    {
        $patient = Auth::user();

        $data = [
            'upcomingAppointments' => Appointment::where('user_id', $patient->id)
                ->where('is_visited', false)
                ->whereDate('appointment_date', '>=', today())
                ->count(),
            'activePrescriptions' => 0, // À implémenter selon votre modèle
            'pendingExams' => 0, // À implémenter selon votre modèle
            'totalMedicalFiles' => 1, // Par défaut, chaque patient a un dossier
            'upcomingAppointmentsList' => Appointment::where('user_id', $patient->id)
                ->where('status', '!=', 'cancelled')
                ->whereDate('appointment_date', '>=', today())
                ->with(['service', 'doctor.grade'])
                ->orderBy('appointment_date')
                ->orderBy('appointment_time')
                ->take(10)
                ->get(),
            'recommendedArticles' => Article::latest()->take(3)->get(),
            'recentActivity' => $this->getPatientActivity($patient->id),
        ];

        return view('patient.dashboard', $data);
    }

    private function getRecentActivity()
    {
        // Simuler des activités récentes pour l'admin
        return collect([
            (object) [
                'type' => 'user',
                'description' => 'Nouveau patient inscrit',
                'created_at' => now()->subMinutes(30)
            ],
            (object) [
                'type' => 'service',
                'description' => 'Service "Cardiologie" créé',
                'created_at' => now()->subHours(2)
            ],
            (object) [
                'type' => 'appointment',
                'description' => 'Rendez-vous confirmé pour Dr. Martin',
                'created_at' => now()->subHours(4)
            ],
        ]);
    }

    private function getPatientActivity($patientId)
    {
        // Simuler des activités récentes pour le patient
        return collect([
            (object) [
                'type' => 'appointment',
                'description' => 'Rendez-vous confirmé avec Dr. Martin',
                'created_at' => now()->subHours(2)
            ],
            (object) [
                'type' => 'prescription',
                'description' => 'Nouvelle prescription reçue',
                'created_at' => now()->subDays(1)
            ],
            (object) [
                'type' => 'exam',
                'description' => 'Résultats d\'examen disponibles',
                'created_at' => now()->subDays(2)
            ],
        ]);
    }

    // Méthodes pour les dashboards spécifiques
    public function adminStatistics()
    {
        return view('admin.statistics');
    }

    public function adminSettings()
    {
        return view('admin.settings');
    }

    public function adminLogs()
    {
        return view('admin.logs');
    }

    public function adminBackup()
    {
        return view('admin.backup');
    }

    public function adminFaq()
    {
        return view('admin.faq');
    }

    public function patientHealthSummary()
    {
        return view('patient.health-summary');
    }

    public function patientVitalSigns()
    {
        return view('patient.vital-signs');
    }

    public function patientMedicalFile()
    {
        $patient = Auth::user();

        // Récupérer les données du dossier médical du patient
        $data = [
            'totalAppointments' => Appointment::where('user_id', $patient->id)->count(),
            'totalPrescriptions' => 0, // À implémenter selon votre modèle
            'totalExams' => 0, // À implémenter selon votre modèle
            'totalVaccines' => 0, // À implémenter selon votre modèle
            'medicalHistory' => collect([]), // À implémenter selon votre modèle
            'prescriptions' => collect([]), // À implémenter selon votre modèle
            'exams' => collect([]), // À implémenter selon votre modèle
            'vaccines' => collect([]), // À implémenter selon votre modèle
            'allergies' => collect([]), // À implémenter selon votre modèle
        ];

        return view('patient.medical-files.show', $data);
    }

    public function patientAllergies()
    {
        return view('patient.allergies');
    }

    public function patientMedications()
    {
        return view('patient.medications');
    }

    public function patientVaccines()
    {
        return view('patient.vaccines');
    }

    public function patientDoctors()
    {
        return view('patient.doctors');
    }

    public function patientPreferences()
    {
        return view('patient.preferences');
    }

    public function patientPrivacy()
    {
        return view('patient.privacy');
    }

    public function secretaryStatistics()
    {
        return view('secretary.statistics');
    }

    public function secretaryPatients()
    {
        return view('secretary.patients');
    }

    public function secretaryCreatePatient()
    {
        return view('secretary.patients.create');
    }

    public function secretarySearchPatients()
    {
        return view('secretary.patients.search');
    }

    public function secretaryDoctors()
    {
        return view('secretary.doctors');
    }

    public function secretaryReception()
    {
        return view('secretary.reception');
    }

    public function secretaryWaitingList()
    {
        return view('secretary.waiting-list');
    }

    public function secretaryCheckIn()
    {
        return view('secretary.check-in');
    }

    public function secretaryReminders()
    {
        return view('secretary.reminders');
    }

    public function secretaryReports()
    {
        return view('secretary.reports');
    }

    public function secretaryBilling()
    {
        return view('secretary.billing');
    }

    public function secretaryInventory()
    {
        return view('secretary.inventory');
    }

    public function secretaryProfile()
    {
        return view('secretary.profile');
    }

    public function secretarySettings()
    {
        return view('secretary.settings');
    }

    // ==================== GESTION DES MÉDICAMENTS ====================

    public function showMedicament($id)
    {
        $medicament = \App\Models\Medicament::findOrFail($id);
        return view('admin.pharmacy.show', compact('medicament'));
    }

    public function editMedicament($id)
    {
        $medicament = \App\Models\Medicament::findOrFail($id);
        return view('admin.pharmacy.edit', compact('medicament'));
    }

    public function updateMedicament(Request $request, $id)
    {
        $medicament = \App\Models\Medicament::findOrFail($id);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'categorie' => 'required|string|max:100',
            'description' => 'nullable|string',
            'quantite_stock' => 'required|integer|min:0',
            'unite_mesure' => 'required|string|max:50',
            'prix_unitaire' => 'required|numeric|min:0',
            'date_expiration' => 'nullable|date',
            'disponible' => 'required|boolean',
        ]);
        
        $medicament->update($validated);
        
        return redirect()->route('admin.pharmacy.show', $medicament)->with('success', 'Médicament mis à jour avec succès.');
    }

    public function storeMedicament(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'categorie' => 'required|string|max:100',
            'description' => 'nullable|string',
            'quantite_stock' => 'required|integer|min:0',
            'unite_mesure' => 'required|string|max:50',
            'prix_unitaire' => 'required|numeric|min:0',
            'date_expiration' => 'nullable|date',
        ]);
        
        $validated['disponible'] = $validated['quantite_stock'] > 0;
        
        $medicament = \App\Models\Medicament::create($validated);
        
        return redirect()->route('admin.pharmacy')->with('success', 'Médicament créé avec succès.');
    }

    public function destroyMedicament($id)
    {
        $medicament = \App\Models\Medicament::findOrFail($id);
        $medicament->delete();
        
        return redirect()->route('admin.pharmacy')->with('success', 'Médicament supprimé avec succès.');
    }

    // ==================== GESTION DES LITS ====================

    public function showBed($id)
    {
        $bed = [
            'number' => $id,
            'status' => rand(0, 1) ? 'occupied' : 'available',
            'patient' => rand(0, 1) ? 'Patient ' . $id : null,
            'room' => 'Chambre ' . ceil($id / 2),
            'service' => 'Service général',
            'type' => 'Standard',
        ];
        
        return view('admin.beds.show', compact('bed'));
    }

    public function editBed($id)
    {
        $bed = [
            'number' => $id,
            'status' => rand(0, 1) ? 'occupied' : 'available',
            'patient' => rand(0, 1) ? 'Patient ' . $id : null,
            'room' => 'Chambre ' . ceil($id / 2),
            'service' => 'Service général',
            'type' => 'Standard',
        ];
        
        return view('admin.beds.edit', compact('bed'));
    }

    public function updateBed(Request $request, $id)
    {
        // Logique de mise à jour du lit
        return redirect()->route('admin.beds.show', $id)->with('success', 'Lit mis à jour avec succès.');
    }

    public function storeBed(Request $request)
    {
        // Logique de création de lit
        return redirect()->route('admin.beds')->with('success', 'Lit créé avec succès.');
    }

    public function destroyBed($id)
    {
        // Logique de suppression de lit
        return redirect()->route('admin.beds')->with('success', 'Lit supprimé avec succès.');
    }

    // ==================== GESTION DES PRESCRIPTIONS (SOINS MÉDICAUX) ====================

    public function showPrescription($id)
    {
        $prescription = \App\Models\Prescription::with('service')->findOrFail($id);
        return view('admin.prescriptions.show', compact('prescription'));
    }

    public function editPrescription($id)
    {
        $prescription = \App\Models\Prescription::with('service')->findOrFail($id);
        $services = \App\Models\Service::all();
        return view('admin.prescriptions.edit', compact('prescription', 'services'));
    }

    public function updatePrescription(Request $request, $id)
    {
        $prescription = \App\Models\Prescription::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'service_id' => 'required|exists:services,id',
        ]);
        
        $prescription->update($validated);
        
        return redirect()->route('admin.prescriptions')->with('success', 'Prescription mise à jour avec succès.');
    }

    public function storePrescription(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'service_id' => 'required|exists:services,id',
        ]);
        
        \App\Models\Prescription::create($validated);
        
        return redirect()->route('admin.prescriptions')->with('success', 'Prescription créée avec succès.');
    }

    public function destroyPrescription($id)
    {
        $prescription = \App\Models\Prescription::findOrFail($id);
        $prescription->delete();
        
        return redirect()->route('admin.prescriptions')->with('success', 'Prescription supprimée avec succès.');
    }

    // ==================== GESTION DES ORDONNANCES ====================

    public function showOrdonnance($id)
    {
        $ordonnance = \App\Models\Ordonnance::with(['medecin', 'medicaments'])->findOrFail($id);
        return view('admin.ordonnances.show', compact('ordonnance'));
    }

    public function editOrdonnance($id)
    {
        $ordonnance = \App\Models\Ordonnance::with(['medecin', 'medicaments'])->findOrFail($id);
        return view('admin.ordonnances.edit', compact('ordonnance'));
    }

    public function updateOrdonnance(Request $request, $id)
    {
        $ordonnance = \App\Models\Ordonnance::findOrFail($id);
        
        $validated = $request->validate([
            'numero_ordonnance' => 'required|string|max:255',
            'date_prescription' => 'required|date',
            'patient_id' => 'required|exists:users,id',
            'medecin_id' => 'required|exists:users,id',
            'statut' => 'required|string|in:active,expiree,annulee',
            'instructions' => 'nullable|string',
            'medicaments' => 'required|array',
        ]);
        
        $ordonnance->update([
            'numero_ordonnance' => $validated['numero_ordonnance'],
            'date_prescription' => $validated['date_prescription'],
            'patient_id' => $validated['patient_id'],
            'medecin_id' => $validated['medecin_id'],
            'statut' => $validated['statut'],
            'instructions' => $validated['instructions'] ?? null,
        ]);
        
        // Mise à jour des médicaments
        $medicaments = [];
        foreach ($validated['medicaments'] as $medicamentId) {
            $medicaments[$medicamentId] = [
                'quantite' => $request->input("medicament_quantite.{$medicamentId}", 1),
                'posologie' => $request->input("medicament_posologie.{$medicamentId}"),
                'duree' => $request->input("medicament_duree.{$medicamentId}"),
            ];
        }
        $ordonnance->medicaments()->sync($medicaments);
        
        return redirect()->route('admin.ordonnances.show', $ordonnance)->with('success', 'Ordonnance mise à jour avec succès.');
    }

    public function storeOrdonnance(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'medecin_id' => 'required|exists:users,id',
            'date_prescription' => 'required|date',
            'statut' => 'required|string|in:active,expiree,annulee',
            'instructions' => 'nullable|string',
            'medicaments' => 'required|array',
        ]);
        
        // Générer un numéro d'ordonnance unique
        $numeroOrdonnance = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
        
        $ordonnance = \App\Models\Ordonnance::create([
            'numero_ordonnance' => $numeroOrdonnance,
            'date_prescription' => $validated['date_prescription'],
            'patient_id' => $validated['patient_id'],
            'patient_nom_complet' => \App\Models\User::find($validated['patient_id'])->name,
            'patient_email' => \App\Models\User::find($validated['patient_id'])->email,
            'medecin_id' => $validated['medecin_id'],
            'medecin_nom_complet' => \App\Models\User::find($validated['medecin_id'])->name,
            'statut' => $validated['statut'],
            'instructions' => $validated['instructions'] ?? null,
        ]);
        
        // Attacher les médicaments
        $ordonnance->medicaments()->attach($validated['medicaments']);
        
        return redirect()->route('admin.ordonnances')->with('success', 'Ordonnance créée avec succès.');
    }

    public function destroyOrdonnance($id)
    {
        $ordonnance = \App\Models\Ordonnance::findOrFail($id);
        $ordonnance->medicaments()->detach();
        $ordonnance->delete();
        
        return redirect()->route('admin.ordonnances')->with('success', 'Ordonnance supprimée avec succès.');
    }
}
