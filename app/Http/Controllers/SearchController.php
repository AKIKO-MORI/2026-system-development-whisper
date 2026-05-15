<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    //引数のキーワードをユーザー名に含むユーザーを検索する
    public function usernameSearch($keyword)
    {
        $users = \App\Models\User::where('name', 'like', '%' . $keyword . '%')->withCount(['followers', 'follows'])->get();

        // 各ユーザーに対して、フォローの有無の結果を入れる
        $users->each(function ($user) {
            $user->isFollowing = auth()->user()->isFollowing($user->id);
        });

        return response()->json($users);
    }

    //引数のキーワードをWhisperの本文に含むWhisperを検索する
    public function whisperSearch($keyword)
    {
        $whispers = \App\Models\Whisper::where('content', 'like', '%' . $keyword . '%')->withCount(['likedBy'])->get();
        // 各ささやきに対して、いいねの有無の結果を入れる
        $whispers->each(function ($whisper) {
            $whisper->isLiking = auth()->user()->isLiking($whisper->id);
        });
        return response()->json($whispers);
    }
}
