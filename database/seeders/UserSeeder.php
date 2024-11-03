<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création d'utilisateurs fictifs
        $user1 = User::create([
            'first_name' => 'Biteye',
            'last_name' => 'Sow',
            'email' => 'ndeye@gmail.com',
            'password' => Hash::make('password'), 
            'adress' => 'Point E',
            'phone_number' => '+221774344454',
            'day_of_birth' => '1990-01-01',
            'status' => true,
        ]);
        $user1->assignRole('Admin'); 
    }     
}