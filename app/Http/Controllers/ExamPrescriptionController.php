<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Ticket;
use App\Models\MedicalFile;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Models\MedicalFileExam;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ExamPrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medicalFileExamens = MedicalFileExam::with(['medicalFile.user', 'exam', 'doctor'])->get();
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
            $user = Auth::user();
            if (!$user || (!$user->hasRole('Doctor') && !$user->hasRole('Admin'))) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
            }

            $request->validate([
                'exam_id' => 'required|exists:exams,id',
                'medical_files_id' => 'required|exists:medical_files,id',
                'instructions' => 'nullable|string',
            ]);
            
            $medicalFileExam = MedicalFileExam::create([
                'is_done' => false,
                'exam_id' => $request->exam_id,
                'medical_files_id' => $request->medical_files_id,
                'doctor_id' => $user->id,
                'instructions' => $request->instructions,
            ]);

            $medicalFile = MedicalFile::with('user')->find($request->medical_files_id);

            Ticket::create([
                'exam_id' => $request->exam_id,
                'user_id' => $medicalFile->user_id,
                'doctor_id' => $user->id,
                'is_paid' => false,
            ]);

            if ($medicalFile && $medicalFile->user && $medicalFile->user->email) {
                Mail::to($medicalFile->user->email)->send(new \App\Mail\PrescriptionMail($medicalFile->user));
            }

            return response()->json([
                'status' => true,
                'message' => 'Examen prescrit avec succès',
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
        $medicalFileExam = MedicalFileExam::with(['medicalFile.user', 'exam', 'result', 'doctor'])->find($id);
        
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
            'message' => "Le statut de l'examen a été modifié avec succès",
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
        $doctor = auth()->user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        $exams = MedicalFileExam::with([
                'medicalFile.user',
                'exam.service',
                'doctor'
            ])
            ->whereHas('exam', function ($query) use ($doctor) {
                $query->where('service_id', $doctor->service_id);
            })
            ->get();

        return view('doctor.exams', compact('exams', 'doctor'));
    }

    public function updateExamStatus(Request $request, $id)
    {
        $doctor = Auth::user();
        $exam = MedicalFileExam::find($id);

        if (!$exam) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Examen non trouvé',
                ], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Examen non trouvé.']);
        }

        if ($exam->exam->service_id !== $doctor->service_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à modifier cet examen.',
                ], 403);
            }
            return redirect()->back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à modifier cet examen.']);
        }

        try {
            $request->validate([
                'is_done' => 'required|boolean'
            ]);
            
            $exam->is_done = $request->is_done;
            $exam->save();
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $e->validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->validator->errors());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut de l\'examen mis à jour avec succès.',
                'data' => $exam,
            ]);
        }
        
        return redirect()->back()->with('success', 'Statut de l\'examen mis à jour avec succès.');
    }

    public function getExamResult($examId)
    {
        $doctor = Auth::user();
        
        try {
            $exam = MedicalFileExam::find($examId);

            if (!$exam) {
                if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Examen non trouvé',
                    ], 404);
                }
                return redirect()->back()->withErrors(['error' => 'Examen non trouvé.']);
            }

            if ($exam->exam->service_id !== $doctor->service_id) {
                if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vous n\'êtes pas autorisé à voir le résultat de cet examen.',
                    ], 403);
                }
                return redirect()->back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à voir le résultat de cet examen.']);
            }

            $result = Result::with('doctor')->where('exam_id', $exam->exam_id)->first();

            if (!$result) {
                if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucun résultat trouvé pour cet examen',
                    ], 404);
                }
                return redirect()->back()->withErrors(['error' => 'Aucun résultat trouvé pour cet examen.']);
            }

            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'success' => true,
                    'result' => $result,
                ]);
            }
            
            return view('doctor.exam-result', compact('result', 'exam'));

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération du résultat: ' . $e->getMessage(),
                ], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la récupération du résultat.']);
        }
    }

    public function storeResult(Request $request, $examId)
    {
        $doctor = Auth::user();
        
        try {
            $request->validate([
                'result' => 'required|string',
                'status' => 'required|in:normal,abnormal,pending',
                'notes' => 'nullable|string',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'pdfs.*' => 'nullable|mimes:pdf|max:10240',
            ]);

            $exam = MedicalFileExam::find($examId);

            if (!$exam) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Examen non trouvé',
                    ], 404);
                }
                return redirect()->back()->withErrors(['error' => 'Examen non trouvé.']);
            }

            if ($exam->exam->service_id !== $doctor->service_id) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vous n\'êtes pas autorisé à ajouter un résultat à cet examen.',
                    ], 403);
                }
                return redirect()->back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à ajouter un résultat à cet examen.']);
            }

            if (!$exam->is_done) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'L\'examen n\'est pas encore effectué. Impossible d\'ajouter un résultat.',
                    ], 400);
                }
                return redirect()->back()->withErrors(['error' => 'L\'examen n\'est pas encore effectué.']);
            }

            $files = [];
            
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('exam_results/photos', 'public');
                    $files[] = [
                        'type' => 'photo',
                        'name' => $photo->getClientOriginalName(),
                        'path' => $path,
                        'size' => $photo->getSize()
                    ];
                }
            }
            
            if ($request->hasFile('pdfs')) {
                foreach ($request->file('pdfs') as $pdf) {
                    $path = $pdf->store('exam_results/pdfs', 'public');
                    $files[] = [
                        'type' => 'pdf',
                        'name' => $pdf->getClientOriginalName(),
                        'path' => $path,
                        'size' => $pdf->getSize()
                    ];
                }
            }

            $result = Result::create([
                'name' => $request->result,
                'exam_id' => $exam->exam_id,
                'description' => $request->notes,
                'status' => $request->status,
                'doctor_id' => $doctor->id,
                'files' => $files
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Résultat ajouté avec succès',
                    'data' => $result,
                ]);
            }
            
            return redirect()->back()->with('success', 'Résultat ajouté avec succès.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $e->validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($e->validator->errors());
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout du résultat: ' . $e->getMessage(),
                ], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Erreur lors de l\'ajout du résultat.']);
        }
    }
}
