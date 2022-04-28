@extends('layouts.default')

@section('content')
<div class="easyui-layout" data-options="fit:true">
    <div id="up" data-options="region:'east',split:true,hideCollapsedContent:false,collapsed:true" title="User Profile" style="width:10%;padding:10px;">
        <a id="btnLogout" href="javascript:void(0)" class="easyui-linkbutton">Logout</a>
    </div>
    <div id="mn" data-options="region:'west',collapsed:false," title="Main Menu" style="width:10%;padding:10px">
        <ul id="tt" class="easyui-tree"></ul>
    </div>

    <div data-options="region:'center'">
        <div id="p"></div>
    </div>
</div>

<script src="{{ asset('/assets/skp/app.js') }}"></script>
@endsection