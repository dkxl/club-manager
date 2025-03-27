<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <x-favicon/>
    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="/css/ocean.css" rel="stylesheet">
</head>
<body id="@yield('body_id', 'guest')">
<div id="wrapper" class="container-fluid">

    <div id="page-header" class="page-header hidden-print">
        <a class="navbar-brand" href="{{ url('/') }}">
            <x-application-logo/>
            {{ config('app.name', 'Laravel') }}
        </a>
    </div><!-- page-header -->

    <div id="content">
        {{ $slot }}
    </div> <!-- content -->

    @yield('footer-nav')

    <x-footer/>

</div> <!-- wrapper -->

</body>
</html>
