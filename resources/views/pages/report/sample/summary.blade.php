@extends('layouts.default')

@section('addCss')
<style>
    .total-info {
        display: table-cell;
        height: 100%;
        width:600px;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="easyui-panel" data-options="fit:true,border:false,">
    <div id="cc" style="width:100%;height:100%;">
        <div data-options="region:'north'" style="padding: 10px;">
            <table style="width: 100%;" border="0">
                <tr>
                    <td>
                        <h1 id="totalActive" class="total-info">0</h1>
                    </td>
                    <td>
                        <h1 id="totalSuspend" class="total-info">0</h1>
                    </td>
                    <td>
                        <h1 id="totalTerminated" class="total-info">0</h1>
                    </td>
                    <td>
                        <h1 id="totalRevenue" class="total-info">0</h1>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <b>Customer Active</b>
                    </td>
                    <td style="text-align: center;">
                        <b>Customer Suspend</b>
                    </td>
                    <td style="text-align: center;">
                        <b>Customer Terminated</b>
                    </td>
                    <td style="text-align: center;">
                        <b>Total Revenue</b>
                    </td>
                </tr>
            </table>
        </div>
        <div data-options="region:'center',fit:true,">
            <table style="width: 100%; height: 30px; padding-top: 3%;" border="0">
                <tr>
                    <td style="width: 30%;">
                        <canvas id="areaChart"></canvas>
                    </td>
                    <td style="width: 30%;">
                        <canvas id="productServiceChart"></canvas>
                    </td>
                    <td style="width: 30%;">
                        <canvas id="customerSegmentChart"></canvas>
                    </td>
                </tr>
            </table>

            <table border="0" width="100%" style="padding-top: 3%;">
                <tr>
                    <td style="width: 70%;">
                        <canvas id="customerANChart"></canvas>
                    </td>
                    <td style="vertical-align: top;">
                        <table id="dglastPay" class="easyui-datagrid" title="Invoice Last Pay">
                            <thead>
                                <tr>
                                    <th data-options="field:'code_customer'">Code Customer</th>
                                    <th data-options="field:'name'">Name</th>
                                    <th data-options="
                                        field:'price_total',
                                        formatter: function(value, row) {
                                            return IDRFormatter(value)
                                        }
                                    ">Total Price</th>
                                    <th data-options="
                                        field:'verif_payment_at',
                                        formatter: function(value, row) {
                                            return TimestampString2Datetime(value)
                                        },
                                    ">Payment At</th>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
<script src="{{ asset('assets/skp/report/summary.js') }}"></script>
@endsection