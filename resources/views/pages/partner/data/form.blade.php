<form id="ff" method="post" enctype="multipart/form-data" style="padding-bottom: 5%;">
    <p style="display: none;">
        <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,disabled:true,">
    </p>
    
    <table width="100%" border="0">
        <tr>
            <td>
                <p>
                    <input name="code" id="code" class="easyui-textbox" data-options="label:'Code',width:800,required:true,labelAlign:'right',max:255,readonly:true,">
                </p>
                <p>
                    <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,required:true,labelAlign:'right',max:255,disabled:true,">
                </p>
                <p>
                    <input name="alias" id="alias" class="easyui-textbox" data-options="label:'Alias',width:800,required:false,labelAlign:'right',max:100,disabled:true,">
                </p>
                <p>
                    <input name="type" id="type" class="easyui-combo" data-options="label:'Type',width:800,required:true,labelAlign:'right',max:100,disabled:true,">
                </p>
                <p>
                    <input name="telp" id="telp" class="easyui-numberbox" data-options="label:'Telp',width:800,required:false,labelAlign:'right',disabled:true,">
                </p>
                <p>
                    <input name="email" id="email" class="easyui-textbox" data-options="label:'Email',width:800,required:true,labelAlign:'right',max:100,disabled:true,validType:'email',">
                </p>
                <p>
                    <input name="fax" id="fax" class="easyui-textbox" data-options="label:'Fax',width:800,required:false,labelAlign:'right',max:100,disabled:true,">
                </p>
                <p>
                    <input name="handphone" id="handphone" class="easyui-numberbox" data-options="label:'Handphone',width:800,required:false,labelAlign:'right',disabled:true,">
                </p>
            </td>
            <td>
                <p>
                    <input name="join" id="join" class="easyui-datebox" data-options="label:'Join',width:800,required:false,labelAlign:'right',disabled:true,">
                </p>
                <p>
                    <input name="leave" id="leave" class="easyui-datebox" data-options="label:'Leave',width:800,required:false,labelAlign:'right',disabled:true,">
                </p>
                <p>
                    <input name="brand" id="brand" class="easyui-textbox" data-options="label:'Brand',width:800,required:false,labelAlign:'right',max:100,disabled:true,">
                </p>
                <p>
                    <input name="user_id_reff" id="user_id_reff" class="easyui-combobox" data-options="label:'User Reff.',width:800,required:false,labelAlign:'right',max:100,disabled:true,">
                </p>
                <p>
                    <input name="logo" id="logo" accept="image/*" class="easyui-filebox" data-options="label:'Logo:',width:400,labelAlign:'right',">
                    <a id="btnPreview" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true">Preview</a>
                </p>
                <p>
                    <input name="active" id="active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right',disabled:true" value="1">
                </p>
                <p>
                    <input name="address" id="address" class="easyui-textbox" data-options="label:'Address',width:800,required:true,labelAlign:'right',multiline:true," style="height: 100px;">
                </p>
            </td>
        </tr>
    </table>
</form>

<div class="easyui-tabs" data-options="fit:true">
    <div title="Contact">
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
                    <th data-options="field:'name'" sortable="true">Name</th>
                    <th data-options="field:'start'" sortable="true">Start</th>
                    <th data-options="field:'end'" sortable="true">End</th>
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
                            <input name="d_name" id="d_name" class="easyui-textbox" data-options="label:'Name',width:400,required:true,labelAlign:'right',">
                        </p>
                        <p>
                            <input name="d_desc" id="d_desc" class="easyui-textbox" data-options="label:'Desc',width:400,required:true,labelAlign:'right',">
                        </p>
                        <p>
                            <input name="d_file" id="d_file" class="easyui-filebox" data-options="label:'File',width:400,required:false,labelAlign:'right',">
                        </p>
                        <p>
                            <input name="d_start" id="d_start" class="easyui-datebox" data-options="label:'Start',width:400,required:true,labelAlign:'right',">
                        </p>
                        <p>
                            <input name="d_end" id="d_end" class="easyui-datebox" data-options="label:'End',width:400,required:true,labelAlign:'right',">
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