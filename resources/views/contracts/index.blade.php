<?php
/**
 * index.blade.php
 * @author davidh
 * @package dk-appt
 *
 * @param $contracts - eloquent collection of the contracts
 */
?>
<h3>Membership History</h3>
<table class="table table-striped table-fixed table-bordered width:100%"
       data-controller="/members/{member}/contracts"
       data-order="[]">  {{-- datatables will start with the original sort order --}}
    <thead>
    <tr>
        <th>Membership Plan</th>
        <th>Free Classes?</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>State</th>
     </tr>
    </thead>
    <tbody>
        @foreach($contracts as $contract)
        <tr data-id="{{ $contract->id }}">
            <td>{{ $contract->membership_plan->name }}</td>
            <td>{{ $contract->membership_plan->free_classes ? 'Yes' : 'No' }}</td>
            <td>{{ $contract->start_date }}</td>
            <td>{{ $contract->end_date }}</td>
            <td>{{ $contract->stateString() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
