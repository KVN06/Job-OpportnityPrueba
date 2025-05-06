@extends('layouts.new-user')

@section('content')
<form action="{{ route('create-user') }}" method="POST">
    @csrf
    <main class="container mx-auto py-8 px-6">
        <h1 class="text-3xl font-bold text-center mb-8">Registro de Usuario</h1>
        <section class="bg-white shadow rounded-lg p-6 max-w-lg mx-auto">
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-gray-700 font-medium">Nombre Completo</label>
                    <input type="text" id="name" name="name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-medium">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="password" name="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    <p class="mt-1 text-sm text-gray-500">Mínimo 8 caracteres</p>
                </div>
                <div>
                    <label for="type" class="block text-gray-700 font-medium">Tipo de Usuario</label>
                    <select name="type" id="type" required>
                        <option value="">Seleccione una opción</option>
                        <option value="unemployed">Desempleado</option>
                        <option value="company">Empresa</option>
                    </select>                  
                    <br>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-400">Registrarse</button>
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