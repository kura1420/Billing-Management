"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/area'

    var rowIndexProduct = undefined;
    var rowIndexCustomer = undefined;

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');

    let _dgProduct = $('#dgProduct');
    let _ffProduct = $('#ffProduct');
    let _wProduct = $('#wProduct');

    let _dgCustomer = $('#dgCustomer');
    let _ffCustomer = $('#ffCustomer');
    let _wCustomer = $('#wCustomer');

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

    let _btnAddCustomer = $('#btnAddCustomer');
    let _btnEditCustomer = $('#btnEditCustomer');
    let _btnRemoveCustomer = $('#btnRemoveCustomer');
    let _btnOkCustomer = $('#btnOkCustomer');
    let _btnCancelCustomer = $('#btnCancelCustomer');

    let _id = $('#id');
    let _code = $('#code');
    let _name = $('#name');
    let _desc = $('#desc');
    let _ppn_tax_id = $('#ppn_tax_id');
    let _active = $('#active');

    let _p_provinsi_id = $('#p_provinsi_id');
    let _p_city_id = $('#p_city_id');
    let _p_product_type_id = $('#p_product_type_id');
    let _p_product_service_id = $('#p_product_service_id');
    let _p_active = $('#p_active');

    let _c_area_product = $('#c_area_product');
    let _c_customer_type_id = $('#c_customer_type_id');
    let _c_customer_segment_id = $('#c_customer_segment_id');
    let _c_active = $('#c_active');

    _ppn_tax_id.combobox({
        valueField:'id',
        textField:'name',
        url: URL_REST + '/tax/lists'
    });

    _p_provinsi_id.combobox({
        valueField:'id',
        textField:'name',
        url: URL_REST + '/provinsi/lists',
        onSelect: function (record) {
            _p_city_id.combobox({
                onBeforeLoad: function (param) {
                    param.provinsi_id = record.id
                }
            });

            _p_city_id.combobox('reload', URL_REST + '/city/lists');
        }
    });

    _p_product_type_id.combobox({
        valueField:'id',
        textField:'name',
        url: URL_REST + '/product-type/lists',
        onSelect: function (record) {
            _p_product_service_id.combobox({
                onBeforeLoad: function (param) {
                    param.product_type_id = record.id
                }
            });

            _p_product_service_id.combobox('reload', URL_REST + '/product-service/lists');
        }
    });

    _c_customer_type_id.combobox({
        valueField:'id',
        textField:'name',
        url: URL_REST + '/customer-type/lists',
    });

    _c_customer_segment_id.combobox({
        valueField:'id',
        textField:'name',
        url: URL_REST + '/customer-segment/lists',
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
            rowIndexProduct = _dgProduct.datagrid('getRowIndex', row);

            _wProduct.window('open');

            _ffProduct.form('load', {
                p_provinsi_id: row.provinsi_id,
                p_city_id: row.city_id,
                p_product_type_id: row.product_type_id,
                p_product_service_id: row.product_service_id,
                p_active: row.active == true ? 1 : 0,
            });
        }
    });

    _dgCustomer.datagrid({
        singleSelect:true,
        collapsible:true,
        border:false,
        fitColumns:true,
        pagination:true,
        rownumbers:true,
        toolbar:'#tbCustomer',
        onDblClickRow: function () {
            let row = _dgCustomer.datagrid('getSelected');
            rowIndexCustomer = _dgCustomer.datagrid('getRowIndex', row);

            _wCustomer.window('open');

            _c_area_product.combogrid({
                panelWidth:600,
                fitColumns:true,
                idField:'id',
                textField:'product_service_name',
                method:'get',
                url: _rest + '/product/' + _id.textbox('getValue'),
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

            _ffCustomer.form('load', {
                c_area_product: row.area_product_id,
                c_customer_type_id: row.customer_type_id,
                c_customer_segment_id: row.customer_segment_id,
                c_active: row.active == true ? 1 : 0,
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

                _code.textbox({readonly:true})
            }
        }
    });

    _btnAdd.linkbutton({
        onClick: function() {
            _tbs.tabs({
                selected: 1
            })

            formEdit()

            _dgProduct.datagrid('loadData', [])
            _dgCustomer.datagrid('loadData', [])

            _code.textbox('readonly', false)

            _ff.form('clear')
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
                    param.active = _active.switchbutton('options').checked

                    param.products = JSON.stringify(_dgProduct.datagrid('getRows')),
                    param.customers = JSON.stringify(_dgCustomer.datagrid('getRows')),
                    
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
                        loadDataProduct(parse.id)
                        loadDataCustomer(parse.id)
    
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
            _p_city_id.combobox('loadData', []);
            _p_product_service_id.combobox('loadData', []);

            _wProduct.window('open');
        },
    });

    _btnEditProduct.linkbutton({
        onClick: function () {
            let row = _dgProduct.datagrid('getSelected');

            if (row) {
                rowIndexProduct = _dgProduct.datagrid('getRowIndex', row);

                _wProduct.window('open');

                _ffProduct.form('load', {
                    p_provinsi_id: row.provinsi_id,
                    p_city_id: row.city_id,
                    p_product_type_id: row.product_type_id,
                    p_product_service_id: row.product_service_id,
                    p_active: row.active == true ? 1 : 0,
                });
            } else {
                Alert('warning', 'No Data selected');
            }
        },
    });

    _btnOkProduct.linkbutton({
        onClick: function () {
            if (_ffProduct.form('validate')) {
                let p_add_provinsi_id = _p_provinsi_id.combobox('getValue');
                let p_add_provinsi_name = _p_provinsi_id.combobox('getText');

                let p_add_city_id = _p_city_id.combobox('getValue');
                let p_add_city_name = _p_city_id.combobox('getText');

                let p_add_product_type_id = _p_product_type_id.combobox('getValue');
                let p_add_product_type_name = _p_product_type_id.combobox('getText');

                let p_add_product_service_id = _p_product_service_id.combobox('getValue');
                let p_add_product_service_name = _p_product_service_id.combobox('getText');

                if (rowIndexProduct !== undefined) {
                    _dgProduct.datagrid('updateRow', {
                        index: rowIndexProduct,
                        row: {
                            provinsi_id: p_add_provinsi_id,
                            provinsi_name: p_add_provinsi_name,

                            city_id: p_add_city_id,
                            city_name: p_add_city_name,

                            product_type_id: p_add_product_type_id,
                            product_type_name: p_add_product_type_name,

                            product_service_id: p_add_product_service_id,
                            product_service_name: p_add_product_service_name,

                            active: _p_active.switchbutton('options').checked
                        }
                    });
                } else {
                    _dgProduct.datagrid('appendRow', {
                        id: null,

                        provinsi_id: p_add_provinsi_id,
                        provinsi_name: p_add_provinsi_name,

                        city_id: p_add_city_id,
                        city_name: p_add_city_name,

                        product_type_id: p_add_product_type_id,
                        product_type_name: p_add_product_type_name,

                        product_service_id: p_add_product_service_id,
                        product_service_name: p_add_product_service_name,

                        active: _p_active.switchbutton('options').checked,
                    });
                }

                rowIndexProduct = undefined;

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

            rowIndexProduct = undefined;
        }
    });

    _btnRemoveProduct.linkbutton({
        onClick: function () {
            let row = _dgProduct.datagrid('getSelected')

            if (row) {
                rowIndexProduct = _dgProduct.datagrid('getRowIndex', row);

                $.messager.confirm('Confirmation', 'Are you sure delete this data?', function(r){
                    if (r){
                        if (row.id) {
                            $.ajax({
                                type: "delete",
                                url: _rest + '/product/' + row.id,
                                dataType: "json",
                                success: function (response) {
                                    loadDataProduct(_id.textbox('getValue'))

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
                            _dgProduct.datagrid('cancelEdit', rowIndexProduct)
                                .datagrid('deleteRow', rowIndexProduct);

                            $.messager.show({
                                title:'Info',
                                msg:'Data deleted.',
                                timeout:5000,
                                showType:'slide'
                            });
                        }
                    }

                    rowIndexProduct = undefined;
                });
            } else {
                Alert('warning', 'No selected data')
            }
        },
    });

    _btnAddCustomer.linkbutton({
        onClick: function () {
            _wCustomer.window('open');

            _c_area_product.combogrid({
                panelWidth:600,
                fitColumns:true,
                idField:'id',
                textField:'product_service_name',
                method:'get',
                url: _rest + '/product/' + _id.textbox('getValue'),
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

    _btnEditCustomer.linkbutton({
        onClick: function () {
            let row = _dgCustomer.datagrid('getSelected')

            if (row) {
                rowIndexCustomer = _dgCustomer.datagrid('getRowIndex', row)

                _wCustomer.window('open');

                _ffCustomer.form('load', {
                    c_area_product: row.area_product_id,
                    c_customer_type_id: row.customer_type_id,
                    c_customer_segment_id: row.customer_segment_id,
                    c_active: row.active == true ? 1 : 0,
                });
            } else {
                Alert('warning', 'No Data selected');
            }
        }
    });

    _btnOkCustomer.linkbutton({
        onClick: function () {
            if (_ffCustomer.form('validate')) {
                let g = _c_area_product.combogrid('grid');
                let r = g.datagrid('getSelected');

                let c_add_customer_type_id = _c_customer_type_id.combobox('getValue');
                let c_add_customer_type_name = _c_customer_type_id.combobox('getText');

                let c_add_customer_segment_id = _c_customer_segment_id.combobox('getValue');
                let c_add_customer_segment_name = _c_customer_segment_id.combobox('getText');

                if (rowIndexCustomer !== undefined) {
                    _dgCustomer.datagrid('updateRow', {
                        index: rowIndexCustomer,
                        row: {
                            area_product_id: r.id,

                            provinsi_id: r.provinsi_id,
                            provinsi_name: r.provinsi_name,

                            city_id: r.city_id,
                            city_name: r.city_name,

                            product_type_id: r.product_type_id,
                            product_type_name: r.product_type_name,

                            product_service_id: r.product_service_id,
                            product_service_name: r.product_service_name,

                            customer_type_id: c_add_customer_type_id,
                            customer_type_name: c_add_customer_type_name,

                            customer_segment_id: c_add_customer_segment_id,
                            customer_segment_name: c_add_customer_segment_name,

                            active: _c_active.switchbutton('options').checked,
                        }
                    });
                } else {
                    _dgCustomer.datagrid('appendRow', {
                        id: null,

                        area_product_id: r.id,

                        provinsi_id: r.provinsi_id,
                        provinsi_name: r.provinsi_name,

                        city_id: r.city_id,
                        city_name: r.city_name,

                        product_type_id: r.product_type_id,
                        product_type_name: r.product_type_name,

                        product_service_id: r.product_service_id,
                        product_service_name: r.product_service_name,

                        customer_type_id: c_add_customer_type_id,
                        customer_type_name: c_add_customer_type_name,

                        customer_segment_id: c_add_customer_segment_id,
                        customer_segment_name: c_add_customer_segment_name,

                        active: _c_active.switchbutton('options').checked,
                    });
                }

                rowIndexCustomer = undefined;

                _dgCustomer.datagrid('fixColumnSize');
                _dgCustomer.datagrid('fixRowHeight');

                _wCustomer.window('close');
                _ffCustomer.form('clear');
            }
        },
    });

    _btnCancelCustomer.linkbutton({
        onClick: function () {
            _wCustomer.window('close');

            _ffCustomer.form('clear');

            rowIndexCustomer = undefined;
        }
    });

    _btnRemoveCustomer.linkbutton({
        onClick: function () {
            let row = _dgCustomer.datagrid('getSelected');

            if (row) {
                rowIndexCustomer = _dgCustomer.datagrid('getRowIndex', rowIndexCustomer);

                $.messager.confirm('Confirmation', 'Are you sure delete this data?', function(r){
                    if (r){
                        if (row.id) {
                            $.ajax({
                                type: "delete",
                                url: _rest + '/customer/' + row.id,
                                dataType: "json",
                                success: function (response) {
                                    loadDataCustomer(_id.textbox('getValue'))

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
                            _dgCustomer.datagrid('cancelEdit', rowIndexCustomer)
                                .datagrid('deleteRow', rowIndexCustomer);

                            $.messager.show({
                                title:'Info',
                                msg:'Data deleted.',
                                timeout:5000,
                                showType:'slide'
                            });
                        }
                    }

                    rowIndexCustomer = undefined;
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
        });

        _dg.datagrid('fixColumnSize');
        _dg.datagrid('fixRowHeight');
    }

    var loadDataProduct = (area_id) => {
        _dgProduct.datagrid({
            method: 'get',
            url: _rest + '/product/' + area_id,
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

    var loadDataCustomer = (area_id) => {
        _dgCustomer.datagrid({
            method: 'get',
            url: _rest + '/customer/' + area_id,
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

        _dgCustomer.datagrid('fixColumnSize');
        _dgCustomer.datagrid('fixRowHeight');
    }

    var formReset = () => {
        _ff.form('clear')

        _btnSave.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:false})
        _btnCopy.linkbutton({disabled:false})

        _dgProduct.datagrid('loadData', [])
        _dgCustomer.datagrid('loadData', [])

        _btnAddProduct.linkbutton({disabled:true})
        _btnEditProduct.linkbutton({disabled:true})
        _btnRemoveProduct.linkbutton({disabled:true})

        _btnAddCustomer.linkbutton({disabled:true})
        _btnEditCustomer.linkbutton({disabled:true})
        _btnRemoveCustomer.linkbutton({disabled:true})

        _name.textbox({disabled:true})
        _desc.textbox({disabled:true})
        _ppn_tax_id.combobox({disabled:true})
        _active.switchbutton({disabled:true})
    }

    var formEdit = () => {
        _btnSave.linkbutton({disabled:false})
        _btnEdit.linkbutton({disabled:true})
        _btnCopy.linkbutton({disabled:true})

        _btnAddProduct.linkbutton({disabled:false})
        _btnEditProduct.linkbutton({disabled:false})
        _btnRemoveProduct.linkbutton({disabled:false})

        _btnAddCustomer.linkbutton({disabled:false})
        _btnEditCustomer.linkbutton({disabled:false})
        _btnRemoveCustomer.linkbutton({disabled:false})

        _name.textbox({disabled:false})
        _desc.textbox({disabled:false})
        _ppn_tax_id.combobox({disabled:false})
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
            loadDataCustomer(row.id);

            _code.textbox({readonly:true})
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData();
});