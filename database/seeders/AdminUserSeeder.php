<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer un compte administrateur
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@tontine.com',
            'phone' => '+225 07 07 07 07 07',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Créer quelques clients de test
        User::create([
            'name' => 'Jean Kouassi',
            'email' => 'jean@example.com',
            'phone' => '+225 01 02 03 04 05',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        User::create([
            'name' => 'Marie Kouadio',
            'email' => 'marie@example.com',
            'phone' => '+225 05 06 07 08 09',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        User::create([
            'name' => 'Yao Konan',
            'email' => 'yao@example.com',
            'phone' => '+225 07 08 09 10 11',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        User::create([
            'name' => 'Aminata Traoré',
            'email' => 'aminata@example.com',
            'phone' => '+225 02 03 04 05 06',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);

        User::create([
            'name' => 'Kouamé Koffi',
            'email' => 'kouame@example.com',
            'phone' => '+225 06 07 08 09 10',
            'password' => Hash::make('password'),
            'role' => 'client',
        ]);
    }
}
