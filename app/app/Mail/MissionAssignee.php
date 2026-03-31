<?php

namespace App\Mail;

use App\Models\Mission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MissionAssignee extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Mission $mission,
        public User $technicien
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle mission assignée : ' . $this->mission->titre,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.mission-assignee',
        );
    }
}
