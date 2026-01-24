<?php

namespace App\Mail;

use App\Models\LostAndFound;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class LostAndFoundResolvedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $report;

    public function __construct(LostAndFound $report)
    {
        $this->report = $report;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('chef.ratatuwi@gmail.com', 'HoloBoard Admin'),
            subject: 'Lost & Found Report Resolved: ' . $this->report->item_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.lost-and-found-resolved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
