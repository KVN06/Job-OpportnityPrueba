@extends('layouts.home')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            {{ request('type') == 'classified' ? 'Crear Nuevo Clasificado' : 'Crear Nueva Oferta Laboral' }}
        </h1>

        <form action="{{ route('job-offers.store') }}" method="POST" class="bg-white rounded-lg shadow-sm p-6">
            @csrf
            <input type="hidden" name="offer_type" value="{{ request('type', 'contract') }}">

            <!-- Campo para el título de la oferta -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo para la descripción de la oferta -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea name="description" id="description" rows="4" 
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo para el salario ofrecido -->
            <div class="mb-4">
                <label for="salary" class="block text-sm font-medium text-gray-700">Salario</label>
                <input type="number" name="salary" id="salary" value="{{ old('salary') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('salary')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo para la ubicación de la oferta -->
            <div class="mb-4">
                <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
                <input type="text" name="location" id="location" value="{{ old('location') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo para la geolocalización (coordenadas de Google Maps) -->
            <div class="mb-4">
                <label for="geolocation" class="block text-sm font-medium text-gray-700">Geolocalización (Google Maps)</label>
                <input type="text" name="geolocation" id="geolocation" value="{{ old('geolocation') }}" 
                       placeholder="Ejemplo: -12.0464,-77.0428" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('geolocation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo para el tipo de contrato -->
            <div class="mb-4">
                <label for="contract_type" class="block text-sm font-medium text-gray-700">Tipo de Contrato</label>
                <select name="contract_type" id="contract_type" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Seleccionar tipo de contrato</option>
                    <option value="tiempo_completo" {{ old('contract_type') == 'tiempo_completo' ? 'selected' : '' }}>Tiempo Completo</option>
                    <option value="medio_tiempo" {{ old('contract_type') == 'medio_tiempo' ? 'selected' : '' }}>Medio Tiempo</option>
                    <option value="proyecto" {{ old('contract_type') == 'proyecto' ? 'selected' : '' }}>Proyecto</option>
                    <option value="practicas" {{ old('contract_type') == 'practicas' ? 'selected' : '' }}>Prácticas</option>
                </select>
                @error('contract_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo para el nivel de experiencia -->
            <div class="mb-4">
                <label for="experience_level" class="block text-sm font-medium text-gray-700">Nivel de Experiencia</label>
                <select name="experience_level" id="experience_level" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Seleccionar nivel de experiencia</option>
                    <option value="junior" {{ old('experience_level') == 'junior' ? 'selected' : '' }}>Junior</option>
                    <option value="medio" {{ old('experience_level') == 'medio' ? 'selected' : '' }}>Medio</option>
                    <option value="senior" {{ old('experience_level') == 'senior' ? 'selected' : '' }}>Senior</option>
                    <option value="lead" {{ old('experience_level') == 'lead' ? 'selected' : '' }}>Lead</option>
                </select>
                @error('experience_level')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo para trabajo remoto -->
            <div class="mb-4">
                <div class="flex items-center">
                    <input type="checkbox" name="remote_work" id="remote_work" value="1" 
                           {{ old('remote_work') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <label for="remote_work" class="ml-2 block text-sm text-gray-700">Trabajo remoto disponible</label>
                </div>
                @error('remote_work')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo para fecha límite de aplicación -->
            <div class="mb-4">
                <label for="application_deadline" class="block text-sm font-medium text-gray-700">Fecha límite de aplicación</label>
                <input type="date" name="application_deadline" id="application_deadline" value="{{ old('application_deadline') }}" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('application_deadline')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- El tipo de oferta está definido por el botón usado para crear -->
            <input type="hidden" name="offer_type" value="{{ request('type', 'contract') }}">

            <!-- Sección de categorías mejorada -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Categorías</label>
                <div class="space-y-4">
                    <select id="categories" name="categories[]" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Botón para agregar nueva categoría -->
                    <button type="button" id="newCategoryBtn" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nueva Categoría
                    </button>
                </div>
                @error('categories')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones de acción: Cancelar o Crear Oferta -->
            <div class="flex justify-end">
                <a href="{{ route('job-offers.index') }}" 
                   class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors mr-4">
                    Cancelar
                </a>
                <button type="submit" 
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Crear Oferta
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal flotante para nueva categoría -->
<div id="newCategoryModal" class="fixed z-50 hidden" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <div class="relative">
        <!-- Overlay semi-transparente -->
        <div class="fixed inset-0 bg-black bg-opacity-30" id="modalOverlay"></div>
        
        <!-- Contenedor del modal -->
        <div class="bg-white rounded-lg shadow-xl w-96 relative z-50">
            <div class="px-4 pt-5 pb-4">
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title">
                        Nueva Categoría
                    </h3>
                    <div>
                        <input type="text" id="newCategoryName" 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                               placeholder="Nombre de la categoría">
                        <input type="hidden" id="categoryType" value="{{ request('type', 'contract') }}">
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 flex justify-end space-x-3 rounded-b-lg">
                <button type="button" id="closeCategoryModal"
                        class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </button>
                <button type="button" id="createCategoryBtn" 
                        class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Crear
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Inicializar Select2 con búsqueda
    $('#categories').select2({
        placeholder: 'Buscar categorías...',
        allowClear: true,
        ajax: {
            url: '{{ route("categories.search") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    query: params.term,
                    type: '{{ request("type", "contract") }}'
                };
            },
            processResults: function(data) {
                if (data.suggestions && data.suggestions.length > 0) {
                    const suggestionsList = data.suggestions.map(cat => cat.name).join('\n');
                    if (confirm('Se encontraron categorías similares:\n\n' + suggestionsList + '\n\n¿Deseas usar alguna de estas categorías existentes?')) {
                        return {
                            results: data.suggestions.map(cat => ({
                                id: cat.id,
                                text: cat.name
                            }))
                        };
                    }
                }
                return {
                    results: data.results || []
                };
            }
        }
    });

    // Manejo del modal de nueva categoría
    $('#newCategoryBtn').click(function(e) {
        e.preventDefault();
        $('#newCategoryModal').removeClass('hidden');
    });

    $('#closeCategoryModal, #modalOverlay').click(function() {
        $('#newCategoryModal').addClass('hidden');
        $('#newCategoryName').val('');
    });

    // Prevenir que los clics dentro del modal lo cierren
    $('.modal-content').click(function(e) {
        e.stopPropagation();
    });

    // Crear nueva categoría
    $('#createCategoryBtn').click(function() {
        const categoryName = $('#newCategoryName').val();
        const categoryType = $('#categoryType').val();
        
        if (!categoryName) {
            alert('Por favor ingresa un nombre para la categoría');
            return;
        }

        $.ajax({
            url: '{{ route("categories.store") }}',
            method: 'POST',
            data: {
                name: categoryName,
                type: categoryType,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                const newOption = new Option(response.name, response.id, true, true);
                $('#categories').append(newOption).trigger('change');
                $('#newCategoryModal').addClass('hidden');
                $('#newCategoryName').val('');
                alert('Categoría creada correctamente');
            },
            error: function(xhr) {
                if (xhr.status === 409) {
                    if (confirm('Ya existe una categoría similar: "' + xhr.responseJSON.category.name + 
                              '"\n\n¿Deseas usar esta categoría existente?')) {
                        const existingCategory = xhr.responseJSON.category;
                        const newOption = new Option(existingCategory.name, existingCategory.id, true, true);
                        $('#categories').append(newOption).trigger('change');
                        $('#newCategoryModal').removeClass('flex').addClass('hidden');
                        $('body').removeClass('overflow-hidden');
                        $('#newCategoryName').val('');
                    }
                } else {
                    alert('Error al crear la categoría: ' + xhr.responseJSON.message);
                }
            }
        });
    });
});
</script>
@endsection
