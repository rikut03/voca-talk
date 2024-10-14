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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id(); // プロフィールID
            $table->foreignId('user_id')->constrained('users'); // ユーザーID (外部キー)
            $table->string('conversation_style'); // 会話形式の設定
            $table->timestamps(); // created_at と updated_at が自動で作成される
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
