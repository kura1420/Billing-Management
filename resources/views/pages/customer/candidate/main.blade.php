@extends('layouts.default')

@section('content')
<div class="easyui-panel" data-options="fit:true,border:false,">
    <div id="tbs" class="easyui-tabs" style="width:100%;height:100%;"
        data-options="border:false,tools:'#tab-tools'">
        <div title="List">
            @include('pages.customer.candidate.list')
        </div>

        <div title="Create">
            @include('pages.customer.candidate.create')
        </div>

        <div title="Edit">
            @include('pages.customer.candidate.edit')
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
        
        <span class="easyui-tooltip" title="Update">
            <a id="btnUpdate" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-ok',disabled:true"></a>
        </span>
        
        <!-- <span class="easyui-tooltip" title="Remove">
            <a id="btnRemove" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-remove'"></a>
        </span> -->
    </div>
</div>

<script src="{{ asset('assets/pages/customer/candidate.js') }}"></script>
@endsection