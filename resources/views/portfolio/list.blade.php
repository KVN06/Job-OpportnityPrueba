@extends('layouts.home') 
<!-- Extiende la plantilla base -->

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Mis Portafolios</h1>
    <!-- Título principal -->

    <div class="text-right mb-4">
        <!-- Botón para agregar un nuevo portafolio -->
        <a href="{{ route('portfolio-form') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-400">
            Agregar Portafolio
        </a>
    </div>

    @if($portfolios->isEmpty())
        <!-- Mensaje si el usuario no tiene portafolios -->
        <p>No tienes portafolios aún.</p>
    @else
        <div class="space-y-4">
            <!-- Lista de portafolios -->
            @foreach($portfolios as $portfolio)
                <div class="bg-white shadow rounded-lg p-4">
                    <!-- Tarjeta individual del portafolio -->
                    <h2 class="text-xl font-semibold">{{ $portfolio->title }}</h2>
                    <p>{{ $portfolio->description }}</p>
                    <a href="{{ $portfolio->file_url }}" target="_blank" class="text-blue-500 hover:underline">
                        Ver URL del portafolio
                    </a>

                    <div class="mt-2 flex gap-2">
                        <!-- Botón para editar el portafolio -->
                        <a href="{{ route('edit-portfolio', $portfolio->id) }}" 
                           class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors">
                            Editar
                        </a>

                        <!-- Formulario para eliminar el portafolio -->
                        <form action="{{ route('delete-portfolio', $portfolio->id) }}" method="POST" 
                              onsubmit="return confirm('¿Estás seguro de eliminar este portafolio?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors ml-2">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
