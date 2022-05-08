$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });
    
    const _rest = URL_REST + '/auth'

    let _layoutLogin = $('#layoutLogin')
    let _layoutForgot = $('#layoutForgot')

    let _ffLogin = $('#ffLogin')
    let _ffForgot = $('#ffForgot')
    
    let _btnLogin = $('#btnLogin')
    let _btnForgot = $('#btnForgot')

    let _btnRequest = $('#btnRequest')
    let _btnBackLogin = $('#btnBackLogin')

    let _email = $('#email')
    let _password = $('#password')

    let _email_forgot = $('#email_forgot')

    _layoutLogin.show();
    _layoutForgot.hide();

    _ffLogin.form('clear');
    _ffForgot.form('clear');

    _btnLogin.linkbutton({
        onClick: function () {
            $.messager.progress();

            _ffLogin.form('submit', {
                url: _rest + '/login',
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
                        window.location.reload()
                    }
                },
            })
        }
    });

    _btnForgot.linkbutton({
        onClick: function () {
            _layoutLogin.hide();
            _ffLogin.form('clear');

            _layoutForgot.show();
            _ffForgot.form('clear');
        }
    });

    _btnRequest.linkbutton({
        onClick: function () {  
            $.messager.progress();

            _ffForgot.form('submit', {
                url: _rest + '/forgot',
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
                        $.messager.alert('Information','Request forgot password has been send for your email.','info');
                    }
                },
            })
        }
    });

    _btnBackLogin.linkbutton({
        onClick: function () {
            _layoutLogin.show();
            _ffLogin.form('clear');

            _layoutForgot.hide();
            _ffForgot.form('clear');
        }
    });
});