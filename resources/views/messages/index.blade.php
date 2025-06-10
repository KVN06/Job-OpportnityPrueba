@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Mensajes</h1>
            <div class="flex items-center space-x-4">
                @if($unreadCount > 0)
                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-sm">
                        {{ $unreadCount }} sin leer
                    </span>
                @endif
                <a href="{{ route('messages.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Mensaje
                </a>
            </div>
        </div>

        <!-- Mensajes Flash -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 mr-3"></i>
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar con contactos recientes -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Contactos Recientes</h2>
                    
                    @if($recentContacts->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentContacts as $contact)
                                <a href="{{ route('messages.show', $contact) }}" 
                                   class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                        {{ substr($contact->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-800">{{ $contact->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $contact->email }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No hay contactos recientes</p>
                    @endif

                    <!-- Formulario rápido para nuevo mensaje -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-md font-medium text-gray-800 mb-3">Envío Rápido</h3>
                        <form action="{{ route('messages.store') }}" method="POST" class="space-y-3">
                            @csrf
                            <select name="receiver_id" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Seleccionar destinatario</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('receiver_id')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror

                            <textarea name="content" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm resize-none"
                                      placeholder="Escribe tu mensaje...">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-xs">{{ $message }}</p>
                            @enderror

                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-sm">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Enviar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Área principal de mensajes -->
            <div class="lg:col-span-2">
                <!-- Conversaciones -->
                @if($conversations->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Conversaciones</h2>
                        <div class="space-y-4">
                            @foreach($conversations as $conversation)
                                @php
                                    $otherUser = $conversation->sender_id === auth()->id() 
                                        ? $conversation->receiver 
                                        : $conversation->sender;
                                @endphp
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-3">
                                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                                {{ substr($otherUser->name, 0, 1) }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    <h3 class="font-medium text-gray-800">{{ $otherUser->name }}</h3>
                                                    @if($conversation->read_at === null && $conversation->receiver_id === auth()->id())
                                                        <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs">Nuevo</span>
                                                    @endif
                                                </div>
                                                <p class="text-gray-600 mt-1">{{ Str::limit($conversation->content, 100) }}</p>
                                                <p class="text-sm text-gray-400 mt-2">
                                                    {{ $conversation->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                        <a href="{{ route('messages.show', $otherUser) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Ver conversación
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $conversations->links() }}
                        </div>
                    </div>
                @endif

                <!-- Mensajes recientes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mensajes recibidos -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Recibidos</h2>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                {{ $received->count() }}
                            </span>
                        </div>

                        @if($received->count() > 0)
                            <div class="space-y-3">
                                @foreach($received as $message)
                                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="font-medium text-gray-800 text-sm">{{ $message->sender->name }}</p>
                                            <span class="text-xs text-gray-400">
                                                {{ $message->created_at->format('d/m H:i') }}
                                            </span>
                                        </div>
                                        <p class="text-gray-600 text-sm">{{ Str::limit($message->content, 80) }}</p>
                                        @if($message->read_at === null)
                                            <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded text-xs mt-1">
                                                Sin leer
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-inbox text-gray-400 text-3xl mb-3"></i>
                                <p class="text-gray-500">No has recibido mensajes</p>
                            </div>
                        @endif
                    </div>

                    <!-- Mensajes enviados -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Enviados</h2>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                {{ $sent->count() }}
                            </span>
                        </div>

                        @if($sent->count() > 0)
                            <div class="space-y-3">
                                @foreach($sent as $message)
                                    <div class="border-l-4 border-green-500 pl-4 py-2">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="font-medium text-gray-800 text-sm">Para: {{ $message->receiver->name }}</p>
                                            <span class="text-xs text-gray-400">
                                                {{ $message->created_at->format('d/m H:i') }}
                                            </span>
                                        </div>
                                        <p class="text-gray-600 text-sm">{{ Str::limit($message->content, 80) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-paper-plane text-gray-400 text-3xl mb-3"></i>
                                <p class="text-gray-500">No has enviado mensajes</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones rápidas -->
        @if($unreadCount > 0)
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                        <p class="text-blue-800">Tienes {{ $unreadCount }} mensaje(s) sin leer</p>
                    </div>
                    <form action="{{ route('messages.mark-all-read') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm">
                            Marcar todos como leídos
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
