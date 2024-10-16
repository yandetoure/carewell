<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Afficher tous les grades
        $grades = Grade::all();
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
        // Validation des données
        $request->validate([
            'name' =>'required|string|max:255',
        ]);

        // Création d'un nouveau grade
        $grade = Grade::create([
            'name' => $request->name,
        ]);

        return response()->json([
           'status' => true,
           'message' => 'Grade créée avec succès',
            'data' => $grade,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Afficher les détails d'un grade
        $grade = Grade::find($id);
        
        if (!$grade) {
            return response()->json([
               'status' => false,
               'message' => 'Grade non trouvée',
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
        // Validation des données
        $request->validate([
            'name' =>'required|string|max:255',
        ]);

        // Modifier un grade
        $grade = Grade::find($id);
        
        if (!$grade) {
            return response()->json([
               'status' => false,
               'message' => 'Grade non trouvée',
            ], 404);
        }

        $grade->name = $request->name;
        $grade->save();

        return response()->json([
           'status' => true,
           'message' => 'Le grade a été modifié avec succès',
            'data' => $grade,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Supprimer un grade
        $grade = Grade::find($id);
        
        if (!$grade) {
            return response()->json([
               'status' => false,
               'message' => 'Grade non trouvée',
            ], 404);
        }

        $grade->delete();

        return response()->json([
           'status' => true,
           'message' => 'Le grade a été supprimé avec succès',
        ]);
    }
}
