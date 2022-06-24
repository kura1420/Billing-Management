<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ asset('assets/jquery-easyui/themes/default/easyui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/jquery-easyui/themes/icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/jquery-easyui/themes/color.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/jquery-easyui/demo/demo.css') }}">

    @yield('addCss')

    <script src="{{ asset('assets/jquery-easyui/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/jquery-easyui/jquery.easyui.min.js') }}"></script>

    @yield('addJs')

    <script src="{{ asset('assets/pages/helper.js') }}"></script>

    <script>
        const URL_ROOT = '{{ url("/") }}'
        const URL_REST = '{{ url("/rest") }}'
        const URL_MOBILE = '{{ url("/m") }}'
    </script>
</head>
<body>
    @yield('content')
</body>
</html>