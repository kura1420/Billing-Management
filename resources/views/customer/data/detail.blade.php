<div class="easyui-tabs" data-options="fit:true">

    <div title="Other Contact">
        <table id="dgContact" class="easyui-datagrid" style="height: 55%;">
        </table>

        <div id="tbContact" style="padding:2px 5px;">
            <span class="easyui-tooltip" title="Create">
                <a id="btnAddContact" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-add',">Create</a>
            </span>
            
            <span class="easyui-tooltip" title="Accept">
                <a id="btnOkContact" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-ok',">Accept</a>
            </span>
            
            <span class="easyui-tooltip" title="Edit">
                <a id="btnEditContact" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-edit',">Edit</a>
            </span>
            
            <span class="easyui-tooltip" title="Cancel">
                <a id="btnCancelContact" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-cancel',">Cancel</a>
            </span>
            
            <span class="easyui-tooltip" title="Remove">
                <a id="btnRemoveContact" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-remove',">Remove</a>
            </span>
        </div>
    </div>

    <div title="Document">
        <table id="dgDocument" class="easyui-datagrid" style="height: 55%;">
            <thead>
                <tr>
                    <th data-options="field:'customer_contact_name'" sortable="true">Contact</th>
                    <th data-options="
                        field:'type',
                        formatter: function (value, index) {
                            return value.toUpperCase()
                        }
                        " sortable="true">Type</th>
                    <th data-options="field:'identity_number'" sortable="true">Number</th>
                    <th data-options="field:'identity_expired'" sortable="true">Expired</th>
                </tr>
            </thead>        
        </table>

        <div id="tbDocument" style="padding:2px 5px;">
            <span class="easyui-tooltip" title="Create">
                <a id="btnAddDocument" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-add',">Create</a>
            </span>
            
            <span class="easyui-tooltip" title="Edit">
                <a id="btnEditDocument" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-edit',">Edit</a>
            </span>
            
            <span class="easyui-tooltip" title="Remove">
                <a id="btnRemoveDocument" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-remove',">Remove</a>
            </span>
        </div>

        <div id="wDocument" class="easyui-window" title="Form Document" data-options="modal:true,closed:true," style="width:500px;height:400px;padding:10px;">
            <div class="easyui-layout" data-options="fit:true,border:false,">
                <form id="ffDocument" method="post" enctype="multipart/form-data">
                    <div data-options="region:'center'" style="padding:10px;">
                        <p style="display: none;">
                            <input name="d_id" id="d_id" class="easyui-textbox" data-options="label:'ID',width:400,required:false,labelAlign:'right',readonly:true,">
                        </p>
                        <p>
                            <input name="d_customer_contact_id" id="d_customer_contact_id" class="easyui-combobox" data-options="label:'Contact',width:400,required:true,labelAlign:'right',">
                        </p>
                        <p>
                            <input name="d_type" id="d_type" class="easyui-combobox" data-options="label:'Type',width:400,required:true,labelAlign:'right',">
                        </p>
                        <p>
                            <input name="d_file" id="d_file" class="easyui-filebox" data-options="label:'File',width:400,required:false,labelAlign:'right',">
                        </p>
                        <p>
                            <input name="d_identity_number" id="d_identity_number" class="easyui-textbox" data-options="label:'ID Number',width:400,required:true,labelAlign:'right',">
                        </p>
                        <p>
                            <input name="d_identity_expired" id="d_identity_expired" class="easyui-datebox" data-options="label:'ID Expired',width:400,required:false,labelAlign:'right',">
                        </p>
                    </div>
                    <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
                        <a id="btnPreviewDocument" class="easyui-linkbutton" href="javascript:void(0)">Preview File</a>
                        <a id="btnSaveDocument" class="easyui-linkbutton" data-options="iconCls:'icon-save'" href="javascript:void(0)">Save</a>
                        <a id="btnCancelDocument" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" href="javascript:void(0)">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>