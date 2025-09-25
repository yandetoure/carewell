<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Urgences',
                'photo' => 'urgences.jpg',
                'description' => 'Service d\'urgence médicale pour les cas nécessitant une prise en charge immédiate.',
                'price' => 25000,
            ],
            [
                'name' => 'Maternité',
                'photo' => 'maternite.jpg',
                'description' => 'Service de gynécologie-obstétrique pour le suivi des grossesses et accouchements.',
                'price' => 30000,
            ],
            [
                'name' => 'Radiologie',
                'photo' => 'radiologie.jpg',
                'description' => 'Service d\'imagerie médicale : radiographies, scanners, IRM, échographies.',
                'price' => 20000,
            ],
            [
                'name' => 'Laboratoire',
                'photo' => 'laboratoire.jpg',
                'description' => 'Service d\'analyses biologiques : sang, urines, cultures, etc.',
                'price' => 15000,
            ],
            [
                'name' => 'Chirurgie',
                'photo' => 'chirurgie.jpg',
                'description' => 'Service de chirurgie générale et spécialisée.',
                'price' => 50000,
            ],
            [
                'name' => 'Cardiologie',
                'photo' => 'cardiologie.jpg',
                'description' => 'Service spécialisé dans les maladies cardiovasculaires.',
                'price' => 25000,
            ],
            [
                'name' => 'Pneumologie',
                'photo' => 'pneumologie.jpg',
                'description' => 'Service spécialisé dans les maladies respiratoires.',
                'price' => 20000,
            ],
            [
                'name' => 'Neurologie',
                'photo' => 'neurologie.jpg',
                'description' => 'Service spécialisé dans les maladies neurologiques.',
                'price' => 30000,
            ],
            [
                'name' => 'Dermatologie',
                'photo' => 'dermatologie.jpg',
                'description' => 'Service spécialisé dans les maladies de la peau.',
                'price' => 20000,
            ],
            [
                'name' => 'Pédiatrie',
                'photo' => 'pediatrie.jpg',
                'description' => 'Service spécialisé dans les soins aux enfants.',
                'price' => 18000,
            ],
            [
                'name' => 'Gynécologie',
                'photo' => 'gynecologie.jpg',
                'description' => 'Service spécialisé dans la santé féminine.',
                'price' => 22000,
            ],
            [
                'name' => 'Urologie',
                'photo' => 'urologie.jpg',
                'description' => 'Service spécialisé dans les maladies urologiques.',
                'price' => 22000,
            ],
            [
                'name' => 'Ophtalmologie',
                'photo' => 'ophtalmologie.jpg',
                'description' => 'Service spécialisé dans les maladies oculaires.',
                'price' => 20000,
            ],
            [
                'name' => 'ORL',
                'photo' => 'orl.jpg',
                'description' => 'Service spécialisé en oto-rhino-laryngologie.',
                'price' => 20000,
            ],
            [
                'name' => 'Endocrinologie',
                'photo' => 'endocrinologie.jpg',
                'description' => 'Service spécialisé dans les maladies endocriniennes.',
                'price' => 25000,
            ],
            [
                'name' => 'Gastro-entérologie',
                'photo' => 'gastro.jpg',
                'description' => 'Service spécialisé dans les maladies digestives.',
                'price' => 25000,
            ],
            [
                'name' => 'Rhumatologie',
                'photo' => 'rhumatologie.jpg',
                'description' => 'Service spécialisé dans les maladies articulaires.',
                'price' => 20000,
            ],
            [
                'name' => 'Psychiatrie',
                'photo' => 'psychiatrie.jpg',
                'description' => 'Service spécialisé dans les troubles mentaux.',
                'price' => 25000,
            ],
            [
                'name' => 'Oncologie',
                'photo' => 'oncologie.jpg',
                'description' => 'Service spécialisé dans le traitement des cancers.',
                'price' => 40000,
            ],
            [
                'name' => 'Néphrologie',
                'photo' => 'nephrologie.jpg',
                'description' => 'Service spécialisé dans les maladies rénales.',
                'price' => 25000,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
