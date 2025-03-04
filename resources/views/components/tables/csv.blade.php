<?php
/**
 * csv.blade.php
 * @author davidh
 * @package dk-appt
 */
?>
@foreach ($data as $row)
'{{ implode("', '", $row)   }}'

@endforeach