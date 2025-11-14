<?php

use Laravel\Fortify\Features;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\TwoFactor;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Settings\Site\EmailSettings;
use App\Livewire\Settings\Site\PusherSettings;
use App\Livewire\Settings\Site\GeneralSettings;


Route::middleware(['auth'])->name('admin.')->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->middleware('throttle:6,1')->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('settings/general', GeneralSettings::class)->name('settings.general');

    Route::get('settings/email', EmailSettings::class)->name('settings.email');

    Route::get('settings/pusher', PusherSettings::class)->name('settings.pusher');
});
