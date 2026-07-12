<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'medikpedia@admin.com'],
            [
                'name' => 'Admin Medikpedia',
                'email' => 'medikpedia@admin.com',
                'password' => Hash::make('Rh.101185'),
                'role' => 'admin',
            ]
        );
    }
}
