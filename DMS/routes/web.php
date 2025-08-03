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
use App\Http\Middleware\AdminMiddleware;


Route::controller(AuthController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login')->name('login.submit');
    });


    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::middleware('ensure.admin')->group(function () {
        // Departments
        Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::post('departments/{id}/restore', [DepartmentController::class, 'restore'])->name('departments.restore');
        Route::delete('departments/{id}/force-delete', [DepartmentController::class, 'forceDelete'])->name('departments.forceDelete');
        Route::resource('departments', DepartmentController::class);

        
        // Regular user routes
        Route::get('users/trashed', [UserController::class, 'trashed'])->name('users.trashed');
        Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');
        Route::put('users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggleAdmin');
        Route::put('users/{user}/toggle-manager', [UserController::class, 'toggleManager'])->name('users.toggleManager');
        Route::resource('users', UserController::class);

        // Document Types
        Route::post('document-types/{id}/restore', [DocumentTypeController::class, 'restore'])->name('documentTypes.restore');
        Route::delete('document-types/{id}/force-delete', [DocumentTypeController::class, 'forceDelete'])->name('documentTypes.forceDelete');
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

        // Department ↔ Document Type Linking
        Route::prefix('departments/{department}/document-types')->group(function () {
            Route::post('{documentType}/attach', [DepartmentDocumentTypeController::class, 'attach'])->name('departments.document-types.attach');
            Route::delete('{documentType}/detach', [DepartmentDocumentTypeController::class, 'detach'])->name('departments.document-types.detach');
        });
    });

    // Users
    // User API routes
    Route::get('/api/user/current', [UserController::class, 'getCurrentUser'])->name('api.user.current');
    Route::get('/api/user/{id}', [UserController::class, 'getUserInfo'])->name('api.user.info');
    
    // Profile route
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.index');
    
    // Documents
    Route::middleware('block.employees')->group(function () {
        Route::get('documents-trash', [DocumentController::class, 'trash'])->name('documents.trash');
        Route::put('documents/{document}/restore', [DocumentController::class, 'restore'])->withTrashed()->name('documents.restore');
        Route::delete('documents/{document}/force-delete', [DocumentController::class, 'forceDelete'])->withTrashed()->name('documents.forceDelete');
        Route::get('documents/{document}/logs', [DocumentController::class, 'documentLogs'])->name('documents.logs');
        Route::post('documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
        Route::delete('documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');

        Route::get('documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit');

        // Clients
        Route::get('clients/trashed', [ClientController::class, 'trashed'])->name('clients.trashed');
        Route::post('clients/{id}/restore', [ClientController::class, 'restore'])->name('clients.restore');
        Route::delete('clients/{id}/force-delete', [ClientController::class, 'forceDelete'])->name('clients.forceDelete');

        Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
        Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    });

    Route::resource('clients', ClientController::class)->except(['create', 'edit']);
    
    Route::get('documents/logs', [DocumentController::class, 'logs'])->name('documents.all-logs');
    Route::get('/documents/pending', [DocumentController::class, 'pending'])
        ->name('documents.pending');

    Route::resource('documents', DocumentController::class)->except(['edit']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

   

    // Department Document Type Routes (Admin only)
    Route::middleware([AdminMiddleware::class])->group(function () {
         Route::get('/department-document-types', [DepartmentDocumentTypeController::class, 'index'])
            ->name('department-document-types.index');
        
        Route::post('/department-document-types/{department}/{type}/attach', [DepartmentDocumentTypeController::class, 'attach'])
            ->name('department-document-types.attach');
        
        Route::delete('/department-document-types/{department}/{type}/detach', [DepartmentDocumentTypeController::class, 'detach'])
            ->name('department-document-types.detach');
            
        Route::post('/department-document-types/{department}/bulk-attach', [DepartmentDocumentTypeController::class, 'bulkAttach'])
            ->name('department-document-types.bulk-attach');
            
        Route::post('/department-document-types/{department}/bulk-detach', [DepartmentDocumentTypeController::class, 'bulkDetach'])
            ->name('department-document-types.bulk-detach');
    });

});

Route::get('/phpinfo', function () {
    phpinfo();
});