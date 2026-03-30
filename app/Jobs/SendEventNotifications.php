<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\Student;
use App\Mail\EventNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Helpers\Messenger;

class SendEventNotifications implements ShouldQueue
{
    use Queueable;

    protected $event;

    /**
     * Create a new job instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $query = Student::with('user')->where('status', 'approved');

        $departments = $this->event->departments;

        // If 'All' is selected or no specific departments are selected, send to all students
        if (!empty($departments) && !in_array('All', $departments)) {
            $query->whereIn('program', $departments);
        }

        // Process students in chunks to handle large numbers efficiently
        $query->chunk(50, function ($students) {
            foreach ($students as $student) {
                if ($student->user && $student->user->email) {
                    Messenger::send($student->user->email, new EventNotification($this->event));
                }
            }
        });
    }
}
