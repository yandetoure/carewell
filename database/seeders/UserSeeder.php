<?php declare(strict_types=1); 

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Création du Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@carewell.sn'],
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'password' => Hash::make('password'),
                'adress' => 'Siège Social',
                'phone_number' => '+221338200000',
                'day_of_birth' => '1980-01-01',
                'status' => true,
            ]
        );
        if (!$superAdmin->hasRole('Super Admin')) {
            $superAdmin->assignRole('Super Admin');
        }

        // Création de l'admin
        $admin = User::firstOrCreate(
            ['email' => 'ndeye@gmail.com'],
            [
                'first_name' => 'Biteye',
                'last_name' => 'Sow',
                'password' => Hash::make('password'), 
                'adress' => 'Point E',
                'phone_number' => '+221774344454',
                'day_of_birth' => '1990-01-01',
                'status' => true,
            ]
        );
        if (!$admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        } 

        // Création de médecins
        $doctors = [
            [
                'first_name' => 'Amadou',
                'last_name' => 'Diallo',
                'email' => 'amadou.diallo@carewell.com',
                'adress' => 'Mermoz',
                'phone_number' => '+221775551111',
                'day_of_birth' => '1975-03-15',
                'specialite' => 'Cardiologie',
                'numero_ordre' => 'CM12345',
            ],
            [
                'first_name' => 'Fatou',
                'last_name' => 'Ndiaye',
                'email' => 'fatou.ndiaye@carewell.com',
                'adress' => 'Almadies',
                'phone_number' => '+221775552222',
                'day_of_birth' => '1980-07-22',
                'specialite' => 'Pédiatrie',
                'numero_ordre' => 'CM12346',
            ],
            [
                'first_name' => 'Moussa',
                'last_name' => 'Ba',
                'email' => 'moussa.ba@carewell.com',
                'adress' => 'Ouakam',
                'phone_number' => '+221775553333',
                'day_of_birth' => '1978-11-08',
                'specialite' => 'Médecine générale',
                'numero_ordre' => 'CM12347',
            ],
            [
                'first_name' => 'Aissatou',
                'last_name' => 'Seck',
                'email' => 'aissatou.seck@carewell.com',
                'adress' => 'Fann',
                'phone_number' => '+221775554444',
                'day_of_birth' => '1982-05-12',
                'specialite' => 'Gynécologie',
                'numero_ordre' => 'CM12348',
            ],
        ];

        foreach ($doctors as $doctorData) {
            $doctor = User::firstOrCreate(
                ['email' => $doctorData['email']],
                [
                    'first_name' => $doctorData['first_name'],
                    'last_name' => $doctorData['last_name'],
                    'password' => Hash::make('password'),
                    'adress' => $doctorData['adress'],
                    'phone_number' => $doctorData['phone_number'],
                    'day_of_birth' => $doctorData['day_of_birth'],
                    'specialite' => $doctorData['specialite'],
                    'numero_ordre' => $doctorData['numero_ordre'],
                    'status' => true,
                ]
            );
            if (!$doctor->hasRole('Doctor')) {
                $doctor->assignRole('Doctor');
            }
        }

        // Création d'infirmiers
        $nurses = [
            [
                'first_name' => 'Jean',
                'last_name' => 'Gomez',
                'email' => 'jean.gomez@carewell.com',
                'adress' => 'Yoff',
                'phone_number' => '+221778881111',
                'day_of_birth' => '1990-01-01',
            ],
            [
                'first_name' => 'Sophie',
                'last_name' => 'Sow',
                'email' => 'sophie.sow@carewell.com',
                'adress' => 'Plateau',
                'phone_number' => '+221778882222',
                'day_of_birth' => '1992-01-01',
            ],
        ];

        foreach ($nurses as $nurseData) {
            $nurse = User::firstOrCreate(
                ['email' => $nurseData['email']],
                [
                    'first_name' => $nurseData['first_name'],
                    'last_name' => $nurseData['last_name'],
                    'password' => Hash::make('password'),
                    'adress' => $nurseData['adress'],
                    'phone_number' => $nurseData['phone_number'],
                    'day_of_birth' => $nurseData['day_of_birth'],
                    'status' => true,
                ]
            );
            if (!$nurse->hasRole('Nurse')) {
                $nurse->assignRole('Nurse');
            }
        }

        // Création de patients
        $patients = [
            [
                'first_name' => 'Ousmane',
                'last_name' => 'Fall',
                'email' => 'ousmane.fall@example.com',
                'phone_number' => '+221776661111',
                'day_of_birth' => '1995-08-20',
                'adress' => 'HLM Grand Yoff',
            ],
            [
                'first_name' => 'Mariama',
                'last_name' => 'Diop',
                'email' => 'mariama.diop@example.com',
                'phone_number' => '+221776662222',
                'day_of_birth' => '1988-04-15',
                'adress' => 'Parcelles Assainies',
            ],
            [
                'first_name' => 'Ibrahima',
                'last_name' => 'Sarr',
                'email' => 'ibrahima.sarr@example.com',
                'phone_number' => '+221776663333',
                'day_of_birth' => '1992-12-03',
                'adress' => 'Médina',
            ],
            [
                'first_name' => 'Awa',
                'last_name' => 'Thiam',
                'email' => 'awa.thiam@example.com',
                'phone_number' => '+221776664444',
                'day_of_birth' => '1985-09-28',
                'adress' => 'Liberté 6',
            ],
            [
                'first_name' => 'Mamadou',
                'last_name' => 'Kane',
                'email' => 'mamadou.kane@example.com',
                'phone_number' => '+221776665555',
                'day_of_birth' => '1990-02-14',
                'adress' => 'Plateau',
            ],
            [
                'first_name' => 'Khady',
                'last_name' => 'Gueye',
                'email' => 'khady.gueye@example.com',
                'phone_number' => '+221776666666',
                'day_of_birth' => '1993-06-07',
                'adress' => 'Sacré-Coeur',
            ],
            [
                'first_name' => 'Modou',
                'last_name' => 'Cissé',
                'email' => 'modou.cisse@example.com',
                'phone_number' => '+221776667777',
                'day_of_birth' => '1987-10-19',
                'adress' => 'Grand Dakar',
            ],
            [
                'first_name' => 'Rokhaya',
                'last_name' => 'Sy',
                'email' => 'rokhaya.sy@example.com',
                'phone_number' => '+221776668888',
                'day_of_birth' => '1991-03-25',
                'adress' => 'Yoff',
            ],
        ];

        foreach ($patients as $patientData) {
            $patient = User::firstOrCreate(
                ['email' => $patientData['email']],
                [
                    'first_name' => $patientData['first_name'],
                    'last_name' => $patientData['last_name'],
                    'password' => Hash::make('password'),
                    'adress' => $patientData['adress'],
                    'phone_number' => $patientData['phone_number'],
                    'day_of_birth' => $patientData['day_of_birth'],
                    'status' => true,
                ]
            );
            if (!$patient->hasRole('Patient')) {
                $patient->assignRole('Patient');
                $patient->createMedicalFile();
            }
        }
    }     
}