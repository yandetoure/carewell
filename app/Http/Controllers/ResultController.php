<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ResultController extends Controller
{
    /**
     * Display a listing of the results.
     */
    public function index()
    {
        $results = Result::with('exam')->get();
        return response()->json([
            'status' => true,
            'data' => $results,
        ]);
    }

    /**
     * Display a listing of results for doctors.
     */
    public function doctorResults()
    {
        $doctor = Auth::user();

        // Vérifier que l'utilisateur est un médecin
        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer les résultats des examens du service du médecin
        $results = Result::with(['exam.service', 'doctor'])
            ->whereHas('exam', function ($query) use ($doctor) {
                $query->where('service_id', $doctor->service_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistiques des résultats du service
        $totalResults = Result::whereHas('exam', function ($query) use ($doctor) {
            $query->where('service_id', $doctor->service_id);
        })->count();
        
        $normalResults = Result::whereHas('exam', function ($query) use ($doctor) {
            $query->where('service_id', $doctor->service_id);
        })->where('status', 'normal')->count();
        
        $abnormalResults = Result::whereHas('exam', function ($query) use ($doctor) {
            $query->where('service_id', $doctor->service_id);
        })->where('status', 'abnormal')->count();
        
        $pendingResults = Result::whereHas('exam', function ($query) use ($doctor) {
            $query->where('service_id', $doctor->service_id);
        })->where('status', 'pending')->count();

        // Résultats récents (dernières 7 jours) du service
        $recentResults = Result::whereHas('exam', function ($query) use ($doctor) {
            $query->where('service_id', $doctor->service_id);
        })->where('created_at', '>=', now()->subDays(7))->count();

        return view('doctor.results', compact(
            'results',
            'totalResults',
            'normalResults',
            'abnormalResults',
            'pendingResults',
            'recentResults',
            'doctor'
        ));
    }

    /**
     * Display a listing of results for admin.
     */
    public function adminIndex()
    {
        $results = Result::with('exam')->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistiques
        $totalResults = Result::count();
        $exams = \App\Models\Exam::all();
        
        return view('admin.results.index', compact('results', 'totalResults', 'exams'));
    }

    /**
     * Store a newly created result in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'name' => 'required|string|max:255',
                'exam_id' => 'required|exists:exams,id', // Assurez-vous que cela correspond à votre table
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optionnel et de taille maximale 2 Mo
            ]);

            // Création d'une nouvelle instance de résultat
            $result = Result::create([
                'name' => $request->name,
                'exam_id' => $request->exam_id,
                'image' => $request->image ? $request->file('image')->store('results', 'public') : null,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Résultat créé avec succès',
                'data' => $result,
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
     * Display the specified result.
     */
    public function show($id)
    {
        $doctor = Auth::user();
        
        // Affichage d'un résultat spécifique
        $result = Result::with(['exam.service', 'doctor'])->find($id);

        if (!$result) {
            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'status' => false,
                    'message' => 'Résultat non trouvé',
                ], 404);
            }
            return redirect()->back()->withErrors(['error' => 'Résultat non trouvé.']);
        }

        // Vérifier que le résultat appartient au service du médecin
        if ($doctor && $doctor->hasRole('Doctor') && $result->exam->service_id !== $doctor->service_id) {
            if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
                return response()->json([
                    'status' => false,
                    'message' => 'Vous n\'êtes pas autorisé à voir ce résultat.',
                ], 403);
            }
            return redirect()->back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à voir ce résultat.']);
        }

        if (request()->expectsJson() || request()->header('Accept') === 'application/json') {
            return response()->json([
                'status' => true,
                'data' => $result,
            ]);
        }
        
        return view('doctor.result-details', compact('result', 'doctor'));
    }

    /**
     * Update the specified result in storage.
     */
    public function update(Request $request, $id)
    {
        $doctor = Auth::user();
        
        try {
            // Validation des données
            $request->validate([
                'name' => 'required|string',
                'status' => 'required|in:normal,abnormal,pending',
                'description' => 'nullable|string',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
                'pdfs.*' => 'nullable|mimes:pdf|max:10240',
            ]);

            // Recherche du résultat à modifier
            $result = Result::with('exam')->find($id);

            if (!$result) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Résultat non trouvé',
                    ], 404);
                }
                return redirect()->back()->withErrors(['error' => 'Résultat non trouvé.']);
            }

            // Vérifier que le résultat appartient au service du médecin
            if ($doctor && $doctor->hasRole('Doctor') && $result->exam->service_id !== $doctor->service_id) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Vous n\'êtes pas autorisé à modifier ce résultat.',
                    ], 403);
                }
                return redirect()->back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à modifier ce résultat.']);
            }

            // Mise à jour des données de base
            $result->name = $request->name;
            $result->status = $request->status;
            $result->description = $request->description;

            // Traitement des nouveaux fichiers
            $existingFiles = $result->files ?? [];
            
            // Traiter les nouvelles photos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('exam_results/photos', 'public');
                    $existingFiles[] = [
                        'type' => 'photo',
                        'name' => $photo->getClientOriginalName(),
                        'path' => $path,
                        'size' => $photo->getSize()
                    ];
                }
            }
            
            // Traiter les nouveaux PDFs
            if ($request->hasFile('pdfs')) {
                foreach ($request->file('pdfs') as $pdf) {
                    $path = $pdf->store('exam_results/pdfs', 'public');
                    $existingFiles[] = [
                        'type' => 'pdf',
                        'name' => $pdf->getClientOriginalName(),
                        'path' => $path,
                        'size' => $pdf->getSize()
                    ];
                }
            }

            $result->files = $existingFiles;
            $result->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Résultat mis à jour avec succès',
                    'data' => $result,
                ]);
            }
            
            return redirect()->back()->with('success', 'Résultat mis à jour avec succès.');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur de validation',
                    'errors' => $e->validator->errors(),
                ], 422);
            }
            return redirect()->back()->withErrors($e->validator->errors());
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage(),
                ], 500);
            }
            return redirect()->back()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    /**
     * Remove the specified result from storage.
     */
    public function destroy($id)
    {
        // Recherche du résultat à supprimer
        $result = Result::find($id);

        if (!$result) {
            return response()->json([
                'status' => false,
                'message' => 'Résultat non trouvé',
            ], 404);
        }

        // Supprimez l'ancienne image si nécessaire
        if ($result->image) {
            Storage::disk('public')->delete($result->image);
        }


        // Suppression du résultat
        $result->delete();

        return response()->json([
            'status' => true,
            'message' => 'Résultat supprimé avec succès',
        ]);
    }
}
