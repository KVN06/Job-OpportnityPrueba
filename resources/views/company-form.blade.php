@extends('layouts.new-user')

@section('content')
<form action="{{ route('agg-company') }}" method="POST">
    @csrf
    <main class="container mx-auto py-8 px-6">
        <h1 class="text-3xl font-bold text-center mb-8">Formulario de Empresa</h1>
        <section class="bg-white shadow rounded-lg p-6 max-w-lg mx-auto">
            <div class="space-y-4">
                <div>
                    <label for="company_name" class="block text-gray-700 font-medium">Nombre de la Empresa</label>
                    <input type="text" id="company_name" name="company_name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label for="description" class="block text-gray-700 font-medium">Descripción</label>
                    <textarea id="description" name="description" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-400">Registrar Empresa</button>
            </div>
        </section>
    </main>
</form>
@endsection
