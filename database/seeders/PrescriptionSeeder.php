<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Prescription;
use Illuminate\Database\Seeder;

class PrescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les services pour associer les prescriptions
        $services = Service::all();
        
        $prescriptions = [
            // Soins d'urgence
            [
                'name' => 'Nébulisation',
                'quantity' => 1,
                'price' => 5000,
                'service_id' => $services->where('name', 'Urgences')->first()->id ?? 1,
            ],
            [
                'name' => 'Perfusion intraveineuse',
                'quantity' => 1,
                'price' => 8000,
                'service_id' => $services->where('name', 'Urgences')->first()->id ?? 1,
            ],
            [
                'name' => 'Oxygénothérapie',
                'quantity' => 1,
                'price' => 6000,
                'service_id' => $services->where('name', 'Urgences')->first()->id ?? 1,
            ],
            [
                'name' => 'Sutures d\'urgence',
                'quantity' => 1,
                'price' => 10000,
                'service_id' => $services->where('name', 'Urgences')->first()->id ?? 1,
            ],
            [
                'name' => 'Réanimation cardio-pulmonaire',
                'quantity' => 1,
                'price' => 15000,
                'service_id' => $services->where('name', 'Urgences')->first()->id ?? 1,
            ],
            [
                'name' => 'Intubation',
                'quantity' => 1,
                'price' => 20000,
                'service_id' => $services->where('name', 'Urgences')->first()->id ?? 1,
            ],
            
            // Soins de maternité
            [
                'name' => 'Accouchement normal',
                'quantity' => 1,
                'price' => 25000,
                'service_id' => $services->where('name', 'Maternité')->first()->id ?? 1,
            ],
            [
                'name' => 'Césarienne',
                'quantity' => 1,
                'price' => 80000,
                'service_id' => $services->where('name', 'Maternité')->first()->id ?? 1,
            ],
            [
                'name' => 'Épisiotomie',
                'quantity' => 1,
                'price' => 12000,
                'service_id' => $services->where('name', 'Maternité')->first()->id ?? 1,
            ],
            [
                'name' => 'Monitoring fœtal',
                'quantity' => 1,
                'price' => 8000,
                'service_id' => $services->where('name', 'Maternité')->first()->id ?? 1,
            ],
            [
                'name' => 'Soins post-partum',
                'quantity' => 1,
                'price' => 10000,
                'service_id' => $services->where('name', 'Maternité')->first()->id ?? 1,
            ],
            
            // Soins de chirurgie
            [
                'name' => 'Anesthésie générale',
                'quantity' => 1,
                'price' => 30000,
                'service_id' => $services->where('name', 'Chirurgie')->first()->id ?? 1,
            ],
            [
                'name' => 'Anesthésie locale',
                'quantity' => 1,
                'price' => 15000,
                'service_id' => $services->where('name', 'Chirurgie')->first()->id ?? 1,
            ],
            [
                'name' => 'Chirurgie laparoscopique',
                'quantity' => 1,
                'price' => 100000,
                'service_id' => $services->where('name', 'Chirurgie')->first()->id ?? 1,
            ],
            [
                'name' => 'Chirurgie ouverte',
                'quantity' => 1,
                'price' => 80000,
                'service_id' => $services->where('name', 'Chirurgie')->first()->id ?? 1,
            ],
            [
                'name' => 'Soins post-opératoires',
                'quantity' => 1,
                'price' => 15000,
                'service_id' => $services->where('name', 'Chirurgie')->first()->id ?? 1,
            ],
            
            // Soins cardiologiques
            [
                'name' => 'Cathétérisme cardiaque',
                'quantity' => 1,
                'price' => 120000,
                'service_id' => $services->where('name', 'Cardiologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Angioplastie',
                'quantity' => 1,
                'price' => 150000,
                'service_id' => $services->where('name', 'Cardiologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Pacemaker',
                'quantity' => 1,
                'price' => 200000,
                'service_id' => $services->where('name', 'Cardiologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Holter ECG 24h',
                'quantity' => 1,
                'price' => 25000,
                'service_id' => $services->where('name', 'Cardiologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Test d\'effort',
                'quantity' => 1,
                'price' => 20000,
                'service_id' => $services->where('name', 'Cardiologie')->first()->id ?? 1,
            ],
            
            // Soins pneumologiques
            [
                'name' => 'Ventilation assistée',
                'quantity' => 1,
                'price' => 30000,
                'service_id' => $services->where('name', 'Pneumologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Bronchoscopie',
                'quantity' => 1,
                'price' => 40000,
                'service_id' => $services->where('name', 'Pneumologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Kinésithérapie respiratoire',
                'quantity' => 1,
                'price' => 8000,
                'service_id' => $services->where('name', 'Pneumologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Spirométrie',
                'quantity' => 1,
                'price' => 12000,
                'service_id' => $services->where('name', 'Pneumologie')->first()->id ?? 1,
            ],
            
            // Soins neurologiques
            [
                'name' => 'Électroencéphalogramme',
                'quantity' => 1,
                'price' => 20000,
                'service_id' => $services->where('name', 'Neurologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Électromyographie',
                'quantity' => 1,
                'price' => 25000,
                'service_id' => $services->where('name', 'Neurologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Ponction lombaire',
                'quantity' => 1,
                'price' => 30000,
                'service_id' => $services->where('name', 'Neurologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Rééducation neurologique',
                'quantity' => 1,
                'price' => 15000,
                'service_id' => $services->where('name', 'Neurologie')->first()->id ?? 1,
            ],
            
            // Soins dermatologiques
            [
                'name' => 'Biopsie cutanée',
                'quantity' => 1,
                'price' => 12000,
                'service_id' => $services->where('name', 'Dermatologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Cryothérapie',
                'quantity' => 1,
                'price' => 8000,
                'service_id' => $services->where('name', 'Dermatologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Électrocoagulation',
                'quantity' => 1,
                'price' => 10000,
                'service_id' => $services->where('name', 'Dermatologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Photothérapie',
                'quantity' => 1,
                'price' => 15000,
                'service_id' => $services->where('name', 'Dermatologie')->first()->id ?? 1,
            ],
            
            // Soins pédiatriques
            [
                'name' => 'Soins intensifs néonatals',
                'quantity' => 1,
                'price' => 50000,
                'service_id' => $services->where('name', 'Pédiatrie')->first()->id ?? 1,
            ],
            [
                'name' => 'Vaccination',
                'quantity' => 1,
                'price' => 5000,
                'service_id' => $services->where('name', 'Pédiatrie')->first()->id ?? 1,
            ],
            [
                'name' => 'Soins de croissance',
                'quantity' => 1,
                'price' => 8000,
                'service_id' => $services->where('name', 'Pédiatrie')->first()->id ?? 1,
            ],
            
            // Soins gynécologiques
            [
                'name' => 'Hystéroscopie',
                'quantity' => 1,
                'price' => 35000,
                'service_id' => $services->where('name', 'Gynécologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Colposcopie',
                'quantity' => 1,
                'price' => 20000,
                'service_id' => $services->where('name', 'Gynécologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Pose de stérilet',
                'quantity' => 1,
                'price' => 15000,
                'service_id' => $services->where('name', 'Gynécologie')->first()->id ?? 1,
            ],
            
            // Soins urologiques
            [
                'name' => 'Cystoscopie',
                'quantity' => 1,
                'price' => 25000,
                'service_id' => $services->where('name', 'Urologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Lithotripsie',
                'quantity' => 1,
                'price' => 40000,
                'service_id' => $services->where('name', 'Urologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Biopsie prostatique',
                'quantity' => 1,
                'price' => 30000,
                'service_id' => $services->where('name', 'Urologie')->first()->id ?? 1,
            ],
            
            // Soins ophtalmologiques
            [
                'name' => 'Chirurgie de la cataracte',
                'quantity' => 1,
                'price' => 80000,
                'service_id' => $services->where('name', 'Ophtalmologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Laser rétinien',
                'quantity' => 1,
                'price' => 50000,
                'service_id' => $services->where('name', 'Ophtalmologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Vitrectomie',
                'quantity' => 1,
                'price' => 100000,
                'service_id' => $services->where('name', 'Ophtalmologie')->first()->id ?? 1,
            ],
            
            // Soins ORL
            [
                'name' => 'Ablation des amygdales',
                'quantity' => 1,
                'price' => 40000,
                'service_id' => $services->where('name', 'ORL')->first()->id ?? 1,
            ],
            [
                'name' => 'Chirurgie des sinus',
                'quantity' => 1,
                'price' => 50000,
                'service_id' => $services->where('name', 'ORL')->first()->id ?? 1,
            ],
            [
                'name' => 'Pose d\'aérateur tympanique',
                'quantity' => 1,
                'price' => 25000,
                'service_id' => $services->where('name', 'ORL')->first()->id ?? 1,
            ],
            
            // Soins endocrinologiques
            [
                'name' => 'Pompe à insuline',
                'quantity' => 1,
                'price' => 150000,
                'service_id' => $services->where('name', 'Endocrinologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Surveillance glycémique continue',
                'quantity' => 1,
                'price' => 80000,
                'service_id' => $services->where('name', 'Endocrinologie')->first()->id ?? 1,
            ],
            
            // Soins gastro-entérologiques
            [
                'name' => 'Gastroscopie',
                'quantity' => 1,
                'price' => 30000,
                'service_id' => $services->where('name', 'Gastro-entérologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Coloscopie',
                'quantity' => 1,
                'price' => 35000,
                'service_id' => $services->where('name', 'Gastro-entérologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Polypectomie',
                'quantity' => 1,
                'price' => 25000,
                'service_id' => $services->where('name', 'Gastro-entérologie')->first()->id ?? 1,
            ],
            
            // Soins rhumatologiques
            [
                'name' => 'Infiltration articulaire',
                'quantity' => 1,
                'price' => 15000,
                'service_id' => $services->where('name', 'Rhumatologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Kinésithérapie',
                'quantity' => 1,
                'price' => 8000,
                'service_id' => $services->where('name', 'Rhumatologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Ostéopathie',
                'quantity' => 1,
                'price' => 12000,
                'service_id' => $services->where('name', 'Rhumatologie')->first()->id ?? 1,
            ],
            
            // Soins psychiatriques
            [
                'name' => 'Thérapie cognitivo-comportementale',
                'quantity' => 1,
                'price' => 20000,
                'service_id' => $services->where('name', 'Psychiatrie')->first()->id ?? 1,
            ],
            [
                'name' => 'Électroconvulsivothérapie',
                'quantity' => 1,
                'price' => 50000,
                'service_id' => $services->where('name', 'Psychiatrie')->first()->id ?? 1,
            ],
            [
                'name' => 'Hospitalisation psychiatrique',
                'quantity' => 1,
                'price' => 30000,
                'service_id' => $services->where('name', 'Psychiatrie')->first()->id ?? 1,
            ],
            
            // Soins oncologiques
            [
                'name' => 'Chimiothérapie',
                'quantity' => 1,
                'price' => 100000,
                'service_id' => $services->where('name', 'Oncologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Radiothérapie',
                'quantity' => 1,
                'price' => 80000,
                'service_id' => $services->where('name', 'Oncologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Immunothérapie',
                'quantity' => 1,
                'price' => 150000,
                'service_id' => $services->where('name', 'Oncologie')->first()->id ?? 1,
            ],
            
            // Soins néphrologiques
            [
                'name' => 'Dialyse hémodialyse',
                'quantity' => 1,
                'price' => 50000,
                'service_id' => $services->where('name', 'Néphrologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Dialyse péritonéale',
                'quantity' => 1,
                'price' => 40000,
                'service_id' => $services->where('name', 'Néphrologie')->first()->id ?? 1,
            ],
            [
                'name' => 'Biopsie rénale',
                'quantity' => 1,
                'price' => 35000,
                'service_id' => $services->where('name', 'Néphrologie')->first()->id ?? 1,
            ],
        ];

        foreach ($prescriptions as $prescription) {
            Prescription::create($prescription);
        }
    }
}
