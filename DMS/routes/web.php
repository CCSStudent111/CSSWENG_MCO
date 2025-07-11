<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\DocumentPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentDocumentTypeController;
use App\Http\Controllers\DepartmentController;


Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.submit');
    
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.submit');
    
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');

    // Hospitals
    Route::get('hospitals/trashed', [HospitalController::class, 'trashed'])->name('hospitals.trashed');
    Route::post('hospitals/{id}/restore', [HospitalController::class, 'restore'])->name('hospitals.restore');
    Route::delete('hospitals/{id}/force-delete', [HospitalController::class, 'forceDelete'])->name('hospitals.forceDelete');
    Route::resource('hospitals', HospitalController::class);

    // Departments
    Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
    Route::delete('departments/{id}/force-delete', [DepartmentController::class, 'forceDelete'])->name('departments.forceDelete');
    Route::resource('departments', DepartmentController::class);

    // Users
    Route::resource('users', UserController::class);

    // Documents
    Route::get('documents-trash', [DocumentController::class, 'trash'])->name('documents.trash');
    Route::put('documents/{document}/restore', [DocumentController::class, 'restore'])->withTrashed()->name('documents.restore');
    Route::delete('documents/{document}/force-delete', [DocumentController::class, 'forceDelete'])->withTrashed()->name('documents.forceDelete');
    Route::get('documents/logs', [DocumentController::class, 'logs'])->name('documents.all-logs');
    Route::get('documents/{document}/logs', [DocumentController::class, 'documentLogs'])->name('documents.logs');
    Route::get('/documents/pending', [DocumentController::class, 'pending'])
        ->middleware('manager')
        ->name('documents.pending');
    Route::post('/documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
    Route::delete('/documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
    Route::resource('documents', DocumentController::class);

    // Document Types
    Route::resource('document-types', DocumentTypeController::class)->except(['show']);

    // Profile
    Route::resource('profile', ProfileController::class);

    // Department ↔ Document Type Linking
    Route::prefix('departments/{department}/document-types')->group(function () {
        Route::post('{documentType}/attach', [DepartmentDocumentTypeController::class, 'attach'])->name('departments.document-types.attach');
        Route::delete('{documentType}/detach', [DepartmentDocumentTypeController::class, 'detach'])->name('departments.document-types.detach');
    });
});
