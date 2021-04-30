<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css?05114') }}" rel="stylesheet">
    <link href="{{ asset('css/waves.css?1') }}" rel="stylesheet">
    <link href="{{ asset('css/toastr.css?1') }}" rel="stylesheet" >
    <link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/chart.min.css') }}" rel="stylesheet">

    <!-- <link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet"> -->
</head>
<body class="">
    <div id="app">
        @guest
            <!-- form de logeo -->
            @yield('contentLogin')
        @else
            <!--Contenido con session iniciada  -->
            @yield('content')
        @endguest
    </div>

    <script src="{{ asset('js/app.js?02034') }}" ></script>
    <script src="{{ asset('js/waves.js') }}" ></script>
    <script src="{{ asset('js/toastr.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap-select.min.js?5') }}" ></script>
    <script src="{{ asset('js/chart.min.js') }}" ></script>
    <script src="{{ asset('js/xlsx.core.min.js') }}"type="text/javascript" ></script>
    <script src="{{ asset('js/Blob.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/FileSaver.js') }}" type="text/javascript"></script>
    <!-- <script lang="javascript" src="dist/xlsx.full.min.js"></script> -->
    <!-- <script src="{{ asset('js/echo.js') }}" ></script> -->
    <script src="https://js.pusher.com/7.0/pusher.min.js?5"></script>
    <!-- <script src="https://js.pusher.com/4.1/pusher.min.js"></script> -->
    @yield('scripts')
    <!-- <script src="{{ asset('js/bootstrap-select.min.js') }}" ></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.11/lodash.min.js"></script>
    <script type="text/javascript">
        Waves.init();
        Waves.attach('.btn-waves', ['waves-button', 'waves-float']);

    </script>   
</body>
</html>
