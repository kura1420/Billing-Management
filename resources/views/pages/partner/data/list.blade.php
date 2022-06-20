<table id="dg" class="easyui-datagrid" style="height: 100%;">
    <thead>
        <tr>
            <th data-options="field:'code'" sortable="true">Code</th>
            <th data-options="field:'name'" sortable="true">Name</th>
            <th data-options="field:'alias'" sortable="true">Alias</th>
            <th data-options="field:'type'" sortable="true">Type</th>
            <th data-options="field:'telp'" sortable="true">Telp</th>
            <th data-options="field:'email'" sortable="true">Email</th>
            <th data-options="
                field:'join',
                formatter: function(value, row, index) {
                    return TimestampString2Date(value)
                },
            " sortable="true">Join</th>
            <th data-options="
                field:'leave',
                formatter: function(value, row, index) {
                    return TimestampString2Date(value)
                },
            " sortable="true">Leave</th>
            <th data-options="field:'brand'" sortable="true">Brand</th>
            <th data-options="field:'user_profile_id_reff'" sortable="true">User Reff.</th>
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