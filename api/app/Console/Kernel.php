<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * php artisan schedule:run
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // $schedule->command('inspire')->hourly();
        $schedule->command('mining_pool_award')->daily();
        $schedule->command('loan_pool_interest')->daily();
        $schedule->command('coin-price-query')->everyMinute();
        $schedule->command('validate-deposit-order')->everyMinute();
        $schedule->command('validate-withdrawal-order')->everyMinute();
        $schedule->command('loan_pool_check_pledge_amount_is_enough')->everyMinute()->withoutOverlapping();
        $schedule->command('auto-open-deposit-pool-ln-rate')->everyMinute();

        //$schedule->command('sum_order_amount_usd')->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
