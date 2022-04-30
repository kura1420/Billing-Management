<table id="dg" class="easyui-datagrid" style="height: 100%;">
    <thead>
        <tr>
            <th data-options="field:'name'" sortable="true">Name</th>
            <th data-options="field:'email'" sortable="true">Email</th>
            <!-- <th data-options="field:'telp'" sortable="true">Telp</th>
            <th data-options="field:'handphone'" sortable="true">Handphone</th> -->
            <th data-options="field:'departement_id'" sortable="true">Departement</th>
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