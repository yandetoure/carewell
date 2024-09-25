<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MedicalFileMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $medical_file; // Ajoutez cette ligne

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;

        // Récupérer la fiche médicale de l'utilisateur
        $this->medical_file = $user->medicalFile; // Assurez-vous que la relation est bien définie
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Bravo, votre dossier medical a ete cree')
                    ->view('emails.medicalfile') // Assurez-vous de créer la vue
                    ->attach(public_path('images/logo.png')) // Chemin de l'image
                    ->with([
                        'first_name' => $this->user->first_name,
                        'last_name' => $this->user->last_name,
                        'identification_number' => $this->medical_file ? $this->medical_file->identification_number : null, // Vérifiez si la fiche médicale existe
                    ]);
    }
}
