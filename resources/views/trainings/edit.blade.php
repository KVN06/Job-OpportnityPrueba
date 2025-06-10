@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Editar Capacitación</h1>

        <form action="{{ route('trainings.update', $training) }}" method="POST" class="bg-white rounded-lg shadow-sm p-6">
            @csrf
            @method('PUT')

            <!-- Información básica -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Información Básica</h2>
                
                <!-- Título -->
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Título de la Capacitación</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $training->title) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descripción -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea name="description" id="description" rows="4" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('description', $training->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo y Nivel -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Capacitación</label>
                        <select name="type" id="type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Seleccionar tipo</option>
                            <option value="online" {{ old('type', $training->type) == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="presencial" {{ old('type', $training->type) == 'presencial' ? 'selected' : '' }}>Presencial</option>
                            <option value="hibrido" {{ old('type', $training->type) == 'hibrido' ? 'selected' : '' }}>Híbrido</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700">Nivel</label>
                        <select name="level" id="level" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <option value="">Seleccionar nivel</option>
                            <option value="basico" {{ old('level', $training->level) == 'basico' ? 'selected' : '' }}>Básico</option>
                            <option value="intermedio" {{ old('level', $training->level) == 'intermedio' ? 'selected' : '' }}>Intermedio</option>
                            <option value="avanzado" {{ old('level', $training->level) == 'avanzado' ? 'selected' : '' }}>Avanzado</option>
                        </select>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Fechas y duración -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Fechas y Duración</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                        <input type="date" name="start_date" id="start_date" 
                               value="{{ old('start_date', $training->start_date ? $training->start_date->format('Y-m-d') : '') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                        <input type="date" name="end_date" id="end_date" 
                               value="{{ old('end_date', $training->end_date ? $training->end_date->format('Y-m-d') : '') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700">Duración</label>
                    <input type="text" name="duration" id="duration" value="{{ old('duration', $training->duration) }}" 
                           placeholder="Ej: 40 horas, 2 semanas, 3 meses"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Información adicional -->
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">Información Adicional</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700">Precio (opcional)</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $training->price) }}" 
                               min="0" step="0.01"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="max_participants" class="block text-sm font-medium text-gray-700">Máximo de Participantes</label>
                        <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants', $training->max_participants) }}" 
                               min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('max_participants')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Ubicación (para presencial o híbrido) -->
                <div class="mb-4">
                    <label for="location" class="block text-sm font-medium text-gray-700">Ubicación (para capacitaciones presenciales)</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $training->location) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Requisitos -->
                <div class="mb-4">
                    <label for="requirements" class="block text-sm font-medium text-gray-700">Requisitos</label>
                    <textarea name="requirements" id="requirements" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('requirements', $training->requirements) }}</textarea>
                    @error('requirements')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="status" id="status" value="1" 
                               {{ old('status', $training->status) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <label for="status" class="ml-2 block text-sm text-gray-700">Capacitación activa</label>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Información de participantes -->
            @if($training->users && $training->users->count() > 0)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-700 mb-4">
                    Participantes Inscritos ({{ $training->users->count() }})
                </h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($training->users as $user)
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-600">{{ $user->email }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Botones de acción -->
            <div class="flex justify-between">
                <div>
                    <form action="{{ route('trainings.destroy', $training) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('¿Estás seguro de que quieres eliminar esta capacitación? Esta acción no se puede deshacer.')"
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Eliminar Capacitación
                        </button>
                    </form>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('trainings.show', $training) }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Actualizar Capacitación
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection