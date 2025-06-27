<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;


Route::resource('hospitals', HospitalController::class);
Route::resource('users', UserController::class);
Route::resource('documents', DocumentController::class);

Route::get('/', [HomeController::class,'home']);