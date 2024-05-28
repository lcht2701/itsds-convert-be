<?php

namespace App\Providers;

use App\Models\Assignment;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Company;
use App\Models\CompanyAddress;
use App\Models\CompanyMember;
use App\Models\Contract;
use App\Models\Reaction;
use App\Models\Service;
use App\Models\ServicesContract;
use App\Models\Ticket;
use App\Models\TicketSolution;
use App\Models\TicketTask;
use App\Models\User;
use App\Policies\AssignmentPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CommentPolicy;
use App\Policies\CompanyAddressPolicy;
use App\Policies\CompanyMemberPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\ContractPolicy;
use App\Policies\ReactionPolicy;
use App\Policies\ServicePolicy;
use App\Policies\ServicesContractPolicy;
use App\Policies\TicketPolicy;
use App\Policies\TicketSolutionPolicy;
use App\Policies\TicketTaskPolicy;
use App\Policies\UserPolicy;
use App\Repositories\Assignment\AssignmentRepository;
use App\Repositories\Assignment\IAssignmentRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Category\ICategoryRepository;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\ICommentRepository;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Company\ICompanyRepository;
use App\Repositories\CompanyAddress\CompanyAddressRepository;
use App\Repositories\CompanyAddress\ICompanyAddressRepository;
use App\Repositories\CompanyMember\CompanyMemberRepository;
use App\Repositories\CompanyMember\ICompanyMemberRepository;
use App\Repositories\Contract\ContractRepository;
use App\Repositories\Contract\IContractRepository;
use App\Repositories\File\FileRepository;
use App\Repositories\File\IFileRepository;
use App\Repositories\Reaction\IReactionRepository;
use App\Repositories\Reaction\ReactionRepository;
use App\Repositories\Service\IServiceRepository;
use App\Repositories\Service\ServiceRepository;
use App\Repositories\ServicesContract\IServicesContractRepository;
use App\Repositories\ServicesContract\ServicesContractRepository;
use App\Repositories\Ticket\ITicketRepository;
use App\Repositories\Ticket\TicketRepository;
use App\Repositories\TicketSolution\ITicketSolutionRepository;
use App\Repositories\TicketSolution\TicketSolutionRepository;
use App\Repositories\TicketTask\ITicketTaskRepository;
use App\Repositories\TicketTask\TicketTaskRepository;
use App\Repositories\User\IUserRepository;
use App\Repositories\User\UserRepository;
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
        $this->app->bind(IFileRepository::class, FileRepository::class);
        $this->app->bind(ICompanyAddressRepository::class, CompanyAddressRepository::class);
        $this->app->bind(ICategoryRepository::class, CategoryRepository::class);
        $this->app->bind(IServiceRepository::class, ServiceRepository::class);
        $this->app->bind(ICommentRepository::class, CommentRepository::class);
        $this->app->bind(IReactionRepository::class, ReactionRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(ITicketSolutionRepository::class, TicketSolutionRepository::class);
        $this->app->bind(ICompanyRepository::class, CompanyRepository::class);
        $this->app->bind(ICompanyMemberRepository::class, CompanyMemberRepository::class);
        $this->app->bind(IContractRepository::class, ContractRepository::class);
        $this->app->bind(IServicesContractRepository::class, ServicesContractRepository::class);
        $this->app->bind(ITicketRepository::class, TicketRepository::class);
        $this->app->bind(ITicketTaskRepository::class, TicketTaskRepository::class);
        $this->app->bind(IAssignmentRepository::class, AssignmentRepository::class);
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
        Gate::policy(Reaction::class, ReactionPolicy::class);
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(CompanyAddress::class, CompanyAddressPolicy::class);
        Gate::policy(CompanyMember::class, CompanyMemberPolicy::class);
        Gate::policy(Contract::class, ContractPolicy::class);
        Gate::policy(ServicesContract::class, ServicesContractPolicy::class);
        Gate::policy(Ticket::class, TicketPolicy::class);
        Gate::policy(TicketTask::class, TicketTaskPolicy::class);
        Gate::policy(Assignment::class, AssignmentPolicy::class);
    }
}
