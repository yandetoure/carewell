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
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AppointmentController extends \Illuminate\Routing\Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Temporairement, rediriger vers la vue patient
        // TODO: Implémenter la logique de rôles plus tard
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
        $doctors = User::whereHas('appointments', function($query) {
            $query->where('doctor_id', '!=', null);
        })->get();
        
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
        // Vérifier que le patient peut voir ce rendez-vous
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
        // Vérifier que le patient peut modifier ce rendez-vous
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        if ($appointment->status !== 'pending') {
            return redirect()->route('patient.appointments')->with('error', 'Ce rendez-vous ne peut plus être modifié');
        }

        $services = Service::all();
        $doctors = User::whereHas('appointments', function($query) {
            $query->where('doctor_id', '!=', null);
        })->get();

        return view('patient.appointments.edit', compact('appointment', 'services', 'doctors'));
    }

    /**
     * Update the specified appointment for patients.
     */
    public function patientUpdate(Request $request, Appointment $appointment)
    {
        // Vérifier que le patient peut modifier ce rendez-vous
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
        // Vérifier que le patient peut supprimer ce rendez-vous
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

        // Récupérer la date et l'heure actuelles
        $currentDateTime = now();

        // Définir la limite par page (par défaut 15)
        $limit = $request->input('limit', 15);

        // Récupérer les rendez-vous du docteur connecté avec pagination
        $appointments = Appointment::with(['user', 'service'])
            ->where('doctor_id', $doctor->id)
            ->orderByRaw('CASE WHEN appointment_date >= ? AND is_urgent = 1 THEN 0 ELSE 1 END', [$currentDateTime])
            ->orderBy('appointment_date', 'desc')
            ->paginate($limit);

        // Statistiques pour le docteur
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', now()->toDateString())
            ->count();

        $pendingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->count();

        $confirmedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'confirmed')
            ->count();

        $urgentAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('is_urgent', true)
            ->where('status', '!=', 'cancelled')
            ->count();

        // Rendez-vous d'aujourd'hui
        $todayAppointmentsList = Appointment::with(['user', 'service'])
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', now()->toDateString())
            ->orderBy('appointment_time')
            ->get();

        // Rendez-vous à venir (prochains 7 jours)
        $upcomingAppointments = Appointment::with(['user', 'service'])
            ->where('doctor_id', $doctor->id)
            ->where('appointment_date', '>', now()->toDateString())
            ->where('status', 'confirmed')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->limit(5)
            ->get();

        return view('doctor.appointments.index', compact(
            'appointments',
            'todayAppointments',
            'pendingAppointments',
            'confirmedAppointments',
            'urgentAppointments',
            'todayAppointmentsList',
            'upcomingAppointments'
        ));
    }

    /**
     * Mettre à jour le statut d'un rendez-vous
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $doctor = Auth::user();

        // Vérifier que le rendez-vous appartient au docteur connecté
        if ($appointment->doctor_id !== $doctor->id) {
    return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier ce rendez-vous.'
    ], 403);
}

        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $appointment->update([
            'status' => $request->status
        ]);

        // Créer une notification pour le patient
        Notification::create([
            'user_id' => $appointment->user_id,
            'title' => 'Statut du rendez-vous mis à jour',
            'message' => 'Votre rendez-vous du ' . \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') . ' a été marqué comme ' . ucfirst($request->status),
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut du rendez-vous mis à jour avec succès.',
            'appointment' => $appointment
        ]);
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
        
        $appointments = Appointment::with(['user', 'service'])
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
            'today'
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
        
        $appointments = Appointment::with(['user', 'service'])
            ->where('doctor_id', $doctor->id)
            ->whereBetween('appointment_date', [$startOfWeek, $endOfWeek])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();

        // Grouper par jour
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
            'endOfWeek'
        ));
    }

    
    

    public function patientAppointment()
    {
        $patient = Auth::user();
    
        if ($patient && $patient->hasRole('Patient')) {
            // Récupérer la date et l'heure actuelles
            $currentDateTime = now();
    
            // Récupérer les rendez-vous du Patient connecté
            $appointments = Appointment::with(['user', 'service'])
                ->where('user_id', $patient->id) 
                            ->orderByRaw('CASE WHEN appointment_date >= ? AND is_urgent = 1 THEN 0 ELSE 1 END', [$currentDateTime]) // Urgents en premier
            ->orderBy('appointment_date', 'asc') // Puis trier par date
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
        // Récupérer les services disponibles
        $services = Service::all();
        
        // Retourner la vue de création
        return view('appointments.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // {
    //     $user = Auth::user();

    //     try {
            // Validation des données
            // $request->validate([
                // 'service_id' => 'required|exists:services,id', // Service existant
                // 'reason' => 'nullable|string|max:255',
                // 'symptoms' => 'nullable|string',
                // 'date' => 'required|date',
            // ]);

            // Récupérer les disponibilités du médecin pour ce service à cette date
            // $availabilities = Availability::where('service_id', $request->service_id)
            //     ->where('available_date', $request->date)
            //     ->get();

            // Vérifier s'il y a des disponibilités
            // if ($availabilities->isEmpty()) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Aucune disponibilité trouvée pour ce médecin à cette date.',
            //     ], 404);
            // }

            // $slots = [];
            
            // Créer des créneaux horaires en fonction de la durée définie dans la disponibilité
            // foreach ($availabilities as $availability) {
            //     $startTime = \Carbon\Carbon::parse($availability->start_time);
            //     $endTime = \Carbon\Carbon::parse($availability->end_time);
            //     $duration = $availability->duration; // Durée du rendez-vous

            //     while ($startTime->addMinutes($duration)->lessThanOrEqualTo($endTime)) {
            //         $slots[] = $startTime->copy(); // Ajouter le créneau à la liste
            //     }
            // }

            // Filtrer les créneaux disponibles en vérifiant les rendez-vous existants
            // $availableSlots = [];
            // foreach ($slots as $slot) {
            //     $doctorId = $availability->doctor_id; // Récupérer l'ID du médecin

            //     $appointmentCount = Appointment::where('doctor_id', $doctorId)
            //         ->where('date', $request->date)
            //         ->where('time', $slot->format('H:i'))
            //         ->count();

                // Si le médecin a moins de 15 rendez-vous, ajouter le créneau à la liste
                // if ($appointmentCount < 15) {
                    // $availableSlots[] = $slot->format('H:i');
            //     }
            // }

            // // Vérifier s'il y a des créneaux disponibles
            // if (empty($availableSlots)) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Aucun créneau disponible pour ce médecin à cette date.',
            //     ], 422);
            // }

            // Choisir un créneau aléatoire parmi ceux disponibles
            // $selectedTime = $availableSlots[array_rand($availableSlots)];

            // Création du rendez-vous
    //         $appointment = Appointment::create([
    //             'user_id' => $user->id,
    //             'service_id' => $request->service_id,
    //             'doctor_id' => $availability->doctor_id, // Prendre le médecin de la disponibilité
    //             'reason' => $request->reason,
    //             'symptoms' => $request->symptoms,
    //             'is_visited' => false,
    //             'date' => $request->date,
    //             'time' => $request->time,
    //         ]);

    //         // Création du ticket associé
    //         $ticket = Ticket::create([
    //             'appointment_id' => $appointment->id,
    //             'doctor_id' => $availability->doctor_id,
    //             'is_paid' => false,
    //         ]);

    //         Mail::to($user->email)->send(new \App\Mail\Newappointment($user));

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Rendez-vous créé avec succès',
    //             'data' => [
    //                 'appointment' => $appointment,
    //                 'ticket' => $ticket,
    //             ],
    //         ], 201);
    //     } catch (ValidationException $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Erreur de validation',
    //             'errors' => $e->validator->errors(),
    //         ], 422);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Erreur lors de la création du rendez-vous',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }



    public function store(Request $request)
{
    // Récupérer l'utilisateur authentifié
    $user = Auth::user();
    
    try {
        // Validation des données
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'reason' => 'nullable|string|max:255',
            'symptoms' => 'nullable|string',
            'appointment_date' => 'required|date', 
            'appointment_time' => 'required|date_format:H:i',
            'patient_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        // Déterminer le patient (soit l'utilisateur connecté, soit le patient spécifié)
        $patientId = $request->patient_id ?? $user->id;
        $patient = User::findOrFail($patientId);

        // Si c'est un docteur qui crée le rendez-vous
        if ($user->hasRole('Doctor')) {
            $doctorId = $user->id;
        } else {
        // Vérifier si l'utilisateur a déjà un rendez-vous dans le même service dans les 48 dernières heures
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

        // Recherche des disponibilités des médecins pour ce service à cette date et heure
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
    
        // Choisir un médecin au hasard parmi ceux qui sont éligibles
        $selectedDoctor = $eligibleDoctors[array_rand($eligibleDoctors)];
            $doctorId = $selectedDoctor->id;
        }
        
        $service = Service::find($request->service_id);
        $servicePrice = $service->price;
        
        // Création du rendez-vous
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

        // Création du ticket associé
        $ticket = Ticket::create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $doctorId, 
            'user_id' => $patientId,
            'is_paid' => false, 
        ]);
        
        // Création de la notification pour le patient
                Notification::create([
            'user_id' => $patientId,
                    'title' => 'Rendez-vous confirmé',
            'message' => 'Votre rendez-vous a été programmé avec succès.',
                    'is_read' => false,
                ]);
        
        // Envoyer un email au patient
        Mail::to($patient->email)->send(new \App\Mail\Newappointment($patient));

        // Si c'est une requête AJAX, retourner JSON
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
        
        // Redirection selon le contexte
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
        // Récupérer les détails d'un rendez-vous
        $appointment = Appointment::with(['user', 'service', 'doctor'])->find($id);

        if (!$appointment) {
            return response()->json([
                'status' => false,
                'message' => 'Rendez-vous non trouvé',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $appointment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation des données
        $request->validate([
            'is_visited' => 'nullable|boolean',
            'service_id' => 'sometimes|required|exists:services,id', // Permet de modifier le service
            'appointment_date' => 'sometimes|required|date',
            'appointment_time' => 'sometimes|required|date_format:H:i',
        ]);
    
        // Rechercher le rendez-vous
        $appointment = Appointment::find($id);
    
        if (!$appointment) {
            return response()->json([
                'status' => false,
                'message' => 'Rendez-vous non trouvé',
            ], 404);
        }
    
        // Vérifier si la modification est possible (24h avant le rendez-vous)
        $appointmentDateTime = \Carbon\Carbon::parse($appointment->appointment_date . ' ' . $appointment->appointment_time);
        if ($appointmentDateTime->diffInHours(now()) < 24) {
            return response()->json([
                'status' => false,
                'message' => 'La modification n\'est pas possible moins de 24 heures avant le rendez-vous.',
            ], 403);
        }
    
        // Mise à jour des champs
        $dataToUpdate = $request->only(['is_visited']);
    
        // Si le service ou la date/heure sont fournis, on les met à jour
        if ($request->has('service_id')) {
            $dataToUpdate['service_id'] = $request->service_id;
        }
        
        if ($request->has('appointment_date')) {
            $dataToUpdate['appointment_date'] = $request->appointment_date;
        }
    
        if ($request->has('appointment_time')) {
            $dataToUpdate['appointment_time'] = $request->appointment_time;
        }
    
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
        // Supprimer un rendez-vous
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
                ->orderByRaw('CASE WHEN date >= ? AND is_urgent = 1 THEN 0 ELSE 1 END', [$currentDateTime]) // Urgents en premier
                ->orderBy('date', 'asc') 
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
        
        // Récupérer les patients qui ont eu ou ont des rendez-vous avec ce docteur
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
        
        // Statistiques
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
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'current_medications' => 'nullable|string',
        ]);

        $patient = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'emergency_contact' => $request->emergency_contact,
            'emergency_phone' => $request->emergency_phone,
            'medical_history' => $request->medical_history,
            'allergies' => $request->allergies,
            'current_medications' => $request->current_medications,
            'password' => Hash::make('password123'), // Mot de passe par défaut
            'email_verified_at' => now(),
        ]);

        // Assigner le rôle patient
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
        
        // Vérifier que le patient a eu des rendez-vous avec ce docteur
        $hasAppointments = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->exists();
            
        if (!$hasAppointments) {
            abort(403, 'Vous ne pouvez pas accéder aux informations de ce patient.');
        }

        // Récupérer les informations du patient
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
        
        // Vérifier que le patient a eu des rendez-vous avec ce docteur
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
        
        // Vérifier que le patient a eu des rendez-vous avec ce docteur
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
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'medical_history' => 'nullable|string',
            'allergies' => 'nullable|string',
            'current_medications' => 'nullable|string',
        ]);

        $patient->update($request->all());

        return redirect()->route('doctor.patients.show', $patient)
            ->with('success', 'Informations du patient mises à jour avec succès.');
    }

    /**
     * Afficher l'historique des rendez-vous d'un patient
     */
    public function patientHistory(User $patient)
    {
        $doctor = Auth::user();
        
        // Vérifier que le patient a eu des rendez-vous avec ce docteur
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
        
        // Vérifier que le patient a eu des rendez-vous avec ce docteur
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
            ->orderByRaw('CASE WHEN date >= ? AND is_urgent = 1 THEN 0 ELSE 1 END', [$currentDateTime])
            ->orderBy('date', 'desc')             
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
                'appointment_date' => $appointment->date,
                'appointment_time' => $appointment->time,
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
    
        // Vérification que l'utilisateur est un médecin
        if (!$doctor || !$doctor->hasRole('Doctor')) {
            return response()->json([
                'status' => false,
                'message' => 'Accès non autorisé',
            ], 403);
        }
    
        try {
            // Date du jour
            $today = Carbon::today();
            $currentMonth = $today->month;
            $currentYear = $today->year;
    
            // Statistiques des rendez-vous mensuels pour l'année en cours
            $monthlyAppointments = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthlyAppointments[] = Appointment::whereMonth('date', $month)
                    ->whereYear('date', $currentYear)
                    ->count();
            }
    
            // Statistiques des rendez-vous pour le mois courant
            $totalAppointmentsForMonth = Appointment::whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->count();
            $completedAppointmentsForMonth = Appointment::where('is_visited', true)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->count();
            $cancelledAppointmentsForMonth = Appointment::where('is_visited', false)
                ->where('date', '<', $today)
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->count();
    
            // Statistiques annuelles
            $totalAppointmentsForYear = Appointment::whereYear('date', $currentYear)->count();
            $completedAppointmentsForYear = Appointment::where('is_visited', true)
                ->whereYear('date', $currentYear)
                ->count();
            $cancelledAppointmentsForYear = Appointment::where('is_visited', false)
                ->where('date', '<', $today)
                ->whereYear('date', $currentYear)
                ->count();
    
            // Rendez-vous complétés pour aujourd'hui
            $appointmentsCompletedToday = Appointment::where('is_visited', true)
                                ->whereDate('date', Carbon::today())
                                ->count();

            // Rendez-vous annulés pour aujourd'hui (passés mais non complétés)
            $appointmentsCancelledToday = Appointment::where('is_visited', false)
                                ->whereDate('date', Carbon::today())
                                ->where('date', '<', Carbon::today())
                                ->count();
            // Réponse avec les statistiques
            return response()->json([
                'status' => true,
                'data' => [
                    'appointments_today' => Appointment::where('doctor_id', $doctor->id)
                        ->whereDate('date', $today)
                        ->count(),
                    'monthly_appointments' => $monthlyAppointments,
                    'total_appointments_for_month' => $totalAppointmentsForMonth,
                    'completed_appointments_for_month' => $completedAppointmentsForMonth,
                    'cancelled_appointments_for_month' => $cancelledAppointmentsForMonth,
                    'total_appointments_for_year' => $totalAppointmentsForYear,
                    'completed_appointments_for_year' => $completedAppointmentsForYear,
                    'cancelled_appointments_for_year' => $cancelledAppointmentsForYear,
                    'appointments_completed_today' => $appointmentsCompletedToday,
                    'appointments_cancelled_today' => $appointmentsCancelledToday, // Ajouter les rendez-vous annulés pour aujourd'hui
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la récupération des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    
    

public function getDoctorStatsForCurrentMonth(Request $request)
{
    try {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Récupération des rendez-vous pour le mois courant
        $dailyAppointments = [];
        for ($day = 1; $day <= 31; $day++) {
            $date = Carbon::createFromDate($currentYear, $currentMonth, $day);
            if ($date->isMonth($currentMonth)) {
                $dailyAppointments[] = Appointment::whereDate('date', $date)->count();
            } else {
                $dailyAppointments[] = 0; // Pas de rendez-vous pour les jours en dehors du mois
            }
        }

        $totalAppointments = Appointment::whereMonth('date', $currentMonth)
                                         ->whereYear('date', $currentYear)
                                         ->count();

        $completedAppointments = Appointment::where('status', 'completed')
                                             ->whereMonth('date', $currentMonth)
                                             ->whereYear('date', $currentYear)
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
        // Gérer les erreurs ici
        return response()->json([
            'status' => false,
            'message' => 'Erreur lors de la récupération des statistiques',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function userAppointmentsStats()
{
    $user = Auth::user();

    if ($user) {
        // Récupérer tous les rendez-vous de l'utilisateur
        $totalAppointments = Appointment::where('user_id', $user->id)->count();

        // Récupérer les rendez-vous à venir (date supérieure à aujourd'hui)
        $upcomingAppointments = Appointment::where('user_id', $user->id)
            ->where('date', '>', now())
            ->orderBy('date', 'asc') 
            ->count();

        // Récupérer les rendez-vous du jour
        $todayAppointments = Appointment::where('user_id', $user->id)
            ->whereDate('date', '=', now()->toDateString())
            ->count();

        return response()->json([
            'status' => true,
            'total_appointments' => $totalAppointments,
            'upcoming_appointments' => $upcomingAppointments,
            'today_appointments' => $todayAppointments,
        ]);
    }

    return response()->json([
        'status' => false,
        'message' => 'Utilisateur non authentifié',
    ], 403);
}

public function storeUrgent(Request $request)
{
    // Récupérer l'utilisateur authentifié
    $user = Auth::user();
    
    try {
        // Validation des données
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'reason' => 'nullable|string|max:255',
            'symptoms' => 'nullable|string',
            'date' => 'required|date', 
            'time' => 'required|date_format:H:i',
        ]);

        // Recherche des disponibilités pour les médecins acceptant des urgences pour ce service, date et heure
        $availableDoctors = Availability::where('service_id', $request->service_id)
            ->where('available_date', $request->date)
            ->where('start_time', '<=', $request->time)
            ->where('end_time', '>=', $request->time)
            // ->where('accepts_urgent', true) // Ajouter une condition pour les urgences
            ->get();
    
        $eligibleDoctors = [];
        foreach ($availableDoctors as $availability) {
            $doctor = User::find($availability->doctor_id);
    
            if ($doctor && $doctor->hasRole('Doctor')) {
                $appointmentCount = Appointment::where('doctor_id', $doctor->id)
                    ->where('date', $request->date)
                    ->count();
    
                // Limite ajustée pour rendez-vous urgents si nécessaire
                if ($appointmentCount < 20) { // Par exemple, 20 pour les urgences
                    $eligibleDoctors[] = $doctor;
                }
            }
        }
    
        if (empty($eligibleDoctors)) {
            return response()->json([
                'status' => false,
                'message' => 'Aucun médecin disponible pour un rendez-vous urgent à cette date et heure.',
            ], 422);
        }
    
        // Sélectionner un médecin disponible
        $selectedDoctor = $eligibleDoctors[array_rand($eligibleDoctors)];
        
        $service = Service::find($request->service_id);
        $servicePrice = $service->urgent_price ?? $service->price; // Tarif urgent si disponible

        // Création du rendez-vous urgent
        $appointment = Appointment::create([
            'user_id' => $user->id,
            'service_id' => $request->service_id,
            'doctor_id' => $selectedDoctor->id,
            'reason' => $request->reason,
            'symptoms' => $request->symptoms,
            'is_visited' => false,
            'is_urgent' => true,
            'date' => $request->date,
            'time' => $request->time,
            'price' => $servicePrice,
        ]);

        // Création du ticket associé
        $ticket = Ticket::create([
            'appointment_id' => $appointment->id,
            'doctor_id' => $selectedDoctor->id,
            'user_id' => $user->id,
            'is_paid' => false,
        ]);
                // Création de la notification
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Rendez-vous confirmé',
                    'message' => 'Votre rendez-vous avec le Dr. ' . $selectedDoctor->name . ' est confirmé pour le ' . $request->date . ' à ' . $request->time . '.',
                    'type' => 'appointment',
                    'is_read' => false,
                ]);
        // Envoi d'un email de confirmation
        Mail::to($user->email)->send(new \App\Mail\Newappointment($user));

        return response()->json([
            'status' => true,
            'message' => 'Rendez-vous d\'urgence créé avec succès',
            'data' => [
                'appointment' => $appointment,
                'ticket' => $ticket
            ],
        ], 201);
    } catch (ValidationException $e) {
        return response()->json([
            'status' => false,
            'message' => 'Erreur de validation',
            'errors' => $e->validator->errors(),
        ], 422);
    } catch (Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Erreur lors de la création du rendez-vous d\'urgence',
            'error' => $e->getMessage(),
        ], 500);
    }
}


public function updateAppointmentStatus(Request $request, $id)
{
    $appointment = Appointment::find($id);

    if (!$appointment) {
        return response()->json(['message' => 'Rendez-vous introuvable.'], 404);
    }

    $appointment->is_visited = $request->input('is_visited');
    $appointment->save();
        // Création de la notification
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Rendez-vous confirmé',
            'message' => 'Votre rendez-vous avec le Dr. ' . $selectedDoctor->name . ' est confirmé pour le ' . $request->date . ' à ' . $request->time . '.',
            'type' => 'appointment',
            'is_read' => false,
        ]);
    return response()->json(['message' => 'Statut mis à jour avec succès.', 'data' => $appointment], 200);
}

}
