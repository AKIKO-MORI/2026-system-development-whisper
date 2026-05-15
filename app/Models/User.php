<?php
/*
***************** 2026-04-01 MORI AKIKO 作成****************
*
 * 2026-04-01 12:44:10に作成
 * UserProfileモデルとuser_profilesテーブルのマイグレーションを作成
 * user_profilesテーブルはusersテーブルのidを参照し、かつ主キーとする
 * これにより、ユーザープロフィールはユーザーと1対1の関係になる
 * 2026-04-01 12:44:37に作成
 * Likeモデルとlikesテーブルのマイグレーションを作成
 * likesテーブルはuser_idとwhisper_idの複合主キーを持ち、ユーザーが同じ投稿に複数回いいねできないようにする
 * 2026-04-01 12:46:04に作成
 * FollowUserモデルとfollow_usersテーブルのマイグレーションを作成
 * follow_usersテーブルはuser_idとfollow_user_idの複合主キーを持ち、ユーザーが同じユーザーを複数回フォローできないようにする
 * 2026-04-01 12:47:00に編集
 * Userモデルに以下のリレーションを追加
 * - profile(): UserProfileモデルとの1対1のリレーション
 * - whispers(): Whisperモデルとの1対多のリレーション
 * - follows(): Userモデルとの多対多のリレーション（自分がフォローしているユーザー一覧）
 * - followers(): Userモデルとの多対多のリレーション（自分をフォローしているユーザー一覧）
 * - likedWhispers(): Whisperモデルとの多対多のリレーション（自分が「いいね」した投稿一覧）
 * また、以下のメソッドを追加
 * - isFollowing($userId): 引数に指定したユーザーをすでにフォローしているか判定するメソッド
 * - isLiking($whisperId): 引数に指定した投稿をすでに「いいね」しているか判定するメソッド   
 * 
 */

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //user_profilesテーブルとのリレーション自分のプロフィールを取得するためのリレーション
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id', 'id');
    }

    // 自分の投稿（Whisper）一覧
    public function whispers()
    {
        return $this->hasMany(Whisper::class, 'user_id', 'id');
    }

    //フォロー関係
    // 自分がフォローしているユーザー一覧
    public function follows()
    {
        return $this->belongsToMany(User::class, 'follow_users', 'user_id', 'follow_user_id');
    }

    // 自分をフォローしているユーザー一覧（フォロワー一覧）
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follow_users', 'follow_user_id', 'user_id');
    }

    // 自分が引数のユーザーをすでにフォローしているか判定するメソッド
    public function isFollowing($userId)
    {
        return $this->follows()->where('follow_user_id', $userId)->exists();
    }
    
    // ユーザーが「いいね」した投稿一覧
    public function likedWhispers()
    {
        return $this->belongsToMany(Whisper::class, 'likes', 'user_id', 'whisper_id')
            ->withTimestamps();
    }
    

    // 引数に指定した投稿をすでに「いいね」しているか判定するメソッド
    public function isLiking($whisperId)
    {
        return $this->likedWhispers()->where('likes.whisper_id', $whisperId)->exists();
    }

    //フォロワー数を取得するアクセサ
    public function getFollowersCountAttribute(){
        return $this->followers()->count();
    }

    //フォロー数を取得するアクセサ
    public function getFollowsCountAttribute(){
        return $this->follows()->count();
    }

}
