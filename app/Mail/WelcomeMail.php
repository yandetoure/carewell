<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $email;
    public $password;
    

    /**
     * Create a new message instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->email = $email;
        $this->password = $password;
        
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Bienvenue sur notre plateforme !')
                    ->view('emails.welcome')
                    ->attach(public_path('images/logo.png'))
                    ->with([
                        'first_name' => $this->user->first_name,
                        'last_name' => $this->user->last_name,
                        'email' => $this->email,
                        'password' => $this->password,
                    ]);
    }
}
