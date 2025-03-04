<?php
/**
 * daily_visits.blade.php
 * All Check In activity for this day
 *
 * data-order="[]" means datatables will start with the original sort order
 *  see https://datatables.net/reference/option/order
 *
 *
 *
 * @author davidh
 * @package dk-appt
 *
 * @param $visits - array of the visit objects
 * @param $statistics - visit statistics
 */
use Carbon\Carbon;
use App\Projections\Member;
use App\Projections\TrainingGoal;
use App\Projections\Note;

// Calculate when to consider this a 'recent' visit
// 'Recent' only gets flagged if viewing today's visits, but that makes sense
$now = new Carbon();
$recent_cutoff = $now->subMinutes(config('club.recent_minutes'));

?>
<meta name="csrf-token" content="{{ csrf_token() }}">
<table class="table table-fixed" data-order="[]" data-searching="false">
    <thead>
    <tr>
        <th>Alerts</th>
        <th>Name</th>
        <th>Time</th>
        <th>Card No.</th>
        <th>Status</th>
        <th>Trainer</th>
        <th>Check In Result</th>
     </tr>
    </thead>
    <tbody>
        @foreach($visits as $visit)
         <?php

         // include a sortable data-order attribute for data-tables
         $d = Carbon::parse($visit->visit_date);
         $sort_date = $d->format('Ymd\THisT'); // UTC, sortable string
         $display_date = $d->setTimezone(config('club.timezone'))->format('H:i A'); // view timezone


         // Assign a CSS class to the row
         if ($visit->event_name == 'EntryWasRefused')
             $row_class = 'bg-danger';
         elseif (strpos($visit->status, 'Recent') !== false) // the member is a 'newbie'
             $row_class = 'bg-success';
         elseif ($d->gte($recent_cutoff)) //this was a 'recent' check in
             $row_class = 'bg-warning';
         else
             $row_class = '';


         // Only check for alerts if viewing today's visits for a known member
         if ($visit->member_id && $d->isToday())
             $hasAlerts = Note::hasAlerts($visit->member_id);
         else
             $hasAlerts = false;

         // Which icon to display?
         $icon = ($hasAlerts) ? 'blueSmF.gif' : 'blueSm.gif';

         if ($visit->event_name == 'EntryWasRefused') {
             $icon = ($hasAlerts) ? 'redSmF.gif' : 'redSm.gif';
         } elseif (strpos($visit->reason, 'Double') !==false) {
             $icon = ($hasAlerts) ? 'amberSmF.gif' : 'amberSm.gif';
         }

         // Find info about the member
         if ($visit->member_id) {
             $the_member = Member::find($visit->member_id); // do not fail - the member may have been deleted from the projection
         }

         ?>
         <tr data-member_id="{{ $visit->member_id }}" @if ($row_class) class="{{ $row_class }}" @endif >
            <td><img src="/images/{{ $icon }}"></td>
            @if  ($visit->member_id && $the_member)
                <td><a href="/club/members/{{ $visit->member_id }}" target="mbrMain">{{ $the_member->getFullName() }}</a></td>
             @else
                <td>Unknown</td>
            @endif
            <td data-order="{{ $sort_date }}">{{ $display_date  }}</td>
            <td>{{ $visit->card_num }}</td>
            <td>{{ $visit->status }}</td>
            <td>{{ ($visit->member_id)? TrainingGoal::currentTrainerName($visit->member_id) : 'N/A'}}</td>
            <td>{{ ($visit->event_name == 'EntryWasApproved')? 'OK' : 'Denied' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
