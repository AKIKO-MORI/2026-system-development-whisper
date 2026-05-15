<?php
/*
 * 2026/04/01 MORI AKIKO 作成
 * UserProfilePolicyの作成
 * - updateアクションは、ユーザープロフィールの所有者のみが更新できるようにする
 * - その他のアクションは、今回は特に制限を設けない
 * - これにより、ユーザーは自分のプロフィールのみを更新できるようになる
 * 削除は親ユーザーが削除されたときにcascadeOnDeleteで自動的に削除されるため、deleteアクションは特に制限を設けない
 */
namespace App\Policies;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Auth\Access\Response;

class UserProfilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserProfile $userProfile): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserProfile $userProfile): bool
    {
        //自分のプロフィールのみ更新可能
        return $user->id === $userProfile->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserProfile $userProfile): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserProfile $userProfile): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserProfile $userProfile): bool
    {
        //
    }
}
