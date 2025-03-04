<?php
/**
 * App\Services\Diary.php
 * @author davidh
 * @package dk-appt
 */

namespace App\Services;

use Carbon\Carbon;


class DiaryServices
{

    /**
     * Return an array of Carbon objects for each diary timeslot on $the_date
     *
     * Create the timeslots in the timezone used in the view, e.g. Europe/London
     * This timezone information then gets passed to the view eg data-timeslot=2017-07-03T09:00:00BST
     *
     * @param Carbon $the_date
     * @return array[Carbon timeslot objects]
     */
    public static function timeslots(Carbon $the_date) : array
    {
        $ymd = $the_date->format('Y-m-d');

        $day_ends = Carbon::parse($ymd . config('club.evening_ends'), config('club.timezone'));
        $the_timeslot = Carbon::parse($ymd . config('club.morning_starts'), config('club.timezone'));

        $resolution = config('club.resolution_seconds', 900);

        $timeslots = [];

        while ( $the_timeslot->lte($day_ends) ) {

            $timeslots[] = $the_timeslot->copy();
            $the_timeslot->addSeconds($resolution);

        }

        return $timeslots;

    }


    /**
     * Generate an array of timeslot strings for this type of series
     * @param Carbon $starts_at
     * @param Carbon $ends_at
     * @param Carbon $repeat_until
     * @param string $repeat_type - daily | weekly | monthly | yearly
     * @return array [ 0 => [Carbon $starts_at, Carbon $ends_at],  1 => [Carbon $starts_at, Carbon $ends_at], ... ]
     */
    public static function seriesTimeslots(Carbon $starts_at, Carbon $ends_at, Carbon $repeat_until,
                                           string $repeat_type ) : array
    {

        $timeslots = [];

        // make sure not to modify the called arguments; objects are passed by reference
        $slot_start = $starts_at->copy();
        $slot_end = $ends_at->copy();

        while ($slot_start->lt($repeat_until)) {

            $timeslots[] = [
                'starts_at' => $slot_start->copy(),
                'ends_at' => $slot_end->copy(),
            ];

            $starts_in_dst = self::isDst($slot_start);
            $ends_in_dst = self::isDst($slot_end);


            switch ($repeat_type) {

                case 'Daily':
                    $slot_start->addDay(1);
                    $slot_end->addDay(1);
                    break;

                case 'Weekly':
                    $slot_start->addWeek(1);
                    $slot_end->addWeek(1);
                    break;

                case 'Monthly':
                    $slot_start->addMonth(1);
                    $slot_end->addMonth(1);
                    break;

                case 'Yearly':
                    $slot_start->addYear(1);
                    $slot_end->addYear(1);
                    break;

                default:
                    // None

            } //switch

            // Handle any Dst Crossings
            self::handleDst($slot_start, $starts_in_dst);
            self::handleDst($slot_end, $ends_in_dst);

        }

        return $timeslots;
    }


    /**
     * Is the date in Daylight Savings Time?
     *
     * Uses PHP's DateTimeZone::getTransitions. Set timestamp_begin
     * and timestamp_end to be the_timestamp, so only one result
     * is returned from the transitions array.
     *
     * @param Carbon $datetime
     * @return bool
     */
    public static function isDst (Carbon $datetime) : bool
    {

        $the_timestamp =  $datetime->timestamp;
        $tz = new \DateTimeZone(config('club.timezone'));

        // fetches a single entry from TzTransitions
        $transition = $tz->getTransitions($the_timestamp, $the_timestamp);

        return $transition[0]['isdst'];
    }


    /**
     * Adjust the time forward or back when crossing DST
     * @param Carbon $datetime - the datetime to adjust
     * @param bool $wasDst - was the previous timeslot in Dst?
     */
    public static function handleDst (Carbon $datetime, bool $wasDst ) : void
    {

        if (!$wasDst && self::isDst($datetime)) {    // entering DST
            $datetime->subHour(1);
        } elseif ($wasDst && !self::isDst($datetime)) {    // leaving DST
            $datetime->addHour(1);
        }

    }

}
