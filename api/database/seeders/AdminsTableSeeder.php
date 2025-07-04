<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admins')->insert([
            'username' => 'admin',
            // 假设你使用的是 Laravel 的默认密码哈希方式
            'password' => Hash::make('123456'),
            'is_super' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
