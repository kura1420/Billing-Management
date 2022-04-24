"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/billing-type'

    var rowIndex = undefined;

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');
    let _dgProduct = $('#dgProduct');
    let _ffProduct = $('#ffProduct');
    let _wProduct = $('#wProduct');
    
    let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    let _btnCopy = $('#btnCopy');
    let _btnRemove = $('#btnRemove');

    let _btnAddProduct = $('#btnAddProduct');
    let _btnEditProduct = $('#btnEditProduct');
    let _btnRemoveProduct = $('#btnRemoveProduct');
    let _btnOkProduct = $('#btnOkProduct');
    let _btnCancelProduct = $('#btnCancelProduct');
    
    let _id = $('#id');
    let _code = $('#code');
    let _name = $('#name');
    let _desc = $('#desc');
    let _notif = $('#notif');
    let _suspend = $('#suspend');
    let _terminated = $('#terminated');
    let _active = $('#active');

    let _product_type_id = $('#product_type_id');
    let _product_service_id = $('#product_service_id');

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

    _dgProduct.datagrid({
        singleSelect:true,
        collapsible:true,
        border:false,
        fitColumns:true,
        pagination:true,
        rownumbers:true,
        toolbar:'#tbProduct',
        onDblClickRow: function () {
            let row = _dgProduct.datagrid('getSelected');
            rowIndex = _dgProduct.datagrid('getRowIndex', row);

            _wProduct.window('open');

            _ffProduct.form('load', {
                product_type_id: row.product_type_id,
                product_service_id: row.product_service_id,
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

                _code.textbox('readonly', true)
            }
        }
    });

    _btnAdd.linkbutton({
        onClick: function() {
            _tbs.tabs({
                selected: 1
            })
        
            formEdit()

            _code.textbox('readonly', false)
    
            _ff.form('clear')
        }
    });

    _btnSave.linkbutton({
        onClick: function() {
            $.messager.progress();

            $.ajax({
                type: "POST",
                url: _rest,
                data: {
                    id: _id.textbox('getValue'),
                    code: _code.textbox('getValue'),
                    name: _name.textbox('getValue'),
                    desc: _desc.textbox('getValue'),
                    active: _active.switchbutton('options').checked,
                    notif: _notif.numberbox('getValue'),
                    suspend: _suspend.numberbox('getValue'),
                    terminated: _terminated.numberbox('getValue'),
                    products: _dgProduct.datagrid('getRows'),
                },
                dataType: "json",
                success: function (response) {
                    $.messager.progress('close');

                    loadData()
                    loadDataProduct(response.id)
    
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

                        _code.textbox('readonly', false)
    
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

    _btnAddProduct.linkbutton({
        onClick: function () {
            _p_product_service_id.combobox('loadData', []);
            
            _wProduct.window('open');
        },
    });

    _btnEditProduct.linkbutton({
        onClick: function () {
            let row = _dgProduct.datagrid('getSelected');

            if (row) {
                rowIndex = _dgProduct.datagrid('getRowIndex', row);

                _wProduct.window('open');

                _ffProduct.form('load', {
                    product_type_id: row.product_type_id,
                    product_service_id: row.product_service_id,
                });
            } else {
                Alert('warning', 'No Data selected');                
            }
        },
    });

    _btnOkProduct.linkbutton({
        onClick: function () {
            if (_ffProduct.form('validate')) {
                let add_product_type_id = _product_type_id.combobox('getValue');
                let add_product_type_name = _product_type_id.combobox('getText');

                let add_product_service_id = _product_service_id.combobox('getValue');
                let add_product_service_name = _product_service_id.combobox('getText');

                if (rowIndex !== undefined) {
                    _dgProduct.datagrid('updateRow', {
                        index: rowIndex,
                        row: {
                            product_type_id: add_product_type_id,
                            product_type_name: add_product_type_name,
        
                            product_service_id: add_product_service_id,
                            product_service_name: add_product_service_name,
                        }
                    });
                } else {
                    _dgProduct.datagrid('appendRow', {
                        id: null,

                        product_type_id: add_product_type_id,
                        product_type_name: add_product_type_name,
    
                        product_service_id: add_product_service_id,
                        product_service_name: add_product_service_name,
                    });
                }            

                rowIndex = undefined;   

                _dgProduct.datagrid('fixColumnSize');
                _dgProduct.datagrid('fixRowHeight');

                _wProduct.window('close');
                _ffProduct.form('clear');
            }
        }
    });

    _btnCancelProduct.linkbutton({
        onClick: function () {
            _wProduct.window('close');

            _ffProduct.form('clear');

            rowIndex = undefined;
        }
    });

    _btnRemoveProduct.linkbutton({
        onClick: function () {
            let row = _dgProduct.datagrid('getSelected')

            if (row) {
                $.messager.confirm('Confirmation', 'Are you sure delete this data?', function(r){
                    if (r){
                        if (row.id) {
                            $.ajax({
                                type: "delete",
                                url: _rest + '/product/' + row.id,
                                dataType: "json",
                                success: function (response) {
                                    loadDataProduct(_id.textbox('getValue'));                                    
        
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
                            loadDataProduct(_id.textbox('getValue'));

                            $.messager.show({
                                title:'Info',
                                msg:'Data deleted.',
                                timeout:5000,
                                showType:'slide'
                            });
                        }
                    }
                });
            } else {
                Alert('warning', 'No selected data')
            }
        },
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

    var loadDataProduct = (billing_type_id) => {
        _dgProduct.datagrid({
            method: 'get',
            url: _rest + '/product/' + billing_type_id,
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

        _dgProduct.datagrid('fixColumnSize');
        _dgProduct.datagrid('fixRowHeight');
    }

    var formReset = () => {
        _ff.form('clear')

        _btnSave.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:false})
        _btnCopy.linkbutton({disabled:false})

        _dgProduct.datagrid('loadData', [])

        _btnAddProduct.linkbutton({disabled:true})
        _btnEditProduct.linkbutton({disabled:true})
        _btnRemoveProduct.linkbutton({disabled:true})

        _name.textbox({disabled:true})
        _desc.textbox({disabled:true})
        _notif.numberbox({disabled:true})
        _suspend.numberbox({disabled:true})
        _terminated.numberbox({disabled:true})
        _active.switchbutton({disabled:true})
    }

    var formEdit = () => {
        _btnSave.linkbutton({disabled:false})
        _btnEdit.linkbutton({disabled:true})
        _btnCopy.linkbutton({disabled:true})

        _btnAddProduct.linkbutton({disabled:false})
        _btnEditProduct.linkbutton({disabled:false})
        _btnRemoveProduct.linkbutton({disabled:false})
    
        _name.textbox({disabled:false})
        _desc.textbox({disabled:false})
        _notif.numberbox({disabled:false})
        _suspend.numberbox({disabled:false})
        _terminated.numberbox({disabled:false})
        _active.switchbutton({disabled:false})
    }

    var getData = (row) => {
        if (row) {
            _ff.form('load', _rest + '/' + row.id)
    
            _tbs.tabs({
                selected: 1
            })
    
            formEdit()
            
            loadDataProduct(row.id);

            _code.textbox('readonly', true)
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData()
});