"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/billing-invoice'

    var _file_invoice_url = null;
    var _file_payment_url = null;

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');
    
    // let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    let _btnUnsuspend = $('#btnUnsuspend');
    let _btnCancel = $('#btnCancel');
    // let _btnRemove = $('#btnRemove');
    let _btnFileInvoice = $('#btnFileInvoice');
    let _btnFilePayment = $('#btnFilePayment');
    
    let _id = $('#id');
    let _billing_type_id = $('#billing_type_id');
    let _code = $('#code');
    let _status = $('#status');
    let _price_ppn = $('#price_ppn');
    let _price_sub = $('#price_sub');
    let _price_total = $('#price_total');
    let _price_discount = $('#price_discount');
    let _verif_payment_at = $('#verif_payment_at');
    let _verif_by_user_id = $('#verif_by_user_id');
    let _notif_at = $('#notif_at');
    let _file_payment = $('#file_payment');

    let _product_type_name = $('#product_type_name');
    let _product_service_name = $('#product_service_name');
    let _customer_data_code = $('#customer_data_code');
    let _name = $('#name');
    let _email = $('#email');
    let _telp = $('#telp');
    let _handphone = $('#handphone');
    let _member_at = $('#member_at');
    let _suspend_at = $('#suspend_at');
    let _terminate_at = $('#terminate_at');

    _dg.datagrid({
        singleSelect:true,
        collapsible:true,
        border:false,
        fitColumns:true,
        pagination:true,
        rownumbers:true,
        remoteSort:true,
        toolbar:'#tb',
    });

    _ss.searchbox({
        prompt: 'Search',
        searcher: function (value, name) {
            if (!value) return loadData()

            $.ajax({
                method: 'get',
                url: _rest,
                data: {
                    search: value,
                },
                dataType: 'json',
                success: function (res) {
                    let {data} = res

                    _dg.datagrid('loadData', data)
                },
                error: function (xhr, status, error) {
                    let {statusText, responseJSON} = xhr

                    Alert('error', responseJSON, statusText)
                }
            })
        },
    });

    _tbs.tabs({
        onSelect: function (title, index) {
            if (index == 0) {
                formReset()
            }
        }
    });

    // _btnAdd.linkbutton({
    //     onClick: function() {
    //         _tbs.tabs({
    //             selected: 1
    //         })
        
    //         formEdit()
    
    //         _ff.form('clear')
    //     }
    // });

    _btnSave.linkbutton({
        onClick: function() {
            $.messager.progress();
    
            _ff.form('submit', {
                url: _rest,
                onSubmit: function(param) {
                    var isValid = $(this).form('validate');
                    if (!isValid){
                        $.messager.progress('close');
                    }
    
                    param.id = _id.textbox('getValue')
                    param._token = $('meta[name="csrf-token"]').attr('content')
    
                    return isValid;
                },
                success: function(res) {
                    $.messager.progress('close');
    
                    let {status, data} = JSON.parse(res)
    
                    if (status == 'NOT') {
                        let msg = []
                        for (var d in data) {
                            msg.push(data[d].toString())
                        }
    
                        Alert('warning', msg.join('<br />'))
                    } else {
                        loadData()

                        _btnSave.linkbutton({disabled:true})
                        _btnEdit.linkbutton({disabled:true})
                        _btnCancel.linkbutton({disabled:true})
                        _btnUnsuspend.linkbutton({disabled:true})

                        _status.textbox('setValue', 'Paid')
    
                        $.messager.show({
                            title:'Info',
                            msg:'Data saved.',
                            timeout:5000,
                            showType:'slide'
                        })
                    }
                },
            })
        }
    });
    
    _btnEdit.linkbutton({
        onClick: function() {
            _file_payment.filebox({disabled:false})

            _btnSave.linkbutton({disabled:false})
            $(this).linkbutton({disabled:true})
            _btnCancel.linkbutton({disabled:false})
            _btnUnsuspend.linkbutton({disabled:false})
        }
    });

    _btnCancel.linkbutton({
        onClick: function () {
            _file_payment.filebox('clear')
            _file_payment.filebox({disabled:true})

            _btnSave.linkbutton({disabled:true})
            _btnEdit.linkbutton({disabled:false})
            $(this).linkbutton({disabled:true})
            _btnUnsuspend.linkbutton({disabled:true})
        }
    });
    
    _btnUnsuspend.linkbutton({
        onClick: function () { 
            $.messager.progress();
            
            $.ajax({
                type: "post",
                url: _rest + "/unsuspend/" + _id.textbox('getValue'),
                dataType: "json",
                success: function (response) {
                    $.messager.progress('close');

                    $.messager.show({
                        title:'Info',
                        msg:'Unsuspend success.',
                        timeout:5000,
                        showType:'slide'
                    })
                    
                    _status.textbox('setValue', 'Unsuspend')

                    loadData()
                },
                error: function (xhr, error) {
                    $.messager.progress('close');
                    
                    let {status, responseJSON} = xhr

                    if (status == 422) {
                        let msg = []
                        for (var d in responseJSON.data) {
                            msg.push(responseJSON.data[d].toString())
                        }

                        Alert('warning', msg.join('<br />'))
                    } else {
                        Alert('warning', 'Internal Server Error')
                    }
                }
            });
        }
    });
    
    // _btnRemove.linkbutton({
    //     onClick: function() {
    //         let row = _dg.datagrid('getSelected')
    
    //         if (row) {
    //             $.messager.confirm('Confirmation', 'Are you sure delete this data?', function(r){
    //                 if (r){
    //                     $.ajax({
    //                         type: "delete",
    //                         url: _rest + '/' + row.id,
    //                         dataType: "json",
    //                         success: function (response) {
    //                             loadData()
    
    //                             _tbs.tabs({
    //                                 selected: 0
    //                             })
    
    //                             $.messager.show({
    //                                 title:'Info',
    //                                 msg:'Data deleted.',
    //                                 timeout:5000,
    //                                 showType:'slide'
    //                             })
    //                         },
    //                         error: function (xhr, status, error) {
    //                             let {statusText, responseJSON} = xhr
    
    //                             Alert('error', responseJSON, statusText)
    //                         }
    //                     });
    //                 }
    //             });
    //         } else {
    //             Alert('warning', 'No selected data')
    //         }
    //     }
    // });

    _btnFileInvoice.linkbutton({
        onClick: function () {
            window.open(_file_invoice_url)
        }
    })

    _btnFilePayment.linkbutton({
        onClick: function () {  
            if (_file_payment_url) {
                window.open(_file_payment_url)
            } else {
                Alert('warning', 'File tidak tersedia')
            }
        }
    })

    var loadData = () => {
        _dg.datagrid({
            method: 'get',
            url: _rest,
            onDblClickRow: function (index, row) {
                getData(row)
            },
            loader: function (param, success, error) {
                let {method, url, pageNumber, pageSize, sortOrder, sortName} = $(this).datagrid('options')
                
                if (method==null || url==null) return false

                $.ajax({
                    method: method,
                    url: url,
                    data: {
                        page: pageNumber,
                        rows: pageSize,
                        sortOrder: sortOrder,
                        sortName: sortName,
                    },
                    dataType: 'json',
                    success: function (res) {
                        let {total, data} = res

                        success({
                            total: total,
                            rows: data
                        })
                    },
                    error: function (xhr, status) {
                        error(xhr)
                    }
                })
            },
            onLoadError: function (objs) {
                let {statusText, responseJSON} = objs

                Alert('error', responseJSON, statusText)
            },
        })
    }

    var formReset = () => {
        _ff.form('clear')

        _btnSave.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:true})
        _btnCancel.linkbutton({disabled:true})
        _btnUnsuspend.linkbutton({disabled:true})

        _btnFileInvoice.linkbutton({disabled:true})
        _btnFilePayment.linkbutton({disabled:true})

        _file_payment.filebox({disabled:true})
    }

    var formEdit = () => {
        _btnSave.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:false})
        _btnCancel.linkbutton({disabled:true})
        _btnUnsuspend.linkbutton({disabled:true})

        _btnFileInvoice.linkbutton({disabled:false})
        _btnFilePayment.linkbutton({disabled:false})
    }

    var getData = (row) => {
        if (row) {
            $.ajax({
                type: "get",
                url: _rest + '/' + row.id,
                dataType: "json",
                success: function (response) {
                    response.status = billingStatus(response.status)
                    response.notif_at = TimestampString2Date(response.notif_at)

                    _file_invoice_url = response.file_invoice_url
                    _file_payment_url = response.file_payment_url

                    _tbs.tabs({
                        selected: 1
                    })

                    _ff.form('load', response)
        
                    formEdit()

                    if (response.status == 'Paid' || response.status == 'Terminated') {
                        _btnEdit.linkbutton({disabled:true})
                    }
                },
                error: function (xhr, error) {
                    Alert('warning', 'Internal Server Error')
                }
            });
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData()
});