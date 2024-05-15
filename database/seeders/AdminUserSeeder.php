<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin_for_test',
            'password' => bcrypt('12345'),
            'user_type' => 'Admin',
            'user_branch' => 'Head Office',
        ]);
    }
}
