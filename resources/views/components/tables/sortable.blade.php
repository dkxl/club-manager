<?php
/**
 * sortable.blade.php
 * @author davidh
 * @package dk-appt
 *
 * @param array $headers
 * @param array[arrays] $content
 *
 */
?>
<table class="tablesorter table table-bordered table-striped">
    <thead>
    <tr>
        @foreach ($headers as $header)
            <th>{{ $header }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
        @foreach ($content as $row)
            <tr>
            @foreach ($row as $cell)
                <td>{{ $cell }}</td>
            @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
