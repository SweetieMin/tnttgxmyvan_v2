<?php

namespace App\Providers;

use App\Models\User;
use App\Helpers\MailConfig;
use App\Helpers\PusherConfig;
use App\Models\GeneralSetting;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\CourseRepository;
use App\Repositories\Eloquent\SectorRepository;
use App\Repositories\Eloquent\ProgramRepository;
use App\Repositories\Eloquent\ScouterRepository;
use App\Repositories\Eloquent\ChildrenRepository;
use App\Repositories\Eloquent\CatechistRepository;
use App\Repositories\Eloquent\SpiritualRepository;
use App\Repositories\Eloquent\RegulationRepository;
use App\Repositories\Eloquent\TransactionRepository;
use App\Repositories\Eloquent\AcademicYearRepository;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use App\Repositories\Eloquent\TransactionItemRepository;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Eloquent\ChildrenInactiveRepository;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\SectorRepositoryInterface;
use App\Repositories\Interfaces\ProgramRepositoryInterface;
use App\Repositories\Interfaces\ScouterRepositoryInterface;
use App\Repositories\Interfaces\ChildrenRepositoryInterface;
use App\Repositories\Interfaces\CatechistRepositoryInterface;
use App\Repositories\Interfaces\SpiritualRepositoryInterface;
use App\Repositories\Interfaces\RegulationRepositoryInterface;
use App\Repositories\Interfaces\TransactionRepositoryInterface;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;
use App\Repositories\Interfaces\TransactionItemRepositoryInterface;
use App\Repositories\Interfaces\ChildrenInactiveRepositoryInterface;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AcademicYearRepositoryInterface::class, AcademicYearRepository::class);
        $this->app->bind(RegulationRepositoryInterface::class, RegulationRepository::class);
        $this->app->bind(ProgramRepositoryInterface::class, ProgramRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(SectorRepositoryInterface::class, SectorRepository::class);

        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);

        $this->app->bind(TransactionItemRepositoryInterface::class, TransactionItemRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);

        $this->app->bind(SpiritualRepositoryInterface::class, SpiritualRepository::class);
        $this->app->bind(CatechistRepositoryInterface::class, CatechistRepository::class);
        $this->app->bind(ScouterRepositoryInterface::class, ScouterRepository::class);
        $this->app->bind(ChildrenRepositoryInterface::class, ChildrenRepository::class);
        $this->app->bind(ChildrenInactiveRepositoryInterface::class, ChildrenInactiveRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        RedirectIfAuthenticated::redirectUsing(function () {
            return route('dashboard');
        });

        Authenticate::redirectUsing(function () {
            Session::flash('status', 'Bạn cần đăng nhập để tiếp tục');
            return route('login');
        });

        Blade::anonymousComponentNamespace('components.flux', 'flux');

        MailConfig::apply();
        PusherConfig::apply();

        // View Composer cho sidebar - inject user_settings
        View::composer('components.layouts.app.sidebar', function ($view) {
            $userSettings = null;

            if (Auth::check()) {
                $userSettings = Auth::user()->settings;
            }

            $view->with('userSettings', $userSettings);
        });

        $generalSettings = GeneralSetting::first();

        View::share([
            'site_title'        => $generalSettings->site_title ?? config('app.name'),
            'site_email'        => $generalSettings->site_email ?? null,
            'site_phone'        => $generalSettings->site_phone ?? null,
            'site_meta_keywords' => $generalSettings->site_meta_keywords ?? null,
            'site_meta_description' => $generalSettings->site_meta_description ?? null,
            'site_logo'         => $generalSettings->site_logo ?? null,
            'site_favicon'      => $generalSettings->site_favicon ?? null,
            'facebook_url'      => $generalSettings->facebook_url ?? null,
            'instagram_url'     => $generalSettings->instagram_url ?? null,
            'youtube_url'       => $generalSettings->youtube_url ?? null,
            'tiktok_url'        => $generalSettings->tikTok_url ?? null,
        ]);
    }
}
