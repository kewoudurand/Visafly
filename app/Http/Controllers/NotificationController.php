<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    /**
     * ✅ Récupérer les notifications non lues (AJAX)
     */
    public function getUnread()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
                             ->unread()
                             ->recent()
                             ->take(10)
                             ->get();

        $unreadCount = $user->notifications()->unread()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * 📋 Afficher toutes les notifications
     */
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()
                             ->recent()
                             ->paginate(20);

        $unreadCount = $user->notifications()->unread()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * ✅ Marquer comme lue
     */
    public function markAsRead(Notification $notification)
    {
        // Vérifier que c'est bien pour l'utilisateur
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * ✅ Marquer toutes comme lues
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        $user->notifications()
             ->unread()
             ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * 🗑️ Supprimer une notification
     */
    public function delete(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }

    /**
     * 🗑️ Supprimer toutes les notifications
     */
    public function deleteAll()
    {
        $user = Auth::user();
        $user->notifications()->delete();

        return response()->json(['success' => true]);
    }

    /**
     * 📊 Nombre de notifications non lues
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->notifications()->unread()->count();
        return response()->json(['count' => $count]);
    }
}