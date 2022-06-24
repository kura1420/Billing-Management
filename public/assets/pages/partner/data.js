"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/partner'
    const _restData = URL_REST + '/partner-data'

    var rowIndexContact = undefined;
    var _logo_url = undefined;
    var _file_url = undefined;

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');

    let _dgContact = $('#dgContact')

    let _dgDocument = $('#dgDocument')
    let _ffDocument = $('#ffDocument')
    let _wDocument = $('#wDocument')
    
    let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    let _btnCopy = $('#btnCopy');
    let _btnRemove = $('#btnRemove');
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
    
    let _id = $('#id');
    let _code = $('#code');
    let _name = $('#name');
    let _active = $('#active');
    let _alias = $('#alias');
    let _type = $('#type');
    let _telp = $('#telp');
    let _email = $('#email');
    let _fax = $('#fax');
    let _handphone = $('#handphone');
    let _address = $('#address');
    let _logo = $('#logo');
    let _join = $('#join');
    let _leave = $('#leave');
    let _brand = $('#brand');
    let _user_id_reff = $('#user_id_reff');

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
                field:'fullname', title:'Fullname', width: 300,
                editor: {
                    type: 'textbox',
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
                field:'handphone', title:'Handphone', width: 300,
                editor: {
                    type: 'numberbox',
                    options: {
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
                field:'position', title:'Position', width: 300,
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

            _logo_url = undefined

            _type.combobox({
                valueField:'id',
                textField:'text',
                url: _restData + '/types',
                onLoadSuccess: function () {
                    _type.combo('clear')
                }
            });
        
            _brand.combobox({
                valueField:'id',
                textField:'text',
                url: _restData + '/brands',
                onLoadSuccess: function () {
                    _brand.combo('clear')
                }
            });
        
            _user_id_reff.combobox({
                valueField:'id',
                textField:'text',
                url: URL_REST + '/app-user/lists',
                onLoadSuccess: function () {
                    _user_id_reff.combo('clear')
                }
            });
    
            _ff.form('clear')

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
    
                    param.id = _id.textbox('getValue')
                    param.type = _type.combo('getText')
                    param.brand = _brand.combo('getText')
                    param.active = _active.switchbutton('options').checked

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
                        loadDataContact(_id.textbox('getValue'))
                        loadDataDocument(_id.textbox('getValue'))
    
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
            window.open(_logo_url)
        }
    });

    _btnAddContact.linkbutton({
        onClick: function () {
            if (rowIndexContact == undefined) {
                _dgContact.datagrid('appendRow', {
                    fullname: '',
                    email: '',
                    handphone: '',
                    telp: '',
                    position: '',
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
    
                    param.partner_id = _id.textbox('getValue')
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
            
            $.ajax({
                type: "get",
                url: _rest + "/document-show/" + row.id,
                dataType: "json",
                success: function (response) {
                    let {
                        id,
                        name,
                        file,
                        desc,
                        start,
                        end,
                        file_url,
                    } = response

                    _ffDocument.form('load', {
                        d_id: id,
                        d_name: name,
                        d_file: file,
                        d_desc: desc,
                        d_start: start,
                        d_end: end,
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

    var formReset = () => {
        _logo_url = undefined
        _file_url = undefined

        _ff.form('clear')
        _type.combo('clear')
        
        _dgContact.datagrid('loadData', [])
        _dgDocument.datagrid('loadData', [])

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

        _code.textbox({disabled:true})
        _name.textbox({disabled:true})        
        _active.switchbutton({disabled:true})
        _alias.textbox({disabled:true})
        _type.combobox({disabled:true})
        _telp.numberbox({disabled:true})
        _email.textbox({disabled:true})
        _fax.textbox({disabled:true})
        _handphone.numberbox({disabled:true})
        _address.textbox({disabled:true})
        _logo.filebox({disabled:true})
        _join.datebox({disabled:true})
        _leave.datebox({disabled:true})
        _brand.combobox({disabled:true})
        _user_id_reff.combobox({disabled:true})
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
    
        _code.textbox({disabled:false})
        _name.textbox({disabled:false})
        _active.switchbutton({disabled:false})
        _alias.textbox({disabled:false})
        _type.combobox({disabled:false})
        _telp.numberbox({disabled:false})
        _email.textbox({disabled:false})
        _fax.textbox({disabled:false})
        _handphone.numberbox({disabled:false})
        _address.textbox({disabled:false})
        _logo.filebox({disabled:false})
        _join.datebox({disabled:false})
        _leave.datebox({disabled:false})
        _brand.combobox({disabled:false})
        _user_id_reff.combobox({disabled:false})
    }

    var getData = (row) => {
        if (row) {
            $.ajax({
                type: "get",
                url: _rest + '/' + row.id,
                dataType: "json",
                success: function (response) {
                    let {
                        id,
                        code,
                        name,
                        alias,
                        type,
                        telp,
                        email,
                        fax,
                        handphone,
                        address,
                        logo,
                        logo_url,
                        active,
                        join,
                        leave,
                        brand,
                        user_id_reff,
                    } = response
                    
                    _tbs.tabs({
                        selected: 1
                    })

                    formEdit()

                    _type.combobox({
                        valueField:'id',
                        textField:'text',
                        url: _restData + '/types',
                        onLoadSuccess: function () {
                            _type.combo('setText', type)
                        }
                    });
                
                    _brand.combobox({
                        valueField:'id',
                        textField:'text',
                        url: _restData + '/brands',
                        onLoadSuccess: function () {
                            _brand.combo('setText', brand)
                        }
                    });
                
                    _user_id_reff.combobox({
                        valueField:'id',
                        textField:'text',
                        url: URL_REST + '/app-user/lists',
                        onLoadSuccess: function () {  
                            _user_id_reff.combobox('setValue', user_id_reff)
                        }
                    });

                    _code.textbox({readonly:true})

                    if (logo_url) {
                        _logo_url = logo_url
                        
                        _btnPreview.linkbutton({disabled:false})
                    } else {
                        _logo_url = undefined

                        _btnPreview.linkbutton({disabled:true})
                    }

                    _ff.form('load', {
                        id: id,
                        code: code,
                        name: name,
                        alias: alias,
                        // type: type,
                        telp: telp,
                        email: email,
                        fax: fax,
                        handphone: handphone,
                        address: address,
                        // logo: logo,
                        active: active,
                        join: join,
                        leave: leave,
                        // brand: brand,
                        // user_id_reff: user_id_reff,
                    });                    
                    
                    loadDataContact(row.id);
                    loadDataDocument(row.id);
                }
            });
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData()
});