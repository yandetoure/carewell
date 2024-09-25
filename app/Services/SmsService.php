<?php

namespace App\Services;

use Twilio\Rest\Client;
use App\Models\User;

class SmsService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
    }

    /**
     * Envoie un message de bienvenue
     */
    public function sendWelcomeMessage(User $user)
    {
        $message = "Bienvenue " . $user->first_name . " ! Merci de vous être inscrit sur notre plateforme.";
        $this->sendSms($user->phone_number, $message);
    }

    /**
     * Envoie un message de création de dossier
     */
    public function sendMedicalFileCreatedMessage(User $user)
    {
        $message = "Votre dossier médical a été créé avec succès. Numéro de dossier : " . $user->medical_file->identification_number;
        $this->sendSms($user->phone_number, $message);
    }

    /**
     * Envoie un message pour la prise de rendez-vous
     */
    public function sendAppointmentMessage(User $user, $appointmentDate)
    {
        $message = "Votre rendez-vous est prévu pour le " . $appointmentDate . ". Merci de le confirmer.";
        $this->sendSms($user->phone_number, $message);
    }

    /**
     * Envoie un message pour une nouvelle prescription
     */
    public function sendPrescriptionMessage(User $user)
    {
        $message = "Votre nouvelle prescription est disponible dans votre espace personnel.";
        $this->sendSms($user->phone_number, $message);
    }

    /**
     * Envoie un message pour un examen
     */
    public function sendExamMessage(User $user)
    {
        $message = "Les résultats de votre examen sont disponibles. Consultez-les dans votre dossier médical.";
        $this->sendSms($user->phone_number, $message);
    }

    /**
     * Méthode générique pour envoyer un SMS
     */
    private function sendSms($phoneNumber, $message)
    {
        $this->twilio->messages->create(
            $phoneNumber,
            [
                'from' => config('services.twilio.from'),
                'body' => $message
            ]
        );
    }
}
