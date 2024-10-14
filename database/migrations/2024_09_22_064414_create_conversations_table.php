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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id(); // 会話ID（自動インクリメント）
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 外部キー usersテーブルへの参照
            $table->text('content'); // 会話内容（テキスト）
            $table->timestamps(); //created_at と updated_atの自動生成
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
