<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Santé générale',
                'slug' => 'general',
                'description' => 'Services de santé générale et consultations de routine',
                'icon' => 'fas fa-heartbeat',
                'color' => 'primary',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Prévention',
                'slug' => 'prevention',
                'description' => 'Services de prévention et dépistage précoce des maladies',
                'icon' => 'fas fa-shield-alt',
                'color' => 'success',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Nutrition',
                'slug' => 'nutrition',
                'description' => 'Conseils nutritionnels et diététique personnalisés',
                'icon' => 'fas fa-apple-alt',
                'color' => 'warning',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Fitness',
                'slug' => 'fitness',
                'description' => 'Services de remise en forme et rééducation fonctionnelle',
                'icon' => 'fas fa-dumbbell',
                'color' => 'info',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Dermatologie',
                'slug' => 'dermatology',
                'description' => 'Soins de la peau et maladies cutanées',
                'icon' => 'fas fa-hand-holding-medical',
                'color' => 'secondary',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Cardiologie',
                'slug' => 'cardiology',
                'description' => 'Soins du cœur et système cardiovasculaire',
                'icon' => 'fas fa-heart',
                'color' => 'danger',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Neurologie',
                'slug' => 'neurology',
                'description' => 'Soins du système nerveux et du cerveau',
                'icon' => 'fas fa-brain',
                'color' => 'dark',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Pédiatrie',
                'slug' => 'pediatrics',
                'description' => 'Soins médicaux spécialisés pour enfants',
                'icon' => 'fas fa-child',
                'color' => 'primary',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Gynécologie',
                'slug' => 'gynecology',
                'description' => 'Soins de santé féminine et suivi gynécologique',
                'icon' => 'fas fa-female',
                'color' => 'pink',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Orthopédie',
                'slug' => 'orthopedics',
                'description' => 'Soins des os, articulations et muscles',
                'icon' => 'fas fa-bone',
                'color' => 'warning',
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Ophtalmologie',
                'slug' => 'ophtalmologie',
                'description' => 'Soins des yeux et chirurgie oculaire',
                'icon' => 'fas fa-eye',
                'color' => 'info',
                'sort_order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'Maternité',
                'slug' => 'maternite',
                'description' => 'Suivi de grossesse et accouchement',
                'icon' => 'fas fa-baby',
                'color' => 'pink',
                'sort_order' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Kinésithérapie',
                'slug' => 'kinesitherapie',
                'description' => 'Rééducation fonctionnelle et thérapie manuelle',
                'icon' => 'fas fa-hands-helping',
                'color' => 'success',
                'sort_order' => 13,
                'is_active' => true,
            ],
            [
                'name' => 'ORL',
                'slug' => 'orl',
                'description' => 'Soins des oreilles, nez et gorge',
                'icon' => 'fas fa-head-side-virus',
                'color' => 'primary',
                'sort_order' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Radiologie',
                'slug' => 'radiologie',
                'description' => 'Imagerie médicale et diagnostic radiologique',
                'icon' => 'fas fa-x-ray',
                'color' => 'secondary',
                'sort_order' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Laboratoire',
                'slug' => 'laboratoire',
                'description' => 'Analyses biologiques et examens de laboratoire',
                'icon' => 'fas fa-flask',
                'color' => 'warning',
                'sort_order' => 16,
                'is_active' => true,
            ],
            [
                'name' => 'Urgences',
                'slug' => 'urgences',
                'description' => 'Services d\'urgence et soins critiques',
                'icon' => 'fas fa-ambulance',
                'color' => 'danger',
                'sort_order' => 17,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        $this->command->info('Categories table seeded successfully!');
        $this->command->info('Created ' . count($categories) . ' categories.');
    }
}