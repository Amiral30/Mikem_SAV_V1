<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Compte Admin par défaut
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

        // Compte Technicien de test
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
    }
}
