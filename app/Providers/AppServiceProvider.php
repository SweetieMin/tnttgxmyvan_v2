<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

/**
 * 
 * Register repository
 */
use App\Repositories\Eloquent\RoleRepository;
use App\Repositories\Eloquent\CourseRepository;
use App\Repositories\Eloquent\SectorRepository;
use App\Repositories\Eloquent\RegulationRepository;
use App\Repositories\Eloquent\AcademicYearRepository;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\SectorRepositoryInterface;
use App\Repositories\Interfaces\RegulationRepositoryInterface;
use App\Repositories\Interfaces\AcademicYearRepositoryInterface;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AcademicYearRepositoryInterface::class, AcademicYearRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(SectorRepositoryInterface::class, SectorRepository::class);
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(RegulationRepositoryInterface::class, RegulationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
    }
}
