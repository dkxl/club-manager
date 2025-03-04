<?php
/**
 *
 * Configuration for Exercise Class Bookings
 */

return [

   /*
    * ---------------------------------
    * Club Manager configuration items
    * ---------------------------------
    *
    * Make sure to run 'php artisan config:clear' to purge any cached information
    *
    */
    'company_name' => env('COMPANY_NAME', 'DKXL Ltd'),
    'company_address' => env('COMPANY_ADDRESS', 'The Windmill, West Chiltington, UK'),
    'club_name' => env('CLUB_NAME', 'The Club'),
    'dd_prefix' => env('DD_PREFIX', 'DKXL'),


    /*
     * Postcode lookups - requires an API key subscription
     */
    'postcode_api_key' => env('POSTCODE_API_KEY', 'get_your_own_key'),


    /*
     * Timezone to use in the Diary views
     *    (but note that datetimes are always stored as UTC in the database)
     */

    'timezone' => 'Europe/London',
    'resolution' => 900, // minimum appointment duration, in seconds

    // earliest appointment to display
    'morning_starts' => '06:45',

    // latest appointment to display
    'evening_ends' => '21:15',

    // Events
    'default_event_duration' => 60,    // duration, in minutes
    'default_event_capacity' => 10,
    'default_event_css' => 'diary-magenta',  // css style for events in the diary view
    'max_repeats' => 100,  // maximum number of events for an event series


    /*
     * Check Ins
     */
    // how long to consider somebody as "already checked in"
    'double_visit_minutes' => 45,

    // "recent" check in interval for the daily Check Ins display
    'recent_minutes' => 90,  // minutes

    // how long to flag as a "new member"
    'newbie_weeks' => 4,  // weeks


    /*
     * Exercise Classes
     */

    'booking-fee' => 2, // credits per class

    'late-hours' => 2, // how close to start time before canx is classed as 'Late'

    'closes-hours' => 2, // (hours) when to close the class for late entry of attendees in club
    'prebook-days' => 6,  // (days) when to open the class for new bookings



    /*
     * Timetable generation
     */

    'timetable-days'    => 7,     // how many days to display

    'timetable-max-places' => 12,  // Maximum number of places to show as 'available'

];
