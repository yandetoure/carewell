<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())->get();
        return response()->json(['notifications' => $notifications]);
    }
    
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification && $notification->user_id == Auth::id()) {
            $notification->is_read = true;
            $notification->save();
            return response()->json(['message' => 'Notification marquée comme lue.']);
        }
        return response()->json(['message' => 'Notification introuvable.'], 404);
    }

    // Nouvelle méthode pour supprimer une notification
    public function destroy($id)
    {
        $notification = Notification::find($id);

        if ($notification && $notification->user_id == Auth::id()) {
            $notification->delete();  // Supprimer la notification de la base de données
            return response()->json(['message' => 'Notification supprimée avec succès.']);
        }

        return response()->json(['message' => 'Notification introuvable.'], 404);
    }
}
