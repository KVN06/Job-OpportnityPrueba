<!DOCTYPE html>
<html lang="es">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('includes.links')

<body class="bg-gray-100 text-gray-800">
    @include('includes/headers/header-profile')

    <div class="main-content">
        @yield('content')
    </div>

    @include('includes.footer')
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>