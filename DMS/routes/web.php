<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;


Route::resource('hospitals', HospitalController::class);
Route::resource('users', UserController::class);

Route::get('/', [HomeController::class,'home']);