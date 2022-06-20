@extends('layouts.default')

@section('content')
<div class="easyui-panel" data-options="fit:true,border:false,">
    <div id="tbs" class="easyui-tabs" style="width:100%;height:100%;"
        data-options="border:false,tools:'#tab-tools'">
        <div title="List">
            @include('pages.service.router_site.list')
        </div>

        <div title="Form">
            @include('pages.service.router_site.form')
        </div>
    </div>
    <div id="tab-tools">
        <span class="easyui-tooltip" title="Create">
            <a id="btnAdd" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-add'"></a>
        </span>
        
        <span class="easyui-tooltip" title="Save">
            <a id="btnSave" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-save',disabled:true"></a>
        </span>
        
        <span class="easyui-tooltip" title="Edit">
            <a id="btnEdit" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-edit'"></a>
        </span>
        
        <span class="easyui-tooltip" title="Test Connection">
            <a id="btnTestConnection" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-ok',disabled:true"></a>
        </span>
        
        <span class="easyui-tooltip" title="Duplicate">
            <a id="btnCopy" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-copy'"></a>
        </span>
        
        <span class="easyui-tooltip" title="Remove">
            <a id="btnRemove" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-remove'"></a>
        </span>
    </div>
</div>

<script src="{{ asset('assets/skp/service/router_site.js') }}"></script>
@endsection