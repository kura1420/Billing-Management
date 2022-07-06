$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    const _rest_app = URL_REST + '/app'
    const _rest_profile = URL_REST + '/profile'

    $('#up').propertygrid({
        method:'get',
        url:_rest_profile,
        showGroup:false,
        scrollbarSize:0,
        showHeader:false,
        border:false,
        fit:true,
        columns:[[
            {
                field:'name',title:'Label',width:60,
                styler: function(value,row,index) {
                    return 'font-weight: bold';
                },
            },
            {
                field:'value',title:'Value',width:100,
                formatter: function (value, row, index) {
                    if (index !== 5) {
                        return value
                    } 
                }
            }
        ]],
        toolbar:[
            {
                text:'Save',
                iconCls:'icon-save',
                handler: function () {
                    $.messager.progress();

                    let profiles = $('#up').propertygrid('getRows')
                    
                    var updateUserProfiles = {}
                    $.each(profiles, function (key, val) { 
                        let updateProfilesKey = val.name.toLowerCase()

                        Object.assign(updateUserProfiles, {[updateProfilesKey]: val.value})
                    });

                    $.ajax({
                        type: "post",
                        url: _rest_profile,
                        data: updateUserProfiles,
                        dataType: "json",
                        success: function (response) {
                            $.messager.progress('close');

                            $('#up').propertygrid('reload')
                            
                            $.messager.show({
                                title:'Info',
                                msg:'Update profile success.',
                                timeout:5000,
                                showType:'slide'
                            });
                        },
                        error: function (xhr, error) {
                            $.messager.progress('close'); 

                            let {status, responseJSON} = xhr

                            if (status == 422) {
                                let msg = []
                                for (var d in responseJSON.data) {
                                    msg.push(responseJSON.data[d].toString())
                                }

                                Alert('warning', msg.join('<br />'));
                            } else {
                                Alert('warning', 'Internal Server Error')
                            }
                        }
                    });
                }
            }, '-',
            {
                text:'Logout',
                iconCls:'icon-cancel',
                handler: function () {
                    $.messager.confirm('Confirmation', 'Do you want to logout?', function(r){
                        if (r){
                            $.ajax({
                                type: "post",
                                url: _rest_profile + "/logout",
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
            }
        ]
    });

    $('#tt').tree({
        url: _rest_app + '/menu',
        animate: true,
        lines: true,
        onClick: function (node) {
            let {url, title} = node

            if (url) {
                LoadPage(url, title)
            }
        }
    });

    const LoadPage = (page, title) => {
        let URL_PAGE = URL_ROOT + '/' + page;
    
        let iframe = `<iframe src="${URL_PAGE}" frameborder="0" style="width:100%;height:99%;"></iframe>`;
        
        let checkIfExistsTabs = $('#p').tabs('exists', title)
        let tabsAll = $('#p').tabs('tabs')

        if (!checkIfExistsTabs) {
            if (tabsAll.length < 10) {
                $('#p').tabs('add', {
                    title: title,
                    content: iframe,
                    border: false,
                    fit: true,
                    closable:true,
                    onLoadError: function (xhr) {
                        let {statusText, responseText} = xhr
                    
                        Alert('error', responseText, statusText)
                    }
                });
            } else {
                Alert('info', 'Maksimal tab hanya 10, silahkan close tab yang anda ingin ganti.', 'Informasi')
            }
        } else {
            $('#p').tabs('select', title)
        }
    }
});