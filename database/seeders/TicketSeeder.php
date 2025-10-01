<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les rendez-vous
        $appointments = Appointment::with(['user', 'doctor'])->get();
        
        if ($appointments->isEmpty()) {
            $this->command->warn('Aucun rendez-vous trouvé. Veuillez d\'abord créer des rendez-vous.');
            return;
        }
        
        // Créer un ticket pour chaque rendez-vous
        foreach ($appointments as $appointment) {
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
        
        $this->command->info(count($appointments) . ' tickets créés avec succès !');
    }
}

