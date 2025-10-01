<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * Display a listing of exams for admin.
     */
    public function adminIndex()
    {
        $exams = Exam::with('service')->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistiques
        $totalExams = Exam::count();
        $services = \App\Models\Service::all();
        
        return view('admin.exams.index', compact('exams', 'totalExams', 'services'));
    }

    /**
     * Show the form for creating a new exam.
     */
    public function adminCreate()
    {
        $services = \App\Models\Service::all();
        return view('admin.exams.create', compact('services'));
    }

    /**
     * Display the specified exam for admin.
     */
    public function adminShow(Exam $exam)
    {
        $exam->load('service', 'results', 'medicalFileExam');
        return view('admin.exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the specified exam.
     */
    public function adminEdit(Exam $exam)
    {
        $services = \App\Models\Service::all();
        $exam->load('service', 'results', 'medicalFileExam');
        return view('admin.exams.edit', compact('exam', 'services'));
    }

    /**
     * Display exams for a specific patient.
     */
    public function patientExams()
    {
        $patient = Auth::user();
        
        // Récupérer les examens du patient connecté
        $exams = collect([]); // Temporairement vide, à implémenter selon votre modèle
        
        return view('patient.exams.index', compact('exams'));
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

            // Si c'est une requête API, retourner JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => true,
                    'message' => 'Examen créé avec succès',
                    'data' => $exam,
                ], 201);
            }

            // Sinon, rediriger vers l'interface admin
            return redirect()->route('admin.exams.show', $exam)
                ->with('success', 'Examen créé avec succès !');
        } catch (ValidationException $e) {
            // Si c'est une requête API, retourner JSON
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $e->validator->errors(),
                ], 422);
            }

            // Sinon, rediriger avec erreurs
            return back()->withErrors($e->validator)->withInput();
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
    public function update(Request $request, Exam $exam)
    {
        // Validation des données
        $request->validate([
            'name' => 'required|string|max:255|unique:exams,name,' . $exam->id,
            'description' => 'required|string|min:5',  
            'price' => 'nullable|numeric',
            'service_id' => 'required|exists:services,id',
        ]);

        $exam->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'service_id' => $request->service_id,
        ]);

        // Si c'est une requête API, retourner JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'L\'examen a bien été modifié',
                'data' => $exam,
            ]);
        }

        // Sinon, rediriger vers l'interface admin
        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Examen modifié avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Exam $exam)
    {
        $exam->delete();

        // Si c'est une requête API, retourner JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'L\'examen a bien été supprimé',
            ]);
        }

        // Sinon, rediriger vers l'interface admin
        return redirect()->route('admin.exams')
            ->with('success', 'Examen supprimé avec succès !');
    }
}
