"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/billing-template'

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');
    let _winPreview = $('#winPreview');
    
    let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    let _btnCopy = $('#btnCopy');
    let _btnRemove = $('#btnRemove');
    let _btnPreview = $('#btnPreview');
    
    let _id = $('#id');
    let _name = $('#name');
    let _sender = $('#sender');
    let _content = $('#content');
    let _type = $('#type');

    _content.texteditor({
        title: 'Content',
        name: 'content',
        width: '100%',
        height: '60%',
        // fit: true,
        border: true,
    })
    _content.texteditor('disable')

    _sender.combobox({
        valueField:'id',
        textField:'text',
        data: [
            {
                id: 'email',
                text: 'Email'
            },
            {
                id: 'sms',
                text: 'SMS',
            },
            {
                id: 'msgr',
                text: 'Messager'
            },
        ],
    })

    _type.combobox({
        valueField:'id',
        textField:'text',
        data: [
            {
                id: 'notif',
                text: 'Notif',
            },
            {
                id: 'suspend',
                text: 'Suspend'
            },
            {
                id: 'terminated',
                text: 'Terminated'
            },
            {
                id: 'paid',
                text: 'Paid',
            },
            {
                id: 'wtverif',
                text: 'Waiting Verif Paid',
            },
        ],
    })

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
    
    _btnCopy.linkbutton({
        onClick: function () {  
            let row = _dg.datagrid('getSelected')
        
            if (row) {
                $.get(_rest + "/" + row.id,
                    function (data, textStatus, jqXHR) {
                        let {
                            id,
                            name,
                            sender,
                            content,
                            type,
                        } = data
    
                        formEdit()
    
                        _id.textbox('clear')
                        _name.textbox('setValue', name)
                        _sender.combobox('setValue', sender)
                        _content.texteditor('setValue', content)
                        _type.combobox('setValue', type)
    
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
            let content_value = _content.texteditor('getValue');
            
            if (content_value) {
                $.ajax({
                    type: "POST",
                    url: _rest + "/preview",
                    data: {content: content_value},
                    dataType: "html",
                    success: function (response) {
                        _winPreview.window({
                            width:600,
                            height:400,
                            modal:true,
                            content: response
                        });
                    }
                });
            } else {
                Alert('warning', 'Content empty');
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

    var formReset = () => {
        _ff.form('clear')

        _content.texteditor('clear')

        _btnSave.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:false})
        _btnCopy.linkbutton({disabled:false})

        _name.textbox({disabled:true})
        _sender.combobox({disabled:true})
        _content.texteditor('disable')
        _type.combobox({disabled:true})
    }

    var formEdit = () => {
        _btnSave.linkbutton({disabled:false})
        _btnEdit.linkbutton({disabled:true})
        _btnCopy.linkbutton({disabled:true})
    
        _name.textbox({disabled:false})
        _sender.combobox({disabled:false})
        _content.texteditor('enable')
        _type.combobox({disabled:false})
    }

    var getData = (row) => {
        if (row) {
            $.ajax({
                type: "GET",
                url: _rest + '/' + row.id,
                dataType: "json",
                success: function (response) {
                    let {
                        id,
                        name,
                        sender,
                        content,
                        type,
                    } = response

                    _tbs.tabs({
                        selected: 1
                    })
            
                    formEdit()

                    _id.textbox('setValue', id)
                    _name.textbox('setValue', name)
                    _sender.combobox('setValue', sender)
                    _content.texteditor('setValue', content)
                    _type.combobox('setValue', type)
                },
                error: function (xhr, status, error) {
                    Alert('warning', 'Internal Server Error')
                }
            });
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData()
});