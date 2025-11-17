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
        Schema::table('users', function (Blueprint $table) {
            // 添加 firebase_uid 欄位，用於存儲 Firebase 用戶 ID
            $table->string('firebase_uid')
                  ->nullable()
                  ->unique()
                  ->comment('Firebase 用戶唯一識別碼');
                  
            // 添加 role 欄位，預設為 'user'
            $table->string('role')
                  ->default('user')
                  ->comment('用戶角色：admin, moderator, user 等');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             // 移除新增的欄位
            $table->dropColumn(['firebase_uid', 'role']); //
        });
    }
};
