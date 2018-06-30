<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ web_api_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/pace-progress.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/pace-progress.js') }}"></script>

    @stack('styles')
</head>
<body class="app flex-row align-items-center">

@yield('content')


<!-- Bootstrap and necessary plugins-->
<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')

</body>
</html>