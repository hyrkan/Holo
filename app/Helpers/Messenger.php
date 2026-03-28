<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class Messenger
{
    /**
     * Send an email immediately.
     * 
     * @param mixed $recipient
     * @param \Illuminate\Mail\Mailable $mailable
     * @return bool
     */
    public static function send($recipient, $mailable)
    {
        if (empty($recipient)) {
            return false;
        }

        try {
            Mail::to($recipient)->send($mailable);
            return true;
        } catch (\Exception $e) {
            Log::error("Messenger [Send] Error: " . $e->getMessage(), [
                'recipient' => $recipient,
                'mailable' => get_class($mailable)
            ]);
            return false;
        }
    }

    /**
     * Queue an email for background sending.
     * 
     * @param mixed $recipient
     * @param \Illuminate\Mail\Mailable $mailable
     * @return bool
     */
    public static function queue($recipient, $mailable)
    {
        if (empty($recipient)) {
            return false;
        }

        try {
            Mail::to($recipient)->queue($mailable);
            return true;
        } catch (\Exception $e) {
            Log::error("Messenger [Queue] Error: " . $e->getMessage(), [
                'recipient' => $recipient,
                'mailable' => get_class($mailable)
            ]);
            return false;
        }
    }
}
