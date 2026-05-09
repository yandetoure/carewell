<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Service;
use App\Models\MedicalFileExam;
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
        $exams = Exam::with('service')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $totalExams = Exam::count();
        $services = Service::all();
        
        return view('admin.exams.index', compact('exams', 'totalExams', 'services'));
    }

    /**
     * Show the form for creating a new exam.
     */
    public function adminCreate()
    {
        $services = Service::all();
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
        $services = Service::all();
        $exam->load('service', 'results', 'medicalFileExam');
        return view('admin.exams.edit', compact('exam', 'services'));
    }

    /**
     * Display exams for a specific patient.
     */
    public function patientExams()
    {
        $patient = Auth::user();
        
        $exams = MedicalFileExam::with(['exam', 'doctor'])
            ->whereHas('medicalFile', function($query) use ($patient) {
                $query->where('user_id', $patient->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('patient.exams.index', compact('exams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
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

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => true,
                    'message' => 'Examen créé avec succès',
                    'data' => $exam,
                ], 201);
            }

            return redirect()->route('admin.exams.show', $exam)
                ->with('success', 'Examen créé avec succès !');
        } catch (ValidationException $e) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $e->validator->errors(),
                ], 422);
            }

            return back()->withErrors($e->validator)->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $exam = Exam::with(['service', 'medicalFileExam'])->find($id);
        
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

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'L\'examen a bien été modifié',
                'data' => $exam,
            ]);
        }

        return redirect()->route('admin.exams.show', $exam)
            ->with('success', 'Examen modifié avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Exam $exam)
    {
        $exam->delete();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => true,
                'message' => 'L\'examen a bien été supprimé',
            ]);
        }

        return redirect()->route('admin.exams')
            ->with('success', 'Examen supprimé avec succès !');
    }
}
