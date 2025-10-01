<?php declare(strict_types=1); 

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medicament;

class MedicamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicaments = [
            [
                'nom' => 'Paracétamol',
                'forme' => 'Comprimé',
                'dosage' => '500mg',
                'description' => 'Antalgique et antipyrétique',
                'laboratoire' => 'Sanofi',
                'prix' => 2.50,
                'disponible' => true,
                'quantite_stock' => 250,
                'date_expiration' => now()->addMonths(18),
            ],
            [
                'nom' => 'Ibuprofène',
                'forme' => 'Comprimé',
                'dosage' => '400mg',
                'description' => 'Anti-inflammatoire non stéroïdien',
                'laboratoire' => 'Bayer',
                'prix' => 3.20,
                'disponible' => true,
                'quantite_stock' => 180,
                'date_expiration' => now()->addMonths(14),
            ],
            [
                'nom' => 'Amoxicilline',
                'forme' => 'Gélule',
                'dosage' => '1g',
                'description' => 'Antibiotique de la famille des pénicillines',
                'laboratoire' => 'GSK',
                'prix' => 8.90,
                'disponible' => true,
                'quantite_stock' => 120,
                'date_expiration' => now()->addMonths(12),
            ],
            [
                'nom' => 'Doliprane',
                'forme' => 'Sirop',
                'dosage' => '100ml',
                'description' => 'Paracétamol en sirop pour enfants',
                'laboratoire' => 'Sanofi',
                'prix' => 4.50,
                'disponible' => true,
                'quantite_stock' => 85,
                'date_expiration' => now()->addMonths(10),
            ],
            [
                'nom' => 'Aspirine',
                'forme' => 'Comprimé',
                'dosage' => '500mg',
                'description' => 'Anti-inflammatoire et antalgique',
                'laboratoire' => 'Bayer',
                'prix' => 2.80,
                'disponible' => true,
                'quantite_stock' => 8,
                'date_expiration' => now()->addDays(25),
            ],
            [
                'nom' => 'Ventoline',
                'forme' => 'Inhalateur',
                'dosage' => '100mcg',
                'description' => 'Bronchodilatateur pour l\'asthme',
                'laboratoire' => 'GSK',
                'prix' => 12.50,
                'disponible' => true,
                'quantite_stock' => 45,
                'date_expiration' => now()->addMonths(15),
            ],
            [
                'nom' => 'Oméprazole',
                'forme' => 'Gélule',
                'dosage' => '20mg',
                'description' => 'Inhibiteur de la pompe à protons',
                'laboratoire' => 'AstraZeneca',
                'prix' => 6.80,
                'disponible' => true,
                'quantite_stock' => 5,
                'date_expiration' => now()->addDays(20),
            ],
            [
                'nom' => 'Metformine',
                'forme' => 'Comprimé',
                'dosage' => '850mg',
                'description' => 'Antidiabétique oral',
                'laboratoire' => 'Merck',
                'prix' => 5.20,
                'disponible' => true,
                'quantite_stock' => 150,
                'date_expiration' => now()->addMonths(20),
            ],
            [
                'nom' => 'Atorvastatine',
                'forme' => 'Comprimé',
                'dosage' => '20mg',
                'description' => 'Hypolipémiant de la famille des statines',
                'laboratoire' => 'Pfizer',
                'prix' => 9.50,
                'disponible' => true,
                'quantite_stock' => 3,
                'date_expiration' => now()->addMonths(8),
            ],
            [
                'nom' => 'Lisinopril',
                'forme' => 'Comprimé',
                'dosage' => '10mg',
                'description' => 'Inhibiteur de l\'enzyme de conversion',
                'laboratoire' => 'Merck',
                'prix' => 7.30,
                'disponible' => true,
                'quantite_stock' => 200,
                'date_expiration' => now()->addMonths(16),
            ],
        ];

        foreach ($medicaments as $medicament) {
            Medicament::create($medicament);
        }
    }
}