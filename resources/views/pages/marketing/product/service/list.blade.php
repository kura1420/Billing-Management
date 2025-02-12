<table id="dg" class="easyui-datagrid" style="height: 100%;">
    <thead>
        <tr>
            <th data-options="field:'product_type_id'" sortable="false">Type</th>
            <th data-options="field:'code'" sortable="true">Code</th>
            <th data-options="field:'name'" sortable="true">Name</th>
            <th data-options="
                field:'price',
                align:'right',
                formatter: function(value, row) {
                    return IDRFormatter(value)
                }" sortable="true">Price</th>
            <th data-options="
                field:'active',
                align:'center',
                formatter: function(value, row, index) {
                    return value == 1 ? 'Active' : 'No Active'
                },
                styler: function(value,row,index) {
                    return value !== 1 ? 'background-color:red;color:white;' : ''
                },
            "
            sortable="true">Active</th>
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
<div id="tb" style="padding:2px 5px;">
    <input id="ss" class="easyui-searchbox" style="width:30%">
</div>