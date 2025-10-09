<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Management\RoleController;
use App\Http\Controllers\Management\AcademicYearController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::prefix('management')->name('management.')->group(function () {
        Route::resource('academic-years', AcademicYearController::class);
        Route::resource('roles', RoleController::class);
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
