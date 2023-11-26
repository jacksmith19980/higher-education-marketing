var app = {};



// Ajax Route
app.ajaxRoute = window.agentsAjaxRoute;

app.uploaderUrl = window.uploaderUrl

app.ajaxResponse = {};

app.order = 0;



app.radioSwitch = function () {
    $('.switch').bootstrapSwitch();
}

app.stripe = function () {

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
                amount: amount
            });
            e.preventDefault();
            $('#paymentContainer').html("<div class='card-body alert alert-warning'>Processing......</div>");
        });
        // Close Checkout on page navigation:
        window.addEventListener('popstate', function () {
            handler.close();
        });
    }
}



app.startModal =  function (el) {
    var route = $(el).data('route');
    var title = $(el).data('title');
    var id = ( title.split(' ').join('-') ) + '-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, 'no-buttons');
}


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
                    filterBy : 'date',
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

}


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
}

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

}


app.customSelect = function () {

    if ($(".lookup").length > 0) {
        $(".lookup").each(function () {

            var emptyPlaceholder = $(this).data('placeholder');

            var dataSource = $(this).data('source');



            $(this).select2({

                placeholder: emptyPlaceholder ,

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



}


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
}

app.showLookupResults = function (results) {
    if (results.loading) {
        return results.text;
    }
    var markup = '<option value="' + results.email + '">' + results.name + '</option>';
    return markup;
}


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




}

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

}

app.toggleAdminPrivileges = function (el ) {
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

app.rolPrivileges = function (el) {
    var agentId = $(el).data('id');
    var rol = $(el).val();

    var data = {
        action: 'agentAgency.rolPrivileges',
        payload: {
            agentId: agentId,
            rol: rol,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response === 'success' && data.status === 200) {
            app.showMessage('success', 'The agent is: ' + rol);
        } else {
            app.showMessage('error', 'Error detected');
        }

    });
};


function selectedLookupResults(results)
{
    return results.email;
}





app.dismissModal = function (el) {



    var modal = $(el).closest('.form_modal');



    modal.modal('hide');



    $('.form_modal').html();



    tinyMCE.remove();



}





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

}





app.addStudent = function (route , data , title , el) {



    var i = 0;

    var id = 'create-student-form-' + ( 1 + Math.floor(Math.random() * 1000) );

    var application = $(el).data('application');

    var booking = $(el).data('booking');

    

    if (typeof application != 'undefined') {
        route = route + '?application=' + application;
    }

    if (typeof booking != 'undefined') {
        route = route + '&booking=' + booking;
    }

    app.openModal(id , route , title , 'Book');
    

        $('.btn-ok').click(function (e) {
            // to prevent duplicate request caused by the modal
            if (i == 0) {
                i++ ;
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



}







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

}

app.getBookingDetails = function (route, booking) {
    var i = 0;
    var id = 'viw-booking-details-' + (1 + Math.floor(Math.random() * 1000));
    var title = 'Booking Details';
    app.openModal(id, route, title, 'no-buttons');
}



app.buttonClicked = function (el) {

    $('.preloader').fadeIn();

    //el.text('Proccessing...');

}



app.displayErrors = function (errors) {

    $.each(errors, function ( name, message ) {

        var errorMessage = '<span class="invalid-feedback" style="display:block;" role="alert"><strong>' + message[0] + '</strong></span>';

        $('[name="' + name + '"]').next('.invalid-feedback').remove();

        $(errorMessage).insertAfter($('[name="' + name + '"]'));

      

    });

}



// Open Modal

app.openModal = function (id , route , title , btn ) {

    if (typeof btn == 'undefined') {
        btn = 'Save';
    }
    $('#form-modal .modal-content .btn-ok').attr('id' , id);
    $('#form-modal .modal-title').html(title);
    if (btn != 'no-buttons') {
        $('#form-modal .btn-ok').show().html(btn);
    } else {
        $('#form-modal .btn-ok').hide();
    }
    $('#form-modal .modal-body').load(route ,function () {
        $('#form-modal').modal('show');
        app.customSelect();
        app.tinyMCE();
        app.radioSwitch();
    });
}





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

        if ( jQuery.inArray(type , ['text' , 'email' , 'hidden' , 'phone' , 'date']) >= 0 ) {
               return el.val();
        }

        //checkbox, radio

        if ( $.inArray(type , ['checkbox' , 'radio']) >= 0 ) {
            if (el.is(":checked")) {
                return el.val();
            }
        }
    }



    return " ";



}





// Global Ajax Request

app.appAjax = function (method , data , route , dataType ) {
    // Default DataType is Data
    dataType = dataType || 'data';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    var args = {
        url           : route,
        method        : method,
        data          : data,
        dataType      : "json",
    };
    // For Ajax File Upload
    if (dataType == 'file') {
        args.processData = false;
        args.contentType = false;
    }
    return $.ajax(args);
}







app.tinyMCE = function (className) {



    if (className) {
        var selector = 'textarea' + '.' + className;
    } else {
        var selector = 'textarea';
    }





    if ($(".mymce").length > 0) {
            tinymce.init({



                mode : "textareas",



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



}







app.redirect = function (link) {



    window.location.href = link;



    return false;



}







// Theme Functions



$(function () {



    "use strict";







    $('.accordion-head').click(function () {



        alert('accordion-head clicked');



        /* $('accordion-active').slideUp(function(){



            $(this).removeClass('accordion-active')



        });







        $(this).next('accordion-content').slideToggle();*/



    });











    $('#confirm-delete').on('show.bs.modal', function (event) {



        var button = $(event.relatedTarget) // Button that triggered the modal



        var title = button.data('title') // Extract info from data-* attributes







      // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).



      // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.



        var modal = $(this)



      



      /*modal.find('.modal-title').text('New message to ' + recipient)



      modal.find('.modal-body input').val(recipient)*/



    });











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



    // ==============================================================



    // Theme options



    // ==============================================================



    // ==============================================================



    // sidebar-hover



    // ==============================================================







    $(".left-sidebar").hover(
        function () {



            $(".navbar-header").addClass("expand-logo");



        },
        function () {



            $(".navbar-header").removeClass("expand-logo");



        }
    );



    // this is for close icon when navigation open in mobile view



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







    // ==============================================================



    // Right sidebar options



    // ==============================================================



    $(function () {



        $(".add_field_toggle").on('click', function (e) {



            e.preventDefault();



            $(".customizer").toggleClass('show-service-panel');







        });



    });



    // ==============================================================



    // This is for the floating labels



    // ==============================================================



    $('.floating-labels .form-control').on('focus blur', function (e) {



        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));



    }).trigger('blur');







    // ==============================================================



    //tooltip



    // ==============================================================



    $(function () {



        $('[data-toggle="tooltip"]').tooltip()



    })



    // ==============================================================



    //Popover



    // ==============================================================



    $(function () {



        $('[data-toggle="popover"]').popover({
            html: true,
            animation : true,
            focus : true
        })



    })







    // ==============================================================



    // Perfact scrollbar



    // ==============================================================



    $('.message-center, .customizer-body, .scrollable').perfectScrollbar({



        wheelPropagation: !0



    });







    // ==============================================================



    // Resize all elements



    // ==============================================================



    $("body, .page-wrapper").trigger("resize");



    $(".page-wrapper").delay(20).show();



    // ==============================================================



    // To do list



    // ==============================================================



    $(".list-task li label").click(function () {



        $(this).toggleClass("task-done");



    });



    // ==============================================================



    // Collapsable cards



    // ==============================================================



    $('a[data-action="collapse"]').on('click', function (e) {



        e.preventDefault();



        $(this).closest('.card').find('[data-action="collapse"] i').toggleClass('ti-minus ti-plus');



        $(this).closest('.card').children('.card-body').collapse('toggle');



    });



    // Toggle fullscreen



    $('a[data-action="expand"]').on('click', function (e) {



        e.preventDefault();



        $(this).closest('.card').find('[data-action="expand"] i').toggleClass('mdi-arrow-expand mdi-arrow-compress');



        $(this).closest('.card').toggleClass('card-fullscreen');



    });



    // Close Card



    $('a[data-action="close"]').on('click', function () {



        $(this).closest('.card').removeClass().slideUp('fast');



    });



    // ==============================================================



    // LThis is for mega menu



    // ==============================================================



    $(document).on('click', '.mega-dropdown', function (e) {



        e.stopPropagation()



    });



    // ==============================================================



    // Last month earning



    // ==============================================================



    /* var sparklineLogin = function() {



        $('.lastmonth').sparkline([6, 10, 9, 11, 9, 10, 12], {



            type: 'bar',



            height: '35',



            barWidth: '4',



            width: '100%',



            resize: true,



            barSpacing: '8',



            barColor: '#2961ff'



        });







    };



    var sparkResize;







    $(window).resize(function(e) {



        clearTimeout(sparkResize);



        sparkResize = setTimeout(sparklineLogin, 500);



    });



    sparklineLogin(); */



    



    // ==============================================================



    // This is for the innerleft sidebar



    // ==============================================================



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

        var removeButton = ` < div class = "form-group" > < button class = "btn waves-effect waves-light btn-outline-danger float-right btn-lg ml-2" type = "button" onclick = "app.removeRepeatedElement( '${removeClass}' );" > < i class = "fa fa-minus" > < / i > <  / button > <  / div > `;

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

        console.log(clonedObj);
    

        clonedObj.removeClass('repeated_fields').addClass('removeclass_' + app.room);

        clonedObj.find('.action_button').remove();
        var removeButton = '<div class="form-group m-t-25"><button class="btn btn-danger" type="button" onclick="remove_education_fields(' + app.room + ');"> <i class="fa fa-minus"></i> </button> </div>';
        clonedObj.find('.action_wrapper').append(removeButton);

        var clonedHTML = clonedObj.html();

        // Container
        var container = $('#' + parent + "_wrapper");
        container.append(clonedHTML);
    }







    app.customSelect();
    app.tinyMCE();
    app.radioSwitch();
    app.dateRange();

});


$(function () {
    "use strict";
    app.stripe();
    app.customSelect();
}); 