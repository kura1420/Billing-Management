<form id="ff" method="post" enctype="multipart/form-data">
    <table border="0" width="100%">
        <tr>
            <td>
                <p style="display: none;">
                    <input name="id" id="id" class="easyui-textbox" data-options="label:'ID',width:500,required:false,labelAlign:'right',max:36,readonly:true,">
                </p>
                
                <p>
                    <input name="code" id="code" class="easyui-textbox" data-options="label:'Code',width:800,required:true,labelAlign:'right',max:20,readonly:true,">
                </p>
                <p>
                    <input name="name" id="name" class="easyui-textbox" data-options="label:'Name',width:800,required:true,labelAlign:'right',max:255,disabled:true,">
                </p>
                <p>
                    <input name="start" id="start" class="easyui-datebox" data-options="label:'Start',width:800,required:true,labelAlign:'right',disabled:true,">
                </p>
                <p>
                    <input name="end" id="end" class="easyui-datebox" data-options="label:'End',width:800,required:true,labelAlign:'right',disabled:true,">
                </p>
                <p>
                    <input name="desc" id="desc" class="easyui-textbox" data-options="label:'Desc',width:800,required:true,labelAlign:'right',disabled:true,multiline:true," style="height: 180px;">
                </p>
                <p>
                    <input name="image" id="image" accept="image/*" class="easyui-filebox" data-options="label:'Image:',width:700,labelAlign:'right',disabled:true,">
                    <a id="btnPreview" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true">Preview</a>
                </p>
                <p>
                    <input name="active" id="active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right',disabled:true" value="1">
                </p>
            </td>
            <td style="vertical-align: top;">
                <p>
                    <input name="type" id="type" class="easyui-combobox" data-options="label:'Type',width:800,required:true,labelAlign:'right',disabled:true,labelWidth:120,">
                </p>
                <p>
                    <input name="product_type_id" id="product_type_id" class="easyui-combobox" data-options="label:'Product Type',width:800,required:false,labelAlign:'right',labelWidth:120,disabled:true,">
                </p>
                <p>
                    <input name="product_service_id" id="product_service_id" class="easyui-combobox" data-options="label:'Product Service',width:800,required:false,labelAlign:'right',labelWidth:120,disabled:true,valueField:'id',textField:'name',">
                </p>
                <p>
                    <input name="discount" id="discount" class="easyui-numberbox" data-options="label:'Discount %',width:800,required:true,labelAlign:'right',disabled:true,min:0,max:100,labelWidth:120,">
                </p>
                <p>
                    <input name="until_payment" id="until_payment" class="easyui-numberbox" data-options="label:'Unti Payment',width:800,required:true,labelAlign:'right',disabled:true,min:1,max:12,labelWidth:120">
                </p>
            </td>
        </tr>
    </table>
</form>

<div class="easyui-tabs" data-options="fit:true">
    <div title="Area">
        <table id="dgArea" class="easyui-datagrid" style="height: 76%;">
        </table>

        <div id="tbArea" style="padding:2px 5px;">
            <span class="easyui-tooltip" title="Create">
                <a id="btnAddArea" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-add',">Create</a>
            </span>
            
            <span class="easyui-tooltip" title="Accept">
                <a id="btnOkArea" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-ok',">Accept</a>
            </span>
            
            <span class="easyui-tooltip" title="Edit">
                <a id="btnEditArea" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-edit',">Edit</a>
            </span>
            
            <span class="easyui-tooltip" title="Cancel">
                <a id="btnCancelArea" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-cancel',">Cancel</a>
            </span>
            
            <span class="easyui-tooltip" title="Remove">
                <a id="btnRemoveArea" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-remove',">Remove</a>
            </span>
        </div>
    </div>
</div>