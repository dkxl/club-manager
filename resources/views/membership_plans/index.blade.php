<table class="table table-striped table-fixed table-bordered width:100%"
       data-controller="{{ route('plans.index') }}"
       data-order="[]">  {{-- datatables will start with the original sort order --}}
    <thead>
    <tr>
        <th>Plan Name</th>
        <th>Includes Free Classes?</th>
        <th>Joining Fee</th>
        <th>First Payment</th>
        <th>Monthly Payment</th>
        <th>Contract Term (Months)</th>
        <th>Earliest Visit</th>
        <th>Latest Visit</th>
        <th>Available for new contracts?</th>
    </tr>
    </thead>
    <tbody>
        @foreach($plans as $plan)
        <tr data-id="{{ $plan->id }}">
            <th scope="row">{{ $plan->name }}</th>
            <td>{{ ($plan->free_classes) ? 'Yes' : 'No' }}</td>
            <td>&pound;{{ $plan->jf_amount }}</td>
            <td>&pound;{{ $plan->puf_amount }}</td>
            <td>&pound;{{ $plan->dd_amount }}</td>
            <td>{{ $plan->term_months }}</td>
            <td>{{ $plan->start_time->format('H:i') }}</td>
            <td>{{ $plan->end_time->format('H:i') }}</td>
            <td>{{ ($plan->available) ? 'Yes' : 'No' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
