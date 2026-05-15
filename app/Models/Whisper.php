<?php
/*
***************** 2026-04-01 MORI AKIKO 作成****************
 * Whisperモデル
 * - content: 投稿内容
 * - whisper_id: 返信先のWhisperのID（自己参照）
 * - user_id: 投稿者のユーザーID
 * - timestamps: created_at, updated_at
 * リレーション:
 * - user(): 投稿者のユーザー情報を取得するリレーション
 * - likedBy(): この投稿を「いいね」したユーザー一覧を取得するリレーション
 * - getLikesCountAttribute(): この投稿の「いいね」数を取得するアクセサ     
 * - 注意点:
 *   - whisper_idはnullableで、親がいない場合を許容する
 *   - 親が削除されたら子も消えるようにcascadeOnDeleteを設定する    
 * - いいね機能は、likesテーブルを介して多対多のリレーションで実装する
 * - いいね数は、likedByリレーションをカウントするアクセサで取得する
 * - 返信機能は、whisper_idを自己参照することで実装するが、今回はリレーションは作成しない
 * 
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Whisper extends Model
{
    use HasFactory;

    // 親のWhisper（返信先）を取得するリレーション(今回は作成しない)

    // 子のWhisper（返信）を取得するリレーション(今回は作成しない)

    // Whisperを投稿したユーザーを取得するリレーション
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // この投稿を「いいね」したユーザー一覧
    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'likes', 'whisper_id', 'user_id')
            ->withTimestamps();
    }

    // この投稿の「いいね」数を取得するアクセサ
    public function getLikesCountAttribute()
    {
        return $this->likedBy()->count();
    }
}
