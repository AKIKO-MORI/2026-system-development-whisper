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
        Schema::create('likes', function (Blueprint $table) {
            // 1. 自動増分のID（主キー）を追加
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('whisper_id')
                ->constrained('whispers')
                ->cascadeOnDelete();

            // 2. 複合主キーの代わりに、ユニーク制約を設定
            // これにより「同じユーザーが同じ投稿に1回だけいいねできる」仕様を維持できます
            $table->unique(['user_id', 'whisper_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
