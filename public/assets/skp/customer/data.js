var _rest = URL_REST + '/customer'

var rowIndexContact = undefined;

var _tbs = $('#tbs');
var _dg = $('#dg');
var _ff = $('#ff');
var _ss = $('#ss');

var _dgContact = $('#dgContact')

var _btnAdd = $('#btnAdd');
var _btnSave = $('#btnSave');
var _btnEdit = $('#btnEdit');
var _btnCopy = $('#btnCopy');
var _btnRemove = $('#btnRemove');

var _btnAddContact = $('#btnAddContact');
var _btnOkContact = $('#btnOkContact');
var _btnEditContact = $('#bbtnEditContact');
var _btnCancelContact = $('#btnCancelContact');
var _btnRemoveContact = $('#btnRemoveContact');

var _id = $('#id');
var _area_id = $('#area_id');
var _area_product_customer = $('#area_product_customer');
var _member_at = $('#member_at');
var _active = $('#active');

var _name = $('#name');
var _gender = $('#gender');
var _email = $('#email');
var _telp = $('#telp');
var _handphone = $('#handphone');
var _fax = $('#fax');
var _address = $('#address');
var _picture = $('#picture');
var _birthdate = $('#birthdate');
var _marital_status = $('#marital_status');
var _work_type = $('#work_type');
var _child = $('#child');

loadData();

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
        });
    },
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
    onDblClickRow: editRowContact,
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
        {
            field:'active', title:'Active', width: 300,
            align:'center',
            editor: {
                type: 'checkbox',
                options: {
                    on:'Active',
                    off:'No Active'
                },
            },
        },
    ]]
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

_btnAdd.linkbutton({
    onClick: function() {
        _tbs.tabs({
            selected: 1
        })

        formEdit()

        _ff.form('clear')
    }
});

_btnSave.linkbutton({
    onClick: function() {
        if (_ff.form('validate')) {
            $.messager.progress();

            let g = _area_product_customer.combogrid('grid');
            let r = g.datagrid('getSelected');

            let formData = new FormData();

            formData.append('_token', $('meta[name="csrf-token"]').attr('content'))
            formData.append('id', _id.textbox('getValue'))
            formData.append('active', _active.switchbutton('options').checked)
            formData.append('member_at', _member_at.datebox('getValue'))
            formData.append('area_id', _area_id.combobox('getValue'))
            
            formData.append('customer_type_id', r.customer_type_id)
            formData.append('customer_segment_id', r.customer_segment_id)
            formData.append('product_id', r.product_id)
            formData.append('provinsi_id', r.provinsi_id)
            formData.append('city_id', r.city_id)
            formData.append('area_product_id', r.area_product_id)
            formData.append('area_product_customer_id', r.id)
            formData.append('product_type_id', r.product_type_id)
            formData.append('product_service_id', r.product_service_id)
            
            formData.append('name', _name.textbox('getValue'))
            formData.append('gender', _gender.textbox('getValue'))
            formData.append('email', _email.textbox('getValue'))
            formData.append('telp', _telp.numberbox('getValue'))
            formData.append('handphone', _handphone.numberbox('getValue'))
            formData.append('fax', _fax.textbox('getValue'))
            formData.append('address', _address.textbox('getValue'))
            formData.append('birthdate', _birthdate.datebox('getValue'))
            formData.append('marital_status', _marital_status.combobox('getValue'))
            formData.append('work_type', _work_type.textbox('getValue'))
            formData.append('child', _child.numberbox('getValue'))

            if (_picture.filebox('files')[0]) {
                formData.append('picture', _picture.filebox('files')[0])                
            }
            
            $.ajax({
                type: "POST",
                url: _rest,
                contentType: false,
                cache: false,
                processData: false,
                data: formData,
                success: function (response) {
                    $.messager.progress('close');
    
                    loadData()
                    loadDataContact(response.id)
    
                    $.messager.show({
                        title:'Info',
                        msg:'Data saved.',
                        timeout:5000,
                        showType:'slide'
                    });
                },
                error: function (xhr, status, error) {
                    $.messager.progress('close');
    
                    if (xhr.status == 422) {
                        let { responseJSON } = xhr
    
                        let msg = []
                        $.each(responseJSON.errors, function (key, value) {
                            msg.push(value[0])
                        });
    
                        Alert('warning', msg.join('<br />'))
                    } else {
                        Alert('error', 'Internal server error');
                    }
                },
            });
        }
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

_btnRemove.linkbutton({
    onClick: function() {
        let row = _dg.datagrid('getSelected')

        if (row) {
            $.messager.confirm('Confirmation', 'Are you sure delete this data?', function(r){
                if (r){
                    $.ajax({
                        type: "delete",
                        url: _rest + '/' + row.id,
                        dataType: "json",
                        success: function (response) {
                            loadData()

                            _tbs.tabs({
                                selected: 0
                            })

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
            Alert('warning', 'No selected data')
        }
    }
});

_btnAddContact.linkbutton({
    onClick: function () {
        if (rowIndexContact == undefined) {
            _dgContact.datagrid('appendRow', {
                active: 'Active',
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
    onClick: editRowContact()
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
                rowIndexContact = _dgContact.datagrid('getRowIndex', rowContact);

                $.messager.confirm('Confirmation', 'Are you sure delete this data?', function(r){
                    if (r){
                        if (row.id) {
                            $.ajax({
                                type: "delete",
                                url: _rest + '/contact/' + row.id,
                                dataType: "json",
                                success: function (response) {
                                    loadDataContact(_id.textbox('getValue'));

                                    $.messager.show({
                                        title:'Info',
                                        msg:'Data deleted.',
                                        timeout:5000,
                                        showType:'slide'
                                    })

                                    rowIndexContact = undefined;
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
                            
                            rowIndexContact = undefined;
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

function editRowContact () {
    if (rowIndexContact == undefined) {
        let rowContact = _dgContact.datagrid('getSelected');

        if (rowContact) {
            rowIndexContact = _dgContact.datagrid('getRowIndex', rowContact);

            _dgContact.datagrid('selectRow', rowIndexContact)
                .datagrid('beginEdit', rowIndexContact);
        } 
    } else {
        setTimeout(function(){
            _dgContact.datagrid('selectRow', rowIndexContact);
        },0);            
    }
}

function onEndEditContact (index, row) {
    var ed = $(this).datagrid('getEditor', {
        index: index,
        field: 'gender',
    });

    row.text = $(ed.target).combobox('getText')
}

function endEditingContact () {
    if (rowIndexContact == undefined){return true}
    if (_dgContact.datagrid('validateRow', rowIndexContact)){
        _dgContact.datagrid('endEdit', rowIndexContact);
        rowIndexContact = undefined;
        return true;
    } else {
        return false;
    }
}

function loadData () {
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

function formReset () {
    _ff.form('clear')

    _btnSave.linkbutton({disabled:true})
    _btnEdit.linkbutton({disabled:false})
    _btnCopy.linkbutton({disabled:false})

    // _dgProduct.datagrid('loadData', [])
    // _dgCustomer.datagrid('loadData', [])

    // _btnAddProduct.linkbutton({disabled:true})
    // _btnEditProduct.linkbutton({disabled:true})
    // _btnRemoveProduct.linkbutton({disabled:true})

    // _btnAddCustomer.linkbutton({disabled:true})
    // _btnEditCustomer.linkbutton({disabled:true})
    // _btnRemoveCustomer.linkbutton({disabled:true})

    _area_id.combobox({disabled:true})
    _area_product_customer.combogrid({disabled:true})
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

function formEdit () {
    _btnSave.linkbutton({disabled:false})
    _btnEdit.linkbutton({disabled:true})
    _btnCopy.linkbutton({disabled:true})

    // _btnAddProduct.linkbutton({disabled:false})
    // _btnEditProduct.linkbutton({disabled:false})
    // _btnRemoveProduct.linkbutton({disabled:false})

    // _btnAddCustomer.linkbutton({disabled:false})
    // _btnEditCustomer.linkbutton({disabled:false})
    // _btnRemoveCustomer.linkbutton({disabled:false})

    _area_id.combobox({disabled:false})
    _area_product_customer.combogrid({disabled:false})
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

function getData (row) {
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

                // loadDataProduct(row.id);
                // loadDataCustomer(row.id);

                let {
                    id,
                    code,
                    active,
                    member_at,
                    suspend_at,
                    terminate_at,
                    dismantle_at,
                    area_id,
                    area_product_customer,

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
                } = response

                _ff.form('load', {
                    id: id,
                    code: code,
                    active: active,
                    member_at: member_at,
                    suspend_at: suspend_at,
                    terminate_at: terminate_at,
                    dismantle_at: dismantle_at,
                    area_id: area_id,

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

                setTimeout(() => {
                    _area_product_customer.combogrid('setValue', area_product_customer)
                }, 3000);
            },
            error: function (xhr, status, error) {
                Alert('error', 'Internal server error');
            }
        });
    } else {
        Alert('warning', 'ID not found')
    }
}
