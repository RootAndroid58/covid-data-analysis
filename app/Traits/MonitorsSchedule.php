<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\ScheduleEvents;

trait MonitorsSchedule
{
    /**
     * Monitor each of the scheduled events.
     *
     * @param Schedule $schedule
     */
    public function monitor(Schedule $schedule)
    {
        $date = Carbon::today()->toDateString();

        $events = new Collection($schedule->events());

        $events->each(function (Event $event) use ($date) {

            $command = substr($event->command, strpos($event->command, 'artisan') + strlen('artisan') + 1);

            $filename = str_slug($command) . "-$date.log";

            $path = storage_path("logs/$filename");

            $event->sendOutputTo($path)->after(function () use ($command, $path) {

                if (file_exists($path) && ($output = file_get_contents($path))) {
                    $ScheduleEvents = new ScheduleEvents;
                    $ScheduleEvents->command = $command;
                    $ScheduleEvents->output = $output;
                    $ScheduleEvents->logged_at = Carbon::now();
                    $ScheduleEvents->save();

                    unlink($path);
                }
            });
        });
    }
}
