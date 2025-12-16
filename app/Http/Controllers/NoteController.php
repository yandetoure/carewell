<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class NoteController extends Controller
{
    /**
     * Display a listing of the notes.
     */
    public function index()
    {
        $note = Note::all();
        return response()->json(['data' => $note]);

    }

    /**
     * Display notes for doctor interface.
     */
    public function doctorNotes()
    {
        $doctor = Auth::user();
        
        // Récupérer les notes des patients du docteur
        // Les notes peuvent être créées par le docteur ou associées à des dossiers médicaux de ses patients
        $notes = Note::with([
            'doctor',
            'medicalFile.user'
        ])
            ->where(function($query) use ($doctor) {
                // Notes créées par ce docteur
                $query->where('doctor_id', $doctor->id)
                    // Ou notes des dossiers médicaux de ses patients
                    ->orWhereHas('medicalFile.user.appointments', function($q) use ($doctor) {
                        $q->where('doctor_id', $doctor->id);
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Ajouter des accesseurs pour compatibilité avec la vue
        $notes = $notes->map(function($note) {
            // Ajouter un accesseur patient (via medicalFile->user)
            if ($note->medicalFile && $note->medicalFile->user) {
                $note->patient = $note->medicalFile->user;
                $note->patient_id = $note->medicalFile->user_id;
            } else {
                // Si pas de medicalFile, créer un objet vide pour éviter les erreurs
                $note->patient = (object)['first_name' => 'N/A', 'last_name' => 'N/A', 'phone_number' => null];
                $note->patient_id = null;
            }
            
            // Ajouter consultation_type (peut être null)
            $note->consultation_type = null;
            
            // S'assurer que note->note existe (le contenu de la note)
            // La colonne dans la DB est 'content' mais le modèle peut avoir 'note' dans fillable
            if (!isset($note->note) || empty($note->note)) {
                $note->note = $note->content ?? 'Note non disponible';
            }
            
            return $note;
        });
        
        return view('doctor.notes', compact('notes'));
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
