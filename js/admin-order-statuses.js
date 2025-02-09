(function(){
    let orderStatusSelects = document.querySelectorAll('.sellpress_order_status');
    let orderPaymentStatusSelects = document.querySelectorAll('.sellpress_order_payment_status');

    [...orderStatusSelects].forEach(select => {
        select.addEventListener('change', changeStatus);
    });

    [...orderPaymentStatusSelects].forEach(select => {
        select.addEventListener('change', changePaymentStatus);
    });

    function changeStatus(e){
        e.preventDefault();

        let status = +e.target.value;
        const id = +e.target.closest('tr').getAttribute('data-id');

        let data = new FormData();

        data.append('action', 'sellpress_update_order_status');
        data.append('status', status);
        data.append('id', id);
        data.append('nonce', sellpressL10n.nonce);

        fetch(sellpressL10n.ajaxUrl, {
            method: 'post',
            body: data
        })
            .then(function (response) {
                if (response.status === 200) {
                    return response.json();
                } else {
                    toastr.error('Something went wrong, please try again later');
                }
            })
            .then(function (result) {
                if (result.status) {
                    toastr.success('Order Updated Successfully!')
                } else {
                    if (result.error) {
                        toastr.error(json.error);
                    }
                }
            })
    }

    function changePaymentStatus(e){
        e.preventDefault();

        let status = +e.target.value;
        const id = +e.target.closest('tr').getAttribute('data-id');

        let data = new FormData();

        data.append('action', 'sellpress_update_order_payment_status');
        data.append('payment_status', status);
        data.append('id', id);
        data.append('nonce', sellpressL10n.nonce);

        fetch(sellpressL10n.ajaxUrl, {
            method: 'post',
            body: data
        })
            .then(function (response) {
                if (response.status === 200) {
                    return response.json();
                } else {
                    toastr.error('Something went wrong, please try again later');
                }
            })
            .then(function (result) {
                if (result.status) {
                    toastr.success('Order Updated Successfully!')
                } else {
                    if (result.error) {
                        toastr.error(json.error);
                    }
                }
            })
    }
}());