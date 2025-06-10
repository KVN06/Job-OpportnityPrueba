<header class="bg-white shadow mb-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo de la aplicación con enlace a la página principal -->
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="Estilos/Imagenes/proyecto.jpeg" alt="Job Opportunity Logo" class="w-12 h-12 rounded-full mr-3">
                <span class="text-xl font-bold">JOB OPPORTUNITY</span>
            </a>

            <!-- Menú de navegación visible en pantallas medianas en adelante -->
            <nav class="hidden md:flex items-center space-x-6">
                @auth
                    <!-- Enlaces principales del menú -->
                    <a href="{{ url('/') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Inicio</a>
                    <a href="{{ route('trainings.index') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Capacitaciones</a>
                    <a href="{{ route('job-offers.index', ['offer_type' => 'classified']) }}" class="text-gray-600 hover:text-blue-600 transition-colors">Clasificados</a>
                    <a href="#" class="text-gray-600 hover:text-blue-600 transition-colors">FAQs</a>

                    <!-- Ícono de notificaciones con contador -->
                    <div class="relative" id="notificationsDropdown">
                        <button class="text-gray-600 hover:text-blue-600 relative">
                            <i class="fas fa-bell text-xl"></i>
                            <!-- Contador de notificaciones -->
                            <span id="notificationCount" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                        </button>
                        <!-- Panel desplegable de notificaciones -->
                        <div id="notificationPanel" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50">
                            <div class="p-4 border-b flex justify-between items-center">
                                <h3 class="text-lg font-semibold">Notificaciones</h3>
                                <!-- Botón de configuración -->
                                <button class="text-gray-400 hover:text-blue-600">
                                    <i class="fas fa-cog"></i>
                                </button>
                            </div>
                            <!-- Lista de notificaciones dinámicas -->
                            <div id="notificationList" class="max-h-96 overflow-y-auto">
                                <!-- Aquí se cargarán las notificaciones -->
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown de usuario con su información y enlaces personalizados -->
                    <div class="relative" id="userDropdown">
                        <button class="flex items-center text-gray-600 hover:text-blue-600">
                            <!-- Imagen del usuario -->
                            <img id="navbar-avatar" src="{{ auth()->user()->avatar_url }}" alt="Usuario" class="w-8 h-8 rounded-full mr-2">
                            <div class="flex flex-col items-center">
                                <span class="text-sm font-medium max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <!-- Rol del usuario: Empresa o Cesante -->
                                <span class="text-xs text-gray-500 -mt-0.5">
                                    @if(auth()->user()->isCompany())
                                        Empresa
                                    @else
                                        Cesante
                                    @endif
                                </span>
                            </div>
                            <!-- Ícono de flecha para indicar que hay un menú desplegable -->
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>

                        <!-- Menú desplegable del usuario -->
                        <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl z-50">
                            <!-- Mi Perfil -->
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Mi Perfil
                            </a>
                            @if(auth()->user()->isUnemployed())
                                <!-- Opción para ver el portafolio si es cesante -->
                                <a href="{{ route('portfolios.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Portafolio
                                </a>
                            @endif

                            <!-- Opciones de creación de ofertas -->
                            @if(auth()->user()->isCompany())
                                <a href="{{ route('job-offers.create', ['type' => 'contract']) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Crear Oferta Laboral
                                </a>
                            @endif
                            <a href="{{ route('job-offers.create', ['type' => 'classified']) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                Crear Clasificado
                            </a>
                            <!-- Enlace a la sección de mensajes -->
                            <a href="{{ route('messages.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Mensajes</a>
                            <!-- Enlace de configuración -->
                            <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Configuración</a>
                            <hr class="my-2">
                            <!-- Enlace para cerrar sesión -->
                            <form action="{{ route('logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Cerrar Sesión</button>
                            </form>
                        </div>
                    </div>
                @endauth
            </nav>

            <!-- Botón hamburguesa para mostrar menú móvil -->
            <button class="md:hidden text-gray-600 hover:text-blue-600" id="mobileMenuButton">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>

        <!-- Menú móvil que se muestra al hacer clic en el botón hamburguesa -->
        <div class="md:hidden hidden" id="mobileMenu">
            <nav class="py-4 space-y-4 px-2">
                @auth
                    <!-- Repetición del menú para pantallas pequeñas -->
                    <a href="{{ url('/') }}" class="block text-gray-600 hover:text-blue-600">Inicio</a>
                    <a href="{{ route('trainings.index') }}" class="block text-gray-600 hover:text-blue-600">Capacitaciones</a>
                    <a href="{{ route('job-offers.index', ['offer_type' => 'classified']) }}" class="block text-gray-600 hover:text-blue-600">Clasificados</a>
                    <a href="#" class="block text-gray-600 hover:text-blue-600">FAQs</a>
                    <a href="{{ route('profile.edit') }}" class="block text-gray-600 hover:text-blue-600">Mi Perfil</a>
                    @if(auth()->user()->isUnemployed())
                        <a href="{{ route('portfolios.index') }}" class="block text-gray-600 hover:text-blue-600">Portafolio</a>
                    @endif
                    @if(auth()->user()->isCompany())
                        <a href="{{ route('job-offers.create', ['type' => 'contract']) }}" class="block text-gray-600 hover:text-blue-600">Crear Oferta Laboral</a>
                    @endif
                    <a href="{{ route('job-offers.create', ['type' => 'classified']) }}" class="block text-gray-600 hover:text-blue-600">Crear Clasificado</a>
                    <a href="{{ route('messages.index') }}" class="block text-gray-600 hover:text-blue-600">Mensajes</a>
                    <a href="{{ route('settings.index') }}" class="block text-gray-600 hover:text-blue-600">Configuración</a>
                    <a href="{{ route('logout') }}" class="block text-red-600 hover:text-red-800">Cerrar Sesión</a>
                @endauth
            </nav>
        </div>
    </div>
</header>
<!-- No usar localStorage para la foto de perfil en el navbar, siempre mostrar la del backend -->
