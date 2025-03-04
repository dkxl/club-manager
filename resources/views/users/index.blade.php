<table class="table table-striped table-fixed table-bordered width:100%"
       data-controller="{{ route('users.index') }}"
       data-order="[]"> {{-- datatables will start with the original sort order --}}
<thead>
<tr class="info">
    <th>Name</th>
    <th>Email</th>
    <th>Email Verified</th>
    @foreach($roles as $role)
        <th>{{ ucfirst($role) }}</th>
    @endforeach
</tr>
</thead>
<tbody>
@foreach($users as $user )
    <tr data-id="{{ $user->id }}">
        <th scope="row">{{ $user->name }}</th>
        <td>{{ $user->email }}</td>
        <td>{{ ($user->email_verified_at) ? 'Y' : 'N' }}</td>
        @foreach($roles as $role)
            <td>{{ $user->hasRole($role) ? 'Y' : 'N' }}</td>
        @endforeach
    </tr>
@endforeach
</tbody>
</table>
