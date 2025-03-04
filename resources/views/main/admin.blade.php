<?php
/**index.blade.php
 *  Tab URLs must be relative to the site origin
 */
?>

@extends('layouts.tabs')

@section('title','Administration')
@section('body_id', 'admin')

@section('Tabs')
    <li class="active"><a href="#tab1" data-controller="/plans">Membership Plans</a></li>
    <li><a href="#tab2" data-controller="/venues">Venues</a></li>
    <li><a href="#tab3" data-controller="/instructors">Instructors</a></li>
    <li><a href="#tab4" data-controller="/users">Users</a></li>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/admin.js"></script>
@endsection
