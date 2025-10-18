<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
   // Affiche toutes les discussions de l'utilisateur authentifié
   public function getAllDiscussions()
   {
       $authUserId = Auth::id();
       
       // Récupère les discussions avec les utilisateurs avec qui des messages ont été échangés
       $discussions = Message::where('sender_id', $authUserId)
                         ->orWhere('receiver_id', $authUserId)
                         ->with(['sender', 'receiver'])
                         ->get()
                         ->groupBy(function($message) use ($authUserId) {
                             // Ici, nous groupons les messages par l'utilisateur qui n'est pas l'utilisateur authentifié
                             return $message->sender_id === $authUserId ? $message->receiver_id : $message->sender_id;
                         });
   
       $result = [];
   
       foreach ($discussions as $userId => $messages) {
           $lastMessage = $messages->last();
           $unreadCount = $messages->where('is_read', false)->count();
           
           // Ici, on récupère l'utilisateur qui n'est pas l'utilisateur authentifié
           $user = $messages->first()->sender_id === $authUserId ? $messages->first()->receiver : $messages->first()->sender;
   
           // On s'assure d'utiliser les détails de l'interlocuteur pour le résultat
           $result[] = [
               'user_id' => $userId, // ID de l'interlocuteur
               'user_first_name' => $user->first_name, // Prénom de l'interlocuteur
               'user_last_name' => $user->last_name, // Nom de famille de l'interlocuteur
               'user_photo' => $user->photo ? asset('storage/' . $user->photo) : null,            
               'last_message' => $lastMessage->message, // Dernier message
               'last_message_time' => $lastMessage->created_at->format('H:i'), // Heure du dernier message
               'unread_count' => $unreadCount, // Nombre de messages non lus
           ];
       }
   
       return response()->json([
           'status' => true,
           'message' => "Liste des discussions",
           'data' => $result,
       ], 200);
   }
   
   

   public function sendMessage(Request $request)
   {
       // Valider les données d'entrée
       $request->validate([
           'receiver_id' => 'required|exists:users,id',
           'message' => 'required|string|max:5000',
           'subject' => 'nullable|string|max:255',
       ]);

       try {
           // Créer un nouveau message
           $message = Message::create([
               'sender_id' => Auth::id(),
               'receiver_id' => $request->receiver_id,
               'message' => $request->message,
               'subject' => $request->subject,
           ]);

           return response()->json([
               'status' => true,
               'message' => 'Message envoyé avec succès',
               'data' => $message,
           ], 201);

       } catch (\Exception $e) {
           return response()->json([
               'status' => false,
               'message' => 'Erreur lors de l\'envoi du message',
               'error' => $e->getMessage(),
           ], 500);
       }
   }

   // Affiche les messages échangés avec un utilisateur spécifique
   public function getMessages($userId)
   {
       $authUserId = Auth::id();
      
       $messages = Message::where(function($query) use ($authUserId, $userId) {
               $query->where('sender_id', $authUserId)
                     ->where('receiver_id', $userId);
           })
           ->orWhere(function($query) use ($authUserId, $userId) {
               $query->where('sender_id', $userId)
                     ->where('receiver_id', $authUserId);
           })
           ->with(['sender', 'receiver'])
           ->get();
      
       return response()->json([
           'status' => true,
           'message' => "Messages échangés",
           'data' => $messages,
       ], 200);
   }
   
   

   // Modifier un message
   public function updateMessage(Request $request, $messageId)
   {
       $message = Message::findOrFail($messageId);

       if ($message->sender_id !== Auth::id()) {
           return response()->json([
               'status' => false,
               'message' => "Vous n'êtes pas autorisé à modifier ce message",
           ], 403);
       }

       $message->update([
           'updated_message' => $request->message,
           'modified_at' => now(),
       ]);

       return response()->json([
           'status' => true,
           'message' => "Message modifié avec succès",
           'data' => $message,
       ], 200);
   }

   // Supprimer un message
   public function deleteMessage($messageId)
   {
       $message = Message::findOrFail($messageId);

       if ($message->sender_id !== Auth::id()) {
           return response()->json([
               'status' => false,
               'message' => "Vous n'êtes pas autorisé à supprimer ce message",
           ], 403);
       }

       $message->update([
           'is_deleted' => true,
           'deleted_at' => now(),
       ]);

       return response()->json([
           'status' => true,
           'message' => "Message supprimé avec succès",
       ], 200);
   }


    // Restaurer un message supprimé
    public function restoreMessage($messageId)
    {
        $message = Message::onlyTrashed()->findOrFail($messageId);

        if ($message->sender_id !== Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => "Vous n'êtes pas autorisé à restaurer ce message",
            ], 403);
        }

        $message->restore();

        return response()->json([
            'status' => true,
            'message' => "Message restauré avec succès",
            'data' => $message,
        ], 200);
    }


    public function markMessagesAsRead($userId)
{
    $authUserId = Auth::id();

    // Met à jour les messages comme lus pour la discussion avec l'utilisateur donné
    Message::where(function($query) use ($authUserId, $userId) {
            $query->where('sender_id', $authUserId)
                  ->where('receiver_id', $userId);
        })
        ->orWhere(function($query) use ($authUserId, $userId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $authUserId);
        })
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json([
        'status' => true,
        'message' => "Messages marqués comme lus",
    ], 200);
}
public function markAsRead($id)
{
    try {
        $message = Message::findOrFail($id);
        $message->is_read = true; // Met à jour le champ booléen
        $message->save();

        return response()->json(['message' => 'Message marked as read'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to mark message as read', 'details' => $e->getMessage()], 500);
    }
}

    /**
     * Afficher les messages pour les médecins
     */
    public function doctorMessages()
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer les discussions du médecin
        $discussions = Message::where('sender_id', $doctor->id)
            ->orWhere('receiver_id', $doctor->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($message) use ($doctor) {
                return $message->sender_id === $doctor->id ? $message->receiver_id : $message->sender_id;
            });

        $result = [];
        foreach ($discussions as $userId => $messages) {
            $lastMessage = $messages->last();
            $unreadCount = $messages->where('is_read', false)->where('receiver_id', $doctor->id)->count();
            
            $user = $messages->first()->sender_id === $doctor->id ? $messages->first()->receiver : $messages->first()->sender;

            $result[] = [
                'user_id' => $userId,
                'user_first_name' => $user->first_name,
                'user_last_name' => $user->last_name,
                'user_photo' => $user->photo ? asset('storage/' . $user->photo) : null,
                'last_message' => $lastMessage->message,
                'last_message_time' => $lastMessage->created_at->format('H:i'),
                'unread_count' => $unreadCount,
            ];
        }

        // Statistiques des messages
        $totalMessages = Message::where('sender_id', $doctor->id)->count();
        $unreadMessages = Message::where('receiver_id', $doctor->id)->where('is_read', false)->count();
        $todayMessages = Message::where('sender_id', $doctor->id)
            ->whereDate('created_at', today())
            ->count();

        return view('doctor.messages', compact('result', 'totalMessages', 'unreadMessages', 'todayMessages', 'doctor'));
    }

    /**
     * Créer un nouveau message pour les médecins
     */
    public function createMessage($patientId = null)
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer les patients du service du médecin
        $patients = \App\Models\User::whereHas('appointments', function($query) use ($doctor) {
            $query->where('service_id', $doctor->service_id);
        })->get();

        $selectedPatient = null;
        if ($patientId) {
            $selectedPatient = \App\Models\User::find($patientId);
        }

        return view('doctor.messages.create', compact('patients', 'selectedPatient', 'doctor'));
    }

    /**
     * Afficher la conversation avec un utilisateur spécifique
     */
    public function chatWithUser($userId)
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que l'utilisateur existe
        $user = \App\Models\User::find($userId);
        if (!$user) {
            abort(404, 'Utilisateur non trouvé');
        }

        // Récupérer les messages entre le médecin et l'utilisateur
        $messages = Message::where(function($query) use ($doctor, $userId) {
                $query->where('sender_id', $doctor->id)
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function($query) use ($doctor, $userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $doctor->id);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Marquer les messages comme lus
        Message::where('sender_id', $userId)
            ->where('receiver_id', $doctor->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('doctor.messages.chat', compact('messages', 'user', 'doctor'));
    }

}