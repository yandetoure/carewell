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
use App\Models\VitalSign;
use App\Models\Note;

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
            ->with(['medicalFile.user', 'prescription', 'medicalFile.beds' => function($query) {
                $query->where('status', 'occupe');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistics
        $totalHospitalized = $hospitalizedPatients->count();
        $pendingPrescriptions = $prescriptions->where('status', 'pending')->count();
        $completedPrescriptions = $prescriptions->where('status', 'administered')->count();

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

        // Get all beds with their details
        $beds = Bed::with(['medicalFile.user', 'service'])->get();
        
        // Get bed statistics
        $bedStats = [
            'total' => Bed::count(),
            'occupied' => Bed::where('status', 'occupe')->count(),
            'available' => Bed::where('status', 'libre')->count(),
            'maintenance' => Bed::where('status', 'maintenance')->count(),
            'admission_impossible' => Bed::where('status', 'admission_impossible')->count(),
        ];
        
        // Get hospitalized patients
        $hospitalizedPatients = User::whereHas('medicalFile.beds', function($query) {
            $query->where('status', 'occupe');
        })->with(['medicalFile.beds' => function($query) {
            $query->where('status', 'occupe');
        }])->get();

        // Get available beds for assignment
        $availableBeds = Bed::where('status', 'libre')->with('service')->get();

        // Get recent bed activities (last 24 hours)
        $recentActivities = Bed::where('updated_at', '>=', now()->subHours(24))
            ->with(['medicalFile.user', 'service'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return view('nurse.beds', compact(
            'beds',
            'bedStats',
            'hospitalizedPatients',
            'availableBeds',
            'recentActivities',
            'nurse'
        ));
    }

    /**
     * Display patient records
     */
    public function patientRecords(Request $request)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Get search parameters
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $dateRange = $request->get('dateRange', '');

        // Base query for medical records
        $recordsQuery = MedicalFile::with(['user', 'prescriptions', 'exams'])
            ->join('users', 'medical_files.user_id', '=', 'users.id');

        // Apply search filter
        if ($search) {
            $recordsQuery->where(function($query) use ($search) {
                $query->where('users.first_name', 'like', "%{$search}%")
                      ->orWhere('users.last_name', 'like', "%{$search}%")
                      ->orWhere('users.identification_number', 'like', "%{$search}%")
                      ->orWhere('medical_files.identification_number', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($status) {
            if ($status === 'hospitalized') {
                $recordsQuery->whereHas('beds', function($query) {
                    $query->where('status', 'occupe');
                });
            } elseif ($status === 'active') {
                $recordsQuery->whereDoesntHave('beds', function($query) {
                    $query->where('status', 'occupe');
                });
            }
        }

        // Apply date range filter
        if ($dateRange) {
            switch ($dateRange) {
                case 'today':
                    $recordsQuery->whereDate('medical_files.updated_at', today());
                    break;
                case 'week':
                    $recordsQuery->where('medical_files.updated_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $recordsQuery->where('medical_files.updated_at', '>=', now()->subMonth());
                    break;
            }
        }

        // Order by hospitalized patients first, then by update date
        $medicalRecords = $recordsQuery
            ->leftJoin('beds', function($join) {
                $join->on('medical_files.id', '=', 'beds.medical_file_id')
                     ->where('beds.status', 'occupe');
            })
            ->orderByRaw('CASE WHEN beds.id IS NOT NULL THEN 0 ELSE 1 END')
            ->orderBy('medical_files.updated_at', 'desc')
            ->select('medical_files.*')
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
            'nurse',
            'search',
            'status',
            'dateRange'
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
    public function prescriptions(Request $request)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Get search parameters
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $patientId = $request->get('patient', '');
        $date = $request->get('date', '');

        // Base query for prescriptions
        $prescriptionsQuery = MedicalFilePrescription::with(['medicalFile.user', 'prescription', 'doctor'])
            ->join('medical_files', 'medical_file_prescriptions.medical_files_id', '=', 'medical_files.id')
            ->join('users', 'medical_files.user_id', '=', 'users.id')
            ->leftJoin('beds', function($join) {
                $join->on('medical_files.id', '=', 'beds.medical_file_id')
                     ->where('beds.status', 'occupe');
            });

        // Apply filters
        if ($search) {
            $prescriptionsQuery->where(function($query) use ($search) {
                $query->where('users.first_name', 'like', "%{$search}%")
                      ->orWhere('users.last_name', 'like', "%{$search}%")
                      ->orWhere('prescriptions.name', 'like', "%{$search}%")
                      ->orWhere('medical_file_prescriptions.dosage', 'like', "%{$search}%");
            });
        }

        if ($status) {
            if ($status === 'pending') {
                $prescriptionsQuery->where('medical_file_prescriptions.is_done', '!=', true);
            } elseif ($status === 'completed') {
                $prescriptionsQuery->where('medical_file_prescriptions.is_done', true);
            }
        }

        if ($patientId) {
            $prescriptionsQuery->where('medical_files.user_id', $patientId);
        }

        if ($date) {
            $prescriptionsQuery->whereDate('medical_file_prescriptions.created_at', $date);
        }

        // Order by hospitalized patients first, then by creation date
        $prescriptions = $prescriptionsQuery
            ->orderByRaw('CASE WHEN beds.id IS NOT NULL THEN 0 ELSE 1 END')
            ->orderBy('medical_file_prescriptions.created_at', 'desc')
            ->select('medical_file_prescriptions.*')
            ->paginate(20);

        // Get pending prescriptions with hospitalized patients first
        $pendingPrescriptionsList = MedicalFilePrescription::with(['medicalFile.user', 'prescription', 'doctor'])
            ->join('medical_files', 'medical_file_prescriptions.medical_files_id', '=', 'medical_files.id')
            ->leftJoin('beds', function($join) {
                $join->on('medical_files.id', '=', 'beds.medical_file_id')
                     ->where('beds.status', 'occupe');
            })
            ->where('medical_file_prescriptions.is_done', '!=', true)
            ->orderByRaw('CASE WHEN beds.id IS NOT NULL THEN 0 ELSE 1 END')
            ->orderBy('medical_file_prescriptions.created_at', 'desc')
            ->select('medical_file_prescriptions.*')
            ->get();

        $totalPrescriptions = MedicalFilePrescription::count();
        $completedPrescriptions = MedicalFilePrescription::where('is_done', '!=', true)->count();
        $pendingPrescriptions = MedicalFilePrescription::where('is_done', '!=', true)->count();
        $todayPrescriptions = MedicalFilePrescription::whereDate('created_at', today())->count();

        $patients = User::whereHas('medicalFile')->get();

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
            'nurse',
            'search',
            'status',
            'patientId',
            'date'
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
        // Compter les patients qui ont un lit occupé (pas juste les lits occupés)
        return User::whereHas('medicalFile.beds', function($query) {
            $query->where('status', 'occupe');
        })->count();
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
        return MedicalFilePrescription::where('is_done', '!=', true)->count();
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

    /**
     * Discharge a patient from bed by bed ID
     */
    public function dischargePatientFromBed(Request $request, $bedId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $bed = Bed::findOrFail($bedId);
        
        if ($bed->status !== 'occupe') {
            return response()->json([
                'success' => false,
                'message' => 'Ce lit n\'est pas occupé'
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

    /**
     * Get patients without beds for assignment
     */
    public function getPatientsWithoutBeds()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        // Get patients who have medical files but no occupied beds
        $patients = User::whereHas('medicalFile')
            ->whereDoesntHave('medicalFile.beds', function($query) {
                $query->where('status', 'occupe');
            })
            ->select('id', 'first_name', 'last_name', 'email')
            ->get();

        return response()->json([
            'success' => true,
            'patients' => $patients
        ]);
    }

    /**
     * Mark prescription as complete
     */
    public function markPrescriptionAsComplete(Request $request, $prescriptionId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $prescription = MedicalFilePrescription::findOrFail($prescriptionId);

        $prescription->update([
            'is_done' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prescription marked as complete'
        ]);
    }

    /**
     * Get prescription details
     */
    public function getPrescriptionDetails($prescriptionId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $prescription = MedicalFilePrescription::with(['medicalFile.user', 'prescription', 'doctor'])
            ->findOrFail($prescriptionId);

        return response()->json([
            'success' => true,
            'prescription' => $prescription
        ]);
    }

    /**
     * Search prescriptions via AJAX
     */
    public function searchPrescriptions(Request $request)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $search = $request->get('q', '');
        $status = $request->get('status', '');
        $patientId = $request->get('patient', '');
        $date = $request->get('date', '');

        $prescriptionsQuery = MedicalFilePrescription::with(['medicalFile.user', 'prescription', 'doctor'])
            ->join('medical_files', 'medical_file_prescriptions.medical_files_id', '=', 'medical_files.id')
            ->join('users', 'medical_files.user_id', '=', 'users.id')
            ->leftJoin('beds', function($join) {
                $join->on('medical_files.id', '=', 'beds.medical_file_id')
                     ->where('beds.status', 'occupe');
            });

        if ($search) {
            $prescriptionsQuery->where(function($query) use ($search) {
                $query->where('users.first_name', 'like', "%{$search}%")
                      ->orWhere('users.last_name', 'like', "%{$search}%")
                      ->orWhere('prescriptions.name', 'like', "%{$search}%")
                      ->orWhere('medical_file_prescriptions.dosage', 'like', "%{$search}%");
            });
        }

        if ($status) {
            if ($status === 'pending') {
                $prescriptionsQuery->where('medical_file_prescriptions.is_done', '!=', true);
            } elseif ($status === 'completed') {
                $prescriptionsQuery->where('medical_file_prescriptions.is_done', true);
            }
        }

        if ($patientId) {
            $prescriptionsQuery->where('medical_files.user_id', $patientId);
        }

        if ($date) {
            $prescriptionsQuery->whereDate('medical_file_prescriptions.created_at', $date);
        }

        $prescriptions = $prescriptionsQuery
            ->orderByRaw('CASE WHEN beds.id IS NOT NULL THEN 0 ELSE 1 END')
            ->orderBy('medical_file_prescriptions.created_at', 'desc')
            ->select('medical_file_prescriptions.*')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'prescriptions' => $prescriptions
        ]);
    }

    /**
     * View patient record details
     */
    public function viewPatientRecord($recordId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        try {
            $record = MedicalFile::with([
                'user', 
                'prescriptions.prescription', 
                'exams.exam', 
                'note', 
                'vitalSigns',
                'medicalHistories',
                'medicaldisease.disease',
                'beds'
            ])->findOrFail($recordId);

            // Ajouter des statistiques
            $record->stats = [
                'total_prescriptions' => $record->prescriptions->count(),
                'pending_prescriptions' => $record->prescriptions->where('is_done', '!=', true)->count(),
                'completed_prescriptions' => $record->prescriptions->where('is_done', true)->count(),
                'total_exams' => $record->exams->count(),
                'total_notes' => $record->note->count(),
                'total_vital_signs' => $record->vitalSigns->count(),
                'total_diseases' => $record->medicaldisease->count(),
                'total_histories' => $record->medicalHistories->count(),
                'is_hospitalized' => $record->beds->where('status', 'occupe')->count() > 0,
                'current_bed' => $record->beds->where('status', 'occupe')->first()
            ];

            return response()->json([
                'success' => true,
                'record' => $record
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement du dossier: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Edit patient record
     */
    public function editPatientRecord($recordId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $record = MedicalFile::with(['user'])->findOrFail($recordId);

        return response()->json([
            'success' => true,
            'record' => $record
        ]);
    }

    /**
     * Add note to patient record
     */
    public function addPatientNote(Request $request, $recordId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'note' => 'required|string|max:1000'
        ]);

        $record = MedicalFile::findOrFail($recordId);

        $note = Note::create([
            'medical_files_id' => $record->id,
            'nurse_id' => $nurse->id,
            'note' => $request->note,
            'created_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note ajoutée avec succès',
            'note' => $note
        ]);
    }

    /**
     * Add vital signs to patient record
     */
    public function addVitalSigns(Request $request, $recordId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'blood_pressure_systolic' => 'required|numeric|min:50|max:300',
            'blood_pressure_diastolic' => 'required|numeric|min:30|max:200',
            'heart_rate' => 'required|numeric|min:30|max:200',
            'temperature' => 'required|numeric|min:30|max:45',
            'oxygen_saturation' => 'required|numeric|min:50|max:100',
            'respiratory_rate' => 'required|numeric|min:5|max:60',
            'weight' => 'nullable|numeric|min:10|max:500',
            'height' => 'nullable|numeric|min:50|max:250',
            'notes' => 'nullable|string|max:500'
        ]);

        $record = MedicalFile::findOrFail($recordId);

        $vitalSign = VitalSign::create([
            'medical_file_id' => $record->id,
            'nurse_id' => $nurse->id,
            'blood_pressure_systolic' => $request->blood_pressure_systolic,
            'blood_pressure_diastolic' => $request->blood_pressure_diastolic,
            'heart_rate' => $request->heart_rate,
            'temperature' => $request->temperature,
            'oxygen_saturation' => $request->oxygen_saturation,
            'respiratory_rate' => $request->respiratory_rate,
            'weight' => $request->weight,
            'height' => $request->height,
            'notes' => $request->notes,
            'recorded_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Signes vitaux enregistrés avec succès',
            'vitalSign' => $vitalSign
        ]);
    }

    /**
     * Get prescriptions for a specific patient
     */
    public function getPatientPrescriptions($patientId)
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

        $prescriptions = $patient->medicalFile->medicalprescription()->with('prescription')->get() ?? collect();

        return response()->json([
            'success' => true,
            'prescriptions' => $prescriptions
        ]);
    }

    /**
     * Get pending prescriptions for a specific patient
     */
    public function getPatientPendingPrescriptions($patientId)
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

        $prescriptions = $patient->medicalFile->medicalprescription()->with('prescription')->where('is_done', false)->get() ?? collect();

        return response()->json([
            'success' => true,
            'prescriptions' => $prescriptions
        ]);
    }

    /**
     * Mark a prescription as administered
     */
    public function markPrescriptionAsAdministered(Request $request, $prescriptionId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $prescription = MedicalFilePrescription::findOrFail($prescriptionId);

        $prescription->update([
            'is_done' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prescription marquée comme administrée'
        ]);
    }

    /**
     * Administer a prescription
     */
    public function administerPrescription(Request $request, $prescriptionId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $prescription = MedicalFilePrescription::findOrFail($prescriptionId);

        if ($prescription->is_done) {
            return response()->json([
                'success' => false,
                'message' => 'Cette prescription a déjà été administrée'
            ], 400);
        }

        $prescription->update([
            'is_done' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Médicament administré avec succès'
        ]);
    }

    /**
     * Mark a prescription as in progress
     */
    public function markPrescriptionAsInProgress(Request $request, $prescriptionId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $prescription = MedicalFilePrescription::findOrFail($prescriptionId);

        if ($prescription->status === 'administered') {
            return response()->json([
                'success' => false,
                'message' => 'Cette prescription a déjà été administrée'
            ], 400);
        }

        $prescription->markAsInProgress();

        return response()->json([
            'success' => true,
            'message' => 'Prescription marquée comme en cours'
        ]);
    }

    /**
     * Get vital signs for a specific patient
     */
    public function getVitalSigns($patientId)
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

        $vitalSigns = $patient->medicalFile->vitalSigns()
            ->with('nurse')
            ->orderBy('recorded_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'vitalSigns' => $vitalSigns,
            'patient' => $patient
        ]);
    }

    /**
     * Store new vital signs for a patient
     */
    public function storeVitalSigns(Request $request, $patientId)
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'blood_pressure_systolic' => 'nullable|numeric|min:50|max:300',
            'blood_pressure_diastolic' => 'nullable|numeric|min:30|max:200',
            'heart_rate' => 'nullable|integer|min:30|max:200',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'oxygen_saturation' => 'nullable|integer|min:70|max:100',
            'respiratory_rate' => 'nullable|integer|min:8|max:40',
            'weight' => 'nullable|numeric|min:1|max:500',
            'height' => 'nullable|numeric|min:30|max:250',
            'notes' => 'nullable|string|max:1000'
        ]);

        $patient = User::findOrFail($patientId);
        
        if (!$patient->medicalFile) {
            return response()->json([
                'success' => false,
                'message' => 'Le patient n\'a pas de dossier médical'
            ], 400);
        }

        $vitalSign = VitalSign::create([
            'medical_file_id' => $patient->medicalFile->id,
            'nurse_id' => $nurse->id,
            'blood_pressure_systolic' => $request->blood_pressure_systolic,
            'blood_pressure_diastolic' => $request->blood_pressure_diastolic,
            'heart_rate' => $request->heart_rate,
            'temperature' => $request->temperature,
            'oxygen_saturation' => $request->oxygen_saturation,
            'respiratory_rate' => $request->respiratory_rate,
            'weight' => $request->weight,
            'height' => $request->height,
            'notes' => $request->notes,
            'recorded_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Signes vitaux enregistrés avec succès',
            'vitalSign' => $vitalSign->load('nurse')
        ]);
    }

    /**
     * Get dashboard statistics for AJAX requests
     */
    public function getDashboardStats()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $stats = [
            'totalPatients' => $this->getTotalPatients(),
            'hospitalizedPatients' => $this->getHospitalizedPatients(),
            'todayAppointments' => $this->getTodayAppointments(),
            'pendingPrescriptions' => $this->getPendingPrescriptions(),
            'bedOccupancy' => $this->getBedOccupancy(),
            'serviceStats' => $this->getServiceStatistics(),
            'recentActivities' => $this->getRecentActivities(),
            'vitalSignsAlerts' => $this->getVitalSignsAlerts(),
            'urgentPrescriptions' => $this->getUrgentPrescriptions(),
            'lastUpdated' => now()->format('H:i:s')
        ];

        return response()->json($stats);
    }

    /**
     * Get vital signs alerts for patients with abnormal values
     */
    private function getVitalSignsAlerts()
    {
        $alerts = [];
        
        // Get recent vital signs with abnormal values
        $abnormalVitalSigns = VitalSign::with(['medicalFile.user', 'nurse'])
            ->where('recorded_at', '>=', now()->subHours(24))
            ->where(function($query) {
                $query->where('heart_rate', '>', 100)
                      ->orWhere('heart_rate', '<', 60)
                      ->orWhere('temperature', '>', 38.5)
                      ->orWhere('temperature', '<', 36.0)
                      ->orWhere('oxygen_saturation', '<', 95)
                      ->orWhere('blood_pressure_systolic', '>', 140)
                      ->orWhere('blood_pressure_systolic', '<', 90);
            })
            ->orderBy('recorded_at', 'desc')
            ->limit(10)
            ->get();

        foreach ($abnormalVitalSigns as $vitalSign) {
            $alerts[] = [
                'patient_name' => $vitalSign->medicalFile->user->first_name . ' ' . $vitalSign->medicalFile->user->last_name,
                'type' => $this->getAlertType($vitalSign),
                'value' => $this->getAlertValue($vitalSign),
                'recorded_at' => $vitalSign->recorded_at->format('H:i'),
                'nurse_name' => $vitalSign->nurse ? $vitalSign->nurse->first_name : 'Unknown'
            ];
        }

        return $alerts;
    }

    /**
     * Get urgent prescriptions that need immediate attention
     */
    private function getUrgentPrescriptions()
    {
        return MedicalFilePrescription::with(['medicalFile.user', 'prescription'])
            ->where('is_done', '!=', true)
            ->where('created_at', '<=', now()->subHours(2)) // Prescriptions older than 2 hours
            ->orderBy('created_at', 'asc')
            ->limit(5)
            ->get()
            ->map(function($prescription) {
                return [
                    'id' => $prescription->id,
                    'patient_name' => $prescription->medicalFile->user->first_name . ' ' . $prescription->medicalFile->user->last_name,
                    'medication' => $prescription->prescription->name ?? 'Unknown',
                    'dosage' => $prescription->dosage ?? 'N/A',
                    'created_at' => $prescription->created_at->format('H:i'),
                    'urgency_level' => $this->calculatePrescriptionUrgency($prescription)
                ];
            });
    }

    /**
     * Get alert type based on vital sign values
     */
    private function getAlertType($vitalSign)
    {
        if ($vitalSign->heart_rate > 100) return 'high_heart_rate';
        if ($vitalSign->heart_rate < 60) return 'low_heart_rate';
        if ($vitalSign->temperature > 38.5) return 'high_temperature';
        if ($vitalSign->temperature < 36.0) return 'low_temperature';
        if ($vitalSign->oxygen_saturation < 95) return 'low_oxygen';
        if ($vitalSign->blood_pressure_systolic > 140) return 'high_blood_pressure';
        if ($vitalSign->blood_pressure_systolic < 90) return 'low_blood_pressure';
        
        return 'general_alert';
    }

    /**
     * Get alert value for display
     */
    private function getAlertValue($vitalSign)
    {
        if ($vitalSign->heart_rate > 100 || $vitalSign->heart_rate < 60) {
            return $vitalSign->heart_rate . ' BPM';
        }
        if ($vitalSign->temperature > 38.5 || $vitalSign->temperature < 36.0) {
            return $vitalSign->temperature . '°C';
        }
        if ($vitalSign->oxygen_saturation < 95) {
            return $vitalSign->oxygen_saturation . '%';
        }
        if ($vitalSign->blood_pressure_systolic > 140 || $vitalSign->blood_pressure_systolic < 90) {
            return $vitalSign->blood_pressure_systolic . '/' . $vitalSign->blood_pressure_diastolic . ' mmHg';
        }
        
        return 'Abnormal reading';
    }

    /**
     * Calculate prescription urgency based on time and medication type
     */
    private function calculatePrescriptionUrgency($prescription)
    {
        $hoursSinceCreated = now()->diffInHours($prescription->created_at);
        
        if ($hoursSinceCreated >= 4) return 'critical';
        if ($hoursSinceCreated >= 2) return 'high';
        return 'normal';
    }

    /**
     * Get real-time notifications for the dashboard
     */
    public function getNotifications()
    {
        $nurse = Auth::user();

        if (!$nurse || !$nurse->hasRole('Nurse')) {
            abort(403, 'Unauthorized access');
        }

        $notifications = [];

        // Check for new appointments
        $newAppointments = Appointment::where('created_at', '>=', now()->subMinutes(15))
            ->whereDate('appointment_date', today())
            ->count();

        if ($newAppointments > 0) {
            $notifications[] = [
                'type' => 'new_appointment',
                'message' => "{$newAppointments} nouveau(x) rendez-vous ajouté(s)",
                'icon' => 'fas fa-calendar-plus',
                'color' => 'info'
            ];
        }

        // Check for urgent prescriptions
        $urgentPrescriptions = $this->getUrgentPrescriptions()->where('urgency_level', 'critical')->count();
        if ($urgentPrescriptions > 0) {
            $notifications[] = [
                'type' => 'urgent_prescription',
                'message' => "{$urgentPrescriptions} prescription(s) urgente(s) en attente",
                'icon' => 'fas fa-exclamation-triangle',
                'color' => 'danger'
            ];
        }

        // Check for vital signs alerts
        $vitalAlerts = $this->getVitalSignsAlerts();
        if (count($vitalAlerts) > 0) {
            $notifications[] = [
                'type' => 'vital_signs_alert',
                'message' => count($vitalAlerts) . " alerte(s) de signes vitaux",
                'icon' => 'fas fa-heartbeat',
                'color' => 'warning'
            ];
        }

        // Check for bed availability changes
        $recentBedChanges = Bed::where('updated_at', '>=', now()->subMinutes(30))
            ->whereIn('status', ['occupe', 'libre'])
            ->count();

        if ($recentBedChanges > 0) {
            $notifications[] = [
                'type' => 'bed_status_change',
                'message' => "Changements de statut de lit détectés",
                'icon' => 'fas fa-bed',
                'color' => 'primary'
            ];
        }

        return response()->json([
            'notifications' => $notifications,
            'total' => count($notifications),
            'timestamp' => now()->format('H:i:s')
        ]);
    }
}