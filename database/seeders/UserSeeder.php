<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'User Diknas',
                'email' => 'diknas@dataprint.com',
                'password' => 'user123',
                'role' => 'user',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@dataprint.com',
                'password' => 'admin123',
                'role' => 'admin',
            ],
            [
                'name' => 'Finance',
                'email' => 'finance@dataprint.com',
                'password' => 'finance123',
                'role' => 'finance',
            ],
            [
                'name' => 'Teknisi',
                'email' => 'teknisi@gmail.com',
                'password' => 'teknisi123',
                'role' => 'teknisi',
            ],
            [
                'name' => 'Owner',
                'email' => 'owner@dataprint.com',
                'password' => 'owner123',
                'role' => 'owner',
            ],
        ];

        foreach ($users as $data) {
            $user = \App\Models\User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                ]
            );

            $user->assignRole([$data['role']]);
        }
    }
}
