<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = User::role('Doctor')->get();
        $patients = User::role('Patient')->get();
        $services = Service::all();
        
        if ($doctors->isEmpty() || $patients->isEmpty() || $services->isEmpty()) {
            $this->command->warn('Assurez-vous que les utilisateurs et services sont déjà créés.');
            return;
        }
        
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        
        // Créer 30 rendez-vous
        for ($i = 1; $i <= 30; $i++) {
            $patient = $patients->random();
            $doctor = $doctors->random();
            $service = $services->random();
            $status = $statuses[array_rand($statuses)];
            
            // Date de rendez-vous aléatoire entre -30 jours et +30 jours
            $daysOffset = rand(-30, 30);
            $appointmentDate = now()->addDays($daysOffset)->format('Y-m-d');
            
            // Heure de rendez-vous aléatoire entre 8h et 17h
            $hour = rand(8, 17);
            $minute = rand(0, 1) * 30; // 00 ou 30
            $appointmentTime = sprintf('%02d:%02d:00', $hour, $minute);
            
            $appointment = Appointment::create([
                'user_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'service_id' => $service->id,
                'appointment_date' => $appointmentDate,
                'appointment_time' => $appointmentTime,
                'status' => $status,
                'reason' => collect([
                    'Consultation générale',
                    'Suivi médical',
                    'Examen de routine',
                    'Contrôle post-opératoire',
                    'Problème de santé',
                    'Vaccination',
                    'Consultation spécialisée',
                ])->random(),
                'symptoms' => collect([
                    'Fièvre et maux de tête',
                    'Douleurs abdominales',
                    'Toux persistante',
                    'Fatigue chronique',
                    'Douleurs articulaires',
                    null,
                ])->random(),
                'is_visited' => $status === 'completed',
                'is_urgent' => rand(1, 100) <= 10, // 10% de rendez-vous urgents
                'price' => $service->price,
            ]);
            
            // Créer un ticket pour chaque rendez-vous
            // 70% des tickets sont payés, 30% non payés
            $isPaid = rand(1, 100) <= 70;
            
            Ticket::create([
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->user_id,
                'doctor_id' => $appointment->doctor_id,
                'prescription_id' => null,
                'exam_id' => null,
                'is_paid' => $isPaid,
            ]);
        }
        
        $this->command->info('30 rendez-vous créés avec leurs tickets !');
    }
}

