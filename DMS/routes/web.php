<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\DocumentPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentDocumentTypeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PasswordController;


Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/register', 'showRegister')->name('register');
        Route::post('/register', 'register')->name('register.submit');

        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login')->name('login.submit');
    });


    Route::post('/logout', 'logout')->name('logout');
});

Route::get('clients/trashed', [ClientController::class, 'trashed'])->name('clients.trashed');
Route::post('clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
Route::delete('clients/{id}/force-delete', [ClientController::class, 'forceDelete'])->name('clients.forceDelete');

Route::resource('client-documents', \App\Http\Controllers\Client\DocumentController::class)
    ->parameters(['client-documents' => 'document'])->only('store', 'create');


Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::post('departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
Route::delete('departments/{id}/force-delete', [DepartmentController::class, 'forceDelete'])->name('departments.forceDelete');

Route::get('documentTypes', [DocumentTypeController::class, 'index'])->name('documentTypes.index');
Route::post('documentTypes/{id}/restore', [DocumentTypeController::class, 'restore'])->name('documentTypes.restore');
Route::delete('documentTypes/{id}/force-delete', [DocumentTypeController::class, 'forceDelete'])->name('documentTypes.forceDelete');


Route::resource('clients', ClientController::class);
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

Route::resource('documents', DocumentController::class);
Route::resource('documentTypes', DocumentTypeController::class)->except(['show']);
Route::resource('departments', DepartmentController::class);

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');

    // Clients
    Route::get('clients/trashed', [ClientController::class, 'trashed'])->name('clients.trashed');
    Route::post('clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
    Route::delete('clients/{id}/force-delete', [ClientController::class, 'forceDelete'])->name('clients.forceDelete');
    Route::resource('clients', ClientController::class);

    // Departments
    Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
    Route::post('departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
    Route::delete('departments/{id}/force-delete', [DepartmentController::class, 'forceDelete'])->name('departments.forceDelete');
    Route::resource('departments', DepartmentController::class);

    // Users
    // User API routes
    Route::get('/api/user/current', [UserController::class, 'getCurrentUser'])->name('api.user.current');
    Route::get('/api/user/{id}', [UserController::class, 'getUserInfo'])->name('api.user.info');
    
    // Profile route
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.index');

    // Regular user routes
    Route::resource('users', UserController::class);
    Route::put('/users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggleAdmin');
    Route::put('/users/{user}/toggle-manager', [UserController::class, 'toggleManager'])->name('users.toggleManager');

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
    Route::resource('document-types', DocumentTypeController::class)
        ->except(['show'])
        ->names([
            'index' => 'documentTypes.index',
            'create' => 'documentTypes.create', 
            'store' => 'documentTypes.store',
            'edit' => 'documentTypes.edit',
            'update' => 'documentTypes.update',
            'destroy' => 'documentTypes.destroy'
        ]);
    Route::post('document-types/{id}/restore', [DocumentTypeController::class, 'restore'])->name('documentTypes.restore');
    Route::delete('document-types/{id}/force-delete', [DocumentTypeController::class, 'forceDelete'])->name('documentTypes.forceDelete');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Department ↔ Document Type Linking
    Route::prefix('departments/{department}/document-types')->group(function () {
        Route::post('{documentType}/attach', [DepartmentDocumentTypeController::class, 'attach'])->name('departments.document-types.attach');
        Route::delete('{documentType}/detach', [DepartmentDocumentTypeController::class, 'detach'])->name('departments.document-types.detach');
    });
});

Route::get('/phpinfo', function () {
    phpinfo();
});