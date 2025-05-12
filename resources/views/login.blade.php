@extends('layouts.new-user') 
{{-- Extiende la plantilla base para nuevos usuarios --}}

@section('content')
<!-- Formulario de inicio de sesión -->
<form action="{{ route('inicia-sesion') }}" method="POST">
    @csrf {{-- Token de seguridad contra ataques CSRF --}}
    
    <main class="flex-grow flex items-center justify-center">
        <!-- Contenedor principal centrado -->
        <div class="bg-white shadow rounded-lg p-8 max-w-md w-full">
            <h1 class="text-2xl font-bold mb-6 text-center">Inicio de Sesión</h1>

            <!-- Campo de correo electrónico -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Correo Electrónico</label>
                <input type="email" id="emailInput" name="email" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Campo de contraseña -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium">Contraseña</label>
                <input type="password" id="passwordInput" name="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Botón para enviar el formulario -->
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-400">CONTINUAR</button>

            <!-- Enlace a la página de registro -->
            <div class="mt-4 text-center">
                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">¿No tienes cuenta? Regístrate</a>
            </div>
        </div>
    </main>
</form>
@endsection
