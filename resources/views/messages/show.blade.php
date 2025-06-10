@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('messages.index') }}" 
                   class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h1>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">
                    {{ $messages->total() }} mensaje(s)
                </span>
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

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar con contactos recientes -->
            <div class="lg:col-span-1 order-2 lg:order-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Otros Contactos</h2>
                    
                    @if($recentContacts->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentContacts as $contact)
                                @if($contact->id !== $user->id)
                                    <a href="{{ route('messages.show', $contact) }}" 
                                       class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                        <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center text-white font-semibold mr-3 text-sm">
                                            {{ substr($contact->name, 0, 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-medium text-gray-800 text-sm truncate">{{ $contact->name }}</p>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">No hay otros contactos</p>
                    @endif

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('messages.create') }}" 
                           class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors text-sm flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i>
                            Nuevo Mensaje
                        </a>
                    </div>
                </div>
            </div>

            <!-- Área principal de conversación -->
            <div class="lg:col-span-3 order-1 lg:order-2">
                <!-- Mensajes -->
                <div class="bg-white rounded-lg shadow-sm">
                    <!-- Header de la conversación -->
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Conversación</h2>
                            @if($messages->count() > 0)
                                <div class="text-sm text-gray-500">
                                    Página {{ $messages->currentPage() }} de {{ $messages->lastPage() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Lista de mensajes -->
                    <div class="p-6">
                        @if($messages->count() > 0)
                            <div class="space-y-6">
                                @foreach($messages->reverse() as $message)
                                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                                        <div class="max-w-xs lg:max-w-md">
                                            <!-- Mensaje -->
                                            <div class="relative {{ $message->sender_id === auth()->id() 
                                                ? 'bg-blue-600 text-white' 
                                                : 'bg-gray-100 text-gray-800' }} rounded-lg px-4 py-3">
                                                
                                                @if($message->subject)
                                                    <div class="font-semibold text-sm mb-2 {{ $message->sender_id === auth()->id() ? 'text-blue-100' : 'text-gray-600' }}">
                                                        {{ $message->subject }}
                                                    </div>
                                                @endif
                                                
                                                <p class="text-sm leading-relaxed">{{ $message->content }}</p>
                                                
                                                <!-- Timestamp -->
                                                <div class="mt-2 text-xs {{ $message->sender_id === auth()->id() ? 'text-blue-200' : 'text-gray-500' }}">
                                                    {{ $message->created_at->format('d/m/Y H:i') }}
                                                    @if($message->sender_id === auth()->id() && $message->read_at)
                                                        <span class="ml-2">
                                                            <i class="fas fa-check-double"></i> Leído
                                                        </span>
                                                    @elseif($message->sender_id === auth()->id())
                                                        <span class="ml-2">
                                                            <i class="fas fa-check"></i> Enviado
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Información del remitente -->
                                            <div class="mt-1 text-xs text-gray-500 {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                                                {{ $message->sender_id === auth()->id() ? 'Tú' : $message->sender->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Paginación -->
                            @if($messages->hasPages())
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    {{ $messages->links() }}
                                </div>
                            @endif
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-comments text-gray-400 text-4xl mb-4"></i>
                                <p class="text-gray-500">No hay mensajes en esta conversación</p>
                                <p class="text-gray-400 text-sm">¡Envía el primer mensaje!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Formulario para responder -->
                <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Responder</h3>
                    
                    <form action="{{ route('messages.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        
                        <!-- Asunto (opcional) -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Asunto (opcional)
                            </label>
                            <input type="text" 
                                   name="subject" 
                                   id="subject" 
                                   value="{{ old('subject') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subject') border-red-500 @enderror"
                                   placeholder="Asunto del mensaje">
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contenido -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Mensaje *
                            </label>
                            <textarea name="content" 
                                      id="content" 
                                      rows="4" 
                                      required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('content') border-red-500 @enderror"
                                      placeholder="Escribe tu respuesta...">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Botón enviar -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition-colors flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Enviar Respuesta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-scroll to bottom of messages on page load
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.querySelector('.space-y-6');
    if (messagesContainer) {
        messagesContainer.scrollIntoView({ behavior: 'smooth', block: 'end' });
    }
});

// Auto-resize textarea
document.getElementById('content').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});

// Focus on textarea when page loads
document.getElementById('content').focus();
</script>
@endpush
@endsection