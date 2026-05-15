<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    // 主キーは user_id であることを明示
    protected $primaryKey = 'user_id';

    // ここを追加！createメソッドで一括代入できるようにするための設定
    protected $fillable = [
        'user_id',
        'profile',
        'icon_file_name',
    ];
}
