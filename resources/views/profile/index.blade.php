@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Tarjeta de perfil -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-24 h-24 relative">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full rounded-full object-cover">
                </div>                <div class="flex-grow">
                    <div class="flex justify-between items-start">
                        <h1 class="text-2xl font-bold text-gray-800">
                            @if(auth()->user()->isCompany())
                                {{ auth()->user()->company->company_name ?? auth()->user()->name }}
                            @else
                                {{ auth()->user()->name }}
                            @endif
                        </h1>
                        <button type="button" id="toggleProfileForm" class="text-gray-400 hover:text-blue-600 transition-colors ml-2">
                            <i class="fas fa-pencil-alt text-lg"></i>
                        </button>
                    </div>
                    <p class="text-gray-500">{{ auth()->user()->email }}</p>
                    <p class="text-gray-600">
                        @if(auth()->user()->isUnemployed())
                            Cesante
                        @else
                            Empresa
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario de perfil -->
        <div id="profileForm" class="bg-white rounded-lg shadow-md p-6 hidden">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <!-- Campos comunes -->
                <div class="space-y-4">
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700">Foto de Perfil</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="name" id="name" value="{{ auth()->user()->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ auth()->user()->email }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Campos específicos para Cesante -->
                    @if(auth()->user()->isUnemployed())
                        <div>
                            <label for="profession" class="block text-sm font-medium text-gray-700">Profesión</label>
                            <input type="text" name="profession" id="profession" value="{{ auth()->user()->unemployed->profession ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="experience" class="block text-sm font-medium text-gray-700">Experiencia</label>
                            <textarea name="experience" id="experience" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ auth()->user()->unemployed->experience ?? '' }}</textarea>
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Ubicación</label>
                            <input type="text" name="location" id="location" value="{{ auth()->user()->unemployed->location ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    @endif

                    <!-- Campos específicos para Empresa -->
                    @if(auth()->user()->isCompany())
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700">Nombre de la Empresa</label>
                            <input type="text" name="company_name" id="company_name" value="{{ auth()->user()->company->company_name ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ auth()->user()->company->description ?? '' }}</textarea>
                        </div>
                    @endif

                    <!-- Cambio de contraseña -->
                    <div class="pt-4 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Cambiar Contraseña</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Guardar Cambios
                    </button>
                </div>
            </form>

            <!-- Zona de Peligro -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-red-600">Zona de Peligro</h3>
                <div class="mt-4">
                    <button type="button" 
                            onclick="confirmDeleteAccount()" 
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Eliminar Cuenta
                    </button>
                    <p class="mt-2 text-sm text-gray-500">
                        Esta acción no se puede deshacer. Se eliminará permanentemente tu cuenta y todos los datos asociados.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar cuenta -->
<div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Confirmar eliminación de cuenta</h3>
        <p class="text-gray-500 mb-6">Esta acción eliminará permanentemente tu cuenta y todos los datos asociados. Esta acción no se puede deshacer.</p>
        
        <form action="{{ route('profile.destroy') }}" method="POST" class="space-y-4">
            @csrf
            @method('DELETE')
              <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                    Por favor, ingresa tu contraseña para confirmar
                </label>
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation" 
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password_confirmation') border-red-500 @enderror"
                       required>
                @error('password_confirmation')
                    <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-4 mt-6">
                <button type="button" 
                        onclick="closeDeleteModal()" 
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="submit" 
                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                    Sí, eliminar mi cuenta
                </button>
            </div>
        </form>
    </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleProfileForm');
    const profileForm = document.getElementById('profileForm');
    const modal = document.getElementById('deleteModal');
    const passwordInput = document.getElementById('password_confirmation');

    function toggleEditForm(e) {
        e.preventDefault();
        profileForm.classList.toggle('hidden');
    }

    toggleBtn.addEventListener('click', toggleEditForm);

    // Mostrar el modal si hay errores de contraseña
    @if($errors->has('password_confirmation') || session('showDeleteModal'))
        modal.classList.remove('hidden');
    @endif

    // Limpiar el campo de contraseña cuando se cierra el modal
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeDeleteModal();
        }
    });
});

function confirmDeleteAccount() {
    const modal = document.getElementById('deleteModal');
    const passwordInput = document.getElementById('password_confirmation');
    passwordInput.value = ''; // Limpiar el campo de contraseña
    modal.classList.remove('hidden');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const passwordInput = document.getElementById('password_confirmation');
    modal.classList.add('hidden');
    passwordInput.value = ''; // Limpiar el campo de contraseña
}
</script>
@endsection
