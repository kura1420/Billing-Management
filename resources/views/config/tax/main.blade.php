<div id="tbs" class="easyui-tabs" style="width:100%;height:100%;"
    data-options="border:false,tools:'#tab-tools'">
    <div title="List">
        @include('config.tax.list')
    </div>

    <div title="Form">
        @include('config.tax.form')
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
    
    <span class="easyui-tooltip" title="Duplicate">
        <a id="btnCopy" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-copy'"></a>
    </span>
    
    <span class="easyui-tooltip" title="Remove">
        <a id="btnRemove" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-remove'"></a>
    </span>
</div>

<script src="{{ asset('assets/skp/config/tax.js') }}"></script>