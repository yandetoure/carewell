<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\Service;
use App\Models\MedicalFile;
use Illuminate\Database\Seeder;

class BedSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = Service::all();
        
        if ($services->isEmpty()) {
            $this->command->warn('Aucun service trouvé. Veuillez exécuter ServiceSeeder d\'abord.');
            return;
        }

        $bedTypes = ['standard', 'premium', 'vip'];
        $statuses = ['libre', 'libre', 'libre', 'occupe', 'maintenance'];
        
        // Créer 50 lits au total
        for ($i = 1; $i <= 50; $i++) {
            $roomNumber = 'R' . str_pad((string)ceil($i / 2), 3, '0', STR_PAD_LEFT);
            $bedNumber = 'L' . str_pad((string)$i, 3, '0', STR_PAD_LEFT);
            $status = $statuses[array_rand($statuses)];
            $service = $services->random();
            
            $bed = Bed::create([
                'bed_number' => $bedNumber,
                'room_number' => $roomNumber,
                'service_id' => $service->id,
                'status' => $status,
                'bed_type' => $bedTypes[array_rand($bedTypes)],
            ]);

            // Si le lit est occupé, assigner un patient
            if ($status === 'occupe') {
                $medicalFiles = MedicalFile::all();
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

        $totalBeds = Bed::count();
        $this->command->info("{$totalBeds} lits créés avec succès !");
    }
}
