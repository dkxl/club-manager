<table class="table table-striped table-fixed table-bordered width:100%"
       data-controller="{{ route('venues.index') }}"
       data-order="[]"> {{-- datatables will start with the original sort order --}}
<thead>
<tr class="info">
    <th>Venue</th>
    <th>Capacity</th>
    <th>Description</th>
</tr>
</thead>
<tbody>
@foreach($venues as $venue )
    <tr data-id="{{ $venue->id }}">
        <th scope="row">{{ $venue->name }}</th>
        <td>{{ $venue->capacity }}</td>
        <td>{{ $venue->description }}</td>
    </tr>
@endforeach
</tbody>
</table>
