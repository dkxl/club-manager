<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Http\Requests\BookingUpdateRequest;
use App\Models\Booking;
use App\Models\Event;


class BookingController extends BaseController
{


    /**
     * Show the list of bookings for the current event
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {

        $bookings = $event->bookings()
                        ->orderBy('state', 'asc')
                        ->orderBy('created_at', 'asc')
                        ->get();

        return view('bookings.index', [
            'event' => $event,
            'bookings' => $bookings,
         ]);

    }


    /**
     * Add a new booking for the event
     *
     * @param  BookingRequest $request
     * @param  Event $event
     * @return \Illuminate\Http\Response
     */
    public function store(BookingRequest $request, Event $event)
    {
        $booking = new Booking($request->validated());
        $booking->event_id = $event->id;
        $booking->created_by = $request->user()->id;
        $booking->state = 'Provisional';

        if ($event->isAlreadyBooked($booking->member_id)) {
            return response()->json(['errors' => ['Already booked on this event']], 422);
        };

        if ($event->isNotYetOpen()) {
            return response()->json(['errors' => ['Event bookings are not yet open']], 422);
        }

        if ($event->isClosed()) {
            return response()->json(['errors' => ['Event Closed for new bookings']], 422);
        }

        // Automatically change to a Reserve place if the class is full
        if ($event->isFull()) {
            $booking->state = 'Reserve';
        } else {
            $booking->state = 'Booked';
        }

        $booking->save();

        return response()->json([
            'message' => $booking->member->getFullName() . ' - booking created.',
        ], 200);
    }


    /**
     * Update the booking.
     *
     * @param BookingUpdateRequest $request
     * @param Booking $booking
     * @return \Illuminate\Http\Response
     *
     */
    public function update(BookingUpdateRequest $request, Booking $booking)
    {
        $requested_state = $request->validated()['state'];

        if (!$booking->isPermittedStateChange($requested_state)) {
            return response()->json([
                'errors' => ["You cannot change state from $booking->state to $requested_state"]
            ], 422);
        }

        $booking->fill($request->validated());
        $booking->save();

        return response()->json([
            'message' => $booking->member->getFullName() . ' - booking updated.'
        ], 200);

    }


    /**
     * Remove the specified resource from storage.
     * Soft delete; preserves the last known state before deletion.
     *
     * @param Booking $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {

       if (!$booking->isPermittedStateChange('Deleted')) {
           return response()->json([
               'errors' => ["Only Provisional or Cancelled bookings can be deleted"]
           ], 422);
       }

       $booking->delete();
       return response()->json(['message' => 'Booking has been deleted']);
    }

}
