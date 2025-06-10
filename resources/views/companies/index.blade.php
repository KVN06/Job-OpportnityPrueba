@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Empresas</h1>

        {{-- Lista de empresas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($companies as $company)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                        <a href="{{ route('companies.show', $company) }}" class="hover:text-blue-600 transition-colors">
                            {{ $company->company_name }}
                        </a>
                    </h2>
                    
                    @if($company->description)
                        <p class="text-gray-600 mt-2">{{ Str::limit($company->description, 150) }}</p>
                    @endif

                    {{-- Estadísticas de la empresa --}}
                    <div class="mt-4 flex items-center text-sm text-gray-500">
                        <span class="mr-4">
                            <i class="fas fa-briefcase mr-1"></i>
                            {{ $company->jobOffers()->count() }} ofertas publicadas
                        </span>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('companies.show', $company) }}" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-800">
                            Ver detalles
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-lg shadow-sm p-6 text-center">
                    <p class="text-gray-600">No hay empresas registradas aún.</p>
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        <div class="mt-6">
            {{ $companies->links() }}
        </div>
    </div>
</div>
@endsection
