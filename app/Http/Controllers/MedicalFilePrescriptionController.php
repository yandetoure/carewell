<?php

namespace App\Http\Controllers;

use App\Models\MedicalFilePrescription;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Ticket;
use Illuminate\Support\Facades\Mail;
use App\Mail\PrescriptionMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;





class MedicalFilePrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Affichage des prescriptions médicales
        $medicalFilePrescriptions = MedicalFilePrescription::with(['medicalFile', 'p'])->get(); // Récupérer les prescriptions avec leurs fichiers médicaux et services
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
            // Validation des données
            $request->validate([
                'prescription_id' => 'required|exists:prescriptions,id', // Corrigez le nom de la table ici
                'medical_files_id' => 'required|exists:medical_files,id', // Correction du nom de la colonne
            ]);
            
            // Création d'une nouvelle instance de prescription médicale
            $medicalFilePrescription = MedicalFilePrescription::create([
                'is_done' => false, // Définit is_done à false par défaut
                'prescription_id' => $request->prescription_id,
                'medical_files_id' => $request->medical_files_id, // Correction du nom de la colonne
            ]);

            // Création du ticket associé à la prescription
            $ticket = Ticket::create([
                'prescription_id' => $request->prescription_id, // Utilisation correcte de la prescription_id
                'is_paid' => false, // Initialement, le ticket n'est pas payé
            ]);

            // Envoi d'un email de bienvenue
            Mail::to($user->email)->send(new \App\Mail\PrescriptionMail($user));

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
}
