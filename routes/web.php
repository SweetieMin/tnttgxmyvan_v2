<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Access\RoleController;
use App\Http\Controllers\Management\CourseController;
use App\Http\Controllers\Management\SectorController;
use App\Http\Controllers\Personnel\ScouterController;
use App\Http\Controllers\Finance\TransactionController;
use App\Http\Controllers\Personnel\CatechistController;
use App\Http\Controllers\Personnel\SpiritualController;
use App\Http\Controllers\Management\RegulationController;
use App\Http\Controllers\Management\AcademicYearController;
use App\Http\Controllers\Personnel\ChildrenActiveController;
use App\Http\Controllers\Personnel\ChildrenGraduationController;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::prefix('management')->name('management.')->group(function () {
        Route::resource('academic-years', AcademicYearController::class);
        Route::resource('sectors', SectorController::class);
        Route::resource('courses', CourseController::class);
        Route::resource('regulations', RegulationController::class);
    });

    Route::prefix('access')->name('access.')->group(function () {
        Route::resource('roles', RoleController::class);
    });

    Route::prefix('finance')->name('finance.')->group(function () {
        Route::resource('transactions', TransactionController::class);
    });

    Route::prefix('personnel')->name('personnel.')->group(function () {
        Route::get('spirituals', [SpiritualController::class, 'index'])->name('spirituals.index');
        Route::get('catechists', [CatechistController::class, 'index'])->name('catechists.index');
        Route::get('scouters', [ScouterController::class, 'index'])->name('scouters.index');
        Route::get('children/active', [ChildrenActiveController::class, 'index'])->name('children.active.index');
        Route::get('children/graduation', [ChildrenGraduationController::class, 'index'])->name('children.graduation.index');
    });
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
