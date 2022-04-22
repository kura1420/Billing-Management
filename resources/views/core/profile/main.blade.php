<div id="tbs" class="easyui-tabs" style="width:100%;height:100%;"
    data-options="border:false,tools:'#tab-tools'">
    <div title="Form" style="padding: 10px;">
        @include('core.profile.form')
    </div>
</div>
<div id="tab-tools">    
    <span class="easyui-tooltip" title="Save">
        <a id="btnSave" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-save'"></a>
    </span>
</div>

<script src="{{ asset('assets/skp/core/profile.js') }}"></script>