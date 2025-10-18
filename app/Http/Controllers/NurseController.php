<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use App\Models\Bed;
use App\Models\MedicalFile;
use App\Models\MedicalFilePrescription;

class NurseController extends Controller
{
    /**
     * Display the nurse dashboard
     */
    public function dashboard()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Patient statistics
        $totalPatients = $this->getTotalPatients();
        $hospitalizedPatients = $this->getHospitalizedPatients();
        $todayAppointments = $this->getTodayAppointments();
        $pendingPrescriptions = $this->getPendingPrescriptions();

        // Service statistics
        $serviceStats = $this->getServiceStatistics();
        
        // Recent activities
        $recentActivities = $this->getRecentActivities();

        // Bed occupancy
        $bedOccupancy = $this->getBedOccupancy();

        return view('nurse.dashboard', compact(
            'nurse',
            'totalPatients',
            'hospitalizedPatients',
            'todayAppointments',
            'pendingPrescriptions',
            'serviceStats',
            'recentActivities',
            'bedOccupancy'
        ));
    }

    /**
     * Display patient management
     */
    public function patients()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Get all patients with their appointments and medical files
        $patients = User::with(['appointments.service', 'medicalFile.beds' => function($query) {
                $query->where('status', 'occupe');
            }])
            ->whereHas('appointments')
            ->orderBy('first_name')
            ->paginate(20);

        // Get occupied beds
        $occupiedBeds = Bed::where('status', 'occupe')->with('medicalFile.user')->get();
        
        // Get hospitalized patients - patients qui ont un lit occupé
        $hospitalizedPatients = User::whereHas('medicalFile.beds', function($query) {
                $query->where('status', 'occupe');
            })
            ->with(['medicalFile.beds' => function($query) {
                $query->where('status', 'occupe');
            }])
            ->get();

        // Get today's appointments
        $todayAppointments = Appointment::with(['user', 'service'])
            ->whereDate('appointment_date', today())
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('appointment_time')
            ->get();

        // Statistics
        $totalPatients = User::whereHas('appointments')->count();
        $hospitalizedCount = $hospitalizedPatients->count();
        $todayAppointmentsCount = $todayAppointments->count();
        $pendingPrescriptions = MedicalFilePrescription::where('is_done', false)->count();

        return view('nurse.patients', compact(
            'patients',
            'hospitalizedPatients',
            'todayAppointments',
            'totalPatients',
            'hospitalizedCount',
            'todayAppointmentsCount',
            'pendingPrescriptions',
            'occupiedBeds',
            'nurse'
        ));
    }

    /**
     * Display medication management
     */
    public function medications()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Get only hospitalized patients
        $hospitalizedPatients = User::whereHas('medicalFile.beds', function($query) {
                $query->where('status', 'occupe');
            })
            ->with(['medicalFile.beds' => function($query) {
                $query->where('status', 'occupe');
            }, 'medicalFile.medicalprescription'])
            ->orderBy('first_name')
            ->get();

        // Get prescriptions for hospitalized patients
        $prescriptions = MedicalFilePrescription::whereHas('medicalFile.beds', function($query) {
                $query->where('status', 'occupe');
            })
            ->with(['medicalFile.user', 'medicalFile.beds' => function($query) {
                $query->where('status', 'occupe');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistics
        $totalHospitalized = $hospitalizedPatients->count();
        $pendingPrescriptions = $prescriptions->where('is_done', false)->count();
        $completedPrescriptions = $prescriptions->where('is_done', true)->count();

        return view('nurse.medications', compact(
            'hospitalizedPatients',
            'prescriptions',
            'totalHospitalized',
            'pendingPrescriptions',
            'completedPrescriptions',
            'nurse'
        ));
    }

    /**
     * Display bed management
     */
    public function beds()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        return view('nurse.beds');
    }

    /**
     * Display patient records
     */
    public function patientRecords()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Get medical records with statistics
        $medicalRecords = MedicalFile::with(['user', 'prescriptions', 'exams'])
            ->paginate(20);

        $totalRecords = MedicalFile::count();
        $todayRecords = MedicalFile::whereDate('updated_at', today())->count();
        $pendingUpdates = MedicalFile::where('updated_at', '<', now()->subDays(7))->count();
        $recentRecords = MedicalFile::where('updated_at', '>=', now()->subDays(7))->count();

        return view('nurse.patient-records', compact(
            'medicalRecords',
            'totalRecords',
            'todayRecords',
            'pendingUpdates',
            'recentRecords',
            'nurse'
        ));
    }

    /**
     * Display vital signs monitoring
     */
    public function vitalSigns()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Mock data for vital signs (in real app, this would come from a vital_signs table)
        $todayReadings = 25;
        $normalReadings = 20;
        $abnormalReadings = 3;
        $pendingReadings = 2;

        $patients = User::whereHas('appointments')->get();
        
        // Mock recent readings data
        $recentReadings = collect([
            (object)[
                'patient_name' => 'John Doe',
                'patient_id' => 'P001',
                'blood_pressure' => '120/80',
                'heart_rate' => 72,
                'temperature' => 36.5,
                'oxygen_saturation' => 98,
                'status' => 'normal',
                'created_at' => now()
            ],
            (object)[
                'patient_name' => 'Jane Smith',
                'patient_id' => 'P002',
                'blood_pressure' => '140/90',
                'heart_rate' => 85,
                'temperature' => 37.2,
                'oxygen_saturation' => 95,
                'status' => 'abnormal',
                'created_at' => now()->subMinutes(30)
            ]
        ]);

        return view('nurse.vital-signs', compact(
            'todayReadings',
            'normalReadings',
            'abnormalReadings',
            'pendingReadings',
            'patients',
            'recentReadings',
            'nurse'
        ));
    }

    /**
     * Display appointments
     */
    public function appointments()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Get appointments with statistics
        $appointments = Appointment::with(['user', 'service', 'doctor'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(20);

        $todaySchedule = Appointment::with(['user', 'service', 'doctor'])
            ->whereDate('appointment_date', today())
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('appointment_time')
            ->get();

        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $confirmedAppointments = Appointment::where('status', 'confirmed')->count();
        $pendingAppointments = Appointment::where('status', 'pending')->count();
        $weekAppointments = Appointment::whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $services = Service::all();

        return view('nurse.appointments', compact(
            'appointments',
            'todaySchedule',
            'todayAppointments',
            'confirmedAppointments',
            'pendingAppointments',
            'weekAppointments',
            'services',
            'nurse'
        ));
    }

    /**
     * Display prescriptions
     */
    public function prescriptions()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Get prescriptions with statistics
        $prescriptions = MedicalFilePrescription::with(['medicalFile.user', 'prescription', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $pendingPrescriptionsList = MedicalFilePrescription::with(['medicalFile.user', 'prescription', 'doctor'])
            ->where('is_done', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalPrescriptions = MedicalFilePrescription::count();
        $completedPrescriptions = MedicalFilePrescription::where('is_done', true)->count();
        $pendingPrescriptions = MedicalFilePrescription::where('is_done', false)->count();
        $todayPrescriptions = MedicalFilePrescription::whereDate('created_at', today())->count();

        $patients = User::whereHas('appointments')->get();

        // Mock medication schedule data
        $morningMedications = 8;
        $afternoonMedications = 6;
        $eveningMedications = 4;
        $nightMedications = 2;

        return view('nurse.prescriptions', compact(
            'prescriptions',
            'pendingPrescriptionsList',
            'totalPrescriptions',
            'completedPrescriptions',
            'pendingPrescriptions',
            'todayPrescriptions',
            'patients',
            'morningMedications',
            'afternoonMedications',
            'eveningMedications',
            'nightMedications',
            'nurse'
        ));
    }

    /**
     * Display nurse profile
     */
    public function profile()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Statistics for profile
        $totalPatients = User::whereHas('appointments')->count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $prescriptionsGiven = MedicalFilePrescription::count();
        $experienceYears = 5; // This would be calculated from hire date
        $monthlyPatients = User::whereHas('appointments', function($query) {
            $query->whereMonth('appointment_date', now()->month);
        })->count();
        $weeklyPrescriptions = MedicalFilePrescription::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $todayVitalSigns = 15; // Mock data

        return view('nurse.profile', compact(
            'nurse',
            'totalPatients',
            'todayAppointments',
            'prescriptionsGiven',
            'experienceYears',
            'monthlyPatients',
            'weeklyPrescriptions',
            'todayVitalSigns'
        ));
    }

    /**
     * Display nurse settings
     */
    public function settings()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Default settings
        $settings = [
            'email_notifications' => true,
            'sms_notifications' => false,
            'appointment_reminders' => true,
            'urgent_notifications' => true,
            'medication_alerts' => true,
            'theme' => 'light',
            'language' => 'en',
            'timezone' => 'Africa/Dakar',
            'date_format' => 'd/m/Y',
            'two_factor_auth' => false,
            'session_timeout' => true,
            'session_duration' => 120,
            'login_notifications' => true,
            'working_hours_start' => '08:00',
            'working_hours_end' => '18:00',
            'appointment_duration' => 30,
            'weekend_appointments' => false,
            'emergency_notifications' => true,
        ];

        return view('nurse.settings', compact('nurse', 'settings'));
    }

    /**
     * Update nurse profile
     */
    public function updateProfile(Request $request)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $nurse->id,
            'phone_number' => 'required|string|max:20',
            'day_of_birth' => 'required|date',
            'adress' => 'required|string|max:255',
            'identification_number' => 'nullable|string|max:50',
            'photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string|max:1000',
        ]);

        $data = $request->only([
            'first_name', 'last_name', 'email', 'phone_number',
            'day_of_birth', 'adress', 'identification_number', 'bio'
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        $nurse->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update nurse password
     */
    public function updatePassword(Request $request)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $nurse->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $nurse->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    /**
     * Update nurse settings
     */
    public function updateSettings(Request $request)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // In a real application, you would save these settings to a settings table
        // For now, we'll just return a success message
        
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Get total patients
     */
    private function getTotalPatients()
    {
        return User::whereHas('appointments')->count();
    }

    /**
     * Get hospitalized patients
     */
    private function getHospitalizedPatients()
    {
        return Bed::where('status', 'occupe')->count();
    }

    /**
     * Get today's appointments
     */
    private function getTodayAppointments()
    {
        return Appointment::whereDate('appointment_date', today())
            ->whereIn('status', ['confirmed', 'pending'])
            ->count();
    }

    /**
     * Get pending prescriptions
     */
    private function getPendingPrescriptions()
    {
        return MedicalFilePrescription::where('is_done', false)->count();
    }

    /**
     * Get service statistics
     */
    private function getServiceStatistics()
    {
        return Service::withCount(['appointments' => function($query) {
            $query->whereDate('appointment_date', today());
        }])->get();
    }

    /**
     * Get recent activities
     */
    private function getRecentActivities()
    {
        return Appointment::with(['user', 'service'])
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time', 'asc')
            ->limit(10)
            ->get();
    }

    /**
     * Get bed occupancy
     */
    private function getBedOccupancy()
    {
        $totalBeds = Bed::count();
        $occupiedBeds = Bed::where('status', 'occupe')->count();
        $availableBeds = Bed::where('status', 'libre')->count();
        
        return [
            'total' => $totalBeds,
            'occupied' => $occupiedBeds,
            'available' => $availableBeds,
            'occupancy_rate' => $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100, 2) : 0
        ];
    }

    /**
     * Get patient details for modal
     */
    public function getPatientDetails($patientId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $patient = User::with(['medicalFile', 'appointments.service'])
            ->findOrFail($patientId);

        return response()->json([
            'success' => true,
            'patient' => $patient
        ]);
    }

    /**
     * Get medical file for modal
     */
    public function getMedicalFile($patientId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $patient = User::findOrFail($patientId);
        $medicalFile = $patient->medicalFile;

        if (!$medicalFile) {
            return response()->json([
                'success' => false,
                'message' => 'No medical file found for this patient'
            ]);
        }

        return response()->json([
            'success' => true,
            'medicalFile' => $medicalFile
        ]);
    }

    /**
     * Get vital signs for modal
     */
    public function getVitalSigns($patientId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Pour l'instant, retourner des données vides
        // Vous pouvez implémenter la logique pour récupérer les vrais signes vitaux
        return response()->json([
            'success' => true,
            'vitalSigns' => []
        ]);
    }

    /**
     * Get available beds for assignment
     */
    public function getAvailableBeds()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $beds = Bed::with('service')
            ->where('status', 'libre')
            ->get()
            ->map(function($bed) {
                return [
                    'id' => $bed->id,
                    'bed_number' => $bed->bed_number,
                    'room_number' => $bed->room_number,
                    'service_name' => $bed->service->name ?? 'N/A'
                ];
            });

        return response()->json([
            'success' => true,
            'beds' => $beds
        ]);
    }

    /**
     * Get appointment details for modal
     */
    public function getAppointmentDetails($appointmentId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $appointment = Appointment::with(['user', 'service', 'doctor'])
            ->findOrFail($appointmentId);

        $appointmentData = [
            'id' => $appointment->id,
            'appointment_date' => $appointment->appointment_date,
            'appointment_time' => $appointment->appointment_time,
            'status' => $appointment->status,
            'service_name' => $appointment->service->name ?? 'N/A',
            'doctor_name' => $appointment->doctor ? $appointment->doctor->first_name . ' ' . $appointment->doctor->last_name : 'N/A',
            'patient_name' => $appointment->user->first_name . ' ' . $appointment->user->last_name,
            'patient_phone' => $appointment->user->phone_number
        ];

        return response()->json([
            'success' => true,
            'appointment' => $appointmentData
        ]);
    }

    /**
     * Assign a bed to a patient
     */
    public function assignBedToPatient(Request $request, $patientId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'bed_id' => 'required|exists:beds,id',
            'admission_reason' => 'required|string|max:500',
            'expected_duration' => 'nullable|numeric|min:1'
        ]);

        $patient = User::findOrFail($patientId);
        
        if (!$patient->medicalFile) {
            return response()->json([
                'success' => false,
                'message' => 'Le patient n\'a pas de dossier médical'
            ], 400);
        }

        $bed = Bed::findOrFail($request->bed_id);
        
        if ($bed->status !== 'libre') {
            return response()->json([
                'success' => false,
                'message' => 'Ce lit n\'est pas disponible'
            ], 400);
        }

        // Assigner le lit au dossier médical
        $expectedDischargeDate = null;
        if ($request->expected_duration && is_numeric($request->expected_duration)) {
            $expectedDischargeDate = now()->addDays((int)$request->expected_duration);
        }
        
        $bed->update([
            'medical_file_id' => $patient->medicalFile->id,
            'status' => 'occupe',
            'admission_date' => now(),
            'expected_discharge_date' => $expectedDischargeDate,
            'notes' => $request->admission_reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lit assigné avec succès',
            'bed' => $bed
        ]);
    }

    /**
     * Discharge a patient from bed
     */
    public function dischargePatient(Request $request, $patientId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $patient = User::findOrFail($patientId);
        
        if (!$patient->medicalFile) {
            return response()->json([
                'success' => false,
                'message' => 'Le patient n\'a pas de dossier médical'
            ], 400);
        }

        $bed = Bed::where('medical_file_id', $patient->medicalFile->id)
                  ->where('status', 'occupe')
                  ->first();

        if (!$bed) {
            return response()->json([
                'success' => false,
                'message' => 'Le patient n\'est pas hospitalisé'
            ], 400);
        }

        // Libérer le lit
        $bed->update([
            'medical_file_id' => null,
            'status' => 'libre',
            'discharge_date' => now(),
            'notes' => $bed->notes . ' - Sorti le ' . now()->format('d/m/Y H:i')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Patient sorti avec succès'
        ]);
    }
}