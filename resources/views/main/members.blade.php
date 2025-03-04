<?php
/*
 * members.blade.php
 *
 * Tab URLs must be relative to the site origin
 */
?>

@extends('layouts.tabs')

@section('title','Members')
@section('body_id', 'members')

@section('Tabs')
    <li class="active"><a href="#parent" data-controller="/members">Member</a></li>
    <li><a href="#contracts" data-controller="/contracts">Contracts</a></li>
    <li><a href="#notes" data-controller="/notes">Notes</a></li>
    <li><a href="#classes" data-controller="/classes">Classes</a></li>
    <li><a href="#checkins" data-controller="/checkins">Check Ins</a></li>
    @include ('components.search')
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/members.js"></script>
    @if (!empty($member_id))
    <script type="text/javascript">
        const initMember = '{{ $member_id }}'; // seed the initial environment
    </script>
    @endif
@endsection
