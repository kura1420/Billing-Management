"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/product-promo'

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');
    
    let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    let _btnCopy = $('#btnCopy');
    let _btnRemove = $('#btnRemove');
    let _btnPreview = $('#btnPreview');
    var _image_url = null;
    
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
                _product_service_id.combobox({disabled:false});
            } else {
                _product_service_id.combobox({disabled:true});                
            }
        }
    });

    _product_service_id.combobox({
        valueField:'id',
        textField:'name',
        url: URL_REST + '/product-service/lists'
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
        _btnCopy.linkbutton({disabled:false})

        _name.textbox({disabled:true})
        _desc.textbox({disabled:true})
        _active.switchbutton({disabled:true})
        _start.datebox({disabled:true})
        _end.datebox({disabled:true})
        _image.filebox({disabled:true})

        _type.combobox({disabled:true})
        _discount.numberbox({disabled:true})
        _until_payment.numberbox({disabled:true})
        _product_service_id.combobox({disabled:true})
    }

    var formEdit = () => {
        _btnSave.linkbutton({disabled:false})
        _btnEdit.linkbutton({disabled:true})
        _btnCopy.linkbutton({disabled:true})
    
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