<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NoteController extends Controller
{
    /**
     * Display a listing of the notes.
     */
    public function index()
    {
        // Récupération de toutes les notes
        $notes = Note::with('medicalFile')->get(); // Assurez-vous que la relation est définie dans le modèle Note
        return response()->json([
            'status' => true,
            'data' => $notes,
        ]);
    }

    /**
     * Store a newly created note in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'content' => 'required|string|max:1000',
                'medical_files_id' => 'required|exists:medical_files,id',
            ]);

            // Création d'une nouvelle note
            $note = Note::create([
                'content' => $request->content,
                'medical_files_id' => $request->medical_files_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Note ajoutée avec succès',
                'data' => $note,
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
     * Display the specified note.
     */
    public function show($id)
    {
        // Affichage d'une note spécifique
        $note = Note::with('medicalFile')->find($id);

        if (!$note) {
            return response()->json([
                'status' => false,
                'message' => 'Note non trouvée',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $note,
        ]);
    }

    /**
     * Update the specified note in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Validation des données
            $request->validate([
                'content' => 'required|string|max:1000',
                'medical_files_id' => 'required|exists:medical_files,id',
            ]);

            // Recherche de la note à modifier
            $note = Note::find($id);

            if (!$note) {
                return response()->json([
                    'status' => false,
                    'message' => 'Note non trouvée',
                ], 404);
            }

            // Mise à jour de la note
            $note->content = $request->content;
            $note->medical_files_id = $request->medical_files_id;
            $note->save();

            return response()->json([
                'status' => true,
                'message' => 'Note mise à jour avec succès',
                'data' => $note,
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
     * Remove the specified note from storage.
     */
    public function destroy($id)
    {
        // Recherche de la note à supprimer
        $note = Note::find($id);

        if (!$note) {
            return response()->json([
                'status' => false,
                'message' => 'Note non trouvée',
            ], 404);
        }

        // Suppression de la note
        $note->delete();

        return response()->json([
            'status' => true,
            'message' => 'Note supprimée avec succès',
        ]);
    }
}
