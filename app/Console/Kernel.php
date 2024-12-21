<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('tasks:send-reminders')->daily();
        $schedule->call(function () {
            \App\Models\Task::where('token_expires_at', '<', now())->update([
                'access_token' => null,
                'token_expires_at' => null,
            ]);
        })->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}