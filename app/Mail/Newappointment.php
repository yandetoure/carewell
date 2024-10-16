<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Newappointment extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $appointment_id; 

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Bonjour, votre rendez-vous a été enregistré')
                    ->view('emails.appointment') 
                    ->attach(public_path('images/logo.png')) 
                    ->with([
                        'first_name' => $this->user->first_name,
                        'last_name' => $this->user->last_name,
                        // 'appointment_id' => $this->appointment->id,
                        // 'appointmentservice' => $this->appointment->service->name,
                    ]);
    }
}
