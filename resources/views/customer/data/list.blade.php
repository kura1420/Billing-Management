<table id="dg" class="easyui-datagrid" style="height: 100%;">
    <thead>
        <tr>
            <th data-options="field:'code'" sortable="true">Code</th>
            <th data-options="field:'member_at'" sortable="true">Member At</th>
            <th data-options="field:'suspend_at'" sortable="true">Suspend At</th>
            <th data-options="field:'terminate_at'" sortable="true">Terminate At</th>
            <th data-options="field:'dismantle_at'" sortable="true">Dismantle At</th>
            <th data-options="
                field:'active',
                align:'center',
                formatter: function(value, row, index) {
                    return value == 1 ? 'Active' : 'No Active'
                },
                styler: function(value,row,index) {
                    return value == 1 ? 'background-color:white;' : 'background-color:red;color:white'
                },
            "
            sortable="true">Active</th>
        </tr>
    </thead>
</table>
<div id="tb" style="padding:2px 5px;">
    <input id="ss" class="easyui-searchbox" style="width:30%">
</div>