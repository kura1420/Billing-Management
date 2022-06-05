<table id="dg" class="easyui-datagrid" style="height: 100%;">
    <thead>
        <tr>
            <th data-options="field:'site'" sortable="true">Site</th>
            <th data-options="field:'host'" sortable="true">Host</th>
            <th data-options="field:'port'" sortable="true">Port</th>
            <th data-options="field:'user'" sortable="true">User</th>
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
        </tr>
    </thead>
</table>
<div id="tb" style="padding:2px 5px;">
    <input id="ss" class="easyui-searchbox" style="width:30%">
</div>