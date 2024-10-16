<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ResultController extends Controller
{
    /**
     * Display a listing of the results.
     */
    public function index()
    {
        // Récupération de tous les résultats
        $results = Result::with('exam')->get(); // Assurez-vous que la relation 'exam' est définie dans le modèle Result
        return response()->json([
            'status' => true,
            'data' => $results,
        ]);
    }

    /**
     * Store a newly created result in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'name' => 'required|string|max:255',
                'exam_id' => 'required|exists:exams,id', // Assurez-vous que cela correspond à votre table
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optionnel et de taille maximale 2 Mo
            ]);

            // Création d'une nouvelle instance de résultat
            $result = Result::create([
                'name' => $request->name,
                'exam_id' => $request->exam_id,
                'image' => $request->image ? $request->file('image')->store('results', 'public') : null,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Résultat créé avec succès',
                'data' => $result,
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
     * Display the specified result.
     */
    public function show($id)
    {
        // Affichage d'un résultat spécifique
        $result = Result::with('exam')->find($id);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Résultat non trouvé',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $result,
        ]);
    }

    /**
     * Update the specified result in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validation des données
            $request->validate([
                'name' => 'required|string|max:255',
                'exam_id' => 'required|exists:exams,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Recherche du résultat à modifier
            $result = Result::find($id);

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'Résultat non trouvé',
                ], 404);
            }

            // Mise à jour du résultat
            $result->name = $request->name;
            $result->exam_id = $request->exam_id;

            // Si une nouvelle image est fournie, stockez-la
            if ($request->hasFile('image')) {
                // Supprimez l'ancienne image si nécessaire
              // Supprimez l'ancienne image si nécessaire
            if ($result->image) {
                Storage::disk('public')->delete($result->image);
            }

                $result->image = $request->file('image')->store('results', 'public');
            }

            $result->save();

            return response()->json([
                'status' => true,
                'message' => 'Résultat mis à jour avec succès',
                'data' => $result,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->validator->errors(),
            ], 422);
        }
    }

    /**
     * Remove the specified result from storage.
     */
    public function destroy($id)
    {
        // Recherche du résultat à supprimer
        $result = Result::find($id);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Résultat non trouvé',
            ], 404);
        }

        // Supprimez l'ancienne image si nécessaire
        if ($result->image) {
            Storage::disk('public')->delete($result->image);
        }


        // Suppression du résultat
        $result->delete();

        return response()->json([
            'status' => true,
            'message' => 'Résultat supprimé avec succès',
        ]);
    }
}
