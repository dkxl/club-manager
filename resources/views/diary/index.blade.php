<?php
/**
 * diary.blade.php
 *
 * Appointment diary page
 *
 * @author davidh
 * @package dk-appt
 */
?>

@extends('layouts.master')

@section('title','Diary')
@section('body_id', 'appointments')

@section('banner')
<div id="menu" class="row">
    <div class="col-sm-9">
        <nav class="navbar navbar-ocean">
            <x-application-logo/>{{ config('app.name', 'Laravel') }}
            <ul id="contexts" class="nav navbar-nav navbar-right">
                <li class="active"><a href="#nav1" data-controller="{{ route('diary') }}">Classes</a></li>
                <li><a href="#nav3" data-controller="{{ route('members.admin') }}" data-target="mbrMain">Members</a></li>
{{--                <li><a href="#nav4" data-controller="{{ route('checkins') }}" data-target="chkMain">CheckIns</a></li>--}}
{{--                <li><a href="#nav5" data-controller="/club/reports/" data-target="rptMain">Reports</a></li>--}}
                @can('administer')
                <li><a href="#nav6" data-controller="{{ route('club.admin') }}" data-target="admMain">Admin</a></li>
                @endcan
                <li><a href="#logout" data-controller="/logout" class="bg-warning">{{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <div class="row">
            <div class="col-sm-8">
                <h1 id="page-title">Diary</h1>
            </div>
            <div class="col-sm-4">

                <nav class="navbar navbar-ocean">
                    <ul id="views" class="nav navbar-nav navbar-right">
                        <li class="active"><a href="#btnDay" data-controller="day">Day</a></li>
                        <li><a href="#btnWeek" data-controller="week">Week</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div id="calendar"></div>
    </div>
</div>
@endsection


@section('content')
    <div class="panel-body">
        <div id="status"></div>
        <div id="diary"></div>
    </div>
@endsection


@section('footer-nav')
    <ul class="nav navbar-nav">
        <li><a href="{{ route('profile.edit') }}">User Profile</a></li>
     </ul>
@endsection


@section('scripts')
    <script type="text/javascript" src="/js/jquery-3.0.0.min.js"></script>
    <script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="/js/date-en-GB.js"></script>
    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script type="text/javascript" src="/js/diary.js"></script>
@endsection
