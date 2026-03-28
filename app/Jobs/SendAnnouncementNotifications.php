<?php

namespace App\Jobs;

use App\Models\Announcement;
use App\Models\Student;
use App\Mail\AnnouncementNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Helpers\Messenger;

class SendAnnouncementNotifications implements ShouldQueue
{
    use Queueable;

    protected $announcement;

    /**
     * Create a new job instance.
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $targetAudience = $this->announcement->target_audience;
        $targetYearLevels = $this->announcement->target_year_levels;

        $query = Student::with('user')->where('status', 'approved');

        if ($targetAudience === 'guests') {
            $query->where('student_type', 'guest');
        } elseif ($targetAudience === 'students') {
            $query->where('student_type', 'regular');
            if ($targetYearLevels && count($targetYearLevels) > 0) {
                $query->whereIn('year_level', $targetYearLevels);
            }
        }

        // Process students in chunks to handle large numbers efficiently
        $query->chunk(50, function ($students) {
            foreach ($students as $student) {
                if ($student->user && $student->user->email) {
                    Messenger::queue($student->user->email, new AnnouncementNotification($this->announcement));
                }
            }
        });
    }
}
