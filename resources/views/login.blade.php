@extends('layouts.new-user')

@section('content')
<form action="{{ route('inicia-sesion') }}" method="POST">
@csrf
<main class="flex-grow flex items-center justify-center">
    <div class="bg-white shadow rounded-lg p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold mb-6 text-center">Inicio de Sesión</h1>
        <form>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Correo Electrónico</label>
                <input type="email" id="emailInput" name="email" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium">Contraseña</label>
                <input type="password" id="passwordInput" name="password" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-400">CONTINUAR</button>
        </form>
        <a href="" class="block text-center text-blue-500 mt-4 hover:underline">¿Olvidaste tu contraseña?</a>
    </div>
</main>
</form>
@endsection

@csrf