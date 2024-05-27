<?php

namespace App\Policies;

use App\Models\ServicesContract;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ServicesContractPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isManager() || $user->isCompanyAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ServicesContract $servicesContract): bool
    {
        return $user->isManager() || $user->isCompanyAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ServicesContract $servicesContract): bool
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ServicesContract $servicesContract): bool
    {
        return $user->isManager();
    }
}
