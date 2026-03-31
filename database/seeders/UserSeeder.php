<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'Mohammad Rahman', 'email' => 'rahman@example.com'],
            ['name' => 'Ayesha Begum', 'email' => 'ayesha@example.com'],
            ['name' => 'Abdul Karim', 'email' => 'karim@example.com'],
            ['name' => 'Fatima Khatun', 'email' => 'fatima@example.com'],
            ['name' => 'Hasan Ali', 'email' => 'hasan@example.com'],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
            ]);
        }
    }
}
