<?php

namespace App\Policies;

use App\Models\CompanyAddress;
use App\Models\User;

class CompanyAddressPolicy
{
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
    public function update(User $user, CompanyAddress $address): bool
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CompanyAddress $address): bool
    {
        return $user->isManager();
    }

}
