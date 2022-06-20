@extends('layouts.default')

@section('content')
<div class="easyui-panel" data-options="fit:true,border:false,">
    <div id="tbs" class="easyui-tabs" style="width:100%;height:100%;"
        data-options="border:false,tools:'#tab-tools'">

        <div title="List">
            @include('pages.core.log.list')
        </div>

        <div title="Show">
            @include('pages.core.log.show')
        </div>
    </div>
    <div id="tab-tools">
        <span class="easyui-tooltip" title="Download">
            <a id="btnDownload" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-download'"></a>
        </span>
    </div>
</div>

<script src="{{ asset('assets/skp/core/log.js') }}"></script>
@endsection