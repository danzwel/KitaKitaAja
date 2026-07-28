<?php

namespace App\Providers;

use App\Models\Application;
use App\Models\Department;
use App\Models\Intern;
use App\Policies\ApplicationPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\InternPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Application::class, ApplicationPolicy::class);
        Gate::policy(Intern::class, InternPolicy::class);
        Gate::policy(Department::class, DepartmentPolicy::class);
    }
}