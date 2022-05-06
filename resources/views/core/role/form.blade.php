<form id="ff" method="post">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,readonly:true,">
    </p>
    
    <p>
        <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,required:true,labelAlign:'right',max:255,disabled:true,">
    </p>
    <p>
        <input name="desc" id="desc" class="easyui-textbox" data-options="label:'Desc',width:800,required:false,labelAlign:'right',disabled:true,multiline:true," style="height: 100px;">
    </p>
    <p>
        <input name="active" id="active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right',disabled:true" value="1">
    </p>
</form>

<div class="easyui-tabs" data-options="fit:true">

    <div title="Departement">
        <table id="dgDepartement" class="easyui-datagrid" style="height: 76%;">
        </table>

        <div id="tbDepartement" style="padding:2px 5px;">
            <span class="easyui-tooltip" title="Create">
                <a id="btnAddDepartement" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-add',">Create</a>
            </span>
            
            <span class="easyui-tooltip" title="Accept">
                <a id="btnOkDepartement" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-ok',">Accept</a>
            </span>
            
            <span class="easyui-tooltip" title="Edit">
                <a id="btnEditDepartement" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-edit',">Edit</a>
            </span>
            
            <span class="easyui-tooltip" title="Cancel">
                <a id="btnCancelDepartement" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-cancel',">Cancel</a>
            </span>
            
            <span class="easyui-tooltip" title="Remove">
                <a id="btnRemoveDepartement" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-remove',">Remove</a>
            </span>
        </div>
    </div>

</div>