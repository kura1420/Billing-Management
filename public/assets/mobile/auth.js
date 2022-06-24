$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });
    
    const _rest = URL_MOBILE + '/auth'

    let _layoutLogin = $('#layoutLogin')

    let _ffLogin = $('#ffLogin')
    
    let _btnLogin = $('#btnLogin')
    
    let _email = $('#email')
    let _password = $('#password')

    _ffLogin.form('clear');

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
});