<?php

namespace App\Policies;

use App\Models\Reaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReactionPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->id === auth()->user()->id;
    }
}
