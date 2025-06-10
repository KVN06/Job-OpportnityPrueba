<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        try {
            $filters = $request->only([
                'read',
                'unread',
                'type',
                'date_from',
                'date_to',
                'per_page'
            ]);

            $notifications = $this->notificationService->getFilteredNotifications(Auth::user(), $filters);
            return view('notifications.index', compact('notifications'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar las notificaciones: ' . $e->getMessage());
        }
    }

    public function markAsRead(Notification $notification)
    {
        try {
            $this->notificationService->markAsRead($notification, Auth::user());
            return response()->json(['message' => 'Notificación marcada como leída']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function markAllAsRead()
    {
        try {
            $this->notificationService->markAllAsRead(Auth::user());
            return response()->json(['message' => 'Todas las notificaciones marcadas como leídas']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(Notification $notification)
    {
        try {
            $this->notificationService->delete($notification, Auth::user());
            return response()->json(['message' => 'Notificación eliminada']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function deleteAllRead()
    {
        try {
            $this->notificationService->deleteAllRead(Auth::user());
            return response()->json(['message' => 'Notificaciones leídas eliminadas']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
