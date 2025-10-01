<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\User;
use App\Models\Medicament;
use App\Models\Ordonnance;
use Illuminate\Database\Seeder;

class OrdonnanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer des médecins et des patients
        $doctors = User::role('Doctor')->get();
        $patients = User::role('Patient')->get();
        $medicaments = Medicament::all();
        
        if ($doctors->isEmpty() || $patients->isEmpty() || $medicaments->isEmpty()) {
            $this->command->warn('Assurez-vous que les utilisateurs (médecins et patients) et les médicaments sont déjà créés.');
            return;
        }
        
        // Créer 20 ordonnances
        for ($i = 1; $i <= 20; $i++) {
            $patient = $patients->random();
            $doctor = $doctors->random();
            
            $ordonnance = Ordonnance::create([
                'patient_id' => $patient->id,
                'medecin_id' => $doctor->id,
                'patient_first_name' => $patient->first_name,
                'patient_last_name' => $patient->last_name,
                'medecin_first_name' => $doctor->first_name,
                'medecin_last_name' => $doctor->last_name,
                'date_prescription' => now()->subDays(rand(1, 60)),
                'date_validite' => now()->addDays(rand(30, 90)),
                'statut' => collect(['active', 'active', 'active', 'expiree', 'annulee'])->random(),
                'instructions' => collect([
                    'Prendre avec de la nourriture',
                    'Prendre à jeun le matin',
                    'Ne pas conduire après la prise',
                    'Éviter l\'alcool pendant le traitement',
                    'Boire beaucoup d\'eau',
                    'Prendre 30 minutes avant les repas',
                    'Conserver au réfrigérateur',
                    'Ne pas arrêter le traitement sans avis médical',
                ])->random(),
                'notes' => collect([
                    'Patient allergique à la pénicilline',
                    'Suivi nécessaire après 15 jours',
                    'Renouvellement possible',
                    'Traitement de courte durée',
                    null,
                    null,
                ])->random(),
            ]);
            
            // Attacher 1 à 4 médicaments aléatoires
            $selectedMedicaments = $medicaments->random(rand(1, 4));
            
            foreach ($selectedMedicaments as $medicament) {
                $ordonnance->medicaments()->attach($medicament->id, [
                    'quantite' => rand(1, 3),
                    'posologie' => collect([
                        '1 comprimé matin et soir',
                        '2 comprimés 3 fois par jour',
                        '1 comprimé le soir au coucher',
                        '1 comprimé toutes les 6 heures',
                        '2 comprimés en cas de douleur',
                        '1 comprimé le matin à jeun',
                        '1/2 comprimé matin et soir',
                    ])->random(),
                    'duree_jours' => rand(5, 30),
                    'instructions_speciales' => collect([
                        'Avec un grand verre d\'eau',
                        'Pendant les repas',
                        'En cas de douleur',
                        'Avant de dormir',
                        'À jeun',
                        null,
                    ])->random(),
                ]);
            }
        }
        
        $this->command->info('20 ordonnances créées avec succès !');
    }
}

