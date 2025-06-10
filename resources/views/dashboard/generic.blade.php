@extends('layouts.home')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Mensajes Flash -->
        @if(session('success'))
            <div id="success-message" class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 transition-opacity duration-500">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-3"></i>
                        <div>
                            <h3 class="font-medium text-green-800">¡Éxito!</h3>
                            <p class="text-green-700 text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button onclick="closeMessage('success-message')" class="text-green-600 hover:text-green-800">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                    <div>
                        <h3 class="font-medium text-red-800">Error</h3>
                        <p class="text-red-700 text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                    <div>
                        <h3 class="font-medium text-blue-800">Información</h3>
                        <p class="text-blue-700 text-sm">{{ session('info') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header del Dashboard -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        ¡Bienvenido, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-gray-600 mt-2">
                        @if(auth()->user()->isCompany())
                            Panel de control para empresas
                        @elseif(auth()->user()->isUnemployed())
                            Panel de control para cesantes
                        @else
                            Panel de administración
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Último acceso:</p>
                    <p class="text-sm font-medium">
                        {{ auth()->user()->last_login_at ? auth()->user()->last_login_at->format('d/m/Y H:i') : 'Primera vez' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @if(auth()->user()->isCompany())
                <!-- Acciones para Empresas -->
                <a href="{{ route('job-offers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow-sm transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-plus-circle text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Crear Oferta</h3>
                            <p class="text-sm opacity-90">Publica una nueva oferta laboral</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('job-offers.index') }}" class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg shadow-sm transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-briefcase text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Mis Ofertas</h3>
                            <p class="text-sm opacity-90">Gestiona tus ofertas publicadas</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('companies.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg shadow-sm transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-building text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Mi Empresa</h3>
                            <p class="text-sm opacity-90">Edita tu perfil empresarial</p>
                        </div>
                    </div>
                </a>
                
            @elseif(auth()->user()->isUnemployed())
                <!-- Acciones para Cesantes -->
                <a href="{{ route('job-offers.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow-sm transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-search text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Buscar Empleos</h3>
                            <p class="text-sm opacity-90">Encuentra tu próxima oportunidad</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('portfolios.index') }}" class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg shadow-sm transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-folder text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Mi Portafolio</h3>
                            <p class="text-sm opacity-90">Gestiona tus proyectos</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('trainings.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg shadow-sm transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Capacitaciones</h3>
                            <p class="text-sm opacity-90">Mejora tus habilidades</p>
                        </div>
                    </div>
                </a>
                
            @else
                <!-- Acciones para Admin -->
                <a href="{{ route('users.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-6 rounded-lg shadow-sm transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-users text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Usuarios</h3>
                            <p class="text-sm opacity-90">Gestionar usuarios del sistema</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('categories.index') }}" class="bg-green-600 hover:bg-green-700 text-white p-6 rounded-lg shadow-sm transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-tags text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Categorías</h3>
                            <p class="text-sm opacity-90">Gestionar categorías</p>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('job-offers.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white p-6 rounded-lg shadow-sm transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-briefcase text-2xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold">Ofertas</h3>
                            <p class="text-sm opacity-90">Supervisar ofertas laborales</p>
                        </div>
                    </div>
                </a>
            @endif
        </div>

        <!-- Estadísticas Básicas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">
                    {{ \App\Models\JobOffer::where('status', true)->count() }}
                </div>
                <div class="text-gray-600">Ofertas Activas</div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">
                    {{ \App\Models\Company::count() }}
                </div>
                <div class="text-gray-600">Empresas Registradas</div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">
                    {{ \App\Models\Training::count() }}
                </div>
                <div class="text-gray-600">Capacitaciones</div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-orange-600 mb-2">
                    {{ \App\Models\User::count() }}
                </div>
                <div class="text-gray-600">Usuarios Totales</div>
            </div>
        </div>

        <!-- Información del Perfil -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-4">Estado del Perfil</h2>
            
            @if(auth()->user()->isCompany())
                @if(!auth()->user()->company)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                            <div>
                                <h3 class="font-medium text-yellow-800">Perfil de Empresa Incompleto</h3>
                                <p class="text-yellow-700 text-sm">Completa tu perfil de empresa para acceder a todas las funcionalidades.</p>
                                <a href="{{ route('companies.create') }}" class="text-yellow-800 underline text-sm font-medium">
                                    Completar perfil →
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-3"></i>
                            <div>
                                <h3 class="font-medium text-green-800">Perfil de Empresa Completo</h3>
                                <p class="text-green-700 text-sm">
                                    Tu empresa <strong>{{ auth()->user()->company->company_name }}</strong> está registrada y activa. 
                                    Ahora puedes publicar ofertas de trabajo, gestionar candidatos y acceder a todas las funcionalidades.
                                </p>
                                <div class="mt-2 space-x-4">
                                    <a href="{{ route('job-offers.create') }}" class="text-green-800 underline text-sm font-medium">
                                        Crear primera oferta →
                                    </a>
                                    <a href="{{ route('companies.show', auth()->user()->company) }}" class="text-green-800 underline text-sm font-medium">
                                        Ver perfil empresa →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
            
            @if(auth()->user()->isUnemployed() && !auth()->user()->unemployed)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                        <div>
                            <h3 class="font-medium text-yellow-800">Perfil Incompleto</h3>
                            <p class="text-yellow-700 text-sm">Completa tu perfil para acceder a todas las funcionalidades.</p>
                            <a href="{{ route('unemployed.create') }}" class="text-yellow-800 underline text-sm font-medium">
                                Completar perfil →
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h3 class="font-medium text-gray-800 mb-2">Información Personal</h3>
                    <p class="text-sm text-gray-600">Nombre: {{ auth()->user()->name }}</p>
                    <p class="text-sm text-gray-600">Email: {{ auth()->user()->email }}</p>
                    <p class="text-sm text-gray-600">Tipo: {{ ucfirst(auth()->user()->type) }}</p>
                </div>
                
                <div>
                    <h3 class="font-medium text-gray-800 mb-2">Acciones</h3>
                    <div class="space-y-2">
                        <a href="{{ route('profile.edit') }}" class="block text-sm text-blue-600 hover:text-blue-800">
                            Editar perfil personal
                        </a>
                        <a href="{{ route('messages.index') }}" class="block text-sm text-blue-600 hover:text-blue-800">
                            Ver mensajes
                        </a>
                        <a href="{{ route('notifications.index') }}" class="block text-sm text-blue-600 hover:text-blue-800">
                            Ver notificaciones
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function closeMessage(messageId) {
    const message = document.getElementById(messageId);
    if (message) {
        message.style.opacity = '0';
        setTimeout(() => {
            message.remove();
        }, 500);
    }
}

// Auto-hide success messages after 8 seconds
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        setTimeout(() => {
            closeMessage('success-message');
        }, 8000);
    }
});
</script>
@endpush

@endsection