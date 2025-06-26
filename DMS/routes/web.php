<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;


Route::resource('hospitals', HospitalController::class);
Route::get('/', [HomeController::class,'home']);