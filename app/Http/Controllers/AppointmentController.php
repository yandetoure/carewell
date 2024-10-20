<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Appointment;
use App\Models\Availability;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer tous les rendez-vous du plus récent au plus ancien
        $appointments = Appointment::with(['user', 'service'])
            ->orderBy('created_at', 'desc') // Tri par date de création
            ->get();

        return response()->json([
            'status' => true,
            'data' => $appointments,
        ]);
    }

    public function doctorAppointment()
    {
        $doctor = Auth::user();
    
        // Vérifier si l'utilisateur est un docteur
        if ($doctor && $doctor->hasRole('Doctor')) {
            // Récupérer les rendez-vous du docteur connecté du plus récent au plus ancien
            $appointments = Appointment::with(['user', 'service'])
                ->where('doctor_id', $doctor->id) // Filtrer les rendez-vous par le docteur connecté
                ->orderBy('created_at', 'desc') // Tri par date de création
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

    public function patientAppointment()
    {
        $patient = Auth::user();

        // Vérifier si l'utilisateur est un docteur
        if ($patient && $patient->hasRole('Patient')) {
            // Récupérer les rendez-vous du Patient connecté
            $appointments = Appointment::with(['user', 'service'])
                ->where('user_id', $patient->id) 
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Récupérer l'utilisateur authentifié
        $user = Auth::user();
        
        try {
            // Validation des données
            $request->validate([
                'service_id' => 'required|exists:services,id', // Service existant
                'reason' => 'nullable|string|max:255',
                'symptoms' => 'nullable|string',
                'date' => 'required|date', 
                'time' => 'required|date_format:H:i',
            ]);

            // Recherche des disponibilités des médecins pour ce service à cette date et heure
            $availableDoctors = Availability::where('service_id', $request->service_id)
                ->where('available_date', $request->date)
                ->where('start_time', '<=', $request->time)
                ->where('end_time', '>=', $request->time)
                ->get();
        
            $eligibleDoctors = [];
            foreach ($availableDoctors as $availability) {
                $doctor = User::find($availability->doctor_id); // Récupérer le médecin par son ID
        
                if ($doctor && $doctor->hasRole('Doctor')) { // Vérifier si le médecin existe et a le rôle 'Doctor'
                    $appointmentCount = Appointment::where('doctor_id', $doctor->id)
                        ->where('date', $request->date)
                        ->count();
        
                    if ($appointmentCount < 15) {
                        $eligibleDoctors[] = $doctor; // Ajouter le médecin éligible
                    }
                }
            }
        
            // Vérifier s'il y a des médecins éligibles
            if (empty($eligibleDoctors)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tous les médecins ont atteint la limite de rendez-vous pour cette date.',
                ], 422);
            }
        
            // Choisir un médecin au hasard parmi ceux qui sont éligibles
            $selectedDoctor = $eligibleDoctors[array_rand($eligibleDoctors)];
        
            // Si un médecin est disponible, création du rendez-vous
            $appointment = Appointment::create([
                'user_id' => $request->user_id,
                'service_id' => $request->service_id,
                'doctor_id' => $selectedDoctor->id,
                'reason' => $request->reason,
                'symptoms' => $request->symptoms,
                'is_visited' => false,
                'date' => $request->date,
                'time' => $request->time,
            ]);
    
            // Création du ticket associé avec l'ID du docteur
            $ticket = Ticket::create([
                'appointment_id' => $appointment->id,
                'doctor_id' => $selectedDoctor->id, 
                'is_paid' => false, 
            ]);
            
            Mail::to($user->email)->send(new \App\Mail\Newappointment($user));

            return response()->json([
                'status' => true,
                'message' => 'Rendez-vous créé avec succès',
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
        ]);
    
        // Rechercher le rendez-vous
        $appointment = Appointment::find($id);
    
        if (!$appointment) {
            return response()->json([
                'status' => false,
                'message' => 'Rendez-vous non trouvé',
            ], 404);
        }
    
        // Mise à jour des champs
        $appointment->update($request->only(['is_visited', 'appointment_date', 'appointment_time']));
    
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
            $appointments = Appointment::with(['service', 'user', 'doctor'])
                ->where('user_id', $user->id) 
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

    public function getPatientsWithAppointmentsDoctor()
    {
        // Récupérer tous les rendez-vous avec les informations des patients et des services associés
        $appointments = Appointment::with(['user', 'service', 'doctor'])
            ->whereNotNull('user_id') // Assurer que l'utilisateur existe
            ->get();

        // Vérifier s'il y a des rendez-vous
        if ($appointments->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Aucun patient n\'a de rendez-vous',
            ], 404);
        }

        // Transformer les rendez-vous pour n'afficher que les informations pertinentes des patients
        $patients = $appointments->map(function ($appointment) {
            return [
                'patient_id' => $appointment->user->id,
                'patient_first_name' => $appointment->user->first_name,
                'patient_last_name' => $appointment->user->last_name,
                'service' => $appointment->service->name,
                'appointment_date' => $appointment->date,
                'appointment_time' => $appointment->time,
                'is_visited' => $appointment->is_visited,
                // 'doctor_first_name' => $appointment->docter_id->first_name,
                'patient_email'=>$appointment->user->email,
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $patients,
        ]);
    }

    public function getAppointmentByDoctor($id)
    {
        // Récupérer tous les rendez-vous associés à un médecin spécifique
        $appointments = Appointment::with(['user', 'service'])
            ->where('doctor_id', $id)
            ->get();

        // Vérifier s'il y a des rendez-vous
        if ($appointments->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Aucun rendez-vous trouvé pour ce médecin',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $appointments,
        ]);
    }
    public function doctorAppointmentStats()
    {
        $doctor = Auth::user();
    
        if ($doctor && $doctor->hasRole('Doctor')) {
            // Récupérer la date du jour
            $today = now()->format('Y-m-d');
    
            // Nombre de rendez-vous pour aujourd'hui
            $appointmentsToday = Appointment::where('doctor_id', $doctor->id)
                ->whereDate('date', $today)
                ->count();
    
            // Total des rendez-vous pour le docteur
            $totalAppointments = Appointment::where('doctor_id', $doctor->id)->count();
    
            // Nombre de rendez-vous effectués (is_visited = true)
            $completedAppointments = Appointment::where('doctor_id', $doctor->id)
                ->where('is_visited', true)
                ->count();
    
            return response()->json([
                'status' => true,
                'data' => [
                    'appointments_today' => $appointmentsToday,
                    'total_appointments' => $totalAppointments,
                    'completed_appointments' => $completedAppointments,
                ],
            ]);
        }
    
        return response()->json([
            'status' => false,
            'message' => 'Utilisateur non autorisé ou rôle incorrect',
        ], 403);
    }

}
