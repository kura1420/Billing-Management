$(function () {
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

    const LoadPage = (page, title) => {
        let URL_PAGE = URL_ROOT + '/' + page;
    
        let iframe = `<iframe src="${URL_PAGE}" frameborder="0" style="width:100%;height:99%;"></iframe>`;

        $('#p').panel({
            title: title,
            content: iframe,
            border: false,
            fit: true,
            onLoadError: function (xhr) {
                let {statusText, responseText} = xhr
            
                Alert('error', responseText, statusText)
            }
        });
    }
});