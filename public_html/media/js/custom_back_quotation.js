var app = {};

// Ajax Route
app.ajaxRoute = window.ajaxRoute;
app.quotationAjaxRoute = window.quotationAjaxRoute;
app.ajaxResponse = {};
app.order = 0;
app.customSelect = function () {
    if ($(".select2").length > 0) {
        $(".select2").select2();
    }
}
app.radioSwitch = function () {
    $('.switch').bootstrapSwitch();
}

app.deselectAll = function(id) {

    $('.test').each(function () {

        $(this).removeAttr('checked');

    })

    
}

app.optionPreSelected = function(name){

    $('.quotation-auto-process').each(function () {

        app.optionChanged(this);

    });
}

app.optionChanged = function (el) {
    
    var current = $(el).data('current');
    var next = $(el).data('next');
    var quotationID = $(el).data('quotation');

    var name = $(el).attr('name');
    var values = app.getValues(name);

    
    if (values.length > 0) {
        
        var data = {
            action: 'getData',
            payload: {
                by: current,
                get: next,
                values: values,
                quotation: quotationID
            }
        };

        app.appAjax('POST', data, app.quotationAjaxRoute).then(function (data) {
            
            if (data.response == 'success' && data.status == 200) {

                $('#' + next + '-wrapper').html(data.extra.html);

            }
        })

    }else{
        
        $('#' + next + '-wrapper').html("");
    
    }
    


}
app.showExtras = function () {
    $('.quotation-extras').show();
}

app.toggleSameDates = function(el , date , action){

    if (action == 'disable') {
        
        $('[value="' + date + '"]').attr('disabled', 'disabled');
        
    } else {
        
        $('[value="' + date + '"]').removeAttr('disabled');
        
    }
    $(el).removeAttr('disabled');
    

}

app.dateSelected = function (el) {
    
    var program = $(el).data('program');
    var quotationID = $(el).data('quotation');
    var name = $(el).attr('name');
    var date = $(el).val();


    
    
    if ($(el).is(':checked')) {
        
        app.toggleSameDates(el , date , 'disable');
        
        if (date == '2019-07-09:2019-07-14') {
            app.showExtras();
            return false;
        }

        var data = {
            action: 'getData',
            payload: {
                by: 'program',
                get: 'addons',
                values: {
                    program: program,
                    date: date,   
                },
                quotation: quotationID
            }
        };

       app.appAjax('POST', data, app.quotationAjaxRoute).then(function (data) {
        
           if (data.response == 'success' && data.status == 200) {

                $(el).closest('div#date-wrapper').next('.addons-wrapper').append(data.extra.html);
               app.showExtras();
           }
       })
    } else {
        
        $('[data-date="' + date + '"][data-program="'+program+'"]').remove();
        app.toggleSameDates(el, date, 'enable');

    }


    
}


app.getValues = function (name) {
    var values = [];
    $('[name="' + name + '"]').each(function () {
        var type = $(this).attr('type');

        if (type == 'checkbox' || type == 'radio') {

            if ($(this).is(':checked')) {

                values.push($(this).val());
            }

        } else {

            values.push($(this).val());

        }


    });

    return values;
}

app.getPrice = function (form) {

    var formData = new FormData(form);
    /* formData.append("action", "getPrice"); */
    formData.append("action", "calculatePrice");

    app.appAjax('POST', formData, app.quotationAjaxRoute, 'file').then(function (data) {

        if (data.response == 'success' && data.status == 200) {

            $('div#CoursePrice').html(data.extra.html);
            
            $('html,body').animate({
                //scrollTop: (document.getElementById('CoursePrice').scrollHeight) - 250
                scrollTop: document.body.scrollHeight
                
            }, "meduim");

        }
    });


}

app.sendQuotationViaEmail = function (el) {
    var quotationId = $(el).data('quotation-id');
    var invoiceId = $(el).data('invoice-id');

    var data = {
        action: 'getSendEmailForm',
        payload: {
            quotation: quotationId,
            invoice: invoiceId
        }
    };

    app.appAjax('POST', data, app.quotationAjaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {

            $('div#nextStepHolder').html(data.extra.html);

        }
    });
}
app.bookNow = function (el) {
    var quotationId = $(el).data('quotation-id');
    var invoiceId = $(el).data('invoice-id');
    var data = {
        action: 'getBookNowForm',
        payload: {
            quotation: quotationId,
            invoice: invoiceId
        }
    };

    app.appAjax('POST', data, app.quotationAjaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {
            $('.cta-container').fadeOut();
            $('div#nextStepHolder').html(data.extra.html);

        }
    });
}

app.loadLoginForm = function (el) {
    var quotation = $(el).data('quotation');
    var booking = $(el).data('booking');

    var data = {
        action: 'loadLoginForm',
        payload: {
            quotation: quotation,
            booking: booking
        }
    };

    app.appAjax('POST', data, app.quotationAjaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {
            $('#nextStepHolder').html(data.extra.html);
        }
    });
}

app.formWizard = function () {
    
    var form = $(".validation-wizard").show();
    var add_button = $(".validation-wizard").data('add-button');
    
    //app.dateTimePicker();
    app.customSelect();

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

            //swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");

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
}
app.dismissModal = function (el) {

    var modal = $(el).closest('.form_modal');

    modal.modal('hide');

    tinyMCE.remove();

}


/**
 * NOT USED
 */
app.calculateNumberOfDays = function (startDate, endDate , courseID ) {
    
    var start = moment(startDate);
    var end = moment(endDate);
    var numberOfDays = end.diff(start, 'days') + 1;

    // Get Price
    if (numberOfDays && numberOfDays > 0) {
        
        var data = {
            action: 'getCoursePrice',
            payload: {
                numberOfDays: numberOfDays,
                courseID: courseID,
                startDate: start.format("YYYY-MM-DD"),
                endDate: end.format("YYYY-MM-DD"),
            }
        };

        app.appAjax('POST', data, app.quotationAjaxRoute).then(function (data) {

            if (data.response == 'success' && data.status == 200) {

                $('.TotalPrice').html(data.extra.price);

           } 
        });
    } 
}

app.addAccommodationPrice = function (el) {

    var value = $(el).val();
    var price = value.split("_");
    var originalPrice = $('.TotalPrice').html();
    
    $('.TotalPrice').html(Number(originalPrice) + price);

}

app.buttonClicked = function (el) {

    el.text('Proccessing...');

}
app.displayErrors = function (errors) {


    $.each(errors, function (name, message) {
        var errorMessage = '<span class="invalid-feedback" style="display:block;" role="alert"><strong>' + message[0] + '</strong></span>';

        // ToDo 
        // Remove Old Messages 
        $(errorMessage).insertAfter($('[name="' + name + '"]'));

    });

}


// Open Modal
app.openModal = function (id, route, title, btn) {

    if (typeof btn == 'undefined') {
        btn = 'Save';
    }

    $('#form-modal .modal-content .btn-ok').attr('id', id);

    $('#form-modal .modal-title').html(title);

    $('#form-modal .btn-ok').html(btn);



    $('#form-modal .modal-body').load(route, function () {

        $('#form-modal').modal('show');

        //app.customSelect();

        //app.tinyMCE();

        app.radioSwitch();

    });

}


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

}





app.redirect = function (link) {

    window.location.href = link;

    return false;

}

$(function () {

    "use strict";
    app.customSelect();
    app.formWizard();
    app.optionPreSelected();

}); 