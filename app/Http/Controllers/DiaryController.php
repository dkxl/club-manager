<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Venue;
use App\Services\DiaryServices;
use Carbon\Carbon;
use Illuminate\View\View;

class DiaryController extends BaseController
{

    /**
     * View the diary for today
     * @return View
     */
    public function today(DiaryServices $diaryServices) : View
    {

        $today = Carbon::today();

        return $this->day($diaryServices, $today);

    }


    /**
     * View the daily diary
     * @return View
     */
    public function day(DiaryServices $diaryServices, string $the_date='') : View
    {

        $the_date = $the_date ? Carbon::parse($the_date) : Carbon::today();

        $events = Event::ForDay($the_date)->get();
        $timeslots = $diaryServices->timeslots($the_date);

        /*
         * Build a map of the screen that will be displayed
         *     $diary[timeslot][venue_id][$event]
         */
        $diary = [];

        foreach ($events as $event) {
            // Start at the event start time, and end one slot before the event end time
            // (since the next slot is for events which start then)

            foreach($timeslots as $timeslot) {
                $t = $timeslot->format('Gi');
                $venue_id = $event->venue->id;
                if ($event->starts_at->eq($timeslot)) {
                    // start of the event
                    $diary[$t][$venue_id]['event'] = $event;
                    $diary[$t][$venue_id]['event_start'] = true;

                }  elseif ($event->starts_at->lt($timeslot) && $event->ends_at->gt($timeslot)) {
                    // continuation of an event
                    $diary[$t][$venue_id]['event'] = $event;
                    $diary[$t][$venue_id]['event_start'] = false;
                }
            } // next timeslot
        } // next event


        return view ('diary.day', [
            'diary' => $diary,
            'timeslots' => $timeslots,
            'venues' => Venue::all()
        ]);

    }


    /**
     * View the weekly diary
     * @return View
     */
    public function week(DiaryServices $diaryServices, string $the_date='', string $the_venue=''): View
    {
        $the_date = $the_date ? Carbon::parse($the_date) : Carbon::today();
        $the_venue = $the_venue ? Venue::find($the_venue) : Venue::first();

        $events = Event::ForWeek($the_date)->where('venue_id', $the_venue->id)->get();

        /*
         * Build a map of the screen that will be displayed
         *     $diary[timeslot][day_of_week][$event]
         */
        $diary = [];

        // The Carbon week starts on Monday
        $days = [
            1 => $the_date->startOfWeek(),   // dow 1
            2 => $the_date->copy()->addDays(1),  // dow 2
            3 => $the_date->copy()->addDays(2), // dow 3
            4 => $the_date->copy()->addDays(3), // dow 4
            5 => $the_date->copy()->addDays(4), // dow 5
            6 => $the_date->copy()->addDays(5),  // dow 6
            0 => $the_date->copy()->addDays(6), // dow 0
        ];

        foreach ($days as $dow => $day) {

            $timeslots = $diaryServices->timeslots($day); // need a new set of timeslots for each day

            foreach ($events as $event) {
                // Start at the event start time, and end one slot before the event end time
                // (since the next slot is for events which start then)

                if ($event->starts_at->dayOfWeek !== $dow)
                    continue; // event does not start on this day, so skip timeslot iteration

                foreach($timeslots as $timeslot) {
                    $t = $timeslot->format('Gi');
                    if ($event->starts_at->eq($timeslot)) {
                        // start of the event
                        $diary[$t][$dow]['event'] = $event;
                        $diary[$t][$dow]['event_start'] = true;

                    }  elseif ($event->starts_at->lt($timeslot) && $event->ends_at->gt($timeslot)) {
                        // continuation of an event
                        $diary[$t][$dow]['event'] = $event;
                        $diary[$t][$dow]['event_start'] = false;
                    }
                } // next timeslot
            } // next event
        } // next day of week

        return view ('diary.week', [
            'diary'   => $diary,
            'timeslots' => $diaryServices->timeslots($the_date),
            'days' => $days,
            'the_venue' => $the_venue,
            'venues' => Venue::all(),
        ]);

    }

}
