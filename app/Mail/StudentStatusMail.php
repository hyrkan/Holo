<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $status;
    public $status_message;

    /**
     * Create a new message instance.
     */
    public function __construct(Student $student, $status, $status_message = null)
    {
        $this->student = $student;
        $this->status = $status;
        $this->status_message = $status_message;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Account Registration Update';
        if ($this->status === Student::STATUS_APPROVED) {
            $subject = 'Account Approved - Welcome to Holo Board';
        } elseif ($this->status === Student::STATUS_DENIED) {
            $subject = 'Account Registration Denied - Holo Board';
        }

        return new Envelope(
            null,
            [],
            [],
            [],
            [],
            $subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            null,
            null,
            null,
            'emails.student-status'
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
