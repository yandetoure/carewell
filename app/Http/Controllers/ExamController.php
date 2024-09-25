<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use Illuminate\Validation\ValidationException;
use App\Models\Ticket;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Affichage des examens
        $exams = Exam::with('service')->get(); // Récupérer les examens avec leurs services
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
                'description' => 'required|string|min:50',  
                'price' => 'nullable|numeric',
                'service_id' => 'required|exists:services,id', // Validation pour l'ID du service
            ]);
            
            // Création d'une nouvelle instance d'examen
            $exam = Exam::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'service_id' => $request->service_id,
            ]);

            // Création du ticket associé à la prescription
            $ticket = Ticket::create([
            'exam_id' => $request->exam_id, // Utilisation correcte de la exam_id
            'is_paid' => false, // Initialement, le ticket n'est pas payé
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
        $exam = Exam::with('service')->find($id);
        
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
