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

    <script src="{{ asset('assets/jquery-easyui/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/jquery-easyui/jquery.easyui.min.js') }}"></script>

    <script>
        const URL_ROOT = '{{ url("/") }}'
        const URL_REST = '{{ url("/rest") }}'
    </script>
    <script src="{{ asset('/assets/skp/app.js') }}"></script>
</head>
<body>
    <div class="easyui-layout" data-options="fit:true">
        <div id="up" data-options="region:'east',split:true,hideCollapsedContent:false,collapsed:true" title="User Profile" style="width:10%;padding:10px;">
            <a id="btnLogout" href="javascript:void(0)" class="easyui-linkbutton">Logout</a>
        </div>
        <div id="mn" data-options="region:'west',collapsed:true," title="Main Menu" style="width:10%;padding:10px">
            <ul id="tt" class="easyui-tree"></ul>
        </div>
        
        <div data-options="region:'center'">
            <div class="easyui-panel" id="p" data-options="border:false,fit:true,"></div>
        </div>
    </div>
</body>
</html>