<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExamPrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicalFileExamens = MedicalExam::with(['medicalFile', 'exam'])->get(); // Récupérer les prescriptions avec leurs fichiers médicaux et services
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
                'message' => 'Examen créée avec succès',
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
         $medicalFileExam = MedicalFileExam::with(['service', 'medicalFile'])->find($id);
        
         if (!$medicalFileExam) {
             return response()->json([
                 'status' => false,
                 'message' => 'Examen non trouvée',
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
        
        if (!$medicalFilePrescription) {
            return response()->json([
                'status' => false,
                'message' => 'Examen non trouvée',
            ], 404);
        }

        $medicalFileExam->is_done = $request->is_done;
        $medicalFileExam->exam_id = $request->exam_id;
        $medicalFileExam->save();

        return response()->json([
            'status' => true,
            'message' => "L'examen a été modifié avec succès",
            'data' => $medicalFilePrescription,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
