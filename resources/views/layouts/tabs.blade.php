<?php
/**
 * tabs.blade.php
 *
 * Main Layout for GUI pages. Provides action buttons and additional structure
 *
 * TODO: concatenate and minify the style sheets and javascript files
 *
 * @author davidh
 * @package dk-appt
 */
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - @yield('title', config('club.club_name'))</title>
    @yield('meta')
    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="/css/bootstrap-datepicker3.css" rel="stylesheet">
    <link href="/css/bootstrap-timepicker.css" rel="stylesheet">
    <link href="/css/datatables.min.css" rel="stylesheet">
    <link href="/css/ocean.css" rel="stylesheet">
    <link href="/css/print.css" rel="stylesheet">
    @yield('css')
</head>
<body id="@yield('body_id', 'master')">
<div id="wrapper" class="container-fluid">

    <div id="page-header" class="page-header hidden-print">
        <div id="action-buttons" class="btn-group-sm pull-right">
            <button id="btnPrint" type="button" class="btn btn-success">Print</button>
            {{--<button id="btnBack" type="button" class="btn btn-info" onclick="self.close();">Close</button>--}}
        </div>
        <div id="task-buttons" class="btn-group-sm pull-right">
            <button id="btnSave" type="button" class="btn btn-success" >Save</button>
            <button id="btnNew" type="button" class="btn btn-info" >New</button>
            <button id="btnEdit" type="button" class="btn btn-primary" >Edit</button>
            <button id="btnDel" type="button" class="btn btn-danger" >Delete</button>
            <button id="btnCanx" type="button" class="btn btn-warning" >Cancel</button>
        </div><!-- task buttons -->

        <h1 id="page-title">@yield('title', 'Page Title') <small id="sub-title">@yield('subTitle')</small></h1>

    </div><!-- page-header -->

    <div id="content">
        <div id="tabs">
            <ul class="nav nav-tabs hidden-print">
                @section('Tabs')
                    <li class="active"><a href="#tab1" data-controller="t1">Tab1</a></li>
                    <li><a href="#tab2" data-controller="t2">Tab2</a></li>
                    <li><a href="#tab3" data-controller="t3">Tab3</a></li>
                    <li><a href="#tab4" data-controller="t4">Tab4</a></li>
                    @show
            </ul>

            <div class="panel-body">
                <div id="status"></div>
                <div id="editor">@yield('editor')</div>
                <div id="tab-content">@yield('content')</div>
            </div>
        </div>

    </div> <!-- content -->

    <x-footer/>

</div> <!-- wrapper -->
<script type="text/javascript" src="/js/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/js/datatables.min.js"></script>
{{--<script type="text/javascript" src="/js/jquery.tablesorter.js"></script>--}}
<script type="text/javascript" src="/js/jquery.PrintArea.js"></script>
<script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-tooltip.js"></script>
<script type="text/javascript" src="/js/ajaxtabs.js"></script>
<!-- page specific scripts -->
@yield('scripts')
</body>
</html>
