<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CertificateAwardedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $event;
    public $certificates;

    public function __construct(Student $student, Event $event, array $certificates)
    {
        $this->student = $student;
        $this->event = $event;
        $this->certificates = $certificates;
    }

    public function envelope(): Envelope
    {
        $subject = 'Certificate Awarded - ' . $this->event->name;
        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.certificate-awarded',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
