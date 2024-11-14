<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Ticket;
use App\Models\Medical;
use Illuminate\Http\Request;
use App\Models\MedicalFileExam;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ExamPrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicalFileExamens = MedicalFileExam::with(['medicalFile', 'exam'])->get(); // Récupérer les examens avec leurs fichiers médicaux et examens
        return response()->json([
            'status' => true,
            'data' => $medicalFileExamens,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'exam_id' => 'required|exists:exams,id',
                'medical_files_id' => 'required|exists:medical_files,id',
            ]);
            
            $medicalFileExam = MedicalFileExam::create([
                'is_done' => false,
                'exam_id' => $request->exam_id,
                'medical_files_id' => $request->medical_files_id,
            ]);

            $ticket = Ticket::create([
                'exam_id' => $request->exam_id,
                'is_paid' => false,
            ]);

            Mail::to($user->email)->send(new \App\Mail\PrescriptionMail($user));

            return response()->json([
                'status' => true,
                'message' => 'Examen créé avec succès',
                'data' => $medicalFileExam,
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
        $medicalFileExam = MedicalFileExam::with([ 'medicalFile.user', 'Exam', 'result'])->find($id);
        
        if (!$medicalFileExam) {
            return response()->json([
                'status' => false,
                'message' => 'Examen non trouvé',
            ], 404);
        }
 
        return response()->json([
            'status' => true,
            'data' => $medicalFileExam,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation des données
        $request->validate([
            'is_done' => 'required|boolean',
            'exam_id' => 'required|exists:exams,id',
        ]);

        $medicalFileExam = MedicalFileExam::find($id);
        
        if (!$medicalFileExam) {
            return response()->json([
                'status' => false,
                'message' => 'Examen non trouvé',
            ], 404);
        }

        $medicalFileExam->is_done = $request->is_done;
        $medicalFileExam->exam_id = $request->exam_id;
        $medicalFileExam->save();

        return response()->json([
            'status' => true,
            'message' => "L'examen a été modifié avec succès",
            'data' => $medicalFileExam,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $medicalFileExam = MedicalFileExam::find($id);
        
        if (!$medicalFileExam) {
            return response()->json([
                'status' => false,
                'message' => 'Examen non trouvé',
            ], 404);
        }

        $medicalFileExam->delete();

        return response()->json([
            'status' => true,
            'message' => "L'examen a été supprimé avec succès",
        ]);
    }

    public function getExamByService()
    {
        // Récupérer l'utilisateur connecté
        $doctor = auth()->user();

        // Vérifier que l'utilisateur est un médecin
        if (!$doctor || !$doctor->service_id) {
            return response()->json([
                'status' => false,
                'message' => 'Utilisateur non autorisé ou service non trouvé',
            ], 403);
        }

        // Récupérer les examens liés au service avec les informations de l'utilisateur
        $examPrescription = MedicalFileExam::with([
                'medicalFile.user',
                'exam.service',
            ])
            ->whereHas('exam', function ($query) use ($doctor) {
                $query->where('service_id', $doctor->service_id);
            })
            ->get();

        // Vérifier si des examens existent
        if ($examPrescription->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Aucun examen trouvé pour ce service',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $examPrescription,
        ]);
    }

    public function updateExamStatus(Request $request, $id)
    {
        $exam =  MedicalFileExam::find($id);

        if (!$exam) {
            return response()->json([
                'status' => false,
                'message' => 'Examen non trouvé',
            ], 404);
        }

        $exam->is_done = $request->is_done;
        $exam->save();

        return response()->json([
            'status' => true,
            'message' => 'Statut de l\'examen mis à jour avec succès',
            'data' => $exam,
        ]);
    }

    public function storeResult(Request $request, $examId)
    {
        // Vérification de l'authentification (si pas fait dans le middleware)
        // if (!auth()->check()) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Utilisateur non authentifié',
        //     ], 401);
        // }
    
        $doctorId = auth()->id();
    
        try {
            // Validation des données
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Vérifier si c'est une image
                'description' => 'nullable|string',
            ]);
    
            // Trouver l'examen correspondant à l'ID
            $exam = MedicalFileExam::find($examId);
    
            // Vérification de l'existence de l'examen
            if (!$exam) {
                return response()->json([
                    'status' => false,
                    'message' => 'Examen non trouvé',
                ], 404);
            }
    
            // Vérifier si l'examen a été effectué
            if (!$exam->is_done) {
                return response()->json([
                    'status' => false,
                    'message' => 'L\'examen n\'est pas encore effectué. Impossible d\'ajouter un résultat.',
                ], 400);
            }
    
            // Gestion de l'image si présente
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('results_images', 'public');
            }
    
            // Créer le résultat et associer l'examen
            $result = Result::create([
                'name' => $request->name,
                'exam_id' => $examId,
                'image' => $imagePath,
                'description' => $request->description,
                'doctor_id' => $doctorId, // ID du médecin connecté
            ]);
    
            // Retourner la réponse JSON avec succès
            return response()->json([
                'status' => true,
                'message' => 'Résultat ajouté avec succès',
                'data' => $result,
            ], 201);
    
        } catch (\Exception $e) {
            // Gérer les erreurs et les renvoyer avec le message d'erreur
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de l\'ajout du résultat',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    
    

}
