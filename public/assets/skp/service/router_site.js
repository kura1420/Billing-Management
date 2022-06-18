"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/router-site'

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');
    
    let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    let _btnTestConnection = $('#btnTestConnection');
    let _btnCopy = $('#btnCopy');
    let _btnRemove = $('#btnRemove');
    let _btnTriggerList = $('#btnTriggerList');
    let _btnTriggerComment = $('#btnTriggerComment');
    let _btnTriggerTerminated = $('#btnTriggerTerminated');
    
    let _id = $('#id');
    let _site = $('#site');
    let _host = $('#host');
    let _port = $('#port');
    let _user = $('#user');
    let _password = $('#password');
    let _command_trigger_list = $('#command_trigger_list');
    let _command_trigger_comment = $('#command_trigger_comment');
    let _command_trigger_terminated = $('#command_trigger_terminated');
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

    _btnTestConnection.linkbutton({
        onClick: function() {
            $.messager.progress();

            $.ajax({
                type: "POST",
                url: _rest + "/test-connection",
                data: {
                    host: _host.textbox('getValue'),
                    port: _port.numberbox('getValue'),
                    user: _user.textbox('getValue'),
                    password: _password.passwordbox('getValue'),
                },
                dataType: "json",
                success: function (response) {
                    $.messager.progress('close');

                    $.messager.show({
                        title:'Info',
                        msg:'Router Connected.',
                        timeout:5000,
                        showType:'slide'
                    });
                },
                error: function (xhr, error) {
                    $.messager.progress('close'); 

                    let {status, responseJSON} = xhr

                    if (status == 422) {
                        let msg = []
                        for (var d in responseJSON.data) {
                            msg.push(responseJSON.data[d].toString())
                        }

                        Alert('warning', msg.join('<br />'));
                    } else {
                        Alert('warning', 'Internal Server Error')
                    }
                }
            });
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

    _btnTriggerList.linkbutton({
        onClick: function () {  
            $.messager.progress();

            $.ajax({
                type: "POST",
                url: _rest + "/test-command-list",
                data: {
                    host: _host.textbox('getValue'),
                    port: _port.numberbox('getValue'),
                    user: _user.textbox('getValue'),
                    password: _password.passwordbox('getValue'),
                    command: _command_trigger_list.textbox('getValue'),
                },
                dataType: "json",
                success: function (response) {
                    $.messager.progress('close');

                    $.messager.show({
                        title:'Info',
                        msg:'Command Ok.',
                        timeout:5000,
                        showType:'slide'
                    });
                },
                error: function (xhr, error) {
                    $.messager.progress('close'); 

                    let {status, responseJSON} = xhr

                    if (status == 422) {
                        let msg = []
                        for (var d in responseJSON.data) {
                            msg.push(responseJSON.data[d].toString())
                        }

                        Alert('warning', msg.join('<br />'));
                    } else {
                        Alert('warning', 'Internal Server Error')
                    }
                }
            });          
        }
    });

    _btnTriggerComment.linkbutton({
        onClick: function () {  
            $.messager.prompt('Mikrotik', 'Target for comment', function(r){
                if (r){
                    $.messager.progress();

                    $.ajax({
                        type: "POST",
                        url: _rest + "/test-command-comment",
                        data: {
                            host: _host.textbox('getValue'),
                            port: _port.numberbox('getValue'),
                            user: _user.textbox('getValue'),
                            password: _password.passwordbox('getValue'),
                            command_list: _command_trigger_list.textbox('getValue'),
                            command: _command_trigger_comment.textbox('getValue'),
                            target: r,
                        },
                        dataType: "json",
                        success: function (response) {
                            $.messager.progress('close');

                            $.messager.show({
                                title:'Info',
                                msg:'Command Ok.',
                                timeout:5000,
                                showType:'slide'
                            });
                        },
                        error: function (xhr, error) {
                            $.messager.progress('close'); 

                            let {status, responseJSON} = xhr

                            if (status == 422) {
                                let msg = []
                                for (var d in responseJSON.data) {
                                    msg.push(responseJSON.data[d].toString())
                                }

                                Alert('warning', msg.join('<br />'));
                            } else {
                                Alert('warning', 'Internal Server Error')
                            }
                        }
                    });
                }
            });     
        }
    });

    _btnTriggerTerminated.linkbutton({
        onClick: function () {  
            $.messager.prompt('Mikrotik', 'Target for disable', function(r){
                if (r){
                    $.messager.progress();

                    $.ajax({
                        type: "POST",
                        url: _rest + "/test-command-disable",
                        data: {
                            host: _host.textbox('getValue'),
                            port: _port.numberbox('getValue'),
                            user: _user.textbox('getValue'),
                            password: _password.passwordbox('getValue'),
                            command_list: _command_trigger_list.textbox('getValue'),
                            command_set: _command_trigger_comment.textbox('getValue'),
                            command: _command_trigger_terminated.textbox('getValue'),
                            target: r,
                        },
                        dataType: "json",
                        success: function (response) {
                            $.messager.progress('close');

                            $.messager.show({
                                title:'Info',
                                msg:'Command Ok.',
                                timeout:5000,
                                showType:'slide'
                            });
                        },
                        error: function (xhr, error) {
                            $.messager.progress('close'); 

                            let {status, responseJSON} = xhr

                            if (status == 422) {
                                let msg = []
                                for (var d in responseJSON.data) {
                                    msg.push(responseJSON.data[d].toString())
                                }

                                Alert('warning', msg.join('<br />'));
                            } else {
                                Alert('warning', 'Internal Server Error')
                            }
                        }
                    });
                }
            });     
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

    var formReset = () => {
        _ff.form('clear')

        _btnSave.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:false})
        _btnTestConnection.linkbutton({disabled:true})
        _btnCopy.linkbutton({disabled:false})

        _site.textbox({disabled:true})
        _host.textbox({disabled:true})
        _port.numberbox({disabled:true})
        _user.textbox({disabled:true})
        _password.passwordbox({disabled:true})
        _desc.textbox({disabled:true})
        _command_trigger_list.textbox({disabled:true})
        _command_trigger_comment.textbox({disabled:true})
        _command_trigger_terminated.textbox({disabled:true})
        _active.switchbutton({disabled:true})
    }

    var formEdit = () => {
        _btnSave.linkbutton({disabled:false})
        _btnEdit.linkbutton({disabled:true})
        _btnTestConnection.linkbutton({disabled:false})
        _btnCopy.linkbutton({disabled:true})
    
        _site.textbox({disabled:false})
        _host.textbox({disabled:false})
        _port.numberbox({disabled:false})
        _user.textbox({disabled:false})
        _password.passwordbox({disabled:false})
        _desc.textbox({disabled:false})
        _command_trigger_list.textbox({disabled:false})
        _command_trigger_comment.textbox({disabled:false})
        _command_trigger_terminated.textbox({disabled:false})
        _active.switchbutton({disabled:false})
    }

    var getData = (row) => {
        if (row) {
            _ff.form('load', _rest + '/' + row.id)
    
            _tbs.tabs({
                selected: 1
            })
    
            formEdit()
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData()
});