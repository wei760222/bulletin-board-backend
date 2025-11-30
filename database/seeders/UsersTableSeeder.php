<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 使用 firstOrCreate 避免重複創建
          User::firstOrCreate([
            'name' => '系統管理員',
            'email' => 'admin@example.com',
            'password' => Hash::make('1234'),
        ]);
    }
}
