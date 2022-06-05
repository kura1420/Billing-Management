<div class="easyui-tabs" data-options="fit:true">

    <div title="Product">
        <table id="dgProduct" class="easyui-datagrid" style="height: 55%;">
            <thead>
                <tr>
                    <th data-options="field:'provinsi_name'" sortable="true">Provinsi</th>
                    <th data-options="field:'city_name'" sortable="true">City</th>
                    <th data-options="field:'product_type_name'" sortable="true">Type</th>
                    <th data-options="field:'product_service_name'" sortable="true">Service</th>
                    <th data-options="
                        field:'price_sub',
                        align:'right',
                        formatter: function(value, row) {
                            return IDRFormatter(value)
                        }
                    " sortable="true">Price Sub.</th>
                    <th data-options="
                        field:'price_ppn',
                        align:'right',
                        formatter: function(value, row) {
                            return IDRFormatter(value)
                        }
                    " sortable="true">Price PPN</th>
                    <th data-options="
                        field:'price_total',
                        align:'right',
                        formatter: function(value, row) {
                            return IDRFormatter(value)
                        }
                    " sortable="true">Price Total</th>
                    <th data-options="
                        field:'active',
                        align:'center',
                        formatter: function(value, row, index) {
                            return value == true ? 'Active' : 'No Active'
                        },
                    " sortable="true">Active</th>
                    <th data-options="
                        field:'created_at',
                        formatter: function(value, row) {
                            return TimestampString2Datetime(value)
                        },
                    " sortable="true">Created At</th>
                    <th data-options="
                        field:'updated_at',
                        formatter: function(value, row) {
                            return TimestampString2Datetime(value)
                        },
                    " sortable="true">Updated At</th>
                </tr>
            </thead>
        </table>

        <div id="tbProduct" style="padding:2px 5px;">
            <span class="easyui-tooltip" title="Create">
                <a id="btnAddProduct" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-add',">Create</a>
            </span>
            
            <span class="easyui-tooltip" title="Edit">
                <a id="btnEditProduct" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-edit',">Edit</a>
            </span>
            
            <span class="easyui-tooltip" title="Remove">
                <a id="btnRemoveProduct" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-remove',">Remove</a>
            </span>
        </div>

        <div id="wProduct" class="easyui-window" title="Form Product" data-options="modal:true,closed:true," style="width:500px;height:400px;padding:10px;">
            <div class="easyui-layout" data-options="fit:true,border:false,">
                <form id="ffProduct" method="post">
                    <div data-options="region:'center'" style="padding:10px;">
                        <p>
                            <input name="p_provinsi_id" id="p_provinsi_id" class="easyui-combobox" data-options="label:'Provinsi',width:400,required:true,labelAlign:'right',">
                        </p>
                        <p>
                            <input name="p_city_id" id="p_city_id" class="easyui-combobox" data-options="label:'City',width:400,required:true,labelAlign:'right',valueField:'id',textField:'name',">
                        </p>
                        <p>
                            <input name="p_product_type_id" id="p_product_type_id" class="easyui-combobox" data-options="label:'Type',width:400,required:true,labelAlign:'right',">
                        </p>
                        <p>
                            <input name="p_product_service_id" id="p_product_service_id" class="easyui-combobox" data-options="label:'Service',width:400,required:true,labelAlign:'right',valueField:'id',textField:'name',">
                        </p>
                        <p>
                            <input name="p_active" id="p_active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right'," value="1">
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

    <div title="Customer">
        <table id="dgCustomer" class="easyui-datagrid" style="height: 55%;">
            <thead>
                <tr>
                    <th data-options="field:'provinsi_name'" sortable="true">Provinsi</th>
                    <th data-options="field:'city_name'" sortable="true">City</th>
                    <th data-options="field:'product_type_name'" sortable="true">Product Type</th>
                    <th data-options="field:'product_service_name'" sortable="true">Product Service</th>
                    <th data-options="field:'customer_type_name'" sortable="true">Customer Type</th>
                    <th data-options="field:'customer_segment_name'" sortable="true">Customer Segment</th>
                    <th data-options="
                        field:'active',
                        align:'center',
                        formatter: function(value, row, index) {
                            return value == true ? 'Active' : 'No Active'
                        },
                    " sortable="true">Active</th>
                    <th data-options="
                        field:'created_at',
                        formatter: function(value, row) {
                            return TimestampString2Datetime(value)
                        },
                    " sortable="true">Created At</th>
                    <th data-options="
                        field:'updated_at',
                        formatter: function(value, row) {
                            return TimestampString2Datetime(value)
                        },
                    " sortable="true">Updated At</th>
                </tr>
            </thead>
        </table>
        
        <div id="tbCustomer" style="padding:2px 5px;">
            <span class="easyui-tooltip" title="Create">
                <a id="btnAddCustomer" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-add',">Create</a>
            </span>
            
            <span class="easyui-tooltip" title="Edit">
                <a id="btnEditCustomer" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-edit',">Edit</a>
            </span>
            
            <span class="easyui-tooltip" title="Remove">
                <a id="btnRemoveCustomer" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-remove',">Remove</a>
            </span>
        </div>

        <div id="wCustomer" class="easyui-window" title="Form Customer" data-options="modal:true,closed:true," style="width:800px;height:400px;padding:10px;">
            <div class="easyui-layout" data-options="fit:true,border:false,">
                <form id="ffCustomer" method="post">
                    <div data-options="region:'center'" style="padding:10px;">
                        <p>
                            <input name="c_area_product" id="c_area_product" class="easyui-combogrid" data-options="label:'Area Product',width:600,required:true,labelAlign:'right',labelWidth:100,">
                        </p>
                        <p>
                            <input name="c_customer_type_id" id="c_customer_type_id" class="easyui-combobox" data-options="label:'Type',width:600,required:true,labelAlign:'right',">
                        </p>
                        <p>
                            <input name="c_customer_segment_id" id="c_customer_segment_id" class="easyui-combobox" data-options="label:'Segment',width:600,required:true,labelAlign:'right',valueField:'id',textField:'name',">
                        </p>
                        <p>
                            <input name="c_active" id="c_active" class="easyui-switchbutton" data-options="label:'Active',labelAlign:'right'," value="1">
                        </p>
                    </div>
                    <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
                        <a id="btnOkCustomer" class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)">Ok</a>
                        <a id="btnCancelCustomer" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" href="javascript:void(0)">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div title="Router">
        <table id="dgRouter" class="easyui-datagrid" style="height: 55%;">
            <thead>
                <tr>
                    <th data-options="field:'site'" sortable="true">Site</th>
                    <th data-options="field:'host'" sortable="true">Host</th>
                    <th data-options="field:'desc'" sortable="true">Desc</th>
                    <th data-options="
                        field:'active',
                        align:'center',
                        formatter: function(value, row, index) {
                            return value == true ? 'Active' : 'No Active'
                        },
                    " sortable="true">Active</th>
                </tr>
            </thead>
        </table>
        
        <div id="tbRouter" style="padding:2px 5px;">
            <span class="easyui-tooltip" title="Create">
                <a id="btnAddRouter" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-add',">Create</a>
            </span>
            
            <span class="easyui-tooltip" title="Edit">
                <a id="btnEditRouter" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-edit',">Edit</a>
            </span>
            
            <span class="easyui-tooltip" title="Remove">
                <a id="btnRemoveRouter" href="javascript:void(0)" class="easyui-linkbutton" data-options="disabled:true,plain:true,iconCls:'icon-remove',">Remove</a>
            </span>
        </div>

        <div id="wRouter" class="easyui-window" title="Form Router" data-options="modal:true,closed:true," style="width:800px;height:400px;padding:10px;">
            <div class="easyui-layout" data-options="fit:true,border:false,">
                <form id="ffRouter" method="post">
                    <div data-options="region:'center'" style="padding:10px;">
                        <p>
                            <input name="r_router" id="r_router" class="easyui-combogrid" data-options="label:'Router Site',width:600,required:true,labelAlign:'right',labelWidth:100,">
                        </p>
                    </div>
                    <div data-options="region:'south',border:false" style="text-align:right;padding:5px 0 0;">
                        <a id="btnOkRouter" class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)">Ok</a>
                        <a id="btnCancelRouter" class="easyui-linkbutton" data-options="iconCls:'icon-cancel'" href="javascript:void(0)">Cancel</a>
                    </div>
                </form>
            </div>
        </div>        
    </div>
</div>