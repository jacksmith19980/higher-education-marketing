var app = {

    init: function () {
        $('[data-toggle="tooltip"]').tooltip();

        app.preloader();
        app.wizardForm();
        app.customSelect();
        app.inlineScrole();
        app.smartField();
        app.dateTimePicker();
        app.syncFiled();
        app.fileUploader();
        app.interPhoneCode();
        //app.jsSignature();
        //app.initFieldsRepeater();
        app.flywire();
        app.stripe();
        app.paypal();

        app.maskInput();
        app.validatePaymentForm();



    },
    startModal: function (el) {
        var route = $(el).data('route');
        var title = $(el).data('title');
        var id = (title.split(' ').join('-')) + '-' + (1 + Math.floor(Math.random() * 1000));
        app.openModal(id, route, title, 'no-buttons');
    },
    // Open Modal
    openModal: function (id, route, title, btn) {

        if (typeof btn == 'undefined') {
            btn = 'Save';
        }
        $('#form-modal .modal-content .btn-ok').attr('id', id);
        $('#form-modal .modal-title').html(title);
        if (btn != 'no-buttons') {
            $('#form-modal .btn-ok').show().html(btn);
        } else {
            $('#form-modal .btn-ok').hide();
        }
        $('#form-modal .modal-body').load(route, function () {
            $('#form-modal').modal('show');
            app.customSelect();
            app.tinyMCE();
            app.radioSwitch();
        });
    },
    dismissModal: function (el) {
        var modal = $(el).closest('.form_modal');
        modal.modal('hide');
        $('.form_modal').html();
        tinyMCE.remove();
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
                console.log("stripe");
                handler.open({
                    name: school_name,
                    description: description,
                    currency: currency,
                    email: email,
                    amount: amount,
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

            paypal.Button.render({
                // Configure environment
                env: env,
                client: {
                    sandbox: client_id,
                    production: client_id
                },

                // Customize button (optional)
                locale: 'en_US',
                style: {
                    size: 'small',
                    color: 'blue',
                    shape: 'pill',
                },
                // Set up a payment
                payment: function (data, actions) {
                    return actions.payment.create({
                        transactions: [{
                            amount: {
                                total: amount,
                                currency: currency,
                                details: {
                                    /* subtotal: '30.00',
                                tax: '0.07',
                                shipping: '0.03',
                                handling_fee: '1.00',
                                shipping_discount: '-1.00',
                                    insurance: '0.01' */
                                }
                            },
                            description: 'Registration Fees.',
                            invoice_number: invoice_number,
                            payment_options: {
                                allowed_payment_method: 'UNRESTRICTED'
                            },
                            soft_descriptor: 'ECHI5786786',
                        }],
                        note_to_payer: 'Contact us for any questions +1 888-888-8888.'
                    });
                },
                // Execute the payment
                onAuthorize: function (data, actions) {

                    return actions.payment.execute().then(function () {
                        data.payment_gateway = 'paypal';
                        data.invoice_number = invoice_number;

                        app.appAjax('POST', data, callback_url);

                        swal({
                                title: "Payment Done!",
                                text: "Thank you for your payment",
                                icon: "success",
                                buttons: true,
                                dangerMode: false,
                            })
                            .then((willDelete) => {
                                app.redirect(thank_you);
                            });


                    });
                }
            }, '#paypal-button');
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


    /* Toggle Buttons status */
    toggleButtonStatus: function (el, status, text) {

        if (status == 'processing') {
            $(el).attr('disabled', 'disabled').html('Processing');
        }

        if (status == 'enabled') {
            $(el).removeAttr('disabled').html(text);
        }

    },
    interPhoneCode: function () {
        if ($('.inter-calling-code-mode').length > 0) {
            $('.inter-calling-code-mode').each(function () {

                var number = $(this).val();
                var code = $(this).data('code');
                var lang = $(this).data('lang');
                var countryCode = $(this).data('country-code');

                if (code) {
                    number = number.replace(code, '');
                }

                $(this).intercode({
                    default: number,
                    country: (typeof countryCode != 'undefined') ? countryCode : 'CAN',
                    lang: lang,
                    code: code,
                    apiToken: '0947abce3254c6',
                    selectClass: 'countryCodeselect left select2 form-control-lg form-control custom-select',
                    intercodeFile: '/media/extra-libs/inter-phone/countrycodes.json'
                });

            });
        }
    },

    printDiv: function () {

        var divToPrint = $('.print-this').html();
        var newWin = window.open('', 'Print-Window');
        newWin.document.open();
        newWin.document.write('<html><body onload="window.print()">' + divToPrint + '</body></html>');
        newWin.document.close();
        setTimeout(function () {
            newWin.close();
        }, 10);
    },

    maskInput: function () {
        $('[data-mask]').each(function () {
            var mask = $(this).data('mask');
            var pattern = "";

            if (mask == 'cc_number') {
                pattern = "9999 9999 9999 9999";
            }

            if (mask == 'cc_expiry') {
                pattern = "99/99";
            }
            if (mask == 'cc_expiry_YYYY_MM') {
                pattern = "9999/99";
            }

            $(this).inputmask({
                "mask": pattern
            });

        })

    },
    validatePaymentForm: function () {
        $('.cc_info').change(function () {
            var empty = $('.cc_info').filter(function () {
                return this.value === "";
            });

            if (!empty.length) {
                $('.payment-btn').removeAttr('disabled');
            }
        });
        //$('.payment-btn').removeAttr('disabled');
    },

    /**Process In Application Payment */
    proccessPayment: async function (el, gateway, route) {
        event.preventDefault();
        var formData = {};

        $('.cc_info').each(function () {
            var name = $(this).data("name");
            formData[name] = app.getFieldValue($(this));
        });
        formData.ajax = true;

        // disable Button
        //app.toggleButtonStatus(el, 'processing');
        $('.errors_wrapper').html("");

        await app.appAjax('POST', formData, route, 'data').then(function (data) {

            if (data.response == 'success' && data.status == 200) {
                // Hide Form and Show Success Message
                $(el).closest($('.payment-form')).html(data.extra.message);
                app.enableSaveButton();
            } else {
                // Show Error
                $('.errors_wrapper').html('<div class="alert alert-danger">' + data.extra.message + '</div>');

                // Clear Form
                $('.cc_info').each(function () {
                    var name = $(this).attr("name");
                    if (name != 'payment') {
                        $(this).val('').change();
                    }
                });

                // Enable Button
                app.toggleButtonStatus(el, 'enabled', 'PAY');
            }

        });
    },
    getFieldValue: function (el) {
        // get the value of select
        if (el.is("select")) {
            //return el.find('option:selected').val();
            return el.val();
        } else if (el.is("textarea")) {
            if (el.attr("id") == 'mymce') {
                return tinymce.activeEditor.getContent();
            }
        } else {
            //get the of input
            var type = el.attr('type');
            var name = el.attr('name');
            //text, email, etc.
            if ($.inArray(type, ['text', 'email', 'hidden', 'phone', 'date']) >= 0) {
                return el.val();
            }
            //checkbox, radio
            if ($.inArray(type, ['checkbox', 'radio']) >= 0) {
                if (el.is(":checked")) {
                    return el.val();
                }
            }
        }
        return " ";
    },

    preloader: function () {
        //disabling form submissions if there are invalid fields
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
        $(".preloader").fadeOut();

    },

    inlineScrole: function () {
        //Inline Scroll Init
        $('.inline-scroll').perfectScrollbar();
    },

    dateTimePicker: function () {

    },

    jsSignature: function () {

        $('.canvas-wrapper').each(function () {
            var canvas = this.querySelector("canvas");
            var wrapper_width = $('.canvas-wrapper').width();
            var image_holder = $(canvas).data('image-holder');
            var data = $(this).find('.' + image_holder).val();
            var clearButton = document.querySelector("[data-action=clear]");

            canvas.width = wrapper_width;
            var signturePad = new SignaturePad(canvas, {
                minWidth: 1,
                maxWidth: 2,
                penColor: "rgb(0, 0, 0)",
                onEnd: function () {
                    var image_holder = $(canvas).data('image-holder');
                    var img = canvas.toDataURL()
                    $('.' + image_holder).val(img);
                }
            }).fromDataURL(data);

        });
    },

    clearSignature: function (holder) {
        signturePad.clear();
    },
    // Select 2 Init
    customSelect: function () {
        $(".select2").select2();
    },

    fileUploader: function () {
        // Files Uploader
        if ($(".fileuploader").length > 0) {
            $(".fileuploader").each(function () {

                var allowed = $(this).data("allowed");
                var uploaderURL = $(this).data("upload");
                var deleteURL = $(this).data("destroy");
                var fileHolderName = $(this).data("name");
                var isList = $(this).data("list");
                var uploadeString = $(this).data("uploadstr");

                var deleteBtnTxt = $(this).data("delete-btn-txt");
                var deleteMessage = $(this).data("delete-message");
                var deleteWarning = $(this).data("delete-message");


                $(this).uploadFile({
                    url: uploaderURL,
                    fileName: "documents",
                    multiple: false,
                    dragDrop: true,
                    maxFileSize: 2097152,
                    showFileCounter: false,
                    returnType: "json",
                    allowedTypes: allowed,
                    showDelete: true,
                    showDone: true,
                    deleteStr: deleteBtnTxt,
                    uploadStr: "<span class='uploadTitle'><b>" + uploadeString + "</b></span>",
                    deleteCallback: function (data, pd) {

                        swal({
                                title: deleteMessage,
                                text: deleteWarning,
                                icon: "warning",
                                buttons: true,
                                dangerMode: true,
                            })
                            .then((willDelete) => {
                                if (willDelete) {
                                    app.deleteFile(data.extra.file, deleteURL, fileHolderName);
                                    pd.statusbar.hide();
                                }
                            });
                    },
                    onSelect: function (files) {
                        // Hide Error
                        $('.error_' + fileHolderName).hide();

                        // Setup CSRF token
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });

                        // Update URL to Send File TYPE
                        var type = $('[name="' + fileHolderName + '_list"]').val();
                        if (type) {
                            this.url = uploaderURL + "?type=" + type;
                        }
                        app.disableSaveButton();
                        return true;
                    },

                    onSuccess: function (files, data, xhr, pd) {},

                    afterUploadAll: function (obj) {

                        var response = obj.getResponses();
                        var fileName = response[response.length - 1].extra.file;

                        if (!isList) {
                            //Hide File Uploader
                            $('[data-name="' + fileHolderName + '"]').hide();
                            // Fill File Name Holder
                            $('input.' + fileHolderName).val(fileName);
                        } else {
                            // Upload Files list
                            var filesList = $('input.' + fileHolderName).val();
                            var theList = $('[name="' + fileHolderName + '_list"]');

                            if (!filesList) {
                                filesList = [];
                            } else {
                                filesList = JSON.parse(filesList)
                            }
                            var fileType = theList.val();

                            filesList.push({
                                type: fileType,
                                file: fileName
                            })
                            $('input.' + fileHolderName).val(JSON.stringify(filesList));
                            theList.val("").change();
                        }
                        app.enableSaveButton();
                    },
                });
            });
        }
    },

    toggleFileUploadList: function (el) {
        var type = $(el).val();
        if (type != "") {
            // Show Uploader
            $('.fileUploadList').removeClass('hide');
            $('.fileUploadList').removeClass('file-upload-error');
            $('.fileUploadList').attr('data-type', type).change();
        } else {
            // Hide Uploader
            $('.fileUploadList').addClass('hide');
            $('.fileUploadList').attr('data-type', "").change();
        }
    },

    deleteUploadedFile: function (fileName, route, fileHolderName, el, isList) {
        var title = $(el).data('title');
        var text = $(el).data('text');
        var button = $(el).data('button');
        var successTitle = $(el).data('success-title');
        var successMessage = $(el).data('success-message');
        swal({
                title: title,
                text: text,
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {

                if (willDelete) {
                    $(el).text(button);
                    app.deleteFile(fileName, route, fileHolderName, isList, successMessage, successTitle);
                }
            });
    },


    deleteFile: function (fileName, route, fileHolderName, isList, successMessage, successTitle) {


        var data = {
            file: fileName
        };

        app.appAjax('POST', data, route, 'data').then(function (data) {

            if (data.response == 'success' && data.status == 200) {
                // List File
                if (data.extra.type != null) {
                    var list = $('input.' + fileHolderName).val();
                    list = JSON.parse(list);

                    var filteredList = list.filter(function (el) {
                        console.log(data.extra.file)
                        return el.file != data.extra.file;
                    });


                    if (filteredList.length == 0) {
                        $('[name="' + fileHolderName + '"]').val("");
                    } else {
                        $('[name="' + fileHolderName + '"]').val(JSON.stringify(filteredList));
                    }


                    $('[data-uploaded-file-name="' + data.extra.file + '"]').hide();
                } else {
                    // Single File
                    $('input.' + fileHolderName).attr("value", "").change();
                    $('[data-uploaded-file="' + fileHolderName + '"]').hide();
                    $('[data-name="' + fileHolderName + '"]').show();
                }

                swal(successTitle, successMessage, "success");
            }
        });
    },
    disableSaveButton: function () {

        $(".actions ul li:nth-child(2) a").addClass("disabled_btn");
        $(".actions ul li:nth-child(3) a").addClass("disabled_btn");

    },

    enableSaveButton: function () {

        $(".actions ul li:nth-child(2) a").removeClass("disabled_btn");
        $(".actions ul li:nth-child(3) a").removeClass("disabled_btn");
    },

    wizardForm: function () {

        jQuery.validator.addMethod("filelistuploaded", function (value, element) {
            var requiredFiles = $(element).data("required-fiels");
            var filesList = JSON.parse(value);

            var exist = [];
            filesList.filter(function (file) {
                if (requiredFiles.indexOf(file.type) >= 0) {
                    exist.push(file.type);
                    return file.type;
                }
            });
            jQuery.unique(exist)
            return exist.length == requiredFiles.length;
        });


        var form = $(".validation-wizard").show();
        var add_button = $(".validation-wizard").data('add-button');
        var next_button = $(".validation-wizard").data('next-button');
        var prev_button = $(".validation-wizard").data('prev-button');

        var finish_button = $(".validation-wizard").data('finish-button');

        if (finish_button == 'Yes') {
            finish_button = true;
        } else {
            finish_button = false;
        }


        var stepsForms = $(".validation-wizard").steps({

            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            titleTemplate: '<div class="step_#index# step_icon"></div>',
            saveState: true,
            showFinishButtonAlways: finish_button,
            labels: {
                finish: add_button,
                next: next_button,
                previous: prev_button
            },
            onInit: function (event, currentIndex) {
                app.jsSignature();

            },
            onStepChanging: function (event, currentIndex, newIndex) {

                // Skip Validation if going back
                if (currentIndex > newIndex) {
                    return true;
                }

                var i = 0;

                // thisis only for ASC to be Moved to it's own file
                app.checkPartTimeProgram();

                var isValid = (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate({
                    errorPlacement: function (error, element) {

                        app.showErrors(element, error)

                    }
                }).settings.ignore = ":disabled,:hidden,:file", form.valid());

                if (isValid && newIndex > currentIndex && i == 0) {
                    var status = 'Updated';
                    //submit Application
                    app.submitApplication(event, status, newIndex);
                    i++;
                }
                return isValid;
            },

            onStepChanged: function (event, currentIndex, priorIndex) {

                var disable = $('.form-wizard-section.current .payment-form[data-submit-button]').length;

                if (disable > 0) {
                    app.disableSaveButton();
                }

                // Init Eversign
                if (window.steps == currentIndex + 1) {
                    // Initialize Eversign
                    app.eversignInit(event, currentIndex);
                } else {
                    app.eversignReset();
                }

                // init  Signture
                app.jsSignature();
            },
            onFinishing: function (event, currentIndex) {
                return form.validate({
                    errorPlacement: function (error, element) {
                        app.showErrors(element, error)
                    }
                }).settings.ignore = ":disabled,:hidden,:file", form.valid()
            },
            onFinished: function (event, currentIndex) {

                //submit Application
                var form = event.target;
                $(form).append('<input type="hidden" name="status" value="Submitted" /> ');
                $(form).append('<input type="hidden" name="step" value="' + (currentIndex + 1) + '" /> ');
                form.submit();
                $(".preloader").fadeIn();
            }
        });


        window.steps = stepsForms.steps('getTotalSteps');

        // Tab Icon
        $('.form-wizard-section').each(function (i, el) {

            var icon = $(el).data('icon');
            var label = $(el).data('label');
            var step = $('.step_' + (i + 1));

            if (typeof icon != 'undefined') {
                step.css({
                    'background-image': "url(" + icon + ")",
                });

                return true;
            }

            if (typeof label != 'undefined') {
                step.css({
                    'width': '100%',
                    'display': 'table',
                    'align': 'centre',
                    'verticale-align': 'middle'
                }).html('<h4 class="step-label">' + label + '</h4>');
                return true;
            }


        });

    },

    eversignReset: function () {

        if ($('#eversigned-body').length) {
            // Reset the Signature Block
            $('#eversigned-body').html("");
        }
    },
    eversignInit: function (event, step) {
        // Get Document hash
        var document_hash = $('[name="document_hash"]').val();
        if ($('#eversigned-body').length > 0 && $('.cp_signing_complete').length == 0) {
            app.disableSaveButton();
            // Reset the Signature Block
            $('#eversigned-body').html("");

            //var mapping = $('#eversigned-body').data('mapping');
            var eversign_url = $('#eversigned-body').data('eversign_url');
            var field_id = $('#eversigned-body').data('field-id');
            var data = {
                field_id: field_id,
                document_hash: document_hash
            };

            $('#eversigned-body').html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');

            app.appAjax('POST', data, eversign_url).then(function (response) {

                if (response.status == 200) {
                    $('#eversigned-body').html("");
                    $('[name="document_hash"]').val(response.extra.documentHash);
                    eversign.open({
                        url: response.extra.documentURL,
                        containerID: 'eversigned-body',
                        width: '100%',
                        height: '1200',
                        events: {
                            loaded: function () {},
                            signed: function () {
                                //Reditect to payment
                                app.applicationSigned(response.extra.documentHash, event, step);
                            },
                            declined: function () {},
                            error: function () {}
                        }
                    });
                }

            });
        }
    },
    applicationSigned: function (hash, event, step) {

        if (hash) {
            $('[name="document_signed"]').val(true);
            $('[name="document_signed_at"]').val(new Date());
        }
        var form = event.target;
        $(form).append('<input type="hidden" name="status" value="Submitted" /> ');
        $(form).append('<input type="hidden" name="step" value="' + (step + 1) + '" /> ');
        form.submit();
        $(".preloader").fadeIn();
    },

    getCart: function () {
        var application = $('input[name="application"]').val();
        var route = $('#cart-review').data('route');
        var field = $('#cart-review').data('field');
        var data = {
            element: field,
            application: application
        };

        app.appAjax('POST', data, route).then(function (response) {
            if (response.status == 200) {
                $('#cart-review').html("");
                $('#cart-review').html(response.extra.html);
            }
        });
    },

    showErrors: function (element, error) {
        var name = $(element).attr('name');
        name = name.replace(']', '');
        name = name.replace('[', '');


        $(element).closest($('.field_wrapper')).find('.error_container').html($(error)).show();

        if ($('.fileuploader').length) {
            $('[data-name="' + name + '"]').addClass('file-upload-error');
            $('.error_' + name).html($(error)).show();
        }

        if ($('#' + name).length) {
            $('[data-image-holder="' + name + '"]').addClass('signature-error');
        }
    },
    submitApplication: function (event, status, step) {
        var form = event.target;

        var route = $(form).attr('action');

        formData = new FormData($(form)[0]);
        if (status) {
            formData.append('status', status);
        }
        if (step) {
            formData.append('step', step);
        }

        app.appAjax('POST', formData, route, 'file');
    },

    // Global Ajax Request
    appAjax: function (method, data, route, dataType) {

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

    smartField: function () {

        // Hide Fields
        $('.smart-field').each(function () {

            app.smartFieldInit($(this), $(this).data('action'));
            // Process Smart Fields Logic
            app.processSmartField($(this));

        });

    },

    smartFieldInit: function (el, action) {

        if (action == 'show') {
            el.hide();
        }

    },

    getFieldValue: function (el) {


        // get the value of select
        if (el.is("select")) {
            //return el.find('option:selected').val();
            return el.val();
        } else if (el.is("textarea")) {
            if (el.attr("id") == 'mymce') {
                return tinymce.activeEditor.getContent();
            }
        } else if (el.is("checkbox")) {} else {
            //get the of input
            var type = el.attr('type');
            var name = el.attr('name');
            var values = [];

            //text, email, etc.
            if (jQuery.inArray(type, ['text', 'email', 'hidden', 'phone', 'date']) >= 0) {
                return el.val();
            }

            //checkbox, radio
            if ($.inArray(type, ['checkbox', 'radio']) >= 0) {
                el.each(function () {

                    if ($(this).is(":checked")) {
                        values.push($(this).val());
                    }
                })
                return values;
            }
        }
        return " ";
    },

    processSmartField: function (el) {

        //get referance field
        var ref = $('[name="' + el.data('reference') + '"]');

        var field = $('[name="' + el.data('field') + '"]');

        // Get Referance field value
        //var ref_value = ref.val();
        var ref_value = app.getFieldValue(ref);

        //get action
        var action = $(el).data('action');

        //get operator
        var operator = $(el).data('operator');
        //get value
        var value = $(el).data('value');

        // Call Action
        eval("app." + action + "SmartField(el , field , ref , ref_value , operator , value )");


        // run the action if referance field is changed
        ref.change(function () {
            var ref_value = app.getFieldValue(ref);
            eval("app." + action + "SmartField(el , field ,  ref , ref_value ,operator , value )");
        });

        ref.keyup(function () {
            var ref_value = app.getFieldValue(ref);
            eval("app." + action + "SmartField(el ,field ,  ref , ref_value ,operator , value )");
        });
    },

    resetField: function (field) {
        field.val("").change();
    },
    showSmartField: function (el, field, ref, ref_value, operator, value) {
        if (operator == 'equal') {
            if (ref_value == value) {
                el.show();
                field.removeAttr("disabled");
            } else {
                el.hide();
                app.resetField(field);
                field.attr("disabled", "disabled");
            }
        } else if (operator == 'not_empty') {
            if (ref_value) {
                el.show();
                field.removeAttr("disabled");
            } else {
                el.hide();
                app.resetField(field);
                field.attr("disabled", "disabled");
            }
        } else if (operator == 'empty') {
            if (ref_value) {
                el.hide();
                app.resetField(field);
                field.attr("disabled", "disabled");
            } else {
                el.show();
                field.removeAttr("disabled");
            }
        } else if (operator == 'contain') {
            value = value.split(",");

            if ($.isArray(ref_value)) {
                var intersection = ref_value.filter(function (el) {
                    return value.indexOf(el) != -1
                });

                if (intersection.length > 0) {
                    el.show();
                    field.removeAttr("disabled");
                } else {
                    el.hide();
                    app.resetField(field);
                    field.attr("disabled", "disabled");
                }
            } else {
                if ($.inArray(ref_value, value) >= 0) {
                    el.show();
                    field.removeAttr("disabled");
                } else {
                    el.hide();
                    app.resetField(field);
                    field.attr("disabled", "disabled");
                }
            }
        }

    },


    hideSmartField: function (el, field, ref, ref_value, operator, value) {

        if (operator == 'equal') {
            if (ref_value == value) {
                el.hide();
                //field.attr("disabled" , "disabled");
            } else {
                el.show();
                //field.removeAttr("disabled");
            }
        } else if (operator == 'not_empty') {
            if (ref_value) {
                el.hide();
                //field.attr("disabled" , "disabled");
            } else {
                el.show();
                //field.removeAttr("disabled");
            }
        } else if (operator == 'empty') {
            if (ref_value) {
                el.show();
                //field.removeAttr("disabled");
            } else {
                el.hide();
                //field.attr("disabled" , "disabled");
            }
        } else if (operator == 'contain') {
            value = value.split(",");

            if ($.isArray(ref_value)) {
                var intersection = ref_value.filter(function (el) {
                    return value.indexOf(el) != -1
                });

                if (intersection.length > 0) {
                    el.show();
                } else {
                    el.hide();
                }
            } else {
                if ($.inArray(ref_value, value) >= 0) {
                    el.show();
                } else {
                    el.hide();
                }
            }
        }

    },
    initFieldsRepeater() {

        if ($('.field_repeater').length > 0) {
            $('.field_repeater').each(function () {

                var fields = $(this).data('fields');
                fields = fields.split(',');

                for (var i = fields.length - 1; i >= 0; i--) {
                    // update the original fields name
                    var originalField = $('[name="' + fields[i] + '"]');
                    //originalField.attr('name' , fields[i]+'_0' ).addClass('originalField');
                    originalField.attr('name', fields[i] + '[]').addClass('originalField');
                }


            })
        }

    },
    repeatFields: function (el, counter) {
        event.preventDefault();
        var max = $('[name="repeater_max_' + counter + '"]').val();
        var wrapper = $(el).closest($('.repeater_box'));
        var repeatsLength = $('.box_' + counter).length;

        // update Counter
        var counterFiled = $('[name="' + counter + '"]');
        var currentCounter = Number(counterFiled.val()) + 1;
        if (currentCounter >= max) {
            // disable all Add More Buttons
            $('button.field_repeater').each(function () {
                $(this).attr('disabled', 'disabled');
            });
        }
        counterFiled.val(currentCounter);

        var clone = wrapper.clone(true);

        //var ID = Math.floor(Math.random() * 1000);
        var ID = repeatsLength + 1;

        $(clone).find('.remove-box').removeClass("hidden");

        $(clone).find('.field_repeater').remove();

        $(clone).find('input, select').each(function () {
            var originalName = $(this).attr('name');
            originalName = originalName.split('[')[0];
            name = originalName + "[" + ID + "]";

            $(this).attr('name', name).change();
            $(this).attr('id', name).change();
            $(this).next('label').attr('for', name);
            $(this).next('.error_container').addClass('error_' + originalName + '_' + ID);

        });

        $(clone).find('.repeated_field').each(function () {
            $(this).removeAttr('required');
            $(this).val("");
        });

        wrapper.nextAll('.repeated_boxes').append(clone)
        //clone.insertAfter(wrapper);

    },
    removeRepeatedFields: function (el, counter) {
        event.preventDefault();
        $(el).closest($('.repeater_box')).fadeOut('meduim', function () {
            var max = $('[name="repeater_max_' + counter + '"]').val();
            var counterFiled = $('[name="' + counter + '"]');
            var currentCounter = Number(counterFiled.val()) - 1;
            counterFiled.val(currentCounter);
            if (currentCounter < max) {
                // disable all Add More Buttons
                $('button.field_repeater').each(function () {
                    $(this).removeAttr('disabled');
                });
            }
            $(this).remove();
        });
    },

    requiredSmartField: function (el, field, ref, ref_value, operator, value) {

        if (operator == 'equal') {
            if (ref_value == value) {
                field.attr("required", "required");
            } else {
                field.removeAttr("required");
            }
        } else if (operator == 'not_empty') {
            if (ref_value) {
                field.attr("required", "required");
            } else {
                field.removeAttr("required");
            }
        } else if (operator == 'empty') {
            if (ref_value) {
                field.removeAttr("required");
            } else {
                field.attr("required", "required");
            }
        } else if (operator == 'contain') {
            if (ref_value.search(value) >= 0) {
                field.attr("required", "required");
            } else {
                field.removeAttr("required");
            }
        }
    },
    // @ Todo Sync. Field
    syncFiled: function () {
        if ($("[data-sync]").length > 0) {
            $("[data-sync]").each(function () {

                var field = $(this);
                var mainField = $(this).data("sync-field");
                var target = $(this).data('sync-target');
                var source = $(this).data('sync-source');
                var route = $(this).data('route');


                $('[name="' + mainField + '"]').change(function () {

                    app.getSyncedData(this, field, target, source, route, fieldValue);
                });

                if (!$(this).val()) {
                    var el = ('[name="' + mainField + '"]');
                    var fieldValue = $(this).data('field-value');
                    app.getSyncedData(el, field, target, source, route, fieldValue);
                }


            });
        }
    },
    getSyncedData(el, field, target, source, route, fieldValue) {

        var value = $(el).val();

        if (!value) {
            return false;
        }

        var data = {
            value: value,
            target: target,
            source: source,
        };

        app.appAjax('POST', data, route, 'data').then(function (data) {

            if (data.response == 'success' && data.status == 200) {
                field.html("").trigger('change');
                $.each(data.extra.data, function (key, value) {

                    var newOption = new Option(value, key, false, false);
                    field.append(newOption).trigger('change');

                    if (fieldValue) {
                        field.val(fieldValue).trigger('change');
                    }

                });
                field.removeAttr('disabled');
            }
        });
    },


    // ASC Custom Functions
    // @todo Separate this to it's own file
    checkPartTimeProgram: function (el) {

        var course = $('.part-time-options').val();

        var partTimeCourses = ["Part-Time Morning: 2 Classes Per Week", "Part-Time Morning: 1 Class Per Week", "Part-Time Evening: 4 Days Per Week", "Part-Time Evening: 3 Days Per Week", "Part-Time Evening: 2 Days Per Week"];

        if ($.inArray(course, partTimeCourses) >= 0) {
            //$(".validation-wizard").steps("skip" ,1);

            $('.dependent_field').each(function () {
                $(this).attr('disabled', 'disabled');
            });
            $('.notAvailable').show()
        } else {
            $('.dependent_field').each(function () {
                $(this).removeAttr('disabled');
                $('.notAvailable').hide();
            })
        }
    },

    searchCourses: function (itemSelected, route) {
        var campus = $(itemSelected);
        field = campus.data('field');
        fieldName = campus.data('fieldname');
        var cleanroute = $(itemSelected).data('cleanroute');
        app.deleteCourseInCart(0, cleanroute);
        $(itemSelected).on('select2:select', function (e) {
            app.cleanCourseDivsContainers();
            $('select[name="' + fieldName + '[courses][]"] option[value]').remove();
            $('select[name="' + fieldName + '[courses][]"]').select2({
                ajax: {
                    url: route,
                    type: "get",
                    dataType: 'json',
                    data: {
                        campus: campus.val(),
                        element: field
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
        });
    },

    searchDatesAddons: function (itemSelected, route) {
        var course = $(itemSelected);
        app.cleanCourseDivsContainers();
        field = course.data('field');
        $.ajax({
            url: route,
            type: "get",
            dataType: 'json',
            data: {
                course: course.val(),
                element: field
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            app.getStratDates(data.extra.datesHtml, itemSelected);
            app.getCourseAddons(data.extra.addonsHtml, itemSelected);
        });
    },

    getStratDates: function (start_dates, itemSelected, hash = 0) {
        if (start_dates !== 0) {
            if (hash === 0) {
                $('.start-date').append(start_dates);
            } else {
                $('.start-date-' + hash).append(start_dates);
            }
        }
    },

    dateSelected: function (itemSelected, hash = 0) {
        $('.date-addons').empty();
        app.getDatesAddons(itemSelected, hash);
        app.syncCourseDateOnCart(itemSelected, hash);
    },

    getDatesAddons: function (itemSelected, hash = 0) {
        value = itemSelected.value;
        field = $(itemSelected).data('field');
        course = $(itemSelected).data('course');
        var url = $(itemSelected).data('route');
        $.ajax({
            url: url,
            type: "get",
            dataType: 'json',
            data: {
                date: value,
                course: course,
                element: field,
                hash: hash
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            app.showDateAddons(data.extra.datesAddonsHtml, hash);
        });
    },

    showDateAddons: function (addons, hash) {
        if (addons !== "") {
            if (hash == '') {
                $('.date-addons').empty();
                $('.date-addons').append(addons);
            } else {
                $('.date-addons-' + hash).empty();
                $('.date-addons-' + hash).append(addons);
            }
        }
    },

    getCourseAddons: function (addons, itemSelected, hash = '') {
        if (addons !== "") {
            if (hash == '') {
                $('.course-addons').append(addons);
            } else {
                $('.course-addons-' + hash).append(addons);
            }
        }
    },

    addCourseRow: function (itemSelected) {
        var button = $(itemSelected);
        var max = button.data('max');
        var route = button.data('route');
        var field = button.data('field');

        var courses_added = 0;
        if (courses_added < max) {
            $.ajax({
                url: route,
                type: "get",
                data: {
                    element: field
                },
                statusCode: {
                    404: function () {
                        console.log('page not found')
                    }
                }
            }).done(function (data) {
                $('.courses-form').append(data);
                courses_added++;
            });
        } else {
            console.log('You cant select more Courses');
        }
    },

    cleanCourseDivsContainers: function () {
        $('.start-date').empty();
        $('.course-addons').empty();
        $('.date-addons').empty();
        $('.addons').empty();
        $('.courses').empty();
    },

    cleanPrograDivsContainers: function () {
        $('.program-start-date').empty();
        $('.program-addons').empty();
    },

    searchPrograms: function (el, object ,route) {
        field = $(el).data('field');
        fieldName = $(el).data('fieldname');

        app.cleanPrograDivsContainers();
        $('select[name="' + fieldName + '[programs]"] option[value]').remove();
        $('select[name="' + fieldName + '[programs]"]').select2({
            ajax: {
                url: route,
                type: "get",
                dataType: 'json',
                data: {
                    value: $(el).val(),
                    object: object,
                    element: field
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });
    },

    searchProgramDatesAddons: function (itemSelected, route) {
        var program = $(itemSelected);
        app.cleanPrograDivsContainers();
        field = program.data('field');
        $.ajax({
            url: route,
            type: "get",
            dataType: 'json',
            data: {
                program: program.val(),
                element: field
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            app.getProgramStratDates(data.extra.datesHtml, itemSelected);
            app.getProgramAddons(data.extra.addonsHtml, itemSelected);
        });
    },

    getProgramStratDates: function (datesHtml, itemSelected) {
        if (datesHtml !== "") {
            $('.program-start-date').append(datesHtml);
        }
    },

    getProgramAddons: function (addonsHtml, itemSelected) {
        if (addonsHtml !== "") {
            $('.program-addons').append(addonsHtml);
        }
    },

    searchCoursesSeconds: function (itemSelected, route) {
        var campus = $(itemSelected);
        field = campus.data('field');
        fieldName = campus.data('fieldname');
        hash = campus.data('hash');
        var cleanroute = $(itemSelected).data('cleanroute');
        app.deleteCourseInCart(hash, cleanroute);
        $(itemSelected).on('select2:select', function (e) {
            app.cleanDivsContainersHash(hash);
            $('select[name="' + fieldName + '[courses][' + hash + ']"] option[value]').remove();
            $('select[name="' + fieldName + '[courses][' + hash + ']"]').select2({
                ajax: {
                    url: route,
                    type: "get",
                    dataType: 'json',
                    data: {
                        campus: campus.val(),
                        element: field
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });
        });
    },

    deleteCourse: function (element) {
        hash = $(element).data('hash');
        $('#' + hash).remove();
        var cleanroute = $(element).data('cleanroute');
        app.deleteCourseInCart(hash, cleanroute);
    },

    searchDatesAddonsSeconds: function (itemSelected, route) {
        var course = $(itemSelected);
        hash = course.data('hash');
        app.cleanDivsContainersHash(hash);
        field = course.data('field');
        $.ajax({
            url: route,
            type: "get",
            dataType: 'json',
            data: {
                course: course.val(),
                element: field,
                hash: hash
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            app.getStratDates(data.extra.datesHtml, itemSelected, hash);
            app.getCourseAddons(data.extra.addonsHtml, itemSelected, hash);
        });
    },

    cleanDivsContainersHash: function (hash) {
        $('.start-date-' + hash).empty();
        $('.course-addons-' + hash).empty();
        $('.date-addons-' + hash).empty();
    },

    syncCourseDateOnCart: function (itemSelected, hash = null) {
        var course = $(itemSelected).data('course');
        var syncroute = $(itemSelected).data('syncroute');
        var date = $(itemSelected).val();
        var application = $('input[name="application"]').val();
        var submission = $('input[name="submission"]').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: syncroute,
            type: "post",
            dataType: 'json',
            data: {
                course: course,
                date: date,
                application: application,
                submission: submission,
                hash: hash
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            console.log(data);
        });
    },

    deleteCourseInCart: function (hash, cleanroute) {
        var application = $('input[name="application"]').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: cleanroute,
            type: "put",
            dataType: 'json',
            data: {
                application: application,
                hash: hash
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            console.log(data);
        });
    },

    addonsCourse: function (itemSelected, hash = 0) {
        var syncroute = $(itemSelected).data('syncroute');
        var course = $(itemSelected).data('course');
        var key = $(itemSelected).attr("id");
        var application = $('input[name="application"]').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: syncroute,
            type: "put",
            dataType: 'json',
            data: {
                application: application,
                course: course,
                action: itemSelected.checked,
                addon: key,
                hash: hash
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            if (data.message == 'Need add course first') {
                alert('Select a course date first');
                itemSelected.checked = false;
            }
        });
    },

    addonsDateCourse: function (itemSelected, hash = 0) {
        var syncroute = $(itemSelected).data('syncroute');
        var course = $(itemSelected).data('course');
        var key = $(itemSelected).attr("id");
        var addonDate = $(itemSelected).data("date");
        var application = $('input[name="application"]').val();
        console.log(addonDate);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: syncroute,
            type: "put",
            dataType: 'json',
            data: {
                application: application,
                course: course,
                action: itemSelected.checked,
                addon: key,
                addonDate: addonDate,
                hash: hash
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            console.log(data);
        });
    },

    syncProgramDateOnCart: function (itemSelected) {
        var syncroute = $(itemSelected).data('syncroute');
        var program = $(itemSelected).data('program');
        var registration_fees = $(itemSelected).data('registration-fees');
        var application = $('input[name="application"]').val();
        var submission = $('input[name="submission"]').val();
        var start_date = $(itemSelected).attr("id");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: syncroute,
            type: "post",
            dataType: 'json',
            data: {
                program: program,
                registration_fees: registration_fees,
                application: application,
                submission: submission,
                start_date: start_date
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            console.log(data);
        });
    },

    addonsProgram: function (itemSelected) {
        var syncroute = $(itemSelected).data('syncroute');
        var program = $(itemSelected).data('program');
        var addon = $(itemSelected).attr("id");
        var application = $('input[name="application"]').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: syncroute,
            type: "put",
            dataType: 'json',
            data: {
                application: application,
                program: program,
                action: itemSelected.checked,
                addon: addon,
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            if (data.message == 'Need select program date first') {
                alert('Select program date first');
                itemSelected.checked = false;
            }
        });
    },

    addons: function (itemSelected, label, price) {
        var syncroute = $(itemSelected).data('syncroute');
        var application = $('input[name="application"]').val();
        console.log(label, price);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: syncroute,
            type: "post",
            dataType: 'json',
            data: {
                application: application,
                label: label,
                action: itemSelected.checked,
                price: price,
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            //
        });
    },

    showPaymentType: function (el, route) {
        var value = $(el).val();
        console.log(value);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: route,
            type: "post",
            dataType: 'json',
            data: {
                value: value,
            },
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            $('.payment-type-resume').empty();
            $('.payment-type-resume').append(data.extra.html);
        });
    },

};

$(function () {
    app.init();
    $('input[type="checkbox"]:disabled').prop("checked", true).trigger("change");

    $(document).on('focus', '.select2', function (e) {
        if (e.originalEvent) {
            var s2element = $(this).siblings('select');
            s2element.select2('open');

            // Set focus back to select2 element on closing.
            s2element.on('select2:closing', function (e) {
                s2element.select2('focus');
            });
        }
    });

    $('body').on('focus', ".datepicker-autoclose", function () {
        var display = $(this).data('format') ? $(this).data('format') : 'YYYY-MM-DD';

        var options = {
            autoclose: true,
            todayHighlight: true,
            assumeNearbyYear: true,
            format: 'yyyy-mm-dd',
            orientation: 'bottom'
        };

        // Add Start And End Date if Avaialable
        if ($(this).data('start-date')) {
            options.startDate = $(this).data('start-date');
        }

        if ($(this).data('end-date')) {
            options.endDate = $(this).data('end-date');
        }

        if ($(this).data('start-view')) {
            options.startView = $(this).data('start-view');
        }

        $(this).removeClass('hasDatepicker').datepicker(options).on("changeDate", function (e) {
            $(this).datepicker('destroy');
        });
    });

    if (submittedStep) {
        for(var i=0; i < submittedStep; i++) {
            $(".validation-wizard").steps('next');
        }
    }
});
