<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'utilisateurs fictifs
        $user1 = User::create([
            'first_name' => 'Ndeye Yande',
            'last_name' => 'Toure',
            'email' => 'ndeyeyandemouhamma@gmail.com',
            'password' => Hash::make('password'), // Hachage du mot de passe
            'adress' => '1234 Main St',
            'call' => '+1234567890',
            'day_of_birth' => '1990-01-01',
            'status' => true,
        ]);
        $user1->assignRole('Patient'); // Assigner le rôle par nom

        $user2 = User::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'password' => Hash::make('password'), // Hachage du mot de passe
            'adress' => '5678 Oak St',
            'call' => '+0987654321',
            'day_of_birth' => '1995-02-15',
            'status' => false,
        ]);
        $user2->assignRole('Patient'); // Assigner le rôle par nom

        $user3 = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password'), // Hachage du mot de passe
            'adress' => '1234 Main St',
            'call' => '+1234567890',
            'day_of_birth' => '1990-01-01',
            'status' => true,
        ]);
        $user3->assignRole('Doctor'); // Assigner le rôle par nom
    }
}
