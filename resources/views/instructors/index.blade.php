<table class="table table-striped table-fixed table-bordered width:100%"
       data-controller="{{ route('instructors.index') }}"
       data-order="[]">  {{-- datatables will start with the original sort order --}}
    <thead>
    <tr>
        <th>Name</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Skills</th>
        <th>Available?</th>
     </tr>
    </thead>
    <tbody>
        @foreach($instructors as $instructor)
        <tr data-id="{{ $instructor->id }}">
            <th scope="row">{{ $instructor->name }}</th>
            <td>{{ $instructor->phone }}</td>
            <td>{{ $instructor->email }}</td>
            <td>{{ $instructor->skills }}</td>
            <td>{{ ($instructor->available) ? 'Yes' : 'No' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
