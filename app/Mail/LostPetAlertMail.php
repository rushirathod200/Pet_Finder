<?php

namespace App\Mail;

use App\Models\LostPetRequest;
use App\Models\usersdata;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LostPetAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LostPetRequest $lostPetRequest,
        public usersdata $requester
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lost Pet Alert in '.$this->lostPetRequest->city,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lost_pet_alert',
        );
    }
}
