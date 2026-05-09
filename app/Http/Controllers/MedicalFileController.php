<?php declare(strict_types=1); 
namespace App\Http\Controllers;

use App\Model;
use App\Models\Exam;
use App\Models\Ticket;
use App\Models\Disease;
use App\Models\Service;
use App\Models\MedicalFile;
use App\Models\MedicalHistory;
use App\Models\Prescription;
use App\Models\Medicament;
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
        $medicalFiles = MedicalFile::with(['note', 'medicalHistories', 'medicalprescription',  'user'  ])->get();
        return response()->json(['data' => $medicalFiles]);
    }

    /**
     * Display medical files for doctor interface.
     */
    public function doctorMedicalFiles()
    {
        $doctor = Auth::user();
        
        $medicalFiles = MedicalFile::with(['note', 'medicalHistories', 'medicalprescription', 'user'])
            ->whereHas('user.appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $totalFiles = $medicalFiles->total();
        $recentFiles = MedicalFile::whereHas('user.appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        return view('doctor.medical-files.index', compact('medicalFiles', 'totalFiles', 'recentFiles'));
    }

    /**
     * Display medical history for doctor interface.
     */
    public function doctorMedicalHistory()
    {
        $doctor = Auth::user();
        
        $medicalFiles = MedicalFile::with([
            'user', 
            'medicalHistories.doctor',
            'note.doctor'
        ])
            ->whereHas('user.appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->orderBy('updated_at', 'desc')
            ->get();
        
        $medicalFiles = $medicalFiles->map(function($file) use ($doctor) {
            $file->patient = $file->user;
            $latestHistory = $file->medicalHistories->sortByDesc('created_at')->first();
            $latestNote = $file->note->sortByDesc('created_at')->first();
            
            if ($latestHistory && $latestHistory->doctor) {
                $file->doctor = $latestHistory->doctor;
            } elseif ($latestNote && $latestNote->doctor) {
                $file->doctor = $latestNote->doctor;
            } else {
                $file->doctor = $doctor;
            }
            
            $file->patient_id = $file->user_id;
            $file->consultation_type = null;
            
            return $file;
        });
        
        return view('doctor.medical-history', compact('medicalFiles'));
    }

    /**
     * Display medical files for secretary interface.
     */
    public function secretaryMedicalFiles()
    {
        $secretary = Auth::user();
        
        if (!$secretary || !$secretary->hasRole('Secretary')) {
            abort(403, 'Accès non autorisé');
        }

        $medicalFiles = MedicalFile::with(['note', 'medicalHistories', 'medicalprescription', 'user'])
            ->whereHas('user.appointments', function($query) use ($secretary) {
                $query->where('service_id', $secretary->service_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $totalFiles = $medicalFiles->total();
        $recentFiles = MedicalFile::whereHas('user.appointments', function($query) use ($secretary) {
                $query->where('service_id', $secretary->service_id);
            })
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        
        $patientsWithFiles = \App\Models\User::role('Patient')
            ->whereHas('appointments', function($query) use ($secretary) {
                $query->where('service_id', $secretary->service_id);
            })
            ->whereHas('medicalFiles')
            ->count();

        return view('secretary.medical-files.index', compact('medicalFiles', 'totalFiles', 'recentFiles', 'patientsWithFiles'));
    }

    /**
     * Display medical file for a specific patient.
     */
    public function showPatientMedicalFile($patientId)
    {
        $doctor = Auth::user()->load('service');
        
        $patient = \App\Models\User::whereHas('appointments', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->findOrFail($patientId);
        
        $medicalFile = MedicalFile::with([
            'note.doctor', 
            'medicalHistories.doctor', 
            'medicalprescription.doctor', 
            'medicalexam.doctor',
            'user'
        ])
            ->where('user_id', $patientId)
            ->first();
        
        if (!$medicalFile) {
            $medicalFile = MedicalFile::create([
                'user_id' => $patientId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        $diseases = \App\Models\Disease::orderBy('name')->get();
        $prescriptions = \App\Models\Prescription::with('service')->orderBy('name')->get();
        $exams = \App\Models\Exam::with('service')->orderBy('name')->get();
        $medicaments = \App\Models\Medicament::disponible()->orderBy('nom')->get();
        
        $ordonnances = \App\Models\Ordonnance::where('patient_id', $patientId)
            ->with(['medicaments' => function($query) {
                $query->withPivot(['quantite', 'posologie', 'duree_jours', 'instructions_speciales']);
            }])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('doctor.medical-files.show', compact('medicalFile', 'patient', 'doctor', 'diseases', 'prescriptions', 'exams', 'medicaments', 'ordonnances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $medicalFile = MedicalFile::create($request->all());
        return response()->json(['message' => 'Dossier médical créé avec succès', 'data' => $medicalFile]);
    }

    public function medicalHystory(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $medicalFile = MedicalFile::with(['note', 'medicalHistories', 'medicalprescription.prescription', 'user', 'medicalexam.exam', 'medicaldisease.disease'])->find($id);
    
        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }
    
        return response()->json(['data' => $medicalFile]);
    }
    
    public function showAuthMedicalFile()
    {
        $user = Auth::user();
        $medicalFile = MedicalFile::with(['note', 'medicalHistories', 'medicalprescription.prescription', 'user', 'medicalexam.exam', 'medicaldisease.disease'])
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
    
        $note = $medicalFile->note()->create([
            'content' => $validated['content'],
            'doctor_id' => Auth::id(), 
        ]);
    
        return response()->json(['message' => 'Note ajoutée avec succès', 'data' => $note]);
    }
    
    public function addPrescription(Request $request, string $id)
    {
        $validated = $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'quantity' => 'nullable|integer|min:1',
            'frequency' => 'nullable|string',
            'duration' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        $medicalFile = MedicalFile::find($id);

        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }

        $prescription = Prescription::find($validated['prescription_id']);
        
        if (!$prescription) {
            return response()->json(['message' => 'Prescription non trouvée'], 404);
        }

        $userId = $medicalFile->user_id;

        $medicalFile->medicalprescription()->create([
            'prescription_id' => $prescription->id,
            'doctor_id' => Auth::id(),
            'quantity' => $validated['quantity'] ?? null,
            'frequency' => $validated['frequency'] ?? null,
            'duration' => $validated['duration'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
        ]);

        $ticket = Ticket::create([
            'prescription_id' => $prescription->id,
            'doctor_id' => Auth::id(),
            'user_id' => $userId,
            'is_paid' => false,
        ]);
        
        return response()->json(['message' => 'Prescription ajoutée avec succès']);
    }
    
    public function addExam(Request $request, string $id)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'type' => 'nullable|string',
            'instructions' => 'nullable|string',
        ]);

        $medicalFile = MedicalFile::find($id);
    
        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }
    
        $exam = Exam::find($validated['exam_id']);
        if (!$exam) {
            return response()->json(['message' => 'Examen non trouvé'], 404);
        }
        $userId = $medicalFile->user_id;

        $medicalFile->medicalexam()->create([
            'exam_id' => $exam->id,
            'doctor_id' => Auth::id(),
            'type' => $validated['type'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
        ]);    

        $ticket = Ticket::create([
            'exam_id' => $exam->id,
            'doctor_id' => Auth::id(),       
            'user_id' => $userId,
            'is_paid' => false, 
        ]);
        return response()->json(['message' => 'Examen ajouté avec succès']);
    }
    
    public function addMedicalHistories(Request $request, string $id)
    {
        $medicalFile = MedicalFile::find($id);
    
        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }
    
        $validated = $request->validate([
            'content' => 'nullable|string',
        ]);
    
        $medicalHistories = $medicalFile->medicalHistories()->create($validated);
    
        return response()->json(['message' => 'Antecedent ajoutée avec succès', 'data' => $medicalHistories]);
    }

    public function addDisease(Request $request, string $id)
    {
        $validated = $request->validate([
            'disease_id' => 'nullable|required|exists:diseases,id',
            'treatment' => 'nullable|required',
            'state' => 'nullable|string',
        ]);
    
        $medicalFile = MedicalFile::find($id);
    
        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }
    
        $disease = Disease::find($validated['disease_id']);
        
        if (!$disease) {
            return response()->json(['message' => 'Maladie non trouvée'], 404);
        }
    
        $medicalFile->medicaldisease()->create([
            'disease_id' => $disease->id,
            'treatment' => $validated['treatment'],
            'state' => $validated['state'],
        ]);
    
        return response()->json(['message' => 'Maladie ajoutée avec succès']);
    }
    
    public function addOrdonnance(Request $request, string $id)
    {
        $validated = $request->validate([
            'medicaments' => 'required|array|min:1',
            'medicaments.*.id' => 'required|exists:medicaments,id',
            'medicaments.*.quantite' => 'required|integer|min:1',
            'medicaments.*.posologie' => 'required|string',
            'medicaments.*.duree_jours' => 'nullable|integer|min:1',
            'medicaments.*.instructions_speciales' => 'nullable|string',
        ]);
    
        $medicalFile = MedicalFile::find($id);
    
        if (!$medicalFile) {
            return response()->json(['message' => 'Dossier médical non trouvé'], 404);
        }
    
        $doctor = Auth::user();
        $patient = $medicalFile->user;
    
        $ordonnance = \App\Models\Ordonnance::create([
            'patient_id' => $patient->id,
            'medecin_id' => $doctor->id,
            'patient_first_name' => $patient->first_name,
            'patient_last_name' => $patient->last_name,
            'medecin_first_name' => $doctor->first_name,
            'medecin_last_name' => $doctor->last_name,
            'date_prescription' => now(),
            'date_validite' => now()->addDays(30),
            'statut' => 'active',
            'instructions' => 'Ordonnance créée depuis le dossier médical'
        ]);
    
        foreach ($validated['medicaments'] as $medicamentData) {
            $ordonnance->medicaments()->attach($medicamentData['id'], [
                'quantite' => $medicamentData['quantite'],
                'posologie' => $medicamentData['posologie'],
                'duree_jours' => $medicamentData['duree_jours'] ?? null,
                'instructions_speciales' => $medicamentData['instructions_speciales'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    
        return response()->json(['message' => 'Ordonnance créée avec succès']);
    }
}
