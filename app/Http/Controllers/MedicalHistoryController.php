<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Afficher tous les antecedents
        $medicalhystory = MedicalHistory::all();
        return response()->json(['data' => $medicalhystory]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'content' => 'required|string|max:255|unique:exams',
            ]);
            
            // Création d'une nouvelle instance d'examen
            $medicalHistory = MedicalHistory::create([
                'content' => $request->content,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Antécédent créé avec succès',
                'data' => $prescription,
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
