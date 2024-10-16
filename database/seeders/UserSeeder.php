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
            'first_name' => 'Ndeye Marie',
            'last_name' => 'Sagna',
            'email' => 'ndeye@gmail.com',
            'password' => Hash::make('password'), 
            'adress' => '1234 Main St',
            'phone_number' => '+1234567890',
            'day_of_birth' => '1990-01-01',
            'status' => true,
        ]);
        $user1->assignRole('Admin'); 

        $user2 = User::create([
            'first_name' => 'Celine',
            'last_name' => 'Mendy',
            'email' => 'mendy@example.com',
            'password' => Hash::make('password'), 
            'adress' => '5678 Oak St',
            'phone_number' => '+0987654321',
            'day_of_birth' => '1989-02-15',
            'status' => false,
        ]);
        $user2->assignRole('Secretary'); 

        $user3 = User::create([
            'first_name' => 'Moussa',
            'last_name' => 'Sagna',
            'email' => 'sagna@example.com',
            'password' => Hash::make('password'), 
            'adress' => 'Yeumbeul',
            'phone_number' => '+1234567890',
            'day_of_birth' => '1995-08-01',
            'service_id' => '2',
            'status' => true,
        ]);
        $user3->assignRole('Doctor'); 

        $user4 = User::create([
            'first_name' => 'Mareme',
            'last_name' => 'Thiaw',
            'email' => 'thiaw@gmail.com',
            'password' => Hash::make('password'), 
            'adress' => 'Parcelles U24',
            'phone_number' => '+221773243234',
            'day_of_birth' => '1990-01-10',
            'service_id' => '1',
            'status' => true,
        ]);
        $user4->assignRole('Accountant'); 
    }
}
