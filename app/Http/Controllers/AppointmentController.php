<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Récupérer tous les rendez-vous
        $appointments = Appointment::with(['user', 'service'])->get();
        return response()->json([
            'status' => true,
            'data' => $appointments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'user_id' => 'required|exists:users,id', // Vérifie que l'utilisateur existe
                'service_id' => 'required|exists:services,id', // Vérifie que le service existe
                'reason' => 'required|string|max:255',
                'symptoms' => 'nullable|string',
                'date' => 'required|date', // Validation de la date
                'time' => 'required|date_format:H:i', // Format d'heure : HH:MM
            ]);

            // Création du rendez-vous
            $appointment = Appointment::create([
                'user_id' => $request->user_id,
                'service_id' => $request->service_id,
                'reason' => $request->reason,
                'symptoms' => $request->symptoms,
                'is_visited' => false, // Initialement, le patient n'a pas encore visité
                'date' => $request->date,
                'time' => $request->time,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Rendez-vous créé avec succès',
                'data' => $appointment,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->validator->errors(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Récupérer les détails d'un rendez-vous
        $appointment = Appointment::with(['user', 'service'])->find($id);

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
            'reason' => 'nullable|string|max:255',
            'symptoms' => 'nullable|string',
            'is_visited' => 'required|boolean', // Le patient a-t-il visité ou non ?
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
        ]);

        // Modifier le rendez-vous
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'status' => false,
                'message' => 'Rendez-vous non trouvé',
            ], 404);
        }

        // Mise à jour des champs
        $appointment->update($request->only(['reason', 'symptoms', 'is_visited', 'date', 'time']));

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
}
