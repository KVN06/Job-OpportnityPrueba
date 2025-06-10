@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Crear Perfil de Desempleado</h1>

        <form action="{{ route('unemployed.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm p-6">
            @csrf

            <!-- Información Personal -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Información Personal</h2>
                
                <!-- Profesión -->
                <div class="mb-4">
                    <label for="profession" class="block text-sm font-medium text-gray-700">Profesión</label>
                    <input type="text" name="profession" id="profession" value="{{ old('profession') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('profession')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Años de Experiencia -->
                <div class="mb-4">
                    <label for="experience_years" class="block text-sm font-medium text-gray-700">Años de Experiencia</label>
                    <input type="number" name="experience_years" id="experience_years" value="{{ old('experience_years') }}" 
                           min="0" max="50" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('experience_years')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nivel de Experiencia -->
                <div class="mb-4">
                    <label for="experience_level" class="block text-sm font-medium text-gray-700">Nivel de Experiencia</label>
                    <select name="experience_level" id="experience_level" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Seleccionar nivel</option>
                        <option value="junior" {{ old('experience_level') == 'junior' ? 'selected' : '' }}>Junior</option>
                        <option value="medio" {{ old('experience_level') == 'medio' ? 'selected' : '' }}>Medio</option>
                        <option value="senior" {{ old('experience_level') == 'senior' ? 'selected' : '' }}>Senior</option>
                        <option value="lead" {{ old('experience_level') == 'lead' ? 'selected' : '' }}>Lead</option>
                    </select>
                    @error('experience_level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ubicación -->
                <div class="mb-4">
                    <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Habilidades y Experiencia -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Habilidades y Experiencia</h2>
                
                <!-- Habilidades -->
                <div class="mb-4">
                    <label for="skills" class="block text-sm font-medium text-gray-700">Habilidades</label>
                    <textarea name="skills" id="skills" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                              placeholder="Ej: PHP, Laravel, JavaScript, MySQL...">{{ old('skills') }}</textarea>
                    @error('skills')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Experiencia -->
                <div class="mb-4">
                    <label for="experience" class="block text-sm font-medium text-gray-700">Experiencia Laboral</label>
                    <textarea name="experience" id="experience" rows="4" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                              placeholder="Describe tu experiencia laboral...">{{ old('experience') }}</textarea>
                    @error('experience')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Educación -->
                <div class="mb-4">
                    <label for="education" class="block text-sm font-medium text-gray-700">Educación</label>
                    <textarea name="education" id="education" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                              placeholder="Describe tu formación académica...">{{ old('education') }}</textarea>
                    @error('education')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Información Adicional</h2>
                
                <!-- CV -->
                <div class="mb-4">
                    <label for="cv" class="block text-sm font-medium text-gray-700">Curriculum Vitae (PDF)</label>
                    <input type="file" name="cv" id="cv" accept=".pdf" 
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('cv')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Disponibilidad para trabajo remoto -->
                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="remote_work" id="remote_work" value="1" 
                               {{ old('remote_work') ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <label for="remote_work" class="ml-2 block text-sm text-gray-700">Disponible para trabajo remoto</label>
                    </div>
                    @error('remote_work')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Salario esperado -->
                <div class="mb-4">
                    <label for="expected_salary" class="block text-sm font-medium text-gray-700">Salario Esperado (opcional)</label>
                    <input type="number" name="expected_salary" id="expected_salary" value="{{ old('expected_salary') }}" 
                           min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('expected_salary')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('dashboard') }}" 
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Crear Perfil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection