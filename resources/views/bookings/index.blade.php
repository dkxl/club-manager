
<form onsubmit="return false;">
<meta name="csrf-token" content="{{ csrf_token() }}">
<table class="table table-striped table-fixed table-bordered width:100%"
       data-controller="{{ route('events.bookings.index', ['event' => $event->id])  }}"
       data-order="[]">
    <thead>
    <tr>
        <th>Name</th>
        <th>Booking State</th>
        <th>Comments</th>
    </tr>
    </thead>
    <tbody>
    @foreach($bookings as $i => $booking)
        <tr data-controller="{{ route('bookings.update', ['booking' => $booking->id]) }}">
            <td>{{ $booking->member->getFullName() }}</td>
            <td>
                <select class="form-control" name="state" id="state-{{ $i }}">
                    @foreach($booking->getBookingStates() as $option)
                        <option value="{{ $option }}" @selected($option == $booking->state)>
                            {{ ucfirst($option) }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td><input class="form-control" type="text" name="comments" id="comments-b{{ $i }}"  value="{{ $booking->comments }}"></td>
        </tr>
    @endforeach
    </tbody>
</table>
</form>
