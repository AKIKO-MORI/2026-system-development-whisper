<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        //自分のアカウントのみ削除可能
        return $user->id === auth()->id();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        //自分のアカウントのみ更新可能
        return $user->id === auth()->id();
    }
}
