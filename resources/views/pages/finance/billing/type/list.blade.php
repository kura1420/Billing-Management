<table id="dg" class="easyui-datagrid" style="height: 100%;">
    <thead>
        <tr>
            <th data-options="field:'code'" sortable="true">Code</th>
            <th data-options="field:'name'" sortable="true">Name</th>
            <th data-options="field:'notif'" sortable="true">Notif</th>
            <th data-options="field:'suspend'" sortable="true">Suspend</th>
            <th data-options="field:'terminated'" sortable="true">Terminated</th>
            <th data-options="field:'member_end_active'" sortable="true">Member End Actived</th>
            <th data-options="
                field:'repeat',
                formatter: function (value, row, index) {
                    return value == 1 ? 'Yes' : 'No'
                }
            " sortable="true">Repeat</th>
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