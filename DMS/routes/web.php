<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.submit');

    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.submit');
    
    Route::post('/logout', 'logout')->name('logout');
});

Route::resource('hospitals', HospitalController::class);
Route::resource('users', UserController::class);
Route::resource('documents', DocumentController::class);

Route::get('/', [HomeController::class,'dashboard'])->name('dashboard');