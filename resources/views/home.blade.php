@extends('layouts.home')

@section('content')
<!-- Main Content -->
<main class="container mx-auto py-8 px-6">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl p-12 mb-12">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Encuentra tu próxima oportunidad laboral</h1>
            <p class="text-xl mb-8">Conectamos talento con las mejores empresas</p>
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Buscar Empleos
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
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
            <div class="text-3xl font-bold text-blue-600 mb-2">10,000+</div>
            <div class="text-gray-600">Candidatos Colocados</div>
        </div>
    </section>

    <!-- Featured Jobs -->
    <section class="mb-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Empleos Destacados</h2>
            <a href="" class="text-blue-600 hover:text-blue-800">Ver todos →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="featuredJobs">
            <!-- Los trabajos se cargarán dinámicamente aquí -->
        </div>
    </section>

    <!-- Categories Section -->
    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Categorías Populares</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="" class="group">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <i class="fas fa-laptop-code text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold group-hover:text-blue-600">Tecnología</h3>
                    <p class="text-sm text-gray-600">1,200+ empleos</p>
                </div>
            </a>
            <a href="" class="group">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <i class="fas fa-chart-line text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold group-hover:text-blue-600">Marketing</h3>
                    <p class="text-sm text-gray-600">800+ empleos</p>
                </div>
            </a>
            <a href="" class="group">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <i class="fas fa-paint-brush text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold group-hover:text-blue-600">Diseño</h3>
                    <p class="text-sm text-gray-600">600+ empleos</p>
                </div>
            </a>
            <a href="" class="group">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <i class="fas fa-handshake text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-semibold group-hover:text-blue-600">Ventas</h3>
                    <p class="text-sm text-gray-600">900+ empleos</p>
                </div>
            </a>
        </div>
    </section>

    <!-- Resources Section -->
    <section class="bg-gray-50 rounded-xl p-8 mb-12">
        <h2 class="text-2xl font-bold mb-6">Recursos para tu Carrera</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <i class="fas fa-users text-2xl text-blue-600 mb-3"></i>
                <h3 class="font-semibold mb-2">Foros de Discusión</h3>
                <p class="text-gray-600 text-sm">Conéctate con otros profesionales</p>
            </a>
            <a href="" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <i class="fas fa-graduation-cap text-2xl text-blue-600 mb-3"></i>
                <h3 class="font-semibold mb-2">Capacitaciones</h3>
                <p class="text-gray-600 text-sm">Mejora tus habilidades</p>
            </a>
            <a href="" class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <i class="fas fa-book-reader text-2xl text-blue-600 mb-3"></i>
                <h3 class="font-semibold mb-2">Blog</h3>
                <p class="text-gray-600 text-sm">Consejos y tendencias</p>
            </a>
        </div>
    </section>
</main>
@endsection

@csrf

