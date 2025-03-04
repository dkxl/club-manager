<?php
/**
 * member_visits.blade.php
 *
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
?>
{{--    <div class="row">--}}
{{--        <div class="col-md-6">--}}
{{--         This Month:</b> {{ $statistics->this_month }} visits--}}
{{--         Last Month:</b> {{ $statistics->last_month }} visits--}}
{{--         Last Visit:</b> {{ Carbon::parse($statistics->last_visit)->format('d/m/Y H:i') }}--}}
{{--        </div><!-- column -->--}}
{{--        <div class="col-md-6">--}}
{{--        </div><!-- column -->--}}
{{--    </div><!-- row -->--}}
<h3>Recent Check Ins</h3>
<table class="table table-striped table-fixed" data-order="[]" data-searching="false">
    <thead>
    <tr>
        <th>Date</th>
        <th>Card Number</th>
        <th>Result</th>
        <th>Reason</th>
     </tr>
    </thead>
    <tbody>
        @foreach($visits as $visit)
        <tr>
            <td data-order="{{ $visit->created_at }}">{{ $visit->created_at->format('d/m/Y H:i A')  }}</td>
            <td>{{ $visit->card_number }}</td>
            <td>{{ $visit->permitted ? 'Permitted' : 'Denied'  }}</td>
            <td>{{ $visit->reason }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
