@extends('layouts.new-user') {{-- Extiende el layout principal para un nuevo usuario --}}

@section('content')
<form action="{{ route('create-user') }}" method="POST">
    @csrf {{-- Token CSRF para proteger contra ataques de falsificación de solicitudes --}}
    
    <main class="container mx-auto py-8 px-6">
        <!-- Título principal de la página -->
        <h1 class="text-3xl font-bold text-center mb-8">Registro de Usuario</h1>
        
        <!-- Sección del formulario de registro -->
        <section class="bg-white shadow rounded-lg p-6 max-w-lg mx-auto">
            <div class="space-y-4">
                
                <!-- Campo para ingresar el nombre completo del usuario -->
                <div>
                    <label for="name" class="block text-gray-700 font-medium">Nombre Completo</label>
                    <input type="text" id="name" name="name" 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                           required>
                </div>
                
                <!-- Campo para ingresar el correo electrónico -->
                <div>
                    <label for="email" class="block text-gray-700 font-medium">Correo Electrónico</label>
                    <input type="email" id="email" name="email" 
                           class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                           required>
                </div>
                
                <!-- Campo para ingresar la contraseña del usuario -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="password" name="password" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                           required>
                </div>
                
                <!-- Campo para seleccionar el tipo de usuario -->
                <div>
                    <label for="type" class="block text-gray-700 font-medium">Tipo de Usuario</label>
                    <select name="type" id="type" required>
                        <!-- Opciones para el tipo de usuario: Cesante o Empresa -->
                        <option value="">Seleccione una opción</option>
                        <option value="unemployed">Cesante</option>
                        <option value="company">Empresa</option>
                    </select>
                </div>
                
                <!-- Botón para enviar el formulario -->
                <button type="submit" 
                        class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-400">
                    Registrarse
                </button>

                <!-- Enlace para redirigir al usuario a la página de inicio de sesión si ya tiene cuenta -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                        ¿Ya tienes una cuenta? Inicia sesión
                    </a>
                </div>
            </div>
        </section>
    </main>
</form>
@endsection
