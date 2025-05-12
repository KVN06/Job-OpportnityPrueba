<header class="bg-white shadow mb-8">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="" class="flex items-center">
                        <img src="Estilos/Imagenes/proyecto.jpeg" alt="Job Opportunity Logo" class="w-12 h-12 rounded-full mr-3">
                        <span class="text-xl font-bold">JOB OPPORTUNITY</span>
                    </a>
                </div>

                <!-- Menú de navegación -->
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Inicio</a>
                    <a href="{{ route('training.index') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Capacitaciones</a>
                    <a href="" class="text-gray-600 hover:text-blue-600 transition-colors">Clasificados</a>
                    <a href="" class="text-gray-600 hover:text-blue-600 transition-colors">FAQs</a>
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Registrarse</a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Iniciar Sesion</a>
                </nav>

                <!-- Menú móvil -->
                <button class="md:hidden text-gray-600 hover:text-blue-600" id="mobileMenuButton">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Menú móvil expandible -->
            <div class="md:hidden hidden" id="mobileMenu">
                <nav class="py-4 space-y-4">
                    <a href="{{ route('home') }}" class="block text-gray-600 hover:text-blue-600">Inicio</a>
                    <a href="" class="block text-gray-600 hover:text-blue-600">Clasificados</a>
                    <a href="" class="block text-gray-600 hover:text-blue-600">Mensajes</a>
                    <a href="" class="block text-gray-600 hover:text-blue-600">Configuración</a>
                    <a href="{{ route('register') }}" class="block text-red-600 hover:text-red-800">Registrarse</a>
                    <a href="{{ route('login') }}" class="block text-red-600 hover:text-red-800">Iniciar Sesion</a>
                </nav>
            </div>
        </div>
    </header>