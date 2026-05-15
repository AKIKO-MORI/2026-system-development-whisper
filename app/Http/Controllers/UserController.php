<?php
/*
 * 2026/04/01 MORI AKIKO 作成
 * UserControllerの作成
 * - registerアクションは、ユーザー登録と同時にユーザープロフィールも作成するようにする
 * - updateアクションは、ユーザープロフィールの所有者のみが更新できるようにする（UserProfilePolicyを使用）
 * - deleteアクションは、ユーザープロフィールの所有者のみが削除できるようにする（UserProfilePolicyを使用）
 * - showアクションは、特に制限を設けない（誰でもユーザー情報を取得できるようにする）
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        // ユーザー登録処理の実装
        // 登録処理の実装
        // バリデーション
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'バリデーションエラー',
                'errors'  => $validator->errors()
            ], 422);
        }

        // 変数を定義しておく（トランザクション内で値を変更するため、参照渡しで使用する）
        $user = null;
        $token = null;

        //トランザクション処理
        DB::transaction(function () use ($request, &$user,&$token) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // トークン発行（モバイルアプリ用）
            $token = $user->createToken('mobile')->plainTextToken;

            //ユーザープロフィールの作成（最初は空のレコード）
            $user->profile()->create([
                'profile' => '',
                'icon_file_name' => '',
            ]);
        }); //トランザクション処理終了

        return response()->json([
            'token' => $token,
            'user'  => $user
        ], 201);
    }

    function update(Request $request)
    {
        // ユーザー情報更新処理の実装
        // バリデーション必要なものを考えよう（ここでは保留）
        // $validator = Validator::make($request->all(), [
        //     // 'name'     => 'required',
        //     // 'email'    => 'required|email|unique:users,email',
        //     // 'password' => 'required|min:6'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'message' => 'バリデーションエラー',
        //         'errors'  => $validator->errors()
        //     ], 422);
        // }

        $user = auth()->user();

        //自分のユーザー情報の時のみ更新可能　ポリシー使用
        $this->authorize('update', $user);

        $user->name = $request->name ?? $user->name;
        $user->profile->profile = $request->profile ?? $user->profile->profile;

        // if ($request->hasFile('icon')) {
        //     $path = $request->file('icon')->store('public/icons');
        //     $user->profile->icon = basename($path);
        // }

        //ユーザー情報とプロフィール情報を保存　トランザクション処理
        DB::transaction(function () use ($user) {
            $user->save();
            $user->profile->save();
        });

        //更新後のユーザー情報を返す
        return response()->json([
            'user' => $user
        ], 200);
    }

    function destroy(Request $request,$id)
    {
        // ユーザー削除処理の実装
        //自分のユーザー情報の時のみ削除可能　ポリシー使用
        $user = User::findOrFail($id);
        $auth_user = auth()->user();
        //ユーザーIDが一致するか確認
        if ($auth_user->id !== $user->id) {
            return response()->json(['message' => '他のユーザーの情報は削除できません'], 403);
        }

        //ポリシーで削除の権限を確認
        // $this->authorize('delete', $user);

        User::destroy($id);
        return response()->json(['message' => 'ユーザーを削除しました']);
    }

    function show(Request $request, User $user)
    {
        // ユーザー情報取得処理の実装
        $id = auth()->id();
        $user = User::with('profile')->find($id);

        return response()->json([
            'user' => $user //ユーザー情報とプロフィール情報をまとめて返す  
        ], 200);
    }
}
