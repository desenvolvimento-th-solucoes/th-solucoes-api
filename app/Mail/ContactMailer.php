<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMailer extends Mailable
{
    use Queueable, SerializesModels;

    private string $body;
    private string $name;
    private string $email;

    public function __construct(string $name, string $email, string $body){
        $this->name = $name;
        $this->email = $email;
        $this->body = $body;
    }

    public function build() {
        $this->view("contact_email", [
            "body" => $this->body,
            "name" => $this->name,
            "email" => $this->email
        ])
        ->subject("E-mail de Contato")
        ->from($this->email, $this->name);
    }
}
