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
        Schema::create('conversation_histories', function (Blueprint $table) {
            $table->id(); //履歴ID
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //外部キー usersテーブルへの参照
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade'); // 外部キー conversationテーブルへの参照
            $table->timestamp('saved_at');
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversation_histories');
    }
};
