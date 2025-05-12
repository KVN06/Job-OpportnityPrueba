<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Mostrar el formulario para enviar un nuevo mensaje
    public function create()
    {
        return view('message-form');
    }

    // Enviar un mensaje a otro usuario
    public function send_message(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'receiver_id' => 'required|integer|exists:users,id', // El destinatario debe existir
            'content' => 'required|string|max:500', // El mensaje no debe superar 500 caracteres
        ]);

        // Crear el mensaje
        $message = new Message();
        $message->sender_id = Auth::id(); // Usuario autenticado como emisor
        $message->receiver_id = $validated['receiver_id'];
        $message->content = $validated['content'];

        // Guardar el mensaje
        $message->save();

        // Redirigir con mensaje de éxito
        return redirect()->route('messages')->with('success', 'Mensaje enviado con éxito');
    }

    // Mostrar los mensajes enviados y recibidos del usuario autenticado
    public function index()
    {
        $userId = Auth::id();

        // Mensajes recibidos
        $received = Message::where('receiver_id', $userId)->get();

        // Mensajes enviados
        $sent = Message::where('sender_id', $userId)->get();

        // Mostrar vista con los mensajes
        return view('messages', compact('received', 'sent'));
    }
}
