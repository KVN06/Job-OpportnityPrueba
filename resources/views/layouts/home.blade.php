<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Job Opportunity') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    @yield('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        @include('includes.headers.header-profile')

        <!-- Page Content -->
        <main class="py-4">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="container mx-auto px-4 mb-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none'">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="container mx-auto px-4 mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none'">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                </div>
            @endif

            @if (session('info'))
                <div class="container mx-auto px-4 mb-4">
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('info') }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-blue-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none'">
                                <title>Close</title>
                                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                            </svg>
                        </span>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        @include('includes.footer')
    </div>

    @yield('scripts')

    <!-- JavaScript para funcionalidad del navbar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle para el menú móvil
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const mobileMenu = document.getElementById('mobileMenu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            // Toggle para el dropdown de usuario
            const userDropdown = document.getElementById('userDropdown');
            if (userDropdown) {
                const userButton = userDropdown.querySelector('button');
                const userMenu = userDropdown.querySelector('div.hidden');
                
                if (userButton && userMenu) {
                    userButton.addEventListener('click', function(e) {
                        e.stopPropagation();
                        userMenu.classList.toggle('hidden');
                        // Cerrar el dropdown de notificaciones si está abierto
                        const notificationPanel = document.getElementById('notificationPanel');
                        if (notificationPanel && !notificationPanel.classList.contains('hidden')) {
                            notificationPanel.classList.add('hidden');
                        }
                    });
                }
            }

            // Toggle para el dropdown de notificaciones
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            if (notificationsDropdown) {
                const notificationButton = notificationsDropdown.querySelector('button');
                const notificationPanel = document.getElementById('notificationPanel');
                
                if (notificationButton && notificationPanel) {
                    notificationButton.addEventListener('click', function(e) {
                        e.stopPropagation();
                        notificationPanel.classList.toggle('hidden');
                        // Cerrar el dropdown de usuario si está abierto
                        const userMenu = document.querySelector('#userDropdown div.hidden');
                        if (userMenu && !userMenu.classList.contains('hidden')) {
                            userMenu.classList.add('hidden');
                        }
                        
                        // Cargar notificaciones si el panel se abre
                        if (!notificationPanel.classList.contains('hidden')) {
                            loadNotifications();
                        }
                    });
                }
            }

            // Cerrar dropdowns al hacer clic fuera
            document.addEventListener('click', function() {
                // Cerrar dropdown de usuario
                const userMenu = document.querySelector('#userDropdown div.hidden');
                if (userMenu && !userMenu.classList.contains('hidden')) {
                    userMenu.classList.add('hidden');
                }
                
                // Cerrar dropdown de notificaciones
                const notificationPanel = document.getElementById('notificationPanel');
                if (notificationPanel && !notificationPanel.classList.contains('hidden')) {
                    notificationPanel.classList.add('hidden');
                }
            });

            // Función para cargar notificaciones
            function loadNotifications() {
                const notificationList = document.getElementById('notificationList');
                const notificationCount = document.getElementById('notificationCount');
                
                if (!notificationList) return;

                // Mostrar loading
                notificationList.innerHTML = '<div class="p-4 text-center text-gray-500">Cargando...</div>';

                // Simular carga de notificaciones (aquí puedes hacer una llamada AJAX real)
                setTimeout(() => {
                    // Ejemplo de notificaciones
                    const notifications = [
                        {
                            id: 1,
                            title: 'Nueva aplicación recibida',
                            message: 'Juan Pérez aplicó a tu oferta de Desarrollador PHP',
                            time: 'Hace 5 minutos',
                            read: false
                        },
                        {
                            id: 2,
                            title: 'Mensaje nuevo',
                            message: 'Tienes un nuevo mensaje de TechCorp',
                            time: 'Hace 1 hora',
                            read: false
                        }
                    ];

                    if (notifications.length === 0) {
                        notificationList.innerHTML = '<div class="p-4 text-center text-gray-500">No tienes notificaciones</div>';
                        notificationCount.textContent = '0';
                    } else {
                        let html = '';
                        notifications.forEach(notification => {
                            html += `
                                <div class="p-4 border-b hover:bg-gray-50 cursor-pointer ${!notification.read ? 'bg-blue-50' : ''}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">${notification.title}</h4>
                                            <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                                            <p class="text-xs text-gray-400 mt-2">${notification.time}</p>
                                        </div>
                                        ${!notification.read ? '<div class="w-2 h-2 bg-blue-500 rounded-full ml-2 mt-1"></div>' : ''}
                                    </div>
                                </div>
                            `;
                        });
                        
                        notificationList.innerHTML = html;
                        const unreadCount = notifications.filter(n => !n.read).length;
                        notificationCount.textContent = unreadCount;
                        
                        if (unreadCount === 0) {
                            notificationCount.classList.add('hidden');
                        } else {
                            notificationCount.classList.remove('hidden');
                        }
                    }
                }, 500);
            }

            // Cargar notificaciones al cargar la página
            loadNotifications();
        });
    </script>
</body>
</html>