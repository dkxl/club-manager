<?php
/**
 *
 * @param array $diary  [timeslot][day_of_week][$event]
 * @param array $timeslots   Carbon - timeslots for the first day in this week
 * @param array $days    Carbon - days of this week
 * @param Eloquent $venues  all available venues
 * @param App\Model\Venue $the_venue the selected venue
 *
 * data-timeslot is url friendly ISO8601 format eg. 2016-12-19T10:00:00GMT
 *
 * For new bookings: <tr> --> data-timeslot, <th> --> data-asset
 * (use datatables to find the column for a cell)
 *
 * Datatables fix the headers for scrolling;
 * Using <colgroup> to specify the column widths
 * include the <table...width="100%"> to make it easier for datatables to recalculate
 * widths if the window is resized.
 *
 * Timeslot indexes are in format G:i e.g. 16:45, so we can generate these from any day of the week
 * But use Monday to be consistent
 *
 */

?>
<nav class="navbar navbar-detail">
    <ul id="venues" class="nav navbar-nav">
        @foreach ($venues as $venue)
            <li @if ($venue->id == $the_venue->id) class="active" @endif>
                <a href="#venue" data-venue="{{ $venue->id }}">{{ $venue->name }}</a>
            </li>
        @endforeach
    </ul>
</nav>
<nav class="row nav-step">
    <div class="col-sm-2 text-left">
        <a href="#weekBefore">&lt;&lt; Week Before</a>
    </div>
    <div class="col-sm-8 text-center">
        <a href="#today">This Week</a>
    </div>
    <div class="col-sm-2 text-right">
        <a href="#weekAfter">Week After &gt;&gt;</a>
    </div>
</nav>
<table id="diary" class="table table-striped table-bordered width:100%" data-controller="/diary/" data-ordering="false"
       data-searching="false" data-scroll-y="500px" data-paging="false" data-info="false">
    <colgroup>
        <col class="width:10%">
        {{--  divide the remaining space equally --}}
        @foreach($days as $day)
        <col class="width:{{ floor(90/7) }}%">
        @endforeach
    </colgroup>
    <thead>
    <tr class="info">
        <th>Time</th>
        @foreach ($days as $dow => $day)
        <th data-venue_id="{{ $the_venue->id }}" data-date="{{ $day->toISOString() }}">{{ $day->format('l') }}<br/>{{ $day->format('jS M') }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach ($timeslots as $timeslot )
        <?php $t = $timeslot->format('Gi'); ?>
        <tr data-timeslot="{{ $timeslot->toISOString() }}">
            <td>{{ $timeslot->format('h:iA') }}</td>
            @foreach ($days as $dow => $day )
                @if (isset($diary[$t][$dow]))
                    <?php $event = $diary[$t][$dow]['event']; ?>
                   @if ($diary[$t][$dow]['event_start'])
                    <td class="{{ isset($event->metadata['css']) ? $event->metadata['css'] : config('club.default_event_css') }}" title="{{ $event->description }}">
                        <a href="#show" data-controller="{{ route('events.show', $event->id) }}">{{ $event->name }}</a>
                    </td>
                    @else
                       {{-- continuation of an event --}}
                    <td class="{{ isset($event->metadata['css']) ? $event->metadata['css'] : config('club.default_event_css') }}">
                    </td>
                    @endif
                @else
                    {{-- unallocated slot --}}
                    <td class="text-center">
                        <a href="#create" data-controller="{{ route('events.create') }}"><img src="/images/new.gif"></a>
                    </td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
