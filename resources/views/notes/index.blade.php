<?php
/**
 * index.blade.php
 * @author davidh
 * @package dk-appt
 *
 * @param $notes - eloquent collection of notes
 */
?>
<table class="table table-striped table-fixed table-bordered width:100%"
       data-controller="/notes"
       data-order="[]">  {{-- datatables will start with the original sort order --}}
    <thead>
    <tr>
        <th>Topic</th>
        <th>Note</th>
        <th>Alerts</th>
     </tr>
    </thead>
    <tbody>
    {{-- use getFormValue for dates so we get them in Form View format (d/m/Y) --}}
        @foreach($notes as $note)
        <tr data-id="{{ $note->id }}">
            <td>{{ ($note->topic) ? $note->topics[$note->topic] : '' }}</td>
            <td>{{ $note->note }}</td>
            <td>{{ ($note->alert) ? $note->alerts[$note->alert] : '' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
