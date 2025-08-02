<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@academia.com',
            'password' => Hash::make('123456'),
        ]);

        User::create([
            'name' => 'João Silva',
            'email' => 'joao@academia.com',
            'password' => Hash::make('123456'),
        ]);
    }
}