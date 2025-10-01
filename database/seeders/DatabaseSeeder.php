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
            ServiceSeeder::class,
            DiseaseSeeder::class,
            PrescriptionSeeder::class,
            ExamSeeder::class,
            ArticleSeeder::class,
            MedicamentSeeder::class,
            OrdonnanceSeeder::class,
            BedSeeder::class,
        ]);
        
    }
}
