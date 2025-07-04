<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\DepartmentDocumentTypeController;
use App\Http\Controllers\DepartmentController;

Route::get('/', [HomeController::class,'dashboard'])->name('dashboard');

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.submit');

    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.submit');
    
    Route::post('/logout', 'logout')->name('logout');
});

Route::get('hospitals/trashed', [HospitalController::class, 'trashed'])->name('hospitals.trashed');
Route::post('hospitals/{id}/restore', [HospitalController::class, 'restore'])->name('hospitals.restore');
Route::delete('hospitals/{id}/force-delete', [HospitalController::class, 'forceDelete'])->name('hospitals.forceDelete');

Route::resource('hospital-documents', \App\Http\Controllers\Hospital\DocumentController::class)
    ->parameters(['hospital-documents' => 'document'])->only('store', 'create');


Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::post('departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');


Route::resource('hospitals', HospitalController::class);
Route::resource('users', UserController::class);
Route::resource('documents', DocumentController::class);
Route::resource('document-types', DocumentTypeController::class)->except(['show']);
Route::resource('departments', DepartmentController::class);

Route::prefix('departments/{department}/document-types')->group(function () {
    Route::post('{documentType}/attach', [DepartmentDocumentTypeController::class, 'attach'])->name('departments.document-types.attach');
    Route::delete('{documentType}/detach', [DepartmentDocumentTypeController::class, 'detach'])->name('departments.document-types.detach');
});


