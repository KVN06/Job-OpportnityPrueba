@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <nav class="mb-4">
        <ol class="flex text-gray-500 text-sm">
            <li><a href="{{ route('job-offers.index') }}" class="hover:text-blue-600">Ofertas de Trabajo</a></li>
            <li class="mx-2">/</li>
            <li>{{ $jobOffer->title }}</li>
        </ol>
    </nav>

    <div class="bg-white rounded-lg shadow p-6">
        {{-- Encabezado con título y botones de acción --}}
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $jobOffer->title }}</h1>
                <p class="text-gray-600 mt-2">
                    <span>{{ $jobOffer->company->company_name }}</span>
                    <span class="mx-2">•</span>
                    <span>{{ $jobOffer->translated_offer_type }}</span>
                    @if(!$jobOffer->isActive())
                        <span class="mx-2">•</span>
                        <span class="text-red-600 font-medium">{{ $jobOffer->status_text }}</span>
                    @endif
                </p>
            </div>
            @if(auth()->check() && $jobOffer->canBeEditedBy(auth()->user()))
            <div class="flex space-x-2">
                {{-- Botón de congelar --}}
                <button type="button" 
                        onclick="toggleJobOfferStatus({{ $jobOffer->id }})"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 freeze-btn">
                    <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18M3 12h18M5.636 5.636l12.728 12.728M18.364 5.636L5.636 18.364"/>
                    </svg>
                    <span class="status-text">{{ $jobOffer->isActive() ? 'Congelar ' . $jobOffer->translated_offer_type : 'Activar ' . $jobOffer->translated_offer_type }}</span>
                </button>
                {{-- Botón de editar --}}
                <a href="{{ route('job-offers.edit', $jobOffer) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar {{ $jobOffer->translated_offer_type }}
                </a>
            </div>
            @endif
        </div>

        {{-- Descripción --}}
        <div class="mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-2">Descripción</h2>
            <div class="text-gray-700">
                {!! nl2br(e($jobOffer->description)) !!}
            </div>
        </div>

        {{-- Salario y Ubicación en fila --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-2">Salario</h2>
                <p class="text-gray-700">{{ $jobOffer->salary ?? 'No especificado' }}</p>
            </div>
            
            <div>
                <h2 class="text-lg font-medium text-gray-900 mb-2">Ubicación</h2>
                <p class="text-gray-700">{{ $jobOffer->location }}</p>
            </div>
        </div>

        {{-- Geolocalización --}}
        @if($jobOffer->geolocation)
        <div class="mb-8">
            <h2 class="text-lg font-medium text-gray-900 mb-2">Geolocalización (Google Maps)</h2>
            <p class="text-gray-600 text-sm">{{ $jobOffer->geolocation }}</p>
        </div>
        @endif

        {{-- Categorías --}}
        @if($jobOffer->categories->count() > 0)
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-900 mb-2">Categorías</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($jobOffer->categories as $category)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                    {{ $category->name }}
                </span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Estadísticas simples --}}
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="flex space-x-4 text-sm text-gray-500">
                <span>{{ $jobOffer->applications_count ?? 0 }} aplicaciones</span>
                <span>{{ $jobOffer->views_count ?? 0 }} vistas</span>
            </div>
        </div>

        {{-- Botón de Aplicar --}}
        @auth
        <div class="mt-8">
            @if($jobOffer->isActive())
                @if($canApply)
                    <button type="button" 
                            onclick="document.getElementById('application-modal').classList.remove('hidden')"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Aplicar a esta oferta
                </button>
                @elseif(auth()->user()->isUnemployed() && auth()->user()->unemployed->hasAppliedTo($jobOffer))
                    <div class="text-center p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-center">
                            <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <p class="text-green-700 font-medium">Ya te has postulado a esta oferta</p>
                        </div>
                        <p class="text-green-600 text-sm mt-1">Espera notificaciones para saber si eres seleccionado</p>
                    </div>
                @else
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600">No puedes aplicar a esta oferta</p>
                    </div>
                @endif
            @else
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-600">Esta oferta no está disponible temporalmente</p>
                </div>
            @endif
        </div>
        @else
        <div class="mt-8">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Inicia sesión para aplicar
            </a>
        </div>
        @endauth
    </div>
</div>

{{-- Modal de Aplicación --}}
<div id="application-modal" class="fixed inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('applications.store') }}" method="POST">
                @csrf
                <input type="hidden" name="job_offer_id" value="{{ $jobOffer->id }}">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900">Aplicar a: {{ $jobOffer->title }}</h3>
                    <div class="mt-4">
                        <label for="message" class="block text-sm font-medium text-gray-700">
                            Mensaje de presentación
                        </label>
                        <textarea id="message" name="message" rows="4" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Enviar aplicación
                    </button>
                    <button type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        onclick="document.getElementById('application-modal').classList.add('hidden')">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (session('success'))
<div id="notification" class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div id="notification" class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('error') }}
</div>
@endif

<script>
    setTimeout(function() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.display = 'none';
        }
    }, 5000);
</script>
@endsection

@section('scripts')
<script>
const toggleJobOfferStatus = async (jobOfferId) => {
    try {
        if (!confirm('¿Estás seguro que deseas cambiar el estado de esta oferta?')) {
            return;
        }

        const toggleStatusUrl = `{{ url('job-offers') }}/${jobOfferId}/toggle-status`;
        const response = await fetch(toggleStatusUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        
        if (data.success) {
            // Recargar la página para mostrar los cambios
            location.reload();
        } else {
            throw new Error(data.message || 'Error al cambiar el estado de la oferta');
        }
    } catch (error) {
        // Manejo de error removido para producción
    }
};

// Auto-ocultar notificaciones
document.addEventListener('DOMContentLoaded', () => {
    const notification = document.getElementById('notification');
    if (notification) {
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
});
</script>
@endsection
