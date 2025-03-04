<?php
/**
 * day.blade.php
 *
 * @param array $diary   [timeslot][venue_id][App\Model\Event $event]
 * @param array $timeslots  Carbon
 * @param Eloquent $venues
 *
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
 *
 */
?>

<nav class="row nav-step">
    <div class="col-md-4 text-left">
        <a href="#dayBefore">&lt;&lt; Day Before</a>
    </div>
    <div class="col-md-4 text-center">
        <a href="#today">Today</a>
    </div>
    <div class="col-md-4 text-right">
        <a href="#dayAfter">Day After &gt;&gt;</a>
    </div>
</nav>
<table id="diary" class="table table-striped table-bordered width:100%" data-controller="/diary/" data-ordering="false"
       data-searching="false" data-scroll-y="500px" data-paging="false" data-info="false">
    <colgroup>
        <col class="width:10%">
        @foreach($venues as $venue)
            <col class="width:{{ floor(90/(count($venues))) }}%">
        @endforeach
    </colgroup>
    <thead>
    <tr class="info">
        <th>Time</th>
        @foreach ($venues as $venue)
            <th data-venue_id="{{ $venue->id }}" class="text-center">{{ $venue->name }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach ($timeslots as $timeslot )
        <?php $t = $timeslot->format('Gi'); ?>
        <tr data-timeslot="{{ $timeslot->toISOString() }}">
            <td>{{ $timeslot->format('h:iA') }}</td>
            @foreach ($venues as $venue )
                @if (isset($diary[$t][$venue->id]))
                        <?php $event = $diary[$t][$venue->id]['event']; ?>
                    @if ($diary[$t][$venue->id]['event_start'])
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
