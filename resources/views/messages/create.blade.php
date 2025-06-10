@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Nuevo Mensaje</h1>
            <a href="{{ route('messages.index') }}" 
               class="text-gray-600 hover:text-gray-800 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a mensajes
            </a>
        </div>

        <!-- Mensajes Flash -->
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                    <p class="text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('messages.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Destinatario -->
                <div>
                    <label for="receiver_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Destinatario *
                    </label>
                    <select name="receiver_id" id="receiver_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('receiver_id') border-red-500 @enderror">
                        <option value="">Seleccionar destinatario</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('receiver_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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
                              rows="8" 
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none @error('content') border-red-500 @enderror"
                              placeholder="Escribe tu mensaje aquí...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Máximo 5000 caracteres</p>
                </div>

                <!-- Botones -->
                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('messages.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancelar
                    </a>
                    
                    <button type="submit" 
                            class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Enviar Mensaje
                    </button>
                </div>
            </form>
        </div>

        <!-- Contactos recientes -->
        @if($recentContacts->count() > 0)
            <div class="mt-8 bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Contactos Recientes</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($recentContacts as $contact)
                        <button type="button" 
                                onclick="selectContact({{ $contact->id }}, '{{ $contact->name }}')"
                                class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-left">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                {{ substr($contact->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">{{ $contact->name }}</p>
                                <p class="text-xs text-gray-500">{{ $contact->email }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function selectContact(userId, userName) {
    const select = document.getElementById('receiver_id');
    select.value = userId;
    
    // Opcional: mostrar confirmación visual
    const option = select.querySelector(`option[value="${userId}"]`);
    if (option) {
        option.selected = true;
        
        // Mostrar feedback visual
        const feedback = document.createElement('div');
        feedback.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        feedback.textContent = `Destinatario seleccionado: ${userName}`;
        document.body.appendChild(feedback);
        
        setTimeout(() => {
            feedback.remove();
        }, 2000);
    }
}

// Auto-resize textarea
document.getElementById('content').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});

// Character counter
document.getElementById('content').addEventListener('input', function() {
    const maxLength = 5000;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    let counter = document.getElementById('char-counter');
    if (!counter) {
        counter = document.createElement('p');
        counter.id = 'char-counter';
        counter.className = 'mt-1 text-sm';
        this.parentNode.appendChild(counter);
    }
    
    counter.textContent = `${currentLength}/${maxLength} caracteres`;
    counter.className = remaining < 100 ? 'mt-1 text-sm text-red-500' : 'mt-1 text-sm text-gray-500';
});
</script>
@endpush
@endsection