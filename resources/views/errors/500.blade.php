<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title>500</title>
    <!-- 主要样式 -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="app flex-row align-items-center">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="clearfix">
                <h1 class="float-left display-3 mr-4">500</h1>
                <h4 class="pt-3">Hi, we have a problem!</h4>
                <p class="text-muted">[{{ $code }}] {{ $message }}</p>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>