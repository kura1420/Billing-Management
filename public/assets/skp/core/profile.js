"use strict"
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/app-profile'

    let _ff = $('#ff');
    
    let _btnSave = $('#btnSave');
    let _btnPreview = $('#btnPreview');
    var _logo_url = null

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
    

    _btnPreview.linkbutton({
        onClick: function () {
            window.open(_logo_url)
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
});