<?php
/**
 * Show booking details for an event
 * Includes sidebar to add new bookings
 */
?>
@extends('layouts.master')

@section('title', $event->name)


@section('banner')
    <div id="action-buttons" class="btn-group-sm pull-right">
        <button id="btnClose" type="button" class="btn btn-info" onclick="self.close()">Close</button>
    </div>
    <div id="task-buttons" class="btn-group-sm pull-right">
        <button id="btnRefresh" type="button" class="btn btn-success">Refresh</button>
    </div>
    <h1 id="page-title">@yield('title', $event->name)
        <small id="sub-title">{{ $event->starts_at->format('H:i A l j F Y') }}</small>
    </h1>
    <div id="summary" class="row">
        <div class="col-sm-3">
            <strong>Instructor:</strong> {{ $event->instructor->name }}
        </div>
        <div class="col-sm-2">
            <strong>Starts:</strong> {{ $event->starts_at->format('H:i A') }}
        </div>
        <div class="col-sm-2">
            <strong>Ends:</strong> {{ $event->ends_at->format('H:i A') }}
        </div>
        <div class="col-sm-2">
            <strong>Capacity:</strong> {{ $event->countCapacity() }}
        </div>

    </div>
@endsection

@section('content')
    <div class="row">
        <div id="primary" class="col-sm-9">
            <div id="bookings" data-controller="{{ route('events.bookings.index', $event->id) }}"></div>
        </div>
        <div id="secondary" class="col-sm-3">
            <div id="search" class="panel">
                <div class="panel-heading bg-primary">Add a Booking</div>
                <div class="panel-body">
                    @include('components.search')
                </div>
                <button id="btnBook" type="button" class="btn btn-block btn-info">Add Booking</button>
             </div>
            <div id="status"></div>
        </div>
    </div>
@endsection

@section('footer-nav')
<ul class="nav navbar-nav">
    <li><a href="{{ route('events.edit', $event->id) }}">Edit Event</a></li>
    @if($event->series_id)
    <li><a href="{{ route('series.edit', $event->series_id) }}">Edit Series</a></li>
    @endif
</ul>
@endsection

@section('scripts')
    <script type="text/javascript" src="/js/bookings.js"></script>
@endsection

