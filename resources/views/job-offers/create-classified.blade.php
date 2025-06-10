@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Publicar Clasificado</h1>

        <form action="{{ route('job-offers.store') }}" method="POST" class="bg-white rounded-lg shadow p-6">
            @csrf
            <input type="hidden" name="offer_type" value="classified">

            <!-- Título -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Título del clasificado</label>
                <input type="text" id="title" name="title" required
                       value="{{ old('title') }}"
                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                       placeholder="Ej: Profesor particular de matemáticas">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción detallada</label>
                <textarea id="description" name="description" required rows="6"
                          class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                          placeholder="Describe el servicio que ofreces, tu experiencia, horarios disponibles, etc.">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Ubicación -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
                    <input type="text" id="location" name="location"
                           value="{{ old('location') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                           placeholder="Ciudad o región">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tarifa/Salario esperado -->
                <div>
                    <label for="salary" class="block text-sm font-medium text-gray-700 mb-2">Tarifa (opcional)</label>
                    <input type="number" id="salary" name="salary"
                           value="{{ old('salary') }}"
                           step="0.01"
                           class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                           placeholder="Tarifa por hora o servicio">
                    @error('salary')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Categorías -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Categorías</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($categories as $category)
                        <div class="flex items-center">
                            <input type="checkbox" id="category_{{ $category->id }}"
                                   name="categories[]" value="{{ $category->id }}"
                                   @if(in_array($category->id, old('categories', []))) checked @endif
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="category_{{ $category->id }}" class="ml-2 text-sm text-gray-700">
                                {{ $category->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('categories')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('job-offers.index', ['offer_type' => 'classified']) }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Publicar Clasificado
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
