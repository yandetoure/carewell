<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\Service;
use App\Models\MedicalFile;
use App\Models\Clinic;
use Illuminate\Database\Seeder;

class BedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinics = Clinic::all();
        
        if ($clinics->isEmpty()) {
            $this->command->warn('Aucune clinique trouvée. Veuillez exécuter ClinicSeeder d\'abord.');
            return;
        }

        // Créer des lits pour chaque clinique
        $bedTypes = ['standard', 'premium', 'vip'];
        $statuses = ['libre', 'libre', 'libre', 'occupe', 'maintenance'];
        
        foreach ($clinics as $clinic) {
            $services = Service::where('clinic_id', $clinic->id)->get();
            
            if ($services->isEmpty()) {
                continue;
            }
            
            // Créer 20 lits par clinique
            for ($i = 1; $i <= 20; $i++) {
                $roomNumber = 'R' . str_pad((string)ceil($i / 2), 3, '0', STR_PAD_LEFT); // 2 lits par chambre
                // Inclure l'ID de la clinique dans le numéro du lit pour garantir l'unicité
                $bedNumber = 'C' . str_pad((string)$clinic->id, 2, '0', STR_PAD_LEFT) . '-L' . str_pad((string)$i, 3, '0', STR_PAD_LEFT);
                $status = $statuses[array_rand($statuses)];
                
                $bed = Bed::create([
                    'bed_number' => $bedNumber,
                    'room_number' => $roomNumber,
                    'service_id' => $services->random()->id,
                    'status' => $status,
                    'bed_type' => $bedTypes[array_rand($bedTypes)],
                    'clinic_id' => $clinic->id,
                ]);

                // Si le lit est occupé, assigner un patient de la même clinique
                if ($status === 'occupe') {
                    $medicalFiles = MedicalFile::where('clinic_id', $clinic->id)->get();
                    if ($medicalFiles->isNotEmpty()) {
                        $bed->admitPatient(
                            $medicalFiles->random()->id,
                            now()->subDays(rand(1, 30)),
                            now()->addDays(rand(1, 15)),
                            'Admission pour traitement',
                            null
                        );
                    }
                }
            }
        }

        $totalBeds = Bed::count();
        $this->command->info("{$totalBeds} lits créés avec succès pour " . $clinics->count() . " clinique(s) !");
    }
}

