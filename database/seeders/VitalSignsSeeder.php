<?php declare(strict_types=1); 

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VitalSign;
use App\Models\MedicalFile;
use App\Models\User;
use Carbon\Carbon;

class VitalSignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some medical files (patients)
        $medicalFiles = MedicalFile::with('user')->take(10)->get();
        
        if ($medicalFiles->isEmpty()) {
            $this->command->info('No medical files found. Please run other seeders first.');
            return;
        }

        // Get nurses
        $nurses = User::whereHas('roles', function($query) {
            $query->where('name', 'Nurse');
        })->get();

        if ($nurses->isEmpty()) {
            $this->command->info('No nurses found. Please create nurse users first.');
            return;
        }

        $nurseIds = $nurses->pluck('id')->toArray();

        // Create normal vital signs
        foreach ($medicalFiles->take(5) as $medicalFile) {
            VitalSign::create([
                'medical_file_id' => $medicalFile->id,
                'nurse_id' => $nurseIds[array_rand($nurseIds)],
                'blood_pressure_systolic' => rand(110, 130),
                'blood_pressure_diastolic' => rand(70, 85),
                'heart_rate' => rand(60, 90),
                'temperature' => rand(360, 375) / 10, // 36.0 to 37.5
                'oxygen_saturation' => rand(96, 100),
                'respiratory_rate' => rand(12, 20),
                'weight' => rand(60, 90),
                'height' => rand(160, 190),
                'notes' => 'Signes vitaux normaux',
                'recorded_at' => Carbon::now()->subHours(rand(1, 6))
            ]);
        }

        // Create abnormal vital signs (alerts)
        foreach ($medicalFiles->skip(5) as $medicalFile) {
            $abnormalType = rand(1, 4);
            
            switch ($abnormalType) {
                case 1: // High temperature
                    VitalSign::create([
                        'medical_file_id' => $medicalFile->id,
                        'nurse_id' => $nurseIds[array_rand($nurseIds)],
                        'blood_pressure_systolic' => rand(110, 130),
                        'blood_pressure_diastolic' => rand(70, 85),
                        'heart_rate' => rand(60, 90),
                        'temperature' => rand(385, 400) / 10, // 38.5 to 40.0 (fever)
                        'oxygen_saturation' => rand(96, 100),
                        'respiratory_rate' => rand(12, 20),
                        'weight' => rand(60, 90),
                        'height' => rand(160, 190),
                        'notes' => 'Fièvre détectée',
                        'recorded_at' => Carbon::now()->subHours(rand(1, 3))
                    ]);
                    break;
                    
                case 2: // High heart rate
                    VitalSign::create([
                        'medical_file_id' => $medicalFile->id,
                        'nurse_id' => $nurseIds[array_rand($nurseIds)],
                        'blood_pressure_systolic' => rand(110, 130),
                        'blood_pressure_diastolic' => rand(70, 85),
                        'heart_rate' => rand(101, 120), // Tachycardia
                        'temperature' => rand(360, 375) / 10,
                        'oxygen_saturation' => rand(96, 100),
                        'respiratory_rate' => rand(12, 20),
                        'weight' => rand(60, 90),
                        'height' => rand(160, 190),
                        'notes' => 'Tachycardie',
                        'recorded_at' => Carbon::now()->subHours(rand(1, 4))
                    ]);
                    break;
                    
                case 3: // Low oxygen saturation
                    VitalSign::create([
                        'medical_file_id' => $medicalFile->id,
                        'nurse_id' => $nurseIds[array_rand($nurseIds)],
                        'blood_pressure_systolic' => rand(110, 130),
                        'blood_pressure_diastolic' => rand(70, 85),
                        'heart_rate' => rand(60, 90),
                        'temperature' => rand(360, 375) / 10,
                        'oxygen_saturation' => rand(88, 94), // Low oxygen
                        'respiratory_rate' => rand(12, 20),
                        'weight' => rand(60, 90),
                        'height' => rand(160, 190),
                        'notes' => 'Saturation en oxygène faible',
                        'recorded_at' => Carbon::now()->subHours(rand(1, 5))
                    ]);
                    break;
                    
                case 4: // High blood pressure
                    VitalSign::create([
                        'medical_file_id' => $medicalFile->id,
                        'nurse_id' => $nurseIds[array_rand($nurseIds)],
                        'blood_pressure_systolic' => rand(141, 160), // High BP
                        'blood_pressure_diastolic' => rand(90, 100),
                        'heart_rate' => rand(60, 90),
                        'temperature' => rand(360, 375) / 10,
                        'oxygen_saturation' => rand(96, 100),
                        'respiratory_rate' => rand(12, 20),
                        'weight' => rand(60, 90),
                        'height' => rand(160, 190),
                        'notes' => 'Hypertension artérielle',
                        'recorded_at' => Carbon::now()->subHours(rand(1, 2))
                    ]);
                    break;
            }
        }

        $this->command->info('Vital signs seeded successfully!');
    }
}
