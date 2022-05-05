<form id="ff" method="post">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,disabled:true,">
    </p>
    
    <p>
        <input name="code" id="code" class="easyui-textbox" data-options="label:'Code',width:800,required:true,labelAlign:'right',max:20,readonly:true,">
    </p>
    <p>
        <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,required:true,labelAlign:'right',max:255,disabled:true,">
    </p>
    <p>
        <input name="desc" id="desc" class="easyui-textbox" data-options="label:'Desc',width:800,required:false,labelAlign:'right',disabled:true,multiline:true," style="height: 180px;">
    </p>
    <p>
        <input name="notif" id="notif" class="easyui-numberbox" data-options="label:'Notif',width:800,required:true,labelAlign:'right',max:30,disabled:true,">
    </p>
    <p>
        <input name="suspend" id="suspend" class="easyui-numberbox" data-options="label:'Suspend',width:800,required:true,labelAlign:'right',max:30,disabled:true,">
    </p>
    <p>
        <input name="terminated" id="terminated" class="easyui-numberbox" data-options="label:'Terminated',width:800,required:true,labelAlign:'right',max:30,disabled:true,">
    </p>
    <p>
        <input name="repeat" id="repeat" class="easyui-switchbutton" data-options="label:'Repeat',labelAlign:'right',disabled:true" value="1">
    </p>
    <p>
        <input name="active" id="active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right',disabled:true" value="1">
    </p>
    <p>
        <input name="member_end_active" id="member_end_active" class="easyui-numberbox" data-options="label:'Member End Active',width:800,required:true,labelAlign:'right',max:30,disabled:true,labelWidth:150,">
    </p>
</form>

<div class="easyui-panel" id="pProduct" title="List Product" data-options="fit:true,">
    <table id="dgProduct" class="easyui-datagrid" style="height: 45%;">
        <thead>
            <tr>
                <th data-options="field:'product_type_name'," sortable="true">Type</th>
                <th data-options="field:'product_service_name'," sortable="true">Service</th>
            </tr>
        </thead>
    </table>
    <div id="tbProduct" style="padding:2px 5px;">
        <span class="easyui-tooltip" title="Create">
            <a id="btnAddProduct" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-add',disabled:true,">Create</a>
        </span>
        
        <span class="easyui-tooltip" title="Edit">
            <a id="btnEditProduct" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-edit',disabled:true,">Edit</a>
        </span>
        
        <span class="easyui-tooltip" title="Remove">
            <a id="btnRemoveProduct" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-remove',disabled:true,">Remove</a>
        </span>
    </div>

    <div id="wProduct" class="easyui-window" title="Form Product" data-options="modal:true,closed:true," style="width:500px;height:250px;padding:10px;">
        <div class="easyui-layout" data-options="fit:true,border:false,">
            <form id="ffProduct" method="post">
                <div data-options="region:'center'" style="padding:10px;">
                    <p>
                        <input name="product_type_id" id="product_type_id" class="easyui-combobox" data-options="label:'Type',width:400,required:true,labelAlign:'right',">
                    </p>
                    <p>
                        <input name="product_service_id" id="product_service_id" class="easyui-combobox" data-options="label:'Service',width:400,required:true,labelAlign:'right',valueField:'id',textField:'name',">
                    </p>
                </div>
                <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
                    <a id="btnOkProduct" class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)">Ok</a>
                    <a id="btnCancelProduct" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" href="javascript:void(0)">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>