<?php declare(strict_types=1); 

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

    /**
     * Afficher les notifications pour les médecins
     */
    public function doctorNotifications()
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        // Récupérer les notifications du médecin
        $notifications = Notification::where('user_id', $doctor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Statistiques des notifications
        $totalNotifications = Notification::where('user_id', $doctor->id)->count();
        $unreadNotifications = Notification::where('user_id', $doctor->id)
            ->where('is_read', false)
            ->count();
        $todayNotifications = Notification::where('user_id', $doctor->id)
            ->whereDate('created_at', today())
            ->count();
        $weekNotifications = Notification::where('user_id', $doctor->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        return view('doctor.notifications', compact(
            'notifications',
            'totalNotifications',
            'unreadNotifications',
            'todayNotifications',
            'weekNotifications',
            'doctor'
        ));
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        Notification::where('user_id', $doctor->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        if (request()->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Toutes les notifications ont été marquées comme lues'
            ]);
        }

        return redirect()->back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }

    /**
     * Supprimer toutes les notifications lues
     */
    public function deleteAllRead()
    {
        $doctor = Auth::user();

        if (!$doctor || !$doctor->hasRole('Doctor')) {
            abort(403, 'Accès non autorisé');
        }

        $deletedCount = Notification::where('user_id', $doctor->id)
            ->where('is_read', true)
            ->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'status' => true,
                'message' => "{$deletedCount} notifications lues ont été supprimées"
            ]);
        }

        return redirect()->back()->with('success', "{$deletedCount} notifications lues ont été supprimées");
    }
}
