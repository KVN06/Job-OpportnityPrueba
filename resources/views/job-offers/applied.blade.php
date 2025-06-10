@extends('layouts.new-user')

@section('title', 'Postulación enviada')

@section('content')
<div class="max-w-lg mx-auto mt-20 bg-white p-10 rounded-lg shadow-lg text-center">
    <h1 class="text-3xl font-bold text-green-600 mb-6">¡Te has postulado!</h1>
    <p class="text-lg text-gray-700 mb-4">
        Tu postulación ha sido enviada correctamente.<br>
        Ahora solo espera a recibir notificaciones si eres seleccionado o si hay novedades sobre la oferta.
    </p>
    <a href="{{ route('applications.index') }}" class="inline-block mt-6 px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">Ver mis postulaciones</a>
    <a href="{{ route('home') }}" class="inline-block mt-6 ml-4 px-6 py-3 bg-gray-200 text-gray-800 rounded-lg font-semibold hover:bg-gray-300 transition">Volver al inicio</a>
</div>
@endsection
