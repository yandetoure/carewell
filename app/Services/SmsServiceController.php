<?php

namespace App\Services;

use Twilio\Rest\Client;
use App\Models\User;

class SmsService
{
    protected $client;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');

        if (empty($sid) || empty($token)) {
            throw new \Exception('Twilio SID or Auth Token is not set.');
        }

        $this->client = new Client($sid, $token);
    }
    /**
     * Envoie un message de bienvenue
     */
    public function sendWelcomeMessage(User $user)
    {
        $message = "Bienvenue " . $user->first_name . " ! Merci de vous être inscrit sur notre plateforme CareWell. Rendez-vous sur notre application pour suivre votre etat de sante en temps reel.";
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
