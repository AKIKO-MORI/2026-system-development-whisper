<?php
/*
 * 2026/04/01 MORI AKIKO 作成
 * WhisperPolicyの作成
 * - deleteアクションは、投稿の所有者のみが削除できるようにする
 * - その他のアクションは、今回は特に制限を設けない
 * - これにより、ユーザーは自分の投稿のみを削除できるようになる
 * 今回更新処理は行わない（予定）
 */
namespace App\Policies;

use App\Models\User;
use App\Models\Whisper;
use Illuminate\Auth\Access\Response;

class WhisperPolicy
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
    public function view(User $user, Whisper $whisper): bool
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
    public function update(User $user, Whisper $whisper): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Whisper $whisper): bool
    {
        //自分の投稿のみ削除可能
        return $user->id === $whisper->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Whisper $whisper): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Whisper $whisper): bool
    {
        //
    }
}
