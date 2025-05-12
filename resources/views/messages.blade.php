@extends('layouts.home') 
{{-- Extiende el layout principal llamado "home" --}}

@section('content')
<main class="container mx-auto py-8 px-6">

    <!-- Título principal -->
    <h1 class="text-3xl font-bold text-center mb-8">Tus Mensajes</h1>

    <!-- Mostrar mensaje de éxito si existe una sesión con el mensaje 'success' -->
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Sección del formulario para enviar nuevos mensajes -->
    <section class="bg-white shadow rounded-lg p-6 max-w-lg mx-auto mb-8">
        <h2 class="text-2xl font-semibold text-center mb-4">Enviar Mensaje</h2>

        <!-- Formulario de envío de mensaje -->
        <form action="{{ route('send-message') }}" method="POST">
            @csrf {{-- Token CSRF para proteger contra ataques de falsificación de solicitudes --}}

            <div class="space-y-4">

                <!-- Campo para ingresar el ID del destinatario -->
                <div>
                    <label for="receiver_id" class="block text-gray-700 font-medium">Destinatario (ID)</label>
                    <input type="number" id="receiver_id" name="receiver_id" 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                           required>
                </div>

                <!-- Campo para escribir el contenido del mensaje -->
                <div>
                    <label for="content" class="block text-gray-700 font-medium">Mensaje</label>
                    <textarea id="content" name="content" rows="4" 
                              class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                              required></textarea>
                </div>

                <!-- Botón para enviar el formulario -->
                <button type="submit" 
                        class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-400">
                    Enviar
                </button>
            </div>
        </form>
    </section>

    <!-- Sección para mostrar los mensajes recibidos y enviados -->
    <section class="max-w-4xl mx-auto space-y-8">

        <!-- Lista de mensajes recibidos -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-blue-600">Recibidos</h2>

            @forelse($received as $message)
                <!-- Cada mensaje recibido -->
                <div class="border-b py-3">
                    <p><strong>De:</strong> {{ $message->sender->name }}</p> {{-- Nombre del remitente --}}
                    <p class="text-gray-700">{{ Str::limit($message->content, 150) }}</p> {{-- Mensaje truncado --}}
                    <p class="text-sm text-gray-400">{{ $message->created_at->format('d/m/Y H:i') }}</p> {{-- Fecha de envío --}}
                </div>
            @empty
                <!-- Mensaje en caso de no tener mensajes recibidos -->
                <p class="text-gray-500">No has recibido mensajes.</p>
            @endforelse
        </div>

        <!-- Lista de mensajes enviados -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-green-600">Enviados</h2>

            @forelse($sent as $message)
                <!-- Cada mensaje enviado -->
                <div class="border-b py-3">
                    <p><strong>Para:</strong> {{ $message->receiver->name }}</p> {{-- Nombre del destinatario --}}
                    <p class="text-gray-700">{{ Str::limit($message->content, 150) }}</p> {{-- Mensaje truncado --}}
                    <p class="text-sm text-gray-400">{{ $message->created_at->format('d/m/Y H:i') }}</p> {{-- Fecha de envío --}}
                </div>
            @empty
                <!-- Mensaje en caso de no haber enviado ningún mensaje -->
                <p class="text-gray-500">No has enviado mensajes aún.</p>
            @endforelse
        </div>

    </section>
</main>
@endsection
