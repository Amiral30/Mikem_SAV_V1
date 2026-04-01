<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@savmikem.com'],
            [
                'name' => 'Administrateur',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'telephone' => '0600000000',
                'disponible' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'technicien@savmikem.com'],
            [
                'name' => 'Technicien Test',
                'password' => Hash::make('tech123'),
                'role' => 'technicien',
                'telephone' => '0611111111',
                'disponible' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'tech1@savmikem.com'],
            [
                'name' => 'Technicien Alpha',
                'password' => Hash::make('tech123'),
                'role' => 'technicien',
                'telephone' => '0622222222',
                'disponible' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'tech2@savmikem.com'],
            [
                'name' => 'Technicien Beta',
                'password' => Hash::make('tech123'),
                'role' => 'technicien',
                'telephone' => '0633333333',
                'disponible' => true,
            ]
        );
    }
}
