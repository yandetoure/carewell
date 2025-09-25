<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les services pour associer les examens
        $services = Service::all();
        
        $exams = [
            // Examens de laboratoire
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Analyse de sang complète',
                'description' => 'Analyse complète du sang incluant hémogramme, glycémie, cholestérol, fonction rénale et hépatique.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Analyse d\'urine',
                'description' => 'Examen complet de l\'urine pour détecter infections, problèmes rénaux et autres anomalies.',
                'price' => 8000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Glycémie à jeun',
                'description' => 'Mesure de la glycémie après 12h de jeûne.',
                'price' => 5000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'HbA1c',
                'description' => 'Dosage de l\'hémoglobine glyquée pour le diabète.',
                'price' => 8000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Cholestérol total',
                'description' => 'Dosage du cholestérol total et des fractions.',
                'price' => 6000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Fonction rénale',
                'description' => 'Dosage de la créatinine et de l\'urée.',
                'price' => 5000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Fonction hépatique',
                'description' => 'Dosage des enzymes hépatiques (ALAT, ASAT, GGT).',
                'price' => 7000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Hormones thyroïdiennes',
                'description' => 'Dosage de TSH, T3, T4.',
                'price' => 12000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Hémogramme',
                'description' => 'Analyse complète des cellules sanguines.',
                'price' => 8000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Vitamine D',
                'description' => 'Dosage de la vitamine D.',
                'price' => 10000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Ferritine',
                'description' => 'Dosage de la ferritine pour évaluer les réserves en fer.',
                'price' => 6000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Culture d\'urine',
                'description' => 'Culture bactérienne de l\'urine.',
                'price' => 8000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Hépatite B',
                'description' => 'Dépistage de l\'hépatite B.',
                'price' => 10000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'Hépatite C',
                'description' => 'Dépistage de l\'hépatite C.',
                'price' => 10000,
            ],
            [
                'service_id' => $services->where('name', 'Laboratoire')->first()->id ?? 1,
                'name' => 'VIH',
                'description' => 'Dépistage du VIH.',
                'price' => 5000,
            ],
            
            // Examens de radiologie
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Radiographie thoracique',
                'description' => 'Radiographie du thorax pour évaluer les poumons et le cœur.',
                'price' => 12000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Radiographie abdominale',
                'description' => 'Radiographie de l\'abdomen pour évaluer les organes digestifs.',
                'price' => 10000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Radiographie osseuse',
                'description' => 'Radiographie des os pour détecter fractures ou anomalies.',
                'price' => 8000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Scanner thoracique',
                'description' => 'Tomodensitométrie du thorax.',
                'price' => 40000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Scanner abdominal',
                'description' => 'Tomodensitométrie de l\'abdomen.',
                'price' => 45000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Scanner cérébral',
                'description' => 'Tomodensitométrie du cerveau.',
                'price' => 50000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'IRM cérébrale',
                'description' => 'Imagerie par résonance magnétique du cerveau.',
                'price' => 80000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'IRM articulaire',
                'description' => 'Imagerie par résonance magnétique des articulations.',
                'price' => 60000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Échographie abdominale',
                'description' => 'Échographie des organes abdominaux.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Échographie pelvienne',
                'description' => 'Échographie des organes pelviens.',
                'price' => 18000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Échographie thyroïdienne',
                'description' => 'Échographie de la glande thyroïde.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Échographie cardiaque',
                'description' => 'Échocardiographie pour évaluer la fonction cardiaque.',
                'price' => 25000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Mammographie',
                'description' => 'Radiographie des seins pour dépistage du cancer.',
                'price' => 20000,
            ],
            [
                'service_id' => $services->where('name', 'Radiologie')->first()->id ?? 1,
                'name' => 'Échographie mammaire',
                'description' => 'Échographie des seins pour évaluation des anomalies.',
                'price' => 15000,
            ],
            
            // Examens cardiologiques
            [
                'service_id' => $services->where('name', 'Cardiologie')->first()->id ?? 1,
                'name' => 'Électrocardiogramme (ECG)',
                'description' => 'Enregistrement de l\'activité électrique du cœur.',
                'price' => 10000,
            ],
            [
                'service_id' => $services->where('name', 'Cardiologie')->first()->id ?? 1,
                'name' => 'Holter ECG 24h',
                'description' => 'Enregistrement continu de l\'ECG pendant 24 heures.',
                'price' => 30000,
            ],
            [
                'service_id' => $services->where('name', 'Cardiologie')->first()->id ?? 1,
                'name' => 'Holter tensionnel 24h',
                'description' => 'Mesure continue de la tension artérielle pendant 24 heures.',
                'price' => 25000,
            ],
            [
                'service_id' => $services->where('name', 'Cardiologie')->first()->id ?? 1,
                'name' => 'Test d\'effort',
                'description' => 'Électrocardiogramme pendant l\'exercice pour évaluer la fonction cardiaque.',
                'price' => 20000,
            ],
            [
                'service_id' => $services->where('name', 'Cardiologie')->first()->id ?? 1,
                'name' => 'Échocardiographie',
                'description' => 'Échographie du cœur pour évaluer la fonction cardiaque et les valves.',
                'price' => 25000,
            ],
            
            // Examens pneumologiques
            [
                'service_id' => $services->where('name', 'Pneumologie')->first()->id ?? 1,
                'name' => 'Spirométrie',
                'description' => 'Mesure de la fonction pulmonaire.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'Pneumologie')->first()->id ?? 1,
                'name' => 'Gazométrie artérielle',
                'description' => 'Analyse des gaz sanguins artériels.',
                'price' => 12000,
            ],
            [
                'service_id' => $services->where('name', 'Pneumologie')->first()->id ?? 1,
                'name' => 'Test de marche 6 minutes',
                'description' => 'Évaluation de la capacité d\'exercice.',
                'price' => 8000,
            ],
            [
                'service_id' => $services->where('name', 'Pneumologie')->first()->id ?? 1,
                'name' => 'Bronchoscopie',
                'description' => 'Examen endoscopique des bronches.',
                'price' => 40000,
            ],
            
            // Examens neurologiques
            [
                'service_id' => $services->where('name', 'Neurologie')->first()->id ?? 1,
                'name' => 'Électroencéphalogramme (EEG)',
                'description' => 'Enregistrement de l\'activité électrique du cerveau.',
                'price' => 25000,
            ],
            [
                'service_id' => $services->where('name', 'Neurologie')->first()->id ?? 1,
                'name' => 'Électromyographie (EMG)',
                'description' => 'Évaluation de la fonction musculaire et nerveuse.',
                'price' => 30000,
            ],
            [
                'service_id' => $services->where('name', 'Neurologie')->first()->id ?? 1,
                'name' => 'Ponction lombaire',
                'description' => 'Prélèvement de liquide céphalo-rachidien.',
                'price' => 30000,
            ],
            
            // Examens dermatologiques
            [
                'service_id' => $services->where('name', 'Dermatologie')->first()->id ?? 1,
                'name' => 'Biopsie cutanée',
                'description' => 'Prélèvement d\'un échantillon de peau pour analyse microscopique.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'Dermatologie')->first()->id ?? 1,
                'name' => 'Test d\'allergie cutané',
                'description' => 'Tests cutanés pour identifier les allergènes.',
                'price' => 18000,
            ],
            [
                'service_id' => $services->where('name', 'Dermatologie')->first()->id ?? 1,
                'name' => 'Dermoscopie',
                'description' => 'Examen microscopique de la peau pour détecter les lésions suspectes.',
                'price' => 12000,
            ],
            
            // Examens pédiatriques
            [
                'service_id' => $services->where('name', 'Pédiatrie')->first()->id ?? 1,
                'name' => 'Bilan de croissance',
                'description' => 'Évaluation complète de la croissance et du développement de l\'enfant.',
                'price' => 10000,
            ],
            [
                'service_id' => $services->where('name', 'Pédiatrie')->first()->id ?? 1,
                'name' => 'Test auditif pédiatrique',
                'description' => 'Évaluation de l\'audition chez l\'enfant.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'Pédiatrie')->first()->id ?? 1,
                'name' => 'Test de vision pédiatrique',
                'description' => 'Évaluation de la vision chez l\'enfant.',
                'price' => 12000,
            ],
            
            // Examens gynécologiques
            [
                'service_id' => $services->where('name', 'Gynécologie')->first()->id ?? 1,
                'name' => 'Frottis cervico-vaginal',
                'description' => 'Dépistage du cancer du col de l\'utérus.',
                'price' => 12000,
            ],
            [
                'service_id' => $services->where('name', 'Gynécologie')->first()->id ?? 1,
                'name' => 'Hystéroscopie',
                'description' => 'Examen endoscopique de l\'utérus.',
                'price' => 35000,
            ],
            [
                'service_id' => $services->where('name', 'Gynécologie')->first()->id ?? 1,
                'name' => 'Colposcopie',
                'description' => 'Examen du col de l\'utérus avec grossissement.',
                'price' => 20000,
            ],
            
            // Examens urologiques
            [
                'service_id' => $services->where('name', 'Urologie')->first()->id ?? 1,
                'name' => 'Échographie rénale',
                'description' => 'Échographie des reins et des voies urinaires.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'Urologie')->first()->id ?? 1,
                'name' => 'Cystoscopie',
                'description' => 'Examen endoscopique de la vessie.',
                'price' => 20000,
            ],
            [
                'service_id' => $services->where('name', 'Urologie')->first()->id ?? 1,
                'name' => 'Analyse du sperme',
                'description' => 'Analyse complète du sperme pour évaluation de la fertilité.',
                'price' => 12000,
            ],
            [
                'service_id' => $services->where('name', 'Urologie')->first()->id ?? 1,
                'name' => 'Test PSA',
                'description' => 'Dosage de l\'antigène prostatique spécifique.',
                'price' => 8000,
            ],
            
            // Examens ophtalmologiques
            [
                'service_id' => $services->where('name', 'Ophtalmologie')->first()->id ?? 1,
                'name' => 'Examen de la vue complet',
                'description' => 'Évaluation complète de la vision et de la santé oculaire.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'Ophtalmologie')->first()->id ?? 1,
                'name' => 'Mesure de la pression intraoculaire',
                'description' => 'Mesure de la pression à l\'intérieur de l\'œil.',
                'price' => 8000,
            ],
            [
                'service_id' => $services->where('name', 'Ophtalmologie')->first()->id ?? 1,
                'name' => 'Angiographie rétinienne',
                'description' => 'Examen des vaisseaux sanguins de la rétine.',
                'price' => 25000,
            ],
            [
                'service_id' => $services->where('name', 'Ophtalmologie')->first()->id ?? 1,
                'name' => 'Tomographie par cohérence optique (OCT)',
                'description' => 'Imagerie haute résolution de la rétine.',
                'price' => 30000,
            ],
            
            // Examens ORL
            [
                'service_id' => $services->where('name', 'ORL')->first()->id ?? 1,
                'name' => 'Audiométrie',
                'description' => 'Test d\'audition complet.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'ORL')->first()->id ?? 1,
                'name' => 'Endoscopie nasale',
                'description' => 'Examen endoscopique des fosses nasales.',
                'price' => 12000,
            ],
            [
                'service_id' => $services->where('name', 'ORL')->first()->id ?? 1,
                'name' => 'Laryngoscopie',
                'description' => 'Examen du larynx et des cordes vocales.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'ORL')->first()->id ?? 1,
                'name' => 'Scanner des sinus',
                'description' => 'Tomodensitométrie des sinus paranasaux.',
                'price' => 30000,
            ],
            
            // Examens psychiatriques
            [
                'service_id' => $services->where('name', 'Psychiatrie')->first()->id ?? 1,
                'name' => 'Évaluation psychologique',
                'description' => 'Tests psychologiques complets pour évaluation mentale.',
                'price' => 20000,
            ],
            [
                'service_id' => $services->where('name', 'Psychiatrie')->first()->id ?? 1,
                'name' => 'Test de QI',
                'description' => 'Évaluation du quotient intellectuel.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'Psychiatrie')->first()->id ?? 1,
                'name' => 'Test de personnalité',
                'description' => 'Évaluation de la personnalité et des traits comportementaux.',
                'price' => 18000,
            ],
            
            // Examens endocrinologiques
            [
                'service_id' => $services->where('name', 'Endocrinologie')->first()->id ?? 1,
                'name' => 'Dosage glycémique',
                'description' => 'Mesure de la glycémie à jeun et post-prandiale.',
                'price' => 5000,
            ],
            [
                'service_id' => $services->where('name', 'Endocrinologie')->first()->id ?? 1,
                'name' => 'Dosage thyroïdien',
                'description' => 'Dosage des hormones thyroïdiennes (TSH, T3, T4).',
                'price' => 12000,
            ],
            
            // Examens gastro-entérologiques
            [
                'service_id' => $services->where('name', 'Gastro-entérologie')->first()->id ?? 1,
                'name' => 'Gastroscopie',
                'description' => 'Examen endoscopique de l\'estomac et du duodénum.',
                'price' => 25000,
            ],
            [
                'service_id' => $services->where('name', 'Gastro-entérologie')->first()->id ?? 1,
                'name' => 'Coloscopie',
                'description' => 'Examen endoscopique du côlon.',
                'price' => 30000,
            ],
            [
                'service_id' => $services->where('name', 'Gastro-entérologie')->first()->id ?? 1,
                'name' => 'Test Helicobacter pylori',
                'description' => 'Détection de la bactérie Helicobacter pylori.',
                'price' => 10000,
            ],
            
            // Examens rhumatologiques
            [
                'service_id' => $services->where('name', 'Rhumatologie')->first()->id ?? 1,
                'name' => 'Radiographie articulaire',
                'description' => 'Radiographie des articulations pour évaluation.',
                'price' => 10000,
            ],
            [
                'service_id' => $services->where('name', 'Rhumatologie')->first()->id ?? 1,
                'name' => 'Échographie articulaire',
                'description' => 'Échographie des articulations pour évaluation.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'Rhumatologie')->first()->id ?? 1,
                'name' => 'Dosage des facteurs rhumatoïdes',
                'description' => 'Analyse sanguine pour détecter les marqueurs de polyarthrite.',
                'price' => 12000,
            ],
            
            // Examens oncologiques
            [
                'service_id' => $services->where('name', 'Oncologie')->first()->id ?? 1,
                'name' => 'Bilan pré-opératoire complet',
                'description' => 'Évaluation complète avant intervention chirurgicale.',
                'price' => 25000,
            ],
            [
                'service_id' => $services->where('name', 'Oncologie')->first()->id ?? 1,
                'name' => 'Marqueurs tumoraux',
                'description' => 'Dosage des marqueurs tumoraux dans le sang.',
                'price' => 15000,
            ],
            
            // Examens néphrologiques
            [
                'service_id' => $services->where('name', 'Néphrologie')->first()->id ?? 1,
                'name' => 'Échographie rénale',
                'description' => 'Échographie des reins pour évaluation.',
                'price' => 15000,
            ],
            [
                'service_id' => $services->where('name', 'Néphrologie')->first()->id ?? 1,
                'name' => 'Biopsie rénale',
                'description' => 'Prélèvement d\'un échantillon de rein pour analyse.',
                'price' => 35000,
            ],
        ];

        foreach ($exams as $exam) {
            Exam::create($exam);
        }
    }
}