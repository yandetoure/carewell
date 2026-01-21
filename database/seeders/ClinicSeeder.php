<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clinics = [
            [
                'name' => 'Clinique CareWell Dakar',
                'email' => 'contact@carewell-dakar.sn',
                'phone_number' => '+221338212345',
                'address' => 'Avenue Cheikh Anta Diop, Point E',
                'city' => 'Dakar',
                'country' => 'Sénégal',
                'description' => 'Clinique principale de CareWell à Dakar offrant des services médicaux complets.',
                'is_active' => true,
            ],
            [
                'name' => 'Clinique CareWell Almadies',
                'email' => 'contact@carewell-almadies.sn',
                'phone_number' => '+221338223456',
                'address' => 'Route de l\'Aéroport, Almadies',
                'city' => 'Dakar',
                'country' => 'Sénégal',
                'description' => 'Clinique secondaire de CareWell dans le quartier d\'Almadies.',
                'is_active' => true,
            ],
            [
                'name' => 'Clinique CareWell Mermoz',
                'email' => 'contact@carewell-mermoz.sn',
                'phone_number' => '+221338234567',
                'address' => 'Boulevard Général de Gaulle, Mermoz',
                'city' => 'Dakar',
                'country' => 'Sénégal',
                'description' => 'Clinique de quartier offrant des services de proximité.',
                'is_active' => true,
            ],
        ];

        foreach ($clinics as $clinicData) {
            Clinic::firstOrCreate(
                ['email' => $clinicData['email']],
                $clinicData
            );
        }
    }
}
