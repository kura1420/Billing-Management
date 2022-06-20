<table id="dg" class="easyui-datagrid" style="height: 100%;">
    <thead>
        <tr>
            <th data-options="field:'billing_type_id'" sortable="true">Billing Type</th>
            <th data-options="field:'code'" sortable="true">Billing Code</th>
            <th data-options="field:'customer_data_id'" sortable="true">Customer Code</th>
            <th data-options="field:'name'" sortable="true">Name</th>
            <th data-options="field:'email'" sortable="true">Email</th>
            <th data-options="
                field:'status',
                align:'center',
                formatter: function(value, row) {
                    return billingStatus(value)
                }
            " sortable="true">Status</th>
            <th data-options="
                field:'price_discount',
                align:'right',
                formatter: function(value, row) {
                    return IDRFormatter(value)
                }
            " sortable="true">Discount</th>
            <th data-options="
                field:'price_total',
                align:'right',
                formatter: function(value, row) {
                    return IDRFormatter(value)
                }
            " sortable="true">Total</th>
            <th data-options="field:'product_type_id'" sortable="true">Product Type</th>
            <th data-options="field:'product_service_id'" sortable="true">Product Service</th>
            <th data-options="
                field:'notif_at',
                formatter: function(value, row) {
                    return TimestampString2Date(value)
                },
            " sortable="true">Invoice</th>
            <th data-options="
                field:'verif_payment_at',
                formatter: function(value, row) {
                    if (value) {
                        return TimestampString2Datetime(value)
                    }
                },
            " sortable="true">Payment At</th>
        </tr>
    </thead>
</table>
<div id="tb" style="padding:2px 5px;">
    <input id="ss" class="easyui-searchbox" style="width:30%">
</div>