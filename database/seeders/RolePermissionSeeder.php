<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifie et crée les permissions si elles n'existent pas
        $permissions = [
            'view appointments',
            'update medical files',
            'create appointments',
            'update appointments',
            'delete appointments',
            'view medical files'
        ];

        foreach ($permissions as $permissionName) {
            if (!Permission::where('name', $permissionName)->exists()) {
                Permission::create(['name' => $permissionName]);
            }
        }

        // Rôles
        $userRole = Role::firstOrCreate(['name' => 'Patient']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $doctorRole = Role::firstOrCreate(['name' => 'Doctor']);
        $secretaryRole = Role::firstOrCreate(['name' => 'Secretary']);
        $accountantRole = Role::firstOrCreate(['name' => 'Accountant']);

        // Attribution des permissions aux rôles
        $userRole->givePermissionTo(['view appointments', 'view medical files']);
        $doctorRole->givePermissionTo(['view appointments', 'update medical files']);
        $secretaryRole->givePermissionTo(['view appointments', 'update appointments']);
        $accountantRole->givePermissionTo(['view medical files']);
        $adminRole->givePermissionTo(['view medical files']);
    }
}
