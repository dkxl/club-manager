<?php
/**
 * FOH Status Blade
 * @parent $visits - object with today's visits
 *
 * TODO port to bootstrap styling
 *
 */

// Longest possible date string is something like "Wednesday 30th September 2017"
// Adds an icon for each visit
// Does not check for Alerts
?>
<div id="statusLeft">{{ date("l j F g:i a") }}</div>
<div id="statusRight">
    @foreach($visits as $visit)
        <?php

        if ($visit->event_name == 'EntryWasRefused') {
            $icon = 'redSm.gif';
        } elseif (strpos($visit->reason, 'Double') !==false) {
            $icon = 'amberSm.gif';
        } else {
            $icon = 'blueSm.gif';
        }

        ?>
        <img src="/images/{{ $icon }}" width="12" height="12">
    @endforeach
</div>
{{-- fixme - current layout needs white space here to align subsequent divs properly --}}
<div>&nbsp;</div>
