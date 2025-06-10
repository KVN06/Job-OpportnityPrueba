@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header de la capacitación -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $training->title }}</h1>
                    <p class="text-gray-600 mb-4">{{ $training->company->company_name ?? 'Proveedor externo' }}</p>
                    
                    <!-- Badges de información -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                            {{ ucfirst($training->type) }}
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                            Nivel {{ ucfirst($training->level) }}
                        </span>
                        @if($training->status)
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                                Activa
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-sm rounded-full">
                                Inactiva
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Acciones -->
                <div class="flex space-x-2">
                    @auth
                        @if(auth()->user()->isCompany() && $training->company_id == auth()->user()->company->id)
                            <a href="{{ route('trainings.edit', $training) }}" 
                               class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 transition-colors">
                                Editar
                            </a>
                            <form action="{{ route('trainings.destroy', $training) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('¿Estás seguro de que quieres eliminar esta capacitación?')"
                                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                                    Eliminar
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contenido principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Descripción -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Descripción</h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-line">{{ $training->description }}</p>
                    </div>
                </div>

                <!-- Requisitos -->
                @if($training->requirements)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Requisitos</h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-line">{{ $training->requirements }}</p>
                    </div>
                </div>
                @endif

                <!-- Participantes inscritos -->
                @if($training->users && $training->users->count() > 0)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        Participantes Inscritos ({{ $training->users->count() }})
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($training->users as $user)
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Información de la capacitación -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Información</h3>
                    <div class="space-y-3">
                        @if($training->start_date)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Fecha de inicio:</span>
                            <p class="text-gray-800">{{ \Carbon\Carbon::parse($training->start_date)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        
                        @if($training->end_date)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Fecha de fin:</span>
                            <p class="text-gray-800">{{ \Carbon\Carbon::parse($training->end_date)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        
                        @if($training->duration)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Duración:</span>
                            <p class="text-gray-800">{{ $training->duration }}</p>
                        </div>
                        @endif
                        
                        @if($training->location)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Ubicación:</span>
                            <p class="text-gray-800">{{ $training->location }}</p>
                        </div>
                        @endif
                        
                        @if($training->max_participants)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Máximo participantes:</span>
                            <p class="text-gray-800">{{ $training->max_participants }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Precio e inscripción -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Inscripción</h3>
                    
                    <!-- Precio -->
                    <div class="mb-4">
                        @if($training->price && $training->price > 0)
                            <span class="text-2xl font-bold text-green-600">
                                ${{ number_format($training->price, 2) }}
                            </span>
                        @else
                            <span class="text-2xl font-bold text-green-600">Gratuito</span>
                        @endif
                    </div>

                    <!-- Botón de inscripción -->
                    @auth
                        @if(auth()->user()->isUnemployed())
                            @if($isEnrolled)
                                <div class="space-y-3">
                                    <div class="bg-green-100 text-green-800 py-3 px-4 rounded-md text-center font-medium">
                                        ✓ Ya estás inscrito
                                    </div>
                                    <form action="{{ route('trainings.complete', $training) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition-colors font-medium">
                                            Marcar como Completada
                                        </button>
                                    </form>
                                </div>
                            @else
                                @if($training->status)
                                    <form action="{{ route('trainings.enroll', $training) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 transition-colors font-medium">
                                            Inscribirse Ahora
                                        </button>
                                    </form>
                                @else
                                    <div class="bg-gray-100 text-gray-600 py-3 px-4 rounded-md text-center">
                                        Capacitación no disponible
                                    </div>
                                @endif
                            @endif
                        @else
                            <div class="bg-blue-100 text-blue-800 py-3 px-4 rounded-md text-center text-sm">
                                Solo los usuarios desempleados pueden inscribirse
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" 
                           class="block w-full bg-blue-600 text-white py-3 px-4 rounded-md hover:bg-blue-700 transition-colors font-medium text-center">
                            Iniciar Sesión para Inscribirse
                        </a>
                    @endauth
                </div>

                <!-- Estadísticas -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Inscritos:</span>
                            <span class="text-sm font-medium text-gray-800">{{ $training->users ? $training->users->count() : 0 }}</span>
                        </div>
                        @if($training->max_participants)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Disponibles:</span>
                            <span class="text-sm font-medium text-gray-800">{{ $training->max_participants - ($training->users ? $training->users->count() : 0) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Creada:</span>
                            <span class="text-sm font-medium text-gray-800">{{ $training->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón de volver -->
        <div class="mt-8">
            <a href="{{ route('trainings.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Volver a Capacitaciones
            </a>
        </div>
    </div>
</div>
@endsection