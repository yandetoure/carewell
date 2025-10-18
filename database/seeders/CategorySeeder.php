<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mettre à jour les services existants avec des catégories appropriées
        $servicesCategories = [
            // Services d'urgence et chirurgie
            'Urgences' => 'general',
            'Chirurgie' => 'general',
            'Radiologie' => 'general',
            'Laboratoire' => 'general',
            
            // Services spécialisés
            'Cardiologie' => 'cardiology',
            'Neurologie' => 'neurology',
            'Dermatologie' => 'dermatology',
            'Pédiatrie' => 'pediatrics',
            'Gynécologie' => 'gynecology',
            
            // Services de prévention
            'Maternité' => 'prevention',
            'Urologie' => 'prevention',
            'Ophtalmologie' => 'prevention',
            'ORL' => 'prevention',
            
            // Services nutritionnels et fitness
            'Endocrinologie' => 'nutrition',
            'Gastro-entérologie' => 'nutrition',
            'Néphrologie' => 'nutrition',
            
            // Services de remise en forme
            'Pneumologie' => 'fitness',
            'Rhumatologie' => 'orthopedics',
            'Psychiatrie' => 'general',
            'Oncologie' => 'general',
        ];

        // Mettre à jour les services existants avec leurs catégories
        foreach ($servicesCategories as $serviceName => $category) {
            $service = Service::where('name', $serviceName)->first();
            if ($service) {
                $service->update(['category' => $category]);
            }
        }

        // Créer de nouveaux services spécifiques pour chaque catégorie si nécessaire
        $newServices = [
            // Services de prévention
            [
                'name' => 'Consultation préventive',
                'photo' => 'prevention.jpg',
                'description' => 'Consultation médicale de prévention et dépistage précoce des maladies.',
                'price' => 15000,
                'category' => 'prevention',
            ],
            [
                'name' => 'Vaccination',
                'photo' => 'vaccination.jpg',
                'description' => 'Service de vaccination pour enfants et adultes.',
                'price' => 8000,
                'category' => 'prevention',
            ],
            [
                'name' => 'Bilan de santé',
                'photo' => 'bilan.jpg',
                'description' => 'Bilan de santé complet avec analyses et examens.',
                'price' => 35000,
                'category' => 'prevention',
            ],

            // Services nutritionnels
            [
                'name' => 'Consultation nutritionnelle',
                'photo' => 'nutrition.jpg',
                'description' => 'Conseils nutritionnels personnalisés et suivi diététique.',
                'price' => 18000,
                'category' => 'nutrition',
            ],
            [
                'name' => 'Suivi de perte de poids',
                'photo' => 'regime.jpg',
                'description' => 'Programme personnalisé de perte de poids avec suivi médical.',
                'price' => 25000,
                'category' => 'nutrition',
            ],
            [
                'name' => 'Diabétologie',
                'photo' => 'diabete.jpg',
                'description' => 'Suivi et traitement du diabète avec éducation thérapeutique.',
                'price' => 22000,
                'category' => 'nutrition',
            ],

            // Services fitness
            [
                'name' => 'Rééducation fonctionnelle',
                'photo' => 'reeducation.jpg',
                'description' => 'Séances de rééducation et réadaptation fonctionnelle.',
                'price' => 20000,
                'category' => 'fitness',
            ],
            [
                'name' => 'Kinésithérapie',
                'photo' => 'kinesitherapie.jpg',
                'description' => 'Séances de kinésithérapie pour récupération musculaire.',
                'price' => 15000,
                'category' => 'fitness',
            ],
            [
                'name' => 'Médecine du sport',
                'photo' => 'sport.jpg',
                'description' => 'Consultation spécialisée en médecine du sport.',
                'price' => 25000,
                'category' => 'fitness',
            ],

            // Services d'orthopédie
            [
                'name' => 'Consultation orthopédique',
                'photo' => 'orthopedie.jpg',
                'description' => 'Consultation spécialisée en chirurgie orthopédique.',
                'price' => 28000,
                'category' => 'orthopedics',
            ],
            [
                'name' => 'Traumatologie',
                'photo' => 'trauma.jpg',
                'description' => 'Prise en charge des traumatismes osseux et articulaires.',
                'price' => 30000,
                'category' => 'orthopedics',
            ],
            [
                'name' => 'Chirurgie de la main',
                'photo' => 'main.jpg',
                'description' => 'Chirurgie spécialisée de la main et du poignet.',
                'price' => 35000,
                'category' => 'orthopedics',
            ],

            // Services de dermatologie
            [
                'name' => 'Dermatologie esthétique',
                'photo' => 'dermato-esthetique.jpg',
                'description' => 'Traitements esthétiques de la peau et anti-âge.',
                'price' => 25000,
                'category' => 'dermatology',
            ],
            [
                'name' => 'Dermatologie pédiatrique',
                'photo' => 'dermato-enfant.jpg',
                'description' => 'Soins dermatologiques spécialisés pour enfants.',
                'price' => 20000,
                'category' => 'dermatology',
            ],
            [
                'name' => 'Chirurgie dermatologique',
                'photo' => 'chirurgie-dermo.jpg',
                'description' => 'Chirurgie des lésions cutanées et mélanomes.',
                'price' => 30000,
                'category' => 'dermatology',
            ],

            // Services de cardiologie
            [
                'name' => 'Échographie cardiaque',
                'photo' => 'echo-cardiaque.jpg',
                'description' => 'Échographie doppler cardiaque et vasculaire.',
                'price' => 22000,
                'category' => 'cardiology',
            ],
            [
                'name' => 'Holter cardiaque',
                'photo' => 'holter.jpg',
                'description' => 'Surveillance cardiaque sur 24h avec holter.',
                'price' => 18000,
                'category' => 'cardiology',
            ],
            [
                'name' => 'Test d\'effort',
                'photo' => 'test-effort.jpg',
                'description' => 'Test d\'effort cardiologique avec électrocardiogramme.',
                'price' => 25000,
                'category' => 'cardiology',
            ],

            // Services de neurologie
            [
                'name' => 'Électroencéphalogramme',
                'photo' => 'eeg.jpg',
                'description' => 'Électroencéphalogramme pour diagnostic neurologique.',
                'price' => 20000,
                'category' => 'neurology',
            ],
            [
                'name' => 'Consultation mémoire',
                'photo' => 'memoire.jpg',
                'description' => 'Consultation spécialisée pour troubles de la mémoire.',
                'price' => 28000,
                'category' => 'neurology',
            ],
            [
                'name' => 'Électromyographie',
                'photo' => 'emg.jpg',
                'description' => 'Électromyographie pour diagnostic des neuropathies.',
                'price' => 25000,
                'category' => 'neurology',
            ],

            // Services de pédiatrie
            [
                'name' => 'Consultation nouveau-né',
                'photo' => 'nouveau-ne.jpg',
                'description' => 'Consultation pédiatrique spécialisée pour nouveau-nés.',
                'price' => 15000,
                'category' => 'pediatrics',
            ],
            [
                'name' => 'Suivi de croissance',
                'photo' => 'croissance.jpg',
                'description' => 'Suivi de la croissance et développement de l\'enfant.',
                'price' => 18000,
                'category' => 'pediatrics',
            ],
            [
                'name' => 'Vaccination pédiatrique',
                'photo' => 'vaccin-enfant.jpg',
                'description' => 'Vaccination spécialisée pour enfants selon le calendrier vaccinal.',
                'price' => 10000,
                'category' => 'pediatrics',
            ],

            // Services de gynécologie
            [
                'name' => 'Consultation gynécologique',
                'photo' => 'gyneco-consultation.jpg',
                'description' => 'Consultation gynécologique de routine et dépistage.',
                'price' => 20000,
                'category' => 'gynecology',
            ],
            [
                'name' => 'Échographie pelvienne',
                'photo' => 'echo-pelvienne.jpg',
                'description' => 'Échographie pelvienne et transvaginale.',
                'price' => 18000,
                'category' => 'gynecology',
            ],
            [
                'name' => 'Suivi de grossesse',
                'photo' => 'suivi-grossesse.jpg',
                'description' => 'Suivi médical complet de la grossesse.',
                'price' => 25000,
                'category' => 'gynecology',
            ],
        ];

        // Créer les nouveaux services
        foreach ($newServices as $serviceData) {
            // Vérifier si le service n'existe pas déjà
            $existingService = Service::where('name', $serviceData['name'])->first();
            if (!$existingService) {
                Service::create($serviceData);
            }
        }

        $this->command->info('Categories seeded successfully!');
        $this->command->info('Updated existing services with categories.');
        $this->command->info('Created new services for each category.');
    }
}