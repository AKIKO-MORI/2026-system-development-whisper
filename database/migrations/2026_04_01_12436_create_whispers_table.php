<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('whispers', function (Blueprint $table) {
            // 主キー id (BigIncrements)
            $table->id();

            // textカラム (最大文字数を考慮してtext型を指定)
            $table->text('content'); 

            // whisper_id (外部キー: 自己参照)
            // 親が削除されたら子も消えるように cascadeOnDelete を設定
            $table->foreignId('whisper_id')
                  ->nullable() // 親がいない場合を許容
                  ->constrained('whispers')
                  ->cascadeOnDelete();
            // user_id (外部キー: usersテーブル)
            // ユーザーが削除されたらそのユーザーのささやきも消えるように cascadeOnDelete を設定
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();  

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whispers');
    }
};
