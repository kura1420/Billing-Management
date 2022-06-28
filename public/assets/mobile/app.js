$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });
    
    const _rest = URL_MOBILE + '/rest'
    const _rest_profile = URL_REST + '/profile'

    let _ffCustomer = $('#ffCustomer');

    let _provinsi_id = $('#provinsi_id');
    let _city_id = $('#city_id');
    let _customer_segment_id = $('#customer_segment_id');
    let _product_service_id = $('#product_service_id');
    let _latitude = $('#latitude');
    let _longitude = $('#longitude');

    let _btnSubmitCustomer = $('#btnSubmitCustomer');
    let _btnResetCustomer = $('#btnResetCustomer');
    let _btnLogout = $('#btnLogout');

    var getLocation = () => {
        if (navigator.geolocation) {
            window.navigator.geolocation.getCurrentPosition(
                function (position) {
                    _latitude.textbox('setValue', position.coords.latitude);
                    _longitude.textbox('setValue', position.coords.longitude);
                },
                
                function onError(error) {
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            Alert('warning', "Anda tidak memberikan akses GPS.");                            
                            break;

                        case error.POSITION_UNAVAILABLE:
                            Alert('warning', "Lokasi anda tidak ditemukan."); 
                            break;

                        case error.TIMEOUT:
                            Alert('warning', "Waktu membaca posisi GPS habis."); 
                            break;

                        case error.UNKNOWN_ERROR:
                            Alert('warning', "Fungsi membaca GPS tidak bekerja."); 
                            break;
                    
                        default:
                            break;
                    }
                }
            );
        } else {
            Alert('warning', "Geolocation is not supported by this browser.");
        }
    }

    getLocation();

    _provinsi_id.combobox({
        valueField:'id',
        textField:'name',
        url: _rest + '/provinsi',
        onSelect: function (record) {
            _city_id.combobox({
                onBeforeLoad: function (param) {
                    param.provinsi_id = record.id
                }
            });

            _city_id.combobox('reload', _rest + '/city');
        }
    });

    _city_id.combobox({
        onSelect: function (record) {
            _product_service_id.combogrid('clear')

            if (record) {
                _customer_segment_id.combogrid({
                    fitColumns:true,
                    idField:'id',
                    textField:'customer_segment_name',
                    method:'post',
                    url: _rest + '/segments',
                    queryParams: {
                        provinsi: _provinsi_id.combobox('getValue'),
                        city: record.id
                    },
                    columns:[[
                        {field:'customer_type_name',title:'Type'},
                        {field:'customer_segment_name',title:'Segment'},
                    ]],
                    onChange: function (newValue, oldValue) {
                        if (newValue) {
                            _product_service_id.combogrid({
                                fitColumns:true,
                                idField:'id',
                                textField:'product_service_name',
                                method:'post',
                                url: _rest + '/products',
                                queryParams: {
                                    provinsi: _provinsi_id.combobox('getValue'),
                                    city: _city_id.combobox('getValue'),
                                    segment: newValue,
                                },
                                columns:[[
                                    {field:'product_type_name',title:'Type'},
                                    {field:'product_service_name',title:'Layanan'},
                                ]],
                            });
                        }
                    }
                });
            }
        }
    });

    _btnSubmitCustomer.linkbutton({
        onClick: function () {
            let lat = _latitude.textbox('getValue');
            let lon = _longitude.textbox('getValue');

            alert(lat, lon);

            // $.messager.progress();

            // _ffCustomer.form('submit', {
            //     url: _rest + '/customer-candidate',
            //     onSubmit: function(param) {
            //         var isValid = $(this).form('validate');
            //         if (!isValid){
            //             $.messager.progress('close');
            //         }
    
            //         param._token = $('meta[name="csrf-token"]').attr('content')
    
            //         return isValid;
            //     },
            //     success: function(res) {
            //         $.messager.progress('close');
    
            //         let {status, data} = JSON.parse(res)
    
            //         if (status == 'NOT') {
            //             let msg = []
            //             for (var d in data) {
            //                 msg.push(data[d].toString())
            //             }
    
            //             Alert('warning', msg.join('<br />'))
            //         } else {
            //             Alert('info', 'Data berhasil di input', 'Informasi');

            //             _ffCustomer.form('clear');
            //         }
            //     },
            // });
        }
    });

    _btnResetCustomer.linkbutton({
        onClick: function () {
            $.messager.confirm('Konfirmasi', 'Apakah anda yakin menghapus data di form?', function(r){
                if (r){
                    _ffCustomer.form('clear');
                }
            });
        }
    });

    $('#up').propertygrid({
        method:'get',
        url: _rest + '/profile',
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
                        url: _rest + '/profile',
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
                                url: _rest + "/logout",
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

    _btnLogout.linkbutton({
        onClick: function () {
            $.messager.confirm('Konfirmasi', 'Apakah anda ingin keluar?', function(r){
                if (r){
                    $.ajax({
                        type: "post",
                        url: _rest + "/logout",
                        dataType: "json",
                        success: function (response) {
                            window.location.reload()
                        },
                        error: function (xhr, status, error) {
                            Alert('warning', 'Internal Server Error');
                        }
                    });
                }
            });
        }
    });
    
});