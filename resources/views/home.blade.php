@extends('layouts.home')

@section('content')

@auth
@if(auth()->user()->type === 'company')

    {{-- Home para empresa --}}
    @if(session('success'))
        <div class="max-w-xl mx-auto mt-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <main class="container mx-auto py-8 px-6">

        <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl p-12 mb-12">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Gestiona tus ofertas laborales</h1>
                <p class="text-xl mb-8">Encuentra los mejores talentos para tu empresa de manera rápida y eficiente</p>
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <a href="{{ route('job-offers.create') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        Publicar Nueva Oferta
                    </a>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">12</div>
                <div class="text-gray-600">Ofertas Activas</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">136</div>
                <div class="text-gray-600">Aplicaciones Recibidas</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">8</div>
                <div class="text-gray-600">Entrevistas Programadas</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">85%</div>
                <div class="text-gray-600">Tasa de Respuesta</div>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6">Acciones Rápidas</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Botón para Ofertas Laborales (Contratos) -->
                <a href="{{ route('job-offers.create', ['type' => 'contract']) }}" class="group">
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center">
                        <div class="text-3xl text-blue-600 mb-3">
                            <i class="fas fa-building"></i>
                        </div>
                        <h3 class="font-semibold text-lg mb-2 group-hover:text-blue-600">Publicar Oferta Empresarial</h3>
                        <p class="text-sm text-gray-600 mb-4">Crea y publica una oferta con contrato</p>
                        <span class="text-blue-600 text-sm font-medium">Crear ahora</span>
                    </div>
                </a>
                
                <!-- Botón para Clasificados -->
                <a href="{{ route('job-offers.create', ['type' => 'classified']) }}" class="group">
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center">
                        <div class="text-3xl text-yellow-600 mb-3">
                            <i class="fas fa-clipboard"></i>
                        </div>
                        <h3 class="font-semibold text-lg mb-2 group-hover:text-yellow-600">Publicar Clasificado</h3>
                        <p class="text-sm text-gray-600 mb-4">Crea y publica un trabajo temporal</p>
                        <span class="text-yellow-600 text-sm font-medium">Crear ahora</span>
                    </div>
                </a>
                <a href="#" class="group">
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center cursor-not-allowed opacity-50">
                        <i class="fas fa-users text-3xl text-blue-600 mb-3"></i>
                        <h3 class="font-semibold text-lg mb-2">Gestionar Candidatos</h3>
                        <p class="text-sm text-gray-600 mb-4">Funcionalidad no implementada</p>
                        <span class="text-blue-600 text-sm font-medium">Próximamente</span>
                    </div>
                </a>
                <a href="#" class="group">
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center cursor-not-allowed opacity-50">
                        <i class="fas fa-chart-line text-3xl text-blue-600 mb-3"></i>
                        <h3 class="font-semibold text-lg mb-2">Ver Estadísticas</h3>
                        <p class="text-sm text-gray-600 mb-4">Funcionalidad no implementada</p>
                        <span class="text-blue-600 text-sm font-medium">Próximamente</span>
                    </div>
                </a>
                <a href="#" class="group">
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center cursor-not-allowed opacity-50">
                        <i class="fas fa-search text-3xl text-blue-600 mb-3"></i>
                        <h3 class="font-semibold text-lg mb-2">Buscar Talentos</h3>
                        <p class="text-sm text-gray-600 mb-4">Funcionalidad no implementada</p>
                        <span class="text-blue-600 text-sm font-medium">Próximamente</span>
                    </div>
                </a>
            </div>
        </section>

    </main>
@elseif(auth()->user()->type === 'unemployed')

    {{-- Home para desempleado --}}
    <main class="container mx-auto py-8 px-6">

        <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl p-12 mb-12">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Encuentra tu próxima oportunidad laboral</h1>
                <p class="text-xl mb-8">Conectamos talento con las mejores empresas</p>
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <a href="{{ route('job-offers.index') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        Buscar Empleos
                    </a>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">5,000+</div>
                <div class="text-gray-600">Empleos Disponibles</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">2,500+</div>
                <div class="text-gray-600">Empresas Registradas</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">75%</div>
                <div class="text-gray-600">Tasa de Colocación</div>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6">Acciones Rápidas</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('profile.edit') }}" class="group">
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center">
                        <i class="fas fa-user-edit text-3xl text-blue-600 mb-3"></i>
                        <h3 class="font-semibold text-lg mb-2">Editar Perfil</h3>
                        <p class="text-sm text-gray-600 mb-4">Personaliza tu información profesional</p>
                        <span class="text-blue-600 text-sm font-medium">Editar ahora</span>
                    </div>
                </a>
                <a href="{{ route('portfolios.index') }}" class="group">
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center">
                        <i class="fas fa-briefcase text-3xl text-blue-600 mb-3"></i>
                        <h3 class="font-semibold text-lg mb-2">Portafolio</h3>
                        <p class="text-sm text-gray-600 mb-4">Gestiona tus proyectos y logros</p>
                        <span class="text-blue-600 text-sm font-medium">Ver portafolio</span>
                    </div>
                </a>
                <a href="{{ route('job-offers.index') }}" class="group">
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center">
                        <i class="fas fa-search text-3xl text-blue-600 mb-3"></i>
                        <h3 class="font-semibold text-lg mb-2">Buscar Empleos</h3>
                        <p class="text-sm text-gray-600 mb-4">Encuentra ofertas que se ajusten a ti</p>
                        <span class="text-blue-600 text-sm font-medium">Buscar ahora</span>
                    </div>
                </a>
            </div>
        </section>

    </main>

@else

    <p class="text-center text-red-600 mt-20 font-semibold">Tipo de usuario desconocido.</p>

@endif
@else
    {{-- Contenido para usuarios no autenticados --}}
    <main class="container mx-auto py-8 px-6">
        <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl p-12 mb-12">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">Bienvenido a Job Opportunity</h1>
                <p class="text-xl mb-8">Conectamos talento con las mejores oportunidades laborales</p>
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        Registrarse
                    </a>
                    <a href="{{ route('login') }}" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                        Iniciar Sesión
                    </a>
                </div>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">5,000+</div>
                <div class="text-gray-600">Empleos Disponibles</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">2,500+</div>
                <div class="text-gray-600">Empresas Registradas</div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">75%</div>
                <div class="text-gray-600">Tasa de Colocación</div>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6 text-center">¿Por qué elegir Job Opportunity?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                    <i class="fas fa-search text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold text-lg mb-2">Búsqueda Inteligente</h3>
                    <p class="text-sm text-gray-600">Encuentra ofertas que se ajusten perfectamente a tu perfil profesional</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                    <i class="fas fa-handshake text-3xl text-green-600 mb-3"></i>
                    <h3 class="font-semibold text-lg mb-2">Conexión Directa</h3>
                    <p class="text-sm text-gray-600">Conecta directamente con empresas y reclutadores</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm text-center">
                    <i class="fas fa-graduation-cap text-3xl text-purple-600 mb-3"></i>
                    <h3 class="font-semibold text-lg mb-2">Capacitación</h3>
                    <p class="text-sm text-gray-600">Accede a cursos y capacitaciones para mejorar tus habilidades</p>
                </div>
            </div>
        </section>
    </main>
@endauth

@endsection
