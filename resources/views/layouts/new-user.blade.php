<!DOCTYPE html>
<html lang="es">
@include('includes.links')

<body class="bg-gray-100 text-gray-800">
    @include('includes/headers/header-register')

    <div class="main-content">
        @yield('content')
    </div>

    @include('includes.footer')
    @vite('resources/js/app.js')
</body>
</html>