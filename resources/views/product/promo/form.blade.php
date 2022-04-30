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
                    <input name="type" id="type" class="easyui-combobox" data-options="label:'Type',width:800,required:true,labelAlign:'right',disabled:true,">
                </p>
                <p>
                    <input name="product_type_id" id="product_type_id" class="easyui-combobox" data-options="label:'Product Type',width:800,required:false,labelAlign:'right',labelWidth:120,disabled:true,">
                </p>
                <p>
                    <input name="product_service_id" id="product_service_id" class="easyui-combobox" data-options="label:'Product Service',width:800,required:false,labelAlign:'right',labelWidth:120,disabled:true,valueField:'id',textField:'name',">
                </p>
                <p>
                    <input name="discount" id="discount" class="easyui-numberbox" data-options="label:'Discount %',width:800,required:true,labelAlign:'right',disabled:true,min:0,max:100,">
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
        <table id="dgArea" class="easyui-datagrid" style="height: 55%;">
            <thead>
                <tr>
                    <th data-options="field:'area_name'" sortable="true">Area</th>
                    <th data-options="field:'product_type_name'" sortable="true">Product Type</th>
                    <th data-options="field:'product_service_name'" sortable="true">Product Service</th>
                    <th data-options="
                        field:'active',
                        align:'center',
                        formatter: function(value, row, index) {
                            return value == 1 ? 'Active' : 'No Active'
                        },
                    " sortable="true">Active</th>
                </tr>
            </thead>
        </table>

        <div id="tbArea" style="padding:2px 5px;">
            <span class="easyui-tooltip" title="Create">
                <a id="btnAddArea" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-add',">Create</a>
            </span>
            
            <span class="easyui-tooltip" title="Edit">
                <a id="btnEditArea" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-edit',">Edit</a>
            </span>
            
            <span class="easyui-tooltip" title="Remove">
                <a id="btnRemoveArea" href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-remove',">Remove</a>
            </span>
        </div>

        <div id="wArea" class="easyui-window" title="Form Area" data-options="modal:true,closed:true," style="width:800px;height:400px;padding:10px;">
            <div class="easyui-layout" data-options="fit:true,border:false,">
                <form id="ffArea" method="post" enctype="multipart/form-data">
                    <div data-options="region:'center'" style="padding:10px;">
                        <p>
                            <input name="a_area_id" id="a_area_id" class="easyui-combobox" data-options="label:'Area',width:600,required:true,labelAlign:'right',labelWidth:100,">
                        </p>
                        <p>
                            <input name="a_area_product" id="a_area_product" class="easyui-combogrid" data-options="label:'Area Product',width:600,required:true,labelAlign:'right',labelWidth:100,">
                        </p>
                        <p>
                            <input name="a_active" id="a_active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right',labelWidth:100," value="1">
                        </p>
                    </div>
                    <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
                        <a id="btnOkArea" class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)">Ok</a>
                        <a id="btnCancelArea" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" href="javascript:void(0)">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>