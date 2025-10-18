<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Mail\PrescriptionMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\MedicalFilePrescription;
use Illuminate\Validation\ValidationException;





class MedicalFilePrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Affichage des prescriptions médicales
        $medicalFilePrescriptions = MedicalFilePrescription::with(['medicalFile', 'prescription'])->get(); // Récupérer les prescriptions avec leurs fichiers médicaux et services
        return response()->json([
            'status' => true,
            'data' => $medicalFilePrescriptions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Assurez-vous que l'utilisateur est un médecin
            $user = Auth::user();
            if (!$user || !$user->hasRole('Doctor')) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
            }
    
            // Validation des données
            $request->validate([
                'prescription_id' => 'required|exists:prescriptions,id',
                'medical_files_id' => 'required|exists:medical_files,id',
            ]);
            
            // Création d'une nouvelle instance de prescription médicale
            $medicalFilePrescription = MedicalFilePrescription::create([
                'is_done' => false,
                'prescription_id' => $request->prescription_id,
                'medical_files_id' => $request->medical_files_id,
            ]);
    
            // Création du ticket associé à la prescription
            $ticket = Ticket::create([
                'prescription_id' => $request->prescription_id,
                'is_paid' => false,
            ]);
    
            // Envoi d'un email (si besoin, ici il faut récupérer l'utilisateur)
            Mail::to($user->email)->send(new PrescriptionMail($user));
    
            return response()->json([
                'status' => true,
                'message' => 'Prescription créée avec succès',
                'data' => $medicalFilePrescription,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la création de la prescription',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Affichage des détails d'une prescription médicale
        $medicalFilePrescription = MedicalFilePrescription::with(['service', 'medicalFile'])->find($id);
        
        if (!$medicalFilePrescription) {
            return response()->json([
                'status' => false,
                'message' => 'Prescription non trouvée',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $medicalFilePrescription,
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
            'prescription_id' => 'required|exists:prescriptions,id', // Correction du nom de la table ici
        ]);

        // Modifier une prescription
        $medicalFilePrescription = MedicalFilePrescription::find($id);
        
        if (!$medicalFilePrescription) {
            return response()->json([
                'status' => false,
                'message' => 'Prescription non trouvée',
            ], 404);
        }

        $medicalFilePrescription->is_done = $request->is_done;
        $medicalFilePrescription->prescription_id = $request->prescription_id; // Assurez-vous que cela est correct
        $medicalFilePrescription->service_id = $request->service_id; // Vérifiez si service_id est inclus dans la requête
        $medicalFilePrescription->save();

        return response()->json([
            'status' => true,
            'message' => 'La prescription a été modifiée avec succès',
            'data' => $medicalFilePrescription,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Supprimer une prescription
        $medicalFilePrescription = MedicalFilePrescription::find($id);
        
        if (!$medicalFilePrescription) {
            return response()->json([
                'status' => false,
                'message' => 'Prescription non trouvée',
            ], 404);
        }

        $medicalFilePrescription->delete();

        return response()->json([
            'status' => true,
            'message' => 'La prescription a été supprimée avec succès',
        ]);
    }


/**
 * Afficher les prescriptions du service du médecin connecté, avec les informations de l'utilisateur.
 */
public function getPrescriptionsByService()
{
    // Récupérer l'utilisateur connecté
    $doctor = auth()->user();

    // Vérifier que l'utilisateur est un médecin
    if (!$doctor || !$doctor->hasRole('Doctor')) {
        abort(403, 'Accès non autorisé');
    }

    // Récupérer les prescriptions de soins hospitaliers du service
    $prescriptions = MedicalFilePrescription::with([
            'medicalFile.user',
            'prescription.service',
            'doctor'
        ])
        ->whereHas('prescription', function ($query) use ($doctor) {
            $query->where('service_id', $doctor->service_id);
        })
        ->get();

    return view('doctor.prescriptions', compact('prescriptions', 'doctor'));
}

public function updatePrescriptionStatus(Request $request, $id)
{
    $doctor = Auth::user();
    $prescription = MedicalFilePrescription::find($id);

    if (!$prescription) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Prescription non trouvée',
            ], 404);
        }
        return redirect()->back()->withErrors(['error' => 'Prescription non trouvée.']);
    }

    // Vérifier que la prescription appartient au service du médecin
    if ($prescription->prescription->service_id !== $doctor->service_id) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'êtes pas autorisé à modifier cette prescription.',
            ], 403);
        }
        return redirect()->back()->withErrors(['error' => 'Vous n\'êtes pas autorisé à modifier cette prescription.']);
    }

    try {
        $request->validate([
            'is_done' => 'required|boolean'
        ]);
        
        $prescription->is_done = $request->is_done;
        $prescription->save();
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
            'message' => 'Statut de la prescription mis à jour avec succès.',
            'data' => $prescription,
        ]);
    }
    
    return redirect()->back()->with('success', 'Statut de la prescription mis à jour avec succès.');
}


}
