<?php
namespace App\Console;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Prune data Telescope yang lebih dari beberapa jam (untuk menghindari penumpukan)
        $hours = env('TELESCOPE_PRUNE_HOURS', 24);
        $schedule->command("telescope:prune --hours={$hours}")->daily()->name('telescope-prune')->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}