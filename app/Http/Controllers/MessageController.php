<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Services\MessageService;
use App\Http\Requests\MessageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    // Mostrar los mensajes enviados y recibidos del usuario autenticado
    public function index()
    {
        try {
            $conversations = $this->messageService->getUserConversations(Auth::user());
            $unreadCount = $this->messageService->getUnreadCount(Auth::user());
            $recentContacts = $this->messageService->getRecentContacts(Auth::user());
            
            // Obtener usuarios para el formulario de envío
            $users = User::where('id', '!=', Auth::id())
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get();
            
            // Obtener mensajes recibidos y enviados
            $received = Message::where('receiver_id', Auth::id())
                ->with('sender')
                ->orderByDesc('created_at')
                ->take(10)
                ->get();
                
            $sent = Message::where('sender_id', Auth::id())
                ->with('receiver')
                ->orderByDesc('created_at')
                ->take(10)
                ->get();

            return view('messages.index', compact('conversations', 'unreadCount', 'recentContacts', 'users', 'received', 'sent'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar los mensajes: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        try {
            $messages = $this->messageService->getConversation(Auth::user(), $user);
            $recentContacts = $this->messageService->getRecentContacts(Auth::user());

            // Marcar mensajes como leídos
            $messages->each(function ($message) {
                if ($message->receiver_id === Auth::id() && !$message->read_at) {
                    $this->messageService->markAsRead($message, Auth::user());
                }
            });

            return view('messages.show', compact('messages', 'user', 'recentContacts'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar la conversación: ' . $e->getMessage());
        }
    }

    // Mostrar el formulario para enviar un nuevo mensaje
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();
        
        $recentContacts = $this->messageService->getRecentContacts(Auth::user());

        return view('messages.create', compact('users', 'recentContacts'));
    }

    // Enviar un mensaje a otro usuario
    public function store(MessageRequest $request)
    {
        try {
            $receiver = User::findOrFail($request->receiver_id);
            
            $message = $this->messageService->sendMessage(
                Auth::user(),
                $receiver,
                $request->validated()
            );

            return redirect()
                ->route('messages.show', $receiver)
                ->with('success', 'Mensaje enviado exitosamente.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al enviar el mensaje: ' . $e->getMessage());
        }
    }

    public function destroy(Message $message)
    {
        try {
            $this->messageService->deleteMessage($message, Auth::user());
            return back()->with('success', 'Mensaje eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el mensaje: ' . $e->getMessage());
        }
    }

    public function markAllRead()
    {
        try {
            $count = $this->messageService->markAllAsRead(Auth::user());
            return back()->with('success', "{$count} mensajes marcados como leídos.");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al marcar los mensajes como leídos.');
        }
    }

    public function search(Request $request)
    {
        try {
            $messages = $this->messageService->searchMessages(Auth::user(), $request->q);
            return view('messages.search', compact('messages'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al buscar mensajes: ' . $e->getMessage());
        }
    }
}
