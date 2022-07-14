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
            status = 'Unsuspend'
            break;

        case 4:
            status = 'Terminated'
            break;
    
        default:
            status = 'No defined'
            break;
    }

    return status
}

function customerCandidateStatus (value) {
    let status = null;

    switch (parseInt(value)) {
        case 0:
            status = 'Registered'
            break;

        case 1:
            status = 'Confirmation'
            break;

        case 2:
            status = 'Schedule Install'
            break;

        case 3:
            status = 'On Process'
            break;

        case 4:
            status = 'Cancel'
            break;

        case 5:
            status = 'Success'
            break;
    
        default:
            status = 'No defined'
            break;
    }

    return status
}