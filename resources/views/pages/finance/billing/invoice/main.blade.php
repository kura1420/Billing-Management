@extends('layouts.default')

@section('content')
<div class="easyui-panel" data-options="fit:true,border:false,">
    <div id="tbs" class="easyui-tabs" style="width:100%;height:100%;"
        data-options="border:false,tools:'#tab-tools'">
        <div title="List">
            @include('pages.finance.billing.invoice.list')
        </div>

        <div title="Form">
            @include('pages.finance.billing.invoice.form')
        </div>
    </div>
    <div id="tab-tools">
        <span class="easyui-tooltip" title="Resend">
            <a id="btnResend" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-send_email',disabled:true"></a>
        </span>
        
        <span class="easyui-tooltip" title="Verif">
            <a id="btnSave" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-ok',disabled:true"></a>
        </span>
        
        <span class="easyui-tooltip" title="Manual Payment">
            <a id="btnEdit" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-edit',disabled:true"></a>
        </span>
        
        <span class="easyui-tooltip" title="Cancel">
            <a id="btnCancel" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cancel',disabled:true"></a>
        </span>
        
        <span class="easyui-tooltip" title="Unsuspend">
            <a id="btnUnsuspend" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-undo',disabled:true,"></a>
        </span>
    </div>
</div>

<script src="{{ asset('assets/skp/billing/invoice.js') }}"></script>
@endsection