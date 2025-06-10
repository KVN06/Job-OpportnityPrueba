@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">{{ auth()->user()->name }}</h1>
                    <p class="text-gray-600">{{ auth()->user()->email }}</p>
                    <p class="text-gray-500">
                        @if(auth()->user()->isCompany())
                            Empresa
                        @else
                            Cesante
                        @endif
                    </p>
                </div>
                <a href="#" onclick="toggleEditForm(event)" class="text-gray-400 hover:text-blue-600">
                    <i class="fas fa-pencil-alt text-xl"></i>
                </a>
            </div>
        </div>

        <!-- Formulario de edición -->
        <div id="profileForm" class="hidden">
            <form action="{{ route('profile.update') }}" method="POST" class="bg-white rounded-lg shadow-sm p-6">
                @csrf
                @method('PUT')

                <!-- Información básica -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Información Básica</h2>
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if($user->isUnemployed())
                <!-- Información específica para desempleados -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Información Profesional</h2>
                    
                    <div class="mb-4">
                        <label for="profession" class="block text-sm font-medium text-gray-700">Profesión</label>
                        <input type="text" name="profession" id="profession" 
                               value="{{ old('profession', $user->unemployed->profession ?? '') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="experience" class="block text-sm font-medium text-gray-700">Experiencia</label>
                        <textarea name="experience" id="experience" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('experience', $user->unemployed->experience ?? '') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
                        <input type="text" name="location" id="location" 
                               value="{{ old('location', $user->unemployed->location ?? '') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
                @endif

                @if($user->isCompany())
                <!-- Información específica para empresas -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-4">Información de la Empresa</h2>
                    
                    <div class="mb-4">
                        <label for="company_name" class="block text-sm font-medium text-gray-700">Nombre de la Empresa</label>
                        <input type="text" name="company_name" id="company_name" 
                               value="{{ old('company_name', $user->company->company_name ?? '') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción de la Empresa</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $user->company->description ?? '') }}</textarea>
                    </div>
                </div>
                @endif

                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="toggleEditForm(event)"
                       class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.querySelector('a[onclick="toggleEditForm(event)"]');
    const profileForm = document.getElementById('profileForm');

    function toggleEditForm(e) {
        e.preventDefault();
        profileForm.classList.toggle('hidden');
    }

    toggleBtn.addEventListener('click', toggleEditForm);
});
</script>
@endsection
