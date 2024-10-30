<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        // Daftarkan command Anda di sini
    ];

    protected function schedule(Schedule $schedule)
    {
        // Jadwalkan command di sini
        $schedule->command('repair:start-automatic')->everySecond();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
