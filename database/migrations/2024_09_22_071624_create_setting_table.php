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
        Schema::create('setting', function (Blueprint $table) {
            $table->id(); // 設定ID
            $table->foreignid('user_id')->constrained()->onDelate('cascade'); //usesテーブルの外部キー
            $table->string('setting_key'); //設定項目
            $table->string('setting_value'); //設置値
            $table->timestamps(); // created_at と updated_atを自動生成
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting');
    }
};
