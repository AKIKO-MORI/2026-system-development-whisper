<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWhisperRequest;
use App\Http\Requests\UpdateWhisperRequest;
use App\Models\Whisper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WhisperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        //認証ユーザーのIDを取得
        $userId = auth()->id();
        //フォローしているユーザーのIDを取得
        $followingIds = Auth::user() -> follows() -> pluck('followed_id');
        //自分とフォローしているユーザーのIDを結合
        $userIds = $followingIds->push($userId);
        //whisperを取得（新しい順）
        $whispers = Whisper::whereIn('user_id', $userIds)->orderBy('created_at', 'desc')->get();
        return response()->json($whispers);
    }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'text' => 'required|string|max:255',
        ]);
        $whisper = new Whisper();
        $whisper->user_id = auth()->id();
            // $whisper->user_id = 1; //仮でユーザーIDを1に設定
        $whisper->content = $request->text;
        //dd($whisper);
        $whisper->save();
        return response()->json($whisper, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Whisper $whisper)
    {
        //
    }

    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWhisperRequest $request, Whisper $whisper)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        //自分のユーザー情報の時のみ削除可能　ポリシー使用
        
        $auth_user = auth()->user();
        $whisper =Whisper::find($id);

        //ユーザーIDが一致するか確認
        if ($auth_user->id !== $whisper->user_id) {
            return response()->json(['message' => '他のユーザーの情報は削除できません'], 403);
        }
        
        $whisper->delete();
        return response()->json(['message' => 'ささやきを１件削除しました']);
    }
}
