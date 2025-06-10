@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- Navegación --}}
        <nav class="mb-8">
            <ol class="flex text-gray-500 text-sm">
                <li><a href="{{ route('companies.index') }}" class="hover:text-blue-600">Empresas</a></li>
                <li class="mx-2">/</li>
                <li>{{ $company->company_name }}</li>
            </ol>
        </nav>

        {{-- Información principal de la empresa --}}
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $company->company_name }}</h1>
                    @if($company->description)
                        <p class="text-gray-600 mt-4">{{ $company->description }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Ofertas de trabajo de la empresa --}}
        <div class="mt-8">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ofertas de trabajo publicadas</h2>
            
            <div class="grid grid-cols-1 gap-6">
                @forelse($company->jobOffers as $offer)
                    <div class="bg-white shadow rounded-lg p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-semibold">
                                    <a href="{{ route('job-offers.show', $offer) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $offer->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 mt-2">{{ Str::limit($offer->description, 150) }}</p>
                                
                                <div class="flex items-center mt-4 text-sm text-gray-500">
                                    @if($offer->location)
                                        <span class="mr-4">📍 {{ $offer->location }}</span>
                                    @endif
                                    @if($offer->salary)
                                        <span class="mr-4">💰 {{ $offer->salary_formatted }}</span>
                                    @endif
                                    <span>📅 {{ $offer->created_at->format('d/m/Y') }}</span>
                                </div>

                                @if($offer->categories->count() > 0)
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach($offer->categories as $category)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow rounded-lg p-6 text-center">
                        <p class="text-gray-600">Esta empresa aún no ha publicado ofertas de trabajo.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
