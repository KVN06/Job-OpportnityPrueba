<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;


Route::get('/home', function () {
    return view('home');
})->name('home-route');

Route::view('/login', 'login')->name('login');
Route::view('/home', 'home')->middleware('auth')->name('home');

Route::get('/register',[UserController::class,'create'])->name('register');
Route::post('/crearUsuario',[UserController::class,'agg_user'])->name('create-user');
Route::post('/inicia-sesion',[UserController::class,'login'])->name('inicia-sesion');
Route::get('/logout',[UserController::class,'logout'])->name('logout');

