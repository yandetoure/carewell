<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class MissingServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $missingServices = [
            // Services d'ophtalmologie manquants
            [
                'name' => 'Consultation ophtalmologique',
                'photo' => 'ophtalmo-consultation.jpg',
                'description' => 'Consultation spécialisée en ophtalmologie pour diagnostic des troubles oculaires.',
                'price' => 22000,
                'category' => 'ophtalmologie',
            ],
            [
                'name' => 'Examen de la vue',
                'photo' => 'examen-vue.jpg',
                'description' => 'Examen complet de la vue avec mesure de l\'acuité visuelle.',
                'price' => 18000,
                'category' => 'ophtalmologie',
            ],
            [
                'name' => 'Chirurgie de la cataracte',
                'photo' => 'cataracte.jpg',
                'description' => 'Chirurgie de la cataracte avec implantation de lentille intraoculaire.',
                'price' => 45000,
                'category' => 'ophtalmologie',
            ],
            [
                'name' => 'Chirurgie réfractive',
                'photo' => 'chirurgie-refractive.jpg',
                'description' => 'Chirurgie au laser pour corriger la myopie, l\'astigmatisme et l\'hypermétropie.',
                'price' => 60000,
                'category' => 'ophtalmologie',
            ],
            [
                'name' => 'Glaucome',
                'photo' => 'glaucome.jpg',
                'description' => 'Diagnostic et traitement du glaucome avec suivi régulier.',
                'price' => 25000,
                'category' => 'ophtalmologie',
            ],

            // Services de grossesse et maternité manquants
            [
                'name' => 'Préparation à l\'accouchement',
                'photo' => 'preparation-accouchement.jpg',
                'description' => 'Séances de préparation à l\'accouchement et à la parentalité.',
                'price' => 15000,
                'category' => 'maternite',
            ],
            [
                'name' => 'Consultation prénatale',
                'photo' => 'consultation-prenatale.jpg',
                'description' => 'Consultation gynécologique spécialisée pour le suivi prénatal.',
                'price' => 20000,
                'category' => 'maternite',
            ],
            [
                'name' => 'Échographie de grossesse',
                'photo' => 'echo-grossesse.jpg',
                'description' => 'Échographie obstétricale pour suivi du développement fœtal.',
                'price' => 25000,
                'category' => 'maternite',
            ],
            [
                'name' => 'Accouchement normal',
                'photo' => 'accouchement.jpg',
                'description' => 'Prise en charge de l\'accouchement par voie basse.',
                'price' => 80000,
                'category' => 'maternite',
            ],
            [
                'name' => 'Césarienne',
                'photo' => 'cesarienne.jpg',
                'description' => 'Intervention chirurgicale pour l\'accouchement par césarienne.',
                'price' => 120000,
                'category' => 'maternite',
            ],
            [
                'name' => 'Consultation post-natale',
                'photo' => 'post-natal.jpg',
                'description' => 'Consultation de suivi après l\'accouchement.',
                'price' => 18000,
                'category' => 'maternite',
            ],

            // Services de kinésithérapie manquants
            [
                'name' => 'Kinésithérapie respiratoire',
                'photo' => 'kine-respiratoire.jpg',
                'description' => 'Séances de kinésithérapie pour troubles respiratoires.',
                'price' => 12000,
                'category' => 'kinesitherapie',
            ],
            [
                'name' => 'Kinésithérapie orthopédique',
                'photo' => 'kine-orthopedique.jpg',
                'description' => 'Rééducation fonctionnelle après traumatisme orthopédique.',
                'price' => 14000,
                'category' => 'kinesitherapie',
            ],
            [
                'name' => 'Kinésithérapie neurologique',
                'photo' => 'kine-neurologique.jpg',
                'description' => 'Rééducation pour troubles neurologiques et AVC.',
                'price' => 16000,
                'category' => 'kinesitherapie',
            ],
            [
                'name' => 'Kinésithérapie pédiatrique',
                'photo' => 'kine-pediatrique.jpg',
                'description' => 'Kinésithérapie spécialisée pour enfants et nourrissons.',
                'price' => 13000,
                'category' => 'kinesitherapie',
            ],
            [
                'name' => 'Massage thérapeutique',
                'photo' => 'massage-therapeutique.jpg',
                'description' => 'Massage thérapeutique pour soulager les douleurs musculaires.',
                'price' => 10000,
                'category' => 'kinesitherapie',
            ],
            [
                'name' => 'Rééducation périnéale',
                'photo' => 'reeducation-perineale.jpg',
                'description' => 'Rééducation du périnée après accouchement ou intervention.',
                'price' => 15000,
                'category' => 'kinesitherapie',
            ],

            // Services d'ORL manquants
            [
                'name' => 'Consultation ORL',
                'photo' => 'orl-consultation.jpg',
                'description' => 'Consultation spécialisée en oto-rhino-laryngologie.',
                'price' => 20000,
                'category' => 'orl',
            ],
            [
                'name' => 'Audiométrie',
                'photo' => 'audiometrie.jpg',
                'description' => 'Test auditif pour évaluer la capacité auditive.',
                'price' => 15000,
                'category' => 'orl',
            ],
            [
                'name' => 'Chirurgie des amygdales',
                'photo' => 'amygdales.jpg',
                'description' => 'Ablation des amygdales (amygdalectomie).',
                'price' => 35000,
                'category' => 'orl',
            ],
            [
                'name' => 'Chirurgie des végétations',
                'photo' => 'vegetations.jpg',
                'description' => 'Ablation des végétations adénoïdes.',
                'price' => 30000,
                'category' => 'orl',
            ],
            [
                'name' => 'Chirurgie de la cloison nasale',
                'photo' => 'septoplastie.jpg',
                'description' => 'Correction de la déviation de la cloison nasale.',
                'price' => 40000,
                'category' => 'orl',
            ],
            [
                'name' => 'Pose de drains transtympaniques',
                'photo' => 'drains.jpg',
                'description' => 'Pose de drains pour traiter les otites à répétition.',
                'price' => 25000,
                'category' => 'orl',
            ],

            // Services de radiologie manquants
            [
                'name' => 'Radiographie standard',
                'photo' => 'radiographie.jpg',
                'description' => 'Radiographie osseuse et pulmonaire standard.',
                'price' => 12000,
                'category' => 'radiologie',
            ],
            [
                'name' => 'Scanner (TDM)',
                'photo' => 'scanner.jpg',
                'description' => 'Tomodensitométrie pour diagnostic précis.',
                'price' => 35000,
                'category' => 'radiologie',
            ],
            [
                'name' => 'IRM',
                'photo' => 'irm.jpg',
                'description' => 'Imagerie par résonance magnétique.',
                'price' => 50000,
                'category' => 'radiologie',
            ],
            [
                'name' => 'Échographie abdominale',
                'photo' => 'echo-abdominale.jpg',
                'description' => 'Échographie des organes abdominaux.',
                'price' => 18000,
                'category' => 'radiologie',
            ],
            [
                'name' => 'Échographie cardiaque',
                'photo' => 'echo-cardiaque.jpg',
                'description' => 'Échocardiographie pour examen du cœur.',
                'price' => 22000,
                'category' => 'radiologie',
            ],
            [
                'name' => 'Mammographie',
                'photo' => 'mammographie.jpg',
                'description' => 'Examen radiologique des seins pour dépistage.',
                'price' => 20000,
                'category' => 'radiologie',
            ],

            // Services de laboratoire manquants
            [
                'name' => 'Bilan sanguin complet',
                'photo' => 'bilan-sanguin.jpg',
                'description' => 'Analyse complète du sang avec tous les paramètres.',
                'price' => 15000,
                'category' => 'laboratoire',
            ],
            [
                'name' => 'Analyse d\'urines',
                'photo' => 'analyse-urines.jpg',
                'description' => 'Examen complet des urines avec cytologie.',
                'price' => 8000,
                'category' => 'laboratoire',
            ],
            [
                'name' => 'Hépatite B et C',
                'photo' => 'hepatite.jpg',
                'description' => 'Dépistage des hépatites B et C.',
                'price' => 12000,
                'category' => 'laboratoire',
            ],
            [
                'name' => 'VIH/SIDA',
                'photo' => 'vih.jpg',
                'description' => 'Test de dépistage du VIH/SIDA.',
                'price' => 10000,
                'category' => 'laboratoire',
            ],
            [
                'name' => 'Culture bactérienne',
                'photo' => 'culture.jpg',
                'description' => 'Culture et identification des bactéries.',
                'price' => 18000,
                'category' => 'laboratoire',
            ],
            [
                'name' => 'Parasitologie',
                'photo' => 'parographie.jpg',
                'description' => 'Recherche de parasites dans les selles.',
                'price' => 10000,
                'category' => 'laboratoire',
            ],

            // Services d'urgence manquants
            [
                'name' => 'Réanimation',
                'photo' => 'reanimation.jpg',
                'description' => 'Service de réanimation pour cas critiques.',
                'price' => 100000,
                'category' => 'urgences',
            ],
            [
                'name' => 'Chirurgie d\'urgence',
                'photo' => 'chirurgie-urgence.jpg',
                'description' => 'Interventions chirurgicales en urgence.',
                'price' => 80000,
                'category' => 'urgences',
            ],
            [
                'name' => 'Traumatologie d\'urgence',
                'photo' => 'trauma-urgence.jpg',
                'description' => 'Prise en charge des traumatismes graves.',
                'price' => 60000,
                'category' => 'urgences',
            ],
            [
                'name' => 'Soins intensifs',
                'photo' => 'soins-intensifs.jpg',
                'description' => 'Unité de soins intensifs pour patients critiques.',
                'price' => 120000,
                'category' => 'urgences',
            ],
        ];

        foreach ($missingServices as $serviceData) {
            // Vérifier si le service n'existe pas déjà
            $existingService = Service::where('name', $serviceData['name'])->first();
            if (!$existingService) {
                Service::create($serviceData);
                $this->command->info('Service créé: ' . $serviceData['name']);
            } else {
                $this->command->warn('Service déjà existant: ' . $serviceData['name']);
            }
        }

        $this->command->info('Missing services seeded successfully!');
        $this->command->info('Total services in database: ' . Service::count());
    }
}