<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Ordonnance;
use App\Models\Medicament;
use App\Models\Bed;
use App\Models\Exam;
use App\Models\Prescription;
use App\Models\MedicalFilePrescription;
use App\Models\MedicalFileExam;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('Doctor')) {
            return $this->doctorDashboard();
        } elseif ($user->hasRole('Secretary')) {
            return $this->secretaryDashboard();
        } else {
            return $this->patientDashboard();
        }
    }

    public function adminDashboard()
    {
        $user = Auth::user();

        $totalUsers = User::count();
        $totalDoctors = User::role('Doctor')->count();
        $totalPatients = User::role('Patient')->count();
        $totalSecretaries = User::role('Secretary')->count();
        $totalAppointments = Appointment::count();
        $totalPrescriptions = Ordonnance::count();

        $totalRevenue = (float) (Appointment::whereMonth('appointments.created_at', now()->month)
            ->whereYear('appointments.created_at', now()->year)
            ->where('appointments.status', 'confirmed')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price') ?? 0);

        $totalBeds = Bed::count();
        $occupiedBeds = Bed::where('status', 'occupe')->count();
        $availableBeds = $totalBeds - $occupiedBeds;

        $lowStockMedicines = Medicament::where('disponible', false)->count();

        $lastMonthUsers = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
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
            'activeUsers' => User::whereNotNull('email_verified_at')->count(),
            'confirmedAppointments' => Appointment::where('status', 'confirmed')->count(),
            'pendingAppointments' => Appointment::where('status', 'pending')->count(),
            'recentActivity' => $this->getRecentActivity(),
        ];

        return view('admin.dashboard', $data);
    }

    public function accounting()
    {
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

        $monthlyAppointments = Appointment::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.accounting.index', compact('monthlyRevenue', 'totalRevenue', 'pendingPayments', 'monthlyAppointments'));
    }

    public function generateInvoice(Request $request)
    {
        $appointments = Appointment::with(['user', 'service', 'doctor'])
            ->where('status', 'confirmed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $total = $appointments->sum(fn($apt) => $apt->service->price ?? 0);

        $pdf = \PDF::loadView('admin.accounting.invoice-pdf', [
            'appointments' => $appointments,
            'total' => $total,
            'month' => now()->format('F Y'),
        ]);

        return $pdf->download('facture-' . now()->format('Y-m') . '.pdf');
    }

    public function exportFinancialData()
    {
        return \Excel::download(new \App\Exports\FinancialDataExport(), 'donnees-financieres-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function generateFinancialReport()
    {
        $monthlyRevenue = (float) (Appointment::whereMonth('appointments.created_at', now()->month)
            ->whereYear('appointments.created_at', now()->year)
            ->where('appointments.status', 'confirmed')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price') ?? 0);

        $totalRevenue = (float) (Appointment::where('appointments.status', 'confirmed')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price') ?? 0);

        $appointments = Appointment::with(['service'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $months = collect(range(0, 11))->map(fn($i) => now()->subMonths($i))->reverse();
        $monthlyData = $months->map(function($date) {
            $appointments = Appointment::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count();
            $revenue = (float) (Appointment::whereMonth('appointments.created_at', $date->month)
                ->whereYear('appointments.created_at', $date->year)
                ->where('appointments.status', 'confirmed')
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price') ?? 0);
            
            return ['month' => $date->format('M Y'), 'appointments' => $appointments, 'revenue' => $revenue];
        });
        
        $topServices = Appointment::join('services', 'appointments.service_id', '=', 'services.id')
            ->select('services.name', DB::raw('count(*) as count'))
            ->groupBy('services.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        $statusDistribution = Appointment::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
        
        $totalPatients = User::role('Patient')->count();
        $totalDoctors = User::role('Doctor')->count();
        $recentAppointments = Appointment::with(['user', 'service', 'doctor'])->orderBy('created_at', 'desc')->limit(10)->get();
        
        return view('admin.accounting.financial-report', compact('monthlyRevenue', 'totalRevenue', 'monthlyData', 'topServices', 'statusDistribution', 'totalPatients', 'totalDoctors', 'recentAppointments'));
    }

    public function bedsManagement()
    {
        $totalBeds = Bed::count();
        $occupiedBeds = Bed::where('status', 'occupe')->count();
        $availableBeds = $totalBeds - $occupiedBeds;
        $beds = Bed::all();

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
        $prescriptions = Prescription::with('service')->orderBy('name')->paginate(20);
        $totalPrescriptions = Prescription::count();
        $totalServices = Service::count();
        $prescriptionsByService = Prescription::with('service')->get()->groupBy('service.name');

        return view('admin.prescriptions.index', compact('prescriptions', 'totalPrescriptions', 'totalServices', 'prescriptionsByService'));
    }

    public function ordonnancesManagement()
    {
        $ordonnances = Ordonnance::with(['patient', 'medecin', 'medicaments'])->orderBy('date_prescription', 'desc')->paginate(20);
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

        $data = [
            'todayAppointments' => Appointment::where('doctor_id', $doctor->id)->whereDate('appointment_date', today())->count(),
            'totalPatients' => Appointment::where('doctor_id', $doctor->id)->distinct('user_id')->count(),
            'pendingAppointments' => Appointment::where('doctor_id', $doctor->id)->where('status', 'pending')->count(),
            'confirmedAppointments' => Appointment::where('doctor_id', $doctor->id)->where('status', 'confirmed')->whereDate('appointment_date', today())->count(),
            'weekAppointments' => Appointment::where('doctor_id', $doctor->id)->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'monthAppointments' => Appointment::where('doctor_id', $doctor->id)->whereMonth('appointment_date', now()->month)->whereYear('appointment_date', now()->year)->count(),
            'totalPrescriptions' => Ordonnance::where('medecin_id', $doctor->id)->count(),
            'totalExams' => Exam::count(),
            'monthlyRevenue' => (float) (Appointment::where('doctor_id', $doctor->id)
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->where('status', 'confirmed')
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price') ?? 0),
            'todayAppointmentsList' => Appointment::where('doctor_id', $doctor->id)->whereDate('appointment_date', today())->with(['user', 'service'])->orderBy('appointment_time')->get(),
            'upcomingAppointments' => Appointment::where('doctor_id', $doctor->id)->whereDate('appointment_date', '>=', today())->where('status', 'confirmed')->with(['user', 'service'])->orderBy('appointment_date')->orderBy('appointment_time')->take(5)->get(),
            'recentPatients' => User::whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))->latest()->take(5)->get(),
            'chartData' => collect(range(6, 0))->map(function($i) use ($doctor) {
                $date = now()->subDays($i);
                return [
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->format('D'),
                    'count' => Appointment::where('doctor_id', $doctor->id)->whereDate('appointment_date', $date)->count()
                ];
            }),
            'servicesDistribution' => Appointment::where('doctor_id', $doctor->id)
                ->whereMonth('appointment_date', now()->month)
                ->whereYear('appointment_date', now()->year)
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->select('services.name', DB::raw('count(*) as count'))
                ->groupBy('services.name')
                ->get(),
            'urgentAppointments' => Appointment::where('doctor_id', $doctor->id)->where('status', 'pending')->whereDate('appointment_date', '<=', now()->addDays(1))->count(),
            'overdueAppointments' => Appointment::where('doctor_id', $doctor->id)->where('status', 'pending')->whereDate('appointment_date', '<', today())->count(),
        ];

        return view('doctor.dashboard', $data);
    }

    public function secretaryDashboard()
    {
        $data = [
            'todayAppointments' => Appointment::whereDate('appointment_date', today())->count(),
            'newPatients' => User::whereDate('created_at', today())->count(),
            'pendingAppointments' => Appointment::where('status', 'pending')->count(),
            'callsToday' => 0,
            'todaySchedule' => Appointment::whereDate('appointment_date', today())->with(['user', 'service', 'doctor.grade'])->orderBy('appointment_time')->get(),
            'recentCalls' => collect([]),
        ];

        return view('secretary.dashboard', $data);
    }

    public function patientDashboard()
    {
        $patient = Auth::user();
        $data = [
            'upcomingAppointments' => Appointment::where('user_id', $patient->id)->where('is_visited', false)->whereDate('appointment_date', '>=', today())->count(),
            'activePrescriptions' => 0,
            'pendingExams' => 0,
            'totalMedicalFiles' => 1,
            'upcomingAppointmentsList' => Appointment::where('user_id', $patient->id)->where('status', '!=', 'cancelled')->whereDate('appointment_date', '>=', today())->with(['service', 'doctor.grade'])->orderBy('appointment_date')->orderBy('appointment_time')->take(10)->get(),
            'recommendedArticles' => Article::latest()->take(3)->get(),
            'recentActivity' => $this->getPatientActivity($patient->id),
        ];

        return view('patient.dashboard', $data);
    }

    private function getRecentActivity()
    {
        return collect([
            (object) ['type' => 'user', 'description' => 'Nouveau patient inscrit', 'created_at' => now()->subMinutes(30)],
            (object) ['type' => 'service', 'description' => 'Service "Cardiologie" créé', 'created_at' => now()->subHours(2)],
            (object) ['type' => 'appointment', 'description' => 'Rendez-vous confirmé', 'created_at' => now()->subHours(4)],
        ]);
    }

    private function getPatientActivity($patientId)
    {
        return collect([
            (object) ['type' => 'appointment', 'description' => 'Rendez-vous confirmé', 'created_at' => now()->subHours(2)],
            (object) ['type' => 'prescription', 'description' => 'Nouvelle prescription reçue', 'created_at' => now()->subDays(1)],
            (object) ['type' => 'exam', 'description' => 'Résultats d\'examen disponibles', 'created_at' => now()->subDays(2)],
        ]);
    }

    public function adminStatistics()
    {
        $months = collect(range(0, 11))->map(fn($i) => now()->subMonths($i))->reverse();
        $activityLabels = $months->map(fn($date) => $date->format('M Y'))->values();
        $appointmentsData = $months->map(fn($date) => Appointment::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count())->values();
        $revenueData = $months->map(fn($date) => Appointment::where('status', 'confirmed')->whereMonth('appointments.created_at', $date->month)->whereYear('appointments.created_at', $date->year)->join('services', 'appointments.service_id', '=', 'services.id')->sum('services.price'))->values();

        $statusStats = Appointment::select('status', DB::raw('count(*) as count'))->groupBy('status')->pluck('count', 'status');
        $statusLabels = $statusStats->keys()->map(fn($s) => ucfirst($s));
        $statusData = $statusStats->values();

        $patients = User::role('Patient')->get();
        $ageGroups = ['0-18' => 0, '19-30' => 0, '31-50' => 0, '51-70' => 0, '70+' => 0];
        foreach ($patients as $patient) {
            $age = $patient->day_of_birth ? \Carbon\Carbon::parse($patient->day_of_birth)->age : null;
            if ($age !== null) {
                if ($age <= 18) $ageGroups['0-18']++;
                elseif ($age <= 30) $ageGroups['19-30']++;
                elseif ($age <= 50) $ageGroups['31-50']++;
                elseif ($age <= 70) $ageGroups['51-70']++;
                else $ageGroups['70+']++;
            }
        }

        $topServices = Appointment::join('services', 'appointments.service_id', '=', 'services.id')->select('services.name', DB::raw('count(*) as count'))->groupBy('services.name')->orderByDesc('count')->limit(5)->get();

        return view('admin.statistics', [
            'activityLabels' => $activityLabels,
            'appointmentsData' => $appointmentsData,
            'revenueData' => $revenueData,
            'statusLabels' => $statusLabels,
            'statusData' => $statusData,
            'demographicsLabels' => array_keys($ageGroups),
            'demographicsData' => array_values($ageGroups),
            'servicesLabels' => $topServices->pluck('name'),
            'servicesData' => $topServices->pluck('count'),
            'totalAppointments' => Appointment::count(),
            'totalRevenue' => Appointment::where('status', 'confirmed')->join('services', 'appointments.service_id', '=', 'services.id')->sum('services.price'),
            'totalPatients' => User::role('Patient')->count(),
            'totalDoctors' => User::role('Doctor')->count(),
        ]);
    }

    public function adminSettings() { return view('admin.settings'); }
    public function adminLogs() { return view('admin.logs'); }
    public function adminBackup() { return view('admin.backup'); }
    public function adminFaq() { return view('admin.faq'); }
    public function patientHealthSummary() { return view('patient.health-summary'); }
    public function patientVitalSigns() { return view('patient.vital-signs'); }
    public function patientMedicalFile()
    {
        $patient = Auth::user();
        return view('patient.medical-files.show', [
            'totalAppointments' => Appointment::where('user_id', $patient->id)->count(),
            'totalPrescriptions' => 0,
            'totalExams' => 0,
            'totalVaccines' => 0,
            'medicalHistory' => collect([]),
            'prescriptions' => collect([]),
            'exams' => collect([]),
            'vaccines' => collect([]),
            'allergies' => collect([]),
        ]);
    }
    public function patientAllergies() { return view('patient.allergies'); }
    public function patientMedications() { return view('patient.medications'); }
    public function patientVaccines() { return view('patient.vaccines'); }
    public function patientDoctors() { return view('patient.doctors'); }
    public function patientPreferences() { return view('patient.preferences'); }
}
