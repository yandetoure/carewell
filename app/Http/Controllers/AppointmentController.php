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
        $user = Auth::user();

        try {
            // Validation des données
            $request->validate([
                'service_id' => 'required|exists:services,id', // Service existant
                'reason' => 'nullable|string|max:255',
                'symptoms' => 'nullable|string',
                'date' => 'required|date',
            ]);

            // Récupérer les disponibilités du médecin pour ce service à cette date
            $availabilities = Availability::where('service_id', $request->service_id)
                ->where('available_date', $request->date)
                ->get();

            // Vérifier s'il y a des disponibilités
            if ($availabilities->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Aucune disponibilité trouvée pour ce médecin à cette date.',
                ], 404);
            }

            $slots = [];
            
            // Créer des créneaux horaires en fonction de la durée définie dans la disponibilité
            foreach ($availabilities as $availability) {
                $startTime = \Carbon\Carbon::parse($availability->start_time);
                $endTime = \Carbon\Carbon::parse($availability->end_time);
                $duration = $availability->duration; // Durée du rendez-vous

                while ($startTime->addMinutes($duration)->lessThanOrEqualTo($endTime)) {
                    $slots[] = $startTime->copy(); // Ajouter le créneau à la liste
                }
            }

            // Filtrer les créneaux disponibles en vérifiant les rendez-vous existants
            $availableSlots = [];
            foreach ($slots as $slot) {
                $doctorId = $availability->doctor_id; // Récupérer l'ID du médecin

                $appointmentCount = Appointment::where('doctor_id', $doctorId)
                    ->where('date', $request->date)
                    ->where('time', $slot->format('H:i'))
                    ->count();

                // Si le médecin a moins de 15 rendez-vous, ajouter le créneau à la liste
                if ($appointmentCount < 15) {
                    $availableSlots[] = $slot->format('H:i');
                }
            }

            // Vérifier s'il y a des créneaux disponibles
            if (empty($availableSlots)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Aucun créneau disponible pour ce médecin à cette date.',
                ], 422);
            }

            // Choisir un créneau aléatoire parmi ceux disponibles
            $selectedTime = $availableSlots[array_rand($availableSlots)];

            // Création du rendez-vous
            $appointment = Appointment::create([
                'user_id' => $user->id,
                'service_id' => $request->service_id,
                'doctor_id' => $availability->doctor_id, // Prendre le médecin de la disponibilité
                'reason' => $request->reason,
                'symptoms' => $request->symptoms,
                'is_visited' => false,
                'date' => $request->date,
                'time' => $selectedTime,
            ]);

            // Création du ticket associé
            $ticket = Ticket::create([
                'appointment_id' => $appointment->id,
                'doctor_id' => $availability->doctor_id,
                'is_paid' => false,
            ]);

            Mail::to($user->email)->send(new \App\Mail\Newappointment($user));

            return response()->json([
                'status' => true,
                'message' => 'Rendez-vous créé avec succès',
                'data' => [
                    'appointment' => $appointment,
                    'ticket' => $ticket,
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
                'message' => 'Aucun rendez-vous trouvé.',
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

        $completedAppointments = Appointment::where('status', 'completed') // Assurez-vous d'avoir une colonne 'status'
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
}
