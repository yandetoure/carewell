<?php declare(strict_types=1); 

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

    /**
     * Display a listing of tickets for admin/accountant.
     */
    public function adminTickets()
    {
        $tickets = Ticket::with(['appointment.service', 'prescription', 'exam', 'user', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $totalTickets = Ticket::count();
        $paidTickets = Ticket::where('is_paid', true)->count();
        $unpaidTickets = Ticket::where('is_paid', false)->count();
        
        $totalRevenue = Ticket::where('is_paid', true)
            ->join('appointments', 'tickets.appointment_id', '=', 'appointments.id')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');
        
        return view('admin.accounting.tickets', compact('tickets', 'totalTickets', 'paidTickets', 'unpaidTickets', 'totalRevenue'));
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['message' => 'Non autorisé'], 401);
        }
    
        if (!$user->hasRole('Accountant') && !$user->hasRole('Admin') && !$user->hasRole('Super Admin')) {
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
            $request->validate([
                'appointment_id' => 'nullable|exists:appointments,id',
                'prescription_id' => 'nullable|exists:prescriptions,id',
                'exam_id' => 'nullable|exists:exams,id',
                'user_id' => 'required|exists:users,id',
                'doctor_id' => 'nullable|exists:users,id',
            ]);

            $ticket = Ticket::create([
                'appointment_id' => $request->appointment_id,
                'prescription_id' => $request->prescription_id,
                'exam_id' => $request->exam_id,
                'user_id' => $request->user_id,
                'doctor_id' => $request->doctor_id,
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
        $ticket = Ticket::with(['appointment.service', 'appointment.user', 'prescription', 'exam', 'user', 'doctor'])->find($id);

        if (!$ticket) {
            if (!request()->expectsJson()) {
                return redirect()->route('admin.tickets')
                    ->with('error', 'Ticket non trouvé');
            }
            
            return response()->json([
                'status' => false,
                'message' => 'Ticket non trouvé',
            ], 404);
        }

        if (!request()->expectsJson()) {
            return view('admin.accounting.show-ticket', compact('ticket'));
        }

        return response()->json([
            'status' => true,
            'data' => $ticket,
        ]);
    }

    public function showTickets(Request $request)
    {
        $user = Auth::user();
        $limit = $request->input('limit', 6);
    
        $tickets = Ticket::with(['appointment.service', 'prescription.service', 'exam.service', 'user', 'doctor'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    
        if ($tickets->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'data' => $tickets,
                'totalItems' => $tickets->total(),
                'totalPages' => $tickets->lastPage(),
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tickets non trouvés',
            ], 404);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'is_paid' => 'required|boolean',
            ]);

            $ticket = Ticket::find($id);

            if (!$ticket) {
                if (!$request->expectsJson()) {
                    return redirect()->route('admin.tickets')
                        ->with('error', 'Ticket non trouvé');
                }
                
                return response()->json([
                    'status' => false,
                    'message' => 'Ticket non trouvé',
                ], 404);
            }

            if ($ticket->is_paid) {
                if (!$request->expectsJson()) {
                    return redirect()->route('admin.tickets')
                        ->with('info', 'Ce ticket est déjà marqué comme payé');
                }
                
                return response()->json([
                    'status' => false,
                    'message' => 'Le statut is_paid est déjà à true',
                ], 400);
            }

            $ticket->update([
                'is_paid' => true,
            ]);

            if (!$request->expectsJson()) {
                return redirect()->route('admin.tickets')
                    ->with('success', 'Ticket marqué comme payé avec succès');
            }

            return response()->json([
                'status' => true,
                'message' => 'Le statut du ticket a été mis à jour à true',
                'data' => $ticket,
            ]);
        } catch (ValidationException $e) {
            if (!$request->expectsJson()) {
                return redirect()->route('admin.tickets')
                    ->withErrors($e->validator->errors());
            }
            
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->validator->errors(),
            ], 422);
        }
    }

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

    public function userTickets()
    {
        $user = Auth::user();
        $limit = request()->input('limit', 10);

        $tickets = Ticket::with(['appointment', 'prescription', 'exam', 'user'])
                        ->where('user_id', $user->id)
                        ->orderBy('created_at', 'desc')
                        ->paginate($limit);

        return response()->json([
            'status' => true,
            'data' => [
                'tickets' => $tickets->items(),
                'total' => $tickets->total(),
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
            ],
        ]);
    }
}
