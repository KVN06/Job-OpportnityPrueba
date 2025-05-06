<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;


//Rutas para la vista 
Route::view('/login', 'login')->name('login');
Route::view('/home', 'home')->middleware('auth')->name('home');

//Rutas para crear un Usuario
Route::get('/register',[UserController::class,'create'])->name('register');
Route::post('/crearUsuario',[UserController::class,'agg_user'])->name('create-user');
Route::post('/inicia-sesion',[UserController::class,'login'])->name('inicia-sesion');
Route::get('/logout',[UserController::class,'logout'])->name('logout');


Route::get('/message/create', [MessageController::class, 'create'])->name('message-form');
Route::post('/message/send', [MessageController::class, 'send_message'])->name('send-message');
Route::get('/messages', [MessageController::class, 'index'])->name('messages')->middleware('auth');



Route::get('/unemployed-form', [UnemployedController::class, 'create'])->name('unemployed-form');
Route::post('/agg-unemployed', [UnemployedController::class, 'agg_unemployed'])->name('agg-unemployed');
Route::get('/company-form', [CompanyController::class, 'create'])->name('company-form');
Route::post('/agg-company', [CompanyController::class, 'agg_company'])->name('agg-company');



    // Ver la lista de portafolios
    Route::get('/portfolio-list', [PortfolioController::class, 'list'])->name('portfolio-list');
    
    // Crear un nuevo portafolio
    Route::get('/portfolio-form', [PortfolioController::class, 'create'])->name('portfolio-form');
    Route::post('/agg-portfolio', [PortfolioController::class, 'store'])->name('agg-portfolio');
    
    // Editar un portafolio
    Route::get('/portfolio-edit/{id}', [PortfolioController::class, 'edit'])->name('edit-portfolio');
    Route::post('/portfolio-update/{id}', [PortfolioController::class, 'update'])->name('update-portfolio');
    
    // Eliminar un portafolio
    Route::delete('/portfolio-delete/{id}', [PortfolioController::class, 'destroy'])->name('delete-portfolio');



    Route::get('/joboffers', [JobOfferController::class, 'index'])->name('job-offers.index');
    Route::get('/crear', [JobOfferController::class, 'create'])->name('job-offers.create');
    Route::post('/joboffers', [JobOfferController::class, 'store'])->name('job-offers.store');
    Route::get('/joboffers/{jobOffer}', [JobOfferController::class, 'show'])->name('job-offers.show');
    Route::get('/joboffers/{jobOffer}/editar', [JobOfferController::class, 'edit'])->name('job-offers.edit');
    Route::put('/joboffers/{jobOffer}', [JobOfferController::class, 'update'])->name('job-offers.update');
    Route::delete('/joboffers/{jobOffer}', [JobOfferController::class, 'destroy'])->name('job-offers.destroy');
    

    // Rutas de ofertas favoritas
    Route::get('/favoriteOffer', [FavoriteOfferController::class, 'index'])->name('favorite-offers.index');
    Route::post('//ofertas/{jobOffer}/favorite', [FavoriteOfferController::class, 'toggle'])->name('job-offers.toggle-favorite');
    // Rutas de postulacioness
    Route::post('/job-applications', [JobApplicationController::class, 'store'])->name('job-applications.store');

    Route::get('/Companies', [CompanyController::class, 'index'])->name('index');
    Route::get('/Company/{company}', [CompanyController::class, 'show'])->name('show');




