<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Code pour lister les tickets (si nécessaire)
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'appointment_id' => 'nullable|exists:appointments,id', // ID de rendez-vous existant
                'prescription_id' => 'nullable|exists:prescriptions,id', // ID de prescription existant
                'exam_id' => 'nullable|exists:exams,id', // ID d'examen existant
            ]);

            // Création du ticket avec is_paid par défaut à false
            $ticket = Ticket::create([
                'appointment_id' => $request->appointment_id,
                'prescription_id' => $request->prescription_id,
                'exam_id' => $request->exam_id,
                'is_paid' => false, // Définir is_paid à false par défaut
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Ticket créé avec succès',
                'data' => $ticket,
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
        // Code pour afficher un ticket spécifique (si nécessaire)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Code pour mettre à jour un ticket spécifique (si nécessaire)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Code pour supprimer un ticket spécifique (si nécessaire)
    }
}
