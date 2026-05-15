<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
    
/*
 * 2026/04/01 MORI AKIKO 追加
 * APIルートの定義
 * - ログイン、ログアウト、ユーザー登録、ユーザー情報更新、ユーザー情報取得、ユーザー削除、ささやきの取得、ささやき登録、ささやき削除のAPIエンドポイント
 * - フォロワー・フォロー一覧取得のAPIエンドポイント
 * - フォロー登録解除、イイね登録解除のAPIエンドポイント
 * - 各エンドポイントは、適切なHTTPメソッドとURLパターンを使用して定義されている
 * - 認証が必要なエンドポイントには、auth:sanctumミドルウェアを適用している

*/
/////////// 1.ログイン　ログアウト
Route::post('v1/auth/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('v1/auth/logout', [App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum')->name('logout');

////////////// 2.ユーザー登録　ユーザー情報更新
Route::post('v1/users/register', [App\Http\Controllers\UserController::class, 'register']);
Route::post('v1/users/profile/{id}', [App\Http\Controllers\UserController::class, 'update'])->middleware('auth:sanctum');
//ユーザー情報取得
Route::get('v1/user', [App\Http\Controllers\UserController::class, 'show'])->middleware('auth:sanctum');
//ユーザー削除
Route::post('v1/users/delete/{id}', [App\Http\Controllers\UserController::class, 'destroy'])->middleware('auth:sanctum');

///////////////////3.フォローユーザーささやきの取得//////////////]
Route::get('v1/whispers', [App\Http\Controllers\WhisperController::class, 'index'])->middleware('auth:sanctum');

///////////////////5.ささやき登録　ささやき削除///////////////
Route::post('v1/whispers', [App\Http\Controllers\WhisperController::class, 'store'])->middleware('auth:sanctum');
// Route::post('v1/whispers', [App\Http\Controllers\WhisperController::class, 'store']);
Route::post('v1/whispers/{id}', [App\Http\Controllers\WhisperController::class, 'destroy'])->middleware('auth:sanctum');  

///////////////////6. 検索結果取得////////////////
Route::get('v1/search/users/{keyword}', [App\Http\Controllers\SearchController::class, 'usernameSearch'])->middleware('auth:sanctum'); 
Route::get('v1/search/whispers/{keyword}', [App\Http\Controllers\SearchController::class, 'whisperSearch'])->middleware('auth:sanctum');  

///////////////////7. フォロワー・フォロー一覧取得////////////////
Route::get('v1/followers', [App\Http\Controllers\FollwerController::class, 'followers'])->middleware('auth:sanctum');
Route::get('v1/followers/{id}', [App\Http\Controllers\FollwerController::class, 'show'])->middleware('auth:sanctum');

Route::get('v1/following', [App\Http\Controllers\FollwerController::class, 'following'])->middleware('auth:sanctum');

//////////////////8. フォロー登録解除　イイね登録解除////////////////
Route::post('v1/followcheck', [App\Http\Controllers\RegistrationController::class, 'followRegister'])->middleware('auth:sanctum');
Route::post('v1/likecheck', [App\Http\Controllers\RegistrationController::class, 'likeRegister'])->middleware('auth:sanctum');

