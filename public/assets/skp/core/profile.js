"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/app-profile'

    let _ff = $('#ff');

    let _id = $('#id')
    let _secret = $("#secret")
    
    let _btnSave = $('#btnSave');
    let _btnPreview = $('#btnPreview');
    let _btnResetSecret = $('#btnResetSecret');
    var _logo_url = null    

    _btnPreview.linkbutton({
        onClick: function () {
            window.open(_logo_url)
        }
    });

    _btnResetSecret.linkbutton({
        onClick: function () {  
            $.messager.progress();

            $.ajax({
                type: "POST",
                url: _rest + "/reset-secret",
                data: {
                    id: _id.textbox('getValue'),
                },
                dataType: "json",
                success: function (response) {
                    $.messager.progress('close');

                    $.messager.show({
                        title:'Info',
                        msg:'Reset secret success.',
                        timeout:5000,
                        showType:'slide'
                    })
                
                    _secret.textbox('setValue', response)
                },
                error: function (xhr, error) {  
                    $.messager.progress('close');
                    
                    let {status, responseJSON} = xhr

                    if (status == 422) {
                        let msg = []
                        for (var d in responseJSON.data) {
                            msg.push(responseJSON.data[d].toString())
                        }

                        Alert('warning', msg.join('<br />'))
                    } else {
                        Alert('warning', 'Internal Server Error')
                    }
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

                        $('#logo').filebox('clear')

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

    var loadData = () => {
        $.ajax({
            type: "get",
            url: _rest,
            dataType: "json",
            success: function (response) {
                let { logo_url } = response
    
                _ff.form('load', response)
    
                if (logo_url) {
                    _btnPreview.linkbutton({disabled:false})
    
                    _logo_url = logo_url
                } 
            },
            error: function (xhr, status, error) {
                let {statusText, responseJSON} = xhr
    
                Alert('error', responseJSON, statusText)
            }
        });
    }

    loadData()
});