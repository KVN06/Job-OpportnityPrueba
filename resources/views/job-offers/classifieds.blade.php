@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Clasificados</h1>
        <a href="{{ route('classifieds.create') }}" 
           class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i>
            Añadir Clasificado
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form action="{{ route('job-offers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="hidden" name="offer_type" value="classified">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Búsqueda</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full rounded-lg border-gray-300" 
                       placeholder="Buscar por título o descripción">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
                <input type="text" name="location" value="{{ request('location') }}" 
                       class="w-full rounded-lg border-gray-300" 
                       placeholder="Ciudad o región">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                <select name="category" class="w-full rounded-lg border-gray-300">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de clasificados -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($jobOffers as $offer)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $offer->title }}</h3>
                <p class="text-gray-600 mb-4">{{ Str::limit($offer->description, 150) }}</p>
                <div class="flex items-center text-sm text-gray-500 mb-4">
                    <i class="fas fa-map-marker-alt mr-2"></i>
                    {{ $offer->location ?? 'Ubicación no especificada' }}
                </div>
                @if($offer->salary)
                    <div class="text-sm text-gray-500 mb-4">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        ${{ number_format($offer->salary, 2) }}
                    </div>
                @endif
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($offer->categories as $category)
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">
                        Publicado {{ $offer->created_at->diffForHumans() }}
                    </span>
                    <a href="{{ route('job-offers.show', $offer) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        Ver detalles
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-500 text-lg">No se encontraron clasificados que coincidan con tu búsqueda.</p>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $jobOffers->links() }}
    </div>
</div>
@endsection
