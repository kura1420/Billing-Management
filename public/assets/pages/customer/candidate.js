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
    let _ffCreate = $('#ffCreate');
    let _ss = $('#ss');
    
    let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnUpdate = $('#btnUpdate');
    let _btnEdit = $('#btnEdit');

    let _btnPositionUpdate = $('#btnPositionUpdate');
    let _btnPositionManual = $('#btnPositionManual');
    let _btnFile = $('#btnFile');
    let _btnSignature = $('#btnSignature');

    let _c_provinsi_id = $('#c_provinsi_id');
    let _c_city_id = $('#c_city_id');
    let _c_customer_segment_id = $('#c_customer_segment_id');
    let _c_product_service_id = $('#c_product_service_id');
    let _c_fullname = $('#c_fullname');
    let _c_email = $('#c_email');
    let _c_handphone = $('#c_handphone');
    let _c_file = $('#c_file');
    let _c_file_type = $('#c_file_type');
    let _c_file_number = $('#c_file_number');
    let _c_address = $('#c_address');
    let _c_latitude = $('#c_latitude');
    let _c_longitude = $('#c_longitude');
    
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

    _c_provinsi_id.combobox({
        valueField:'id',
        textField:'name',
        url: URL_REST + '/provinsi/lists',
        onSelect: function (record) {
            _c_city_id.combobox({
                onBeforeLoad: function (param) {
                    param.provinsi_id = record.id
                }
            });

            _c_city_id.combobox('reload', URL_REST + '/city/lists');
        }
    });

    _c_city_id.combobox({
        onSelect: function (record) {
            _c_product_service_id.combogrid('clear')

            if (record) {
                _c_customer_segment_id.combogrid({
                    fitColumns:true,
                    idField:'id',
                    textField:'customer_segment_name',
                    method:'post',
                    url: URL_REST + '/area/customer-search',
                    queryParams: {
                        params: {
                            provinsi_id: _c_provinsi_id.combobox('getValue'),
                            city_id: record.id,
                        }
                    },
                    columns:[[
                        {field:'customer_type_name',title:'Type'},
                        {field:'customer_segment_name',title:'Segment'},
                    ]],
                    onChange: function (newValue, oldValue) {
                        if (newValue) {
                            _c_product_service_id.combogrid({
                                fitColumns:true,
                                idField:'id',
                                textField:'product_service_name',
                                method:'post',
                                url: URL_REST + '/area/product-search',
                                queryParams: {
                                    params: {
                                        provinsi_id: _c_provinsi_id.combobox('getValue'),
                                        city_id: _c_city_id.combobox('getValue'),
                                        customer_segment_id: newValue,
                                    }
                                },
                                columns:[[
                                    {field:'product_type_name',title:'Type'},
                                    {field:'product_service_name',title:'Layanan'},
                                ]],
                            });
                        }
                    }
                });
            }
        }
    });

    _c_file_type.combobox({
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
                formResetCreate()
            }
        }
    });

    _btnPositionUpdate.linkbutton({
        onClick: function () {
            Alert('info', 'Posisi sudah diupdate.', 'Informasi');

            updateLocation();
        }
    });

    _btnPositionManual.linkbutton({
        onClick: function () {
            Alert('info', 'Harap masukkan latitude & longitude pelanggan.', 'Informasi');

            _c_latitude
                .textbox('clear')
                .textbox('readonly', false);
    
            _c_longitude
                .textbox('clear')
                .textbox('readonly', false);
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

    _btnAdd.linkbutton({
        onClick: function () {
            _tbs.tabs({
                selected: 1
            })

            formEditCreate()
            
            _btnEdit.linkbutton({disabled:true})
            _btnUpdate.linkbutton({disabled:true})
        }
    });

    _btnSave.linkbutton({
        onClick: function () {
            $.messager.progress();

            _ffCreate.form('submit', {
                url: _rest + '/create',
                onSubmit: function(param) {
                    var isValid = $(this).form('validate');
                    if (!isValid){
                        $.messager.progress('close');
                    }

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
            });
        }
    });

    _btnUpdate.linkbutton({
        onClick: function() {
            $.messager.progress();
    
            _ff.form('submit', {
                url: _rest + '/update',
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

        _btnUpdate.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:false})

        _file_type.textbox({disabled:true})
        _file_number.textbox({disabled:true})
        _status.textbox({disabled:true})
    }

    var formEdit = () => {
        _btnUpdate.linkbutton({disabled:false})
        _btnEdit.linkbutton({disabled:true})
    
        _file_type.textbox({disabled:false})
        _file_number.textbox({disabled:false})
        _status.textbox({disabled:false})
    }

    var formResetCreate = () => {
        _ffCreate.form('clear')

        _btnAdd.linkbutton({disabled:false})
        _btnSave.linkbutton({disabled:true})

        _c_provinsi_id.combobox({disabled:true})
        _c_city_id.combobox({disabled:true})
        _c_customer_segment_id.combogrid({disabled:true})
        _c_product_service_id.combogrid({disabled:true})
        _c_fullname.textbox({disabled:true})
        _c_email.textbox({disabled:true})
        _c_handphone.numberbox({disabled:true})
        _c_file.filebox({disabled:true})
        _c_file_type.combobox({disabled:true})
        _c_file_number.textbox({disabled:true})
        _c_address.textbox({disabled:true})
    }

    var formEditCreate = () => {
        _ffCreate.form('clear')

        _btnAdd.linkbutton({disabled:true})
        _btnSave.linkbutton({disabled:false})

        _c_provinsi_id.combobox({disabled:false})
        _c_city_id.combobox({disabled:false})
        _c_customer_segment_id.combogrid({disabled:false})
        _c_product_service_id.combogrid({disabled:false})
        _c_fullname.textbox({disabled:false})
        _c_email.textbox({disabled:false})
        _c_handphone.numberbox({disabled:false})
        _c_file.filebox({disabled:false})
        _c_file_type.combobox({disabled:false})
        _c_file_number.textbox({disabled:false})
        _c_address.textbox({disabled:false})
    }

    var getData = (row) => {
        if (row) {
            $.ajax({
                type: "GET",
                url: _rest + '/' + row.id,
                dataType: "json",
                success: function (response) {
                    _tbs.tabs({
                        selected: 2
                    })
            
                    formEdit()
                    
                    _btnAdd.linkbutton({disabled:true})
                    _btnSave.linkbutton({disabled:true})
                    
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

    var getLocation = () => {
        if (navigator.geolocation) {
            window.navigator.geolocation.getCurrentPosition(                
                function onError(error) {
                    console.log(error);
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            Alert('warning', "Anda tidak memberikan akses GPS.");                            
                            break;

                        case error.POSITION_UNAVAILABLE:
                            Alert('warning', "Lokasi anda tidak ditemukan."); 
                            break;

                        case error.TIMEOUT:
                            Alert('warning', "Waktu membaca posisi GPS habis."); 
                            break;

                        case error.UNKNOWN_ERROR:
                            Alert('warning', "Fungsi membaca GPS tidak bekerja."); 
                            break;
                    
                        default:
                            break;
                    }
                }
            );
        } else {
            Alert('warning', "Geolocation is not supported by this browser.");
        }
    }

    var updateLocation = () => window.navigator.geolocation.getCurrentPosition(setPosition);

    var setPosition = (position) => {
        _c_latitude
            .textbox('clear')
            .textbox('readonly');

        _c_longitude
            .textbox('clear')
            .textbox('readonly');

        _c_latitude.textbox('setValue', position.coords.latitude);
        _c_longitude.textbox('setValue', position.coords.longitude);
    }

    loadData()
});