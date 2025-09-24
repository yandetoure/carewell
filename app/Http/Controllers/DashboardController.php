<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\Article;

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
        $data = [
            'totalUsers' => User::count(),
            'totalAppointments' => Appointment::count(),
            'totalServices' => Service::count(),
            'totalArticles' => Article::count(),
            'activeUsers' => User::where('email_verified_at', '!=', null)->count(),
            'confirmedAppointments' => Appointment::where('status', 'confirmed')->count(),
            'pendingAppointments' => Appointment::where('status', 'pending')->count(),
            'recentActivity' => $this->getRecentActivity(),
        ];

        return view('admin.dashboard', $data);
    }

    public function doctorDashboard()
    {
        $doctor = Auth::user();
        
        $data = [
            'todayAppointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->count(),
            'totalPatients' => Appointment::where('doctor_id', $doctor->id)
                ->distinct('user_id')
                ->count(),
            'pendingAppointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'pending')
                ->count(),
            'totalPrescriptions' => 0, // À implémenter selon votre modèle
            'todayAppointmentsList' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->with(['user', 'service'])
                ->orderBy('appointment_time')
                ->get(),
            'recentPatients' => User::whereHas('appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })->latest()->take(5)->get(),
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
}
