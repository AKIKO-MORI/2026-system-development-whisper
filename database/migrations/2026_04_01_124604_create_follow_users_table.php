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
        Schema::create('follow_users', function (Blueprint $table) {
            // 1. 標準の主キーを追加
            $table->id();

            // フォローする側のユーザーID
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // フォローされる側のユーザーID
            $table->foreignId('follow_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 2. 複合主キーの代わりにユニーク制約を設定（二重フォローを防止）
            $table->unique(['user_id', 'follow_user_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_users');
    }
};
