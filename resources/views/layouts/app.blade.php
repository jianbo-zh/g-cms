<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" content="{{ web_api_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- 页面加载进度条 -->
    <link href="{{ asset('css/pace-progress.css') }}" rel="stylesheet">
    <script src="{{ asset('js/pace-progress.js') }}"></script>

    <!-- 主要样式 -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">

@include('layouts.header')

<div class="app-body">

    @include('layouts.sidebar')

    <main class="main">

        @include('layouts.breadcrumb')

        @yield('content')

    </main>

    @include('layouts.aside')

</div>

@include('layouts.footer')

<script src="{{ asset('js/app.js') }}"></script>

@stack('scripts')

</body>
</html>