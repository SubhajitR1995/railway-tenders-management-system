<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TenderCategoryController;
use App\Http\Controllers\TenderController;
use App\Http\Controllers\TenderDocumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// Auth routes (guest only)
Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated routes
Route::middleware('auth')->group(function (): void {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tenders
    Route::get('/tenders', [TenderController::class, 'index'])->name('tenders.index');
    Route::get('/tenders/create', [TenderController::class, 'create'])->name('tenders.create')->middleware('role:admin,manager');
    Route::post('/tenders', [TenderController::class, 'store'])->name('tenders.store')->middleware('role:admin,manager');
    Route::get('/tenders/{tender}', [TenderController::class, 'show'])->name('tenders.show');
    Route::get('/tenders/{tender}/edit', [TenderController::class, 'edit'])->name('tenders.edit')->middleware('role:admin,manager');
    Route::put('/tenders/{tender}', [TenderController::class, 'update'])->name('tenders.update')->middleware('role:admin,manager');
    Route::delete('/tenders/{tender}', [TenderController::class, 'destroy'])->name('tenders.destroy')->middleware('role:admin,manager');
    Route::post('/tenders/{tender}/publish', [TenderController::class, 'publish'])->name('tenders.publish')->middleware('role:admin,manager');
    Route::post('/tenders/{tender}/close', [TenderController::class, 'close'])->name('tenders.close')->middleware('role:admin,manager');

    // Tender Categories (admin only)
    Route::get('/categories', [TenderCategoryController::class, 'index'])->name('categories.index')->middleware('role:admin');
    Route::get('/categories/create', [TenderCategoryController::class, 'create'])->name('categories.create')->middleware('role:admin');
    Route::post('/categories', [TenderCategoryController::class, 'store'])->name('categories.store')->middleware('role:admin');
    Route::get('/categories/{category}', [TenderCategoryController::class, 'show'])->name('categories.show')->middleware('role:admin');
    Route::get('/categories/{category}/edit', [TenderCategoryController::class, 'edit'])->name('categories.edit')->middleware('role:admin');
    Route::put('/categories/{category}', [TenderCategoryController::class, 'update'])->name('categories.update')->middleware('role:admin');
    Route::delete('/categories/{category}', [TenderCategoryController::class, 'destroy'])->name('categories.destroy')->middleware('role:admin');

    // Tender Documents (LOA PDF upload & management)
    Route::get('/documents', [TenderDocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/upload', [TenderDocumentController::class, 'upload'])->name('documents.upload');
    Route::post('/documents/upload', [TenderDocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/review', [TenderDocumentController::class, 'review'])->name('documents.review');
    Route::post('/documents/{document}/confirm', [TenderDocumentController::class, 'confirm'])->name('documents.confirm');
    Route::get('/documents/{document}', [TenderDocumentController::class, 'show'])->name('documents.show');
    Route::delete('/documents/{document}', [TenderDocumentController::class, 'destroy'])->name('documents.destroy');
});
