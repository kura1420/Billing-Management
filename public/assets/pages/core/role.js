"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/app-role'

    var editIndexDepartement = undefined;
    var editIndexMenu = undefined;

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');
    
    let _dgDepartement = $('#dgDepartement');
    let _dgMenu = $('#dgMenu');
    
    let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    let _btnCopy = $('#btnCopy');
    let _btnRemove = $('#btnRemove');

    let _btnAddDepartement = $('#btnAddDepartement');
    let _btnOkDepartement = $('#btnOkDepartement');
    let _btnEditDepartement = $('#btnEditDepartement');
    let _btnCancelDepartement = $('#btnCancelDepartement');
    let _btnRemoveDepartement = $('#btnRemoveDepartement');

    let _btnLoadMenu = $('#btnLoadMenu');
    let _btnOkMenu = $('#btnOkMenu');
    let _btnEditMenu = $('#btnEditMenu');
    let _btnCancelMenu = $('#btnCancelMenu');
    
    let _id = $('#id');
    let _name = $('#name');
    let _desc = $('#desc');
    let _active = $('#active');

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

    _dgDepartement.datagrid({
        singleSelect:true,
        collapsible:true,
        border:false,
        fitColumns:true,
        pagination:true,
        rownumbers:true,
        remoteSort:true,
        toolbar:'#tbDepartement',
        onDblClickRow: function (index, row) {
            onClickCellDepartement()
        },
        onEndEdit: function (index,row,changes) {
            onEndEditDepartement()
        },
        columns: [[
            {
                field:'departement_id', title: 'Departement', width: 300,
                formatter: function (value, row){
                    return row.name;
                },
                editor: {
                    type: 'combobox',
                    options: {
                        valueField:'id',
                        textField:'name',
                        url: URL_REST + '/departement/lists',
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

    _dgMenu.treegrid({
        singleSelect:true,
        collapsible:true,
        border:false,
        fitColumns:true,
        rownumbers:true,
        idField:'id',
        treeField:'name',
        lines:true,
        toolbar:'#tbMenu',
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

            loadDataDepartement('loadData', [])
            loadDataMenu('loadData', [])
        }
    });

    _btnSave.linkbutton({
        onClick: function() {
            let treeMenus = _dgMenu.treegrid('getData')
            var menus = []

            if (treeMenus.length > 0) {
                $.each(treeMenus, function (key, val) { 
                    menus.push({
                        id: val.id,
                        parent: null,
                        active: val.active == undefined || val.active == 'Yes' ? 1 : 0
                    });

                    if (val.children) {
                        $.each(val.children, function (k, v) { 
                            if (val.active == 'No') {
                                menus.push({
                                    id: v.id,
                                    parent: v.parent,
                                    active: 0
                                });
                            } else {
                                menus.push({
                                    id: v.id,
                                    parent: v.parent,
                                    active: v.active == undefined || v.active == 'Yes' ? 1 : 0
                                });
                            }
                        });
                    } 
                });
            }

            $.messager.progress();
    
            _ff.form('submit', {
                url: _rest,
                onSubmit: function(param) {
                    var isValid = $(this).form('validate');
                    if (!isValid){
                        $.messager.progress('close');
                    }
    
                    param.active = _active.switchbutton('options').checked
                    param.departements = JSON.stringify(_dgDepartement.datagrid('getRows'))
                    param.menus = JSON.stringify(menus)

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
                        loadDataDepartement(parse.id)
                        loadDataMenu(parse.id)
    
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

    _btnAddDepartement.linkbutton({
        onClick: function () {
            if (endEditingDepartement()) {
                _dgDepartement.datagrid('appendRow', {
                    active: 'Yes',
                });

                editIndexDepartement = _dgDepartement.datagrid('getRows').length - 1;

                _dgDepartement.datagrid('selectRow', editIndexDepartement)
                    .datagrid('beginEdit', editIndexDepartement);
            }
        }
    });

    _btnOkDepartement.linkbutton({
        onClick: function () {
            if (endEditingDepartement()) {
                _dgDepartement.datagrid('acceptChanges');
            }
        }
    });

    _btnEditDepartement.linkbutton({
        onClick: function () {
            onClickCellDepartement()
        }
    });

    _btnCancelDepartement.linkbutton({
        onClick: function () {
            _dgDepartement.datagrid('rejectChanges');

            editIndexDepartement = undefined;
        }
    });

    _btnRemoveDepartement.linkbutton({
        onClick: function () {
            let row = _dgDepartement.datagrid('getSelected')
    
            if (row) {
                let index = _dgDepartement.datagrid('getRowIndex', row)

                $.messager.confirm('Confirmation', 'Are you sure delete this data?', function(r){
                    if (r){
                        if (row.id) {
                            $.ajax({
                                type: "delete",
                                url: _rest + '/departement/' + row.id,
                                dataType: "json",
                                success: function (response) {
                                    loadDataDepartement(_id.textbox('getValue'))

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
                            _dgDepartement.datagrid('cancelEdit', index)
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

    var endEditingDepartement = () => {
        if (editIndexDepartement == undefined) {return true}

        if (_dgDepartement.datagrid('validateRow', editIndexDepartement)) {
            let ed = _dgDepartement.datagrid('getEditor', {
                index: editIndexDepartement,
                field: 'departement_id',
            })

            let row = _dgDepartement.datagrid('selectRow', editIndexDepartement)

            row.name = $(ed.target).combobox('getText');

            _dgDepartement.datagrid('endEdit', editIndexDepartement)

            editIndexDepartement = undefined

            return true
        } else {
            return false
        }
    }

    var onClickCellDepartement = () => {
        let row = _dgDepartement.datagrid('getSelected')

        if (row) {
            let index = _dgDepartement.datagrid('getRowIndex', row)

            if (editIndexDepartement !== index) {
                if (endEditingDepartement()) {
                    _dgDepartement.datagrid('selectRow', index)
                        .datagrid('beginEdit', index)

                    editIndexDepartement = index
                } else {
                    setTimeout(function(){
                        _dgDepartement.datagrid('selectRow', editIndexDepartement);
                    },0);
                }
            }
        } else {
            Alert('warning', 'No selected data');
        }
    }

    var onEndEditDepartement = () => {
        let row = _dgDepartement.datagrid('getSelected')
        let index = _dgDepartement.datagrid('getRowIndex', row)

        if (editIndexDepartement == index) {
            let ed = _dgDepartement.datagrid('getEditor', {
                index: editIndexDepartement,
                field: 'departement_id',
            })

            row.name = $(ed.target).combobox('getText');
        } else {
            setTimeout(function(){
                _dgDepartement.datagrid('selectRow', editIndexDepartement);
            },0);
        }
    }

    _btnLoadMenu.linkbutton({
        onClick: function () {
            _dgMenu.treegrid({
                url: URL_REST + '/app-menu/lists',
                loader: function (param, success, error) {
                    let {method, url} = $(this).treegrid('options')
                    
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
                onLoadError: function (xhr) {
                    let {statusText, responseJSON} = xhr
    
                    Alert('error', responseJSON, statusText)
                },
            });
        }
    });

    _btnEditMenu.linkbutton({
        onClick: function () {
            let row = _dgMenu.treegrid('getSelected');

            if (row) {
                if (editIndexMenu !== undefined) {
                    _dgMenu.treegrid('select', editIndexMenu);
                    return
                }

                editIndexMenu = row.id

                _dgMenu.treegrid('beginEdit', editIndexMenu)
            } else {
                Alert('warning', 'No selected data')                
            }
        }
    });

    _btnOkMenu.linkbutton({
        onClick: function () {
            if (editIndexMenu !== undefined) {
                _dgMenu.treegrid('endEdit', editIndexMenu)
                
                editIndexMenu = undefined
            }
        }
    });

    _btnCancelMenu.linkbutton({
        onClick: function () {
            if (editIndexMenu !== undefined) {
                _dgMenu.treegrid('cancelEdit', editIndexMenu);

                editIndexMenu = undefined
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

    var loadDataDepartement = (app_role_id) => {
        _dgDepartement.datagrid({
            method: 'get',
            url: _rest + '/departement/' + app_role_id,
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

        _dgDepartement.datagrid('fixColumnSize');
        _dgDepartement.datagrid('fixRowHeight');
    }

    var loadDataMenu = (app_role_id) => {
        _dgMenu.treegrid({
            url: _rest + '/menu/' + app_role_id,
            loader: function (param, success, error) {
                let {method, url} = $(this).treegrid('options')
                
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
            onLoadError: function (xhr) {
                let {statusText, responseJSON} = xhr

                Alert('error', responseJSON, statusText)
            },
        });
    }

    var formReset = () => {
        _ff.form('clear')

        _dgDepartement.datagrid('loadData', [])
        _dgMenu.treegrid('loadData', [])

        _btnSave.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:false})
        _btnCopy.linkbutton({disabled:false})
        
        _btnAddDepartement.linkbutton({disabled:true})
        _btnOkDepartement.linkbutton({disabled:true})
        _btnEditDepartement.linkbutton({disabled:true})
        _btnCancelDepartement.linkbutton({disabled:true})
        _btnRemoveDepartement.linkbutton({disabled:true})

        _btnLoadMenu.linkbutton({disabled:true})
        _btnOkMenu.linkbutton({disabled:true})
        _btnEditMenu.linkbutton({disabled:true})
        _btnCancelMenu.linkbutton({disabled:true})

        _name.textbox({disabled:true})
        _desc.textbox({disabled:true})
        _active.switchbutton({disabled:true})
    }

    var formEdit = () => {
        _btnSave.linkbutton({disabled:false})
        _btnEdit.linkbutton({disabled:true})
        _btnCopy.linkbutton({disabled:true})
        
        _btnAddDepartement.linkbutton({disabled:false})
        _btnOkDepartement.linkbutton({disabled:false})
        _btnEditDepartement.linkbutton({disabled:false})
        _btnCancelDepartement.linkbutton({disabled:false})
        _btnRemoveDepartement.linkbutton({disabled:false})

        _btnLoadMenu.linkbutton({disabled:false})
        _btnOkMenu.linkbutton({disabled:false})
        _btnEditMenu.linkbutton({disabled:false})
        _btnCancelMenu.linkbutton({disabled:false})
    
        _name.textbox({disabled:false})
        _desc.textbox({disabled:false})
        _active.switchbutton({disabled:false})
    }

    var getData = (row) => {
        if (row) {
            _ff.form('load', _rest + '/' + row.id)
    
            _tbs.tabs({
                selected: 1
            })
    
            formEdit()
            
            loadDataDepartement(row.id)
            loadDataMenu(row.id)
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData()
});