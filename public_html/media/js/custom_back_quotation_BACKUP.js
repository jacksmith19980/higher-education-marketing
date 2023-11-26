app.campusSelected = function (el) {

    var campuses = [];
    var name = $(el).attr('name');

    $('[name="' + name + '"]').each(function () {
        if ($(this).is(':checked')) {
            campuses.push($(this).val());
        }
    });

    var quotationID = $(el).data('quotation');

    if (campuses.length > 0) {
        var data = {
            action: 'getCampusCourses',
            payload: {
                campuses: campuses,
                quotation: quotationID,
            }
        };

        app.appAjax('POST', data, app.quotationAjaxRoute).then(function (data) {

            if (data.response == 'success' && data.status == 200) {

                $('div.courseSelection').html(data.extra.html);
                app.customSelect();
            }
        });
    } else {
        $('div.courseSelection').html("");
    }

}

app.upperCase = function ucwords(str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

app.nextStep = function (el) {

    var values = [];
    var name = $(el).attr('name');

    /* $('[name="' + name + '"]').each(function () {
        if ($(this).is(':checked')) {
            values.push($(this).val());
        }
    }); */

    if ($(el).is(':checked')) {
        values.push($(this).val());
    }

    var quotationID = $(el).data('quotation');
    var by = $(el).data('by');
    var get = $(el).data('get');

    if (values.length > 0) {
        var data = {
            action: 'getData',
            payload: {
                values: values,
                by: by,
                get: get,
                quotation: quotationID,
            }
        };

        app.appAjax('POST', data, app.quotationAjaxRoute).then(function (data) {

            if (data.response == 'success' && data.status == 200) {

                $('div.courseSelection').html(data.extra.html);
                app.customSelect();
            }
        });
    } else {
        $('div.courseSelection').html("");
    }

}
app.courseSelected = function (el) {

    var courses = [];
    var name = $(el).attr('name');

    $('[name="' + name + '"]').each(function () {
        if ($(this).is(':checked')) {
            courses.push($(this).val());
        }
    });
    var quotationID = $(el).data('quotation');

    if (courses.length > 0) {

        app.getDateSelection(courses, quotationID);

    } else {
        $('div.dateSelection').html("");
    }

}

app.getDateSelection = function (courses, quotationID) {

    var data = {
        action: 'getCourseDates',
        payload: {
            courses: courses,
            quotation: quotationID,
        }
    };

    app.appAjax('POST', data, app.quotationAjaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {

            $('div.dateSelection').html(data.extra.html);
            app.customSelect();

        }
    });

}


app.dateSelected = function (el) {

    var dates = $(el).val();
    var quotationID = $(el).data('quotation');
    var course = $(el).data('course');

    var enableActivities = $(el).data('enable-misc');

    if (enableActivities == true) {
        var data = {
            action: 'getQuotationActivitis',
            payload: {
                dates: dates,
                quotation: quotationID,
                course: course
            }
        };
        app.appAjax('POST', data, app.quotationAjaxRoute).then(function (data) {

            if (data.response == 'success' && data.status == 200) {

                $('div.miscDetails').append(data.extra.html);

                if ($('.addMorePrograms').length > 0) {

                    $('.addMorePrograms').show();


                }
                $('.AccommodationTransferOptions').show();

                app.customSelect();

            }

        });


    } else {
        $('div.miscDetails').html("");
    }
    $('.GetAPriceButton').show();

}