function Alert(type, objs, title=null) {
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

function IDRFormatter (value) {
    if (value !== '' && value !== undefined) {
        var number = parseInt(value)
    
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', }).format(number)
    }
}

function TimestampString2Datetime (value) {
    let datetime = new Date(value);
    let date = datetime.toLocaleDateString();
    let time = datetime.toLocaleTimeString();

    return `${date} ${time}`;
}