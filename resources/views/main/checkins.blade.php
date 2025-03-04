<?php
/**
 * checkins.blade.php
 *
 * Today's Check Ins etc
 *
 * @author davidh
 * @package dk-appt
 */
?>

@extends('layouts.master')

@section('title','Check Ins')
@section('body_id', 'check_ins')

@section('banner')
    <div id="action-buttons" class="btn-group-sm pull-right">
        <button id="btnRefresh" type="button" class="btn btn-primary">Refresh</button>
        <button id="btnPrint" type="button" class="btn btn-success">Print</button>
        <button id="btnClose" type="button" class="btn btn-info" onclick="self.close()">Close</button>
    </div>
    <h1 id="page-title">Check Ins <small id="sub-title">{{ date("l j F") }}</small></h1>
@endsection


@section('content')
    <div id="primary" class="col-sm-9">
        <div id="status"></div>
        <div id="tab-content"></div>
    </div>
    <div id="secondary" class="col-sm-3">
        <div class="panel">
            <div class="panel-heading bg-primary">Today's Totals</div>
            <div class="panel-body text-center" id="totals">&nbsp;</div>
        </div>
        <div class="panel">
            <div class="panel-heading bg-primary">Manual Check In</div>
            <div class="panel-body" id="search" data-toggle="tooltip" title="Search by first name, last name, or phone number">
                Search: <input type="text" name="namesearch" id="namesearch"/>
            </div>
            <button id="btnGo" type="button" class="btn btn-block btn-info">Check In</button>
        </div>
    </div>
@endsection


@section('scripts')
    <script type="text/javascript" src="/js/jquery-3.0.0.min.js"></script>
    <script type="text/javascript" src="/js/jquery-migrate-3.0.0.js"></script>
    <script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
    <script type="text/javascript" src="/js/datatables.min.js"></script>
    <script type="text/javascript" src="/js/checkins.js"></script>
@endsection




