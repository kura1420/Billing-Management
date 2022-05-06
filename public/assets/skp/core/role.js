"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/app-role'

    var editIndex = undefined;

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');
    
    let _dgDepartement = $('#dgDepartement')
    
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
            onClickCell()
        },
        onEndEdit: function (index,row,changes) {
            onEndEdit()
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
                    param.departements = JSON.stringify(_dgDepartement.datagrid('getRows'))

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
            if (endEditing()) {
                _dgDepartement.datagrid('appendRow', {
                    active: 'Yes',
                });

                editIndex = _dgDepartement.datagrid('getRows').length - 1;

                _dgDepartement.datagrid('selectRow', editIndex)
                    .datagrid('beginEdit', editIndex);
            }
        }
    });

    _btnOkDepartement.linkbutton({
        onClick: function () {
            if (endEditing()) {
                _dgDepartement.datagrid('acceptChanges');
            }
        }
    });

    _btnEditDepartement.linkbutton({
        onClick: function () {
            onClickCell()
        }
    });

    _btnCancelDepartement.linkbutton({
        onClick: function () {
            _dgDepartement.datagrid('rejectChanges');

            editIndex = undefined;
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

    var endEditing = () => {
        if (editIndex == undefined) {return true}

        if (_dgDepartement.datagrid('validateRow', editIndex)) {
            let ed = _dgDepartement.datagrid('getEditor', {
                index: editIndex,
                field: 'departement_id',
            })

            let row = _dgDepartement.datagrid('selectRow', editIndex)

            row.name = $(ed.target).combobox('getText');

            _dgDepartement.datagrid('endEdit', editIndex)

            editIndex = undefined

            return true
        } else {
            return false
        }
    }

    var onClickCell = () => {
        let row = _dgDepartement.datagrid('getSelected')

        if (row) {
            let index = _dgDepartement.datagrid('getRowIndex', row)

            if (editIndex !== index) {
                if (endEditing()) {
                    _dgDepartement.datagrid('selectRow', index)
                        .datagrid('beginEdit', index)

                    editIndex = index
                } else {
                    setTimeout(function(){
                        _dgDepartement.datagrid('selectRow', editIndex);
                    },0);
                }
            }
        } else {
            Alert('warning', 'No selected data');
        }
    }

    var onEndEdit = () => {
        let row = _dgDepartement.datagrid('getSelected')
        let index = _dgDepartement.datagrid('getRowIndex', row)

        if (editIndex == index) {
            let ed = _dgDepartement.datagrid('getEditor', {
                index: editIndex,
                field: 'departement_id',
            })

            row.name = $(ed.target).combobox('getText');
        } else {
            setTimeout(function(){
                _dgDepartement.datagrid('selectRow', editIndex);
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

    var formReset = () => {
        _ff.form('clear')

        _dgDepartement.datagrid('loadData', [])

        _btnSave.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:false})
        _btnCopy.linkbutton({disabled:false})
        
        _btnAddDepartement.linkbutton({disabled:true})
        _btnOkDepartement.linkbutton({disabled:true})
        _btnEditDepartement.linkbutton({disabled:true})
        _btnCancelDepartement.linkbutton({disabled:true})
        _btnRemoveDepartement.linkbutton({disabled:true})

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
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData()
});