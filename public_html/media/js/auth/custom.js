var app = {};
app.ajaxRoute = window.ajaxRoute;

app.init = function ()
{
    app.interPhoneCode();
}
app.toggleReadMore = function (el)
{
    $(".toggle-body").toggle();
    $(".toogle-img").toggle();
    if ($(el).hasClass('show-more')) {
        $(el).hide();
        $('.show-less').show();
    } else {
        $(el).hide();
        $('.show-more').show();
    }
}
app.interPhoneCode = function () {
    if ($('.inter-calling-code-mode').length > 0) {
        $('.inter-calling-code-mode').each(function () {
            var disable = false;
            var number = $(this).val();
            var code = $(this).data('code');
            var lang = $(this).data('lang');
            var countryCode = $(this).data('country-code');
            if (code) {
                number = number.replace(code, '');
            }
            $(this).intercode({
                disabled: disable,
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
}
app.programFilter = function (el)
{
    var campus = $(el).val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    $.ajax({
        url: app.ajaxRoute,
        type: "post",
        dataType: 'json',
        data: {
            action: "application.filterProgramsList",
            campus: campus,
        },
        statusCode: {
            404: function () {
                console.log('page not found')
            }
        }
    }).done(function (data) {
        $('#programsListContainer').html(data.extra.html);
    });
}

app.checkAgencyEmail = function (el) {

    var email = $(el).val();
    if (email.length > 5) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        $.ajax({
            url: app.ajaxRoute,
            type: "post",
            dataType: 'json',
            data: {
                action: "agent|agent_agency.checkAgencyRegistration",
                email: email,
            },

            statusCode: {
                404: function () {
                    console.log('page not found')
                }
            }
        }).done(function (data) {
            if (data.status == 200) {
                $('#agency_message').html(data.extra.html);
            }

        });
    }

}

$(function () {
    app.init();
});
