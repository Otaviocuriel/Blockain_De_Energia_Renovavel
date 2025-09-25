<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'otavio@admin.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Otavio123456'),
                'role' => 'admin',
            ]
        );
    }
}
