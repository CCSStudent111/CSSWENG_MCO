<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\DepartmentDocumentTypeController;

Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.submit');

    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.submit');

    Route::post('/logout', 'logout')->name('logout');
});

Route::resource('hospital-documents', \App\Http\Controllers\Hospital\DocumentController::class)
    ->parameters(['hospital-documents' => 'document'])->only('store', 'create');

Route::resource('hospitals', HospitalController::class);
Route::resource('users', UserController::class);

Route::get('documents-trash', [DocumentController::class, 'trash'])->name('documents.trash');
Route::put('documents/{document}/restore', [DocumentController::class, 'restore'])
    ->withTrashed()
    ->name('documents.restore');
Route::delete('documents/{document}/force-delete', [DocumentController::class, 'forceDelete'])
    ->withTrashed()
    ->name('documents.forceDelete');
Route::get('documents/logs', [DocumentController::class,'logs'])->name('documents.all-logs');
Route::get('documents/{document}/logs', [DocumentController::class, 'documentLogs'])->name('documents.logs');

Route::get('/documents/search', [DocumentController::class, 'search'])->name('documents.search');
Route::resource('documents', DocumentController::class);


Route::resource('document-types', DocumentTypeController::class)->except(['show']);


Route::prefix('departments/{department}/document-types')->group(function () {
    Route::post('{documentType}/attach', [DepartmentDocumentTypeController::class, 'attach'])->name('departments.document-types.attach');
    Route::delete('{documentType}/detach', [DepartmentDocumentTypeController::class, 'detach'])->name('departments.document-types.detach');
});
