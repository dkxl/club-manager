<?php
/**
 * Handles class schedules
 *
 * Similar to Appointments handling but uses a different Form
 * and a different FormRequest
 * */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\EventSeries;
use App\Models\Venue;
use App\Models\Instructor;
use App\Services\DiaryServices;


class EventScheduleController extends BaseController
{


    /**
     * Display the Event
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event){

        return view('events.show', [
            'event' => $event,
        ]);
    }


    /**
     * Show the form for creating a new event. The form includes the option to create a series of events
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $validated = $request->validate([
           'starts_at' => 'required|date',
           'venue_id' => 'required|ulid|exists:venues,id',
        ]);

        $event = new Event();

        $event->venue_id = $validated['venue_id'];
        $event->starts_at = $validated['starts_at']; // the validation returns a Carbon object
        $event->ends_at = $event->starts_at->copy()->addMinutes(config('club.default_event_duration'));
        $event->repeat_type = 'None';
        $event->metadata = [
            'report_ref' => '',
            'capacity' => config('club.default_event_capacity'),
            'css' => config('club.default_event_css'),
        ];

        return view('events.editor', [
                'event' => $event,
                'instructors' => Instructor::selector(),
                'venues' => Venue::selector(),
                'controller' => url('/events'),
            ]);
    }


    /**
     * Store a newly created resource in storage
     * Uses a FormRequest to validate the input
     * @param  EventRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {

        $data = $request->validated();

        if ($data['repeat_type'] === 'None' ) {  // single event

	        $event = Event::create($data);

        } else {  // repeated events

            $event = EventSeries::create($data);
            $timeslots = DiaryServices::seriesTimeslots($event->starts_at, $event->ends_at, $event->repeat_until,
                                                        $event->repeat_type);

            foreach ($timeslots as $timeslot) {
                // each $timeslot is an array[ Carbon $starts_at, Carbon $ends_at]; merge the remaining data
                $timeslot['name'] = $event->name;
                $timeslot['description'] = $event->description;
                $timeslot['venue_id'] = $event->venue_id;
                $timeslot['instructor_id'] = $event->instructor_id;
                $timeslot['metadata'] = $event->metadata;
                $timeslot['series_id'] = $event->id;
                Event::create($timeslot);
            }

        }

        return view('events.form', [
            'event' => $event,
            'instructors' => Instructor::selector(),
            'venues' => Venue::selector(),
            'controller' => ($data['repeat_type'] === 'None') ? url('/events') : url('/series'),
        ]);

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    //
    public function edit(Event $event){
        return view('events.editor', [
            'event' => $event,
            'instructors' => Instructor::selector(),
            'venues' => Venue::selector(),
            'controller' => url('/events')
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param EventRequest $request
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, Event $event)
    {

        $event->fill($request->validated());
        $event->save();

        return view( 'events.form', [
                'event' => $event,
                'instructors' => Instructor::selector(),
                'venues' => Venue::selector(),
                'controller' => url('/events')
            ] );

    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event) {

        if ($event->hasBookings()) {
            return response()->json(['errors' => ['Deletion not permitted - there are bookings for this event.']], 422);
        }

        $event->delete();
        return view('status.warning')->with('message', 'Event deleted');

    }


}
