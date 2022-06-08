"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/report'

    let _cc = $('#cc');

    let _totalActive = $('#totalActive');
    let _totalSuspend = $('#totalSuspend');
    let _totalTerminated = $('#totalTerminated');
    let _totalRevenue = $('#totalRevenue');

    let _areaChart = $('#areaChart');
    let _productServiceChart = $('#productServiceChart');
    let _customerSegmentChart = $('#customerSegmentChart');
    let _customerANChart = $('#customerANChart');

    let _dglastPay = $('#dglastPay');    

    _cc.layout();

    $.ajax({
        type: "POST",
        url: _rest + "/totals",
        dataType: "json",
        success: function (response) {
            let { active, suspend, terminated, revenue } = response 

            _totalActive.text(active)
            _totalSuspend.text(suspend)
            _totalTerminated.text(terminated)
            _totalRevenue.text(IDRFormatter(revenue))
        },
        error: function (xhr, error) {
            $.messager.progress('close'); 

            Alert('warning', 'Internal Server Error')
        }
    });

    $.ajax({
        type: "POST",
        url: _rest + "/area",
        dataType: "json",
        success: function (response) {
            let { labels, colors, data } = response

            let configArea = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Customer Use",
                        backgroundColor: colors,
                        data: data
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false,
                            text: 'Area Used Customer'
                        }
                    }
                },
            }
            new Chart(_areaChart, configArea)            
        },
        error: function (xhr, error) {
            $.messager.progress('close'); 

            Alert('warning', 'Internal Server Error')
        }
    });

    $.ajax({
        type: "POST",
        url: _rest + "/product-service",
        dataType: "json",
        success: function (response) {
            let { labels, colors, data } = response
            
            let configProductService = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Customer Use",
                        backgroundColor: colors,
                        data: data
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false,
                            text: 'Product Used Customer'
                        }
                    }
                },
            }
            new Chart(_productServiceChart, configProductService)
        },
        error: function (xhr, error) {
            $.messager.progress('close'); 

            Alert('warning', 'Internal Server Error')
        }
    });

    $.ajax({
        type: "POST",
        url: _rest + "/customer-segment",
        dataType: "json",
        success: function (response) {
            let { labels, colors, data } = response
            

            let configCustomerSegment = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Customer Use",
                        backgroundColor: colors,
                        data: data
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false,
                            text: 'Product Service Used Customer'
                        }
                    }
                },
            }
            new Chart(_customerSegmentChart, configCustomerSegment)
        },
        error: function (xhr, error) {
            $.messager.progress('close'); 

            Alert('warning', 'Internal Server Error')
        }
    });

    let configCustomerAN = {
        type: 'line',
        data: {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei'],
            datasets: [
                {
                    label: 'Active',
                    data: [Math.random(), Math.random(), Math.random(), Math.random(), Math.random()],
                    backgroundColor: '#FFA500',
                    borderColor: '#FFA500',
                },
                {
                    label: 'No Active',
                    data: [Math.random(), Math.random(), Math.random(), Math.random(), Math.random()],
                    backgroundColor: '#3A5BA0',
                    borderColor: '#3A5BA0',
                }
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Monthly Customer Active VS No Active'
                }
            }
        },
    }
    new Chart(_customerANChart, configCustomerAN)

    _dglastPay.datagrid({
        singleSelect:true,
        border:true,
        fitColumns:true,
        rownumbers:true,
        method:'post',
        url: _rest + "/list-invoice-pay"
    });
});