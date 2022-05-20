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

function TimestampString2Date (value) {
    let datetime = new Date(value)
    let date = datetime.toLocaleDateString();

    return date;
}

function billingStatus (value) {
    let status = null;

    switch (value) {
        case 0:
            status = 'Unpaid'
            break;

        case 1:
            status = 'Paid'
            break;

        case 2:
            status = 'Suspend'
            break;

        case 3:
            status = 'Terminated'
            break;

        case 4:
            status = 'Waiting Verif'
            break;

        case 5:
            status = 'Unsuspend'
            break;
    
        default:
            status = 'No defined'
            break;
    }

    return status
}