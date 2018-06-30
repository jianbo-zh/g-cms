<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="/js/app.js"></script>
    <script>
        Echo.private(`App.User`)
            .listen('App.User', (e) => {
                alert('232432333');
            });
    </script>
</head>