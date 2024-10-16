<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Disease::all();
        return response()->json([
           'status' => true,
            'data' => $grades,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Ajout d'une maladie avec validation
        $request->validate([
            'name' => ['required','string','max:255'],
        ]);

        $grade = Disease::create($request->all());

        return response()->json([
           'status' => true,
           'message' => 'Maladie créée avec succès',
            'data' => $grade,
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //Afficher les details d'une maladie
        $grade = Disease::find($id);

        if (!$grade) {
            return response()->json([
               'status' => false,
               'message' => 'Maladie introuvable',
            ], 404);
        }

        return response()->json([
           'status' => true,
            'data' => $grade,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Modifier une maladie
        $request->validate([
            'name' => ['required','string','max:255'],
        ]);

        $grade = Disease::find($id);

        if (!$grade) {
            return response()->json([
               'status' => false,
               'message' => 'Maladie introuvable',
            ], 404);
        }

        $grade->update($request->all());

        return response()->json([
           'status' => true,
           'message' => 'Maladie modifiée avec succès',
            'data' => $grade,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Supprimer une maladie
        $grade = Disease::find($id);

        if (!$grade) {
            return response()->json([
               'status' => false,
               'message' => 'Maladie introuvable',
            ], 404);
        }

        $grade->delete();

        return response()->json([
           'status' => true,
           'message' => 'Maladie supprimée avec succès',
        ]);
    }
}
