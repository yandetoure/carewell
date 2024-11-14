<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Affichage des examens
        $exams = Exam::with('service')->get(); 
        return response()->json([
            'status' => true,
            'data' => $exams,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'name' => 'required|string|max:255|unique:exams',
                'description' => 'required|string|min:5',  
                'price' => 'nullable|numeric',
                'service_id' => 'required|exists:services,id',
            ]);
            
            $exam = Exam::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'service_id' => $request->service_id,
            ]);

            $ticket = Ticket::create([
            'exam_id' => $request->exam_id, 
            'is_paid' => false, 
        ]);


            return response()->json([
                'status' => true,
                'message' => 'Examen créé avec succès',
                'data' => $exam,
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
        // Affichage des détails d'un examen
        $exam = Exam::with('service','medicalFileExam')->find($id);
        
        if (!$exam) {
            return response()->json([
                'status' => false,
                'message' => 'Examen non trouvé',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $exam,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255|unique:exams,name,' . $id,
            'description' => 'required|string|min:50',  
            'price' => 'nullable|numeric',
            'service_id' => 'required|exists:services,id', // Validation pour l'ID du service
        ]);

        // Modifier un examen
        $exam = Exam::find($id);
        
        if (!$exam) {
            return response()->json([
                'status' => false,
                'message' => 'Examen non trouvé',
            ], 404);
        }

        $exam->name = $request->name;
        $exam->description = $request->description;
        $exam->price = $request->price;
        $exam->service_id = $request->service_id; // Mettre à jour l'ID du service
        $exam->save();

        return response()->json([
            'status' => true,
            'message' => 'L\'examen a bien été modifié',
            'data' => $exam,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Supprimer un examen
        $exam = Exam::find($id);
        
        if (!$exam) {
            return response()->json([
                'status' => false,
                'message' => 'Examen non trouvé',
            ], 404);
        }

        $exam->delete();

        return response()->json([
            'status' => true,
            'message' => 'L\'examen a bien été supprimé',
        ]);
    }
}
