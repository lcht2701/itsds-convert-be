<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Service;
use App\Models\TicketSolution;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\ServicePolicy;
use App\Policies\TicketSolutionPolicy;
use App\Policies\UserPolicy;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\ICategoryRepository;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\ICommentRepository;
use App\Repositories\Service\IServiceRepository;
use App\Repositories\Service\ServiceRepository;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Register Repositories
        $this->app->bind(ICategoryRepository::class, CategoryRepository::class);
        $this->app->bind(IServiceRepository::class, ServiceRepository::class);
        $this->app->bind(ICommentRepository::class, CommentRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerGates();
        $this->registerPolicies();
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }

    public function registerGates()
    {
        Gate::define('role.customer', function (User $user): bool {
            return $user->isCustomer();
        });
        Gate::define('role.company-admin', function (User $user): bool {
            return $user->isCompanyAdmin();
        });
        Gate::define('role.technician', function (User $user): bool {
            return $user->isTechnician();
        });
        Gate::define('role.manager', function (User $user): bool {
            return $user->isManager();
        });
        Gate::define('role.admin', function (User $user): bool {
            return $user->isAdmin();
        });
    }

    public function registerPolicies()
    {
        Gate::policy(Category::class, CategoryPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(TicketSolution::class, TicketSolutionPolicy::class);
        Gate::policy(Service::class, ServicePolicy::class);
    }
}
