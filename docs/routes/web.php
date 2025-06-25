<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::get('/documents/trash', [DocumentController::class, 'trash'])->name('documents.trash');
Route::patch('/documents/{id}/restore', [DocumentController::class, 'restore'])->name('documents.restore');
Route::delete('/documents/{id}/force-delete', [DocumentController::class, 'forceDelete'])->name('documents.forceDelete');

Route::resource('documents', DocumentController::class);