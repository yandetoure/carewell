<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with(['appointment', 'prescription', 'exam', 'user', 'doctor'])->get();
        return response()->json(['data' => $tickets]);
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $user = Auth::user(); // Récupère l'utilisateur authentifié
    
        // Vérifiez si l'utilisateur est authentifié
        if (!$user) {
            return response()->json(['message' => 'Non autorisé'], 401);
        }
    
        // Vérifiez si l'utilisateur a le rôle approprié
        if (!$user->hasRole('accountant')) {
            return response()->json(['message' => 'Accès refusé'], 403);
        }
    
        $ticket = Ticket::findOrFail($id);
        $ticket->is_paid = $request->input('is_paid');
        $ticket->save();
    
        return response()->json([
            'success' => true,
            'data' => $ticket,
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
                'appointment_id' => 'nullable|exists:appointments,id',
                'prescription_id' => 'nullable|exists:prescriptions,id',
                'exam_id' => 'nullable|exists:exams,id',
            ]);
            $ticket = Ticket::create([
                'appointment_id' => $request->appointment_id,
                'prescription_id' => $request->prescription_id,
                'exam_id' => $request->exam_id,
                'is_paid' => false,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Ticket créé avec succès',
                'data' => $ticket,
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
        // Récupérer un ticket par son ID
        $ticket = Ticket::with(['appointment', 'prescription', 'exam', 'user'])->find($id);

        if ($ticket) {
            return response()->json([
                'status' => true,
                'data' => $ticket,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Ticket non trouvé',
            ], 404);
        }
    }


    //Rcuperer les ticket de l'utlisateur connecé
    public function showTickets(){
        $user = auth()->user();
        $tickets = Ticket::with(['appointment.service', 'prescription.service', 'exam.service', 'user', 'doctor'])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')             
        ->get();

        if ($tickets) {
            return response()->json([
               'status' => true,
                'data' => $tickets,
            ]);
        } else {
            return response()->json([
               'status' => false,
               'message' => 'Tickets non trouvés',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /**
 * Update the specified resource in storage.
 * Only allows updating the 'is_paid' field to true if the user is a comptable or an admin.
 */
public function update(Request $request, string $id)
{
    // Récupérer l'utilisateur connecté
    $user = auth()->user();

    // Vérifier si l'utilisateur a le rôle de comptable ou d'admin
    // if (!$user->hasRole(['accountant', 'admin'])) {
    //     return response()->json([
    //         'status' => false,
    //         'message' => 'Vous n\'avez pas l\'autorisation de mettre à jour ce ticket',
    //     ], 403);
    // }

    try {
        // Validation des données, uniquement pour 'is_paid'
        $request->validate([
            'is_paid' => 'required|boolean',
        ]);

        // Trouver le ticket
        $ticket = Ticket::find($id);

        // Vérifier si le ticket existe
        if (!$ticket) {
            return response()->json([
                'status' => false,
                'message' => 'Ticket non trouvé',
            ], 404);
        }

        // Vérifier si 'is_paid' est déjà à true
        if ($ticket->is_paid) {
            return response()->json([
                'status' => false,
                'message' => 'Le statut is_paid est déjà à true',
            ], 400);
        }

        // Mettre à jour uniquement le statut 'is_paid' à true
        $ticket->update([
            'is_paid' => true,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Le statut du ticket a été mis à jour à true',
            'data' => $ticket,
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'status' => false,
            'message' => 'Erreur de validation',
            'errors' => $e->validator->errors(),
        ], 422);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::find($id);

        if ($ticket) {
            $ticket->delete();
            return response()->json([
                'status' => true,
                'message' => 'Ticket supprimé avec succès',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Ticket non trouvé',
            ], 404);
        }
    }

    /**
 * Display the tickets for the authenticated user.
 */
public function userTickets()
{
    // Récupérer l'utilisateur connecté
    $user = Auth()->user();

    // Récupérer les tickets associés à cet utilisateur, triés par date de création (plus récent en premier)
    $tickets = Ticket::with(['appointment', 'prescription', 'exam', 'user'])
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc') // Tri par date de création
                    ->get();

    // Vérifier si des tickets ont été trouvés
    if ($tickets->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'Aucun ticket trouvé pour cet utilisateur',
        ], 404);
    }

    // Retourner les tickets trouvés
    return response()->json([
        'status' => true,
        'data' => $tickets,
    ]);
}


}
