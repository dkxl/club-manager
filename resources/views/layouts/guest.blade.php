<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <x-favicon/>
    <link href="/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="/css/ocean.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <x-application-logo/>
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-4">
           {{ $slot }}
        </main>
        <x-footer/>
    </div>
</body>
</html>
