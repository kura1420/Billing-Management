"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/product-promo'

    var rowIndexArea = undefined;

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');

    let _dgArea = $('#dgArea');
    let _ffArea = $('#ffArea');
    let _wArea = $('#wArea');
    
    let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    let _btnCopy = $('#btnCopy');
    let _btnRemove = $('#btnRemove');
    let _btnPreview = $('#btnPreview');
    var _image_url = null;

    let _btnAddArea = $('#btnAddArea');
    let _btnEditArea = $('#btnEditArea');
    let _btnRemoveArea = $('#btnRemoveArea');
    let _btnOkArea = $('#btnOkArea');
    let _btnCancelArea = $('#btnCancelArea');
    
    let _id = $('#id');
    let _code = $('#code');
    let _name = $('#name');
    let _desc = $('#desc');
    let _active = $('#active');
    let _start = $('#start');
    let _end = $('#end');
    let _image = $('#image');

    let _type = $('#type');
    let _discount = $('#discount');
    let _until_payment = $('#until_payment');
    let _product_type_id = $('#product_type_id');
    let _product_service_id = $('#product_service_id');

    let _a_area_id = $('#a_area_id');
    let _a_area_product = $('#a_area_product');
    let _a_active = $('#a_active');

    _type.combobox({
        valueField:'id',
        textField:'name',
        data: [{
            "id":"PAY",
            "name":"Payment"
        },{
            "id":"SER",
            "name":"Service"
        }]
    });

    _type.combobox({
        onSelect: function (record) {
            if (record.id == 'SER') {
                _product_type_id.combobox({disabled:false});
                _product_service_id.combobox({disabled:false});
            } else {
                _product_type_id.combobox({disabled:true});
                _product_service_id.combobox({disabled:true});                
            }
        }
    });

    _product_type_id.combobox({
        valueField:'id',
        textField:'name',
        url: URL_REST + '/product-type/lists',
        onSelect: function (record) {
            _product_service_id.combobox({
                onBeforeLoad: function (param) {
                    param.product_type_id = record.id
                }
            });

            _product_service_id.combobox('reload', URL_REST + '/product-service/lists');
        }
    });

    _a_area_id.combobox({
        valueField:'id',
        textField:'name',
        url: URL_REST + '/area/lists',
        onSelect: function (record) {
            let url = URL_REST + '/area/product/' + record.id

            _a_area_product.combogrid({
                panelWidth:600,
                fitColumns:true,
                idField:'id',
                textField:'product_service_name',
                method:'get',
                url: url,
                columns:[[
                    {field:'provinsi_name',title:'Provinsi'},
                    {field:'city_name',title:'City'},
                    {field:'product_type_name',title:'Type'},
                    {field:'product_service_name',title:'Service'},
                    {
                        field:'active',title:'Active',
                        formatter: function (value, row) {
                            return value == 1 ? 'Active' : 'No Active'
                        }
                    },
                ]],
            });
        }
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

    _dgArea.datagrid({
        singleSelect:true,
        collapsible:true,
        border:false,
        fitColumns:true,
        pagination:true,
        rownumbers:true,
        toolbar:'#tbArea',
        onDblClickRow: function () {
            let row = _dgArea.datagrid('getSelected');
            rowIndexArea = _dgArea.datagrid('getRowIndex', row);

            _wArea.window('open');

            _ffArea.form('load', {
                a_area_id: row.area_id,
                a_area_product: row.area_product_id,
                a_active: row.active == 1 ? "on" : "off",
            });
        }
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

                _btnPreview.linkbutton({disabled:true})
            }
        }
    });

    _btnAdd.linkbutton({
        onClick: function() {
            _tbs.tabs({
                selected: 1
            });
        
            formEdit()
    
            _ff.form('clear');

            _dgArea.datagrid('loadData', [])

            _code.textbox('readonly', false)
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
    
                    param.active = _active.switchbutton('options').checked
                    param.areas = JSON.stringify(_dgArea.datagrid('getRows'))
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
                        let parse = JSON.parse(res)

                        loadData()
                        loadDataArea(parse.id)
    
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
    
    _btnCopy.linkbutton({
        onClick: function () {  
            let row = _dg.datagrid('getSelected')
        
            if (row) {
                $.get(_rest + "/" + row.id,
                    function (data, textStatus, jqXHR) {
                        delete data.id
    
                        formEdit()
    
                        _ff.form('load', data)

                        _code.textbox({readonly:false})

                        _btnPreview.linkbutton({disabled:true})
    
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

    _btnPreview.linkbutton({
        onClick: function () {
            window.open(_image_url)
        }
    });

    _btnAddArea.linkbutton({
        onClick: function () {
            _wArea.window('open');
        }
    });

    _btnOkArea.linkbutton({
        onClick: function () {
            if (_ffArea.form('validate')) {
                let g = _a_area_product.combogrid('grid');
                let r = g.datagrid('getSelected');

                let a_add_area_id = _a_area_id.combobox('getValue');
                let a_add_area_name = _a_area_id.combobox('getText');            

                if (rowIndexArea !== undefined) {
                    _dgArea.datagrid('updateRow', {
                        index: rowIndexArea,
                        row: {
                            area_id: a_add_area_id,
                            area_name: a_add_area_name,

                            provinsi_id: r.provinsi_id,
                            city_id: r.city_id,
                            area_product_id: r.id,

                            product_type_id: r.product_type_id,
                            product_type_name: r.product_type_name,

                            product_service_id: r.product_service_id,
                            product_service_name: r.product_service_name,

                            active: _a_active.switchbutton('options').checked,
                        }
                    });
                } else {
                    _dgArea.datagrid('appendRow', {
                        id: null,

                        area_id: a_add_area_id,
                        area_name: a_add_area_name,

                        provinsi_id: r.provinsi_id,
                        city_id: r.city_id,
                        area_product_id: r.id,

                        product_type_id: r.product_type_id,
                        product_type_name: r.product_type_name,

                        product_service_id: r.product_service_id,
                        product_service_name: r.product_service_name,

                        active: _a_active.switchbutton('options').checked,
                    });                    
                }

                rowIndexArea = undefined

                _dgArea.datagrid('fixColumnSize');
                _dgArea.datagrid('fixRowHeight');

                _wArea.window('close');
                _ffArea.form('clear');
            }
        }
    });

    _btnEditArea.linkbutton({
        onClick: function () {
            let row = _dgArea.datagrid('getSelected')

            if (row) {
                rowIndexArea = _dgArea.datagrid('getRowIndex', row)

                _wArea.window('open');

                _ffArea.form('load', {
                    a_area_id: row.area_id,
                    a_area_product: row.area_product_id,
                    a_active: row.active == 1 ? "on" : "off",
                });
            } else {
                Alert('warning', 'No Data selected');
            }
        }
    });

    _btnCancelArea.linkbutton({
        onClick: function () {
            _wArea.window('close');

            _ffArea.form('clear');

            rowIndexArea = undefined;
        }
    });

    _btnRemoveArea.linkbutton({
        onClick: function () {  
            let row = _dgArea.datagrid('getSelected');

            if (row) {
                rowIndexArea = _dgArea.datagrid('getRowIndex', rowIndexArea);

                $.messager.confirm('Confirmation', 'Are you sure delete this data?', function(r){
                    if (r){
                        if (row.id) {
                            $.ajax({
                                type: "delete",
                                url: _rest + '/area/' + row.id,
                                dataType: "json",
                                success: function (response) {
                                    loadDataArea(_id.textbox('getValue'))

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
                            _dgArea.datagrid('cancelEdit', rowIndexArea)
                                .datagrid('deleteRow', rowIndexArea);

                            $.messager.show({
                                title:'Info',
                                msg:'Data deleted.',
                                timeout:5000,
                                showType:'slide'
                            });
                        }
                    }

                    rowIndexArea = undefined;
                });
            } else {
                Alert('warning', 'No selected data')
            }
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
        })        

        _dg.datagrid('fixColumnSize');
        _dg.datagrid('fixRowHeight');
    }

    var loadDataArea = (product_promo_id) => {
        _dgArea.datagrid({
            method: 'get',
            url: _rest + '/area/' + product_promo_id,
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

        _dgArea.datagrid('fixColumnSize');
        _dgArea.datagrid('fixRowHeight');
    }

    var formReset = () => {
        _ff.form('clear')

        _dgArea.datagrid('loadData', [])

        _btnSave.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:false})
        _btnCopy.linkbutton({disabled:false})

        _btnAddArea.linkbutton({disabled:true})
        _btnEditArea.linkbutton({disabled:true})
        _btnRemoveArea.linkbutton({disabled:true})

        _name.textbox({disabled:true})
        _desc.textbox({disabled:true})
        _active.switchbutton({disabled:true})
        _start.datebox({disabled:true})
        _end.datebox({disabled:true})
        _image.filebox({disabled:true})

        _type.combobox({disabled:true})
        _discount.numberbox({disabled:true})
        _until_payment.numberbox({disabled:true})
        _product_type_id.combobox({disabled:true})
        _product_service_id.combobox({disabled:true})
    }

    var formEdit = () => {
        _btnSave.linkbutton({disabled:false})
        _btnEdit.linkbutton({disabled:true})
        _btnCopy.linkbutton({disabled:true})

        _btnAddArea.linkbutton({disabled:false})
        _btnEditArea.linkbutton({disabled:false})
        _btnRemoveArea.linkbutton({disabled:false})
    
        _name.textbox({disabled:false})
        _desc.textbox({disabled:false})
        _active.switchbutton({disabled:false})
        _start.datebox({disabled:false})
        _end.datebox({disabled:false})
        _image.filebox({disabled:false})

        _type.combobox({disabled:false})
        _discount.numberbox({disabled:false})
        _until_payment.numberbox({disabled:false})
        // _product_service_id.combobox({disabled:false})
    }

    var getData = (row) => {
        if (row) {
            _image_url = null

            $.ajax({
                type: "get",
                url: _rest + '/' + row.id,
                dataType: "json",
                success: function (response) {    
                    _tbs.tabs({
                        selected: 1
                    });
            
                    formEdit()

                    loadDataArea(row.id)

                    _ff.form('load', response)

                    _code.textbox({readonly:true})

                    if (response.image_url) {
                        _btnPreview.linkbutton({disabled:false})
        
                        _image_url = response.image_url
                    }
                }
            });
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData()
});