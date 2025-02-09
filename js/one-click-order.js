(function () {
    let buttons = document.querySelectorAll('.sellpress_one_click_order');
    let html = sellpressOneClickL10n.popupHtml;
    let productId;
    let productPrice;
    let form;


    [...buttons].forEach(button => {
        button.addEventListener('click', openPopup);
    });

    function openPopup(e) {
        e.preventDefault();
        productId = +e.target.getAttribute('data-product-id');
        productPrice = e.target.getAttribute('data-product-price');
        document.body.insertAdjacentHTML('beforeend', html);


        form = document.querySelector('.sellpress_one_click_order_popup_form')

        document.querySelector('.sellpress_one_click_order_popup_close').addEventListener('click', closePopup);
        form.addEventListener('submit', submit);
        document.querySelector('.sellpress_once_click_order_popup_background').addEventListener('click', closePopup);

        let paymentSelector = document.querySelector('#sellpress_one_click_payment_method');

        if (paymentSelector) {
            paymentSelector.addEventListener('change', paymentMethodChanged);
        }

        if (sellpressOneClickL10n.paymentSettings.paypal.enabled === true) {
            initPaypal();
        }
    }

    function closePopup() {
        document.querySelector('.sellpress_one_click_order_popup').remove();
    }

    function submit(e = null, paymentStatus = 'pending') {
        if (e) {
            e.preventDefault();
        }


        let formData = new FormData(form);

        formData.append('product_id', productId);
        formData.append('payment_status', paymentStatus);

        let loading = document.querySelector('.sellpress_one_click_popup_loading');
        let submit = form.querySelector('input[type="submit"]');

        if (submit && loading) {
            submit.style.display = 'none';
            loading.classList.add('active');
        }

        fetch(sellpressOneClickL10n.ajaxUrl, {
            method: 'post',
            body: formData
        })
            .then(function (response) {
                if (response.status === 200) {
                    return response.json();
                } else {
                    toastr.error('Something went wrong, please try again later');
                    if (submit && loading) {
                        loading.classList.remove('active');
                        submit.style.display = 'initial';
                    }

                }
            })
            .then(function (result) {
                document.querySelector('.sellpress_one_click_order_popup_close').click();
                if (result.status) {
                    toastr.success('Order Created Successfully!')
                } else {
                    if (submit && loading) {
                        loading.classList.remove('active');
                        submit.style.display = 'initial';
                    }

                    if (result.error) {
                        toastr.error(json.error);
                    }
                }
            })
    }

    function paymentMethodChanged(e) {
        e.preventDefault();
        if (!form.checkValidity()) {
            e.target.value = '';
            toastr.error('Please, fill required fields first');
            return false;
        }
        let paypalButton = document.querySelector('#sellpress_one_click_paypal');
        let submitButton = document.querySelector('#submit_one_click_order_submit');

        if (e.target.value === 'paypal') {
            if (paypalButton) {
                paypalButton.classList.remove('sellpress_hide');
            }

            if (submitButton) {
                submitButton.classList.add('sellpress_hide');
            }

        } else {
            if (paypalButton) {
                paypalButton.classList.add('sellpress_hide');
            }

            if (submitButton) {
                submitButton.classList.remove('sellpress_hide');
            }

        }
    }

    function initPaypal() {
        paypal.Button.render({
            // Configure environment
            env: sellpressOneClickL10n.paymentSettings.paypal.sandbox ? 'sandbox' : 'production',
            client: {
                sandbox: sellpressOneClickL10n.paymentSettings.paypal.client_id,
                production: sellpressOneClickL10n.paymentSettings.paypal.sandbox_client_id
            },
            // Customize button (optional)
            locale: 'en_US',
            style: {
                size: 'small',
                color: 'gold',
                shape: 'pill',
            },

            // Enable Pay Now checkout flow (optional)
            commit: true,

            // Set up a payment
            payment: function (data, actions) {
                return actions.payment.create({
                    payment: {
                        transactions: [{
                            amount: {
                                total: productPrice,
                                currency: sellpressOneClickL10n.generalSettings.currency
                            }
                        }]
                    }
                });
            },
            // Execute the payment
            onAuthorize: function (data, actions) {
                return actions.payment.execute().then(function () {
                    // Show a confirmation message to the buyer
                    submit(null, 'paid');
                });
            },
            onError: function (error) {
                // You will want to handle this differently
                submit(null, 'failed');
            }
        }, '#sellpress_one_click_paypal');
    }
}());