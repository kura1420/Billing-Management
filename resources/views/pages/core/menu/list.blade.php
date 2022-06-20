<table id="dg" class="easyui-treegrid" style="height: 100%;">
    <thead>
        <tr>
            <th data-options="field:'text'" sortable="true">Text</th>
            <th data-options="field:'title'" sortable="true">Title</th>
            <!-- <th data-options="field:'url'" sortable="false">URL</th> -->
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