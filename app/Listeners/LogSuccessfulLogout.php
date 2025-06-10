<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogout
{
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        Log::info('User logged out successfully', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'type' => $event->user->type,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
