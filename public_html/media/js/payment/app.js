var payment = {
    initPayment: function () {
        payment.flywire();
        payment.stripe();
        payment.paypal();
    },

    redirect: function (link) {
        window.location.href = link;
        return false;
    },
    redirect: function (link) {
        window.location.href = link;
        return false;
    },

    stripe: function () {

        if ($('#stripPaymentButton').length > 0) {


            var el = $('#stripPaymentButton'),
                key = el.data('api-key'),
                logo = el.data('logo'),
                school_name = el.data('school-name'),
                description = el.data('description'),
                currency = el.data('currency'),
                email = el.data('email'),
                call_back = el.data('call-back'),
                response_call_back = el.data('response-call-back'),
                amount = el.data('amount');

            var handler = StripeCheckout.configure({
                key: key,
                image: logo,
                locale: 'auto',
                token: function (token) {
                    token.payment = 'stripe';
                    token.amount = amount;
                    token.currency = currency;
                    token.description = description;
                    app.appAjax('POST', token, call_back).then(function (data) {
                        if (data.response.paid == true && data.status == 200) {
                            app.appAjax('POST', data, response_call_back).then(function (response) {
                                if (response.status == 200 && response.response == 'success') {
                                    $('#paymentContainer').html(response.message);
                                }
                            })
                        }
                    });
                }
            });
            el.click(function (e) {

                handler.open({
                    name: school_name,
                    description: description,
                    currency: currency,
                    email: email,
                    amount: amount,
                    allowRememberMe: false,
                    panelLabel: "Register Now",
                    closed: function () {
                        setTimeout(function () {
                            $('.proccessing-box').addClass('hidden');
                            $('#paymentContainer').removeClass('hidden');
                        }, 5000);
                    }
                });
                e.preventDefault();

                $('.proccessing-box').removeClass('hidden');
                $('#paymentContainer').addClass('hidden');

                /* $('#paymentContainer').html("<div class='card-body alert alert-warning'>Processing......</div>"); */
            });
            // Close Checkout on page navigation:
            window.addEventListener('popstate', function (e) {
                handler.close();
            });
        }
    },

    paypal: function () {

        if ($('#paypal-button').length > 0) {
            var amount = $('#paypal-button').data('amount');
            var client_id = $('#paypal-button').data('client-id');
            var env = $('#paypal-button').data('env');
            var currency = $('#paypal-button').data('currency');
            var invoice_number = $('#paypal-button').data('invoice-number');
            var callback_url = $('#paypal-button').data('callback-url');
            var thank_you = $('#paypal-button').data('thank-you');

            paypal.Buttons({
                createOrder: function (data, actions) {
                    // Set up the transaction
                    return actions.order.create({
                        purchase_units: [{
                            "amount": {
                                "currency_code": currency,
                                "value": amount
                            }
                        }]
                    });
                },
                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        data.details = details;
                        data.gateway = 'paypal';
                        data.invoice_number = invoice_number;

                        $('#paymentContainer').html("<div class='card-body alert alert-warning'>Processing......</div>");

                        app.appAjax('POST', data, callback_url)
                            .then(function (response) {
                                if (response.status == 200 && response.response == 'success') {
                                    $('#paymentContainer').html(response.message);
                                    if (typeof thank_you != 'undefined') {
                                        window.location.href = thank_you
                                    }
                                }
                            })
                    });
                },
                onCancel: function (data) {
                    console.log("onCancel");
                    console.log(data);
                },
                onError: function (err) {
                    console.log("onError");
                    console.log(err);
                    //	window.location.href = "/your-error-page-here";
                }
            }).render('#paypal-button');
        }

    },

    flywire: function () {

        if ($('#flywire-payex').length > 0) {
            var amount = $('#flywire-payex').data('amount');
            var destination = $('#flywire-payex').data('destination');
            var callback = $('#flywire-payex').data('call-back');
            var invoice_number = $('#flywire-payex').data('invoice-number');
            var sender_first_name = $('#flywire-payex').data('sender-first-name');
            var sender_last_name = $('#flywire-payex').data('sender-last-name');
            var sender_email = $('#flywire-payex').data('sender-email');

            var student_first_name = $('#flywire-payex').data('student-first-name');
            var student_last_name = $('#flywire-payex').data('student-last-name');
            var student_id = $('#flywire-payex').data('student-id');
            var env = $('#flywire-payex').data('env');
            window.flywire.Payment.render({

                // Set your environment

                // demo, production
                env: env,

                // Set the origin of the transaction
                provider: "embed",

                // Set your preferred locale
                // en-EN, es-ES, zh-CN, ko, fr-FR, ja, pt-PT
                locale: "en-EN",
                // Set your institution unique Code
                destination: destination,

                read_only: "amount,invoice_number,sender_email , student_first_name , student_last_name , student_id",

                invoice_number: invoice_number,

                amount: amount,

                callback_url: callback,

                sender_first_name: sender_first_name,
                sender_last_name: sender_last_name,
                sender_email: sender_email,
                student_first_name: student_first_name,
                student_last_name: student_last_name,
                student_id: student_id,

                callback_id: invoice_number,

                theme: {
                    brandColor: "#3498db",
                    chat: false,
                    footer: false,
                }
            }, "#flywire-payex");
        }

    },
    appAjax: function (method, data, route, dataType) {
        console.log(method);
        // Default DataType is Data
        dataType = dataType || 'data';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        var args = {
            url: route,
            method: method,
            data: data,
            dataType: "json",
        };

        // For Ajax File Upload
        if (dataType == 'file') {
            args.processData = false;
            args.contentType = false;
        }
        return $.ajax(args);
    },

};

$(function () {
    payment.initPayment();
});
