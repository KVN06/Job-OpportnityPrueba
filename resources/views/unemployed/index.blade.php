@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Perfiles de Desempleados</h1>
        
        @auth
            @if(auth()->user()->isUnemployed() && !auth()->user()->unemployed)
                <a href="{{ route('unemployed.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Crear Mi Perfil
                </a>
            @endif
        @endauth
    </div>

    <!-- Filtros de búsqueda -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('unemployed.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nombre, habilidades..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
                <input type="text" name="location" id="location" value="{{ request('location') }}" 
                       placeholder="Ciudad, región..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="profession" class="block text-sm font-medium text-gray-700">Profesión</label>
                <input type="text" name="profession" id="profession" value="{{ request('profession') }}" 
                       placeholder="Desarrollador, diseñador..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de perfiles -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($unemployedProfiles as $unemployed)
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <!-- Header del perfil -->
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white text-lg font-bold">
                        {{ substr($unemployed->user->name, 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $unemployed->user->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $unemployed->profession }}</p>
                    </div>
                </div>

                <!-- Información básica -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $unemployed->location }}
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $unemployed->experience_years }} años de experiencia
                    </div>
                    
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Nivel {{ ucfirst($unemployed->experience_level) }}
                    </div>
                </div>

                <!-- Habilidades -->
                @if($unemployed->skills)
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Habilidades</h4>
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice(explode(',', $unemployed->skills), 0, 3) as $skill)
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                    {{ trim($skill) }}
                                </span>
                            @endforeach
                            @if(count(explode(',', $unemployed->skills)) > 3)
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                    +{{ count(explode(',', $unemployed->skills)) - 3 }} más
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Botones de acción -->
                <div class="flex space-x-2">
                    <a href="{{ route('unemployed.show', $unemployed) }}" 
                       class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-md text-sm hover:bg-blue-700 transition-colors">
                        Ver Perfil
                    </a>
                    
                    @auth
                        @if(auth()->user()->isCompany())
                            <a href="{{ route('messages.create', ['user' => $unemployed->user->id]) }}" 
                               class="bg-green-600 text-white py-2 px-4 rounded-md text-sm hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay perfiles disponibles</h3>
                <p class="mt-1 text-sm text-gray-500">No se encontraron perfiles que coincidan con tu búsqueda.</p>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($unemployedProfiles->hasPages())
        <div class="mt-8">
            {{ $unemployedProfiles->links() }}
        </div>
    @endif
</div>
@endsection