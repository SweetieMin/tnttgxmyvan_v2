<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Management\AcademicYear\AcademicYears;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::prefix('admin')->middleware(['auth', 'verified'])->name('admin.')->group(function () {

    Route::prefix('general')->name('general.')->group(function () {
        
    });
   

    Route::prefix('management')->name('management.')->group(function () {
        Route::get('academic-year', AcademicYears::class)->name('academic-year');
    });

    Route::prefix('access')->name('access.')->group(function () {});

    Route::prefix('finance')->name('finance.')->group(function () {});

    Route::prefix('personnel')->name('personnel.')->group(function () {});
});

require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
