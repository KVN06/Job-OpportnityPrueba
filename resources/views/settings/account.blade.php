@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex items-center mb-6">
            <a href="{{ route('settings.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Configuración de Cuenta</h1>
        </div>

        <!-- Información de la Cuenta -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Información de la Cuenta</h2>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nombre</label>
                        <p class="text-gray-800">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-800">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tipo de Cuenta</label>
                        <p class="text-gray-800">{{ ucfirst($user->type) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Estado</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Miembro Desde</label>
                        <p class="text-gray-800">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Último Acceso</label>
                        <p class="text-gray-800">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de la Cuenta -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Estadísticas</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($user->isCompany())
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">{{ $user->company->jobOffers()->count() ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Ofertas Publicadas</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ $user->company->trainings()->count() ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Capacitaciones</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600">{{ $user->sentMessages()->count() }}</p>
                        <p class="text-sm text-gray-600">Mensajes Enviados</p>
                    </div>
                @endif

                @if($user->isUnemployed())
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">{{ $user->unemployed->jobApplications()->count() ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Aplicaciones</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ $user->unemployed->favoriteOffers()->count() ?? 0 }}</p>
                        <p class="text-sm text-gray-600">Favoritos</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600">{{ $user->sentMessages()->count() }}</p>
                        <p class="text-sm text-gray-600">Mensajes Enviados</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Acciones de Cuenta -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Acciones de Cuenta</h2>
            
            <div class="space-y-4">
                <!-- Exportar Datos -->
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div>
                        <h3 class="text-sm font-medium text-gray-800">Exportar Mis Datos</h3>
                        <p class="text-xs text-gray-600">Descarga una copia de toda tu información</p>
                    </div>
                    <button onclick="exportData()" 
                            class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Exportar
                    </button>
                </div>

                <!-- Desactivar Cuenta -->
                <div class="flex items-center justify-between p-4 border border-yellow-200 rounded-lg bg-yellow-50">
                    <div>
                        <h3 class="text-sm font-medium text-yellow-800">Desactivar Cuenta</h3>
                        <p class="text-xs text-yellow-700">Tu cuenta será ocultada pero no eliminada</p>
                    </div>
                    <button onclick="deactivateAccount()" 
                            class="px-4 py-2 text-sm font-medium text-yellow-600 bg-yellow-100 border border-yellow-300 rounded-md hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        Desactivar
                    </button>
                </div>
            </div>
        </div>

        <!-- Zona de Peligro -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
            <h2 class="text-lg font-semibold text-red-800 mb-4">Zona de Peligro</h2>
            
            <div class="bg-red-50 rounded-lg p-4 mb-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Eliminar Cuenta Permanentemente</h3>
                        <p class="text-xs text-red-700 mt-1">
                            Esta acción no se puede deshacer. Se eliminarán permanentemente todos tus datos, 
                            incluyendo ofertas de trabajo, aplicaciones, mensajes y cualquier otra información asociada.
                        </p>
                    </div>
                </div>
            </div>

            <button onclick="showDeleteModal()" 
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Eliminar Cuenta
            </button>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Eliminar Cuenta -->
<div id="deleteAccountModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900">Confirmar Eliminación</h3>
            </div>
            
            <p class="text-sm text-gray-500 mb-4">
                Esta acción eliminará permanentemente tu cuenta y todos los datos asociados. 
                Para confirmar, ingresa tu contraseña y escribe "ELIMINAR" en el campo de confirmación.
            </p>

            <form action="{{ route('settings.account.delete') }}" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" name="password" id="password" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>
                
                <div class="mb-6">
                    <label for="confirmation" class="block text-sm font-medium text-gray-700">
                        Escribe "ELIMINAR" para confirmar
                    </label>
                    <input type="text" name="confirmation" id="confirmation" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="hideDeleteModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                        Eliminar Cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showDeleteModal() {
    document.getElementById('deleteAccountModal').classList.remove('hidden');
}

function hideDeleteModal() {
    document.getElementById('deleteAccountModal').classList.add('hidden');
    document.getElementById('password').value = '';
    document.getElementById('confirmation').value = '';
}

function exportData() {
    alert('Función de exportación en desarrollo. Pronto estará disponible.');
}

function deactivateAccount() {
    if (confirm('¿Estás seguro de que quieres desactivar tu cuenta? Podrás reactivarla más tarde.')) {
        alert('Función de desactivación en desarrollo. Pronto estará disponible.');
    }
}
</script>
@endsection