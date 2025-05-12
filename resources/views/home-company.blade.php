@extends('layouts.home') 
<!-- Utiliza la plantilla principal 'home' -->

@section('content')
<!-- Contenido principal -->
<main class="container mx-auto py-8 px-6">

    <!-- Sección de bienvenida / Hero -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl p-12 mb-12">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Gestiona tus ofertas laborales</h1>
            <p class="text-xl mb-8">Encuentra los mejores talentos para tu empresa de manera rápida y eficiente</p>
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <!-- Botón para crear nueva oferta -->
                <a href="{{ route('job-offers.create') }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Publicar Nueva Oferta
                </a>
            </div>
        </div>
    </section>

    <!-- Sección de estadísticas principales -->
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

    <!-- Acciones rápidas para el empleador -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Acciones Rápidas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Cada opción lleva a una función importante -->
            <a href="{{ route('job-offers.create') }}" class="group">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center">
                    <div class="text-3xl text-blue-600 mb-3">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2 group-hover:text-blue-600">Publicar Oferta</h3>
                    <p class="text-sm text-gray-600 mb-4">Crea y publica una nueva oferta laboral</p>
                    <span class="text-blue-600 text-sm font-medium">Crear ahora</span>
                </div>
            </a>
            <a href="" class="group">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center">
                    <i class="fas fa-users text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold text-lg mb-2 group-hover:text-blue-600">Gestionar Candidatos</h3>
                    <p class="text-sm text-gray-600 mb-4">Revisa aplicaciones y gestiona el proceso</p>
                    <span class="text-blue-600 text-sm font-medium">Ver candidatos</span>
                </div>
            </a>
            <a href="" class="group">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center">
                    <i class="fas fa-chart-line text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold text-lg mb-2 group-hover:text-blue-600">Ver Estadísticas</h3>
                    <p class="text-sm text-gray-600 mb-4">Analiza el rendimiento de tus ofertas</p>
                    <span class="text-blue-600 text-sm font-medium">Ver informes</span>
                </div>
            </a>
            <a href="" class="group">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow flex flex-col items-center text-center">
                    <i class="fas fa-search text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold text-lg mb-2 group-hover:text-blue-600">Buscar Talentos</h3>
                    <p class="text-sm text-gray-600 mb-4">Encuentra candidatos para tus necesidades</p>
                    <span class="text-blue-600 text-sm font-medium">Buscar ahora</span>
                </div>
            </a>
        </div>
    </section>

    <!-- Sección de ofertas laborales publicadas -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Tus Ofertas Publicadas</h2>
            <a href="{{ route('job-offers.index') }}" class="text-blue-600 hover:text-blue-800">Ver todas →</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Tarjeta de una oferta laboral -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-lg mb-2">Desarrollador Frontend</h3>
                <p class="text-gray-600 mb-4">Tech Solutions</p>
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="badge"><i class="fas fa-map-marker-alt mr-1"></i> Remoto</span>
                    <span class="badge"><i class="fas fa-clock mr-1"></i> Tiempo completo</span>
                    <span class="badge"><i class="fas fa-eye mr-1"></i> 156 vistas</span>
                </div>
                <div class="flex justify-between items-center">
                    <a href="" class="text-blue-600 text-sm font-medium">Ver aplicaciones (12)</a>
                    <button class="text-yellow-400 hover:text-yellow-500"><i class="fas fa-star"></i></button>
                </div>
            </div>

            <!-- Otras ofertas similares -->
            <!-- ... mismas estructuras para Backend Java y UI/UX ... -->
        </div>
    </section>

    <!-- Sección de candidatos recomendados -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Candidatos Recomendados</h2>
            <a href="" class="text-blue-600 hover:text-blue-800">Ver todos →</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Tarjeta de candidato -->
            <div class="bg-white p-6 rounded-lg shadow-sm flex flex-col items-center text-center">
                <div class="w-20 h-20 rounded-full bg-gray-200 mb-4 overflow-hidden">
                    <img src="" alt="Ana García" class="w-full h-full object-cover">
                </div>
                <h3 class="font-bold text-lg mb-1">Ana García</h3>
                <p class="text-gray-600 text-sm mb-4">Desarrolladora Frontend Senior</p>
                <div class="flex flex-wrap gap-1 justify-center mb-4">
                    <span class="skill">React</span>
                    <span class="skill">JavaScript</span>
                    <span class="skill">CSS</span>
                </div>
                <a href="" class="text-blue-600 text-sm font-medium">Ver perfil completo</a>
            </div>

            <!-- Más candidatos similares -->
            <!-- ... mismas estructuras para Carlos, Sofía y Miguel ... -->
        </div>
    </section>
</main>
@endsection
