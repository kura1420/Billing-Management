"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/product-promo'

    var editIndex = undefined;

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');

    let _dgArea = $('#dgArea');
    
    let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    let _btnCopy = $('#btnCopy');
    let _btnRemove = $('#btnRemove');
    let _btnPreview = $('#btnPreview');
    var _image_url = null;

    let _btnAddArea = $('#btnAddArea');
    let _btnOkArea = $('#btnOkArea');
    let _btnEditArea = $('#btnEditArea');
    let _btnCancelArea = $('#btnCancelArea');
    let _btnRemoveArea = $('#btnRemoveArea');
    
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
        remoteSort:true,
        toolbar:'#tbArea',
        onDblClickRow: function (index, row) {
            onClickCell()
        },
        onEndEdit: function (index,row,changes) {
            onEndEdit()
        },
        columns: [[
            {
                field:'area_id', title: 'Area', width: 300,
                formatter: function (value, row){
                    return row.name;
                },
                editor: {
                    type: 'combobox',
                    options: {
                        valueField:'id',
                        textField:'name',
                        url: URL_REST + '/area/lists',
                        required: true,
                    }
                },
            },
            {
                field:'active', title: 'Active', width: 300,
                editor: {
                    type: 'checkbox',
                    options: {
                        on: 'Yes',
                        off: 'No'
                    }
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
            if (endEditing()) {
                _dgArea.datagrid('appendRow', {
                    active: 'Yes',
                });

                editIndex = _dgArea.datagrid('getRows').length - 1;

                _dgArea.datagrid('selectRow', editIndex)
                    .datagrid('beginEdit', editIndex);
            }
        }
    });

    _btnOkArea.linkbutton({
        onClick: function () {
            if (endEditing()) {
                _dgArea.datagrid('acceptChanges');
            }
        }
    });

    _btnEditArea.linkbutton({
        onClick: function () {
            onClickCell()
        }
    });

    _btnCancelArea.linkbutton({
        onClick: function () {
            _dgArea.datagrid('rejectChanges');

            editIndex = undefined;
        }
    });

    _btnRemoveArea.linkbutton({
        onClick: function () {
            let row = _dgArea.datagrid('getSelected')
    
            if (row) {
                let index = _dgArea.datagrid('getRowIndex', row)

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
                            _dgArea.datagrid('cancelEdit', index)
                                .datagrid('deleteRow', index)

                            $.messager.show({
                                title:'Info',
                                msg:'Data deleted.',
                                timeout:5000,
                                showType:'slide'
                            })
                        }
                    }
                })
            } else {
                Alert('warning', 'No selected data');            
            }
        }
    });

    var endEditing = () => {
        if (editIndex == undefined) {return true}

        if (_dgArea.datagrid('validateRow', editIndex)) {
            let ed = _dgArea.datagrid('getEditor', {
                index: editIndex,
                field: 'area_id',
            })

            let row = _dgArea.datagrid('selectRow', editIndex)

            row.name = $(ed.target).combobox('getText');

            _dgArea.datagrid('endEdit', editIndex)

            editIndex = undefined

            return true
        } else {
            return false
        }
    }

    var onClickCell = () => {
        let row = _dgArea.datagrid('getSelected')

        if (row) {
            let index = _dgArea.datagrid('getRowIndex', row)

            if (editIndex !== index) {
                if (endEditing()) {
                    _dgArea.datagrid('selectRow', index)
                        .datagrid('beginEdit', index)

                    editIndex = index
                } else {
                    setTimeout(function(){
                        _dgArea.datagrid('selectRow', editIndex);
                    },0);
                }
            }
        } else {
            Alert('warning', 'No selected data');
        }
    }

    var onEndEdit = () => {
        let row = _dgArea.datagrid('getSelected')
        let index = _dgArea.datagrid('getRowIndex', row)

        if (editIndex == index) {
            let ed = _dgArea.datagrid('getEditor', {
                index: editIndex,
                field: 'area_id',
            })

            row.name = $(ed.target).combobox('getText');
        } else {
            setTimeout(function(){
                _dgArea.datagrid('selectRow', editIndex);
            },0);
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

                    setTimeout(() => {
                        _product_service_id.combobox('setValue', response.product_service_id);
                    }, 3000);

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