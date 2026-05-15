<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FollwerController extends Controller
{
    //自分のフォロワーの一覧を取得する
    public function followers()
    {
        $user = auth()->user();
        $followers = $user->followers()->withCount(['followers', 'follows'])->get();

        // 各フォロワーに対して、フォローの有無の結果を入れる
        $followers->each(function ($follower) {
            $follower->isFollowing = auth()->user()->isFollowing($follower->id);
        });


        return response()->json($followers);
    }

    //自分がフォローをしている一覧を取得する
    public function following()
    {
        $user = auth()->user();
        $followingUsers = $user->follows()->withCount(['followers', 'follows'])->get();
       
        // 各フォロワーに対して、フォローの有無の結果を入れる
        $followingUsers->each(function ($following) {
            $following->isFollowing = auth()->user()->isFollowing($following->id);
        });

        return response()->json($followingUsers);
    }

    //引数で指定したユーザーの詳細情報を取得する(制限掛けるか検討すべし)
    public function show($id)
    {
        $user = auth()->user();
        $f_user = \App\Models\User::with('profile')->find($id);
        if (!$f_user) {
            return response()->json(['message' => 'ユーザーが見つかりませんでした'], 404);
        }

        //フォロワー数とフォロー数を取得する
        $f_user->followers_count = $f_user->followers()->count();
        $f_user->follows_count = $f_user->follows()->count();
        //フォローの有無の結果を入れる
        $f_user->isFollowing = $user->isFollowing($f_user->id);
        
        
        //f_userのささやき情報を取得する
        $f_user->whispers = $f_user->whispers()->withCount(['likedBy'])->get();

        //各ささやきに対して、いいねの有無の結果を入れる
        $f_user->whispers->each(function ($whisper) {
            $whisper->isLiking = auth()->user()->isLiking($whisper->id);
        });

        return response()->json($f_user);
    }
}
