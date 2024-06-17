<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Testing</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">

    </head>
    <body>
        <div class="container">
            <h1>Testing laravel echo redis socket.io broadcasts</h1>

            <div id="notification"></div>
        </div>
    </body>

    {{-- Base Scripts --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>

    <script type="text/javascript">
        var i = 0;
        window.Echo.channel('user-channel')
                   .listen('.test.user-event', (data) => {
                        i++;
                        console.log(data);
                        $("#notification").append('<div class="alert alert-success">'+i+'.'+data.message+'</div>');
        });
        window.Echo.channel('user-leagues')
            .listen('.LeagueCharPickEvent', (data) => {
                i++;
                $("#notification").append('<div class="alert alert-success">'+i+'.'+data.league.shortname+'</div>');
        });


    </script>
</html>
