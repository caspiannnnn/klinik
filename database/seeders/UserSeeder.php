<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => 'password',
            ],
            [
                'name' => 'Dokter User',
                'username' => 'dokter',
                'email' => 'dokter@gmail.com',
                'role' => 'dokter',
                'password' => 'password',
            ],
            [
                'name' => 'Pasien User',
                'username' => 'pasien',
                'email' => 'pasien@gmail.com',
                'role' => 'pasien',
                'password' => 'password',
            ],
            [
                'name' => 'Resepsionis User',
                'username' => 'resepsionis',
                'email' => 'resepsionis@gmail.com',
                'role' => 'resepsionis',
                'password' => 'password',
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'username' => $u['username'],
                    'role' => $u['role'],
                    'password' => Hash::make($u['password']),
                ]
            );
        }
    }
}
