<?php
/**
 * master.blade.php
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
        @yield('banner')
    </div><!-- page-header -->

    <div id="content">
        @yield('content')
    </div> <!-- content -->

    @yield('footer-nav')

    <x-footer/>

</div> <!-- wrapper -->

<script type="text/javascript" src="/js/jquery-3.0.0.min.js"></script>
<script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
<script type="text/javascript" src="/js/datatables.min.js"></script>
{{--<script type="text/javascript" src="/js/jquery.tablesorter.js"></script>--}}
<script type="text/javascript" src="/js/jquery.PrintArea.js"></script>
<script type="text/javascript" src="/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/js/bootstrap-tooltip.js"></script>
<!-- page specific scripts -->
@yield('scripts')
</body>
</html>
