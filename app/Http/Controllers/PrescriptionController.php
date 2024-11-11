<?php

namespace App\Http\Controllers;

use App\Models\User; 
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $prescriptions = Prescription::with(['service'])->get();
        return response()->json([
            'status' => true,
            'data' => $prescriptions,
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
                'quantity' => 'required',  
                'price' => 'nullable|numeric',
                'service_id' => 'required|exists:services,id', // Validation pour l'ID du service
            ]);
            
            // Création d'une nouvelle instance d'examen
            $prescription = Prescription::create([
                'name' => $request->name,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'service_id' => $request->service_id,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Prescription créée avec succès',
                'data' => $prescription,
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
        $prescription = Prescription::with('prescription')->find($id);
        
        if (!$prescription) {
            return response()->json([
                'status' => false,
                'message' => 'Prescription non trouvé',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $prescription,
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
        $prescription = Prescription::find($id);
        
        if (!$prescription) {
            return response()->json([
                'status' => false,
                'message' => 'Examen non trouvé',
            ], 404);
        }

        $prescription->name = $request->name;
        $prescription->description = $request->description;
        $prescription->price = $request->price;
        $prescription->service_id = $request->service_id; // Mettre à jour l'ID du service
        $prescription->save();

        return response()->json([
            'status' => true,
            'message' => 'L\'examen a bien été modifié',
            'data' => $prescription,
        ]);
    }



    /**
 * Affiche la liste des prescriptions du service du médecin connecté.
 */
// public function getPrescriptionsByService(Request $request)
// {
//     // Récupérer l'utilisateur connecté
//     $user = auth()->user();

//     // Vérifier que l'utilisateur a un service_id
//     if (!$user->service_id) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Aucun service associé à ce médecin.',
//         ], 404);
//     }

//     // Récupérer les prescriptions du service du médecin
//     $prescriptions = Prescription::where('service_id', $user->service_id)
//         ->with('service')
//         ->get();

//     // Vérifier s'il y a des prescriptions
//     if ($prescriptions->isEmpty()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Aucune prescription trouvée pour ce service.',
//         ], 404);
//     }

//     // Renvoyer les prescriptions
//     return response()->json([
//         'status' => true,
//         'data' => $prescriptions,
//     ]);
// }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Supprimer un examen
        $prescription = Prescription::find($id);
        
        if (!$prescription) {
            return response()->json([
                'status' => false,
                'message' => 'La Prescription non trouvée',
            ], 404);
        }

        $prescription->delete();

        return response()->json([
            'status' => true,
            'message' => 'La Prescription a bien été supprimée',
        ]);
    }
}
