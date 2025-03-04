<?php
/**
 * FOH Entry Result Blade - shows results for a Card Entry Attempt
 * @param $result  - object with the result event data
 * @param $statistics - object with the members recent visit stats
 * @param $member_name
 * @param bool $alerts - any alerts pending for this member?
 *
 * TODO port to bootstrap styling and JQuery
 *      use HTML5 to play the sound, within a hidden div
 */

//TODO: move the meta to the main page template
?>
<meta name="csrf-token" content="{{ csrf_token() }}">
{{--  Prepare the text to display and the sound to play for this result   --}}
@if ($result->reason == 'Unknown Card')
    <h1 class="err">Unknown Card</h1>
    <h3>Please See a Member of Staff</h3>
    <input type="hidden" id="chkSound" name="chkSound" value="err">
@elseif($result->event_name == 'EntryWasRefused')
    <h1>Hello {{ $member_name }}</h1>
    <h1 class="err">Entry Declined</h1>
    <h3>Please See a Member of Staff</h3>
    <input type="hidden" id="chkSound" name="chkSound" value="err">
@elseif($alerts)
    <h1>Hello {{ $member_name }}</h1>
    <h1 class="ok">Welcome</h1>
    <h3>Please See a Member of Staff</h3>
    <input type="hidden" id="chkSound" name="chkSound" value="warn">
@elseif($result->reason == 'Double Visit')
    <h1>Hello {{ $member_name }}</h1>
    <h1 class="warn">You are already Checked In</h1>
    <input type="hidden" id="chkSound" name="chkSound" value="warn">
@else   {{-- All is good --}}
    <h1>Hello {{ $member_name }}</h1>
    <h1 class="ok">Welcome</h1>
@endif


@if ($statistics)
<table summary="stats" width="90%">
    <tbody>
    <tr>
        <td class="CL" width="25%">Visits This Month:</td>
        <td class="CL" width="15%">{{ $statistics->this_month }}</td>
        <td class="CL" width="15%">Last Month:</td>
        <td class="CL" width="15%">{{ $statistics->last_month }}</td>
    </tr>
    </tbody>
</table>
@endif