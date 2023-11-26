var app = {};

// Ajax Route
app.ajaxRoute = window.agentsAjaxRoute;

app.uploaderUrl = window.uploaderUrl

app.ajaxResponse = {};

app.order = 0;

var token = $('meta[name="_token"]').attr('content');

$("#accounting_student_table_wrapper .dataTables_length").css("display", "none");
$("#accounting_student_table_wrapper .dataTables_filter").css("display", "none");
var accounting_student_table = $('#accounting_student_table').DataTable({
    processing: true,
    serverSide: true,
    'bInfo': false,
    responsive: true,
    'columnDefs': [{
            responsivePriority: 0,
            targets: 0
        },
        {
            responsivePriority: 1,
            targets: -1
        }
    ],
    "language": {
        // "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/" + $('#accounting_student_table').data('i18n') + ".json"
    },
    "ajax": {
        "url": $('#accounting_student_table').data('route'),
        'beforeSend': function (request) {
            request.setRequestHeader("X-CSRF-TOKEN", token);
        },
        'data': function (data) {
            // Read values
            var date_range = $('#calendarRanges').val();

            var start_date = '';
            var end_date = '';
            var student_id = '';

            if ($('.calendarRanges').length > 0 && calendarRanges != '') {
                var dates = date_range.split(" - ");
                start_date = dates[0];
                end_date = dates[1];
            }

            student_id = $('#student_id').val();

            // Append to data
            data.start_date = start_date;
            data.end_date = end_date;
            data.student_id = student_id;
        }
    },
    "order": [
        [0, "desc"]
    ],
    columns: [{
            data: 'date'
        },
        {
            data: 'type'
        },
        {
            data: 'no'
        },
        {
            data: 'balance'
        },
        {
            data: 'total'
        },
        {
            data: 'status'
        },
    ],

    rowCallback: function (row, data) {
        $(row).attr('data-accounting-id', data['accounting_id']);
    }
});

app.textEditor =  function () {
    if ($('.replayBody').length > 0) {
        $('.replayBody').each(function () {
            window.editor = new Jodit(this, {
                autofocus: false,
                uploader: {
                    insertImageAsBase64URI: true
                },
                enter: "BR",
                buttonsSM: "bold,image,|,brush,paragraph,|,align,,undo,redo,|,eraser,dots,strikethrough,underline,superscript,subscript,outdent,indent,font,file,video,cut,hr,symbol",

                buttonsMD: "bold,image,|,brush,paragraph,|,align,,undo,redo,|,eraser,dots,strikethrough,underline,superscript,subscript,outdent,indent,font,file,video,cut,hr,symbol",

                buttonsXS: "bold,image,|,brush,paragraph,|,align,,undo,redo,|,eraser,dots,strikethrough,underline,superscript,subscript,outdent,indent,font,file,video,cut,hr,symbol"

            });
        });

        window.editor.editor.addEventListener('keyup', function (event) {
            if (window.editor.value.length) {
                $('#saveReplay').removeAttr('disabled');
            } else {
                $('#saveReplay').attr('disabled', 'disabled');
            }
        });
    }
}

app.filePondUploader = function () {
    if ($('.filePondUploader').length > 0) {
        $('.filePondUploader').each(function () {
            const uploadUrl = $(this).data('upload-url');
            const deleteUrl = $(this).data('delete-url');
            let pond = FilePond.create(this);



            pond.setOptions({
                labelIdle: 'Upload Attachments',
                allowMultiple: true,
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        const filePondUploading = new Event('filepond-uploading', {
                            'bubbles': true,
                            'cancelable': true
                        });
                        window.dispatchEvent(filePondUploading);

                        // fieldName is the name of the input field
                        // file is the actual file object to send
                        const formData = new FormData();
                        formData.append(fieldName, file, file.name);
                        const request = new XMLHttpRequest();
                        request.open('POST', uploadUrl);
                        request.setRequestHeader('X-CSRF-TOKEN', $('meta[name="_token"]').attr('content'))


                        // Should call the progress method to update the progress to 100% before calling load
                        // Setting computable to false switches the loading indicator to infinite mode
                        request.upload.onprogress = (e) => {
                            progress(e.lengthComputable, e.loaded, e.total);
                        };

                        // Should call the load method when done and pass the returned server file id
                        // this server file id is then used later on when reverting or restoring a file
                        // so your server knows which file to return without exposing that info to the client
                        request.onload = function () {
                            if (request.status >= 200 && request.status < 300) {
                                // the load method accepts either a string (id) or an object
                                load(request.responseText);
                            } else {
                                // Can call the error method if something is wrong, should exit after
                                error('oh no');
                            }

                            const filePondUploaded = new Event('filepond-uploaded', {
                                'bubbles': true,
                                'cancelable': true
                            });
                            window.dispatchEvent(filePondUploaded);
                        };

                        request.send(formData);
                        // Should expose an abort method so the request can be cancelled
                        return {
                            abort: () => {
                                // This function is entered if the user has tapped the cancel button
                                request.abort();

                                // Let FilePond know the request has been cancelled
                                abort();
                            },
                        };
                    },
                    revert: (uniqueFileId, load, error) => {

                        uniqueFileId = JSON.parse(uniqueFileId);

                        const formData = new FormData();
                        formData.append('fileName', uniqueFileId.fileName);
                        formData.append('url', uniqueFileId.url);
                        app.appAjax('POST', formData, deleteUrl, 'file').then(function (response) {
                            // success
                            if (response.response == "success") {

                            }
                        }).fail(function (error) {
                            error('Somthing went wrong, Please try again!');
                        });


                        load();
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
                    }
                },
            });
        });
    }
}



app.showMessage = function (type, message, timeout = 3000) {
    new Noty({
        type: type,
        timeout: timeout,
        layout: 'topRight',
        theme: 'mint',
        closeWith: ['click', 'button'],
        text: message,
    }).show();
};

app.loadPreview = function (el) {
    var id = '#imagePreview';
    var input = el;
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(id).attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
        $('#imageUploadForm').submit(function (e) {
            e.preventDefault();
            var form = document.forms.namedItem("imageUploadForm");
            var data = new FormData(form);
            // var data = $(form).serialize();

            var route = $('#imageUploadForm').attr('action');
            console.log(data);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: route,
                data: data,
                dataType: 'json',
                method: 'POST',
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response.extra);
                }

            });

        });
        $('#imageUploadForm').trigger('submit');

    }

};

app.deleteApplication = function (el) {
    var action = $(el).data('action');
    var route = $(el).data('route');
    console.log(route);
    var i = 0;

    $('#confirm-' + action).modal('show');

    $('.btn-ok').click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;

            e.preventDefault();
            data = {};
            app.appAjax('DELETE', data, route).then(function (data) {
                console.log(data);
                if (data.response == "success") {
                    var id = data.extra.removedId;

                    if (action == 'restart') {
                        $('#confirm-' + action).modal('hide');
                        var redirectUrl = $(el).data('redirect-url');
                        window.location.href = redirectUrl;
                    }
                    if (action == 'delete') {
                        $('div[data-submission-id="submission-' + id + '"]').remove();
                        $('#confirm-' + action).modal('hide');

                    }
                }

            }).catch(function () {

                // Error

            });
        }

    });

};

app.radioSwitch = function () {
    $('.switch').bootstrapSwitch();
};
app.startModal = function (el) {
    var route = $(el).data('route');
    var title = $(el).data('title');
    var id = (title.split(' ').join('-')) + '-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, 'no-buttons');
};

app.dateRange = function () {

    if ($('.shawCalRanges').length > 0) {
        $('.shawCalRanges').daterangepicker({
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]

            },
            alwaysShowCalendars: true,
            opens: 'left',
            startDate: moment(),
            endDate: moment().subtract(29, 'days'),
            locale: {
                format: 'YYYY-MM-DD'
            }
        });

        $('.shawCalRanges').on('apply.daterangepicker', function (ev, picker) {

            var startDate = picker.startDate.format('YYYY-MM-DD 00:00:00');
            var endDate = picker.endDate.format('YYYY-MM-DD 23:59:59');

            var data = {
                action: 'home.getFilteredStudents',
                payload: {
                    filterBy: 'date',
                    startDate: startDate,
                    endDate: endDate
                }
            };
            app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
                if (data.response == 'success' && data.status == 200) {
                    $('.students-table').html(data.extra.html);
                }
            });
        });
    }

};

app.filterApplicants = function (el) {

    var value = $(el).val();

    if (value.length > 4) {
        var data = {
            action: 'home.getFilteredStudents',
            payload: {
                filterBy: 'text',
                string: value
            }
        };
        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            if (data.response == 'success' && data.status == 200) {
                $('.students-table').html(data.extra.html);
            }
        });
    }
};

app.clearFilters = function (el) {

    var data = {
        action: 'home.getFilteredStudents',
        payload: {
            filterBy: 'clear'
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success' && data.status == 200) {
            $('.students-table').html(data.extra.html);
            $('.filter-input').val("");
        }
    });

};

app.customSelect = function () {

    if ($(".lookup").length > 0) {
        $(".lookup").each(function () {

            var emptyPlaceholder = $(this).data('placeholder');

            var dataSource = $(this).data('source');



            $(this).select2({

                placeholder: emptyPlaceholder,

                allowClear: true,



                /*ajax: {

                    url: dataSource,

                    dataType: 'json',

                    cache: true,

                    processResults: function (data) {

                      return {

                        results: data

                      };

                    }

                  },

                minimumInputLength: 3,

                escapeMarkup: function(markup) { return markup; },

                templateResult : app.showLookupResults,

                templateSelection: app.selectedLookupResults*/



            });



        });
    }



};

app.createNewAgency = function (el) {
    var value = $(el).val();
    if (value === 'new') {
        $('#createAgency').removeClass('hidden');

        $('#createAgency input').each(function () {
            $(this).removeAttr('disabled');
        });
    } else {
        $('#createAgency').addClass('hidden');
        $('#createAgency input').each(function () {
            $(this).attr('disabled', 'disabled');
            $(this).val('');
        });
    }
};

app.showLookupResults = function (results) {
    if (results.loading) {
        return results.text;
    }
    var markup = '<option value="' + results.email + '">' + results.name + '</option>';
    return markup;
};

app.deleteAgent = function (el) {

    var agentId = $(el).data('id');
    $('a.delete-icon[data-agent-container="' + agentId + '"]').removeClass().addClass('fas fa-spin fa-spinner text-muted control-icon active-icon');

    var data = {
        action: 'agentAgency.deleteAgent',
        payload: {
            agentId: agentId,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {
            $('tr[data-row="' + agentId + '"]').fadeOut();
        }

    });




};


/**Process In Application Payment */
app.proccessPayment = async function (el, gateway, route) {
    event.preventDefault();
    var formData = {};

    $('.cc_info').each(function () {
        var name = $(this).data("name");
        formData[name] = app.getFieldValue($(this));
    });
    formData.ajax = true;
    var paymentText = el.innerHTML;
    // disable Button
    app.toggleButtonStatus(el, 'processing');
    $('.errors_wrapper').html("");

    await app.appAjax('POST', formData, route, 'data').then(function (data) {

        if (data.response == 'success' && data.status == 200) {
            // Hide Form and Show Success Message
            $(el).closest($('.payment-form')).html(data.extra.message);
            app.enableSaveButton();
        } else {
            // Show Error
            $('.errors_wrapper').html(data.extra.message);

            // Clear Form
            $('.cc_info').each(function () {
                var name = $(this).attr("name");
                if (name != 'payment') {
                    $(this).val('').change();
                }
            });

            // Enable Button
            app.toggleButtonStatus(el, 'enabled', paymentText);
        }

    }).fail(function (data) {
        console.log(data);
    });
};

app.processHelcimPayment = async function (el) {
    $(el).attr('disabled', 'disabled');
    javascript: helcimProcess()
        .then(response => {
            $("#helcimResults").addClass("hidden");
        })
        .catch(error => {
            $(el).removeAttr('disabled');
            $("#helcimResults").removeClass("hidden");
            $("#helcimResults").html(error);
        });
};

app.disableSaveButton = function () {

    $(".actions ul li:nth-child(2) a").addClass("disabled_btn");
    $(".actions ul li:nth-child(3) a").addClass("disabled_btn");

};

app.enableSaveButton = function () {

    $(".actions ul li:nth-child(2) a").removeClass("disabled_btn");
    $(".actions ul li:nth-child(3) a").removeClass("disabled_btn");
};

/* Toggle Buttons status */
app.toggleButtonStatus = function (el, status, text) {

    if (status == 'processing') {
        $(el).attr('disabled', 'disabled').html('Processing! Please wait...');
    }

    if (status == 'enabled') {
        $(el).removeAttr('disabled').html(text);
    }

};

app.resendActivationEmail = function (el) {

    var agentId = $(el).data('id');

    $('a.active-icon[data-agent-container="' + agentId + '"]').removeClass().addClass('fas fa-spin fa-spinner text-muted control-icon active-icon');

    var data = {
        action: 'agentAgency.resendActivationEmail',
        payload: {
            agentId: agentId,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {


        if (data.response == 'success' && data.status == 200) {
            swal({
                    title: "Sent Successfuly!",
                    text: "The activation email sent successfuly",
                    icon: "success",
                    buttons: true,
                    dangerMode: false,
                })
                .then((willDelete) => {

                    $('a.active-icon[data-agent-container="' + agentId + '"]').removeClass().addClass('fas fa-user-circle text-warning control-icon active-icon');


                });
        }

    });

};

app.toggleAdminPrivileges = function (el) {
    var agentId = $(el).data('id');


    var container = $('a.admin-icon[data-agent-container="' + agentId + '"]');
    var wasAdmin = container.data('is-admin');



    container.removeClass().addClass('fas fa-spin fa-spinner text-muted control-icon admin-icon');

    var data = {
        action: 'agentAgency.toggleIsAdmin',
        payload: {
            agentId: agentId,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {


        if (data.response == 'success' && data.status == 200) {
            if (wasAdmin == 1) {
                var color = ' text-muted'

                $('a.admin-icon[data-agent-container="' + agentId + '"]').removeClass('fas fa-spin fa-spinner text-muted control-icon').addClass('fas fa-star control-icon admin-icon' + color).removeAttr('data-is-admin');
            } else {
                console.log('not admin');

                var color = ' text-success';
                $('a.admin-icon[data-agent-container="' + agentId + '"]').removeClass('fas fa-spin fa-spinner text-muted control-icon').addClass('fas fa-star control-icon admin-icon' + color).attr('data-is-admin', 1).change();
            }
        }

    });
};

function selectedLookupResults(results) {
    return results.email;
}

app.dismissModal = function (el) {



    var modal = $(el).closest('.form_modal');



    modal.modal('hide');



    $('.form_modal').html();



    tinyMCE.remove();



};

app.deleteBooking = function (el) {

    var application = $(el).data('application');

    var booking = $(el).data('booking');

    var route = $(el).data('route');



    var i = 0;



    $('#confirm-delete').modal('show');



    $('.btn-ok').click(function (e) {

        // to prevent duplicate request caused by the modal

        if (i == 0) {
            i++;

            e.preventDefault();



            data = {};



            app.appAjax('DELETE', data, route).then(function (data) {



                if (data.response == "success") {
                    var id = data.extra.booking_id;

                    $('tr[data-booking-id="' + id + '"]').fadeOut(function () {



                        $('#confirm-delete').modal('hide');



                        return true;



                    });
                }





            }).catch(function () {

                // Error

            });
        }

    });

};

app.addStudent = function (route, data, title, el) {
    var i = 0;
    var id = 'create-student-form-' + (1 + Math.floor(Math.random() * 1000));
    var application = $(el).data('application');
    var booking = $(el).data('booking');



    if (typeof application != 'undefined') {
        route = route + '?application=' + application;
    }

    if (typeof booking != 'undefined') {
        route = route + '&booking=' + booking;
    }

    app.openModal(id, route, title, 'Book');


    $('.btn-ok').click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();
            var formData = {};

            $('.ajax-form-field').each(function () {
                var name = $(this).attr("name");
                formData[name] = app.getFieldValue($(this));
            });

            var errors = {};



            if (!formData.child && !formData.first_name && !formData.last_name) {

                errors.child = ['Please, Select a child or add new'];
                app.displayErrors(errors);
                i = 0;
                return false;

            }

            if (!formData.first_name && !formData.last_name && formData.child == 99999) {
                if (!formData.first_name) {
                    errors.first_name = ['First Name is required'];
                }
                if (!formData.last_name) {
                    errors.last_name = ['Last Name is required'];
                }
                if (errors.first_name || errors.last_name) {
                    app.displayErrors(errors);
                    i = 0;
                    return false;
                }
            }

            // Reset Child
            if (formData.child == 99999) {
                formData.child = null;
            }

            app.buttonClicked($(this));
            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('POST', formData, actionRoute).done(function (data) {

                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    // Get Wrapper
                    $('.students-table').prepend(data.extra.html);

                    if (data.extra.application_id != 0) {
                        var url = data.extra.application_url;

                        if (typeof data.extra.booking != 'undefined') {
                            url = url + '?booking=' + data.extra.booking;
                        }

                        app.redirect(url);
                    }
                } else {
                    app.displayErrors(data.extra.errors);

                    i = 0;
                }



                return false;



            });

            // Remove Form
            $('#' + id + ' .modal-body form').remove();
        }
    });
};

app.addNewChild = function (el) {



    var value = $(el).val(),

        wrapper = $(el).data('wrapper'),

        formWrapper = $('.' + wrapper);



    if (value == 99999) {
        formWrapper.show().addClass('d-flex');

        formWrapper.find($('input , select')).each(function () {

            $(this).removeAttr('disabled');

        });
    } else {
        formWrapper.hide().removeClass('d-flex');

        formWrapper.find($('input , select')).each(function () {

            $(this).attr('disabled', 'disabled');

        });
    }

};

app.getBookingDetails = function (route, booking) {
    var i = 0;
    var id = 'viw-booking-details-' + (1 + Math.floor(Math.random() * 1000));
    var title = 'Booking Details';
    app.openModal(id, route, title, 'no-buttons');
};

app.buttonClicked = function (el) {

    $('.preloader').fadeIn();

    //el.text('Proccessing...');

};

app.displayErrors = function (errors) {

    $.each(errors, function (name, message) {

        var errorMessage = '<span class="invalid-feedback" style="display:block;" role="alert"><strong>' + message[0] + '</strong></span>';

        $('[name="' + name + '"]').next('.invalid-feedback').remove();

        $(errorMessage).insertAfter($('[name="' + name + '"]'));



    });

};

// Open Modal
app.openModal = function (id, route, title, btn) {

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
};

app.getFieldValue = function (el) {



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

        if (jQuery.inArray(type, ['text', 'email', 'hidden', 'phone', 'date']) >= 0) {
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



};

// Global Ajax Request
app.appAjax = function (method, data, route, dataType) {
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
};

app.tinyMCE = function (className) {

    if (className) {
        var selector = 'textarea' + '.' + className;
    } else {
        var selector = 'textarea';
    }
    if ($(".mymce").length > 0) {
        tinymce.init({
            mode: "textareas",
            selector: selector,
            theme: "modern",
            height: 200,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
        });
    }
};

app.redirect = function (link) {
    window.location.href = link;
    return false;
};

app.requestUnlock = function (el, submission_id) {
    var route = $(el).data('route');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        url: route,
        type: "get",
        dataType: 'json',
        data: {
            lock: 0,
            'submission_id': submission_id
        },
        statusCode: {
            404: function () {
                console.log('page not found')
            }
        }
    }).done(function (data) {
        el.innerHTML = 'Unlock Requested';
        app.showMessage('success', 'Request sent <i class="icon-lock-open"></i>', false);
    });
};

app.renderReviewPage = function (el) {
    var url = $(el).data('route');
    var data = {}

    app.appAjax('POST', data, url).then(function (response) {
        if (response.status == 200) {
            $('.review-page').html(response.extra.html);
            //app.editable();
        }
    });
};

app.editable = function () {
    if ($('.editable').length) {

        $('.editable').each(function () {

            if ($(this).attr('data-validation')) {
                var validation = $(this).attr('data-validation');
                validation = JSON.parse(validation);

            } else {
                var validation = null;
            }

            $(this).editable({
                placement: 'right',
                toggle: 'dblclick',
                emptytext: 'Empty',
                pk: 1,
                params: function (params) {
                    $('[name="' + params.name + '"]').val(params.value).change();
                    return params;
                },

                validate: function (value) {

                    if (validation) {
                        if (validation.required && $.trim(value) == '') {
                            return validation.required;
                        }
                    }
                },
            });
        });

    }
};

// Theme Functions
$(function () {
    "use strict";
    if ($('div.review-page').length) {
        app.renderReviewPage($('div.review-page')[0]);
    }

    $('#confirm-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var title = button.data('title') // Extract info from data-* attributes

        var modal = $(this)

    });
    //disabling form submissions if there are invalid fields
    window.addEventListener('load', function () {

        var forms = document.getElementsByClassName('needs-validation');
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

    $(".left-sidebar").hover(
        function () {
            $(".navbar-header").addClass("expand-logo");
        },
        function () {
            $(".navbar-header").removeClass("expand-logo");
        }
    );
    $(".nav-toggler").on('click', function () {
        $("#main-wrapper").toggleClass("show-sidebar");
        $(".nav-toggler i").toggleClass("ti-menu");
    });

    $(".nav-lock").on('click', function () {
        $("body").toggleClass("lock-nav");
        $(".nav-lock i").toggleClass("mdi-toggle-switch-off");
        $("body, .page-wrapper").trigger("resize");
    });

    $(".search-box a, .search-box .app-search .srh-btn").on('click', function () {
        $(".app-search").toggle(200);
        $(".app-search input").focus();
    });

    $(function () {
        $(".add_field_toggle").on('click', function (e) {
            e.preventDefault();
            $(".customizer").toggleClass('show-service-panel');
        });
    });


    $('.floating-labels .form-control').on('focus blur', function (e) {
        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
    }).trigger('blur');

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    $(function () {
        $('[data-toggle="popover"]').popover({
            html: true,
            animation: true,
            focus: true
        })
    })

    $('.message-center, .customizer-body, .scrollable').perfectScrollbar({
        wheelPropagation: !0
    });

    $("body, .page-wrapper").trigger("resize");
    $(".page-wrapper").delay(20).show();

    $(".list-task li label").click(function () {
        $(this).toggleClass("task-done");
    });

    $('a[data-action="collapse"]').on('click', function (e) {
        e.preventDefault();
        $(this).closest('.card').find('[data-action="collapse"] i').toggleClass('ti-minus ti-plus');
        $(this).closest('.card').children('.card-body').collapse('toggle');
    });

    $('a[data-action="expand"]').on('click', function (e) {
        e.preventDefault();
        $(this).closest('.card').find('[data-action="expand"] i').toggleClass('mdi-arrow-expand mdi-arrow-compress');
        $(this).closest('.card').toggleClass('card-fullscreen');
    });

    // Close Card
    $('a[data-action="close"]').on('click', function () {
        $(this).closest('.card').removeClass().slideUp('fast');
    });


    $(document).on('click', '.mega-dropdown', function (e) {
        e.stopPropagation()
    });

    $(".show-left-part").on('click', function () {
        $('.left-part').toggleClass('show-panel');
        $('.show-left-part').toggleClass('ti-menu');
    });
    var form = $(".validation-wizard").show();
    var add_button = $(".validation-wizard").data('add-button')
    $(".validation-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: add_button
        },
        onStepChanging: function (event, currentIndex, newIndex) {
            return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
        },
        onFinishing: function (event, currentIndex) {
            return form.validate().settings.ignore = ":disabled", form.valid()
        },
        onFinished: function (event, currentIndex) {
            form.submit();
        }
    }), $(".validation-wizard").validate({
        ignore: "input[type=hidden]",
        errorClass: "text-danger",
        successClass: "text-success",
        highlight: function (element, errorClass) {
            $(element).removeClass(errorClass)
        },
        unhighlight: function (element, errorClass) {
            $(element).removeClass(errorClass)
        },
        errorPlacement: function (error, element) {
            error.insertAfter(element)
        },
        rules: {
            email: {
                email: !0

            }
        }
    });
    app.removeRepeatedElement = function (removeClass) {
        $('.' + removeClass).fadeOut('meduim');
    }

    // to be Deprecated
    app.room = 1;
    app.repeat_fields = function () {
        app.room++;
        var objTo = $('.repeated_fields');
        var clonedObj = objTo.clone();
        var clonedHTML = clonedObj.html();
        var removeClass = 'removeclass_' + app.room;
        clonedObj.removeClass('repeated_fields').addClass(removeClass);
        // Remove Add Button
        clonedObj.find('.action_button').remove();
        var removeButton = ` < div class = "form-group" > < button class = "btn waves-effect waves-light btn-outline-danger btn-lg" type = "button" onclick = "app.removeRepeatedElement( '${removeClass}' );" > < i class = "fa fa-minus" > < / i > <  / button > <  / div > `;
        clonedObj.find('.action_wrapper').append(removeButton);
        $('.repeated_fields_wrapper').append(clonedObj);
        //Reset All fields Value
        $('.repeated_fields  input').each(function () {
            $(this).val("");
        })

    }

    app.room = 1;
    app.fieldRepeater = function (el) {
        var parent = $(el).data('repeat');
        app.room++;
        var objTo = $('#' + parent);
        var clonedObj = objTo.clone();

        clonedObj.removeClass('repeated_fields').addClass('removeclass_' + app.room);

        clonedObj.find('.action_button').remove();
        var removeButton = '<div class="form-group m-t-25"><button class="btn btn-danger" type="button" onclick="remove_education_fields(' + app.room + ');"> <i class="fa fa-minus"></i> </button> </div>';
        clonedObj.find('.action_wrapper').append(removeButton);

        var clonedHTML = clonedObj.html();

        // Container
        var container = $('#' + parent + "_wrapper");
        container.append(clonedHTML);
    }

    app.textEditor();
    app.customSelect();
    app.tinyMCE();
    app.radioSwitch();
    app.dateRange();
    app.filePondUploader();

});

app.initPlugins = function () {
    app.textEditor();
    app.customSelect();
    app.tinyMCE();
    app.radioSwitch();
    app.dateRange();
    app.filePondUploader();
}

$(function () {
    "use strict";


});
