<?php

namespace App\Providers;

use App\Models\Task;
use App\Policies\TaskPolicy;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Client;
use App\Policies\ClientPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\ContentType;
use App\Policies\ContentTypePolicy;
use App\Models\WorkLogClient;
use App\Policies\WorkLogClientPolicy;
use App\Models\MenuPermission;
use App\Policies\MenuPermissionPolicy;
use App\Models\GmbClient;
use App\Policies\GmbClientPolicy;

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
        Gate::policy(Task::class, TaskPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(ContentType::class, ContentTypePolicy::class);
        Gate::policy(WorkLogClient::class, WorkLogClientPolicy::class);
        Gate::policy(MenuPermission::class, MenuPermissionPolicy::class);
        Gate::policy(GmbClient::class, GmbClientPolicy::class);
    }
}
