<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Availability;
use App\Models\Notification;
use App\Models\Bed;
use App\Mail\Newappointment;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AppointmentController extends \Illuminate\Routing\Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->patientIndex();
    }

    /**
     * Display a listing of appointments for patients.
     */
    public function patientIndex()
    {
        $patient = Auth::user();
        
        $appointments = Appointment::where('user_id', $patient->id)
            ->with(['service', 'doctor.grade'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        return view('patient.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new appointment for patients.
     */
    public function patientCreate()
    {
        $services = Service::all();
        $doctors = User::role('Doctor')->get();
        return view('patient.appointments.create', compact('services', 'doctors'));
    }

    /**
     * Store a newly created appointment for patients.
     */
    public function patientStore(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'doctor_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
            'urgency' => 'nullable|in:normal,urgent,very_urgent',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        $appointment = Appointment::create($validated);

        return redirect()->route('patient.appointments')->with('success', 'Rendez-vous créé avec succès !');
    }

    /**
     * Display the specified appointment for patients.
     */
    public function patientShow(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('patient.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment for patients.
     */
    public function patientEdit(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        if ($appointment->status !== 'pending') {
            return redirect()->route('patient.appointments')->with('error', 'Ce rendez-vous ne peut plus être modifié');
        }

        $services = Service::all();
        $doctors = User::role('Doctor')->get();

        return view('patient.appointments.edit', compact('appointment', 'services', 'doctors'));
    }

    /**
     * Update the specified appointment for patients.
     */
    public function patientUpdate(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        if ($appointment->status !== 'pending') {
            return redirect()->route('patient.appointments')->with('error', 'Ce rendez-vous ne peut plus être modifié');
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|date_format:H:i',
            'doctor_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
            'urgency' => 'nullable|in:normal,urgent,very_urgent',
        ]);

        $appointment->update($validated);

        return redirect()->route('patient.appointments')->with('success', 'Rendez-vous mis à jour avec succès !');
    }

    /**
     * Remove the specified appointment for patients.
     */
    public function patientDestroy(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        if ($appointment->status !== 'pending') {
            return redirect()->route('patient.appointments')->with('error', 'Ce rendez-vous ne peut plus être supprimé');
        }

        $appointment->delete();

        return redirect()->route('patient.appointments')->with('success', 'Rendez-vous supprimé avec succès !');
    }

    /**
     * Display a listing of appointments for doctors.
     */
    public function doctorIndex()
    {
        $doctor = Auth::user();
        
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->with(['user', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        return view('doctor.appointments.index', compact('appointments'));
    }


    public function doctorAppointments(Request $request)
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        $currentDateTime = now();
        $limit = $request->input('limit', 15);

        $appointments = Appointment::with(['user', 'service', 'doctor'])
            ->where('service_id', $doctor->service_id)
            ->orderByRaw('CASE WHEN appointment_date >= ? AND is_urgent = 1 THEN 0 ELSE 1 END', [$currentDateTime])
            ->orderBy('appointment_date', 'desc')
            ->paginate($limit);

        $todayAppointments = Appointment::where('service_id', $doctor->service_id)
            ->whereDate('appointment_date', now()->toDateString())
            ->count();

        $pendingAppointments = Appointment::where('service_id', $doctor->service_id)
            ->where('status', 'pending')
            ->count();

        $confirmedAppointments = Appointment::where('service_id', $doctor->service_id)
            ->where('status', 'confirmed')
            ->count();

        $urgentAppointments = Appointment::where('service_id', $doctor->service_id)
            ->where('is_urgent', true)
            ->where('status', '!=', 'cancelled')
            ->count();


        return view('doctor.appointments.index', compact(
            'appointments',
            'todayAppointments',
            'pendingAppointments',
            'confirmedAppointments',
            'urgentAppointments',
            'doctor'
        ));
    }

    /**
     * Mettre à jour le statut d'un rendez-vous
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $doctor = Auth::user();

        if ($appointment->service_id && $doctor->service_id && $appointment->service_id !== $doctor->service_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à modifier ce rendez-vous.'
                ], 403);
            }
            return redirect()->back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à modifier ce rendez-vous.']);
        }

        try {
            $request->validate([
                'status' => 'required|in:pending,confirmed,cancelled,completed'
            ]);

            $appointment->update([
                'status' => $request->status
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $e->validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->validator->errors());
        }

        Notification::create([
            'user_id' => $appointment->user_id,
            'title' => 'Statut du rendez-vous mis à jour',
            'message' => 'Votre rendez-vous du ' . \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') . ' a été marqué comme ' . ucfirst($request->status),
            'is_read' => false,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut du rendez-vous mis à jour avec succès.',
                'appointment' => $appointment
            ]);
        }
        
        return redirect()->back()->with('success', 'Statut du rendez-vous mis à jour avec succès.');
    }

    /**
     * Rendez-vous d'aujourd'hui pour le docteur
     */
    public function doctorTodayAppointments()
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        $today = now()->toDateString();
        
        $appointments = Appointment::with(['user', 'service', 'doctor'])
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_time')
            ->get();

        $completedCount = $appointments->where('status', 'completed')->count();
        $pendingCount = $appointments->where('status', 'pending')->count();
        $confirmedCount = $appointments->where('status', 'confirmed')->count();

        return view('doctor.appointments.today', compact(
            'appointments',
            'completedCount',
            'pendingCount',
            'confirmedCount',
            'today',
            'doctor' 
        ));
    }

    /**
     * Rendez-vous de la semaine pour le docteur
     */
    public function doctorWeekAppointments()
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        $appointments = Appointment::with(['user', 'service', 'doctor'])
            ->where('doctor_id', $doctor->id)
            ->whereBetween('appointment_date', [$startOfWeek, $endOfWeek])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        $appointmentsByDay = $appointments->groupBy(function($appointment) {
            return \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d');
        });

        $totalAppointments = $appointments->count();
        $completedCount = $appointments->where('status', 'completed')->count();
        $pendingCount = $appointments->where('status', 'pending')->count();
        $confirmedCount = $appointments->where('status', 'confirmed')->count();

        return view('doctor.appointments.week', compact(
            'appointments',
            'appointmentsByDay',
            'totalAppointments',
            'completedCount',
            'pendingCount',
            'confirmedCount',
            'startOfWeek',
            'endOfWeek',
            'doctor'
        ));
    }

    public function patientAppointment()
    {
        $patient = Auth::user();
    
        if ($patient && $patient->hasRole('Patient')) {
            $currentDateTime = now();
            $appointments = Appointment::with(['user', 'service'])
                ->where('user_id', $patient->id) 
                ->orderByRaw('CASE WHEN appointment_date >= ? AND is_urgent = 1 THEN 0 ELSE 1 END', [$currentDateTime])
                ->orderBy('appointment_date', 'asc')
                ->get();
    
            return response()->json([
                'status' => true,
                'data' => $appointments,
            ]);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'Utilisateur non autorisé ou rôle incorrect',
        ], 403);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::all();
        return view('appointments.create', compact('services'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        try {
            $request->validate([
                'service_id' => 'required|exists:services,id',
                'reason' => 'nullable|string|max:255',
                'symptoms' => 'nullable|string',
                'appointment_date' => 'required|date', 
                'appointment_time' => 'required|date_format:H:i',
                'patient_id' => 'nullable|exists:users,id',
                'notes' => 'nullable|string',
            ]);

            $patientId = $request->patient_id ?? $user->id;
            $patient = User::findOrFail($patientId);

            if ($user->hasRole('Doctor')) {
                $doctorId = $user->id;
            } else {
                $recentAppointment = Appointment::where('user_id', $patientId)
                    ->where('service_id', $request->service_id)
                    ->where('created_at', '>=', now()->subHours(48))
                    ->first();

                if ($recentAppointment) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Vous avez déjà pris un rendez-vous dans ce service au cours des 48 dernières heures.',
                        ], 422);
                    }
                    return redirect()->back()->withErrors(['error' => 'Vous avez déjà pris un rendez-vous dans ce service au cours des 48 dernières heures.']);
                }

                $availableDoctors = Availability::where('service_id', $request->service_id)
                    ->where('available_date', $request->appointment_date)
                    ->where('start_time', '<=', $request->appointment_time)
                    ->where('end_time', '>=', $request->appointment_time)
                    ->get();
            
                $eligibleDoctors = [];
                foreach ($availableDoctors as $availability) {
                    $doctor = User::find($availability->doctor_id);
            
                    if ($doctor && $doctor->hasRole('Doctor')) {
                        $appointmentCount = Appointment::where('doctor_id', $doctor->id)
                            ->where('appointment_date', $request->appointment_date)
                            ->count();
            
                        if ($appointmentCount < 15) {
                            $eligibleDoctors[] = $doctor;
                        }
                    }
                }
            
                if (empty($eligibleDoctors)) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Tous les médecins ont atteint la limite de rendez-vous pour cette date, choisissez une autre date.',
                        ], 422);
                    }
                    return redirect()->back()->withErrors(['error' => 'Tous les médecins ont atteint la limite de rendez-vous pour cette date, choisissez une autre date.']);
                }
            
                $selectedDoctor = $eligibleDoctors[array_rand($eligibleDoctors)];
                $doctorId = $selectedDoctor->id;
            }
            
            $service = Service::find($request->service_id);
            $servicePrice = $service->price;
            
            $appointment = Appointment::create([
                'user_id' => $patientId,
                'service_id' => $request->service_id,
                'doctor_id' => $doctorId,
                'reason' => $request->reason,
                'symptoms' => $request->symptoms,
                'is_visited' => false,
                'is_urgent' => false,
                'appointment_date' => $request->appointment_date,
                'appointment_time' => $request->appointment_time,
                'price' => $servicePrice, 
                'status' => $request->status ?? 'pending',
                'notes' => $request->notes,
            ]);

            $ticket = Ticket::create([
                'appointment_id' => $appointment->id,
                'doctor_id' => $doctorId, 
                'user_id' => $patientId,
                'is_paid' => false, 
            ]);
            
            Notification::create([
                'user_id' => $patientId,
                'title' => 'Rendez-vous confirmé',
                'message' => 'Votre rendez-vous a été programmé avec succès.',
                'is_read' => false,
            ]);
            
            Mail::to($patient->email)->send(new \App\Mail\Newappointment($patient));

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Rendez-vous créé avec succès',
                    'data' => [
                        'appointment' => $appointment,
                        'ticket' => $ticket
                    ],
                ], 201);
            }
            
            if ($user->hasRole('Doctor')) {
                return redirect()->route('doctor.patients.appointments', $patientId)
                    ->with('success', 'Rendez-vous créé avec succès pour ' . $patient->first_name . ' ' . $patient->last_name);
            } else {
                return redirect()->route('patient.appointments')
                    ->with('success', 'Votre rendez-vous a été créé avec succès');
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la création du rendez-vous',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $appointment = Appointment::with(['user', 'service', 'doctor'])->find($id);

        if (!$appointment) {
            if (request()->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Rendez-vous non trouvé',
                ], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Rendez-vous non trouvé.']);
        }

        if ($user->hasRole('Doctor')) {
            if ($appointment->service_id && $user->service_id && $appointment->service_id !== $user->service_id) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Vous n\'êtes pas autorisé à voir ce rendez-vous',
                    ], 403);
                }
                return redirect()->back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à voir ce rendez-vous.']);
            }
        }

        if ($user->hasRole('Patient') && $appointment->user_id !== $user->id) {
            if (request()->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Vous n\'êtes pas autorisé à voir ce rendez-vous',
                ], 403);
            }
            return redirect()->back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à voir ce rendez-vous.']);
        }

        if (request()->expectsJson() || request()->ajax() || request()->header('Accept') === 'application/json') {
            return response()->json([
                'status' => true,
                'data' => $appointment,
            ]);
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'is_visited' => 'nullable|boolean',
            'service_id' => 'sometimes|required|exists:services,id',
            'appointment_date' => 'sometimes|required|date',
            'appointment_time' => 'sometimes|required|date_format:H:i',
        ]);
    
        $appointment = Appointment::find($id);
    
        if (!$appointment) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Rendez-vous non trouvé',
                ], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Rendez-vous non trouvé.']);
        }
    
        $appointmentDateTime = \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
        if ($appointmentDateTime->diffInHours(now()) < 24) {
            return response()->json([
                'status' => false,
                'message' => 'La modification n\'est pas possible moins de 24 heures avant le rendez-vous.',
            ], 403);
        }
    
        $dataToUpdate = $request->only(['is_visited']);
    
        if ($request->has('service_id')) $dataToUpdate['service_id'] = $request->service_id;
        if ($request->has('appointment_date')) $dataToUpdate['appointment_date'] = $request->appointment_date;
        if ($request->has('appointment_time')) $dataToUpdate['appointment_time'] = $request->appointment_time;
    
        $appointment->update($dataToUpdate);
    
        return response()->json([
            'status' => true,
            'message' => 'Rendez-vous mis à jour avec succès',
            'data' => $appointment,
        ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'status' => false,
                'message' => 'Rendez-vous non trouvé',
            ], 404);
        }

        $appointment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Rendez-vous supprimé avec succès',
        ]);
    }

    public function userAppointments()
    {
        $user = Auth::user();
    
        if ($user) {
            $currentDateTime = now(); 
            $appointments = Appointment::with(['service', 'user', 'doctor'])
                ->where('user_id', $user->id) 
                ->orderByRaw('CASE WHEN appointment_date >= ? AND is_urgent = 1 THEN 0 ELSE 1 END', [$currentDateTime])
                ->orderBy('appointment_date', 'asc') 
                ->get();
    
            return response()->json([
                'status' => true,
                'data' => $appointments,
            ]);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'Utilisateur non authentifié',
        ], 401);
    }
    

    /**
     * Get patients with appointments for doctor interface
     */
    public function getPatientsWithAppointments()
    {
        $doctor = Auth::user();
        $patients = User::role('Patient')
            ->whereHas('appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->with(['appointments' => function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                      ->with(['service'])
                      ->orderBy('appointment_date', 'desc');
            }])
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(15);
        
        $totalPatients = $patients->total();
        $activePatients = User::role('Patient')
            ->whereHas('appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                      ->where('status', 'confirmed')
                      ->whereDate('appointment_date', '>=', now()->subMonths(3));
            })->count();
        
        $recentPatients = User::role('Patient')
            ->whereHas('appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id)
                      ->where('created_at', '>=', now()->subDays(30));
            })->count();
        
        return view('doctor.patients.index', compact('patients', 'totalPatients', 'activePatients', 'recentPatients'));
    }

    /**
     * Afficher le formulaire de création d'un nouveau patient
     */
    public function createPatient()
    {
        $services = Service::all();
        return view('doctor.patients.new', compact('services'));
    }

    /**
     * Enregistrer un nouveau patient
     */
    public function storePatient(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string|max:500',
        ]);

        $patient = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'day_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'adress' => $request->address,
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);

        $patient->assignRole('Patient');

        return redirect()->route('doctor.patients.show', $patient)
            ->with('success', 'Patient créé avec succès.');
    }

    /**
     * Afficher les détails d'un patient
     */
    public function showPatient(User $patient)
    {
        $doctor = Auth::user();
        $hasAppointments = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->exists();
            
        if (!$hasAppointments) {
            abort(403, 'Vous ne pouvez pas accéder aux informations de ce patient.');
        }

        $patient->load(['appointments' => function($query) use ($doctor) {
            $query->where('doctor_id', $doctor->id)
                  ->with(['service'])
                  ->orderBy('appointment_date', 'desc');
        }]);

        $lastAppointment = $patient->appointments->first();
        $nextAppointment = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->where('appointment_date', '>', now())
            ->where('status', 'confirmed')
            ->orderBy('appointment_date', 'asc')
            ->first();

        $medicalFile = $patient->medicalFile;
        $totalAppointments = $patient->appointments()->where('doctor_id', $doctor->id)->count();

        return view('doctor.patients.show', compact('patient', 'lastAppointment', 'nextAppointment', 'medicalFile', 'totalAppointments'));
    }

    /**
     * Afficher le formulaire d'édition d'un patient
     */
    public function editPatient(User $patient)
    {
        $doctor = Auth::user();
        $hasAppointments = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->exists();
            
        if (!$hasAppointments) {
            abort(403, 'Vous ne pouvez pas modifier les informations de ce patient.');
        }

        return view('doctor.patients.edit', compact('patient'));
    }

    /**
     * Mettre à jour les informations d'un patient
     */
    public function updatePatient(Request $request, User $patient)
    {
        $doctor = Auth::user();
        $hasAppointments = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->exists();
            
        if (!$hasAppointments) {
            abort(403, 'Vous ne pouvez pas modifier les informations de ce patient.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $patient->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'nullable|string|max:500',
        ]);

        $patient->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'day_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'adress' => $request->address,
        ]);

        return redirect()->route('doctor.patients.show', $patient)
            ->with('success', 'Informations du patient mises à jour avec succès.');
    }

    /**
     * Afficher l'historique des rendez-vous d'un patient
     */
    public function patientHistory(User $patient)
    {
        $doctor = Auth::user();
        $hasAppointments = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->exists();
            
        if (!$hasAppointments) {
            abort(403, 'Vous ne pouvez pas accéder à l\'historique de ce patient.');
        }

        $appointments = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->with(['service'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        $totalAppointments = $patient->appointments()->where('doctor_id', $doctor->id)->count();
        $confirmedAppointments = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->where('status', 'confirmed')
            ->count();
        $cancelledAppointments = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->where('status', 'cancelled')
            ->count();

        return view('doctor.patients.history', compact('patient', 'appointments', 'totalAppointments', 'confirmedAppointments', 'cancelledAppointments'));
    }

    /**
     * Afficher la gestion des rendez-vous d'un patient
     */
    public function patientAppointments(User $patient)
    {
        $doctor = Auth::user();
        $hasAppointments = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->exists();
            
        if (!$hasAppointments) {
            abort(403, 'Vous ne pouvez pas gérer les rendez-vous de ce patient.');
        }

        $appointments = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->with(['service'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);

        $services = Service::all();

        return view('doctor.patients.appointments', compact('patient', 'appointments', 'services'));
    }

    public function getPatientsWithAppointmentsDoctor()
    {
        $currentDateTime = now();
        $appointments = Appointment::with(['user', 'service', 'doctor'])
            ->whereNotNull('user_id') 
            ->orderByRaw('CASE WHEN appointment_date >= ? AND is_urgent = 1 THEN 0 ELSE 1 END', [$currentDateTime])
            ->orderBy('appointment_date', 'desc')             
            ->get();
    
        if ($appointments->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Aucun patient n\'a de rendez-vous',
            ], 404);
        }
    
        $patients = $appointments->map(function ($appointment) {
            return [
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->user->id,
                'patient_first_name' => $appointment->user->first_name,
                'patient_last_name' => $appointment->user->last_name,
                'service' => $appointment->service->name,
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,
                'is_visited' => $appointment->is_visited,
                'patient_email' => $appointment->user->email,
                'is_urgent' => $appointment->is_urgent,
            ];
        });
    
        return response()->json([
            'status' => true,
            'data' => $patients,
        ]);
    }    

    public function doctorAppointmentStats()
    {
        $doctor = Auth::user();
        if (!$doctor || !$doctor->hasRole('Doctor')) {
            return response()->json(['status' => false, 'message' => 'Accès non autorisé'], 403);
        }
    
        try {
            $today = Carbon::today();
            $currentMonth = $today->month;
            $currentYear = $today->year;
    
            $monthlyAppointments = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthlyAppointments[] = Appointment::whereMonth('appointment_date', $month)
                    ->whereYear('appointment_date', $currentYear)
                    ->count();
            }
    
            $totalAppointmentsForMonth = Appointment::whereMonth('appointment_date', $currentMonth)
                ->whereYear('appointment_date', $currentYear)
                ->count();
            $completedAppointmentsForMonth = Appointment::where('status', 'completed')
                ->whereMonth('appointment_date', $currentMonth)
                ->whereYear('appointment_date', $currentYear)
                ->count();
            $cancelledAppointmentsForMonth = Appointment::where('status', 'cancelled')
                ->whereMonth('appointment_date', $currentMonth)
                ->whereYear('appointment_date', $currentYear)
                ->count();
    
            $totalAppointmentsForYear = Appointment::whereYear('appointment_date', $currentYear)->count();
            $completedAppointmentsForYear = Appointment::where('status', 'completed')
                ->whereYear('appointment_date', $currentYear)
                ->count();
            $cancelledAppointmentsForYear = Appointment::where('status', 'cancelled')
                ->whereYear('appointment_date', $currentYear)
                ->count();
    
            $appointmentsCompletedToday = Appointment::where('status', 'completed')
                                ->whereDate('appointment_date', Carbon::today())
                                ->count();

            $appointmentsCancelledToday = Appointment::where('status', 'cancelled')
                                ->whereDate('appointment_date', Carbon::today())
                                ->count();
            return response()->json([
                'status' => true,
                'data' => [
                    'appointments_today' => Appointment::where('doctor_id', $doctor->id)
                        ->whereDate('appointment_date', $today)
                        ->count(),
                    'monthly_appointments' => $monthlyAppointments,
                    'total_appointments_for_month' => $totalAppointmentsForMonth,
                    'completed_appointments_for_month' => $completedAppointmentsForMonth,
                    'cancelled_appointments_for_month' => $cancelledAppointmentsForMonth,
                    'total_appointments_for_year' => $totalAppointmentsForYear,
                    'completed_appointments_for_year' => $completedAppointmentsForYear,
                    'cancelled_appointments_for_year' => $cancelledAppointmentsForYear,
                    'appointments_completed_today' => $appointmentsCompletedToday,
                    'appointments_cancelled_today' => $appointmentsCancelledToday,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Erreur lors de la récupération des statistiques', 'error' => $e->getMessage()], 500);
        }
    }
    
    
public function getDoctorStatsForCurrentMonth(Request $request)
{
    try {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $dailyAppointments = [];
        for ($day = 1; $day <= 31; $day++) {
            $date = Carbon::createFromDate($currentYear, $currentMonth, $day);
            if ($date->isMonth($currentMonth)) {
                $dailyAppointments[] = Appointment::whereDate('appointment_date', $date)->count();
            } else {
                $dailyAppointments[] = 0;
            }
        }

        $totalAppointments = Appointment::whereMonth('appointment_date', $currentMonth)
                                         ->whereYear('appointment_date', $currentYear)
                                         ->count();

        $completedAppointments = Appointment::where('status', 'completed')
                                             ->whereMonth('appointment_date', $currentMonth)
                                             ->whereYear('appointment_date', $currentYear)
                                             ->count();

        return response()->json([
            'status' => true,
            'data' => [
                'daily_appointments' => $dailyAppointments,
                'total_appointments' => $totalAppointments,
                'completed_appointments' => $completedAppointments,
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['status' => false, 'message' => 'Erreur lors de la récupération des statistiques', 'error' => $e->getMessage()], 500);
    }
}

public function userAppointmentsStats()
{
    $user = Auth::user();
    if ($user) {
        $totalAppointments = Appointment::where('user_id', $user->id)->count();
        $upcomingAppointments = Appointment::where('user_id', $user->id)
            ->where('appointment_date', '>', now())
            ->count();
        $todayAppointments = Appointment::where('user_id', $user->id)
            ->whereDate('appointment_date', '=', now()->toDateString())
            ->count();

        return response()->json([
            'status' => true,
            'total_appointments' => $totalAppointments,
            'upcoming_appointments' => $upcomingAppointments,
            'today_appointments' => $todayAppointments,
        ]);
    }
    return response()->json(['status' => false, 'message' => 'Utilisateur non authentifié'], 403);
}

public function storeUrgent(Request $request)
{
    $user = Auth::user();
    try {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'reason' => 'nullable|string|max:255',
            'symptoms' => 'nullable|string',
            'date' => 'required|date', 
            'time' => 'required|date_format:H:i',
        ]);

        $availableDoctors = Availability::where('service_id', $request->service_id)
            ->where('available_date', $request->date)
            ->where('start_time', '<=', $request->time)
            ->where('end_time', '>=', $request->time)
            ->get();
    
        $eligibleDoctors = [];
        foreach ($availableDoctors as $availability) {
            $doctor = User::find($availability->doctor_id);
            if ($doctor && $doctor->hasRole('Doctor')) {
                $appointmentCount = Appointment::where('doctor_id', $doctor->id)
                    ->where('appointment_date', $request->date)
                    ->count();
                if ($appointmentCount < 20) {
                    $eligibleDoctors[] = $doctor;
                }
            }
        }
    
        if (empty($eligibleDoctors)) {
            return response()->json(['status' => false, 'message' => 'Aucun médecin disponible pour un rendez-vous urgent à cette date et heure.'], 422);
        }
    
        $selectedDoctor = $eligibleDoctors[array_rand($eligibleDoctors)];
        $service = Service::find($request->service_id);
        $servicePrice = $service->urgent_price ?? $service->price;

        $appointment = Appointment::create([
            'user_id' => $user->id,
            'service_id' => $request->service_id,
            'doctor_id' => $selectedDoctor->id,
            'reason' => $request->reason,
            'symptoms' => $request->symptoms,
            'is_visited' => false,
            'is_urgent' => true,
            'appointment_date' => $request->date,
            'appointment_time' => $request->time,
            'price' => $servicePrice,
        ]);

        $ticket = Ticket::create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $selectedDoctor->id,
            'user_id' => $user->id,
            'is_paid' => false,
        ]);

        Notification::create([
            'user_id' => $user->id,
            'title' => 'Rendez-vous confirmé',
            'message' => 'Votre rendez-vous avec le Dr. ' . $selectedDoctor->name . ' est confirmé pour le ' . $request->date . ' à ' . $request->time . '.',
            'is_read' => false,
        ]);

        Mail::to($user->email)->send(new \App\Mail\Newappointment($user));

        return response()->json([
            'status' => true,
            'message' => 'Rendez-vous d\'urgence créé avec succès',
            'data' => ['appointment' => $appointment, 'ticket' => $ticket],
        ], 201);
    } catch (ValidationException $e) {
        return response()->json(['status' => false, 'message' => 'Erreur de validation', 'errors' => $e->validator->errors()], 422);
    } catch (Exception $e) {
        return response()->json(['status' => false, 'message' => 'Erreur lors de la création du rendez-vous d\'urgence', 'error' => $e->getMessage()], 500);
    }
}


public function updateAppointmentStatus(Request $request, $id)
{
    $appointment = Appointment::find($id);
    if (!$appointment) return response()->json(['message' => 'Rendez-vous introuvable.'], 404);
    $appointment->is_visited = $request->input('is_visited');
    $appointment->save();
    return response()->json(['message' => 'Statut mis à jour avec succès.', 'data' => $appointment], 200);
}

    public function doctorStatistics()
    {
        $doctor = Auth::user();
        if (!$doctor || !$doctor->hasRole('Doctor')) abort(403, 'Accès non autorisé');

        $totalAppointments = Appointment::where('doctor_id', $doctor->id)->count();
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', now()->toDateString())
            ->count();
        $pendingAppointments = Appointment::where('doctor_id', $doctor->id)->where('status', 'pending')->count();
        $confirmedAppointments = Appointment::where('doctor_id', $doctor->id)->where('status', 'confirmed')->count();
        $completedAppointments = Appointment::where('doctor_id', $doctor->id)->where('status', 'completed')->count();

        $currentMonth = now()->month;
        $currentYear = now()->year;
        $monthlyAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereMonth('appointment_date', $currentMonth)
            ->whereYear('appointment_date', $currentYear)
            ->count();

        $uniquePatients = Appointment::where('doctor_id', $doctor->id)->distinct('user_id')->count('user_id');
        $recentAppointments = Appointment::where('doctor_id', $doctor->id)->with(['user', 'service'])->orderBy('appointment_date', 'desc')->limit(5)->get();
        $todayAppointmentsList = Appointment::where('doctor_id', $doctor->id)->whereDate('appointment_date', now()->toDateString())->with(['user', 'service'])->orderBy('appointment_time')->get();

        return view('doctor.statistics', compact('totalAppointments', 'todayAppointments', 'pendingAppointments', 'confirmedAppointments', 'completedAppointments', 'monthlyAppointments', 'uniquePatients', 'recentAppointments', 'todayAppointmentsList'));
    }

    public function doctorConsultations()
    {
        $doctor = Auth::user();
        if (!$doctor || !$doctor->hasRole('Doctor')) abort(403, 'Accès non autorisé');

        $consultations = Appointment::with(['user', 'service', 'doctor'])
            ->where('service_id', $doctor->service_id)
            ->whereIn('status', ['completed', 'confirmed'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(20);

        $totalConsultations = Appointment::where('service_id', $doctor->service_id)->whereIn('status', ['completed', 'confirmed'])->count();
        $completedConsultations = Appointment::where('service_id', $doctor->service_id)->where('status', 'completed')->count();
        $confirmedConsultations = Appointment::where('service_id', $doctor->service_id)->where('status', 'confirmed')->count();
        $recentConsultations = Appointment::where('service_id', $doctor->service_id)->whereIn('status', ['completed', 'confirmed'])->where('appointment_date', '>=', now()->subDays(7))->count();

        return view('doctor.consultations', compact('consultations', 'totalConsultations', 'completedConsultations', 'confirmedConsultations', 'recentConsultations', 'doctor'));
    }

    public function doctorFollowUp()
    {
        $doctor = Auth::user();
        if (!$doctor || !$doctor->hasRole('Doctor')) abort(403, 'Accès non autorisé');

        $hospitalizedPatients = \App\Models\Bed::with(['medicalFile.user', 'service', 'currentAdmission'])
            ->where('service_id', $doctor->service_id)
            ->where('status', 'occupe')
            ->orderBy('admission_date', 'desc')
            ->paginate(15);

        $totalHospitalized = \App\Models\Bed::where('service_id', $doctor->service_id)->where('status', 'occupe')->count();
        $newAdmissions = \App\Models\Bed::where('service_id', $doctor->service_id)->where('status', 'occupe')->where('admission_date', '>=', now()->subDays(7))->count();
        $longStayPatients = \App\Models\Bed::where('service_id', $doctor->service_id)->where('status', 'occupe')->where('admission_date', '<=', now()->subDays(30))->count();
        $expectedDischarges = \App\Models\Bed::where('service_id', $doctor->service_id)->where('status', 'occupe')->where('expected_discharge_date', '<=', now()->addDays(3))->where('expected_discharge_date', '>=', now())->count();

        return view('doctor.follow-up', compact('hospitalizedPatients', 'totalHospitalized', 'newAdmissions', 'longStayPatients', 'expectedDischarges', 'doctor'));
    }

    public function doctorStats()
    {
        $doctor = Auth::user();
        if (!$doctor || !$doctor->hasRole('Doctor')) abort(403, 'Accès non autorisé');
        return redirect()->route('doctor.statistics')->with('success', 'Statistiques mises à jour avec succès.');
    }

    public function secretaryAppointments(Request $request)
    {
        $secretary = Auth::user();
        if (!$secretary || !$secretary->hasRole('Secretary')) abort(403, 'Accès non autorisé');

        $currentDateTime = now();
        $limit = $request->input('limit', 15);

        $appointments = Appointment::with(['user', 'service', 'doctor'])
            ->where('service_id', $secretary->service_id)
            ->orderByRaw('CASE WHEN appointment_date >= ? AND is_urgent = 1 THEN 0 ELSE 1 END', [$currentDateTime])
            ->orderBy('appointment_date', 'desc')
            ->paginate($limit);

        $todayAppointments = Appointment::where('service_id', $secretary->service_id)->whereDate('appointment_date', now()->toDateString())->count();
        $pendingAppointments = Appointment::where('service_id', $secretary->service_id)->where('status', 'pending')->count();
        $confirmedAppointments = Appointment::where('service_id', $secretary->service_id)->where('status', 'confirmed')->count();
        $urgentAppointments = Appointment::where('service_id', $secretary->service_id)->where('is_urgent', true)->where('status', '!=', 'cancelled')->count();

        return view('secretary.appointments.index', compact('appointments', 'todayAppointments', 'pendingAppointments', 'confirmedAppointments', 'urgentAppointments', 'secretary'));
    }

    public function secretaryTodayAppointments()
    {
        $secretary = Auth::user();
        if (!$secretary || !$secretary->hasRole('Secretary')) abort(403, 'Accès non autorisé');

        $today = now()->toDateString();
        $appointments = Appointment::with(['user', 'service', 'doctor'])->where('service_id', $secretary->service_id)->whereDate('appointment_date', $today)->orderBy('appointment_time')->get();
        $completedCount = $appointments->where('status', 'completed')->count();
        $pendingCount = $appointments->where('status', 'pending')->count();
        $confirmedCount = $appointments->where('status', 'confirmed')->count();

        return view('secretary.appointments.today', compact('appointments', 'completedCount', 'pendingCount', 'confirmedCount', 'today', 'secretary'));
    }

    public function secretaryWeekAppointments()
    {
        $secretary = Auth::user();
        if (!$secretary || !$secretary->hasRole('Secretary')) abort(403, 'Accès non autorisé');

        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $appointments = Appointment::with(['user', 'service', 'doctor'])->where('service_id', $secretary->service_id)->whereBetween('appointment_date', [$startOfWeek, $endOfWeek])->orderBy('appointment_date')->orderBy('appointment_time')->get();
        $appointmentsByDay = $appointments->groupBy(function($appointment) { return \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d'); });

        $totalAppointments = $appointments->count();
        $completedCount = $appointments->where('status', 'completed')->count();
        $pendingCount = $appointments->where('status', 'pending')->count();
        $confirmedCount = $appointments->where('status', 'confirmed')->count();

        return view('secretary.appointments.week', compact('appointments', 'appointmentsByDay', 'totalAppointments', 'completedCount', 'pendingCount', 'confirmedCount', 'startOfWeek', 'endOfWeek', 'secretary'));
    }

    public function secretaryCreateAppointment()
    {
        $secretary = Auth::user();
        if (!$secretary || !$secretary->hasRole('Secretary')) abort(403, 'Accès non autorisé');
        $service = Service::find($secretary->service_id);
        $doctors = User::role('Doctor')->where('service_id', $secretary->service_id)->get();
        $patients = User::role('Patient')->get();
        return view('secretary.appointments.create', compact('service', 'doctors', 'patients'));
    }

    public function secretarySchedule()
    {
        $secretary = Auth::user();
        if (!$secretary || !$secretary->hasRole('Secretary')) abort(403, 'Accès non autorisé');
        $startDate = now();
        $endDate = now()->addDays(30);
        $appointments = Appointment::with(['user', 'service', 'doctor'])->where('service_id', $secretary->service_id)->whereBetween('appointment_date', [$startDate, $endDate])->orderBy('appointment_date')->orderBy('appointment_time')->get();
        return view('secretary.schedule', compact('appointments', 'secretary'));
    }

    public function secretaryDoctorsSchedule()
    {
        $secretary = Auth::user();
        if (!$secretary || !$secretary->hasRole('Secretary')) abort(403, 'Accès non autorisé');
        $doctors = User::whereHas('appointments', function($query) use ($secretary) { $query->where('service_id', $secretary->service_id); })->get();
        $availabilities = \App\Models\Availability::with('doctor')->where('service_id', $secretary->service_id)->where('available_date', '>=', now())->orderBy('available_date')->orderBy('start_time')->get();
        return view('secretary.doctors.schedule', compact('doctors', 'availabilities', 'secretary'));
    }
}
