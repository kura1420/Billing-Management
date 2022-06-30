"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/customer-candidate'

    var _file_url = undefined;
    var _signature_url = undefined;

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');
    
    let _btnFile = $('#btnFile');
    let _btnSignature = $('#btnSignature');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    
    let _id = $('#id');
    let _fullname = $('#fullname');
    let _email = $('#email');
    let _handphone = $('#handphone');
    let _file_type = $('#file_type');
    let _file_number = $('#file_number');
    let _address = $('#address');
    let _status = $('#status');
    let _from = $('#from');
    let _user_id = $('#user_id');
    let _provinsi_id = $('#provinsi_id');
    let _city_id = $('#city_id');
    let _product_service_id = $('#product_service_id');
    let _customer_segment_id = $('#customer_segment_id');

    _file_type.combobox({
        valueField:'id',
        textField:'text',
        data: [{
            "id":'ktp',
            "text":"KTP"
        },{
            "id":'sim',
            "text":"SIM"
        },{
            "id":'passpor',
            "text":"Passpor"
        },{
            "id":'siup',
            "text":"SIUP"
        },{
            "id":'npwp',
            "text":"NPWP"
        },{
            "id":'tdp',
            "text":"TDP"
        },]
    });

    _status.combobox({
        valueField:'id',
        textField:'text',
        data: [{
            "id":'0',
            "text":"Registered"
        },{
            "id":'1',
            "text":"Confirmation"
        },{
            "id":'2',
            "text":"Schedule Install"
        },{
            "id":'3',
            "text":"On Process"
        },{
            "id":'4',
            "text":"Cancel"
        },{
            "id":'5',
            "text":"Success"
        },]
    });

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

    _btnFile.linkbutton({
        onClick: function () {
            if (_file_url) {
                window.open(_file_url);
            }
        }
    });

    _btnSignature.linkbutton({
        onClick: function () {
            if (_file_url) {
                window.open(_signature_url);
            }
        }
    });

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
            let row = _dg.datagrid('getSelected')
    
            getData(row)
        }
    });

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
        });

        _dg.datagrid('fixColumnSize');
        _dg.datagrid('fixRowHeight');
    }

    var formReset = () => {
        _ff.form('clear')

        _btnEdit.linkbutton({disabled:false})

        _file_type.textbox({disabled:true})
        _file_number.textbox({disabled:true})
        _status.textbox({disabled:true})
    }

    var formEdit = () => {
        _btnEdit.linkbutton({disabled:true})
    
        _file_type.textbox({disabled:false})
        _file_number.textbox({disabled:false})
        _status.textbox({disabled:false})
    }

    var getData = (row) => {
        if (row) {
            $.ajax({
                type: "GET",
                url: _rest + '/' + row.id,
                dataType: "json",
                success: function (response) {
                    _tbs.tabs({
                        selected: 1
                    })
            
                    formEdit()
                    
                    let {
                        id,
                        fullname,
                        email,
                        handphone,
                        file,
                        file_url,
                        file_type,
                        file_number,
                        address,
                        longitude,
                        latitude,
                        status,
                        from,
                        signature,
                        signature_url,
                        user_id,
                        area_id,
                        provinsi_id,
                        city_id,
                        product_type_id,
                        product_service_id,
                        customer_type_id,
                        customer_segment_id,
                    } = response

                    _ff.form('load', {
                        id,
                        fullname,
                        email,
                        handphone,
                        file_type,
                        file_number,
                        address,
                        status,
                        from,
                        signature_url,
                        user_id,
                        provinsi_id,
                        city_id,
                        product_service_id,
                        customer_segment_id,
                    });
                    
                    _file_url = file_url
                    _signature_url = signature_url
                },
                error: function (xhr, status, error) {
                    Alert('error', 'Internal server error');
                }
            });
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData()
});