<?php
namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AvailabilityController extends Controller
{

        /**
     * Display a listing of the available slots for a specific doctor and service.
     */
    public function index()
    {
        $availabilities = Availability::with(['doctor', 'service'])->get();
        return response()->json([
            'status' => true,
            'data' => $availabilities,
        ]);
    }
    
    /**
     * Store a newly created availability in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation des données
            $request->validate([
                'doctor_id' => 'required|exists:users,id', // Vérifie que le médecin existe
                'service_id' => 'required|exists:services,id', // Vérifie que le service existe
                'available_date' => 'required|date', // Validation de la date
                'start_time' => 'required|date_format:H:i', // Format d'heure : HH:MM
                'end_time' => 'required|date_format:H:i|after:start_time', // L'heure de fin doit être après l'heure de début
            ]);

            // Création de la disponibilité
            $availability = Availability::create([
                'doctor_id' => $request->doctor_id,
                'service_id' => $request->service_id,
                'available_date' => $request->available_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Disponibilité ajoutée avec succès',
                'data' => $availability,
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
 * Display the available slots for a specific doctor and service.
 */
public function show($doctorId, $serviceId)
{
    $availabilities = Availability::where('doctor_id', $doctorId)
        ->where('service_id', $serviceId)
        ->with(['doctor', 'service'])
        ->get();

    if ($availabilities->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'Aucune disponibilité trouvée pour ce médecin et ce service',
        ], 404);
    }

    return response()->json([
        'status' => true,
        'data' => $availabilities,
    ]);
}

}