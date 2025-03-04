<?php
/**
 * ajax.blade.php
 * Displays errors for an ajax store/update
 * @author davidh
 * @package dk-appt
 */
?>
@if (count($errors) > 0)
<div class="alert alert-danger">
    <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
   </ul>
</div>
@endif
