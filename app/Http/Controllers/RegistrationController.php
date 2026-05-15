<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    //フォロー処理
    //引数で指定したユーザーをフォローする
    //すでにフォロー済みの場合は、フォローを解除する
    public function followRegister(Request $request){    
    $user = auth()->user();
        $followedUser = \App\Models\User::find($request->follow_user_id);
        if (!$followedUser) {
            return response()->json(['message' => 'ユーザーが見つかりませんでした'], 404);
        }
        if ($user->isFollowing($followedUser->id)) {
            //すでにフォローしている場合は、フォローを解除する
            $user->follows()->detach($followedUser->id);
            return response()->json(['message' => 'フォローを解除しました']);
        } else {
            //フォローしていない場合は、フォローする
            $user->follows()->attach($followedUser->id);
            return response()->json(['message' => 'ユーザーをフォローしました']);
        }
    }

    //言い値処理
    //引数で指定したwhisper_idのWhisperに対して、いいねをする
    //すでにいいね済みの場合は、いいねを解除する
    public function likeRegister(Request $request){
        $user = auth()->user();
        $whisper = \App\Models\Whisper::find($request->whisper_id);
        if (!$whisper) {
            return response()->json(['message' => 'ささやきが見つかりませんでした'], 404);
        }
        if ($user->isLiking($whisper->id)) {
            //すでにいいねしている場合は、いいねを解除する
            $user->likedWhispers()->detach($whisper->id);
            return response()->json(['message' => 'いいねを解除しました']);
        } else {
            //いいねしていない場合は、いいねする
            $user->likedWhispers()->attach($whisper->id);
            return response()->json(['message' => 'ささやきをいいねしました']); 
        }
    }
}
