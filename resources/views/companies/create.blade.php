@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Registrar Empresa</h1>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('companies.store') }}" method="POST" id="company-form">
                @csrf

                <div class="space-y-6">
                    <!-- Nombre de la empresa -->
                    <div>
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Empresa *
                        </label>
                        <input type="text" 
                               id="company_name" 
                               name="company_name" 
                               value="{{ old('company_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('company_name') border-red-500 @enderror"
                               required>
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descripción -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción de la Empresa *
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Describe tu empresa, su misión, valores y lo que la hace única..."
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ubicación -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                            Ubicación *
                        </label>
                        <input type="text" 
                               id="location" 
                               name="location" 
                               value="{{ old('location') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('location') border-red-500 @enderror"
                               placeholder="Ciudad, País"
                               required>
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Industria -->
                    <div>
                        <label for="industry" class="block text-sm font-medium text-gray-700 mb-2">
                            Industria *
                        </label>
                        <select id="industry" 
                                name="industry"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('industry') border-red-500 @enderror"
                                required>
                            <option value="">Selecciona una industria</option>
                            <option value="tecnologia" {{ old('industry') == 'tecnologia' ? 'selected' : '' }}>Tecnología</option>
                            <option value="salud" {{ old('industry') == 'salud' ? 'selected' : '' }}>Salud</option>
                            <option value="educacion" {{ old('industry') == 'educacion' ? 'selected' : '' }}>Educación</option>
                            <option value="finanzas" {{ old('industry') == 'finanzas' ? 'selected' : '' }}>Finanzas</option>
                            <option value="manufactura" {{ old('industry') == 'manufactura' ? 'selected' : '' }}>Manufactura</option>
                            <option value="servicios" {{ old('industry') == 'servicios' ? 'selected' : '' }}>Servicios</option>
                            <option value="comercio" {{ old('industry') == 'comercio' ? 'selected' : '' }}>Comercio</option>
                            <option value="construccion" {{ old('industry') == 'construccion' ? 'selected' : '' }}>Construcción</option>
                            <option value="turismo" {{ old('industry') == 'turismo' ? 'selected' : '' }}>Turismo</option>
                            <option value="otro" {{ old('industry') == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('industry')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sitio web -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">
                            Sitio Web
                        </label>
                        <input type="url" 
                               id="website" 
                               name="website" 
                               value="{{ old('website') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('website') border-red-500 @enderror"
                               placeholder="https://www.ejemplo.com">
                        @error('website')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('dashboard') }}" 
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancelar
                        </a>
                        
                        <button type="submit" 
                                id="submit-btn"
                                class="px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Registrar Empresa
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Información adicional -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Información importante
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Una vez registres tu empresa, podrás publicar ofertas de trabajo y gestionar candidatos. Asegúrate de completar toda la información para que tu perfil sea más atractivo para los candidatos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('company-form');
    const submitBtn = document.getElementById('submit-btn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Deshabilitar el botón para evitar doble envío
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Registrando...';
            
            // Verificar que todos los campos requeridos estén llenos
            const requiredFields = form.querySelectorAll('[required]');
            let allValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    allValid = false;
                }
            });
            
            if (!allValid) {
                e.preventDefault();
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Registrar Empresa';
                alert('Por favor, completa todos los campos requeridos.');
                return false;
            }
        });
    }
});
</script>
@endpush

@endsection