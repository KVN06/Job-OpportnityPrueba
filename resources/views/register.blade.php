@extends('layouts.new-user')

@section('content')
<form action="{{ route('register') }}" method="POST" class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-lg mt-12 mb-12">
    @csrf

    <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Registro de Usuario</h1>

    {{-- Nombre Completo --}}
    <div class="mb-6">
        <label for="name" class="block text-gray-700 font-semibold mb-2">Nombre Completo</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            placeholder="Tu nombre completo"
            required
            autofocus
            autocomplete="name"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 placeholder-gray-400
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        >
        @error('name')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Correo Electrónico --}}
    <div class="mb-6">
        <label for="email" class="block text-gray-700 font-semibold mb-2">Correo Electrónico</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="ejemplo@correo.com"
            required
            autocomplete="email"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 placeholder-gray-400
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        >
        @error('email')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Contraseña con "ojito" --}}
    <div class="mb-8 relative">
        <label for="password" class="block text-gray-700 font-semibold mb-2">Contraseña</label>
        <input
            type="password"
            id="password"
            name="password"
            placeholder="Mínimo 8 caracteres"
            required
            autocomplete="new-password"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-12 text-gray-800 placeholder-gray-400
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        >
        <button type="button" id="togglePassword" class="absolute right-3 top-9 text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Mostrar u ocultar contraseña">
            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
        </button>
        <p class="text-sm text-gray-500 mt-1">Mínimo 8 caracteres</p>
        @error('password')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Confirmar Contraseña --}}
    <div class="mb-8">
        <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Confirmar Contraseña</label>
        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            placeholder="Confirma tu contraseña"
            required
            autocomplete="new-password"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 placeholder-gray-400
                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
        >
        @error('password_confirmation')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Tipo de Usuario con tarjetas visuales --}}
    <div class="mb-8">
        <label class="block text-gray-700 font-semibold mb-4">Selecciona tu rol</label>
        <div class="grid grid-cols-2 gap-4">
            {{-- Tarjeta Cesante --}}
            <label class="cursor-pointer group">
                <input type="radio" name="type" value="unemployed" class="peer hidden" {{ old('type') == 'unemployed' ? 'checked' : '' }} required>
                <div class="border-2 rounded-lg p-4 hover:border-blue-500 transition-all duration-200 h-full
                           group-hover:shadow-md group-hover:-translate-y-1
                           peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-lg
                           {{ old('type') == 'unemployed' ? 'border-blue-500 bg-blue-50 shadow-lg' : 'border-gray-200' }}">
                    <div class="flex flex-col items-center text-center">
                        <div class="rounded-full bg-blue-100 p-3 mb-3 transform transition-transform group-hover:scale-110">
                            <i class="fas fa-user-tie text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Cesante</h3>
                        <p class="text-sm text-gray-600">Encuentra tu próxima oportunidad laboral y conecta con empresas que buscan tu talento</p>
                        <!-- Indicador de selección -->
                        <div class="absolute top-3 right-3 transform scale-0 transition-transform peer-checked:scale-100
                                  text-blue-500 bg-blue-100 rounded-full p-1">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </label>

            {{-- Tarjeta Empresa --}}
            <label class="cursor-pointer group">
                <input type="radio" name="type" value="company" class="peer hidden" {{ old('type') == 'company' ? 'checked' : '' }}>
                <div class="border-2 rounded-lg p-4 hover:border-blue-500 transition-all duration-200 h-full
                           group-hover:shadow-md group-hover:-translate-y-1
                           peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-lg
                           {{ old('type') == 'company' ? 'border-blue-500 bg-blue-50 shadow-lg' : 'border-gray-200' }}">
                    <div class="flex flex-col items-center text-center">
                        <div class="rounded-full bg-blue-100 p-3 mb-3 transform transition-transform group-hover:scale-110">
                            <i class="fas fa-building text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Empresa</h3>
                        <p class="text-sm text-gray-600">Publica ofertas de trabajo y encuentra los mejores talentos para tu organización</p>
                        <!-- Indicador de selección -->
                        <div class="absolute top-3 right-3 transform scale-0 transition-transform peer-checked:scale-100
                                  text-blue-500 bg-blue-100 rounded-full p-1">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </label>
        </div>
        @error('type')
            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- Botón Registrar --}}
    <button
        type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg shadow-md
               focus:outline-none focus:ring-4 focus:ring-blue-300 transition">
        Registrarse
    </button>

    {{-- Link a Login --}}
    <p class="mt-6 text-center text-gray-600">
        ¿Ya tienes una cuenta?
        <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Inicia sesión</a>
    </p>
</form>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordInput = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    togglePassword.addEventListener('click', () => {
        // Alternar tipo de input
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Cambiar icono
        if(type === 'text'){
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.269-2.944-9.543-7a9.966 9.966 0 012.784-4.412m1.233-1.23A9.96 9.96 0 0112 5c4.478 0 8.269 2.944 9.543 7a10.07 10.07 0 01-4.132 5.128M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18" />
            `;
        } else {
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            `;
        }
    });
</script>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleCards = document.querySelectorAll('input[name="type"]');
    const form = document.querySelector('form');

    // Función para validar la selección del rol antes de enviar
    form.addEventListener('submit', function(e) {
        const roleSelected = Array.from(roleCards).some(radio => radio.checked);
        if (!roleSelected) {
            e.preventDefault();
            alert('Por favor, selecciona un rol (Cesante o Empresa)');
            document.querySelector('.mb-8').scrollIntoView({ behavior: 'smooth' });
        }
    });

    // Agregar efecto de clic y validación visual
    roleCards.forEach(radio => {
        radio.addEventListener('change', function() {
            // Reproducir efecto de sonido sutil (opcional)
            const audio = new Audio('data:audio/mp3;base64,SUQzBAAAAAAAI1RTU0UAAAAPAAADTGF2ZjU4LjI5LjEwMAAAAAAAAAAAAAAA//OEAAAAAAAAAAAAAAAAAAAAAAAASW5mbwAAAA8AAAACAAABIADAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDV1dXV1dXV1dXV1dXV1dXV1dXV1dXV1dXV6urq6urq6urq6urq6urq6urq6urq6urq6v////////////////////////////////8AAAAATGF2YzU4LjU0AAAAAAAAAAAAAAAAJAAAAAAAAAAAASDs4EqWAAAAAAAAAAAAAAAAAAAA//MUZAAAAAGkAAAAAAAAA0gAAAAATEFN//MUZAMAAAGkAAAAAAAAA0gAAAAARTMz//MUZAYAAAGkAAAAAAAAA0gAAAAANVVV');
            audio.volume = 0.2;
            audio.play();

            // Añadir clase de animación a la tarjeta seleccionada
            const selectedCard = this.nextElementSibling;
            selectedCard.classList.add('scale-105');
            setTimeout(() => selectedCard.classList.remove('scale-105'), 200);

            // Mostrar mensaje de confirmación
            const roleType = this.value === 'unemployed' ? 'Cesante' : 'Empresa';
            const confirmMessage = document.createElement('div');
            confirmMessage.className = 'text-sm text-blue-600 mt-2 text-center transition-opacity duration-500';
            confirmMessage.textContent = `Has seleccionado el rol de ${roleType}`;
            
            // Remover mensaje anterior si existe
            const prevMessage = document.querySelector('.text-sm.text-blue-600.mt-2');
            if (prevMessage) prevMessage.remove();
            
            // Insertar nuevo mensaje
            this.closest('.mb-8').appendChild(confirmMessage);
            setTimeout(() => confirmMessage.remove(), 2000);
        });
    });
});
</script>
@endpush
@endsection
