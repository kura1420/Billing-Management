$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest = URL_REST + '/app'

    $('#tt').tree({
        url: _rest + '/menu',
        animate: true,
        lines: true,
        onClick: function (node) {
            let {url, text} = node

            if (url) {
                LoadPage(url, text)
            }
        }
    });

    $('#btnLogout').linkbutton({
        onClick: function () {
            $.messager.confirm('Informasi', 'Apakah anda yakin ini keluar?', function(r){
                if (r){
                    $.ajax({
                        type: "post",
                        url: URL_REST + "/user/logout",
                        dataType: "json",
                        success: function (response) {
                            window.location.reload()
                        },
                        error: function (xhr, status, error) {
                            console.log(xhr);
                        }
                    });
                }
            });
        }
    });

    var LoadPage = (url, text) => {
        $('#p').panel({
            href: URL_ROOT + '/' + url,
            title: text,
            loader: function (param, success, error) {
                let {method, href} = $(this).panel('options');

                if (method==null || href==null) return false

                $.ajax({
                    type: method,
                    url: href,
                    dataType: "html",
                    success: function (response) {
                        success(response);
                    },
                    error: function (xhr, status) {  
                        error(xhr);
                    }
                });
            },
            onLoadError: function (xhr) {
                let {statusText, responseText} = xhr

                Alert('error', responseText, statusText)
            }
        });
    }

    LoadPage('area', 'Area');
});

function Alert (type, objs, title=null) {
    switch (type) {
        case 'info':
            $.messager.alert(title, objs, type)
            break;

        case 'warning':
            $.messager.alert('Warning', objs, type)
            break;

        case 'error':
            let text = null

            if (typeof objs == 'string') {
                text = objs
            } else {
                let {file, message, line} = objs

                text = `<b>File:</b> ${file} <br />
                    <b>Message:</b> ${message} <br />
                    <b>Line:</b> ${line}`
            }

            $.messager.alert(title, text, type)
            break;
    
        default:
            break;
    }
}