@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Capacitaciones Disponibles</h1>
        
        @auth
            @if(auth()->user()->isCompany() || auth()->user()->isAdmin())
                <a href="{{ route('trainings.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Nueva Capacitación
                </a>
            @endif
        @endauth
    </div>

    <!-- Filtros de búsqueda -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form method="GET" action="{{ route('trainings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Título, descripción..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                <select name="type" id="type" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos los tipos</option>
                    <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="presencial" {{ request('type') == 'presencial' ? 'selected' : '' }}>Presencial</option>
                    <option value="hibrido" {{ request('type') == 'hibrido' ? 'selected' : '' }}>Híbrido</option>
                </select>
            </div>
            
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700">Nivel</label>
                <select name="level" id="level" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Todos los niveles</option>
                    <option value="basico" {{ request('level') == 'basico' ? 'selected' : '' }}>Básico</option>
                    <option value="intermedio" {{ request('level') == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                    <option value="avanzado" {{ request('level') == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" 
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Lista de capacitaciones -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($trainings as $training)
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <!-- Header de la capacitación -->
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $training->title }}</h3>
                    <p class="text-sm text-gray-600">{{ $training->company->company_name ?? 'Proveedor externo' }}</p>
                </div>

                <!-- Descripción -->
                <p class="text-gray-700 text-sm mb-4 line-clamp-3">
                    {{ Str::limit($training->description, 120) }}
                </p>

                <!-- Información básica -->
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $training->duration ?? 'Duración no especificada' }}
                    </div>
                    
                    @if($training->start_date)
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Inicia: {{ \Carbon\Carbon::parse($training->start_date)->format('d/m/Y') }}
                        </div>
                    @endif
                    
                    @if($training->type)
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ ucfirst($training->type) }}
                        </div>
                    @endif
                    
                    @if($training->level)
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                            Nivel {{ ucfirst($training->level) }}
                        </div>
                    @endif
                </div>

                <!-- Precio -->
                @if($training->price)
                    <div class="mb-4">
                        <span class="text-lg font-semibold text-green-600">
                            ${{ number_format($training->price, 2) }}
                        </span>
                    </div>
                @else
                    <div class="mb-4">
                        <span class="text-lg font-semibold text-green-600">Gratuito</span>
                    </div>
                @endif

                <!-- Botones de acción -->
                <div class="flex space-x-2">
                    <a href="{{ route('trainings.show', $training) }}" 
                       class="flex-1 bg-blue-600 text-white text-center py-2 px-4 rounded-md text-sm hover:bg-blue-700 transition-colors">
                        Ver Detalles
                    </a>
                    
                    @auth
                        @if(auth()->user()->isUnemployed())
                            @if($training->users && $training->users->contains('id', auth()->id()))
                                <span class="bg-green-100 text-green-800 py-2 px-4 rounded-md text-sm">
                                    Inscrito
                                </span>
                            @else
                                <form action="{{ route('trainings.enroll', $training) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-green-600 text-white py-2 px-4 rounded-md text-sm hover:bg-green-700 transition-colors">
                                        Inscribirse
                                    </button>
                                </form>
                            @endif
                        @endif
                        
                        @if(auth()->user()->isCompany() && $training->company_id == auth()->user()->company->id)
                            <a href="{{ route('trainings.edit', $training) }}" 
                               class="bg-yellow-600 text-white py-2 px-4 rounded-md text-sm hover:bg-yellow-700 transition-colors">
                                Editar
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay capacitaciones disponibles</h3>
                <p class="mt-1 text-sm text-gray-500">No se encontraron capacitaciones que coincidan con tu búsqueda.</p>
                
                @auth
                    @if(auth()->user()->isCompany() || auth()->user()->isAdmin())
                        <div class="mt-6">
                            <a href="{{ route('trainings.create') }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Nueva Capacitación
                            </a>
                        </div>
                    @endif
                @endauth
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($trainings->hasPages())
        <div class="mt-8">
            {{ $trainings->links() }}
        </div>
    @endif
</div>
@endsection