<?php

namespace Support\Providers;

// use Illuminate\Support\Facades\Gate;
use Domain\Task\Models\Category;
use Domain\Task\Models\Task;
use Domain\Task\Policies\CategoryPolicy;
use Domain\Task\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Task::class => TaskPolicy::class,
        Category::class => CategoryPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
