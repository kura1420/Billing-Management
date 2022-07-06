<table id="dg" class="easyui-datagrid" style="height: 100%;">
    <thead>
        <tr>
            <th data-options="field:'provinsi_name'" sortable="true">Provinsi</th>
            <th data-options="field:'city_name'" sortable="true">City</th>
            <th data-options="field:'customer_segment_name'" sortable="true">Segment</th>
            <th data-options="field:'product_service_name'" sortable="true">Product Service</th>
            <th data-options="field:'fullname'" sortable="true">Fullname</th>
            <th data-options="field:'email'" sortable="true">Email</th>
            <th data-options="field:'handphone'" sortable="true">Handphone</th>
            <th data-options="
                field:'file_type',       
                formatter: function(value, row, index) {
                    if (value) {
                        return value.toUpperCase()
                    }
                },                
            " sortable="true">File Type</th>
            <th data-options="field:'file_number'" sortable="true">File Number</th>
            <th data-options="
                field:'status',                
                align:'center',
                formatter: function(value, row, index) {
                    return customerCandidateStatus(value)
                },
            " sortable="true">Status</th>
        </tr>
    </thead>
</table>
<div id="tb" style="padding:2px 5px;">
    <input id="ss" class="easyui-searchbox" style="width:30%">
</div>