<?php
/**
 * Handles class schedules
 *
 * Similar to Appointments handling but uses a different Form
 * and a different FormRequest
 * */

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\EventSeries;
use App\Models\Venue;
use App\Models\Instructor;
use App\Services\DiaryServices;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EventSeriesController extends BaseController
{


    /**
     * Show the form for editing the specified resource.
     *
     * @param EventSeries $series
     * @return \Illuminate\Http\Response
     */
    //
    public function edit(EventSeries $series){
        return view('events.editor', [
            'event' => $series,
            'instructors' => Instructor::selector(),
            'venues' => Venue::selector(),
            'controller' => url('/series')
        ]);
    }


    /**
     * Update the specified resource in storage.
     * The EventRequest validates that there are no existing bookings for any of the child events before permitting the
     * changes.
     * If the timeslots or repeats have changed, deletes and recreates the event children.
     * @param EventRequest $request
     * @param Event $series
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, EventSeries $series)
    {

        $series->fill($request->validated());

        if ($series->isDirty(['starts_at', 'ends_at', 'repeat_until', 'repeat_type'])) {

            // Timeslots or repeats have changed - delete and recreate all children
            DB::transaction(function () use ($series) {
                Event::where('series_id', $series->id)->delete();

                $timeslots = DiaryServices::seriesTimeslots($series->starts_at, $series->ends_at, $series->repeat_until,
                    $series->repeat_type);

                foreach ($timeslots as $timeslot) {
                    // each $timeslot is an array[ Carbon $starts_at, Carbon $ends_at]; merge the remaining data
                    $timeslot['name'] = $series->name;
                    $timeslot['description'] = $series->description;
                    $timeslot['venue_id'] = $series->venue_id;
                    $timeslot['instructor_id'] = $series->instructor_id;
                    $timeslot['metadata'] = $series->metadata;
                    $timeslot['series_id'] = $series->id;
                    Event::create($timeslot);
                }

                $series->save();
            });

        } else {

            // Timeslots not modified - update changed attributes for the existing child events

            DB::transaction(function () use ($series) {

                $children = Event::where('series_id', $this->input('id'))->get();
                foreach ($children as $child) {
                    $child['name'] = $series->name;
                    $child['description'] = $series->description;
                    $child['venue_id'] = $series->venue_id;
                    $child['instructor_id'] = $series->instructor_id;
                    $child['metadata'] = $series->metadata;
                    $child->save();
                }
                $series->save();

            });

        }

        return view( 'events.form', [
                'event' => $series,
                'instructors' => Instructor::selector(),
                'venues' => Venue::selector(),
                'controller' => url('/series')
            ] );

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventSeries $series) {

        $errors = [];

        $children = Event::where('series_id', $this->input('id'))->get();
        foreach ($children as $idx => $child) {
            if ($child->hasBookings()) {
                $errors[] = $child->starts_at->format('d-m-Y');
            }
        }

        if ($errors) {
            return response()->json(['errors' => ['Deletion not permitted - there are bookings for the following child events: '
            + join(', ', $errors)]], 422);
        }

        // Delete all children, or rollback on failure
        DB::transaction(function () use ($series) {
            Event::where('series_id', $series->id)->delete();
            $series->delete();
        });

        return view('status.warning')->with('message', 'Event Series deleted');

    }

}
