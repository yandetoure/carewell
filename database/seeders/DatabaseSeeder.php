<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            CategoryTableSeeder::class, // Crée la table des catégories
            ServiceSeeder::class,
            CategorySeeder::class, // Ajoute les catégories aux services
            DiseaseSeeder::class,
            PrescriptionSeeder::class,
            ExamSeeder::class,
            ArticleSeeder::class,
            MedicamentSeeder::class,
            OrdonnanceSeeder::class,
            BedSeeder::class,
            AppointmentSeeder::class, // Crée des rendez-vous avec leurs tickets
        ]);
        
    }
}
