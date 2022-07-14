"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/customer'

    var rowIndexContact = undefined;
    
    var _picture_url = null;
    var _file_url = null;
    var _device_picture_url = null;

    var _area_product_customer_value = undefined;
    var _area_product_promo_value = undefined;

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');

    let _dgContact = $('#dgContact')

    let _dgDocument = $('#dgDocument')
    let _ffDocument = $('#ffDocument')
    let _wDocument = $('#wDocument')

    let _dgDevice = $('#dgDevice')
    let _ffDevice = $('#ffDevice')
    let _wDevice = $('#wDevice')

    let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    let _btnCopy = $('#btnCopy');
    // let _btnRemove = $('#btnRemove');
    let _btnPreview = $('#btnPreview');

    let _btnAddContact = $('#btnAddContact');
    let _btnOkContact = $('#btnOkContact');
    let _btnEditContact = $('#btnEditContact');
    let _btnCancelContact = $('#btnCancelContact');
    let _btnRemoveContact = $('#btnRemoveContact');

    let _btnAddDocument = $('#btnAddDocument');
    let _btnEditDocument = $('#btnEditDocument');
    let _btnRemoveDocument = $('#btnRemoveDocument');
    let _btnPreviewDocument = $('#btnPreviewDocument');
    let _btnSaveDocument = $('#btnSaveDocument');
    let _btnCancelDocument = $('#btnCancelDocument');

    let _btnAddDevice = $('#btnAddDevice');
    let _btnEditDevice = $('#btnEditDevice');
    let _btnRemoveDevice = $('#btnRemoveDevice');
    let _btnPreviewDevice = $('#btnPreviewDevice');
    let _btnSaveDevice = $('#btnSaveDevice');
    let _btnCancelDevice = $('#btnCancelDevice');

    let _id = $('#id');
    let _service_trigger = $('#service_trigger');
    let _area_id = $('#area_id');
    let _area_product_customer = $('#area_product_customer');
    let _area_product_promo_id = $('#area_product_promo_id');
    let _member_at = $('#member_at');
    let _active = $('#active');

    let _name = $('#name');
    let _gender = $('#gender');
    let _email = $('#email');
    let _telp = $('#telp');
    let _handphone = $('#handphone');
    let _fax = $('#fax');
    let _address = $('#address');
    let _picture = $('#picture');
    let _birthdate = $('#birthdate');
    let _marital_status = $('#marital_status');
    let _work_type = $('#work_type');
    let _child = $('#child');

    let _d_id = $('#d_id')
    let _d_customer_contact_id = $('#d_customer_contact_id')
    let _d_type = $('#d_type')
    let _d_file = $('#d_file')
    let _d_identity_number = $('#d_identity_number')
    let _d_identity_expired = $('#d_identity_expired')

    let _e_id = $('#e_id')
    let _e_item_id = $('#e_item_id')
    let _e_qty = $('#e_qty')
    let _e_desc = $('#e_desc')
    let _e_picture = $('#e_picture')

    _area_id.combobox({
        valueField:'id',
        textField:'name',
        url: URL_REST + '/area/lists',
        onSelect: function (record) {
            _area_product_customer.combogrid({
                // panelWidth:600,
                fitColumns:true,
                idField:'id',
                textField:'product_service_name',
                method:'get',
                url: URL_REST + '/area/customer/' + record.id,
                columns:[[
                    {field:'provinsi_name',title:'Provinsi'},
                    {field:'city_name',title:'City'},
                    {field:'product_type_name',title:'Product Type'},
                    {field:'product_service_name',title:'Product Service'},
                    {field:'customer_type_name',title:'Customer Type'},
                    {field:'customer_segment_name',title:'Customer Service'},
                    {
                        field:'active',title:'Active',
                        formatter: function (value, row) {
                            return value == 1 ? 'Active' : 'No Active'
                        }
                    },
                ]],
                onLoadSuccess: function (data) {  
                    if (_area_product_customer_value) {
                        _area_product_customer.combogrid('setValue', _area_product_customer_value)
                    }
                }
            });
        },
    });

    _area_product_customer.combogrid({
        onChange: function (newValue, oldValue) {
            if (newValue) {
                let g = $(this).combogrid('grid')
                let r = g.datagrid('getSelected')

                if (r) {
                    _area_product_promo_id.combobox({
                        url: URL_REST + '/product-promo/area-filter',
                        queryParams: {
                            area_id: _area_id.combobox('getValue'),
                            product_type_id: r.product_type_id,
                            product_service_id: r.product_service_id
                        },
                        onLoadSuccess: function () {
                            if (_area_product_promo_value) {
                                _area_product_promo_id.combobox('setValue', _area_product_promo_value)
                            }
                        }
                    })                    
                }
            } else {
                _area_product_promo_id.combobox('loadData', []);
            }
        }
    });

    _gender.combobox({
        valueField:'id',
        textField:'text',
        data: [{
            "id":'l',
            "text":"Laki-laki"
        },{
            "id":'p',
            "text":"Perempuan"
        }]
    });

    _marital_status.combobox({
        valueField:'id',
        textField:'text',
        data: [{
            "id":"BM",
            "text":"Belum Menikah"
        },{
            "id":"MK",
            "text":"Menikah"
        },{
            "id":"DD",
            "text":"Duda"
        },{
            "id":"JD",
            "text":"Janda"
        }]
    });

    _d_type.combobox({
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

    _dgContact.datagrid({
        singleSelect:true,
        collapsible:true,
        border:false,
        fitColumns:true,
        pagination:true,
        rownumbers:true,
        border:false,
        toolbar:'#tbContact',
        onDblClickRow: function (index, row) {
            editRowContact()
        },
        columns:[[
            {
                field:'name', title:'Name', width: 300,
                editor: {
                    type: 'textbox',
                    options: {
                        required:true,
                    },
                },
            },
            {
                field:'gender', title:'Gender', width: 300,
                formatter:function(value,row){
                    return value == 'l' ? 'Laki-laki' : 'Perempuan'
                },
                editor: {
                    type: 'combobox',
                    options: {
                        valueField:'id',
                        textField:'text',
                        data: [{
                            "id":'l',
                            "text":"Laki-laki"
                        },{
                            "id":'p',
                            "text":"Perempuan"
                        }],
                        required:true,
                    },
                },
            },
            {
                field:'telp', title:'Telp', width: 300,
                editor: {
                    type: 'numberbox',
                    options: {
                        required:false,
                    },
                },
            },
            {
                field:'handphone', title:'Handphone', width: 300,
                editor: {
                    type: 'numberbox',
                    options: {
                        required:true,
                    },
                },
            },
            {
                field:'email', title:'Email', width: 300,
                editor: {
                    type: 'textbox',
                    options: {
                        required:true,
                        validType:'email',
                    },
                },
            },
            {
                field:'address', title:'Address', width: 300,
                editor: {
                    type: 'textbox',
                    options: {
                        required:false,
                    },
                },
            },
        ]]
    });

    _dgDocument.datagrid({
        singleSelect:true,
        collapsible:true,
        border:false,
        fitColumns:true,
        pagination:true,
        rownumbers:true,
        border:false,
        toolbar:'#tbDocument',
        onDblClickRow: function (index, row) {
            editDocument();
        },
    });

    _dgDevice.datagrid({
        singleSelect:true,
        collapsible:true,
        border:false,
        fitColumns:true,
        pagination:true,
        rownumbers:true,
        border:false,
        toolbar:'#tbDevice',
        onDblClickRow: function (index, row) {
            editDevice();
        },
    })

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

    _btnAdd.linkbutton({
        onClick: function() {
            _tbs.tabs({
                selected: 1
            })

            formEdit()

            _ff.form('clear')

            loadDataContact('loadData', [])
            loadDataDocument('loadData', [])
            _area_product_customer.combogrid({
                method: 'get',
                url: URL_REST + '/area/customer/reset'
            })
            _area_product_promo_id.combobox('loadData', [])

            _picture_url = null
            _area_product_customer_value = null
            _area_product_promo_value = null
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
                    
                    let g = _area_product_customer.combogrid('grid');
                    let r = g.datagrid('getSelected');
    
                    param.id = _id.textbox('getValue')
                    param.active = _active.switchbutton('options').checked

                    if (r) {
                        param.customer_type_id = r.customer_type_id
                        param.customer_segment_id = r.customer_segment_id
                        param.product_id = r.product_id
                        param.provinsi_id = r.provinsi_id
                        param.city_id = r.city_id
                        param.area_product_id = r.area_product_id
                        param.area_product_customer_id = r.id
                        param.product_type_id = r.product_type_id
                        param.product_service_id = r.product_service_id
                    }

                    param.contacts = JSON.stringify(_dgContact.datagrid('getRows'))
                    
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
                        loadDataContact(res.id)
    
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

    _btnEdit.linkbutton({
        onClick: function() {
            let row = _dg.datagrid('getSelected')

            getData(row)
        }
    });

    _btnCopy.linkbutton({
        onClick: function () {
            let row = _dg.datagrid('getSelected')

            if (row) {
                $.get(_rest + "/" + row.id,
                    function (data, textStatus, jqXHR) {
                        delete data.id

                        formEdit()

                        _ff.form('load', data)

                        _tbs.tabs({
                            selected: 1
                        })
                    },
                    "json"
                );
            } else {
                Alert('warning', 'No selected data')
            }
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

    _btnPreview.linkbutton({
        onClick: function () {
            if (_picture_url) {
                window.open(_picture_url)                
            }
        }
    });

    _btnAddContact.linkbutton({
        onClick: function () {
            if (rowIndexContact == undefined) {
                _dgContact.datagrid('appendRow', {
                    name: '',
                    gender: '',
                    telp: '',
                    handphone: '',
                    email: '',
                    address: '',
                });

                rowIndexContact = _dgContact.datagrid('getRows').length-1;

                _dgContact.datagrid('selectRow', rowIndexContact)
                    .datagrid('beginEdit', rowIndexContact);
            } else {
                setTimeout(function(){
                    _dgContact.datagrid('selectRow', rowIndexContact);
                },0);
            }        
        }
    });

    _btnOkContact.linkbutton({
        onClick: function () {  
            if (rowIndexContact !== undefined) {
                if (endEditingContact()) {
                    _dgContact.datagrid('acceptChanges');
                } 
            } else {
                Alert('warning', 'No selected data');            
            }
        }
    });

    _btnEditContact.linkbutton({
        onClick: function () {
            editRowContact()
        }
    });

    _btnCancelContact.linkbutton({
        onClick: function () {
            _dgContact.datagrid('rejectChanges');
            
            rowIndexContact = undefined;
        }
    });

    _btnRemoveContact.linkbutton({
        onClick: function () {
            if (rowIndexContact == undefined) {
                let row = _dgContact.datagrid('getSelected');

                if (row) {
                    rowIndexContact = _dgContact.datagrid('getRowIndex', row);

                    $.messager.confirm('Confirmation', 'Are you sure delete this data?', function(r){
                        if (r){
                            if (row.id) {
                                $.ajax({
                                    type: "delete",
                                    url: _rest + '/contact/' + row.id,
                                    dataType: "json",
                                    success: function (response) {
                                        loadDataContact(_id.textbox('getValue'))

                                        $.messager.show({
                                            title:'Info',
                                            msg:'Data deleted.',
                                            timeout:5000,
                                            showType:'slide'
                                        })
                                    },
                                    error: function (xhr, status, error) {
                                        let {statusText, responseJSON} = xhr

                                        Alert('error', responseJSON, statusText)
                                    }
                                });
                            } else {
                                _dgContact.datagrid('cancelEdit', rowIndexContact)
                                        .datagrid('deleteRow', rowIndexContact);

                                $.messager.show({
                                    title:'Info',
                                    msg:'Data deleted.',
                                    timeout:5000,
                                    showType:'slide'
                                });
                            }
                        }

                        rowIndexContact = undefined;
                    });
                } else {
                    Alert('warning', 'No selected data');             
                }
            } else {
                setTimeout(function(){
                    _dgContact.datagrid('selectRow', rowIndexContact);
                },0);                  
            }
        }
    });

    _btnAddDocument.linkbutton({
        onClick: function () {
            _wDocument.window('open');

            _file_url = null

            _d_customer_contact_id.combobox({
                valueField:'id',
                textField:'name',
                url: _rest + '/contact-merge/' + _id.textbox('getValue'),
            });
        }
    });

    _btnSaveDocument.linkbutton({
        onClick: function () {
            $.messager.progress();

            _ffDocument.form('submit', {
                url: _rest + '/document',
                onSubmit: function (param) {
                    var isValid = $(this).form('validate');
                    if (!isValid){
                        $.messager.progress('close');
                    }
    
                    param.customer_data_id = _id.textbox('getValue')
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
                        loadDataDocument(_id.textbox('getValue'));
                        
                        _wDocument.window('close');

                        _ffDocument.form('clear');

                        _file_url = null
                        _area_product_customer_value = null
                        _area_product_promo_value = null

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

    _btnEditDocument.linkbutton({
        onClick: function () {
            editDocument()
        }
    });

    _btnCancelDocument.linkbutton({
        onClick: function () {
            _wDocument.window('close');

            _ffDocument.form('clear');

            _file_url = null
        }
    });

    _btnRemoveDocument.linkbutton({
        onClick: function () {
            let row = _dgDocument.datagrid('getSelected');

            if (row) {
                $.messager.confirm('Confirmation', 'Are you sure delete this data?', function(r){
                    if (r){
                        $.ajax({
                            type: "delete",
                            url: _rest + '/document/' + row.id,
                            dataType: "json",
                            success: function (response) {
                                loadDataDocument(_id.textbox('getValue'))

                                $.messager.show({
                                    title:'Info',
                                    msg:'Data deleted.',
                                    timeout:5000,
                                    showType:'slide'
                                })
                            },
                            error: function (xhr, status, error) {
                                let {statusText, responseJSON} = xhr

                                Alert('error', responseJSON, statusText)
                            }
                        });
                    }
                });
            } else {
                Alert('warning', 'No selected data');                  
            }
        }
    });

    _btnPreviewDocument.linkbutton({
        onClick: function () {
            if (_file_url) {
                window.open(_file_url)                
            }
        }
    });

    _btnAddDevice.linkbutton({
        onClick: function () {
            _wDevice.window('open')

            _device_picture_url = null

            _e_item_id.combogrid({
                fitColumns:true,
                idField:'id',
                textField:'serial_numbers',
                method:'get',
                url: URL_REST + '/item-data/lists/',        
                columns:[[
                    {field:'partner_name',title:'Partner'},
                    {field:'unit_name',title:'Satuan'},
                    {field:'code',title:'Code'},
                    {field:'name',title:'Name'},
                    {field:'serial_numbers',title:'Serial Number'},
                    {field:'brand',title:'Brand'},
                ]],
            });
        }
    });
    
    _btnSaveDevice.linkbutton({
        onClick: function () {
            $.messager.progress();

            _ffDevice.form('submit', {
                url: _rest + '/device',
                onSubmit: function (param) {
                    var isValid = $(this).form('validate');
                    if (!isValid){
                        $.messager.progress('close');
                    }
    
                    param.customer_data_id = _id.textbox('getValue')
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
                        loadDataDevice(_id.textbox('getValue'));
                        
                        _wDevice.window('close');

                        _ffDevice.form('clear');

                        _device_picture_url = null

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

    _btnEditDevice.linkbutton({
        onClick: function () {
            editDevice()
        }
    });

    _btnCancelDevice.linkbutton({
        onClick: function () {
            _wDevice.window('close');

            _ffDevice.form('clear');

            _device_picture_url = null
        }
    });

    _btnRemoveDevice.linkbutton({
        onClick: function () {
            let row = _dgDevice.datagrid('getSelected');

            if (row) {
                $.messager.confirm('Confirmation', 'Are you sure delete this data?', function(r){
                    if (r){
                        $.ajax({
                            type: "delete",
                            url: _rest + '/device/' + row.id,
                            dataType: "json",
                            success: function (response) {
                                loadDataDevice(_id.textbox('getValue'))

                                $.messager.show({
                                    title:'Info',
                                    msg:'Data deleted.',
                                    timeout:5000,
                                    showType:'slide'
                                })
                            },
                            error: function (xhr, status, error) {
                                let {statusText, responseJSON} = xhr

                                Alert('error', responseJSON, statusText)
                            }
                        });
                    }
                });
            } else {
                Alert('warning', 'No selected data');                  
            }
        }
    });

    _btnPreviewDevice.linkbutton({
        onClick: function () {
            if (_device_picture_url) {
                window.open(_device_picture_url)                
            }
        }
    });

    var editRowContact = () => {
        if (rowIndexContact == undefined) {
            let row = _dgContact.datagrid('getSelected');

            if (row) {
                rowIndexContact = _dgContact.datagrid('getRowIndex', row);

                _dgContact.datagrid('selectRow', rowIndexContact)
                    .datagrid('beginEdit', rowIndexContact);
            } 
        } else {
            setTimeout(function(){
                _dgContact.datagrid('selectRow', rowIndexContact);
            },0);            
        }
    }

    var onEndEditContact = (index, row) => {
        var ed = $(this).datagrid('getEditor', {
            index: index,
            field: 'gender',
        });

        row.text = $(ed.target).combobox('getText')
    }

    var endEditingContact = () => {
        if (rowIndexContact == undefined){return true}
        if (_dgContact.datagrid('validateRow', rowIndexContact)){
            _dgContact.datagrid('endEdit', rowIndexContact);
            rowIndexContact = undefined;
            return true;
        } else {
            return false;
        }
    }

    var editDocument = () => {
        let row = _dgDocument.datagrid('getSelected')

        if (row) {
            _wDocument.window('open')

            _d_customer_contact_id.combobox({
                valueField:'id',
                textField:'name',
                url: _rest + '/contact-merge/' + _id.textbox('getValue'),
            });
            
            $.ajax({
                type: "get",
                url: _rest + "/document-show/" + row.id,
                dataType: "json",
                success: function (response) {
                    let {
                        id,
                        type,
                        file,
                        identity_number,
                        identity_expired,
                        customer_contact_id,
                        file_url,
                    } = response

                    _ffDocument.form('load', {
                        d_id: id,
                        d_type: type,
                        d_file: file,
                        d_identity_number: identity_number,
                        d_identity_expired: identity_expired,
                        d_customer_contact_id: customer_contact_id,
                    });

                    _file_url = file_url
                },
                error: function (xhr, status, error) {
                    Alert('error', 'Internal server error');
                }
            });
        } else {
            Alert('warning', 'No selected data');                 
        }
    }

    var editDevice = () => {
        let row = _dgDevice.datagrid('getSelected')

        if (row) {
            _wDevice.window('open')

            _e_item_id.combogrid({
                fitColumns:true,
                idField:'id',
                textField:'serial_numbers',
                method:'get',
                url: URL_REST + '/item-data/lists/',        
                columns:[[
                    {field:'partner_name',title:'Partner'},
                    {field:'unit_name',title:'Satuan'},
                    {field:'code',title:'Code'},
                    {field:'name',title:'Name'},
                    {field:'serial_numbers',title:'Serial Number'},
                    {field:'brand',title:'Brand'},
                ]],
            });
            
            $.ajax({
                type: "get",
                url: _rest + "/device-show/" + row.id,
                dataType: "json",
                success: function (response) {
                    let {
                        id,
                        desc,
                        picture,
                        picture_url,
                        qty,
                        item_id,
                    } = response

                    _ffDevice.form('load', {
                        e_id: id,
                        e_desc: desc,
                        e_picture: picture,
                        e_qty: qty,
                        e_item_id: item_id,
                    });

                    _device_picture_url = picture_url
                },
                error: function (xhr, status, error) {
                    Alert('error', 'Internal server error');
                }
            });
        } else {
            Alert('warning', 'No selected data');                 
        }
    }

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

    var loadDataContact = (customer_data_id) => {
        _dgContact.datagrid({
            method: 'get',
            url: _rest + '/contact/' + customer_data_id,
            loader: function (param, success, error) {
                let {method, url} = $(this).datagrid('options')

                if (method==null || url==null) return false

                $.ajax({
                    method: method,
                    url: url,
                    dataType: 'json',
                    success: function (res) {
                        success(res)
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

        _dgContact.datagrid('fixColumnSize');
        _dgContact.datagrid('fixRowHeight');
    }

    var loadDataDocument = (customer_data_id) => {
        _dgDocument.datagrid({
            method: 'get',
            url: _rest + '/document/' + customer_data_id,
            loader: function (param, success, error) {
                let {method, url} = $(this).datagrid('options')

                if (method==null || url==null) return false

                $.ajax({
                    method: method,
                    url: url,
                    dataType: 'json',
                    success: function (res) {
                        success(res)
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

        _dgDocument.datagrid('fixColumnSize');
        _dgDocument.datagrid('fixRowHeight');
    }

    var loadDataDevice = (customer_data_id) => {
        _dgDevice.datagrid({
            method: 'get',
            url: _rest + '/device/' + customer_data_id,
            loader: function (param, success, error) {
                let {method, url} = $(this).datagrid('options')

                if (method==null || url==null) return false

                $.ajax({
                    method: method,
                    url: url,
                    dataType: 'json',
                    success: function (res) {
                        success(res)
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

        _dgDevice.datagrid('fixColumnSize');
        _dgDevice.datagrid('fixRowHeight');
    }

    var formReset = () => {
        _ff.form('clear')
        
        _dgContact.datagrid('loadData', [])
        _dgDocument.datagrid('loadData', [])
        _dgDevice.datagrid('loadData', [])

        _area_product_customer.combogrid({
            method: 'get',
            url: URL_REST + '/area/customer/reset'
        })
        _area_product_promo_id.combobox('loadData', []);

        _picture_url = null;
        _device_picture_url = null;

        _area_product_customer_value = null;
        _area_product_promo_value = null;

        _btnSave.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:false})
        _btnCopy.linkbutton({disabled:false})
        
        _btnAddContact.linkbutton({disabled:true})
        _btnOkContact.linkbutton({disabled:true})
        _btnEditContact.linkbutton({disabled:true})
        _btnCancelContact.linkbutton({disabled:true})
        _btnRemoveContact.linkbutton({disabled:true})

        _btnAddDocument.linkbutton({disabled:true})
        _btnEditDocument.linkbutton({disabled:true})
        _btnRemoveDocument.linkbutton({disabled:true})
        _btnPreviewDocument.linkbutton({disabled:true})
        _btnSaveDocument.linkbutton({disabled:true})
        _btnCancelDocument.linkbutton({disabled:true})

        _btnAddDevice.linkbutton({disabled:true})
        _btnEditDevice.linkbutton({disabled:true})
        _btnRemoveDevice.linkbutton({disabled:true})
        _btnPreviewDevice.linkbutton({disabled:true})
        _btnSaveDevice.linkbutton({disabled:true})
        _btnCancelDevice.linkbutton({disabled:true})

        _service_trigger.textbox({disabled:true})
        _area_id.combobox({disabled:true})
        _area_product_customer.combogrid({disabled:true})
        _area_product_promo_id.combobox({disabled:true})
        _member_at.datebox({disabled:true})
        _active.switchbutton({disabled:true})

        _name.textbox({disabled:true})
        _gender.combobox({disabled:true})
        _email.textbox({disabled:true})
        _telp.numberbox({disabled:true})
        _handphone.numberbox({disabled:true})
        _fax.textbox({disabled:true})
        _address.textbox({disabled:true})
        _picture.filebox({disabled:true})
        _birthdate.datebox({disabled:true})
        _marital_status.combobox({disabled:true})
        _work_type.textbox({disabled:true})
        _child.numberbox({disabled:true})
    }

    var formEdit = () => {
        _btnSave.linkbutton({disabled:false})
        _btnEdit.linkbutton({disabled:true})
        _btnCopy.linkbutton({disabled:true})
        
        _btnAddContact.linkbutton({disabled:false})
        _btnOkContact.linkbutton({disabled:false})
        _btnEditContact.linkbutton({disabled:false})
        _btnCancelContact.linkbutton({disabled:false})
        _btnRemoveContact.linkbutton({disabled:false})

        _btnAddDocument.linkbutton({disabled:false})
        _btnEditDocument.linkbutton({disabled:false})
        _btnRemoveDocument.linkbutton({disabled:false})
        _btnPreviewDocument.linkbutton({disabled:false})
        _btnSaveDocument.linkbutton({disabled:false})
        _btnCancelDocument.linkbutton({disabled:false})

        _btnAddDevice.linkbutton({disabled:false})
        _btnEditDevice.linkbutton({disabled:false})
        _btnRemoveDevice.linkbutton({disabled:false})
        _btnPreviewDevice.linkbutton({disabled:false})
        _btnSaveDevice.linkbutton({disabled:false})
        _btnCancelDevice.linkbutton({disabled:false})

        _service_trigger.textbox({disabled:false})
        _area_id.combobox({disabled:false})
        _area_product_customer.combogrid({disabled:false})
        _area_product_promo_id.combobox({disabled:false})
        _member_at.datebox({disabled:false})
        _active.switchbutton({disabled:false})

        _name.textbox({disabled:false})
        _gender.combobox({disabled:false})
        _email.textbox({disabled:false})
        _telp.numberbox({disabled:false})
        _handphone.numberbox({disabled:false})
        _fax.textbox({disabled:false})
        _address.textbox({disabled:false})
        _picture.filebox({disabled:false})
        _birthdate.datebox({disabled:false})
        _marital_status.combobox({disabled:false})
        _work_type.textbox({disabled:false})
        _child.numberbox({disabled:false})
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
                    
                    loadDataContact(row.id);
                    loadDataDocument(row.id);
                    loadDataDevice(row.id);

                    let {
                        id,
                        code,
                        active,
                        member_at,
                        suspend_at,
                        terminate_at,
                        dismantle_at,
                        service_trigger,
                        area_id,
                        area_product_customer,
                        area_product_promo_id,

                        name,
                        gender,
                        email,
                        telp,
                        handphone,
                        fax,
                        address,
                        // picture,
                        birthdate,
                        marital_status,
                        work_type,
                        child,
                        picture_url,
                    } = response

                    _ff.form('load', {
                        id: id,
                        code: code,
                        active: active.toString(),
                        service_trigger: service_trigger,
                        member_at: member_at,
                        suspend_at: suspend_at,
                        terminate_at: terminate_at,
                        dismantle_at: dismantle_at,
                        area_id: area_id,
                        // area_product_customer: area_product_customer,

                        name: name,
                        gender: gender,
                        email: email,
                        telp: telp,
                        handphone: handphone,
                        fax: fax,
                        address: address,
                        // picture: picture,
                        birthdate: birthdate,
                        marital_status: marital_status,
                        work_type: work_type,
                        child: child,
                    });

                    _picture_url = picture_url;
                    _area_product_customer_value = area_product_customer
                    _area_product_promo_value = area_product_promo_id
                },
                error: function (xhr, status, error) {
                    Alert('error', 'Internal server error');
                }
            });
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData();
});