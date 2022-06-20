@extends('layouts.default')

@section('content')
    @if (session()->get('user_login'))
    <div class="easyui-layout" data-options="fit:true">
        <div data-options="region:'east',split:true,hideCollapsedContent:false,collapsed:true" title="User Profile" style="width:15%;">
            <table class="easyui-propertygrid" id="up">
            </table>
        </div>
        <div id="mn" data-options="region:'west',collapsed:false," title="Main Menu" style="width:10%;padding:10px">
            <ul id="tt" class="easyui-tree"></ul>
        </div>

        <div data-options="region:'center'">
            <div id="p"></div>
        </div>
    </div>

    <script src="{{ asset('/assets/skp/app.js') }}"></script>
    @else
        @include('pages.auth.main')
    @endif
@endsection