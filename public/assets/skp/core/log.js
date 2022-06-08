"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/app-log'

    let _tbs = $('#tbs');
    let _dg = $('#dg');
    let _log_content = $('#log_content');
    let _p_content = $('#p_content');
    
    let _btnDownload = $('#btnDownload');

    _dg.datagrid({
        singleSelect:true,
        collapsible:true,
        border:false,
        fitColumns:true,
        pagination:true,
        rownumbers:true,
        remoteSort:false,
    });

    _tbs.tabs({
        onSelect: function (title, index) {
            if (index == 0) {
                formReset()
            }
        }
    });
    
    _btnDownload.linkbutton({
        onClick: function() {
            let row = _dg.datagrid('getSelected')
            
            if (row) {
                window.open(_rest + '/download/' + row.filename)
            } else {
                Alert('warning', 'ID not found')                
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
        })

        _dg.datagrid('fixColumnSize');
        _dg.datagrid('fixRowHeight');
    }

    var formReset = () => {
        _p_content.panel({
            title: '',
        })

        _log_content.val('');

        _btnDownload.linkbutton({disabled:false})
    }

    var formEdit = () => {
        _btnDownload.linkbutton({disabled:true})
    }

    var getData = (row) => {
        if (row) {
            $.ajax({
                type: "get",
                url: _rest + '/' + row.filename,
                dataType: "html",
                success: function (response) {
                    _p_content.panel({
                        title: row.filename,
                        fit: true,
                        border: false,
                    });  
                    
                    _log_content.val(response)


                    _tbs.tabs({
                        selected: 1
                    })
    
                    formEdit()
                }
            });   
        } else {
            Alert('warning', 'ID not found')
        }
    }

    loadData()
});