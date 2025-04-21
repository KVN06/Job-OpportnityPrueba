@extends('layouts.new-user')

@section('content')
<form action="{{ route('agg-unemployed') }}" method="POST">
    @csrf
    <main class="container mx-auto py-8 px-6">
        <h1 class="text-3xl font-bold text-center mb-8">Formulario de Desempleado</h1>
        <section class="bg-white shadow rounded-lg p-6 max-w-lg mx-auto">
            <div class="space-y-4">
                <div>
                    <label for="profession" class="block text-gray-700 font-medium">Profesión</label>
                    <input type="text" id="profession" name="profession" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="experience" class="block text-gray-700 font-medium">Experiencia</label>
                    <textarea id="experience" name="experience" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>
                <div>
                    <label for="location" class="block text-gray-700 font-medium">Ubicación</label>
                    <input type="text" id="location" name="location" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-400">Registrar Desempleado</button>
            </div>
        </section>
    </main>
</form>
@endsection
