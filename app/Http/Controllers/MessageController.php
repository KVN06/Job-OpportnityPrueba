<?php

namespace App\Http\Controllers;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function create()
    {
        return view('message-form');
    }

    public function send_message(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'receiver_id' => 'required|integer|exists:users,id', // Validar que el destinatario exista
            'content' => 'required|string|max:500', // Limitar el contenido del mensaje
        ]);

        // Crear el mensaje
        $message = new Message();
        $message->sender_id = Auth::id(); 
        $message->receiver_id = $validated['receiver_id'];
        $message->content = $validated['content'];

        // Guardar el mensaje en la base de datos
        $message->save();

        // Redirigir a la página de mensajes
        return redirect()->route('messages')->with('success', 'Mensaje enviado con éxito');
    }
    

    public function index(){
    $userId = Auth::id();

    // Obtener los mensajes recibidos
    $received = Message::where('receiver_id', $userId)->get();

    // Obtener los mensajes enviados
    $sent = Message::where('sender_id', $userId)->get();

    // Pasar las variables a la vista
    return view('messages', compact('received', 'sent'));
    }


}