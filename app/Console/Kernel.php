<?php

namespace App\Console;

use App\Models\Prisoner;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {

            DB::beginTransaction();
            try {

                //get all ids
                $ids = Prisoner::pluck('id');
                foreach ($ids as $key) {
                    $prisoner = Prisoner::find($key);
                    if (!empty($prisoner->prisoner_charges)) {
                        $charges = $prisoner->prisoner_charges->pluck('crime_charges')->toArray();
                        $all_charges = implode(',', $charges);
                        $prisoner->all_charges = $all_charges;
                        $prisoner->save();
                    }
                }

                DB::commit();
                // all good
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
            }

        })->everyMinute();
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
