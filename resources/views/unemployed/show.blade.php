@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header del perfil -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white text-xl font-bold">
                        {{ substr($unemployed->user->name, 0, 1) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $unemployed->user->name }}</h1>
                        <p class="text-gray-600">{{ $unemployed->profession }}</p>
                        <p class="text-sm text-gray-500">{{ $unemployed->location }}</p>
                    </div>
                </div>
                
                @can('update', $unemployed)
                <div class="flex space-x-2">
                    <a href="{{ route('unemployed.edit', $unemployed) }}" 
                       class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                        Editar Perfil
                    </a>
                </div>
                @endcan
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Experiencia -->
                @if($unemployed->experience)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Experiencia Laboral</h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-line">{{ $unemployed->experience }}</p>
                    </div>
                </div>
                @endif

                <!-- Educación -->
                @if($unemployed->education)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Educación</h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-line">{{ $unemployed->education }}</p>
                    </div>
                </div>
                @endif

                <!-- Habilidades -->
                @if($unemployed->skills)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Habilidades</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $unemployed->skills) as $skill)
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                                {{ trim($skill) }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Portfolio -->
                @if($unemployed->portfolio)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Portfolio</h2>
                    <a href="{{ route('portfolios.show', $unemployed->portfolio) }}" 
                       class="text-blue-600 hover:text-blue-800">
                        Ver Portfolio Completo →
                    </a>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Información básica -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Información</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Experiencia:</span>
                            <p class="text-gray-800">{{ $unemployed->experience_years }} años</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Nivel:</span>
                            <p class="text-gray-800 capitalize">{{ $unemployed->experience_level }}</p>
                        </div>
                        @if($unemployed->expected_salary)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Salario esperado:</span>
                            <p class="text-gray-800">${{ number_format($unemployed->expected_salary, 2) }}</p>
                        </div>
                        @endif
                        <div>
                            <span class="text-sm font-medium text-gray-500">Trabajo remoto:</span>
                            <p class="text-gray-800">{{ $unemployed->remote_work ? 'Disponible' : 'No disponible' }}</p>
                        </div>
                    </div>
                </div>

                <!-- CV -->
                @if($unemployed->cv)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Curriculum Vitae</h3>
                    <a href="{{ asset('storage/' . $unemployed->cv) }}" target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Descargar CV
                    </a>
                </div>
                @endif

                <!-- Estadísticas -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Aplicaciones:</span>
                            <span class="text-sm font-medium text-gray-800">{{ $unemployed->jobApplications->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Ofertas favoritas:</span>
                            <span class="text-sm font-medium text-gray-800">{{ $unemployed->favoriteOffers->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Miembro desde:</span>
                            <span class="text-sm font-medium text-gray-800">{{ $unemployed->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Contacto -->
                @auth
                    @if(auth()->user()->isCompany())
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Contacto</h3>
                        <a href="{{ route('messages.create', ['user' => $unemployed->user->id]) }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            Enviar Mensaje
                        </a>
                    </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection