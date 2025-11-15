<?php

use App\Livewire\Access\Role\Roles;
use Illuminate\Support\Facades\Route;
use App\Livewire\Access\Role\ActionsRole;
use App\Livewire\Management\Course\Courses;
use App\Livewire\Management\Sector\Sectors;
use App\Livewire\Personnel\Scouter\Scouters;
use App\Livewire\Management\Program\Programs;
use App\Livewire\Personnel\Children\Children;
use App\Livewire\Personnel\Catechist\Catechists;
use App\Livewire\Personnel\Spiritual\Spirituals;
use App\Livewire\Finance\Transaction\Transactions;
use App\Livewire\Management\Regulation\Regulations;
use App\Livewire\Personnel\Spiritual\ActionsSpiritual;
use App\Livewire\Management\AcademicYear\AcademicYears;
use App\Livewire\Management\Regulation\ActionsRegulation;
use App\Livewire\Finance\TransactionItem\TransactionItems;
use App\Livewire\Personnel\ChildrenInactive\ChildrenInactive;


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

        Route::get('regulations', Regulations::class)->name('regulations');

        Route::get('regulations/action', ActionsRegulation::class)->name('regulations.action');

        Route::get('programs', Programs::class)->name('programs');

        Route::get('courses', Courses::class)->name('courses');

        Route::get('sectors', Sectors::class)->name('sectors');
    });

    Route::prefix('access')->name('access.')->group(function () {
        Route::get('roles', Roles::class)->name('roles');

        Route::get('roles/action', ActionsRole::class)->name('roles.action');
    });

    Route::prefix('finance')->name('finance.')->group(function () {

        Route::get('transaction-items', TransactionItems::class)->name('transaction-items');

        Route::get('transactions', Transactions::class)->name('transactions');
    });

    Route::prefix('personnel')->name('personnel.')->group(function () {
        Route::get('spirituals', Spirituals::class)->name('spirituals');

        Route::get('spirituals/action', ActionsSpiritual::class)->name('spirituals.action');

        Route::get('catechists', Catechists::class)->name('catechists');
        Route::get('scouters', Scouters::class)->name('scouters');
        Route::get('children', Children::class)->name('children');
        Route::get('children-inactive', ChildrenInactive::class)->name('children-inactive');
    });
});

require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
