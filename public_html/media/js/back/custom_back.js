var app = {};

// Ajax Route
app.ajaxRoute = window.ajaxRoute;

app.uploaderUrl = window.uploaderUrl;

app.ajaxResponse = {};

app.order = 1;

app.toggleFullpermissions = function(el)
{

    var el = $(el);
    var permission = el.data('permission');

    if (el.is(':checked')) {
        $('.permission-field[data-permission="' + permission + '"]').prop('checked', true);
searchAvailableSlots
    } else {
        $('.permission-field[data-permission="' + permission + '"]').prop('checked', false);
    }
}

app.manageCustomList = function () {
    $('input[name="properties[isCustomized]"]').val('Yes');
}

app.showAddSchedule = function (el) {
    var route = $(el).data('attr');
    console.log(route);
    var flg = 0;
    $('.date_schedule').on("select2:open", function () {
        flg++;
        //if (flg == 1) {
        if ($('.addSch').length === 0) {
            let $this_html = `<hr style="margin:2px"><a href="javascript:void(0)"
                        onclick="javascript:$('.date_schedule').select2('close');">Try button</a>`;
            $(".select2-results").prepend(`<div onclick='app.addCalendarSchedule(this)' data-route=` + route + ` class='addSch btn btn-success select2-results__option text-center' style="width:100%"><i class="fa fa-plus"></i> Add Schedule</div>`);
        }
    });
};

app.duplicate = function (el, traget) {

    var action = $(el).data('action');
    var container = $(el).data('container');
    var payload = $(el).data('payload');
    var data = {
        action: action,
        payload: payload
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response === 'success' && data.status === 200) {
            $(container).prepend(data.extra.html);
        }
    });
};

app.disablePromoCode = function (route, data, removedElement) {
    var i = 0;
    $('#confirm-delete').modal('show');
    $('.btn-ok').click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i === 0) {
            i++;
            e.preventDefault();

            app.appAjax('DELETE', data, route).then(function (data) {
                // success
                if (data.response === "success") {
                    if (removedElement) {
                        circleStatus = $('[' + removedElement + '="' + data.extra.removedId + '"]');
                        circleStatus.removeClass("text-success");
                        circleStatus.addClass("text-danger");
                    }
                    //close Modal
                    $('#confirm-delete').modal('hide');
                    return true;
                }
            }).fail(function () {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
        }
    });
};

app.deleteSchedule = function (el, cssClass) {
    var data = $(el).attr('id');
    var route = $(el).attr('delete-route');

    var row = $(el).closest("." + cssClass);

    app.appAjax('DELETE', data, route).then(function (data) {
        // success
        if (data.response == "success") {
            $(el).closest("." + cssClass).children().hide();
            $(el).closest("." + cssClass).append('<div style="width:100%" class="alert alert-success" role="alert">Deleted successfully...</div>');
            window.setTimeout(function () {
                $(el).closest("." + cssClass).remove();
            }, 3000);
        }
    });
};

app.addCalendarSchedule = function (el) {
    var template = $(el).data('template');
    var title = $(el).data('title');
    var route = $(el).data('route');

    var i = 0;
    var id = 'add-schedule-' + (1 + Math.floor(Math.random() * 1000));

    app.openModal(id, route, title , {});
    $('.btn-ok#' + id).click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i == 0) {
            e.preventDefault();

            // var count = $('#form-modal .modal-body form .countSch').val();
            $('#form-modal .modal-body form .countSch').remove();

            var formData = $('#form-modal .modal-body form').serialize();
            var actionRoute = $('#form-modal .modal-body form').attr('action');


            app.appAjax('POST', formData, actionRoute).then(function (data) {

                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    var sch_label = (data.extra.res.schedule_label);
                    var sch_sTime = (data.extra.res.schedule_start_time);
                    var sch_eTime = (data.extra.res.schedule_end_time);
                    var sch_Id = (data.extra.id);
                    $('.date_schedule').append('<option value="' + sch_Id + '">' + sch_label + ' (' + sch_sTime + ' - ' + sch_eTime + ')</option>');

                }

                $('#' + id + ' .modal-body form').remove();
                i++;
                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
            // Remove Form
        }
        //console.log(formData); */

    });
    //     console.log(el);
};


app.showAddSingleItem = function (el) {
    let uid = $(el).attr('id');
    let route = $(el).find('.new-single-item').data('route');
    let fieldName = $(el).find('.new-single-item').data('fieldName');

    $(el).find('.new-single-item').on("select2:open", function () {
        if ($('.addSch').length === 0) {
            $(".select2-results").prepend(`<div onclick='app.addSingleItem(this)' data-uid="${uid}" data-route="` + route + '"' + (fieldName ? 'data-field-name="' + fieldName + '"' : '') + ` class='addSch btn select2-results__option text-center' style="width:100%"><i class="fa fa-plus"></i> Add New</div>`);
        }
    });
};

app.addSingleItem = function (el) {

    let select = $(el).data('uid');

    let route = $(el).data('route');
    let fieldName = $(el).data('field-name') || 'name';
    let value = $(el).closest('.select2-dropdown').find('.select2-search__field').val();

    app.appAjax('POST', {[fieldName]: value}, route).then(function (data) {

        if (data.response == "success") {
            var name = (data.extra.name);
            var value = (data.extra.value);
            $('.new-single-item').append('<option value="' + value + '">' + name + '</option>');
            $('#' + select + ' select').val(value).trigger('change')
        }

    }).fail(function (error) {
        var errors = $.parseJSON(error.responseText).errors
        app.displayErrors(errors, '');
    });

};

app.addCourseDate = function (el) {
    var template = $(el).data('template');
    var route = $(el).data('route');
    var title = $(el).data('title');

    var i = 0;
    var id = 'add-course-date-' + (1 + Math.floor(Math.random() * 1000));
    console.log(route);
    app.openModal(id, route, title , {});
    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal
        if (i == 0) {
            e.preventDefault();
            var formData = {};

            $('.ajax-form-field').each(function () {
                var name = $(this).attr("name");
                var value = app.getFieldValue($(this));
                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }
                formData[name] = value;
            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('POST', formData, actionRoute).then(function (data) {
                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    // Get Wrapper
                    $('div.dates-blocks').prepend(data.extra.html);
                }

                $('#' + id + ' .modal-body form').remove();
                i++;
                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
            // Remove Form
        }

    });

};

app.addCourseAddon = function (el) {
    var template = $(el).data('template');
    var route = $(el).data('route');
    var title = $(el).data('title');


    var i = 0;
    var id = 'add-course-date-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {});
    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal
        if (i == 0) {
            e.preventDefault();
            var formData = {};

            $('.ajax-form-field').each(function () {
                var name = $(this).attr("name");
                var value = app.getFieldValue($(this));
                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }
                formData[name] = value;
            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('POST', formData, actionRoute).then(function (data) {
                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    // Get Wrapper
                    $('.addons-blocks').prepend(data.extra.html);
                }

                $('#' + id + ' .modal-body form').remove();
                i++;
                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
            // Remove Form
        }

    });

};

app.editCourseProp = function (el) {
    var route = $(el).data('route');
    var title = $(el).data('title');

    var i = 0;
    var id = 'edit-course-date-addon' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {});
    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal
        if (i == 0) {
            e.preventDefault();
            var formData = {};

            $('.ajax-form-field').each(function () {
                var name = $(this).attr("name");
                var value = app.getFieldValue($(this));
                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }
                formData[name] = value;
            });

            app.appAjax('POST', formData, route).then(function (data) {

                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    $('[data-key="' + data.extra.key + '"]').replaceWith(data.extra.html);
                }
                $('#' + id + ' .modal-body form').remove();
                i++;
                location.reload();
                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
            // Remove Form
        }

    });

};

app.editDateStatus = function (el) {
    var course = $(el).data('course'),
        date = $(el).data('date'),
        status = $(el).data('status'),
        data = {
            action: "date.toggleStatus",
            payload: {
                course: course,
                date: date,
                status: status,
            }
        };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success' && data.status == 200) {
            $('[data-date-id="' + data.extra.key + '"]').html(data.extra.html);
            toastr.success(data.extra.message);
        } else {
            toastr.error(data.extra.message);
        }
    });
}

app.removeBlock = function (key) {
    $('[data-key="' + key + '"]').fadeOut().remove();
};

app.deleteCourseProp = function (el) {
    var route = $(el).data("delete-route");
    var i = 0;
    $('#confirm-delete').modal('show');
    $('.btn-ok').click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();
            data = {};
            app.appAjax('DELETE', data, route).then(function (data) {
                // success
                if (data.response == "success") {
                    if (data.extra.key) {
                        $('[data-key="' + data.extra.key + '"]').fadeOut();
                    }

                    $('#confirm-delete').modal('hide');
                    location.reload();
                    return true;
                }
            }).fail(function () {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
        }
    });

};

app.initApplicantTable = function () {
    $('#applicant_table').DataTable({
        processing: true,
        paging: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        responsive: true,
        'columnDefs': [{
            'targets': [-1],
            orderable: false,
        },
        {
            'targets': [0],
            orderable: false,
        },
        {
            responsivePriority: 1,
            targets: 1
        },
        {
            responsivePriority: 2,
            targets: -1
        }
        ],
        "order": [
            [1, "asc"]
        ],
    });
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
};

app.initIndexTable = function () {
    $('#index_table').dataTable({
        dom: 'Bfrtip',
        paging: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        responsive: true,
        'order': [2, 'desc'],
        'columnDefs': [{
            'targets': [-1],
            'orderable': false,
        },
        {
            responsivePriority: 1,
            targets: 0
        },
        {
            responsivePriority: 2,
            targets: -1
        }
        ],
    });
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');

};


app.inlineScrole = function () {

    //Inline Scroll Init

    $('.inline-scroll').perfectScrollbar();

};

app.radioSwitch = function () {

    $('.switch').bootstrapSwitch();

};

app.cssEditor = function () {

    /*  if( $('.css-editor').length > 0 ){
             $('.css-editor').each(function(){
                 var id = $(this).data('id');
                 CodeMirror.fromTextArea( document.getElementById(id) , {
                     autofocus: true,
                     lineNumbers: true
                 });

             })
     } */
};

app.colorPicker = function () {
    $('.colorpicker').each(function () {
        $(this).minicolors({
            control: $(this).attr('data-control') || 'hue',
            defaultValue: $(this).attr('data-defaultValue') || '',
            format: $(this).attr('data-format') || 'hex',
            keywords: $(this).attr('data-keywords') || '',
            inline: $(this).attr('data-inline') === 'true',
            letterCase: $(this).attr('data-letterCase') || 'lowercase',
            opacity: $(this).attr('data-opacity'),
            position: $(this).attr('data-position') || 'bottom left',
            swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
            change: function (value, opacity) {
                if (!value) {
                    return;
                }
                if (opacity) {
                    value += ', ' + opacity;
                }
                if (typeof console === 'object') {
                    console.log(value);
                }
            },
            theme: 'bootstrap'
        });
    });
};

app.changeDefaultCurrencyHint = function (el) {
    var currency = $(el).val();

    $('.with-currency').next('span.input-group-text').text(currency);

};

app.dateTimePicker = function () {

    var element = $('.datepicker-autoclose');

    var options = {
        autoclose: true,
        todayHighlight: true,
        assumeNearbyYear: true,
        format: 'yyyy-mm-dd',
        orientation: 'bottom',
    };
    if (element.hasClass('years-only')) {
        options.minViewMode = 2;
    }

    element.datepicker(options);

};

app.duallistbox = function () {
    $('.duallistbox').bootstrapDualListbox();
    $('.moveall i').removeClass().addClass('fas fa-angle-right');
    $('.removeall i').removeClass().addClass('fas fa-angle-left');
    $('.move i').removeClass().addClass('fas fa-angle-right');
    $('.remove i').removeClass().addClass('fas fa-angle-left');
};


app.enableOptions = function (el) {

    var optionsGroup = $(el).data('option');
    var group = $('.options_group_' + optionsGroup)

    if ($(el).is(':checked')) {
        group.show();
        group.removeAttr('disabled');
    } else {
        group.hide();
        group.attr('disabled', 'disabled');
    }
}

app.customSelect = function () {
    if ($(".select2").length > 0) {
        $(".select2").select2();
    }
}

app.dragElements = function () {

    dragula([document.getElementById("sortable")], {



        moves: function (el, container, handle) {

            return handle.classList.contains('handle');

        }

    });

}

app.dismissModal = function (el) {

    var modal = $(el).closest('.form_modal');

    modal.modal('hide');

    /* tinyMCE.remove(); */

}

app.getThemeCustomization = function (el) {

    var applicationTheme = $(el).val();

    // get Application Customization
    var data = {
        action: 'application.getApplicationThemeCustomization',
        payload: {
            theme: applicationTheme,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success' && data.status == 200) {
            $('div.loadCustomization').html(data.extra.html);
        }
    });
}

app.loadCoursePricing = function (el) {

    var coursePricesTemplate = $(el).val();

    var data = {

        action: 'course.getCoursePricingTemplate',

        payload: {

            template: coursePricesTemplate,

        }

    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {



        if (data.response == 'success' && data.status == 200) {
            $('div.loadCoursePricing').html(data.extra.html);
        }



    });

}

app.loadProgramPricing = function (el) {

    var programPricesTemplate = $(el).val();

    var data = {
        action: 'program.getProgramPricingTemplate',
        payload: {
            template: programPricesTemplate,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success' && data.status == 200) {
            $('div.loadCoursePricing').html(data.extra.html);
        }
    });
}

app.loadCourseDates = function (el) {
    var courseDatesTemplate = $(el).val();
    var data = {
        action: 'course.getCourseDatesTemplate',
        payload: {
            template: courseDatesTemplate,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success' && data.status == 200) {
            $('div.loadCourseDates').html(data.extra.html);
            app.dateTimePicker();
        }
    });
}

/* app.loadProgramDates = function (el) {
    var programDatesTemplate = $(el).val();
    var data = {
        action: 'program.getDatesTemplate',
        payload: {
            template: programDatesTemplate,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success' && data.status == 200) {
            $('div.loadProgramDates').html(data.extra.html);
            app.dateTimePicker();
            app.customSelect();
        }
    });
} */

app.addElements = function (el) {

    var action = $(el).data('action');
    var container = $(el).data('container');
    var order = app.order++;

    var data = {

        action: action,

        payload: {
            order: order
        }

    };


    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {

            $(container).append(data.extra.html);
            app.dateTimePicker();
            app.customSelect();
        }
    });
};

app.deleteElementsRow = function (el, cssClass) {
    $(el).closest("." + cssClass).remove();
};

app.addSlotElements = function (el, custom_order) {
    var action = $(el).data('action');
    var container = $(el).data('container');
    var selectDay = $(el).data('day');
    var count = $(el).data('count');

    var order = null;
    if (!custom_order) {
        if (!isNaN(count) && count > app.order) {
            app.order = count;
        }
        order = app.order++;
    } else {
        order = custom_order;
    }


    var data = {
        action: action,
        payload: {
            'day': selectDay,
            'order': order
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success' && data.status == 200) {



            $(container).append(data.extra.html);
            app.dateTimePicker();
            app.customSelect();
        }
    });
};

app.getProgramDates = function (el) {

    var dateType = $(el).val();

    // get Application Customization

    var data = {
        action: 'program.getProgramDatesSelection',
        payload: {
            dateType: dateType,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {
            $('div.loadDateType').html(data.extra.html);
        }
    });
};

app.getCustomfields = function (el) {
    var entity = $(el).val();
    var customfields_select = $(el).closest('.row').find('[name^="properties[sync_source]"]');

    var data = {
        action: 'customfield.getCustomfields',
        payload: {
            entity: entity,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {

            $.each(data.extra.customfields, function (i, item) {
                customfields_select.empty().append(new Option(item, i));
            });
        }
    });
};

app.showMessage = function (type, message) {
    new Noty({
        type: type,
        timeout: 3000,
        layout: 'topRight',
        theme: 'mint',
        closeWith: ['click', 'button'],
        text: message,
    }).show();
};

app.getCourseDates = function (el) {

    var dateType = $(el).val();

    // get Application Customization

    var data = {
        action: 'course.getCourseDatesSelection',
        payload: {
            dateType: dateType,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {
            $('div.loadDateType').html(data.extra.html);
        }
    });
};

app.getApplicationActionDetails = function (el) {

    var application_action = $(el).val();
    var wrapper = $('.applicationActionDetails');

    var data = {

        action: 'application_action.getApplicationActionDetails',

        payload: {

            application_action: application_action,

        }

    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {
            wrapper.append(data.extra.html);
        }

    });


}
app.interPhoneCode =  function () {
    if ($('.inter-calling-code-mode').length > 0) {
        $('.inter-calling-code-mode').each(function () {

            console.log("phone");
            console.log($(this));
            var disable = false;
            var number = $(this).val();
            var code = $(this).data('code');
            var disable = $(this).data('disable');
            var lang = $(this).data('lang');
            var countryCode = $(this).data('country-code');
            var countryCodeNumber = $(this).data('countrycode-number');

            if (code) {
                number = number.replace(code, '');
            }

            $(this).intercode({
                default: number,
                disabled: disable,
                orginalCountryCode: (typeof countryCodeNumber != 'undefined') ? countryCodeNumber : false,
                country: (typeof countryCode != 'undefined') ? countryCode : 'CAN',
                lang: lang,
                code: code,
                apiToken: '0947abce3254c6',
                selectClass: 'countryCodeselect left select2 form-control-lg form-control custom-select',
                intercodeFile: '/media/extra-libs/inter-phone/countrycodes.json'
            });

        });
    }
}
app.applicationEdit = function () {

    var sections = $("#draggable-area")[0];

    dragula([sections], {

        moves: function (el, container, handle) {

            return handle.classList.contains('handle');

        }

    }).on("drag", function (e) { }).on("drop", function (e) {

        // Update Section Order

        var sectionsOrder = [];

        var applicationSlug = "";

        $('.application_section').each(function () {

            var sectionId = $(this).data("sectionid");

            sectionsOrder.push(sectionId);

            applicationSlug = $(this).data("application-slug");



        });

        app.updateSectionsOrder(sectionsOrder, applicationSlug);



    }).on("over", function (e, t) {





    }).on("out", function (e, t) {



        //t.className = t.className.replace("card-over", "")

    });



    // Fileds Drag

    $('.fields-wrapper').each(function () {

        dragula([$(this)[0]], {

        }).on("drag", function (e) {

        }).on("drop", function (e) {

            var parentSection = $(e).closest('.application_section');

            // Update Field Order
            var fieldsOrder = [];
            var sectionId = "";

            parentSection.find('.field-row').each(function () {



                var fieldId = $(this).data("fieldid");

                fieldsOrder.push(fieldId);

                sectionsId = $(this).data('section');



            });



            app.updateFieldsOrder(fieldsOrder, sectionsId);



        }).on("over", function (e, t) {

        }).on("out", function (e, t) {

        });


    });
    // quotation Drag
    var quotationDragElements = $('#quotation-draggable-area')[0];

    dragula([quotationDragElements], {

        moves: function (el, container, handle) {
            return handle.classList.contains('handle');
        }

    }).on('drop', function (e) {
        //    / alert(e);
    });


}

app.formBuilder = function () {



}

app.updateFieldsOrder = function (order, section) {
    var data = {

        action: 'section.fieldsOrder',

        payload: {

            order: order,

            section: section

        }

    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {



        // Fire Notification

        if (data.response == 'success' && data.status == 200) {
            // @Todo Fire Notification
        }



    });
};

app.updateSectionsOrder = function (order, application) {



    var data = {

        action: 'application.sectionsOrder',

        payload: {

            order: order,

            application: application

        }

    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {



        // Fire Notification

        if (data.response == 'success' && data.status == 200) {
            // @Todo Fire Notification
        }



    });



}

// Create New Section
app.createSection = function (route, title) {



    var i = 0;
    var id = 'create-section-form-' + (1 + Math.floor(Math.random() * 1000));



    app.openModal(id, route, title, {} );



    $('.btn-ok').click(function (e) {
        var form = $('#ajax-form');
        formData = new FormData(form[0]);

        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();



            var actionRoute = $('#form-modal .modal-body form').attr('action');
            app.appAjax('POST', formData, actionRoute, 'file').then(function (data) {
                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    $('.sections-container').append(data.extra.html);
                    return true;
                }
            });
        }
    });
};

app.editSection = function (route, data, title, el) {

    var i = 0;

    var id = 'edit-section-form-' + (1 + Math.floor(Math.random() * 1000));

    app.openModal(id, route, title, {} );



    $('.btn-ok').click(function (e) {



        var form = $('#ajax-form');



        var formData = new FormData(form[0]);



        // to prevent duplicate request caused by the modal

        if (i == 0) {
            i++;

            e.preventDefault();



            var actionRoute = $('#form-modal .modal-body form').attr('action');



            app.appAjax('POST', formData, actionRoute, 'file').then(function (data) {



                // success

                if (data.response == "success") {
                    $('[data-sectionid=' + data.extra.sectionId + '] .card-header h4').text(data.extra.title);

                    $('#form-modal').modal('hide');

                    return true;
                }



            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
        }

    });

}

/* Create A New Field */
app.createField = function (route, title) {

    var i = 0;
    var id = 'create-field-form-' + (1 + Math.floor(Math.random() * 1000));

    app.openModal(id, route, title, {} );

    $('.btn-ok#' + id).click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i == 0) {
            e.preventDefault();
            var formData = {};

            $('.ajax-form-field').each(function () {
                var name = $(this).attr("name");
                var value = app.getFieldValue($(this));
                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }
                formData[name] = value;
            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('POST', formData, actionRoute).then(function (data) {
                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    // Get Wrapper
                    $('div[data-parent-section="' + data.extra.section_id + '"]').append(data.extra.html);
                }
                $('#' + id + ' .modal-body form').remove();
                //tinymce.execCommand('mceRemoveControl', true, 'mymce');
                i++;
                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
            // Remove Form
        }
    });
}

/** Edit Application Field */
app.editIntegration = function (route, data, title, el) {

    var i = 0;

    var id = 'edit-integration-' + (1 + Math.floor(Math.random() * 1000));

    app.openModal(id, route, title, {} );

    $('.btn-ok#' + id).click(function (e) {



        // to prevent duplicate request caused by the modal

        if (i == 0) {
            i++;

            e.preventDefault();

            var formData = {};



            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                formData[name] = app.getFieldValue($(this));

            });
            var actionRoute = $('#form-modal .modal-body form').attr('action');
            app.appAjax('PUT', formData, actionRoute).done(function (data) {
                // success
                if (data.response == "success") {
                    // get New HTML
                    //var html = data.extra.html;
                    //var innerHtml = $(html).html();

                    // Replace integration with new HTML

                    $('[data-integration-id = ' + data.extra.integrationID + '] .integration-title').html(data.extra.title);



                    $('#form-modal').modal('hide');



                    // Remove Form

                    /* tinyMCE.remove();

                        tinymce.remove();

                    tinymce.execCommand('mceRemoveControl', true, 'mymce'); */

                    $('#' + id + ' .modal-body form').remove();
                }

                return false;



            }).fail(function (errors) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
        }



    });

}

app.editPaymentGateWay = function (route, data, title, el) {

    var i = 0;

    var id = 'edit-payment-gateway-' + (1 + Math.floor(Math.random() * 1000));

    app.openModal(id, route, title, {} );

    $('.btn-ok#' + id).click(function (e) {



        // to prevent duplicate request caused by the modal

        if (i == 0) {
            i++;

            e.preventDefault();

            var formData = {};



            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                formData[name] = app.getFieldValue($(this));

            });



            var actionRoute = $('#form-modal .modal-body form').attr('action');



            app.appAjax('PUT', formData, actionRoute).done(function (data) {

                // success
                if (data.response == "success") {
                    $('[data-payment-id = ' + data.extra.paymentID + '] .payment-title').html(data.extra.title);
                    $('#form-modal').modal('hide');

                    // Remove Form
                    /* tinyMCE.remove();
                        tinymce.remove();
                    tinymce.execCommand('mceRemoveControl', true, 'mymce'); */
                    $('#' + id + ' .modal-body form').remove();
                }
                return false;
            }).fail(function (errors) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');


            });
        }



    });

}

app.editaction = function (route, data, title, el) {

    var i = 0;

    var id = 'edit-action-' + (1 + Math.floor(Math.random() * 1000));

    app.openModal(id, route, title, {} );

    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();
            var formData = {};

            $('.ajax-form-field').each(function () {
                var name = $(this).attr("name");
                formData[name] = app.getFieldValue($(this));
            });


            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('PUT', formData, actionRoute).done(function (data) {
                // success
                if (data.response == "success") {
                    $('[data-payment-id = ' + data.extra.paymentID + '] .payment-title').html(data.extra.title);
                    $('#form-modal').modal('hide');

                    // Remove Form
                    /* tinyMCE.remove();
                        tinymce.remove();
                    tinymce.execCommand('mceRemoveControl', true, 'mymce'); */
                    $('#' + id + ' .modal-body form').remove();
                }
                return false;
            }).fail(function (errors) {

            });
        }
    });

}

app.editField = function (route, data, title, el) {
    var i = 0;
    var id = 'edit-field-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );
    $('.btn-ok#' + id).click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();
            var formData = {};
            $('.ajax-form-field').each(function () {
                var name = $(this).attr("name");
                formData[name] = app.getFieldValue($(this));
            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');
            console.log(actionRoute);
            console.log(formData);
            app.appAjax('PUT', formData, actionRoute).done(function (data) {
                console.log(data.fieldd);
                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    // Remove Form
                    /* tinyMCE.remove();
                        tinymce.remove();
                    tinymce.execCommand('mceRemoveControl', true, 'mymce'); */
                    $('#' + id + ' .modal-body form').remove();

                    //Check if field is pushed to a different section
                    var orgSection = $(el).closest('.list-group-item').data('section');
                    if (orgSection != data.extra.section_id) {
                        //Hide the Original Filed
                        $(el).closest('.list-group-item').hide();
                        // Get Wrapper
                        $('div[data-parent-section="' + data.extra.section_id + '"]').append(data.extra.html);
                        return false;
                    } else {
                        // Field is pushed to the same section
                        $(el).closest('.list-group-item').replaceWith(data.extra.html);
                    }
                }
                return false;
            });
        }
    });
}

app.editPaymentField = function (route, data, title, el) {

    var i = 0;

    var id = 'edit-field-form-' + (1 + Math.floor(Math.random() * 1000));

    app.openModal(id, route, title, {} );

    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal

        if (i == 0) {
            i++;

            e.preventDefault();

            var formData = {};



            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                formData[name] = app.getFieldValue($(this));

            });


            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('PUT', formData, actionRoute).done(function (data) {

                // success

                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    // Remove Form
                    /* tinyMCE.remove();

                tinymce.remove();

                    tinymce.execCommand('mceRemoveControl', true, 'mymce'); */

                    $('#' + id + ' .modal-body form').remove();

                    //Check if field is pushed to a different section

                    var orgSection = $(el).closest('.list-group-item').data('section');



                    if (orgSection != data.extra.section_id) {
                        //Hide the Original Filed
                        $(el).closest('.list-group-item').hide();

                        // Get Wrapper
                        $('div[data-parent-section="' + data.extra.section_id + '"]').append(data.extra.html);



                        return false;
                    } else {
                        // Field is pushed to the same section

                        $(el).closest('.list-group-item').replaceWith(data.extra.html);
                    }
                }



                return false;

            });
        }

    });

}

app.constructSlug = function (el) {

    var value = $(el).val().toLowerCase();

    if (!$('[name="slug"]').is(":disabled")) {
        $('[name="slug"]').val(app.stringReplace(value));
    }
}

app.constructFieldName = function (el) {

    var value = $(el).val().toLowerCase();

    if (!$('[name="name"]').is(":disabled")) {
        $('[name="name"]').val(app.stringReplace(value));
    }

}

app.stringReplace = function (string) {
    return string.replace(/\?/g, "_")
        .replace(/\-/g, "_")
        .replace(/\//g, "_")
        .replace(/\\/g, "_")
        .replace(/\)/g, "_")
        .replace(/\(/g, "_")
        .replace(/\'/g, "_")
        .replace(/\,/g, "_")
        .replace(/\./g, "_")
        .replace(/\{/g, "_")
        .replace(/\}/g, "_")
        .replace(/\[/g, "_")
        .replace(/\]/g, "_")
        .replace(/\@/g, "_")
        .replace(/\:/g, "_")
        .replace(/\#/g, "_")
        .replace(/\$/g, "_")
        .replace(/\%/g, "_")
        .replace(/\^/g, "_")
        .replace(/\&/g, "_")
        .replace(/\*/g, "_")
        .replace(/\ /g, "_");
}

app.resetValidation = function () {



    $('.ajax-form-field').keyup(function () {

        $(this).removeClass('in-invalid');

    });



}

/**
 * Load Fields to customize integration field names
 */
app.loadApplicationFields = function (el) {
    if ($(el).is(':checked')) {
        app.loadApplicationFieldsAction($(el));
    } else {
        $('#applicationFieldsWrapper').html('');
    }
}

app.loadMoreApplicationFields = function (el) {

    app.spin($(el));
    app.loadApplicationFieldsAction($('[name="custom_field_names"]'));
}

app.removeIntegrationFields = function (el, parent_name) {
    // $(this).data("id")
    var parent_div = $(el).closest('.' + parent_name);
    var cutom_field_name = parent_div.find('[name^=cutom_field_name]').val();
    var cutom_field_value = parent_div.find('[name^=cutom_field_name]').data("value");
    var custom_mautic_field_alias = parent_div.find('[name^=custom_mautic_field_alias]').val();
    var custom_mautic_field_alias_value = parent_div.find('[name^=custom_mautic_field_alias]').data("value");
    var fieldsPairEl = $('[name="mautic_field_pairs"]');
    var fieldsPair = fieldsPairEl.val();

    fieldsPair = JSON.parse(fieldsPair);

    try {
        fieldsPair = JSON.parse(fieldsPair);
    } catch (err) {
        console.log('no necesario');
    }

    fieldsPair = fieldsPair.filter(function (jsonObject) {
        return jsonObject.field != cutom_field_value && jsonObject.mautic_field != custom_mautic_field_alias_value;
    });

    parent_div.remove();
    fieldsPairEl.val(JSON.stringify(fieldsPair));
}

app.loadApplicationFieldsAction = function (el) {

    var applicationid = el.data('application-id');
    var formData = {};

    $('.integration_data_field').each(function () {
        formData[$(this).attr("name")] = $(this).val();
    });


    var data = {
        action: 'application.getFieldsList',
        payload: {
            applicationid: applicationid,
            formData: formData
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        $('#applicationFieldsWrapper').prepend(data.extra.html);

        // Stop Spinning
        $('.spinner').attr('disabled', 'disabled');
        app.stopSpinning($('.spinner'));
    });
}

app.sendEnvelope = function (route, data, title, el) {
    var i = 0;
    var id = 'add-template-form-' + (1 + Math.floor(Math.random() * 1000));
    app.loadingIconStart(el);
    app.openModal(id, route, title ,{});
    app.loadingIconStop(el);
    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();

        }

    });
}

app.addTemplateToEnvelope = function (route, data, title, el) {
    var i = 0;
    var id = 'add-template-form-' + (1 + Math.floor(Math.random() * 1000));
    app.loadingIconStart(el);
    app.openModal(id, route, title, {} );
    app.loadingIconStop(el);
    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();
            var formData = {};

            $('.ajax-form-field').each(function () {
                var name = $(this).attr("name");
                formData[name] = app.getFieldValue($(this));
            });

            var template = {
                id: formData.template,
                name: formData.template_name,
                fields: formData.custom_fields
            };

            var envelopeTemplates = $('[name="properties[templates]"]').val();
            if (envelopeTemplates) {
                envelopeTemplates = JSON.parse(envelopeTemplates);
            } else {
                var envelopeTemplates = [];
            }
            envelopeTemplates.push(template);

            $('[name="properties[templates]"]').val(JSON.stringify(envelopeTemplates)).stop();

            app.getTemplatesRows(envelopeTemplates);
            app.loadingIconStop(el);
            $('#form-modal').modal('hide');
            return false;
        }

    });
}

app.showShareDocument = function (route, data, title, el, sharingRoute) {
    var i = 0;
    var id = 'add-template-form-' + (1 + Math.floor(Math.random() * 1000));
    app.loadingIconStart(el);
    app.openModal(id, route, title , {});
    app.loadingIconStop(el);
    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();

            var document = $('select[name="document"]').val();
            var student = $('#student').val();

            var data = {
                action: 'documentBuilder.shareDocument',
                payload: {
                    'document': document,
                    'student': student
                }
            };

            app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
                if (data.status == 200) {
                    toastr.success(data.extra.message);
                    $('.applicant_shareables_div').html(data.extra.html);
                } else {
                    toastr.info(data.extra.message);
                }
            }).fail(function (data) {
                toastr.error('Please, Try again later', 'Somthing went wrong!');
            })

            app.loadingIconStop(el);
            $('#form-modal').modal('hide');
            return false;
        }

    });
}

app.getStudentSharedDocument = function (shareableId, option, el) {
    var data = {
        action: 'documentBuilder.getSharedDocument',
        payload: {
            shareableId: shareableId,
            option: option,
        }
    };

    app.loadingIconStart(el);


    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.status == 200) {
            var blob = app.b64toBlob(data.extra.FileContent, data.extra.ContentType, 512);

            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);

            if (data.extra.option == "download") {
                link.download = data.extra.FileName;
            } else {
                link.target = "_blank";
            }

            link.click();
        }
        app.loadingIconStop(el);
    }).fail(function () {
        app.loadingIconStop(el);
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    });
}

app.addSignerToEnvelope = function (route, data, title, el) {
    var i = 0;
    var id = 'add-signer-form-' + (1 + Math.floor(Math.random() * 1000));
    app.loadingIconStart(el);
    app.openModal(id, route, title, {} );
    app.loadingIconStop(el);
    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();
            var formData = {};

            $('.ajax-form-field').each(function () {
                var name = $(this).attr("name");
                formData[name] = app.getFieldValue($(this));
            });

            var signer = {
                order: formData.order,
                role: formData.role,
                first_name: formData.first_name,
                last_name: formData.last_name,
                email: formData.email
            };

            var envelopeSigners = $('[name="properties[signers]"]').val();
            if (envelopeSigners) {
                envelopeSigners = JSON.parse(envelopeSigners);
            } else {
                var envelopeSigners = [];
            }
            envelopeSigners.push(signer);

            $('[name="properties[signers]"]').val(JSON.stringify(envelopeSigners)).stop();

            app.getSignersRows(envelopeSigners);
            app.loadingIconStop(el);
            $('#form-modal').modal('hide');
            return false;
        }
    });
}

app.getSignersRows = function (envelopeSigners) {
    var data = {
        action: 'envelope.getSignersRows',
        payload: {
            'envelopeSigners': envelopeSigners
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.status == 200) {
            $('.signers-wrapper').html(data.extra.html);
        }
    });
}


app.getTemplatesRows = function (envelopeTemplates) {

    var envelopeId = $("[name='envelopeID']").val();

    var data = {
        action: 'envelope.getTemplatesRows',
        payload: {
            envelopeTemplates: envelopeTemplates,
            envelopeId: envelopeId
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.status == 200) {
            $('.temaplates-wrapper').html(data.extra.html);
        }
    });
}

app.editEnvelopeTemplate = function (route, data, el) {
    var i = 0;
    var id = 'edit-template-form-' + (1 + Math.floor(Math.random() * 1000));
    var title = $(el).data('title').replace("_", " ");
    var currentTemplate = $(el).data('template-id');
    app.loadingIconStart(el);
    app.openModal(id, route, title, {} );
    app.loadingIconStop(el);
    $('.btn-ok#' + id).click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();
            var formData = {};

            $('.ajax-form-field').each(function () {
                var name = $(this).attr("name");
                formData[name] = app.getFieldValue($(this));
            });
            var template = {
                id: formData.template,
                name: formData.template_name,
                fields: formData.custom_fields
            };

            var envelopeTemplates = $('[name="properties[templates]"]').val();

            if (envelopeTemplates) {
                envelopeTemplates = JSON.parse(envelopeTemplates);
            } else {
                var envelopeTemplates = [];
            }

            // Save all template except current one
            envelopeTemplates = envelopeTemplates.filter(function (envelopeTemplate) {
                return envelopeTemplate.id != currentTemplate
            })
            envelopeTemplates.push(template);

            $('[name="properties[templates]"]').val(JSON.stringify(envelopeTemplates)).stop();

            app.getTemplatesRows(envelopeTemplates);
            app.loadingIconStop(el);
            $('#form-modal').modal('hide');
            return false;
        }

    });
}

app.deleteEnvelopeTemplate = function (templateId) {

    var envelopeTemplates = JSON.parse($('[name="properties[templates]"]').val());

    envelopeTemplates = envelopeTemplates.filter(function (template) {
        return template.id != templateId
    })
    app.getTemplatesRows(envelopeTemplates);

    $('[name="properties[templates]"]').val(JSON.stringify(envelopeTemplates)).stop();

}
app.deleteEnvelopeSigner = function (order) {

    var envelopeSigners = JSON.parse($('[name="properties[signers]"]').val());

    envelopeSigners = envelopeSigners.filter(function (signer) {
        return signer.order != order
    })
    app.getSignersRows(envelopeSigners);

    $('[name="properties[signers]"]').val(JSON.stringify(envelopeSigners)).stop();

}
app.loadEnvelopesTemplates = function (el) {
    var hash = $(el).val();
    var templateName = $('[name="template"] option:selected').text();

    $('[name="properties[mapping_fields]"]').val('');
    $('[name="template_name"]').val('');

    if (hash) {

        $('[name="template_name"]').val(templateName);
        app.loadMappingFields($(el), hash, true);

    } else {
        $('#applicationFieldsWrapper').html('');
    }
}

app.loadMappingFields = function (el, hash, changed) {

    var data = {
        action: 'envelope.getMappingFields',
        payload: {
            'hash': hash
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (changed) {
            $('#applicationFieldsWrapper').html(data.extra.html);
        } else {
            $('#applicationFieldsWrapper').prepend(data.extra.html);
        }
        // Stop Spinning
        $('.spinner').attr('disabled', 'disabled');
        app.stopSpinning($('.spinner'));
    });
}

app.addMappedField = function (el) {

    var field_name_label = $('[name="field_name"] option:selected').text();

    var field_name_value = $('[name="field_name"]').val();

    var template_field_name_label = $('[name="template_field_alias"] option:selected').val();




    app.updateMappedFields(field_name_value, template_field_name_label);

    var data = {
        action: 'envelope.addMappedField',
        payload: {
            field_name: field_name_label,
            field_name_value: field_name_value,
            template_field_name: template_field_name_label,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        $('#applicationFieldsWrapper').append(data.extra.html);
    });
}

app.updateMappedFields = function (field_name_value, custom_field_name_value) {
    var fieldsPair = $('[name="custom_fields"]').val();

    if (fieldsPair.length > 1) {
        fieldsPair = JSON.parse(fieldsPair);
    } else {
        fieldsPair = [];
    }
    console.log("fieldsPair222");
    console.log(fieldsPair);

    /* try {
        fieldsPair = JSON.parse(fieldsPair);
    } catch (err) {

    } */

    fieldsPair.push({
        'field': field_name_value,
        'Esignature_field': custom_field_name_value,
    });

    $('[name="custom_fields"]').val(JSON.stringify(fieldsPair));
};

app.removeMappedField = function (name) {

    var fieldsPair = $('[name="custom_fields"]').val();

    fieldsPair = JSON.parse(fieldsPair);
    try {
        fieldsPair = JSON.parse(fieldsPair);
    } catch (err) {
        console.log('no necesario');
    }

    fieldsPair = fieldsPair.filter(function (jsonObject) {
        return jsonObject.field != name;
    });

    $('[data-field-name="' + name + '"]').remove();
    $('[name="custom_fields"]').val(JSON.stringify(fieldsPair));
}

app.showEnvelopeActions = function (el) {
    var envelope = $("[name=envelope]").val();
    var submission = $("[name=submission]").val();
    var studentId = $("[name=student_id]").val();

    if (envelope) {
        var data = {
            action: 'envelope.getSignersList',
            payload: {
                envelope: envelope,
                submission: submission,
                student_id: studentId,
            }
        };
        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

            if (data.status == 200) {
                $('#signersList').html(data.extra.html);
            }
        });

        $('#envelopeActions').removeClass("hidden");

    } else {
        $('#envelopeActions').addClass("hidden");
    }
}

app.signerRoleSelected = function (el) {
    var role = $(el).val();

    if (role != 'School') {
        $('.signer_data').prop('disabled', 'disabled');
    } else {
        $('.signer_data').prop('disabled', false);
    }
}
app.reviewSendEnvelope = function (el) {
    app.loadingIconStart(el);
    var envelopeId = $('[name = "envelope"]').val();
    var studentId = $('[name = "student_id"]').val();
    var submissionId = $('[name = "submission"]').val();
    var redirectURL = $('[name = "redirect_url"]').val();
    var data = {
        action: 'envelope.reviewAndSendEnvelope',
        payload: {
            'envelopeId': envelopeId,
            'studentId': studentId,
            'submissionId': submissionId,
            'redirectURL': redirectURL,
            'generate': true
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.status == 200) {
            app.redirect(data.extra.esignature.url, null);
            /* if (data.extra.esignature.service == "adobesign") {
                app.redirect(data.extra.esignature.url.url, null);
            } else {
                app.redirect(data.extra.esignature.url, null);
            } */
            app.loadingIconStop(el);
        }
    }).fail(function (data) {
        app.loadingIconStop(el);
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    })


}


app.loadApplicationFieldsForEsignature = function (el) {
    var hash = $(el).val();
    $('[name="properties[custom_fields]"]').val('');
    if (hash) {
        app.loadApplicationFieldsActionForEsignature($(el), hash, true);
    } else {
        $('#applicationFieldsWrapper').html('');
    }
};

app.loadMoreApplicationEsignatureFields = function (el) {
    app.spin($(el));
    var template = $('[name="properties[documentHash]"]');
    app.loadApplicationFieldsActionForEsignature(template, template.val(), false);
};

app.loadApplicationFieldsActionForEsignature = function (el, hash, changed) {
    var applicationid = el.data('application-id');
    var data = {
        action: 'application.getSignatureFieldsList',
        payload: {
            'applicationid': applicationid,
            'hash': hash
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (changed) {
            $('#applicationFieldsWrapper').html(data.extra.html);
        } else {
            $('#applicationFieldsWrapper').prepend(data.extra.html);
        }
        // Stop Spinning
        $('.spinner').attr('disabled', 'disabled');
        app.stopSpinning($('.spinner'));
    });
};

app.removeMapEsignatureField = function (name) {

    var fieldsPair = $('[name="properties[custom_fields]"]').val();

    fieldsPair = JSON.parse(fieldsPair);
    try {
        fieldsPair = JSON.parse(fieldsPair);
    } catch (err) {
        console.log('no necesario');
    }

    fieldsPair = fieldsPair.filter(function (jsonObject) {
        return jsonObject.field != name;
    });

    $('[data-field-name="' + name + '"]').remove();
    $('[name="properties[custom_fields]"]').val(JSON.stringify(fieldsPair));
}

app.filterBackApplicants = function (el) {

    var value = $(el).val();

    if (value.length > 4) {
        var data = {
            action: 'student.getFilteredStudents',
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
        action: 'student.getFilteredStudents',
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

app.calendarRange = function () {
    $('div.date').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Year': [moment().startOf('year'), moment().endOf('year')],
            'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
            'All Dates': [moment(), moment().subtract(1, 'days')],

        },
        alwaysShowCalendars: true,
        opens: 'left',
        //startDate: moment(),
        //endDate: moment().subtract(29, 'days'),
        autoUpdateInput: false,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });




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
            separator: ' to ',
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
                action: 'student.getFilteredStudents',
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

app.loadMauticEmails = function (el) {

    if ($(el).is(':checked')) {
        $('#MauticEmailsList').html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');

        var data = {
            action: 'applicationAction.loadMauticEmails',
            payload: {
                'email': 'test'
            }
        }

        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

            $('#MauticEmailsList').html(data.extra.html);

        });
    } else {
        $('#MauticEmailsList').html("");
    }


}

app.addCustomFieldName = function (el) {
    var field_name_label = $('[name="field_name"] option:selected').text();
    var field_name_value = $('[name="field_name"]').val();

    var custom_field_name_label = app.getFieldValue($('[name="mautic_field_alias"]'));

    var custom_field_name_value = $('[name="mautic_field_alias"]').val();

    var mautic_contact_type = $('[name="mautic_contact_type"] option:selected').text();

    app.updateFieldPair(field_name_value, custom_field_name_value, mautic_contact_type);

    var data = {
        action: 'application.getCustomizedFieldName',
        payload: {
            field_name: field_name_label,
            field_value: field_name_value,
            custom_field_name: custom_field_name_label,
            custom_field_value: custom_field_name_value,
            mautic_contact_type: mautic_contact_type,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        $('#applicationFieldsWrapper').append(data.extra.html);
    });
}

app.addCustomFieldNameForEsignature = function (el) {
    var field_name_label = $('[name="field_name"] option:selected').text();
    var field_name_value = $('[name="field_name"]').val();
    var custom_field_name_label = $('[name="mautic_field_alias"] option:selected').val();

    app.updateEsignatureFields(field_name_value, custom_field_name_label);

    var data = {
        action: 'application.getSignatureCustomizedFieldName',
        payload: {
            field_name: field_name_label,
            custom_field_name: custom_field_name_label,
        }

    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        $('#applicationFieldsWrapper').append(data.extra.html);
    });
}

app.updateFieldPair = function (field_name_value, custom_field_name_value, mautic_contact_type) {
    var fieldsPair = $('[name="mautic_field_pairs"]').val();

    if (fieldsPair) {
        fieldsPair = $.parseJSON(fieldsPair);
    } else {
        var fieldsPair = [];
    }

    fieldsPair.push({
        'field': field_name_value,
        'mautic_field': custom_field_name_value,

        'mautic_contact_type': mautic_contact_type,
    });
    $('[name="mautic_field_pairs"]').val(JSON.stringify(fieldsPair));
}

app.updateEsignatureFields = function (field_name_value, custom_field_name_value) {
    var fieldsPair = $('[name="properties[custom_fields]"]').val();

    if (fieldsPair) {
        fieldsPair = JSON.parse(fieldsPair);

    } else {
        var fieldsPair = [];
    }

    try {
        fieldsPair = JSON.parse(fieldsPair);
    } catch (err) {
        console.log('no necesario');
    }

    fieldsPair.push({
        'field': field_name_value,
        'Esignature_field': custom_field_name_value,
    });

    $('[name="properties[custom_fields]"]').val(JSON.stringify(fieldsPair));
};


app.resyncSubmission = function (submissionId, studentId, el) {

    app.loadingIconStart(el);
    var data = {
        action: 'submission.resync',
        payload: {
            submissionId: submissionId,
            studentId: studentId,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        app.loadingIconStop(el);
        console.log(data);
        if (data.status == 200) {
            toastr.success(data.extra.message);
        } else {
            toastr.error(data.extra.message);
        }

    }).fail(function (data) {

        app.loadingIconStop(el);
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    });
}

app.resyncField = function (submissionId, studentId, el) {

    var field = $(el).data('field-name');
    app.loadingIconStart(el);
    var data = {
        action: 'submission.resync',
        payload: {
            submissionId: submissionId,
            studentId: studentId,
            field: field,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        app.loadingIconStop(el);
        console.log(data);
        if (data.status == 200) {
            toastr.success(data.extra.message);
        } else {
            toastr.error(data.extra.message);
        }

    }).fail(function (data) {

        app.loadingIconStop(el);
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    });
}

app.loadingIconStart = function (el) {
    var icon = $(el).find($("i"));


    //icon.hide();
    var orgClass = icon.attr("class");

    icon.attr("class", "fa fa-spinner fa-spin");
    icon.attr("org-class", orgClass);
}
app.loadingIconStop = function (el) {
    var icon = $(el).find($("i"));
    icon.attr("class", icon.attr("org-class"));
}

app.approveStudentSubmission = function (submissionId, studentId, el) {
    app.loadingIconStart(el);
    var data = {
        action: 'submission.approve',
        payload: {
            submissionId: submissionId,
            studentId: studentId,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.extra.show_contract_cta) {
            $('.status-icon').html('<div class="btn-circle btn-success small-btn d-inline-block"><i class="icon-check"></i></div>');

            $('.approve-section').hide();
            $('.approved-section').show();
            app.loadingIconStop(el);

        }
    });
}

/***
 * E-Signature flow
 */

/***
 * Review and Send a CREATRD Contract
 */
app.reviewContract = function (contractId, el) {
    app.loadingIconStart(el);
    var data = {
        action: 'contract.reviewAndSendContract',
        payload: {
            'contractId': contractId,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        app.loadingIconStop(el);

        if (data.status == 200) {
            app.redirect(data.extra.esignature.url, null);
        }
    }).fail(function (data) {
        app.loadingIconStop(el);
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    });
}

/***
 * Void SENT Contract
 */
app.voidContract = function (contractId, el) {
    app.loadingIconStart(el);
    var data = {
        action: 'contract.voidContract',
        payload: {
            contractId: contractId
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.status == 200) {
            toastr.success(data.extra.message);
        }
        app.loadingIconStop(el);
    }).fail(function (data) {
        app.loadingIconStop(el);
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    });
}

/***
 * Generate, Review and send a NEW contract
 */

app.reviewSendEnvelope = function (el) {
    app.loadingIconStart(el);
    var envelopeId = $('[name = "envelope"]').val();
    var studentId = $('[name = "student_id"]').val();
    var submissionId = $('[name = "submission"]').val();
    var redirectURL = $('[name = "redirect_url"]').val();

    var signers = {};
    $('.ajax-form-field').each(function () {
        var name = $(this).attr("name");
        signers[name] = app.getFieldValue($(this));
    });

    var data = {
        action: 'envelope.reviewAndSendEnvelope',
        payload: {
            'envelopeId': envelopeId,
            'studentId': studentId,
            'submissionId': submissionId,
            'redirectURL': redirectURL,
            'generate': true,
            'signers': signers
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.status == 200) {

            if (typeof data.extra.response.esignature.url != 'undefined') {
                app.redirect(data.extra.response.esignature.url, null);
            } else {

                var i = 0;
                $.each(data.extra.response.esignature.documents, function (id, url) {
                    console.log(id);
                    console.log(i);
                    if (i == 0) {
                        app.redirect(url, null);
                    } else {
                        app.redirect(url, true);
                    }
                    i++;
                });
            }
            app.loadingIconStop(el);
        }
    }).fail(function (data) {
        app.loadingIconStop(el);
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    })
}

app.generateContract = function (submissionId, studentId, route, el) {

    app.loadingIconStart(el);
    var data = {
        action: 'submission.generateContract',
        payload: {
            submissionId: submissionId,
            studentId: studentId,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.status == 200) {
            window.open(data.extra.esignature.url, '_blank').focus();
            app.loadingIconStop(el);
        }
    }).fail(function (data) {
        app.loadingIconStop(el);
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    });

}

app.sendContract = function (submissionId, studentId, el) {
    var data = {
        action: 'submission.sendContract',
        payload: {
            submissionId: submissionId,
            studentId: studentId,
        }
    };
    app.loadingIconStart(el);
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.status == 200) {
            $('.approved-section').html(data.extra.html);
            toastr.success(data.extra.message);
        }
    }).fail(function () {
        app.loadingIconStop(el);
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    });
}

app.sendContractReminder = function (uid, studentId, el) {
    var data = {
        action: 'contract.sendContractReminder',
        payload: {
            uid: uid,
            studentId: studentId,
        }
    };
    app.loadingIconStart(el);
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.status == 200) {
            $('.approved-section').html(data.extra.html);
            toastr.success(data.extra.message);
        }
    }).fail(function () {
        app.loadingIconStop(el);
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    });
}


app.toggleFullAccessPermission = function(el)
{
    var value = $(el).val();
    if(value == 'No'){
        $('#permissions_selection').removeClass("hidden");
        $('#full_permission').addClass("hidden");
    }else{
        $('#permissions_selection').addClass("hidden");
        $('#full_permission').removeClass("hidden");
    }
}

app.downloadContract = function (el, route) {
    var title = $(el).data('title');
    var i = 0;
    var id = 'get-evelope-documents-list' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );

}

app.downloadContractDocument = function (documentId, documentName, uid, download, el) {
    var data = {
        action: 'contract.downloadContractDocument',
        payload: {
            documentId: documentId,
            documentName: documentName,
            uid: uid,
        }
    };

    app.loadingIconStart(el);
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        var blob = app.b64toBlob(data.FileContent, data.ContentType, 512);
        console.log(blob);

        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);

        if (download) {

            link.download = data.FileName;

        } else {

            link.target = "_blank";
        }

        link.click();
        app.loadingIconStop(el);
    }).fail(function () {
        app.loadingIconStop(el);
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    });
}

app.b64toBlob = function (b64Data, contentType, sliceSize) {
    contentType = contentType || '';
    sliceSize = sliceSize || 512;

    var byteCharacters = atob(b64Data);
    var byteArrays = [];

    for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        var slice = byteCharacters.slice(offset, offset + sliceSize);

        var byteNumbers = new Array(slice.length);
        for (var i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        var byteArray = new Uint8Array(byteNumbers);

        byteArrays.push(byteArray);
    }

    var blob = new Blob(byteArrays, {
        type: contentType
    });
    return blob;
}

app.displayErrors = function (errors, form) {
    console.log(errors)

    $.each(errors, function (index, value) {
        var field = $("[name='" + index + "']");
        field.addClass('has-error');
        var errorMessage = $('.error-' + index);
        if (!errorMessage.length) {
            field.closest($('div.form-group')).append('<div class="invalid-feedback error-' + index + '" style="display:block;">' + value[0] + '</div>');
        }
    });
}



app.getFieldValue = function (el) {

    // get the value of select

    if (el.is("select")) {
        return el.val();
    } else if (el.is("textarea")) {
        return el.val();

    } else if (el.hasClass("filePondUploader")) {
        console.log(el.closest($('.filepond--browser')));
        console.log(el.closest($('.filepond--browser')).val());
        //return el.closest($('input[type="file"]')).prop('files');

    } else {
        //get the of input
        var type = el.attr('type');
        var name = el.attr('name');

        //text, email, etc.
        if ($.inArray(type, ['text', 'email', 'hidden', 'phone', 'date', 'time', 'password']) >= 0) {
            if (el.val()) {
                return el.val();
            }
        }
        //checkbox, radio
        if ($.inArray(type, ['checkbox', 'radio']) >= 0) {
            return (el.is(":checked")) ? true : null;
        }
    }
    return " ";
};

app.cloneField = function (route, element) {
    app.appAjax('POST', null, route).then(function (data) {
        if (data.response == "success") {
            $('div[data-parent-section="' + data.extra.section_id + '"]').append(data.extra.html);
            $('#field-' + data.extra.field_id).trigger("click");
        }
    }).fail(function () {
        var errors = $.parseJSON(error.responseText).errors
        app.displayErrors(errors, '');
    });
};

app.archiveSubscription = function (subscriptionId) {
    app.appAjax('PUT', { 'status' : 'Archived' }, `/submissions/${subscriptionId}/status`).then(function (data) {
        if (data.response == "success") {
            window.submissions_table.ajax.reload();
        }
    }).catch(function () {
        // Error
    });
};



app.restoreSubscription = function (subscriptionId) {
    app.appAjax('GET', null, `/submissions/${subscriptionId}/activate`).then(function (data) {
        if (data.response == "success") {
            window.submissions_table.ajax.reload();
        }
    }).catch(function () {
        // Error
    });
};

//Delete Fields , Delete Section
app.deleteElement = function (route, data, removedElement) {
    var i = 0;
    $('#confirm-delete').modal('show');
    $('.btn-ok').click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();

            app.appAjax('DELETE', data, route).then(function (data) {
                // success
                if (data.response == "success") {
                    if (removedElement) {
                        // Hide Deleted Row
                        $('[' + removedElement + '="' + data.extra.removedId + '"]').fadeOut(function () {
                            $(this).remove();
                        });
                    }
                    $('#confirm-delete').modal('hide');
                    return true;
                } else {
                    $('#confirm-delete').modal('hide');
                    swal({
                        icon: 'error',
                        text: 'Item could not be deleted, possibly has a dependency',
                    });
                }
            }).fail(function () {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
        }
    });
};

app.unlock = function (route, data, removedElement) {
    var i = 0;
    $('#confirm-action').modal('show');
    $('.btn-ok').click(function (e) {

        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();

            app.appAjax('GET', data, route).then(function (data) {
                // success
                if (data.response == "success") {
                    if (removedElement) {
                        $(removedElement).remove();
                    }
                    //close Modal
                    $('#confirm-action').modal('hide');
                    // swal({
                    //     icon: 'success',
                    //     text: 'Deleted successfully',
                    // });
                    return true;
                } else {
                    $('#confirm-action').modal('hide');
                    swal({
                        icon: 'error',
                        text: 'Item could not be deleted, possibly has a dependency',
                    });
                }
            }).fail(function () {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
        }
    });
};

app.deleteInvoice = function (route, data) {
    var i = 0;
    $('#confirm-delete').modal('show');
    $('.btn-ok').click(function (e) {
        if (i == 0) {
            i++;
            e.preventDefault();

            app.appAjax('DELETE', data, route).then(function (data) {
                // success
                if (data.response == "success") {
                    //close Modal
                    $('#confirm-delete').modal('hide');
                    swal({
                        icon: 'success',
                        text: 'Deleted successfully',
                    }).then(function () {
                        location.reload();
                    });
                    return true;
                } else {
                    $('#confirm-delete').modal('hide');
                    swal({
                        icon: 'error',
                        text: 'Item could not be deleted, possibly has a dependency',
                        type: "success"
                    })
                        .then(function () {
                            location.reload();
                        });
                }
            }).fail(function () {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
        }
    });
};

app.changeInvoiceAsPaid = function (route, data) {
    var i = 0;
    var id = 'paid-invoice-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, 'Convert invoice as paid' , {});

    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal

        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));

                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }

                formData[name] = value;

            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('POST', formData, actionRoute).then(function (data) {

                // success

                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    location.reload();
                }

                //$('#' + id + ' .modal-body form').remove();

                i++;

                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });

            // Remove Form
        }

    });
};

app.createInvoice = function (route, data) {
    var i = 0;
    var id = 'create-invoice-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, 'Create invoice' , {});

    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal

        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));

                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }

                formData[name] = value;

            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('POST', formData, actionRoute).then(function (data) {

                // success

                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    location.reload();
                }

                //$('#' + id + ' .modal-body form').remove();

                i++;

                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors

                app.displayErrors(errors, '');
            });

            // Remove Form
        }

    });
};

app.editAttendance = function (route, data) {
    var i = 0;
    var id = 'edit-attendance-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, 'Edit attendance' ,{});

    $('.btn-ok#' + id).click(function (e) {
        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));

                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }

                formData[name] = value;

                var actionRoute = $('#form-modal .modal-body form').attr('action');

                app.appAjax('POST', formData, actionRoute).then(function (data) {

                    // success

                    if (data.response == "success") {
                        $('#form-modal').modal('hide');

                        location.reload();
                        //window.location.href = '../'; //one level up

                    }

                    //$('#' + id + ' .modal-body form').remove();

                    i++;

                    return false;
                }).fail(function (error) {
                    var errors = $.parseJSON(error.responseText).errors

                    app.displayErrors(errors, '');
                });
            });
        }
    });
};

app.editLessonAttendance = function (route, data) {
    var i = 0;
    var id = 'edit-attendance-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, 'Edit attendance', {});

    $('.btn-ok#' + id).click(function (e) {
        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));

                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }

                formData[name] = value;

                var actionRoute = $('#form-modal .modal-body form').attr('action');

                app.appAjax('POST', formData, actionRoute).then(function (data) {

                    // success

                    if (data.response == "success") {
                        $('#form-modal').modal('hide');

                        location.reload();
                        //window.location.href = '../'; //one level up

                    }

                    //$('#' + id + ' .modal-body form').remove();

                    i++;
                    location.reload();
                    return false;
                }).fail(function (error) {

                    var errors = $.parseJSON(error.responseText).errors

                    app.displayErrors(errors, '');
                });
            });
        }
    });
};

app.cloneSection = function (route) {
    app.appAjax('POST', null, route).then(function (data) {
        if (data.response == "success") {
            $('.sections-container').append(data.extra.html);
            return true;
        }
    }).fail(function () {
        var errors = $.parseJSON(error.responseText).errors
        app.displayErrors(errors, '');
    })
};

app.dataListChange = function (el) {
    var value = $(el).find($('option:selected')).val();
    var application = $(el).data('application');
    $('.list_name').val(value).change();

    if (value == 'custom_list') {
        $('.add-values').show();
        app.toggleListSync('hide');

    } else {
        $('.custom_list_wrapper').html("");
        $('.add-values').hide()
        app.toggleListSync('hide');

        // Get A list of Avaliable options
        var data = {
            action: 'field.getOptionsList',
            payload: {
                list: value,
                application: application
            }
        };
        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            //custom_list_wrapper
            $('.custom_list_wrapper').html(data.extra.html);
        });
    }
};

app.getMauticCustomFieldData = function (el) {
    var value = $(el).find($('option:selected')).val();

    var data = {
        action: 'field.getMauticCustomFieldData',
        payload: {
            list: value
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        //custom_list_wrapper
        $('#mautic_fields').html(data.extra.html);
    });
};

app.syncStudentToMautic = function (route) {
    console.log(route);
};

app.dataListRefresh = function () {

    var value = $('select[name ="data"]').find($('option:selected')).val()
    //var value = $(el).find($('option:selected')).val();
    $('.list_name').val(value).change();

    if (value == 'custom_list') {
        $('.add-values').show();
        app.toggleListSync('hide');
    } else {
        if (value == "program" || value == "campus" || value == "intake") {
            app.toggleListSync('show');
        }
        $('.custom_list_wrapper').html("<h4>Loading ...</h4>");
        $('.add-values').hide()
        app.toggleListSync('hide');

        // Get A list of Avaliable options
        var data = {
            action: 'field.getOptionsList',
            payload: {
                list: value
            }
        };

        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            //custom_list_wrapper
            $('.custom_list_wrapper').html(data.extra.html);
        });
    }
};

app.toggleListSync = function (action) {

    if (action == 'show') {
        $('.sync_with_campus').show();
        $('.refresh').show();
        $('.sync_isSynced').show();

        $('.add-values').hide();
    } else {
        $('.sync_with_campus').hide();

        $('.sync_field').prop('checked', false).change();
    }

}

app.addValidationRule = function (el) {



    if ($(el).val() != 0) {
        app.order++;

        var data = {

            action: 'validationRules.create',

            payload: {

                type: $(el).val()

            }

        };



        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {



            $(el).val(0).change();



            $('.ValidationRules').append(data.extra.html);



            $(el).find('option[value="' + data.extra.type + '"]').attr('disabled', 'disabled');





        });
    }



}

app.removeValidationRule = function (el, type) {

    // Find the Parent Row

    $(el).closest('.validationRuleWrapper').remove();



    $('select[name="validation_rules_select"] option[value="' + type + '"]').removeAttr('disabled');

}

app.smartFieldSwitch = function (el) {



    var applicationId = $(el).data('application');



    if ($(el).is(":checked")) {
        var data = {

            action: 'field.getIntelligenceRule',



            payload: {

                applicationId: applicationId

            }



        };



        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {



            $('.intelligence_rules').append(data.extra.html);

            app.customSelect();



        });
    } else {
        $('.intelligence_rules').html(" ");
    }







}

// Not Used
app.uploadFile = function (el) {

    fileData = new FormData();
    if ($(el).prop('files').length > 0) {
        file = $(el).prop('files')[0];
        fileData.append("icon", file);
    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    var files = null;
    return $.ajax({
        url: app.uploaderUrl,
        type: "POST",
        data: fileData,
        processData: false,
        contentType: false,
        success: function (result) {
            return result;
        }
    });
};

app.deleteFile = function (fileName, route, fileHolderName, isList, successMessage, successTitle) {
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
};

app.disableSaveButton = function () {

    $(".actions ul li:nth-child(2) a").addClass("disabled_btn");
    $(".actions ul li:nth-child(3) a").addClass("disabled_btn");

};

app.enableSaveButton = function () {

    $(".actions ul li:nth-child(2) a").removeClass("disabled_btn");
    $(".actions ul li:nth-child(3) a").removeClass("disabled_btn");
};

app.fileUploader = function (el) {
    if ($(".fileuploader").length > 0) {
        $(".fileuploader").each(function () {

            var allowed = $(this).data("allowed");
            var uploaderURL = $(this).data("upload");
            var multiple = $(this).data("multiple");
            var deleteURL = $(this).data("destroy");
            var fileHolderName = $(this).data("name");
            var isList = $(this).data("list");
            var uploadeString = $(this).data("uploadstr");
            var student_id = $(this).data("student_id");

            var deleteBtnTxt = $(this).data("delete-btn-txt");
            var deleteMessage = $(this).data("delete-message");
            var deleteWarning = $(this).data("delete-message");


            $(this).uploadFile({
                url: uploaderURL,
                fileName: "documents",
                multiple: multiple,
                dragDrop: true,
                maxFileSize: 2097152,
                showFileCounter: false,
                returnType: "json",
                allowedTypes: allowed,
                showDelete: true,
                showDone: true,
                formData: {
                    "student_id": student_id
                },
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

                onSuccess: function (files, data, xhr, pd) { },

                afterUploadAll: function (obj) {

                    var response = obj.getResponses();
                    var fileName = response[response.length - 1].extra.file;

                    if (!isList) {
                        //Hide File Uploader
                        // $('[data-name="' + fileHolderName + '"]').hide();
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
};

app.syncWithSettings = function (el) {
    data = $('[name="data"]').val();
    if ($(el).is(":checked") && data !== 'custom_field') {
        var application = $(el).data('application');

        var data = {
            action: 'application.showSynSettings',
            payload: {
                application: application,
            }
        };
        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            $('.sync-settings-wrapper').html(data.extra.html);
            app.customSelect();
        });
    } else {
        $('.sync-settings-wrapper').html("");
    }

}

app.smartFieldChanged = function (el, condition) {
    var fieldName = $(el).val();
    var applicationId = $(el).data("applicationid");

    if (typeof condition == 'undefined') {
        condition = 'equals'
    }

    if (fieldName) {
        var data = {
            action: 'field.fieldData',
            payload: {
                fieldName: fieldName,
                applicationId: applicationId,
                condition: condition,
            }
        };
        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.fieldValue').html(data.extra.html);
        });
    }
}

app.getMultipleLessons = function (el, object , lesson) {

    var value = $(el).val();
    var name = $(el).attr('name');
    $(".select2").select2();
    var data = {
        action: 'lesson.getLessonsDetails',
        payload: {
            from: {
                fieldName: name,
                fieldValue: value,
            },
            get: object,
            lesson : lesson
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response == "success") {
            $(data.extra.container).html(data.extra.html);
        }

    });


}

app.searchAvailableSlots = function (el, condition) {
    var classroom = $('.classtoom-holder [name="classroom"]').val();
    var date = $('#date').val();

    if (classroom && date) {
        var data = {
            action: 'classroomSlotAvailable.index',
            payload: {
                classroom: classroom,
                date: date
            }
        };

        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            console.log(data);
            app.customSelect();
            $('.slots-available').html(data.extra.html);
        });
    } else {
        $('.slots-available').html('');
    }
};

app.searchMultiAvailableSlots = function (el, condition) {
    var classroom = $('[name="classroom"]').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var week = $('[name="week[]"]').val();

    if (classroom && start_date && end_date && week !== undefined && week.length > 0) {
        var data = {
            action: 'classroomSlotAvailable.multi',
            payload: {
                classroom: classroom,
                start_date: start_date,
                end_date: end_date,
                week: week
            }
        };

        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.slots-available').html(data.extra.html);
        });
    } else {
        $('.slots-available').html('');
    }
};

app.searchMultiSlots = function (el) {
    var week_day = $(el).val();
    var slots_select = $(el).closest('.row').find('[name^="classroom_slot"]');
    var classroom = $('[name="classroom"]').val();

    slots_select.empty();

    if (classroom && week_day) {
        var data = {
            action: 'classroomSlot.classroomSlots',
            payload: {
                week_day: week_day,
                classroom: classroom
            }
        };

        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            console.log(data.extra.slots);
            $.each(data.extra.slots, function (i, item) {
                slots_select.append(new Option(item, i));
            });
        });
    }
};

app.clearSlots = function (cssClass) {
    $(cssClass).html('');
    var default_slots_select = $('.default_slots').find('[name^="classroom_slot"]');
    var default_week = $('.default_slots').find('[name^="week"]');
    default_slots_select.empty();
    default_week.val('').change();
};

app.groupOrSemester = function (el) {
    var program = $(el).val();

    if (program) {
        var dataGroups = {
            action: 'group.groupOrSemester',
            payload: {
                program: program
            }
        };

        app.appAjax('POST', dataGroups, app.ajaxRoute).then(function (data) {
            app.customSelect();
            if (data.semester === false) {
                $('.semesters').hide();
                $('.semester_group_radio').hide();
                $('.groups').html(data.extra.html);
                $(".select2").select2();
                $('select:disabled').prop("checked", true).trigger("change");
            } else {
                $('.groups').html('');
                $('.semesters').show();
                $('.semester_group_radio').show();
                $('.semester_group_radio').html(data.extra.html);
            }
        });

        var dataCourses = {
            action: 'course.coursesFromProgram',
            payload: {
                program: program
            }
        };

        app.appAjax('POST', dataCourses, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.courses').html(data.extra.html);
            $(".select2").select2();
        });
    } else {
        $('.fieldValue').html('');
    }
};

app.showGroups = function (el) {
    var program = $('.program-field').val();
    if (program) {
        $('.semesters').hide();
        $('.groups').show();

        var dataGroups = {
            action: 'group.courseGroups',
            payload: {
                program: program
            }
        };

        app.appAjax('POST', dataGroups, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.semesters').html('');
            $('.groups').html(data.extra.html);
            $(".select2").select2();
            $('select:disabled').prop("checked", true).trigger("change");
        });
    }
};

app.showSemesters = function (el) {
    var program = $('.program-field').val();
    if (program) {
        $('.groups').hide();
        $('.semesters').show();

        var dataGroups = {
            action: 'semester.programSemester',
            payload: {
                program: program
            }
        };

        app.appAjax('POST', dataGroups, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.groups').html('');

            $('.semesters').html(data.extra.html);
            $(".select2").select2();
            $('select:disabled').prop("checked", true).trigger("change");
        });
    }
}

app.courseModulesGroup = function (el) {
    var program = $(el).val();

    if (program) {
        var dataModules = {
            action: 'courseModules.index',
            payload: {
                program: program
            }
        };

        app.appAjax('POST', dataModules, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.modules').html(data.extra.html);
            $(".select2").select2();
        });

        var dataGroups = {
            action: 'group.courseGroups',
            payload: {
                program: program
            }
        };

        app.appAjax('POST', dataGroups, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.groups').html(data.extra.html);
            $(".select2").select2();
            $('select:disabled').prop("checked", true).trigger("change");
        });

        var dataCourses = {
            action: 'course.coursesFromProgram',
            payload: {
                program: program
            }
        };

        app.appAjax('POST', dataCourses, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.courses').html(data.extra.html);
            $(".select2").select2();
        });

        var dataCourses = {
            action: 'semester.programSemester',
            payload: {
                program: program
            }
        };

        app.appAjax('POST', dataCourses, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.semesters').html(data.extra.html);
            $(".select2").select2();
        });
    } else {
        $('.fieldValue').html('');
    }
};

app.groupByProgram = function (el) {
    var program = $(el).val();

    if (program) {
        var dataGroups = {
            action: 'group.groupByProgram',
            payload: {
                program: program
            }
        };
        app.appAjax('POST', dataGroups, app.ajaxRoute).then(function (data) {
            if (data.response == 'success') {
                $('#GroupsList').html(data.html);
                $('.select2').select2();
            }
        });
    }
};

app.groupByCourse = function (el) {
    var course = $(el).val();

    if (course) {
        var dataGroups = {
            action: 'group.groupByCourse',
            payload: {
                course: course
            }
        };

        app.appAjax('POST', dataGroups, app.ajaxRoute).then(function (data) {
            $('.groups').empty();
            $.each(data.groups, function (i, item) {
                $('.groups').append(new Option(item, i));
            });
        });
    }
};

app.groupInstructors = function (el) {
    var group = $(el).val();

    if (group) {
        var data = {
            action: 'instructor.byGroup',
            payload: {
                group: group
            }
        };

        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.instructors').html(data.extra.html);
            $(".select2").select2();
        });
    }
};

app.courseInstructors = function (el) {
    var course = $(el).val();

    if (course) {
        var data = {
            action: 'instructor.byCourse',
            payload: {
                course: course
            }
        };

        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.instructors').html(data.extra.html);
            $(".select2").select2();
        });
    }
}

app.smartFieldConditionChanged = function (el) {
    var value = $(el).val();

    if (value == 'empty' || value == 'not_empty') {
        $('.logic_value').attr('disabled', 'disabled');
    } else if (value == 'contain') {
        app.smartFieldChanged($('.logic_field'), 'contain');
    } else {
        $('.logic_value').removeAttr('disabled');
    }
};

app.resetSmartFieldLogic = function (el) {

    $(el).closest('.fields-wrapper.list-group').hide();

    $('.is_smart_field').attr("checked", false).change();

}

app.addCourse = function (route, data, title, el) {
    var i = 0;

    var id = 'create-course-form-' + (1 + Math.floor(Math.random() * 1000));

    app.openModal(id, route, title, {} );

    $('.btn-ok#' + id).click(function (e) {
        // to prevent duplicate request caused by the modal

        if (i == 0) {
            i++;
            e.preventDefault();
            var formData = {};
            $('.ajax-form-field').each(function () {
                var name = $(this).attr("name");
                formData[name] = app.getFieldValue($(this));
            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('POST', formData, actionRoute).done(function (data) {
                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    // Get Wrapper
                    $('div[data-section="' + data.extra.section_id + '"]').append(data.extra.html);
                }
                return false;
            });
            // Remove Form
            $('#' + id + ' .modal-body form').remove();
        }
    });
};

app.resetSelection = function(el)
{
    event.preventDefault();
    var action = $(el).attr('data-action');
    console.log(action);
    if (action == 'uncheck') {

        $("[name='groups[]']").each(function () {
            $(this).removeAttr('checked');
        });

        $('.uncheck-check-button').html('Check All');
        $('.uncheck-check-button').attr('data-action', 'check').change();

    } else if (action == 'check') {

        $("[name='groups[]']").each(function () {
            $(this).attr('checked', 'checked');
        });

        $('.uncheck-check-button').attr('data-action', 'uncheck');
        $('.uncheck-check-button').html('Uncheck All');

    } else if (action == 'exclude') {

        $("[data-count='0']").each(function () {
            $(this).removeAttr('checked');
        });

        $('.exclude-include-button').html('Include Empty Cohorts');
        $('.exclude-include-button').attr('data-action', 'include').change();

    } else if (action == 'include') {

        $("[data-count='0']").each(function () {
           $(this).attr('checked', 'checked');
        });

        $('.exclude-include-button').attr('data-action', 'exclude');
        $('.exclude-include-button').html('Exclude Empty Cohorts');

    }

}

app.addPaymentGateway = function (route, title) {

    var i = 0;

    var id = 'add-payment-gateway-' + (1 + Math.floor(Math.random() * 1000));

    // Open Modal

    app.openModal(id, route, title, {} );





    $('.btn-ok#' + id).click(function (e) {



        // to prevent duplicate request caused by the modal

        if (i == 0) {
            e.preventDefault();

            var formData = {};



            $('.ajax-form-field').each(function () {



                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));



                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }



                formData[name] = value;



            });



            var actionRoute = $('#form-modal .modal-body form').attr('action');



            app.appAjax('POST', formData, actionRoute).done(function (data) {



                // success

                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    // Get Wrapper
                    if (typeof data.extra.section_id != 'undefined') {
                        $('div[data-parent-section="' + data.extra.section_id + '"]').append(data.extra.html);
                    } else {
                        $('#draggable-area').append(data.extra.html);
                    }

                    i++;
                }

                return false;

            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');

            });

            // Remove Form

            $('#' + id + ' .modal-body form').remove();
        }

    });



}

app.addIntegration = function (route, title) {

    var i = 0;

    var id = 'add-integration-' + (1 + Math.floor(Math.random() * 1000));

    // Open Modal

    app.openModal(id, route, title, {} );



    $('.btn-ok#' + id).click(function (e) {



        // to prevent duplicate request caused by the modal

        if (i == 0) {
            e.preventDefault();

            var formData = {};



            $('.ajax-form-field').each(function () {



                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));



                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }



                formData[name] = value;



            });



            var actionRoute = $('#form-modal .modal-body form').attr('action');



            app.appAjax('POST', formData, actionRoute).done(function (data) {



                // success

                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    // Get Wrapper

                    //$('div[data-parent-section="'+data.extra.section_id+'"]').append(data.extra.html);

                    $('#draggable-area').append(data.extra.html);
                }

                i++;

                return false;



            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');


            });

            // Remove Form

            $('#' + id + ' .modal-body form').remove();
        }

    });



}

app.addApplicationAction = function (route, title) {
    var i = 0;

    var id = 'add-application-action-' + (1 + Math.floor(Math.random() * 1000));

    // Open Modal
    app.openModal(id, route, title, {} );

    $('.btn-ok#' + id).click(function (e) {



        // to prevent duplicate request caused by the modal

        if (i == 0) {
            e.preventDefault();

            var formData = {};



            $('.ajax-form-field').each(function () {



                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));



                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }



                formData[name] = value;



            });



            var actionRoute = $('#form-modal .modal-body form').attr('action');



            app.appAjax('POST', formData, actionRoute).done(function (data) {



                // success

                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    // Get Wrapper

                    //$('div[data-parent-section="'+data.extra.section_id+'"]').append(data.extra.html);

                    $('#draggable-area').append(data.extra.html);
                }

                i++;

                return false;



            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });

            // Remove Form

            $('#' + id + ' .modal-body form').remove();
        }

    });



}

app.toggleSchoolIntegration = function (el) {
    var platform = $(el).data('integration');

    if ($(el).is(':checked')) {
        app.settingsToggle(platform, 'enable');
    } else {
        app.settingsToggle(platform, 'disable');
    }

    $('#' + platform + ' input').each(function () {

        if ($(el).is(':checked')) {
            $(this).removeAttr('disabled');
        } else {
            $(this).attr('disabled', 'disabled');
        }


    });

}

app.toggleTracking = function (el) {

    var platform = $(el).data('tracking');

    if ($(el).is(':checked')) {
        app.settingsToggle(platform, 'enable');
    } else {
        app.settingsToggle(platform, 'disable');
    }

    $('#' + platform + ' input').each(function () {

        if ($(el).is(':checked')) {
            $(this).removeAttr('disabled');
        } else {
            $(this).attr('disabled', 'disabled');
        }


    });

}

app.settingsToggle = function (item, status) {

    var action = 'setting.toggle';
    var data = {
        action: action,
        payload: {
            item: item,
            status: status,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        console.log(data);
    });


}

app.sendInvoiceReminder = function (route, data, title, el) {
    var i = 0;
    var id = 'send-reminder-email-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );
    $('.btn-ok').click(function (e) {

        // to prevent duplicate request caused by the modal

        if (i == 0) {
            i++;
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");
                var value = app.getFieldValue($(this));
                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }
                formData[name] = value;
            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('POST', formData, actionRoute).then(function (data) {

                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    return true;
                }
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
        }
    });
}

app.sendDirectMessage = function (route, data, title, el) {
    var i = 0;
    var id = 'send-direct-message-' + (1 + Math.floor(Math.random() * 1000));

    var params = JSON.parse(data);
    //var route = route + '?' + $.param(params);

    app.openModal(id, route, title , params);

    $('.btn-ok#'+ id).click(function (e) {
        if (i == 0) {
            e.preventDefault();
            var formData = new FormData(document.getElementById("sendMessageFrom"));
            var actionRoute = $('#sendMessageFrom').attr('action');
            app.appAjax('POST', formData, actionRoute, 'file').then(function (data) {

                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    // Append the new message
                    $('#MessagesTable table').prepend(data.extra.html);
                    toastr.success(data.extra.message);
                    i++;
                    app.refreshMessagesList(el, data.extra.recipient);
                    return true;
                }
            }).fail(function (error) {
                i=0;
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
        }


    });

}

app.showDirectMessagesList = function (el , recipientId) {

    $('#directMessages').fadeIn("fast");
    $('#MessageDetails').html("").fadeOut("fast");

    app.refreshMessagesList(el,recipientId);
}

app.filePondUploader = function () {
    if ($('.filePondUploader').length > 0) {
        $('.filePondUploader').each(function () {

            const uploadUrl = $(this).data('upload-url');
            const deleteUrl = $(this).data('delete-url');
            const allowedFileTypes = $(this).data('allowed');
            const labelIdle = $(this).data('label-idle');
            console.log(labelIdle);
            FilePond.registerPlugin(FilePondPluginFileValidateType);

            let pond = FilePond.create(this);
            pond.setOptions({
                labelIdle: (labelIdle) ? labelIdle : 'Drag & Drop your files or <span class="filepond--label-action"> Browse </span>',
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
                        formData.append('fileName',uniqueFileId.fileName);
                        formData.append('url',uniqueFileId.url);
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

app.filePondUploading = function (event) {

}

app.filePondUploaded = function (event) {

}

app.viewDirectMessage = function (el, messageId, recipientId) {

    var data = {
        action: 'message.view',
        payload: {
            message: messageId,
            recipient: recipientId,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute, data).then(function (response) {
        if (response.response == 'success') {
            $('#MessageDetails').html(response.extra.html).fadeIn("fast");
            app.initPlugins();
            $('#directMessages').fadeOut("fast");
            window.editor.editor.addEventListener('keyup', function (event) {
                if (window.editor.value.length) {
                    $('#saveReplay').removeAttr('disabled');
                } else {
                    $('#saveReplay').attr('disabled', 'disabled');
                }
            });
        }
    });
}

app.lookupAction =  function(el, action, payload) {
    var payload = JSON.parse(payload);
    const query = $(el).val();
    const container = $(el).data('field-id')+'_results';
    if (query.length >= 3 || query.length == 0) {
        var data = {
            action: action,
            payload: {
                query: query,
                ...payload
            }
        };
        app.appAjax('POST', data, app.ajaxRoute, data).then(function (response) {
            if (response.response == 'success') {
                $('#' + container).fadeIn('fast');
                $('#' + container).html(response.extra.html);
            }
        });

    }
}

app.lookUpSelected = function(el,data,value,label)
{
    var result = JSON.parse(data);
    $('input.lookup-field').val(value).change();
    $('input.lookup-field-search').val(label).change();
    $(el).closest('.lookup-results-container').fadeOut("fast");
}

app.searchMessageRecipient = function (el) {

    const query = $(el).val();
    if (query.length >= 3 || query.length == 0) {

        var data = {
            action: 'message.findRecipient',
            payload: {
                query: rquery,
            }
        };

        app.appAjax('POST', data, app.ajaxRoute, data).then(function (response) {
            if (response.response == 'success') {
               console.log(response)
            }
        });

    }
}

app.saveDirectMessageReplay = function (el)
{
    const body = window.editor.value;


    var formData = new FormData(document.getElementById("replayContainer"));
    formData.append('action', 'message.storeReplay');


    app.appAjax('POST', formData, app.ajaxRoute, 'file').then(function (response) {
        if(response.response == 'success') {
            $('#messageReplies').html(response.extra.html);
            window.editor.value = "";
            $('#saveReplay').attr('disabled', 'disabled');
            app.initPlugins();
        }
    });
}

app.searchMessages = function (el, recipientId) {

    const query = $(el).val();
    if (query.length >= 3 || query.length == 0) {
        var data = {
            action: 'message.search',
            payload: {
                query: query,
                recipient: recipientId,
            }
        };

        app.appAjax('POST', data, app.ajaxRoute, data).then(function (response) {
            if (response.response == 'success') {
                $('#MessagesTable').html(response.extra.html);
            }
        });

    }

}

app.refreshMessagesList = function (el, recipientId) {

    const query = $(el).val();
    if (query.length >= 3 || query.length == 0) {
        app.loadingIconStart(el);
        var data = {
            action: 'message.search',
            payload: {
                query: null,
                recipient: recipientId,
            }
        };
        app.appAjax('POST', data, app.ajaxRoute, data).then(function (response) {
            if (response.response == 'success') {
                $('#MessagesTable').html(response.extra.html);
            }
        });
    }
    app.loadingIconStop(el);
}


app.addApplicationSubmission = function (el , studentID) {
    var title = $(el).data('title');
    var route = $(el).data('route');

    var i = 0;
    var id = 'add-application-submission-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {
        button : 'Add',
        cancelButton : 'Cancel'
    });
    $('.btn-ok#' + id).click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i == 0) {
            $(this).attr('disabled', 'disabled');
            $(this).html("Processing....");
            e.preventDefault();
            // var count = $('#form-modal .modal-body form .countSch').val();
            $('#form-modal .modal-body form .countSch').remove();

            var formData = $('#form-modal .modal-body form').serialize();
            var actionRoute = $('#form-modal .modal-body form').attr('action');


            app.appAjax('POST', formData, actionRoute).then(function (response) {
                if (response.response == "success") {

                    app.redirect(response.url);

                    $('#' + id + ' .modal-body form').remove();
                    i++;
                    return false;
                } else {
                    toastr.error(response.message);
                    $(this).removeAttr('disabled');
                    $(this).html("Add");
                }

            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
                i = 0;

                $(this).removeAttr('disabled');
                $(this).html("Add");
            });
            // Remove Form
        }
    });
};


app.addAdmission = function (route, title) {
    var i = 0;
    var id = 'add-admission-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );
    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal

        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");
                var value = app.getFieldValue($(this));
                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }
                formData[name] = value;
            });


            var actionRoute = $('#form-modal .modal-body form').attr('action');



            app.appAjax('POST', formData, actionRoute).then(function (data) {

                // success

                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    // Get Wrapper
                    $('tbody#admissions').append(data.extra.html);
                }

                $('#' + id + ' .modal-body form').remove();
                i++;
                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });

            // Remove Form
        }

    });

}
app.setupPlugin = function (route, title, authorize) {

    var i = 0;

    var id = 'setup-plugin-form-' + (1 + Math.floor(Math.random() * 1000));

    app.openModal(id, route, title , {});

    $('.btn-ok#' + id).click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");
                var value = app.getFieldValue($(this));
                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }
                formData[name] = value;
            });


            var actionRoute = $('#form-modal .modal-body form').attr('action');



            app.appAjax('POST', formData, actionRoute).then(function (data) {

                // success
                if (data.response == "success") {
                    if (data.extra.status == 'ACTIVE') {
                        $('.plugin-status').removeClass('text-danger').addClass('text-success').text(data.extra.status);
                    } else {
                        $('.plugin-status').removeClass('text-success').addClass('text-danger').text(data.extra.status);
                    }

                    $('#form-modal').modal('hide');
                }

                $('#' + id + ' .modal-body form').remove();
                i++;
                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });

            // Remove Form
        }

    });

}


app.toggleStatus = function (el) {

    var prop = $(el).data('prop'),
        model = $(el).data('model'),
        controller = $(el).data('controller'),
        id = $(el).data('id');
    var data = {
            action: controller + '.toggle',
            payload: {
                prop: prop,
                model: model,
                id: id
            }
        };

    app.appAjax('POST', data, app.ajaxRoute, data).then(function (response) {


        if (response.response == 'success') {
            if (response.extra.status) {
                $(el).find('i').removeClass('text-danger').addClass('text-success');
            } else {
                $(el).find('i').removeClass('text-success').addClass('text-danger');
            }
        }
    });


}

app.exportSubmissions = function (el) {

    var id = 'edit-field-form-' + (1 + Math.floor(Math.random() * 1000));
    var route = $(el).data('route');
    var file = $(el).data('file');
    var title = $(el).data('title');
    var filters = {};
    var parameters = '?payload=ert';

    var date_range = $('#calendarRanges').val();
    var start_date = '';
    var end_date = '';
    if ($('.calendarRanges').length > 0 && calendarRanges !== '') {
        var dates = date_range.split(" - ");
        start_date = dates[0];
        end_date = dates[1];
        if (start_date < end_date) {
            filters['dates'] = {
                start_date: start_date,
                end_date: end_date,
            }
        }
    }
    $('.filterField').each(function () {
        var val = $(this).val();
        var name = $(this).attr("name");
        if (typeof val !== 'undefined') {
            filters[name] = val;
        }
    });

    var search = $('#submissions_table_filter input[type=search]').val();
    if (search && search !== null) {
        filters['search'] = search;
    }

    if($('#submissions_table').data('archived')){
        filters['submission_status'] = ['Archived'];
    }else{
        filters['submission_status'] = ['Archived', "Started", "Updated", "Submitted", "Account Created", "Approved", "Contract Sent", "Contract Voided", "Contract Delivered", "Contract Signed", "Contract Declined", "Locked", "Unlocked","Unlock Requested"];
    }

    parameters += '&file=' + file;

    filters = encodeURI(JSON.stringify(filters));
    parameters += '&filters=' + filters;

    route += parameters;

    app.openModal(id, route, title, {} );

}

app.exportStudents = function (el) {

    var route = $(el).data('route');
    var file = $(el).data('file');
    var title = $(el).data('title');
    var search = $("#students_table_filter input[type='search']").val()

    var groups = $("#group").val();
    var programs = $("#program").val();

    var date_range = $('#calendarRanges').val();
    var start_date = '';
    var end_date = '';
    if ($('.calendarRanges').length > 0 && calendarRanges !== '') {
        var dates = date_range.split(" - ");
        start_date = dates[0];
        end_date = dates[1];
    }

    parameters = '?file=' + file + '&search=' + search + '&title=' + title + '&start_date=' + start_date + '&end_date=' + end_date

    if(groups.length){
        parameters += '&' + groups.map( a => 'groups[]=' + a).join('&')
    }

    if(programs.length){
        programs += '&' + programs.map( a => 'programs[]=' + a).join('&')
    }

    route += encodeURI(parameters);
    downloadLink(route)
}

const downloadLink = (url) => {
    const a = document.createElement("a");
    document.body.appendChild(a);
    a.style = "display: none";
    a.href = url;
    a.click();
    a.remove();
}

app.reorderColumns = (userOrder = null) => {

    let reorder = (order) => {
        window.submissions_table.colReorder.reset();

        for (let i = 0; i < window.submissions_table.rows().data().length + 2; i++) {
            window.submissions_table.column(i).visible(order.includes(i));
        }
        console.log('applying new order')
        console.log(order);
        window.submissions_table.colReorder.order(order);
    }

    if(userOrder) {
        reorder([0, 1,2, ...userOrder, 14].map( a => +a))

        for (let i = 1; i < userOrder.length; i++) {
            $("#item-" + userOrder[i]).insertAfter($("#item-" + userOrder[i - 1]));
        }
    }
    else{
        $('#reorder-columns').modal('show');
        $('.btn-ok').click(function (e) {
            e.preventDefault();

            cols = [];
            $( ".sortable" ).children().each(function () {
                if($(this).is(":visible"))
                    cols.push(+$(this).attr("id").replace('item-',''));
            });

            reorder([0, 1, 2, ...cols, 14])

            usersettings = {
                'category': 'submission_table_columns',
                'settings': cols
            }

            app.appAjax('PUT', usersettings, '/usersettings').then(function (data) {

            });

            $('#reorder-columns').modal('hide');
        });
    }
}

/**Toggle Select all for Bulk Actions */
app.toggleSelectAll = function (el) {
    if ($(el).is(':checked')) {
        $("[name='multi-select']").prop("checked", true);
        $('.toggle-bulk-actions').prop('disabled', false);
    } else {
        $("[name='multi-select']").prop("checked", false);
        $('.toggle-bulk-actions').prop('disabled', true);
    }
}

/**Toggle Row selection for Bulk Actions */
app.selectRow = function (el) {
    var selected = $("input:checkbox[name=multi-select]:checked").length;
    if (selected > 0) {
        $('.toggle-bulk-actions').prop('disabled', false);
    } else {
        $('.toggle-bulk-actions').prop('disabled', true);
    }

}
//@TODO Make Table Name Dynamic
/**Bulk Edit */
app.bulkEdit = function (route, title) {
    var i = 0;
    var id = 'bulk-edit-form-' + (1 + Math.floor(Math.random() * 1000));
    var selected = [];
    $("input:checkbox[name=multi-select]:checked").each(function () {
        selected.push($(this).val());
    });

    if (route.includes("?")) {

        route = route + '&selected=' +
        encodeURIComponent(selected);

    } else {
        route = route + '?selected=' +
        encodeURIComponent(selected);
    }
    app.openModal(id, route, title, {} );


    $('.btn-ok#' + id).click(function (e) {
        // to prevent duplicate request caused by the modal
        if (i == 0) {
            e.preventDefault();
            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");
                var value = app.getFieldValue($(this));
                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }
                formData[name] = value;
            });
            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('POST', formData, actionRoute).then(function (data) {
                console.log(data)
                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                    // @TODO MAKE TABLE NAME DYNAMIC
                    window[data.extra.data_table].draw();
                    toastr.success(data.extra.message);
                    location.reload();
                }

                $('#' + id + ' .modal-body form').remove();
                i++;
                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors
                toastr.error('Please, Try again later', 'Somthing went wrong!');
            });
        }

    });
}

app.toggleDetails = function(e){
    details = $(e.target).parents('tr').find(".details");
    if(details.height()){
        details.css('max-height', '0');
        $(e.target).attr('src', '/assets/images/icons/plus.png')
    }else{
        details.css('max-height', '500px');
        $(e.target).attr('src', '/assets/images/icons/minus.png')
    }
}

app.bulkDelete = function (route, reloadOnDelete = false) {
    var i = 0;
    $('#confirm-delete').modal('show');
    $('.btn-ok').click(function (e) {
        if (i == 0) {
            i++;
            e.preventDefault();
            var selected = [];
            $("input:checkbox[name=multi-select]:checked").each(function () {
                var target = '';
                if($(this).attr('target')){
                    target = $(this).attr('target');
                }
                selected.push({'selected': $(this).val(), 'target': target});
            });
            var data = {
                selected: selected
            }
            app.appAjax('DELETE', data, route).then(function (data) {
                // success
                if (data.response == "success") {
                    //close Modal
                    $('#confirm-delete').modal('hide');
                    if(window[data.extra.data_table]){
                        window[data.extra.data_table].draw();
                    }
                    toastr.success(data.extra.message);
                    if(reloadOnDelete){
                        setTimeout(() => {
                            location.reload()
                        }, 2000);
                    }
                } else {
                    toastr.error('Please, Try again later', 'Somthing went wrong!');
                    $('#confirm-delete').modal('hide');

                }
            }).fail(function () {
                $('#confirm-delete').modal('hide');
                toastr.error('Please, Try again later', 'Somthing went wrong!');
            });
        }
    });
};


app.bulkArchive = function () {

    var selected = [];
    $("input:checkbox[name=multi-select]:checked").each(function () {
        selected.push($(this).val());
    });
    app.appAjax('POST', { selected }, '/submissions/bulk-archive', 'json').then(function (data) {
        if (data.response == "success") {
            window.submissions_table.clear();
            window.submissions_table.ajax.reload();
            toastr.success(data.message);
        } else {
            toastr.error('Please, Try again later', 'Somthing went wrong!');

        }
    }).fail(function () {
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    });
};

app.bulkUnarchive = function () {

    var selected = [];
    $("input:checkbox[name=multi-select]:checked").each(function () {
        selected.push($(this).val());
    });
    app.appAjax('POST', { selected }, '/submissions/bulk-unarchive', 'json').then(function (data) {
        if (data.response == "success") {
            window.submissions_table.clear();
            window.submissions_table.ajax.reload();
            toastr.success(data.message);
        } else {
            toastr.error('Please, Try again later', 'Somthing went wrong!');

        }
    }).fail(function () {
        toastr.error('Please, Try again later', 'Somthing went wrong!');
    });
};

// Open Modal
app.openModal = function (id, route, title , params) {


    $('#form-modal .modal-content .btn-ok').attr('id', id);

    if (params.button) {
        $('#form-modal .modal-content .btn-ok').html(params.button);
    }
    if (params.cancelButton) {
        $('#form-modal .modal-content .btn-danger').html(params.cancelButton);
    }

    $('#form-modal .modal-title').html(title);

    $('#form-modal .modal-body').load(route, function () {

        $('#form-modal').modal('show');


        app.initPlugins();
        $('select:disabled').prop("checked", true).trigger("change");
    });
}

app.openWideModal = function (id, route, title) {

    $('#wide-modal .modal-content .btn-ok').attr('id', id);
    $('#wide-modal .modal-title').html(title);

    $('#wide-modal .modal-body').load(route, function () {
        $('#wide-modal').modal('show');
        app.customSelect();
        app.initTextEditor();
        //app.tinyMCE();
        app.resetValidation();
        app.fileInput();
        app.radioSwitch();
        app.dateTimePicker();
        $('select:disabled').prop("checked", true).trigger("change");
    });
}
app.initiateIntegrationAuthorization = function (el) {
    var form = $("[name='plugin-auth-form']");

    event.preventDefault();
    var formData = {};

    $('.ajax-form-field').each(function () {
        var name = $(this).attr("name");
        var value = app.getFieldValue($(this));
        if ($(this).is(':required') && !value) {
            $(this).addClass('is-invalid');
        }
        formData[name] = value;
    });
    var actionRoute = $('#form-modal .modal-body form').attr('action');

    app.appAjax('POST', formData, actionRoute).then(function (data) {

        //var authLink = $(el).data('auth-link');
        var authLink = $('[name="base_url"]').val();
        authLink += "/public/oauth/v2?response_type=code";


        var appendParams = $(el).data('append').split(",");
        appendParams.forEach(param => {
            var val = $('[name="' + param + '"]').val();
            if (typeof val !== 'undefined') {
            }
            authLink += '&' + param + '=' + val;
        });

        authLink += '&state=' + $('meta[name="_token"]').attr('content');
        alert(authLink);
        var generator = window.open(authLink, '_blank', 'height=500,width=500');

        if (!generator || generator.closed || typeof generator.closed == 'undefined') {
            alert("Please allow popup window");
        }
    }).fail(function (error) {
        var errors = $.parseJSON(error.responseText).errors
        app.displayErrors(errors, '');
    });;
}


app.integrationauth = function () {
    alert("auth");
}


app.initDocumentBuilderTable = function () {
    $('#documentBuilder_list_table').DataTable({
        processing: true,
        paging: true,
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        responsive: true,
        'columnDefs': [{
            'targets': [-1],
            orderable: false,
        },
        {
            'targets': [0],
            orderable: false,
        },
        {
            responsivePriority: 1,
            targets: 1
        },
        {
            responsivePriority: 2,
            targets: -1
        }
        ],
        "order": [
            [1, "desc"]
        ],
    });
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');

    setTimeout(function () {
        var firstColTableFilter = $('#datatableNewFilter #lenContainer');

        var searchConatiner = $('#documentBuilder_list_table_filter');

        //Remove search label;
        var sInput = $("#documentBuilder_list_table_filter").find('input');
        var sLabel = $("#documentBuilder_list_table_filter").find('label');
        sLabel.replaceWith(sInput);


        var iPaginate = $('#documentBuilder_list_table_wrapper .dataTables_length select');
        console.log(iPaginate);

        $(iPaginate).detach().appendTo(firstColTableFilter);
        $(searchConatiner).detach().appendTo(firstColTableFilter);

        $('#documentBuilder_list_table_wrapper .dataTables_length').hide();

        if (window.location.hash) {
            window.location.href = window.location.hash;
        }

    }, 900);
};

app.initDocumentBuilderTable()

// Global Ajax Request
app.appAjax = function (method, data, route, dataType) {
    // zDefault DataType is Data
    dataType = dataType || 'data';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content'),
            '_token': $('meta[name="_token"]').attr('content')
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

    //console.log(args);
    return $.ajax(args);
};

app.repeatElement = function (action, wrapperClass, backEnd) {


    if (typeof backEnd == 'undefined' || backEnd == false) {
        var backEnd = false;
    } else {
        var backEnd = true;
    }

    var payload = $(event.target).data('payload');

    if (typeof payload == 'undefined') {
        payload = {};
    }

    var count = $('.' + wrapperClass + " .repeated_fields").length;
    var order = app.order++;
    if (count > 0) {
        order = count + 1;
    }

    var data = {
        action: action,
        payload: {
            order: order,
            backEnd: backEnd,
            payload: payload
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        $('.' + wrapperClass).append(data.extra.html);
        return false;
    });
    return false;
}

app.removeRepeatedElement = function (el, order) {

    if (order === undefined) {
        $('.' + el).remove();
    } else {
        $('[data-repeated="' + order + '"]').fadeOut('meduim', function () {
            $(this).remove();
        })
    }
};

// to be Deprecated
app.room = 1;

app.repeat_fields_new = function (elem) {
    app.room++;

    var wrapperId = elem.id;
    //var firstObj = $('#'+wrapperId);
    var objTo = $(".repeated_fields_new:first");

    //firstObj.addClass('repeated_fields_new');

    var clonedObj = objTo.clone();

    var clonedHTML = clonedObj.html();

    var removeClass = 'removeclass_' + app.room;

    clonedObj.removeClass('repeated_fields_new').addClass(removeClass);

    // Remove Add Button
    clonedObj.find('.action_button_new').remove();

    var removeButton = ` <div class="form-group"> <button class="btn btn-danger" type="button" onclick="app.deleteElementsRow(this, 'logo-row')" > <i class="fa fa-minus"> </i> </button> </div> `;
    clonedObj.find('.action_wrapper_new').append(removeButton);
    var locale = ` <select name="logo_locale[]" id="logo_locale" class="form-control form-control-lg" required>
                <option value=""> Language </option>
                <option value="en"> English </option>
                <option value="fr"> French </option>
                <option value="gr"> German </option>
                <option value="es"> Spanish </option>
            </select>`;
    clonedObj.find('.locale-select').append(locale);


    $('.repeated_fields_new_wrapper#' + wrapperId).append(clonedObj);
    $('.' + removeClass + ' #logo_img').remove();
    $('.' + removeClass + ' input').val('');
    $('.' + removeClass + ' .select2').remove();
}

app.repeat_fields = function () {
    app.room++;

    var objTo = $('.repeated_fields');
    console.log(objTo);
    var clonedObj = objTo.clone();

    var clonedHTML = clonedObj.html();

    var removeClass = 'removeclass_' + app.room;

    clonedObj.removeClass('repeated_fields').addClass(removeClass);

    // Remove Add Button
    clonedObj.find('.action_button').remove();

    var removeButton = `<div class="form-group">

    <button class="btn waves-effect waves-light btn-outline-danger float-right btn-lg ml-2 mb-3" type="button" onclick="app.removeRepeatedElement('${removeClass}');"> <i class="fa fa-minus"> </i> </button> </div> `;

    clonedObj.find('.action_wrapper').append(removeButton);

    $('.repeated_fields_wrapper').append(clonedObj);

    //Reset All fields Value
    $('.repeated_fields  input').each(function () {
        $(this).val("");
    })
}

// @ TODO refactor
app.room = 1;
app.fieldRepeater = function (el) {
    var parent = $(el).data('repeat');
    app.room++;
    var objTo = $('#' + parent);
    var clonedObj = objTo.clone();

    clonedObj.removeClass('repeated_fields').addClass('removeclass_' + app.room);

    clonedObj.find('.action_button').remove();
    var removeButton = '<div class="form-group m-t-25"><button class="btn btn-danger" type="button" onclick="remove_education_fields(' + app.room + ');"><i class="fa fa-minus"></i></button></div>';
    clonedObj.find('.action_wrapper').append(removeButton);

    var clonedHTML = clonedObj.html();

    // Container
    var container = $('#' + parent + "_wrapper");
    container.append(clonedHTML);
    app.dateTimePicker();
}

app.accordion = function () { }

app.fileInput = function () {

    $('.custom-file-input').change(function () {

        var id = $(this).attr('id');

        var file = $(this).prop('files');



        $('.label_' + id).text(file[0].name);

    });

}

app.initTextEditor = function (className) {

    if ($('.text_editor').length > 0) {
        $('.text_editor').each(function () {
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
            //editor.setReadOnly(true);
        })
    }

}




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

}

app.redirect = function (link, newWindow = null) {

    if (newWindow == null) {
        window.location.href = link;
    } else {
        window.open(link, '_blank').focus();
    }

    return false;
}

app.codeHighLight = function () {

    hljs.initHighlightingOnLoad();

}

app.spin = function (el) {
    el.prepend('<i class="fa fa-spinner fa-spin"></i>  ')
}

app.stopSpinning = function (el) {
    el.find('i.fa-spinner').remove();
}

/*Agency - Agents Managment */
app.deleteAgent = function (el) {

    var agentId = $(el).data('id');
    $('a.delete-icon[data-agent-container="' + agentId + '"]').removeClass().addClass('fas fa-spin fa-spinner text-muted control-icon active-icon');

    var data = {
        action: 'agency.deleteAgent',
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
        action: 'agency.resendActivationEmail',
        payload: {
            agentId: agentId,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {


        if (data.response == 'success' && data.status == 200) {
            $('a.active-icon[data-agent-container="' + agentId + '"]').removeClass().addClass('fas fa-user-circle text-warning control-icon active-icon');

            /* swal({
                title: "Sent Successfuly!",
                text: "The activation email sent successfuly",
                icon: "success",
                buttons: true,
                dangerMode: false,
            })
                .then((willDelete) => {

                    $('a.active-icon[data-agent-container="' + agentId + '"]').removeClass().addClass('fas fa-user-circle text-warning control-icon active-icon');


                }); */
        }

    });

}

app.toggleAgencyStatus = function (el) {
    var agencyId = $(el).data('agency-id');
    var data = {
        action: 'agency.toggleStatus',
        payload: {
            agencyId: agencyId,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success' && data.status == 200) {
            // Link
            var link = $('a[data-agency-id="' + agencyId + '"]');
            if (link.hasClass('approved')) {
                link.removeClass('approved').addClass('unapporved');
                link.html('<i class="icon-check text-success"></i><span class="icon-text">Approve</span>')
            } else {
                link.removeClass('unapporved').addClass('approved');
                link.html('<i class="icon-close text-danger"></i><span class="icon-text">Unapprove</span>');
            }

            // Bullet
            //approve-bullet
            var row = $('tr[data-agency-id="' + agencyId + '"] td.approve-bullet');
            //console.log(data.extra.status);
            if (data.extra.status) {
                row.find('i.unapporved').addClass('hidden');
                row.find('i.approved').removeClass('hidden');
            } else {
                row.find('i').addClass('hidden');
                row.find('i.unapporved').removeClass('hidden');
            }
        }

    });
};

app.toggleApplicationPublishStatus = function (el) {
    var application_id = $(el).data('application-id');
    var data = {
        action: 'application.togglePublishStatus',
        payload: {
            application_id: application_id,
        }
    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success' && data.status == 200) {
            // Link
            var span = $('span[data-application-id="' + application_id + '"]');

            if (span.hasClass('publishable')) {

                $('span[data-application-id="' + application_id + '"].publishable').removeClass('publishable').addClass('unpublishable').text('Unpublished');

            } else if (span.hasClass('unpublishable')) {
                $('span[data-application-id="' + application_id + '"].unpublishable').removeClass('unpublishable').addClass('publishable').text('Published');

            } else if (span.hasClass('publish-button')) {

                $('span[data-application-id="' + application_id + '"].publish-button').text('Unpublish').removeClass('publish-button').addClass('unpublish-button');

            } else if (span.hasClass('unpublish-button')) {
                $('span[data-application-id="' + application_id + '"].unpublish-button').text('Publish').removeClass('unpublish-button').addClass('publish-button');
            }


            // Bullet
            //approve-bullet
            var row = $('div[data-application-id="' + application_id + '"] div.approve-bullet');

            if (data.extra.status) {
                row.find('i.unapporved').addClass('hidden');
                row.find('i.approved').removeClass('hidden');
            } else {
                row.find('i').addClass('hidden');
                row.find('i.unapporved').removeClass('hidden');
            }
        }

    });
}


app.toggleAdminPrivileges = function (el) {
    var agentId = $(el).data('id');


    var container = $('a.admin-icon[data-agent-container="' + agentId + '"]');
    var wasAdmin = container.data('is-admin');


    container.removeClass().addClass('fas fa-spin fa-spinner text-muted control-icon admin-icon');

    var data = {
        action: 'agency.toggleIsAdmin',
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
        action: 'agency.rolPrivileges',
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

app.actionUser = function (el, action) {
    var route = $(el).data('route');

    if (action === 'delete') {
        $('#confirm-delete').modal('show');
    } else {
        $('#confirm-action').modal('show');
    }

    $('.btn-ok').click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: route,
            type: "get",
            dataType: 'json',
            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            location.reload();
        });
    });
};

app.paymentType = function (payment_type, cssClass) {
    if (payment_type !== '' && $('.' + cssClass).html().trim() === '') {
        var data = {
            action: 'Application.' + payment_type,
            payload: {
                cssClass: cssClass
            }
        };
        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

            if (data.response == 'success' && data.status == 200) {
                $('.' + cssClass).append(data.extra.html);
                $(".select2").select2();
            }
        });
    }
};

app.addInstallment = function (cssClass) {
    if (cssClass !== '') {
        var data = {
            action: 'Application.installment',
            payload: {
                cssClass: cssClass
            }
        };
        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

            if (data.response == 'success' && data.status == 200) {
                $('.' + cssClass).append(data.extra.html);
                $(".select2").select2();
                app.dateTimePicker();
            }
        });
    }
};

app.removeInstallment = function (el) {
    $(el).closest('.repeated_fields').remove();
};

app.deletePaymentType = function (cssClass) {
    $('#confirm-delete').modal('show');

    $('.btn-ok').click(function (e) {
        $('#confirm-delete').modal('hide');
        $('.' + cssClass).empty();
    });
};

app.addEventFullCalendar = function (route, title) {
    var i = 0;
    var id = 'add-event-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );
}

app.updateStudentStage = function (route, title, profile) {
    var i = 0;
    var id = 'change-stage-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );

    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal

        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));

                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }

                formData[name] = value;

            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('PUT', formData, actionRoute).then(function (data) {

                // success

                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    // Get Wrapper

                    if (profile == 'profile') {
                        location.reload();
                    } else {
                        $('tr[data-student-id="' + data.extra.student_id + '"]').hide();
                    }
                }

                $('#' + id + ' .modal-body form').remove();

                i++;

                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors

                app.displayErrors(errors, '');
            });

            // Remove Form
        }

    });

};

app.updateUserRoles = function (route, title, profile) {
    var i = 0;
    var id = 'change-roles-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );

    $('.btn-ok#' + id).click(function (e) {
        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));

                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }

                formData[name] = value;

            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('PUT', formData, actionRoute).then(function (data) {

                // success

                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    // Get Wrapper
                    if (profile == 'profile') {
                        location.reload();
                    }
                }

                $('#' + id + ' .modal-body form').remove();

                i++;

                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors

                app.displayErrors(errors, '');
            });

            // Remove Form
        }
    });
};

app.updateApplicationStatus = function (route, title, profile) {
    var i = 0;
    var id = 'change-status-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );

    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal

        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));

                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }

                formData[name] = value;

            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('PUT', formData, actionRoute).then(function (data) {

                // success

                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    // Get Wrapper

                    if (profile == 'profile') {
                        location.reload();
                    } else {
                        $('tr[data-student-id="' + data.extra.student_id + '"]').hide();
                    }
                }

                $('#' + id + ' .modal-body form').remove();

                i++;

                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors

                app.displayErrors(errors, '');
            });

            // Remove Form
        }

    });

};

app.addStudentToCohortForm = function (route, title) {
    var i = 0;
    var id = 'add-cohort-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );

    $('[id^=add-cohort-form-]').click(function (e) {
        $('#form-modal').modal('hide');
        location.reload();
    });

    $('.form_modal > .modal-dialog > .modal-content > .modal-body > .modal-footer > .btn-danger').click(function (e) {
        $('#form-modal ').modal('hide');
        location.reload();
    });
}

app.updateStudentUuid = function (route, title) {
    var i = 0;
    var id = 'change-uuid-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );

    $('.btn-ok#' + id).click(function (e) {

        // to prevent duplicate request caused by the modal

        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));

                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }

                formData[name] = value;

            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('PUT', formData, actionRoute).then(function (data) {

                // success

                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    location.reload();

                }

                i++;

                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors

                app.displayErrors(errors, '');
            });

            // Remove Form
        }

    });
}

app.lessonCreate = function (route, title) {

    var i = 0;
    var title = (typeof title == 'undefined') ? "Edit Lesson" : title;
    var id = 'create-lesson-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );
    $('.btn-ok#' + id).click(function (e) {
        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));

                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }

                formData[name] = value;

            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('POST', formData, actionRoute).then(function (data) {
                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');
                }

                $('#' + id + ' .modal-body form').remove();

                i++;

                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors

                app.displayErrors(errors, '');
            });

            // Remove Form
        }

    });

};

app.attendanceCreate = function (route, title) {

    var i = 0;
    var title = (typeof title == 'undefined') ? "Add New Attendance" : title;
    var id = 'create-attendance-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );
    $('.btn-ok#' + id).click(function (e) {
        if (i == 0) {
            e.preventDefault();

            var formData = {};

            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));

                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                }

                formData[name] = value;

            });

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            app.appAjax('POST', formData, actionRoute).then(function (data) {
                // success
                if (data.response == "success") {
                    $('#form-modal').modal('hide');

                    // Get Wrapper

					swal({
                        icon: 'success',
                        text: 'Added successfully: ',
                    }).then(function () {
                        location.reload();
                    });
                    return true;
                }else if(data.response == "error"){
                    swal({
                        icon: 'error',
                        text: data.text,
                    });
                }

                return false;
            }).fail(function (error) {
                var errors = $.parseJSON(error.responseText).errors

                app.displayErrors(errors, '');
            });
        }

    });

};

app.addStudentToCohort = function () {
    var formData = {};
    $('.ajax-form-field').each(function () {

        var name = $(this).attr("name");

        var value = app.getFieldValue($(this));

        if ($(this).is(':required') && !value) {
            $(this).addClass('is-invalid');
            return;
        }

        formData[name] = value;

    });

    var actionRoute = $('#form-modal .modal-body form').attr('action');

    app.appAjax('PUT', formData, actionRoute).then(function (data) {

        // success

        if (data.response == "success") {
            // Get Wrapper
            var table = document.getElementById("group-body");
            var row = table.insertRow(0);
            var action = row.insertCell(0);
            var cohort = row.insertCell(1);
            var program = row.insertCell(2);
            var start_date = row.insertCell(3);
            var end_date = row.insertCell(4);
            action.innerHTML = '<a href="javascript:void(0)"' +
                ' onclick="app.deleteStudentFromGroup(\'/students/' + data.extra.student_id + '/cohort/' + data.extra.group_id + '/delete\')"' +
                ' class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"' +
                ' data-original-title="Delete">' +
                '<i class="icon-trash text-danger"></i>' +
                '</a>';
            cohort.innerHTML = '<span class="badge badge-secondary">' + data.extra.group + '</span>';
            program.innerHTML = '<span class="badge badge-secondary">' + data.extra.program + '</span>';
            start_date.innerHTML = data.extra.start_date;
            end_date.innerHTML = data.extra.end_date;

        } else if (data.response == "duplicate") {
            swal({
                icon: 'error',
                text: 'Students already belongs to this cohort',
            });
        }

        switch (data.response) {
            case "success":
                break;
            case "duplicate":
                break;
        }

        //$('#' + id + ' .modal-body form').remove();

        return false;
    }).fail(function (error) {
        var errors = $.parseJSON(error.responseText).errors
        app.displayErrors(errors, '');
    });
};

app.deleteStudentFromGroup = function (route) {
    var i = 0;
    $('#confirm-delete').modal('show');
    $('.btn-ok').click(function (e) {

        // to prevent duplicate request caused by the modal
        if (i == 0) {
            i++;
            e.preventDefault();

            app.appAjax('DELETE', '', route).then(function (data) {
                // success
                if (data.response == "success") {
                    // if (removedElement) {
                    //     // Hide Deleted Row
                    //     $('[' + removedElement + '="' + data.extra.removedId + '"]').fadeOut(function () {
                    //         $(this).remove();
                    //     });
                    // }
                    $('tr[data-group-id="' + data.extra.group_id + '"]').hide();
                    //close Modal
                    $('#confirm-delete').modal('hide');
                    return true;
                } else {
                    $('#confirm-delete').modal('hide');
                    swal({
                        icon: 'error',
                        text: 'Item could not be deleted, possibly has a dependency',
                    });
                }
            }).fail(function () {
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(errors, '');
            });
        }
    });
};

app.getCustomerInfo = function (route) {
    var student_id = $('[name ="customer"]').find(':selected').val();
    var formData = {};
    formData['student_id'] = student_id;

    app.appAjax('GET', formData, route).then(function (data) {

        // success
        if (data.response == "success") {
            // Get Wrapper
            $('[name ="customer_email"]').val(data.extra.email)
            $('[name ="billing_address"]').val(data.extra.address)
        }

        return false;
    }).fail(function (error) {
        var errors = $.parseJSON(error.responseText).errors
        app.displayErrors(errors, '');
    });
};

app.productsByCategory = function (el) {
    var category = $(el).val();
    var order = $(el).data('order');
    var field = $(el).data('field');

    if (category) {
        var dataProducts = {
            action: 'invoice.productsByCategory',
            payload: {
                category: category,
                order: order,
                field: field === 1 ? 1 : 0
            }
        };

        app.appAjax('POST', dataProducts, app.ajaxRoute).then(function (data) {
            app.customSelect();
            if (data.extra.product === '') {
                $('.product-' + order).html('No Educational Products in This Category');
            } else {
                $('.product-' + order).html(data.extra.product);
            }

            $(".select2").select2();
        });

    } else {
        $('.fieldValue').html('');
    }
};

app.amountByProduct = function (el) {
    var order = $(el).data('order');
    var id = $(el).val();

    if (id) {
        var dataProducts = {
            action: 'invoice.amountByProduct',
            payload: {
                id: id
            }
        };

        app.appAjax('POST', dataProducts, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.amount-' + order).val(data.extra.amount);
            $('.description-' + order).val(data.extra.description);
            app.fixInvoiceAmount();
        });

    } else {
        $('.fieldValue').html('');
    }
}

app.addProduct = function (route, title) {
    var i = 0;
    var id = 'create-product-form-' + (1 + Math.floor(Math.random() * 1000));
    app.openModal(id, route, title, {} );

    var formData = {};

    $('.btn-ok#' + id).click(function (e) {
        if (i == 0) {
            $('.ajax-form-field').each(function () {

                var name = $(this).attr("name");

                var value = app.getFieldValue($(this));

                if ($(this).is(':required') && !value) {
                    $(this).addClass('is-invalid');
                    console.log('invalid');
                }

                formData[name] = value;
                if (name == 'educational_product') {
                    formData['text'] = $('[name ="educational_product"]  option:selected').text();
                }
            });

            e.preventDefault();

            var table_body = document.getElementById("table-invoice-products-tbody");
            var row = table_body.insertRow(0);
            // var number = row.insertCell(0);
            var product = row.insertCell(0);
            var description = row.insertCell(1);
            var quantity = row.insertCell(2);
            var amount = row.insertCell(3);
            var action = row.insertCell(4);

            action.innerHTML = '<a href="javascript:void(0)"' +
                ' onclick="app.deleteProductFromInvoice()"' +
                ' class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"' +
                ' data-original-title="Delete">' +
                '<i class="icon-trash text-danger"></i>' +
                '</a>';
            // number.innerHTML = i + 1;
            product.innerHTML = formData['text'];
            description.innerHTML = formData['income_category'];
            quantity.innerHTML = formData['quantity'];
            amount.innerHTML = formData['amount'];

            var total = parseInt(formData['quantity']) * parseInt(formData['amount']);

            // var table = document.getElementById("table-invoice-products");
            // for (var i = 0, row; row = table.rows[i]; i++) {
            //     for (var j = 0, col; col = row.cells[j]; j++) {
            //         if (j == 2 )
            //         console.log();
            //     }
            // }

            var old_total = document.getElementById("total-table").innerText;
            console.log(old_total);

            if (old_total != null && old_total != '' && typeof old_total !== "undefined") {
                total = total + parseInt(old_total);
            }

            $('#total-table').text(total);
            $('#due-balance').text(total);

            $('#form-modal').modal('hide');
            i++;
        }
    });
};

app.addProductLine = function (el, custom_order) {
    var action = $(el).data('action');
    var container = $(el).data('container');
    var count = $(el).data('count');

    var order = null;
    if (!custom_order) {
        if (!isNaN(count) && count > app.order) {
            app.order = count;
        }
        order = app.order++;
    } else {
        order = custom_order;
    }


    var data = {
        action: action,
        payload: {
            'order': order
        }
    };

    console.log(data);
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {
            $('#' + container).append(data.extra.html);
            app.dateTimePicker();
            app.customSelect();
        }
    });
}

app.addDeleteProducts = function () {
    $("#table-invoice-products-tbody").html("");
    $('#total-table').text('0.00');
    $('#due-balance').text('0.00');
};

app.invoiceSave = function (el, next) {
    var student_id = $('[name="customer"]').val();
    var billing_address = $('[name="billing_address"]').val();
    var invoice_date = $('[name="invoice_date"]').val();
    var due_date = $('[name="due_date"]').val();
    var invoice_message = $('#invoice_message').val();
    var action = $(el).data('action');
    var invoice_id = $('#invoice_id').val();

    if (student_id == '' || student_id == null || student_id == undefined) {
        app.showMessage('error', 'Some mandatory fields are empty');
        return;
    }

    var invoice = {};
    var products = {};
    $('#table-invoice-products-tbody tr').each(function (i, row) {
        var formData = {};
        $(this).find('.ajax-form-field').each(function () {
            var name = $(this).attr("name");
            var value = app.getFieldValue($(this));
            if ($(this).is(':required') && !value) {
                $(this).addClass('is-invalid');
            }
            formData[name] = value;
        });
        products[i] = formData;
    });

    invoice['student_id'] = student_id;
    invoice['billing_address'] = billing_address;
    invoice['invoice_date'] = invoice_date;
    invoice['due_date'] = due_date;
    invoice['invoice_message'] = invoice_message;
    invoice['products'] = products;
    invoice['invoice_id'] = invoice_id;

    var data = {
        action: action,
        payload: {
            'invoice': invoice
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success' && data.status == 200) {
            if (next == 'new') {
                window.location.href = 'create';
            } else if (next == 'close') {
                window.history.back();
            }
            $('#invoice_text_id').text(data.extra.invoice_uid);
            $('#invoice_id').val(data.extra.invoice_uid);
            $('#invoice_save').html('Update');
            $('#invoice_close').text('Update and close');
            $('#invoice_new').text('Update and create other');
        }
    });
};

app.invoiceCancel = function () {
    window.history.back();
};

app.fixInvoiceAmount = function () {
    var total = 0;
    $('#table-invoice-products-tbody tr').each(function (i, row) {
        var amount = $(this).find($('[name="amount"]')).val();
        var quantity = $(this).find($('[name="quantity"]')).val();
        total = total + parseInt(quantity) * parseFloat(amount);
    });

    if (isNaN(total)) {
        total = (0).toFixed(2);
    }
    total = total.toFixed(2)

    $('#total-table').text(total);
    $('#due-balance').text(total);
}

app.changeProductQuantity = function () {
    app.fixInvoiceAmount();
}

app.changeProductAmount = function () {
    app.fixInvoiceAmount();
}

app.deleteProductFromInvoice = function (el) {
    $(el).closest("tr").remove();
}

app.showApiKey = function (key, el) {
    $('p.api_key_wrapper').html(key);
    $(el).addClass("hidden");
    $('.hide_button').removeClass("hidden");
}

app.hideApiKey = function (el) {
    $('p.api_key_wrapper').html("*****-*****-*****-*****");
    $(el).addClass("hidden");
    $('.show_button').removeClass("hidden");
}

app.deactivateApiKey = function () {
    var data = {
        action: 'apiKey.deactivate',
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success') {
            $('.api_key_card_wrapper').html(data.extra.html);
            toastr.success(data.extra.message);
        }
    });
}

app.generateApiKey = function () {
    var data = {
        action: 'apiKey.generate',
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success') {
            $('.api_key_card_wrapper').html(data.extra.html);
            toastr.success(data.extra.message);
        }

    });
}

app.changeDueDate = function (el) {
    var time_lapse = $(el).val();
    var due_date = $('[name="due_date"]');

    var result = moment().add(time_lapse, 'days').format("YYYY-MM-DD");

    due_date.val(result);
}

app.getCustomerPaymentInfo = function (route) {
    var student_id = $('[name ="customer"]').find(':selected').val();

    if (student_id == '' || student_id == null || student_id == undefined) {
        app.displayInvoices('');
        return;
    }

    var formData = {};
    formData['student_id'] = student_id;

    app.appAjax('GET', formData, route).then(function (data) {

        // success
        if (data.response == "success") {
            // Get Wrapper
            $('[name ="customer_email"]').val(data.extra.email)
            $('[name ="billing_address"]').val(data.extra.address)

            app.displayInvoices(data.extra.invoices);
        }

        return false;
    }).fail(function (error) {
        var errors = $.parseJSON(error.responseText).errors
        app.displayErrors(errors, '');
    });
};

app.displayInvoices = function (invoices) {
    $('#table-payment-invoices-tbody').html('');
    $('#table-payment-invoices-tbody').append(invoices);
}

app.paymentSave = function (el) {
    var student_id = $('[name="customer"]').val();
    var payment_date = $('[name="payment_date"]').val();
    var payment_method = $('[name="payment_method"]').val();
    var reference_no = $('[name="reference_no"]').val();
    var payment_message = $('[name="payment_message"]').val();
    var action = $(el).data('action');
    var payment_id = $('#payment_id').val();

    if (student_id == '' || student_id == null || student_id == undefined) {
        return;
    }

    var payment = {};
    var invoices = {};

    $('#table-payment-invoices-tbody tr').each(function (i, row) {
        var formData = {};
        $(this).find('.ajax-form-field').each(function () {
            var name = $(this).attr("name");
            var value = app.getFieldValue($(this));
            if ($(this).is(':required') && !value) {
                $(this).addClass('is-invalid');
            }
            formData[name] = value;
        });

        $(this).find(':hidden').each(function () {
            var name = $(this).attr("name");
            var value = app.getFieldValue($(this));
            formData[name] = value;
        });

        invoices[i] = formData;
    });

    payment['invoices'] = invoices;
    payment['student_id'] = student_id;
    payment['payment_date'] = payment_date;
    payment['payment_method'] = payment_method;
    payment['reference_no'] = reference_no;
    payment['payment_id'] = payment_id;
    payment['payment_message'] = payment_message;

    var data = {
        action: action,
        payload: {
            'payment': payment
        }
    };

    console.log(data);

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
        if (data.response == 'success' && data.status == 200) {
            window.history.back();
        }
    });
}

app.paymentCancel = function () {
    window.history.back();
}

app.changeInvoiceAmount = function (el) {
    var amount = $(el).val();
    var open_balance = $(el).closest('td').prev().html();

    if (parseFloat(amount) > parseFloat(open_balance)) {
        $(el).val(parseFloat(open_balance));
    }
    app.fixPaymentAmount();
}

app.fixPaymentAmount = function () {
    var total = 0;

    $('#table-payment-invoices-tbody tr').each(function (i, row) {
        var payment = $(this).find($('[name="payment"]')).val();

        if (isNaN(parseFloat(payment))) {
            payment = (0).toFixed(2);
        }

        total = total + parseFloat(payment);
    });

    if (isNaN(total)) {
        total = (0).toFixed(2);
    }

    total = parseFloat(total);
    $('#amount-received').text(total);
}

app.renderReviewPage = function (el) {
    var i = 0;
    $(el).each(function () {
        var url = $(this).data('route');
        var revId = $(this).attr('id');
        var data = {};
        app.appAjax('POST', data, url).then(function (response) {
            if (response.status == 200) {
                $('.review-page#' + revId).html(response.extra.html);
                app.editable();
            }
        });
        i++;
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

            //Update by Quin add toggle click 3/22/2022
            if ($(this).hasClass('singleClick')) {
                var toggleTrigger = 'click';
            } else {
                var toggleTrigger = 'dblclick';
            }

            $.ajaxSetup({
            headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $(this).editable({
                placement: 'right',
                toggle: toggleTrigger,
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

            $.fn.editable.defaults.params = function (params) {
                params._token = $("meta[name=token]").attr("content");
                return params;
            };

        });

    }
};

app.getCustomfieldForm = function (el) {
    var customfield_id = $(el).val();
    var wrapper = $('#customfield-data');

    var data = {
        action: 'customfield.getCustomfieldForm',
        payload: {
            customfield_id: customfield_id,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {
            wrapper.append(data.extra.html);
        }

    });
};

app.enablePrograms = function (el) {
    if (el.checked) {
        $('.program_wrapper').show();
        //$("[name='courses_check']").removeAttr("checked");
        $('#courses_check').prop("checked", false);
        $('.course_wrapper').hide();
    } else {
        $('.program_wrapper').hide();
    }
};

app.enableCourses = function (el) {
    if (el.checked) {
        $('.course_wrapper').show();
        $('#programs_check').prop("checked", false);
        $('.program_wrapper').hide();
    } else {
        $('.course_wrapper').hide();
    }
};

app.downloadSubmissionExcel = function (filters) {
    console.log(filters);
};

app.loadAuthLayout = function(el)
{
    var layout = $(el).val();
    var wrapper = $('#auth-layout-container');
    var disabled = $(el).prop("disabled");
    var data = {
        action: 'setting.getAuthLayout',
        payload: {
            layout: layout,
            disabled: disabled,
        }
    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {
            console.log(data.extra.html);
            wrapper.html(data.extra.html);
            app.initTextEditor();
            app.customSelect();
            app.colorPicker();
        }

    });

}

function closeOption(id){
    $("#col-" + id).prop("checked", false)
    $("#item-" + id).hide()
}

app.showArchived = function(){
    $('#archived-text').addClass("show");
    $('#archived-text').removeClass("hide");
    $('#submissions_table').data('archived', true);
    window.submissions_table.ajax.reload(() => {
        $('.archive-btn').hide();
        $('.unarchive-btn').addClass("show");
        $('.unarchive-btn').removeClass("hide");
        $('#show-archived').hide();
        $('#show-unarchived').addClass("show");
        $('#show-unarchived').removeClass("hide");
        $('#bulk-activate').addClass("show");
        $('#bulk-activate').removeClass("hide");
        $('#bulk-archive').addClass("hide");
        $('#bulk-archive').removeClass("show");
        $('#bulk-archive').attr("show", false);
    }, true);
}

app.hideArchived = function(){
    $('#archived-text').addClass("hide");
    $('#archived-text').removeClass("show");
    $('#submissions_table').data('archived', false);
    window.submissions_table.ajax.reload(
        () => {
            $('#show-archived').show();
            $('#show-unarchived').addClass("hide");
            $('#show-unarchived').removeClass("show");
            $('.archive-btn').show();
            $('.unarchive-btn').addClass("hide");
            $('.unarchive-btn').removeClass("show");
            $('#bulk-activate').addClass("hide");
            $('#bulk-activate').removeClass("show");
            $('#bulk-archive').addClass("show");
            $('#bulk-archive').removeClass("hide");
            $('#bulk-archive').attr("show", true);
        }
    );
}

app.loadSubmissionsTable = function(){
    window.submissions_table = $('#submissions_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        colReorder: true,
        dom: "<'row'<'col-sm-3'lf>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6'p>>",
        // dom: 'Bfrtip',
        // buttons: [
        //     'excel', 'csv'
        // ],
        "language": {
            search: "_INPUT_",
            searchPlaceholder: "Search...",
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/" + $('#submissions_table').data('i18n') + ".json"
        },
        "ajax": {
            "url": $('#submissions_table').data('route'),
            'beforeSend': function (request) {

                request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="_token"]').attr('content'));
            },
            'data': function (data) {
                // Read values
                var date_range = $('#calendarRanges').val();
                var start_date = '';
                var end_date = '';
                if ($('.calendarRanges').length > 0 && calendarRanges != '') {
                    var dates = date_range.split(" - ");
                    start_date = dates[0];
                    end_date = dates[1];
                }
                // var applications_id = $("[name='application[]']").val();
                // var statuses_id = $("[name='status[]']").val();
                var applications_id = $('#applications').val();
                var statuses_id = $('#status').val();
                var progress = $('#progress').val();
                var student_statuses = $('#student_statuses').val();
                data.start_date = start_date;
                data.end_date = end_date;
                data.applications = applications_id;
                data.statuses = statuses_id;
                data.progress = progress;
                data.student_statuses = student_statuses;
                data.groupbyemail = $('#groupbyemail').is(':checked');
                data.archived = $('#submissions_table').data('archived');
            }
        },
        "order": [
            [12, "desc"]
        ],
        columns: [{
            data: null,
            "render": function (data, type, row, meta) {
                return '';
            },
            "orderable": false
        },
        {
            data: 'select',
            "render": function (data, type, row, meta) {
                return data;
            },
            "orderable": false,
        },
        {
            data: 'name',
            "render": function (data, type, row, meta) {
                if (type === 'display') {
                    if(row['role'] === 'student'){
                        data = '<a href=/students/' + row['id'] + '?link=' + row['link'] + '#' + row['link'] + '>' + data + '</a><br/>';

                    }else if(row['role'] === 'applicant'){
                        data = `<a href="/submissions/applicants/${row['id']}">${data}</a>`;
                    }
                }
                return data;
            },
            "orderable": true
        },
        {
            data: 'email',
            "orderable": true
        },
        {
            data: 'application',
            "orderable": false
        },
        {
            data: 'course',
            "orderable": false
        },
        {
            data: 'program',
            "orderable": false
        },
        {
            data: 'recent_transaction',
            "orderable": false
        },
        {
            data: 'application_status',
            'orderable': false
        },
        {
            data: 'progress_status',
            "render": function (data, type, row, meta) {
                var html_data = '';
                if (type === 'display') {
                    html_data = '<td class="min-column norm-column">' +
                        '<div class="progressbar-container">' +
                        '<div class="c-progressbar-el">' +
                        '<div class="c-progressbar-pcolor">' +
                        '<div style="z-index: 999;">' +
                        '<i class="mdi mdi-play"></i><span class="c-progressbar-num">' + data + '%</span>' +
                        '</div>' +
                        '<div class="progress-overlay" style="left:' + data + '%;"></div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="c-progress-details">' +
                        row['app_text'] + ': ' + row['step'] + '/' + row['application_sections'] +
                        '</div>' +
                        '</div>' +
                        '</td>';
                }
                return html_data;
            }
        },
        {
            data: 'student_stage',
            'orderable': false
        },
        {
            data: 'campus',
            "orderable": false
        },
        {
            data: 'updated'
        },
        {
            data: 'created'
        },
        {
            data: null,
            "render": function (data, type, row, meta) {
                var html_data = '';
                if (type === 'display') {
                    html_data = '<div class="btn-group more-optn-group">' +
                        '<button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>' +
                        ' <div class="dropdown-menu">';
                    html_data += ' <a class="dropdown-item archive-btn" href="javascript:void(0)"' +
                    ' onclick="app.archiveSubscription(' + data['submission_id'] + ');"' +
                    ' class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"' +
                    ' data-original-title="Archive">' +
                    '<img src="/assets/images/archive.svg" class="custom-icon" width="12px"> Archive' +
                    '</a>';
                    html_data += ' <a class="dropdown-item unarchive-btn hide" href="javascript:void(0)"' +
                    ' onclick="app.restoreSubscription(' + data['submission_id'] + ');"' +
                    ' class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"' +
                    ' data-original-title="Restore">' +
                    '<img src="/assets/images/restore.svg" class="custom-icon" width="12px"> Restore' +
                    '</a>';
                    if (data['delete_permission']) {
                        html_data += `<a class="dropdown-item" href="javascript:void(0)"
                        onclick="app.deleteElement('${data['delete_route']}','','${data['deleted_element']}')" class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top" data-original-title="Delete"><i class="icon-trash text-danger"></i>Delete</a>`;
                    }
                    if (data['view_permission']) {
                    html_data += ' <a class="dropdown-item" href="javascript:void(0)"' +
                        ' onclick="app.redirect(\'/submissions/' + data['submission_id'] + '\', \'\', \'data-submission-id\')"' +
                        ' class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Delete">' +
                        '<i class="icon-graph "></i> History' +
                        '</a>';
                    }
                    if (data['unlock_request']) {
                        html_data += ' <a class="dropdown-item" href="javascript:void(0)"' +
                        ' onclick="app.unlock(\'submission/change/lock?submission_id=' + data['submission_id'] + '\',\'data-submission-id\', this)"' +
                        ' class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Unlock">' +
                        '<i class="icon-unlock text-danger"></i> Unlock' +
                        '</a>';
                }
                html_data += '</div></div>';
            }
            return html_data;
        },
        'orderable': false,
    },
    ],
    'columnDefs': [{
        'targets': [2],
        'orderable': false,
    },
    {
        responsivePriority: 0,
        targets: 0
    },
    {
        responsivePriority: 1,
        targets: -1
    }
    ],
    rowCallback: function (row, data) {
        $(row).attr('data-submission-id', data['submission_id']);
    },
    initComplete: function( settings, json ) {
        app.appAjax('GET', null, '/usersettings').then(function (data) {
            if(data["submission_table_columns"].length){
                app.reorderColumns(data["submission_table_columns"]);
                $(".modal-body .sortable-options input[type='checkbox']").each(function() {
                    id = $(this).prop('id').replace('col-', '')
                    checked = data["submission_table_columns"].includes(+id)
                    $(this).prop("checked",  checked)
                    if(this.checked) {
                        $(`#item-${id}` ).show()
                    }else{
                        $(`#item-${id}` ).hide()
                    }
                })

            }
        });
    }
});
}

app.initPlugins = function () {
    app.resetValidation();
    app.filePondUploader();

    app.dragElements();
    app.inlineScrole();
    app.applicationEdit();
    app.accordion();
    app.customSelect();
    app.fileInput();
    app.initTextEditor();
    app.codeHighLight();
    app.radioSwitch();
    app.colorPicker();
    app.cssEditor();
    app.initApplicantTable();
    app.initIndexTable();
    app.dateTimePicker();
    app.duallistbox();
    app.dateRange();
    app.calendarRange();
    app.fileUploader();
    app.editable();
}

app.loadStudentsTable = function(){
    window.students_table = $('#students_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        colReorder: true,
        dom: "<'row'<'col-sm-3'lf>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6'p>>",
        // dom: 'Bfrtip',
        // buttons: [
        //     'excel', 'csv'
        // ],
        // "language": {
        //     search: "_INPUT_",
        //     searchPlaceholder: "Search...",
        //     "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/" + $('#students_table').data('i18n') + ".json"
        // },
        "ajax": {
            "url": $('#students_table').data('route'),
            'beforeSend': function (request) {

                request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="_token"]').attr('content'));
            },
            'data': function (data) {
                // Read values
                var date_range = $('#calendarRanges').val();
                var start_date = '';
                var end_date = '';
                if ($('.calendarRanges').length > 0 && calendarRanges != '') {
                    var dates = date_range.split(" - ");
                    start_date = dates[0];
                    end_date = dates[1];
                }
                var groups = $("#group").val();
                var courses = $("#course").val();
                var programs = $('#program').val();
                data.start_date = start_date;
                data.end_date = end_date;
                data.groups = groups;
                data.courses = courses;
                data.programs = programs;
            }
        },
        "order": [
            [1, "desc"]
        ],
        columns: [
        {
            data: 'select',
            "render": function (data, type, row, meta) {
                return data;
            },
            "orderable": false,
        },
        {
            data: 'id',
            "orderable": true
        },
        {
            data: 'name',
            "render": function (data, type, row, meta) {
                if (type === 'display') {
                    data = '<a href="/students/' + row['id']  + '" >' + data + '</a><br/>';
                }
                return data;
            },
            "orderable": true
        },
        {
            data: 'email',
            "orderable": true
        },
        {
            data: 'course',
            "orderable": false
        },
        {
            data: 'cohort',
            "orderable": false
        },
        {
            data: 'program',
            "orderable": false
        },
        {
            data: 'startDate',
            "orderable": false
        },
        {
            data: 'endDate',
            "orderable": false
        },
        {
            data: 'created',
            'orderable': true,
        },
        {
            data: null,
            "render": function (data, type, row, meta) {
                var html_data = '';
                if (type === 'display') {
                    html_data = '<div class="btn-group more-optn-group">' +
                        '<button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>' +
                        ' <div class="dropdown-menu">';
                    html_data += ' <a class="dropdown-item archive-btn" href="javascript:void(0)"' +
                    ' onclick="app.archiveSubscription(' + data['id'] + ');"' +
                    ' class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"' +
                    ' data-original-title="Archive">' +
                    '<img src="/assets/images/archive.svg" class="custom-icon" width="12px"> Archive' +
                    '</a>';
                    html_data += ' <a class="dropdown-item unarchive-btn hide" href="javascript:void(0)"' +
                    ' onclick="app.restoreSubscription(' + data['id'] + ');"' +
                    ' class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"' +
                    ' data-original-title="Restore">' +
                    '<img src="/assets/images/restore.svg" class="custom-icon" width="12px"> Restore' +
                    '</a>';
                    if (data['delete_permission']) {
                        html_data += ' <a class="dropdown-item" href="javascript:void(0)"' +
                            ' onclick="app.deleteElement(\'/submissions/destroy/' + data['id'] + '\', \'\', \'data-submission-id\')"' +
                            ' class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"' +
                            ' data-original-title="Delete">' +
                            '<i class="icon-trash text-danger"></i> Delete' +
                            '</a>';
                    }
                    if (data['view_permission']) {
                    html_data += ' <a class="dropdown-item" href="javascript:void(0)"' +
                        ' onclick="app.redirect(\'/submissions/' + data['id'] + '\', \'\', \'data-submission-id\')"' +
                        ' class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Delete">' +
                        '<i class="icon-graph "></i> History' +
                        '</a>';
                    }
                    if (data['unlock_request']) {
                        html_data += ' <a class="dropdown-item" href="javascript:void(0)"' +
                        ' onclick="app.unlock(\'submission/change/lock?submission_id=' + data['id'] + '\',\'data-submission-id\', this)"' +
                        ' class="btn btn-circle btn-light text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Unlock">' +
                        '<i class="icon-unlock text-danger"></i> Unlock' +
                        '</a>';
                }
                html_data += '</div></div>';
            }
            return html_data;
        },
        'orderable': false,
    },
    ],
    rowCallback: function (row, data) {
        $(row).attr('data-student-id', data['id']);
    },
});
}

// Theme Functions
$(document).ready(function () {

    "use strict";

    var hasfilter = false;
    var hasCustomizeFilter = false;

    var token = $('meta[name="_token"]').attr('content');

    //initiate clear checkfilter hide clearall btn
    checkFilter(false);

    $('#student_academics_table').DataTable({
        "searching": false,
        "paging": false,
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        'columnDefs': [{
            'targets': [0],
            orderable: false,
        }],
        "order": [
            [1, "asc"]
        ],
        columns: [{
            data: 'show',
        },
        {
            data: 'course',
        },
        {
            data: 'cohort',
        },
        {
            data: 'semester',
        },
        {
            data: 'actions',
            'orderable': false,
        }]
      });

    $( ".sortable" ).sortable()

    $("#groupbyemail").change(function() {
        window.submissions_table.clear();
        window.submissions_table.ajax.reload();
        if($(this).prop('checked')){
            $('#bulk-archive').addClass("hide")
            $('#bulk-archive').removeClass("show")
        }else{
            if($('#bulk-archive').attr("show")){
                $('#bulk-archive').addClass("show")
                $('#bulk-archive').removeClass("hide")
            }else{
                $('#bulk-archive').addClass("hide")
                $('#bulk-archive').removeClass("show")
            }
        }
    });

    $(".modal-body .sortable-options input[type='checkbox']").change(function() {

        if(this.checked) {
            $(`#item-${$(this).attr('name').replace('item-','')}` ).show()
        }else{
            $(`#item-${$(this).attr('name').replace('item-','')}` ).hide()
        }

    });

    app.loadSubmissionsTable();
    app.loadStudentsTable();

    $("#submissions_table_wrapper .dataTables_length").css("display", "none");
    $("#submissions_table_wrapper .dataTables_filter").css("display", "none");
    $("#accounting_table_wrapper .dataTables_length").css("display", "none");
    $("#accounting_table_wrapper .dataTables_filter").css("display", "none");
    $("#accounting_student_table_wrapper .dataTables_length").css("display", "none");
    $("#accounting_student_table_wrapper .dataTables_filter").css("display", "none");

    var accounting_table = $('#accounting_table').DataTable({
        processing: true,
        serverSide: true,
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
            search: "_INPUT_",
            searchPlaceholder: "Search...",
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/" + $('#accounting_table').data('i18n') + ".json"
        },
        "ajax": {
            "url": $('#accounting_table').data('route'),
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
            data: 'paymentMethod'
        },
        {
            data: 'no'
        },
        {
            data: 'dueDate'
        },
        {
            data: 'student',
            "render": function (data, type, row, meta) {
                if (type === 'display') {
                    data = '<a href=/students/' + row[`student_id`] + '>' + data + '</a>';
                }
                return data;
            }
        },
        {
            data: 'balance'
        },
        {
            data: 'status'
        },
        {
            data: 'total'
        },
        {
            data: null,
            "render": function (data, type, row, meta) {
                if (data['type'] === 'Invoice') {
                    data = '<div class="btn-group more-optn-group"><button type="button" ' +
                        'class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt" ' +
                        'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '</button><div class="dropdown-menu">' +
                        '<a class="dropdown-item" href="/payments/create/Polymorph?invoice=' + data[`id`] + '&student=' + data[`student_id`] + '"' +
                        ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Receive Payment">' +
                        '<i class="fas fa-dollar-sign text-info"></i> Receive Payment' +
                        '</a>' +
                        '<a class="dropdown-item" href="javascript:void(0)"' +
                        'onclick="app.deleteInvoice(`/invoices/' + data[`uid`] + '`)"' +
                        ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Delete Invoice">' +
                        '<i class="fas fa-trash text-danger"></i> Delete Invoice' +
                        '</a>' +
                        '<a class="dropdown-item" href="/invoices/update/' + data[`uid`] + '"' +
                        ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Update Invoice">' +
                        '<i class="fas fa-pencil-alt text-info"></i> Update Invoice' +
                        '</a>' +
                        '<a class="dropdown-item" href="/invoices/update/' + data[`uid`] + '"' +
                        ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Update Invoice">' +
                        '<i class="fas fa-eye text-info"></i> View Invoice' +
                        '</a>'  + (
                            data[`paymentLink`]
                                ? '<a class="dropdown-item" href="' + data[`paymentLink`] + '"' +
                                ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                                ' data-original-title="Payment link">' +
                                '<i class="fas fa-credit-card text-info"></i> Payment link' +
                                '</a>'
                                : ''
                        ) +
                        '<a class="dropdown-item" href="' + data[`view`] + '"' +
                        ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Update Invoice">' +
                        '<i class="fas fa-file text-info"></i> Print Invoice' +
                        '</a>' +
                        '<a class="dropdown-item" href="' + data[`download`] + '"' +
                        ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Update Invoice">' +
                        '<i class="fas fa-download"></i> Download Invoice' +
                        '</a>' +
                        '</div></div>';
                } else {
                    data = '<div class="btn-group more-optn-group"><button type="button" ' +
                        'class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt" ' +
                        'data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                        '</button><div class="dropdown-menu">' +
                        '<a class="dropdown-item" href="/payments/edit/' + data[`id`] + '/Polymorph?student=' + data[`student_id`] + '"' +
                        ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Receive Payment">' +
                        '<i class="fas fa-pencil-alt text-info"></i> Receive Payment' +
                        '</a>' +
                        '<a class="dropdown-item" href="javascript:void(0)"' +
                        'onclick="app.deleteInvoice(`/payments/delete/' + data[`id`] + '/Polymorph`)"' +
                        ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Delete Payment">' +
                        '<i class="fas fa-trash text-danger"></i> Delete Payment' +
                        '</a>' +
                        '<a class="dropdown-item" href="' + data[`view`] + '"' +
                        ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Update Invoice">' +
                        '<i class="fas fa-eye text-info"></i> View Payment' +
                        '</a>' +
                        '<a class="dropdown-item" href="' + data[`download`] + '"' +
                        ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                        ' data-original-title="Update Invoice">' +
                        '<i class="fas fa-download"></i> Download Payment' +
                        '</a>' +
                        '</div></div>';
                }
                return data;
            },
            'orderable': false,
        },
        ],

        rowCallback: function (row, data) {
            $(row).attr('data-accounting-id', data['accounting_id']);
        }
    });

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
            search: "_INPUT_",
            searchPlaceholder: "Search...",
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/" + $('#accounting_student_table').data('i18n') + ".json"
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
            [3, "desc"]
        ],
        columns: [
            {
                data: 'date',
            },
            {
                data: 'type'
            },
            {
                data: 'paymentMethod'
            },
            {
                data: 'id'
            },
            {
                data: 'no'
            },
            {
                data: 'dueDate'
            },
            {
                data: 'balance'
            },
            {
                data: 'status'
            },
            {
                data: 'total'
            },
            {
                data: null,
                "render": function (data, type, row, meta) {
                    if (data['type'] === 'Invoice') {
                        data = '<div class="btn-group more-optn-group"><button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button><div class="dropdown-menu"><a class="dropdown-item" href="/payments/create/Polymorph?invoice=' + data[`id`] + '&student=' + data[`student_id`] + '"' +
                            ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                            ' data-original-title="Receive Payment">' +
                            '<i class="fas fa-dollar-sign text-info"></i> Receive Payment' +
                            '</a>' +
                            '<a class="dropdown-item" href="javascript:void(0)"' +
                            'onclick="app.deleteInvoice(`/invoices/' + data[`uid`] + '`)"' +
                            ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                            ' data-original-title="Delete Invoice">' +
                            '<i class="fas fa-trash text-danger"></i> Delete Invoice' +
                            '</a>' +
                            '<a class="dropdown-item" href="/invoices/update/' + data[`uid`] + '"' +
                            ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                            ' data-original-title="Update Invoice">' +
                            '<i class="fas fa-pencil-alt text-info"></i> Update Invoice' +
                            '</a>' +
                            '<a class="dropdown-item" href="/invoices/update/' + data[`uid`] + '"' +
                            ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                            ' data-original-title="Update Invoice">' +
                            '<i class="fas fa-eye text-info"></i> View Invoice' +
                            '</a>' + (
                                data[`paymentLink`]
                                    ? '<a class="dropdown-item" href="' + data[`paymentLink`] + '"' +
                                    ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                                    ' data-original-title="Payment link">' +
                                    '<i class="fas fa-credit-card text-info"></i> Payment link' +
                                    '</a>'
                                    : ''
                            ) +
                            '<a class="dropdown-item" href="' + data[`view`] + '"' +
                            ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                            ' data-original-title="Update Invoice">' +
                            '<i class="fas fa-file text-info"></i> Print Invoice' +
                            '</a>' +
                            '<a class="dropdown-item" href="' + data[`download`] + '"' +
                            ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                            ' data-original-title="Update Invoice">' +
                            '<i class="fas fa-download text-info"></i> Download Invoice' +
                            '</a></div></div>';
                    } else {
                        data = '<div class="btn-group more-optn-group"><button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button><div class="dropdown-menu"><a class="dropdown-item" href="/payments/edit/' + data[`id`] + '/Polymorph?student=' + data[`student_id`] + '"' +
                            ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                            ' data-original-title="Receive Payment">' +
                            '<i class="fas fa-pencil-alt text-info"></i> Edit' +
                            '</a>' +
                            '<a class="dropdown-item" href="javascript:void(0)"' +
                            'onclick="app.deleteInvoice(`/payments/delete/' + data[`id`] + '/Polymorph`)"' +
                            ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                            ' data-original-title="Delete Payment">' +
                            '<i class="fas fa-trash text-danger"></i> Delete' +
                            '</a></div></div>';
                    }
                    return data;
                },
                'orderable': false,
            },
        ],

        rowCallback: function (row, data) {
            $(row).attr('data-accounting-id', data['accounting_id']);
        }
    });

    window.lessons_table = $('#lessons_table').DataTable({
        searching: false,
        processing: true,
        serverSide: true,
        dom: "<'row'<'col-sm-3'lf>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6'p>>",
        bInfo: true,
        responsive: true,
        columnDefs: [{
            responsivePriority: 0,
            targets: 0
        },
        {
            responsivePriority: 1,
            targets: -1
        }
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/" + $('#accounting_student_table').data('i18n') + ".json"
        },
        ajax: {
            "url": $('#lessons_table').data('route'),
            'beforeSend': function (request) {
                request.setRequestHeader("X-CSRF-TOKEN", token);
            },
            'data': function (data) {
                // Read values
                var date_range = $('#calendarRanges').val();

                var start_date = '';
                var end_date = '';
                var student_id = '';
                var url_lessons = "{{route('lessons.index')}}";

                if ($('.calendarRanges').length > 0 && calendarRanges != '') {
                    var dates = date_range.split(" - ");
                    start_date = dates[0];
                    end_date = dates[1];
                }

                student_id = $('#student_id').val();
                var classrooms_id = $('#classrooms').val();
                var courses_id = $('#courses').val();
                var instructors_id = $('#instructors').val();

                // Append to data
                data.start_date = start_date;
                data.end_date = end_date;
                data.classrooms = classrooms_id;
                data.courses = courses_id;
                data.instructors = instructors_id;
            }
        },
        "order": [
            [5, "desc"]
        ],
        columns: [{
            data: 'select',
            "render": function (data, type, row, meta) {
                return data;
            },
            "orderable": false,
        },

        {
            data: 'course',
            'orderable': false,
        },
        {
            data: 'instructor',
            'orderable': false,
        },
        {
            data: 'classroom',
            'orderable': false,
        },
        {
            data: 'date'
        },
        {
            data: 'start_time'
        },
        {
            data: 'end_time'
        },
        {
            data: 'held',
            'orderable': false,
        },
        {
            data: 'cohorts',
            'orderable': false,
            },
            {
            data: 'students',
            'orderable': false,
        },
        {
            data: 'action',
            'orderable': false,
        },

        ]
    });

	window.attendances_table = $('#attendances_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        dom: "<'row'<'col-sm-3'lf>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-6'i><'col-sm-6'p>>",
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
			search: "_INPUT_",
            searchPlaceholder: "Search...",
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/" + $('#attendances_table').data('i18n') + ".json"
        },
        "ajax": {
            "url": $('#attendances_table').data('route'),
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

				var students_id = $('#students').val();
                var classrooms_id = $('#classrooms').val();
                var courses_id = $('#courses').val();
                var instructors_id = $('#instructors').val();
				var status = $('#statuses').val();
				var classroom_slots_id = $('#timeslots').val();

                // Append to data
                data.start_date = start_date;
                data.end_date = end_date;
				data.students = students_id;
                data.classrooms = classrooms_id;
                data.courses = courses_id;
                data.instructors = instructors_id;
				data.statuses = status;
				data.classroomslots = classroom_slots_id;
            }
        },
        "order": [
            [5, "desc"]
        ],
            columns: [{
            data: 'select',
            "render": function (data, type, row, meta) {
                return data;
            },
            "orderable": false,

        },
        {
            data: 'student',
            'orderable': true,
        },
        {
            data: 'course',
            'orderable': false,
        },
        {
            data: 'instructor',
            'orderable': false,
        },
        {
            data: 'timeslot',
            'orderable': false,
        },
        {
            data: 'date'
        },
        {
            data: 'status',
			'orderable': false,
        },
        {
            data: 'id',
			"render": function (data, type, row, meta) {
                data = '<div class="btn-group more-optn-group" > ' +
                    '<button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split ti-more-alt"' +
                    ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>' +
                    '<div class="dropdown-menu"><a class="dropdown-item" href="#" onclick=app.attendanceCreate("/attendance/' + data + '/edit-attendance")' +
                    ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                    ' data-original-title="Receive Payment">' +
                    '<i class="fas fa-pencil-alt text-info"></i> Edit Attendance' +
                    '</a>' +
                    '<a class="dropdown-item" href="javascript:void(0)"' +
                    'onclick="app.deleteInvoice(`/attendance/' + data + '`)"' +
                    ' class="text-muted" data-toggle="tooltip" data-placement="top"' +
                    ' data-original-title="Delete Payment">' +
                    '<i class="fas fa-trash text-danger"></i> Delete Attendance' +
                    '</a></div></div>';
                return data;
            },
            'orderable': false,
        }
        ]
    });

	setTimeout(function () {
        //$(".dataTables_length").appendTo('#lenContainer');
        // $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
    }, 300);

	$("#attendances_table_wrapper .dataTables_length").css("display", "none");
    $("#attendances_table_wrapper .dataTables_filter").css("display", "none");

    var lessons_instructor_table = $('#lessons_instructor_table').DataTable({
        searching: false,
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
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/" + $('#accounting_student_table').data('i18n') + ".json"
        },
        "ajax": {
            "url": $('#lessons_instructor_table').data('route'),
            'beforeSend': function (request) {
                request.setRequestHeader("X-CSRF-TOKEN", token);
            },
            'data': function (data) {
                // Read values
                var date_range = $('#calendarRanges').val();

                var start_date = '';
                var end_date = '';
                var instructor_id = '';

                if ($('.calendarRanges').length > 0 && calendarRanges != '') {
                    var dates = date_range.split(" - ");
                    start_date = dates[0];
                    end_date = dates[1];
                }

                instructor_id = $('#instructor_id').val();

                // Append to data
                data.start_date = start_date;
                data.end_date = end_date;
                data.instructor_id = instructor_id;
            }
        },
        "order": [
            [4, "desc"]
        ],
        columns: [{
            data: 'title',
            'orderable': false,
            "render": function (data, type, row, meta) {
                if (type === 'display') {
                    data = '<a href=' + row[`route`] + '>' + data + '</a>';
                }
                return data;
            }
        },
        {
            data: 'instructor',
            'orderable': false,
        },
        {
            data: 'classroom',
            'orderable': false,
        },
        {
            data: 'course',
            'orderable': false,
        },
        {
            data: 'date'
        },
        {
            data: 'start_time'
        },
        {
            data: 'end_time'
        },
        {
            data: 'held',
            'orderable': false,
        }
        ]
    });


    if ($('div.review-page').length) {
        var i = 0;
        $('div.review-page').each(function () {
            app.renderReviewPage($('div.review-page')[i]);
            i++;
        });
        //app.renderReviewPage($('div.review-page')[0]);
    }



    $('div.date').on('apply.daterangepicker', function (ev, picker) {
        $('.calendarRanges').val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
        window.submissions_table.draw();
        accounting_table.draw();
        accounting_student_table.draw();
        window.lessons_table.draw();
		window.attendances_table.draw();
        lessons_instructor_table.draw();

        checkFilter(true);
    });

    //Added Quin 3/24/220
    //this fucntion checks if table has currently filtered
    // to toggle class is-active and show/hide clear all btn
    function checkFilter(hasfilter) {

        if (hasfilter) {
            if ($('#panel-toggle:not(.is-active)')) {
                $('#panel-toggle').addClass('is-active');
                $('#clear_filter').show();
                $('#clear_filter_attendance').show();
                $('#clear_student_filter').show();
            }
        } else {
            $('#panel-toggle').removeClass('is-active');
            $('#clear_filter').hide();
            $('#clear_filter_attendance').hide();
            $('#clear_student_filter').hide();
        }
    }

    $("[name='application[]']").on('select2:close', function (e) {
        submissions_table.draw();
    });

    $("[name='status[]']").on('select2:close', function (e) {
        submissions_table.draw();
    });



    $('#apply').click(function () {
        submissions_table.draw();
        window.lessons_table.draw();
		attendances_table.draw();
        //   $('#panel-toggle').removeClass("btn-light");
        //  $('#panel-toggle').addClass("btn-primary");

        $(".customizer").removeClass('show-service-panel');

        // Added Quin 3/24/220
        // check sidepanel count if currently has active filter once the apply button is click
        var haschecked = false;
        var checkSum = 0;

        $('.customizer-body .multiselect-count').each(function () {
            checkSum += parseInt($(this).text());

        });

        if (checkSum >= 1) {
            checkFilter(true);
        }


    });

    $('#apply-students-filter').click(function () {
        window.students_table.ajax.reload();

        $(".customizer").removeClass('show-service-panel');

        checkFilter(true);
    });

    $('#clear_filter').click(function () {
        $('#panel-toggle').removeClass("btn-primary");
        $('#panel-toggle').addClass("btn-light");

        document.multiselect('#applications').deselectAll();
        document.multiselect('#status').deselectAll();
        document.multiselect('#progress').deselectAll();
        document.multiselect('#student_statuses').deselectAll();

        $('.calendarRanges').val('');

        submissions_table.draw();

        checkFilter(false);
    });

    $('#clear_student_filter').click(function () {
        $('#panel-toggle').removeClass("btn-primary");
        $('#panel-toggle').addClass("btn-light");

        document.multiselect('#course').deselectAll();
        document.multiselect('#group').deselectAll();
        document.multiselect('#program').deselectAll();

        $('.calendarRanges').val('');

        window.students_table.ajax.reload();

        checkFilter(false);
    });

    $('#clear_student_filter').click(function () {
        $('#panel-toggle').removeClass("btn-primary");
        $('#panel-toggle').addClass("btn-light");

        document.multiselect('#group').deselectAll();
        document.multiselect('#course').deselectAll();
        document.multiselect('#program').deselectAll();

        $('.calendarRanges').val('');

        students_table.draw();

        checkFilter(false);
    });


    $('#clear_filter_lesson').click(function () {
        $('#panel-toggle').removeClass("btn-primary");
        $('#panel-toggle').addClass("btn-light");

        document.multiselect('#classrooms').deselectAll();
        document.multiselect('#courses').deselectAll();
        document.multiselect('#instructors').deselectAll();

        $('.calendarRanges').val('');

        window.lessons_table.draw();
    });

	$('#clear_filter_attendance').click(function () {
        $('#panel-toggle').removeClass("btn-primary");
        $('#panel-toggle').addClass("btn-light");

        document.multiselect('#students').deselectAll();
		document.multiselect('#courses').deselectAll();
        document.multiselect('#instructors').deselectAll();
		document.multiselect('#statuses').deselectAll();
		document.multiselect('#timeslots').deselectAll();

        $('.calendarRanges').val('');

		window.attendances_table.draw();

        checkFilter(false);
    });


    $('.accordion-head').click(function () {
        alert('accordion-head clicked');
        /* $('accordion-active').slideUp(function(){
            $(this).removeClass('accordion-active')
        });
        $(this).next('accordion-content').slideToggle();*/
    });



    $('.lessons-export').click(function () {
        var route = $(this).data('route');
        var file = $(this).data('file');
        var parameters = '?payload=ert';

        var date_range = $('#calendarRanges').val();
        var start_date = '';
        var end_date = '';
        if ($('.calendarRanges').length > 0 && calendarRanges !== '') {
            var dates = date_range.split(" - ");
            start_date = dates[0];
            end_date = dates[1];
            if (start_date < end_date) {
                parameters += '&start_date=' + start_date + '&end_date=' + end_date;
            }
        }

        var classrooms_id = $('#classrooms').val();
        var courses_id = $('#courses').val();
        var instructors_id = $('#instructors').val();


        if (classrooms_id && classrooms_id !== null && Array.isArray(classrooms_id) && classrooms_id.length > 0) {
            parameters += '&classrooms=' + classrooms_id;
        }

        if (courses_id && courses_id !== null && Array.isArray(courses_id) && courses_id.length > 0) {
            parameters += '&courses=' + courses_id;
        }

        if (instructors_id && instructors_id !== null && Array.isArray(instructors_id) && instructors_id.length > 0) {
            parameters += '&instructors=' + instructors_id;
        }

        var search = $('#lessons_table_filter input[type=search]').val();
        if (search && search !== null) {
            parameters += '&search=' + search;
        }

        parameters += '&file=' + file;

        route += parameters;

        window.location = route;
    });

	$('.attendance-export').click(function () {
        var route = $(this).data('route');
        var file = $(this).data('file');
        var parameters = '?payload=ert';

        var date_range = $('#calendarRanges').val();
        var start_date = '';
        var end_date = '';
        if ($('.calendarRanges').length > 0 && calendarRanges !== '') {
            var dates = date_range.split(" - ");
            start_date = dates[0];
            end_date = dates[1];
            if (start_date < end_date) {
                parameters += '&start_date=' + start_date + '&end_date=' + end_date;
            }
        }

		var students_id = $('#student').val();
        var classrooms_id = $('#classrooms').val();
        var courses_id = $('#courses').val();
        var instructors_id = $('#instructors').val();

		if (students_id && students_id !== null && Array.isArray(students_id) && students_id.length > 0) {
            parameters += '&students=' + students_id;
        }

        if (classrooms_id && classrooms_id !== null && Array.isArray(classrooms_id) && classrooms_id.length > 0) {
            parameters += '&classrooms=' + classrooms_id;
        }

        if (courses_id && courses_id !== null && Array.isArray(courses_id) && courses_id.length > 0) {
            parameters += '&courses=' + courses_id;
        }

        if (instructors_id && instructors_id !== null && Array.isArray(instructors_id) && instructors_id.length > 0) {
            parameters += '&instructors=' + instructors_id;
        }

        var search = $('#attendances_table_filter input[type=search]').val();
        if (search && search !== null) {
            parameters += '&search=' + search;
        }

        parameters += '&file=' + file;

        route += parameters;

        window.location = route;
    });

    $('#confirm-delete').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var title = button.data('title') // Extract info from data-* attributes
        var modal = $(this)
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

    /* $(function () {
        $(".add_field_toggle").on('click', function (e) {
            e.preventDefault();
            $(".customizer").toggleClass('show-service-panel');
        });
    }); */

    // ==============================================================

    // This is for the floating labels

    // ==============================================================

    $('.floating-labels .form-control').on('focus blur', function (e) {

        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));

    }).trigger('blur');

    // ==============================================================
    // Right sidebar options
    // ==============================================================
    $(function () {
        $('#panel-toggle').on('click', function () {
            $(".customizer").toggleClass('show-service-panel');
        });

        $('.page-wrapper').on('click', function () {
            if (!$(event.target).closest('#panel-toggle').length) {
                $(".customizer").removeClass('show-service-panel');
            }
        });

        $('.customizer-close-btn').on('click', function () {
            $(".customizer").removeClass('show-service-panel');
        });

    });

    document.multiselect('#status');
    document.multiselect(".multiSelect");

    // ==============================================================
    //tooltip
    // ==============================================================

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    })

    // ==============================================================
    //Popover
    // ==============================================================

    $(function () {
        $('[data-toggle="popover"]').popover({
            html: true,
            animation: true,
            focus: true
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

    var sparklineLogin = function () {
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

    $(window).resize(function (e) {
        clearTimeout(sparkResize);
        sparkResize = setTimeout(sparklineLogin, 500);
    });

    sparklineLogin();



    // ==============================================================
    // This is for the innerleft sidebar
    // ==============================================================

    $(".show-left-part").on('click', function () {
        $('.left-part').toggleClass('show-panel');
        $('.show-left-part').toggleClass('ti-menu');
    });

    var form = $(".validation-wizard").show();

    var add_button = $(".validation-wizard").data('add-button')
    var cancel_button = 'Cancel';

    $(".validation-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        showFinishButtonAlways: true,
        enableCancelButton: true,
        backButtonSupport: false,
        enableAllSteps: false,

        labels: {
            finish: add_button
        },

        onInit: function (event, currentIndex) {
            app.dateTimePicker();
        },


        onStepChanging: function (event, currentIndex, newIndex) {

            app.dateTimePicker();
            app.duallistbox();

            return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())

        },

        onFinishing: function (event, currentIndex) {

            return form.validate().settings.ignore = ":disabled", form.valid()

        },

        onFinished: function (event, currentIndex) {

            if(!$('.validation-wizard .custom-validator').length)
            {
                form.submit();
            }

        },

        onCanceled: function (event, currentIndex) {
            window.history.back();
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

    app.dragElements();



    app.inlineScrole();

    app.applicationEdit();

    app.formBuilder();

    app.accordion();

    app.customSelect();

    app.fileInput();

    //app.tinyMCE();
    app.initTextEditor();

    app.codeHighLight();

    app.radioSwitch();

    app.colorPicker();

    app.cssEditor();

    app.initApplicantTable();

    app.initIndexTable();

    app.dateTimePicker();

    app.duallistbox();

    app.dateRange();

    app.calendarRange();

    app.fileUploader();
    app.editable();



});

function remove_education_fields(rid) {
    $('.removeclass_' + rid).remove();
}


$(".daterangepicker").datepicker({
    maxDate: 0,
    changeMonth: true,
    changeYear: true,
    dateFormat: 'yy-mm-dd',
    onClose: function (selectedDate) {
        table.fnDraw();
    }
});
$('#min_create, #max_create, #min_update, #max_update').keyup(function () {
    table.draw();
});
$('#min_create, #max_create, #min_update, #max_update').click(function () {
    table.draw();
});
$('#min_create').datepicker().bind('onClose', function () {
    table.draw();
});

//Jquery for table
//Detabke Data table search and append to diff table
$(document).ready(function () {

    $("[validator-url]").focusout(
        (e) => {
            email = $("[validator-url]").val();
            app.appAjax('POST', { email }, $("[validator-url]").attr('validator-url')).then(function (data) {

                if (data.response !== 'success' || data.status !== 200) {
                    if(!$(e.target).siblings('.custom-validator').length)
                        $("[validator-url]").after(`<span class="text-danger custom-validator">${ data.message }</span>`);
                }

            });
        }
    );

    $("[validator-url]").keyup(
        (e) => {
            $(e.target).siblings('.custom-validator').remove();
        }
    );

    app.interPhoneCode();

    app.accounting_student_table = function (el) {
        setTimeout(function () {
            var firstColTableFilter = $('#datatableNewFilter #lenContainer');

            var searchConatiner = $('#accounting_student_table_wrapper #accounting_student_table_filter');

            //Remove search label;
            var sInput = $("#accounting_student_table_filter").find('input');
            var sLabel = $("#accounting_student_table_filter").find('label');
            sLabel.replaceWith(sInput);

            var iPaginate = $('#accounting_student_table_wrapper .dataTables_length select');

            $(iPaginate).detach().appendTo(firstColTableFilter);

            $(searchConatiner).detach().appendTo(firstColTableFilter);

            //hide dataTables_length table column
            $('#accounting_student_table_wrapper .dataTables_length').hide();


            if (window.location.hash) {
                window.location.href = window.location.hash;
            }

        }, 300);
    }
    app.accounting_student_table();

    app.students_table = function (el) {
        setTimeout(function () {
            var firstColTableFilter = $('#datatableNewFilter #lenContainer');

            var searchConatiner = $('#students_table_wrapper #students_table_filter');

            //Remove search label;
            var sInput = $("#students_table_filter").find('input');
            var sLabel = $("#students_table_filter").find('label');
            sLabel.replaceWith(sInput);

            var iPaginate = $('#students_table_wrapper .dataTables_length select');

            $(iPaginate).detach().appendTo(firstColTableFilter);

            $(searchConatiner).detach().appendTo(firstColTableFilter);

            //hide dataTables_length table column
            $('#students_table_wrapper .dataTables_length').hide();

            if (window.location.hash) {
                window.location.href = window.location.hash;
            }

        }, 300);
    }
    app.students_table();

    app.accounting_table = function (el) {
        setTimeout(function () {
            var firstColTableFilter = $('#datatableNewFilter #lenContainer');

            var searchConatiner = $('#accounting_table_wrapper #accounting_table_filter');

            //Remove search label;
            var sInput = $("#accounting_table_filter").find('input');
            var sLabel = $("#accounting_table_filter").find('label');
            sLabel.replaceWith(sInput);

            var iPaginate = $('#accounting_table_wrapper .dataTables_length select');

            $(iPaginate).detach().appendTo(firstColTableFilter);

            $(searchConatiner).detach().appendTo(firstColTableFilter);

            //hide dataTables_length table column
            $('#accounting_table_wrapper .dataTables_length').hide();


            if (window.location.hash) {
                window.location.href = window.location.hash;
            }

        }, 900);
    }
    app.accounting_table();

    app.applicantTable = function () {

        setTimeout(function () {
            var firstColTableFilter = $('#datatableNewFilter #lenContainer');

            var searchConatiner = $('#applicant_table_wrapper #applicant_table_filter');

            //Remove search label;
            var sInput = $("#applicant_table_filter").find('input');
            var sLabel = $("#applicant_table_filter").find('label');
            sLabel.replaceWith(sInput);

            var iPaginate = $('#applicant_table_wrapper .dataTables_length select');

            $(iPaginate).detach().appendTo(firstColTableFilter);

            $(searchConatiner).detach().appendTo(firstColTableFilter);

            //hide dataTables_length table column
            $('#applicant_table_wrapper .dataTables_length').hide();


            if (window.location.hash) {
                window.location.href = window.location.hash;
            }

        }, 300);
    }
    app.applicantTable();

    app.initSubmissionTable = function (el) {
        setTimeout(function () {
            var firstColTableFilter = $('#datatableNewFilter #lenContainer');

            var searchConatiner = $('#submissions_table_filter');

            //Remove search label;
            var sInput = $("#submissions_table_filter").find('input');
            var sLabel = $("#submissions_table_filter").find('label');
            sLabel.replaceWith(sInput);


            var iPaginate = $('#submissions_table_wrapper .dataTables_length select');
            console.log(iPaginate);

            $(iPaginate).detach().appendTo(firstColTableFilter);
            $(searchConatiner).detach().appendTo(firstColTableFilter);

            $('#submissions_table_wrapper .dataTables_length').hide();




            if (window.location.hash) {
                window.location.href = window.location.hash;
            }

        }, 900);

    }

    app.initLessonTable = function (el) {
        setTimeout(function () {
            var firstColTableFilter = $('#datatableNewFilter #lenContainer');

            var searchConatiner = $('#lessons_table_wrapper #lessons_table_filter');

            //Remove search label;
            var sInput = $("#lessons_table_filter").find('input');
            var sLabel = $("#lessons_table_filter").find('label');
            sLabel.replaceWith(sInput);


            $(searchConatiner).detach().appendTo(firstColTableFilter);

            if (window.location.hash) {
                window.location.href = window.location.hash;
            }

        }, 300);

    }

	app.initAttendanceTable = function (el) {
        setTimeout(function () {
            var firstColTableFilter = $('#datatableNewFilter #lenContainer');

            var searchConatiner = $('#attendances_table_wrapper #attendances_table_filter');

            //Remove search label;
            var sInput = $("#attendances_table_filter").find('input');
            var sLabel = $("#attendances_table_filter").find('label');
            sLabel.replaceWith(sInput);


            var iPaginate = $('#attendances_table_wrapper .dataTables_length select');
            console.log(iPaginate);

            $(iPaginate).detach().appendTo(firstColTableFilter);
            $(searchConatiner).detach().appendTo(firstColTableFilter);

            $('#attendances_table_wrapper .dataTables_length').hide();


            if (window.location.hash) {
                window.location.href = window.location.hash;
            }

        }, 300);

    }

	app.initSubmissionTable();
	app.initLessonTable();
    app.initAttendanceTable();

});

//added for panel close btn
$(document).ready(function () {

    $('.btn-toggler.float-right-btn').click(function () {

        let closetPanel = $(this).closest('.nav-application');
        console.log(closetPanel);
        if ($(this).attr('aria-expanded') == "false") {

            closetPanel.addClass('is-expanded');
        } else {

            closetPanel.removeClass('is-expanded');
        }

    });

    window.addEventListener("filepond-uploading", function (event) {
        app.filePondUploading(event);
    });
    window.addEventListener("filepond-uploaded", function (event) {
        app.filePondUploaded(event);
    });
});
