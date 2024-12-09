<?php

declare(strict_types=1);

namespace App\V1\Core\UI\CLI;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Telescope\Console\PruneCommand;

class Kernel extends ConsoleKernel
{
    public const MIDNIGHT = '00:00';

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(PruneCommand::class, ['--hours' => 336])->dailyAt(self::MIDNIGHT); // two weeks
    }
}
