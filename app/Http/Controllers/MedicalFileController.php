<?php
namespace App\Http\Controllers;

use App\Model;
use App\Models\Service;
use App\Models\MedicalFile;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Models\medicalHystory;
use Illuminate\Support\Facades\Auth; 
use App\Models\medicalfilePrescription;

class MedicalFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicalFiles = MedicalFile::with(['note', 'medicalHistory', 'medicalprescription', 'user'  ])->get();
        return response()->json(['data' => $medicalFiles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $medicalFile = MedicalFile::create($request->all());
        return response()->json(['message' => 'Dossier médical créé avec succès', 'data' => $medicalFile]);
    }

    public function medicalHystory(Reqquest $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $medicalFile = MedicalFile::with(['note', 'medicalHistory', 'medicalprescription', 'user'])->find($id);
    
        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }
    
        return response()->json(['data' => $medicalFile]);
    }
    

    
    public function showAuthMedicalFile()
    {
        $user = Auth::user();
        $medicalFile = MedicalFile::with(['note', 'medicalHistory', 'medicalprescription', 'user'])
            ->where('user_id', $user->id) 
            ->first();

        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }

        return response()->json(['data' => $medicalFile]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $medicalFile = MedicalFile::find($id);

        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }

        $medicalFile->update($request->all());

        return response()->json(['message' => 'Dossier médical mis à jour avec succès', 'data' => $medicalFile]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $medicalFile = MedicalFile::find($id);

        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }

        $medicalFile->delete();

        return response()->json(['message' => 'Dossier médical supprimé avec succès']);
    }




    public function addNote(Request $request, string $id)
    {
        $medicalFile = MedicalFile::find($id);
    
        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }
    
        $validated = $request->validate([
            'content' => 'required|string',
        ]);
    
        $note = $medicalFile->note()->create($validated);
    
        return response()->json(['message' => 'Note ajoutée avec succès', 'data' => $note]);
    }
    
    
    public function addPrescription(Request $request, string $id)
    {
        // Validation des données d'entrée
        $validated = $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
        ]);
    
        // Récupération du dossier médical
        $medicalFile = MedicalFile::find($id);
    
        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }
    
        // Récupération de la prescription
        $prescription = Prescription::find($validated['prescription_id']);
        
        if (!$prescription) {
            return response()->json(['message' => 'Prescription non trouvée'], 404);
        }
    
        // Ajout de la prescription à la table de jointure
        $medicalFile->medicalprescription()->create(['prescription_id' => $prescription->id]);
    
        return response()->json(['message' => 'Prescription ajoutée avec succès']);
    }
    
    

    public function addExam(Request $request, string $id)
    {
        $medicalFile = MedicalFile::find($id);

        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }

        $exam = Exam::find($request->exam_id);
        if (!$exam) {
            return response()->json(['message' => 'Examen non trouvé'], 404);
        }

        $medicalFile->exams()->attach($exam);

        return response()->json(['message' => 'Examen ajouté avec succès']);
    }


    public function addMedicalHistory(Request $request, string $id)
    {
        $medicalFile = MedicalFile::find($id);
    
        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }
    
        $validated = $request->validate([
            'content' => 'nullable|string',
        ]);
    
        $medicalHistory = $medicalFile->medicalHistory()->create($validated);
    
        return response()->json(['message' => 'Antecedent ajoutée avec succès', 'data' => $medicalHistory]);
    }

}
