@extends('layouts.app')

@section('content')
<div class="main-actions">
    <div class="left-actions">
        <div class="search-bar">
            <input type="text" placeholder="Buscar trabajos...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
        <div class="filter-buttons">
            <button class="filter-btn"><i class="fas fa-filter"></i> Filtros</button>
            <a href="clasificados.html" class="classified-btn"><i class="fas fa-list"></i> Clasificados</a>
        </div>
    </div>
    <div class="right-actions">
        <div class="add-job"> 
            <button id="addJobBtn"><i class="fas fa-plus"></i>Agregar Trabajo</button>
        </div>
        <div class="notification-bell">
            <i class="fas fa-bell"></i>
            <span class="notification-count">3</span>
        </div>
    </div>
</div>

<section class="intro">
    <h1>Bienvenido a Job Opportunity</h1>
    <p>Tu plataforma para encontrar empleo y recursos que te ayudarán a mejorar tu perfil laboral.</p>
</section>

<section class="job-posts">
    <h2>Trabajos Disponibles</h2>
    <div class="job-cards" id="jobCards">
        <div class="job-card">
            <div class="card-content">
                <h3>Trabajo 1</h3>
                <p>Descripción breve del trabajo 1. Aquí se puede incluir más información sobre el trabajo, los requisitos y otros detalles relevantes.</p>
            </div>
        </div>
    </div>
</section>
</main>
@endsection

@csrf

