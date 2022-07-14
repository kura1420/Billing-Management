"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/item'
    const _restData = URL_REST + '/item-data'

    var _qrcode_url = undefined
    var _picture_url = undefined

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _ff = $('#ff');
    let _ss = $('#ss');
    
    let _btnAdd = $('#btnAdd');
    let _btnSave = $('#btnSave');
    let _btnEdit = $('#btnEdit');
    let _btnCopy = $('#btnCopy');
    let _btnRemove = $('#btnRemove');
    let _btnQrcode = $('#btnQrcode');
    let _btnPicture = $('#btnPicture');
    
    let _id = $('#id');
    let _code = $('#code');
    let _name = $('#name');
    let _serial_numbers = $('#serial_numbers');
    let _spec = $('#spec');
    let _desc = $('#desc');
    let _year = $('#year');
    let _picture = $('#picture');
    let _price = $('#price');
    let _brand = $('#brand');
    let _partner_id = $('#partner_id');
    let _unit_id = $('#unit_id');

    _partner_id.combobox({
        valueField:'id',
        textField:'name',
        groupField:'type',
        url: URL_REST + '/partner-data/lists'
    });

    _unit_id.combobox({
        valueField:'id',
        textField:'name',
        url: URL_REST + '/unit/lists'
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
            _code.textbox('readonly', false)

            _brand.combobox({
                valueField:'id',
                textField:'text',
                url: _restData + '/brands',
                onLoadSuccess: function () {
                    _brand.combo('clear')
                }
            });
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
                    param.brand = _brand.combo('getText')
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

                        _id.textbox('readonly', false)
    
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

    _btnQrcode.linkbutton({
        onClick: function () {
            if (_qrcode_url) {
                window.open(_qrcode_url);
            }
        }
    });

    _btnPicture.linkbutton({
        onClick: function () {
            if (_picture_url) {
                window.open(_picture_url);
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

    var formReset = () => {
        _qrcode_url = undefined
        _picture_url = undefined

        _ff.form('clear')
        _brand.combo('clear')

        _btnSave.linkbutton({disabled:true})
        _btnEdit.linkbutton({disabled:false})
        _btnCopy.linkbutton({disabled:false})

        _code.textbox({disabled:true})
        _name.textbox({disabled:true})
        _serial_numbers.textbox({disabled:true})
        _spec.textbox({disabled:true})
        _desc.textbox({disabled:true})
        _year.numberbox({disabled:true})
        _picture.filebox({disabled:true})
        _price.numberbox({disabled:true})
        _brand.combobox({disabled:true})
        _partner_id.combobox({disabled:true})
        _unit_id.combobox({disabled:true})
    }

    var formEdit = () => {
        _btnSave.linkbutton({disabled:false})
        _btnEdit.linkbutton({disabled:true})
        _btnCopy.linkbutton({disabled:true})
    
        _code.textbox({disabled:false})
        _name.textbox({disabled:false})
        _serial_numbers.textbox({disabled:false})
        _spec.textbox({disabled:false})
        _desc.textbox({disabled:false})
        _year.numberbox({disabled:false})
        _picture.filebox({disabled:false})
        _price.numberbox({disabled:false})
        _brand.combobox({disabled:false})
        _partner_id.combobox({disabled:false})
        _unit_id.combobox({disabled:false})
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
                        code,
                        name,
                        serial_numbers,
                        spec,
                        desc,
                        year,
                        picture,
                        picture_url,
                        qrcode,
                        qrcode_url,
                        price,
                        brand,
                        partner_id,
                        unit_id,
                    } = response

                    _tbs.tabs({
                        selected: 1
                    })
            
                    formEdit()

                    _brand.combobox({
                        valueField:'id',
                        textField:'text',
                        url: _restData + '/brands',
                        onLoadSuccess: function () {
                            _brand.combo('setText', brand)
                        }
                    });

                    _code.textbox('readonly')

                    if (qrcode_url) {
                        _qrcode_url = qrcode_url
                    } else {
                        _qrcode_url = undefined
                    }

                    if (picture_url) {
                        _picture_url = picture_url
                    } else {
                        _picture_url = undefined
                    }

                    _ff.form('load', {
                        id: id,
                        code: code,
                        name: name,
                        serial_numbers: serial_numbers,
                        spec: spec,
                        desc: desc,
                        year: year,
                        price: price,
                        brand: brand,
                        partner_id: partner_id,
                        unit_id: unit_id,
                    });
                }
            });    
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData()
});