//Icon Toggle for fontawesome
function toggleIcon(el, ar) {
    var [iconDefault, iconHover] = ar;
    if (el.hasClass(iconDefault)) {
        el.removeClass(iconDefault);
        el.addClass(iconHover);
    } else {
        el.removeClass(iconHover);
        el.addClass(iconDefault);
    }
}

// toogle for .list-item collapse
function compileToggleScript(el) {
    el.closest('.list-item').find(".list-content").toggle('fast');
    el.closest('.list-item').toggleClass('active');
    var footerIndicatorSvg = el.closest('.list-item').find(".list-footer svg");
    var iconSet = ['fa-angle-down', 'fa-angle-up'];
    toggleIcon(footerIndicatorSvg, iconSet);
}

function isArrayEmpty(array) {
    return array.length === 0;
}

function appAjax(method, data, route, dataType) {
    // Default DataType is Data
    dataType = dataType || 'data';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var args = {
        url: route,
        method: method,
        data: data,
        dataType: "json",
    };

    // For Ajax File Upload
    if (dataType === 'file') {
        args.processData = false;
        args.contentType = false;
    }

    return $.ajax(args);
}

var app = {
    init: function () {
        app.set();
        app.isActive();
        app.interPhoneCode();
    },

    goBack: function () {
        window.history.back();
    },

    debug: function () {
        var cart = this.get();
        var html = '';
        for (let i = 0; i < cart.courses.length; i++) {
            const el = cart.courses[i];
            html += "<li>" + el.title + "</li>";
        }
        $('.debug').html(html);
    },

    selectCampus: function (campus, course, multi) {
        // Toggle Active Class;
        $('[data-campus="' + campus + '"]').toggleClass('active');
        var cart = this.get();

        // remove Campus from Cart if exist
        if (cart.campuses.indexOf(campus) >= 0) {
            cart.campuses.splice(cart.campuses.indexOf(campus), 1);
        } else {
            // Add Campus to Cart if Not Exist
            (cart.campuses).push(campus)
        }
        // Update Cart
        this.put(cart);

        // if the campus has one course only .. add the course to the selection
        if (typeof course != 'undefined') {
            this.selectCourse(course, campus, multi);
        } else {
            // if Campus has Multiple Courses
        }
    },

    selectCourse: function (course, campus, multi) {

        var title = $('[data-course="' + course + '"]').data('title');
        var slug = $('[data-course="' + course + '"]').data('slug');
        var fee = $('[data-course="' + course + '"]').data('fee');
        var cart = this.get();

        // if NOT Multi Select
        if (typeof multi == 'undefined') {
            cart.courses[0] = {
                id: course,
                slug: slug,
                fee: fee,
                title: title,
                campus: campus,
                dates: []
            };
            var message = 'You have selected ' + title
            this.showMessage(message);
        } else {
            if ((cart.courses).length == 0) {
                cart.courses.push({
                    id: course,
                    slug: slug,
                    fee: fee,
                    title: title,
                    campus: campus,
                    dates: []
                });
                var message = 'You have selected ' + title
                this.showMessage(message);
            } else {
                var courses = [];
                for (let i = 0; i < (cart.courses).length; i++) {
                    courses.push(cart.courses[i].id);
                }
                if (courses.indexOf(course) < 0) {
                    cart.courses.push({
                        id: course,
                        slug: slug,
                        fee: fee,
                        title: title,
                        campus: campus,
                        dates: []
                    })
                    var message = 'You have selected ' + title
                    this.showMessage(message);
                } else {
                    cart.courses = cart.courses.filter(function (item) {
                        if (item.id !== course) {
                            return item;
                        }
                    });
                }
            }
        }


        this.put(cart);
        app.isActive();
        //this.debug();
    },

    selectDate: function (dateKey, course, price, start, end) {
        var el = $('[data-date="' + dateKey + '"]');

        // retun if date is disabled
        if (el.hasClass('disabled')) {
            return;
        }
        el.toggleClass('active');

        if (el.hasClass('active')) {
            $('[data-date-footer="' + dateKey + '"]').removeClass('disabled');
        } else {
            $('[data-date-footer="' + dateKey + '"]').addClass('disabled');
        }


        // Disable All Dates with the same strat date
        $('[data-start="' + start + '"]').toggleClass('disabled');
        el.removeClass('disabled');

        if (el.hasClass('active')) {
            app.toggleSubMenu(el, 'expand');
        } else {
            app.toggleSubMenu(el, 'collapse');
        }


        var cart = this.get();


        for (let i = 0; i < cart.courses.length; i++) {
            if (cart.courses[i].id == course) {
                // Add First Date
                if (cart.courses[i].dates.length == 0) {
                    (cart.courses[i].dates).push({
                        key: dateKey,
                        price: price,
                        start: start,
                        end: end,
                        addons: {
                            activity: [],
                            excursion: [],
                            material: [],
                        },
                    })
                    var message = "You have selected " + cart.courses[i].title + ' From ' + start + " to " + end;
                    this.showMessage(message);

                    cart.dates.push(dateKey);
                } else {
                    if (cart.dates.indexOf(dateKey) < 0) {
                        cart.dates.push(dateKey);
                    } else {
                        // remove date If Date is selected
                        cart.dates.splice(cart.dates.indexOf(dateKey), 1);
                    }
                    // Remove Added Date or Add if not exist
                    var dates = [];
                    for (let c = 0; c < (cart.courses[i].dates).length; c++) {
                        dates.push(cart.courses[i].dates[c].key);
                    }
                    if (dates.indexOf(dateKey) < 0) {
                        (cart.courses[i].dates).push({
                            key: dateKey,
                            price: price,
                            start: start,
                            end: end,
                            addons: {
                                activity: [],
                                excursion: [],
                                material: [],
                            },
                        })

                        var message = "You have selected " + cart.courses[i].title + ' From ' + start + " to " + end;
                        this.showMessage(message);
                    } else {
                        (cart.courses[i].dates) = (cart.courses[i].dates).filter(function (item) {

                            if (item.key !== dateKey) {
                                return item;
                            } else {
                                // Remove Selected Activity
                                if (typeof item.addons.activity[0] != 'undefined') {
                                    var activiyKey = item.addons.activity[0].key;
                                    cart.addons.activity.splice(cart.addons.activity.indexOf(activiyKey), 1);
                                }


                                // Remove Materials and Exxcursions
                                var groups = ['material', 'excursion'];
                                groups.forEach(function (group) {
                                    item.addons[group].forEach(function (addon) {
                                        if (typeof cart.addons[group] != 'undefined') {
                                            cart.addons[group].splice(cart.addons[group].indexOf(addon.key), 1);
                                        }
                                    });
                                });
                            }

                        });
                    }
                }
            }
        }

        this.put(cart);
        app.isActive();
        //app.debug();
    },

    selectAddon: function (el) {

        if ($(el).hasClass('disabled')) {
            return false;
        }

        $(el).toggleClass('active')
        var addonKey = $(el).data('addon-key');
        var dateKey = $(el).data('date-key');
        var addonTitle = $(el).data('addon-title');
        var addonPrice = $(el).data('addon-price');
        var addonPriceType = $(el).data('addon-price-type');
        var addonDate = $(el).data('addon-date');

        if (typeof addonDate == 'undefined') {
            addonDate = null;
        }

        var multi = $(el).data('allow-multi');
        var group = $(el).data('addon-group');
        var cart = this.get();

        // disable all other Addons on the same date if date is specified
        if (addonDate) {
            var similar = $('[data-date-key="' + dateKey + '"][data-addon-date="' + addonDate + '"]');
            similar.not(el).toggleClass('disabled');
        }

        // if Activity Type allow Multiple Selection
        if (multi) {
            if (cart.addons[group].indexOf(addonKey) >= 0) {
                cart.addons[group].splice(cart.addons[group].indexOf(addonKey), 1);
            } else {
                // Add Campus to Cart if Not Exist
                (cart.addons[group]).push(addonKey)
            }

            cart.courses.forEach(function (course) {
                course.dates.forEach(function (date) {
                    if (date.key == dateKey) {
                        if (date.addons[group].length == 0) {
                            date.addons[group].push({
                                key: addonKey,
                                title: addonTitle,
                                date: addonDate,
                                price: addonPrice,
                                price_type: addonPriceType,
                            })
                        } else {
                            var addons = [];
                            for (let c = 0; c < (date.addons[group]).length; c++) {
                                addons.push(date.addons[group][c].key);
                            }
                            if (addons.indexOf(addonKey) < 0) {
                                (date.addons[group]).push({
                                    key: addonKey,
                                    title: addonTitle,
                                    date: addonDate,
                                    price: addonPrice,
                                    price_type: addonPriceType,
                                })
                            } else {
                                date.addons[group] = date.addons[group].filter(function (addon) {
                                    if (addon.key !== addonKey) {
                                        return addon;
                                    }
                                });
                            }
                        }
                    }
                })
            });
        } else { // if Activity type allow single selection
            // remove addon if it was selected
            if (cart.addons[group].indexOf(addonKey) == 0) {
                cart.addons[group] = [];
                return;
            }

            cart.addons[group] = [];

            cart.courses.forEach(function (course) {

                course.dates.forEach(function (date) {

                    if (date.key == dateKey) {
                        date.addons[group][0] = {
                            key: addonKey,
                            title: addonTitle,
                            date: addonDate,
                            price: addonPrice,
                            price_type: addonPriceType,
                        }
                    }

                    if (typeof date.addons[group][0] != 'undefined') {
                        cart.addons[group].push(date.addons[group][0].key);
                    }

                })
            });
        }
        this.put(cart);
        app.isActive();
    },

    selectAccommodation: function (el) {
        $(el).toggleClass('active');
        var option = $(el).data('accommodation-option');
        var price = $(el).data('accommodation-option-price');
        var key = $(el).data('accommodation-option-key');
        var cart = this.get();

        if (cart.accomodations.length == 0) {
            cart.accomodations.push({
                key: key,
                option: option,
                price: price,
            });
        } else {
            var accom = [];

            for (let c = 0; c < (cart.accomodations).length; c++) {
                accom.push(cart.accomodations[c].key);
            }

            if (accom.indexOf(key) < 0) {
                (cart.accomodations).push({
                    key: key,
                    option: option,
                    price: price,
                })
            } else {
                cart.accomodations = cart.accomodations.filter(function (item) {
                    if (item.key !== key) {
                        return item;
                    }
                });
            }
        }
        this.put(cart);
        app.isActive();
    },

    selectTransfer: function (el) {
        $(el).toggleClass('active');
        var option = $(el).data('transfer-option');
        var price = $(el).data('transfer-option-price');
        var key = $(el).data('transfer-option-key');
        var cart = this.get();

        if (cart.transfer.length == 0) {
            cart.transfer.push({
                key: key,
                option: option,
                price: price,
            });
        } else {
            var transfer = [];

            for (let c = 0; c < (cart.transfer).length; c++) {
                transfer.push(cart.transfer[c].key);
            }

            if (transfer.indexOf(key) < 0) {
                (cart.transfer).push({
                    key: key,
                    option: option,
                    price: price,
                })
            } else {
                cart.transfer = cart.transfer.filter(function (item) {
                    if (item.key !== key) {
                        return item;
                    }
                });
            }
        }
        this.put(cart);
        app.isActive();
    },

    selectMisc: function (el) {

        $(el).toggleClass('active');
        var option = $(el).data('misc-option');
        var price = $(el).data('misc-option-price');
        var key = $(el).data('misc-option-key');
        var cart = this.get();

        if (isArrayEmpty(cart.miscellaneous)) {
            cart.miscellaneous.push({
                key: key,
                option: option,
                price: price,
            });
        } else {
            var misc = [];

            for (let c = 0; c < (cart.miscellaneous).length; c++) {
                misc.push(cart.miscellaneous[c].key);
            }

            if (misc.indexOf(key) < 0) {
                (cart.miscellaneous).push({
                    key: key,
                    option: option,
                    price: price,
                });
            } else {
                cart.miscellaneous = cart.miscellaneous.filter(function (item) {
                    if (item.key !== key) {
                        return item;
                    }
                });
            }
        }

        this.put(cart);
        app.isActive();
    },

    applyPromocode: function (rute) {
        var quotation = $("meta[name='quotation']").attr("content");
        promocode = $('#promocode').val();
        help = $('#promocode-help');
        price = $('#price');
        total_before_discount = $('#total_before_discount');
        total_container = $('#total_container');
        total = $('#total');
        cart_input = $('input[name=cart]');
        var cart = this.get();
        var data = {
            payload: {
                promocode: promocode,
                quotation: quotation,
                cart: JSON.stringify(cart)
            }
        };

        appAjax('GET', data, rute).then(function (data) {
            if (data.response === 'success' && data.status === 200) {
                help.removeClass('text-danger');
                help.addClass('text-success');

                help.text('Promo Code Applied');

                // cart.price = data.cart.price;
                // cart.discount = data.code;
                cart = data.cart;

                cart = JSON.stringify(cart);
                cart_input.val('');
                cart_input.val(cart);

                total.text(data.price.total);
                total_before_discount.text(data.price.total_before_discount);

                Cookies.remove('cart_' + quotation);

                Cookies.set('cart_' + quotation, cart, {});


                total_container.show('fast');
                $('#promo-inputs').hide('fast');

            } else {
                help.removeClass('text-success');
                help.addClass('text-danger');

                help.text('This is not a correct promo Code');
            }
            help.show('fast');
        });
    },

    isValid(el) {
        var route = $(el).data('route');
        var step = $(el).data('step');
        var message = $(el).data('message');
        var cart = this.get();
        var isValid = false;
        if (step === 'campus-course') {
            if (cart.campuses.length == 0 && cart.courses.length == 0) {
                this.showErrors(message);
            } else {
                isValid = true;
            }
        }
        if (step == 'date-addons') {
            for (let i = 0; i < cart.courses.length; i++) {
                const dates = cart.courses[i].dates;
                if (dates.length == 0) {
                    this.showErrors(message);
                    return;
                }
            }
            isValid = true;
        }


        if (step == 'transfer' || step == 'accomodations' || step == 'miscellaneous') {
            isValid = true;
        }

        if (!isValid) {
            return false;
        }
        // if is valid redirect to the next page
        window.location = route
    },

    showMessage: function (message) {
        new Noty({
            type: 'success',
            timeout: 3000,
            layout: 'topRight',
            theme: 'mint',
            closeWith: ['click', 'button'],
            text: message,
        }).show();
    },

    showErrors: function (message) {
        new Noty({
            killer: true,
            type: 'error',
            layout: 'topRight',
            theme: 'mint',
            closeWith: ['click', 'button'],
            text: message,
        }).show();
    },

    isActive: function (namespace) {
        var cart = this.get();
        // Reset Active
        $('[data-course]').removeClass('active');
        $('[data-campus]').removeClass('active');
        $('[data-date]').removeClass('active');
        $('[data-transfer-option-key]').removeClass('active');
        $('[data-accomodations-option-key]').removeClass('active');

        $('[data-addon-key]').removeClass('active').removeClass('disabled');

        //$('[data-addon-date]').removeClass('disabled');

        if ((cart.courses).length) {
            for (let i = 0; i < (cart.courses).length; i++) {
                $('[data-course="' + cart.courses[i].id + '"]').addClass('active');
                $('[data-campus="' + cart.courses[i].campus + '"]').addClass('active');

                if (cart.courses[i].dates.length > 0) {
                    for (let c = 0; c < (cart.courses[i].dates).length; c++) {
                        var date = $('[data-date="' + cart.courses[i].dates[c].key + '"]');
                        $('[data-date-footer="' + cart.courses[i].dates[c].key + '"]').removeClass('disabled')
                        date.addClass('active');

                        $('[data-start="' + date.data('start') + '"]').addClass('disabled');
                        date.removeClass('disabled');
                    }
                }
            }
        }

        var groups = ['activity', 'material', 'excursion'];
        // Add Active Class and Remove Disabled Class for selected Addons
        groups.forEach(function (group) {
            if (typeof cart.addons[group] != 'undefined') {
                cart.addons[group].forEach(function (addon) {
                    var element = $('[data-addon-key="' + addon + '"]');
                    $(element).addClass('active').removeClass('disabled');


                    var date = $(element).data('addon-date');
                    var dateKey = $(element).data('date-key');
                    var multi = $(element).data('allow-multi');
                    // Disable Others
                    if (multi) {
                        $('[data-addon-date="' + date + '"][data-addon-group="' + group + '"]').not(element).addClass('disabled');
                    }
                });
            }

        });

        // Add Active Class to Transfer
        if ((cart.accomodations).length) {
            cart.accomodations.forEach(function (accomodations) {
                $('[data-accomodations-option-key="' + accomodations.key + '"]').addClass('active');
            });
        }
        if ((cart.transfer).length) {
            cart.transfer.forEach(function (transfer) {
                $('[data-transfer-option-key="' + transfer.key + '"]').addClass('active');
            });
        }

        if ((cart.miscellaneous).length) {
            cart.miscellaneous.forEach(function (misc) {
                $('[data-misc-option-key="' + misc.key + '"]').addClass('active');
            });
        }
    },

    toggleSubMenu: function (el, action) {

        if (action == 'expand') {
            el.next(".list-content.expandable").slideDown('fast');
        } else {
            el.next(".list-content.expandable").slideUp('fast');
        }
        var footerIndicatorSvg = el.closest('.list-item').find(".list-footer svg");
        var iconSet = ['fa-angle-down', 'fa-angle-up'];
        toggleIcon(footerIndicatorSvg, iconSet);
    },

    set: function () {
        var quotation = $("meta[name='quotation']").attr("content");
        var step = $("meta[name='step']").attr("content");
        var firstStep = $("meta[name='first_step']").attr("content");
        var cart = false;
        if (firstStep != step) {
            cart = this.get();
        }

        if (!cart) {
            Cookies.set('cart_' + quotation, JSON.stringify({
                campuses: [],
                courses: [],
                dates: [],
                discount: [],
                addons: {
                    activity: [],
                    excursion: [],
                    material: [],
                },
                accomodations: [],
                transfer: [],
                miscellaneous: [],
                price: []
            }), {
                /*  expires: 1 */
            });
        }
    },

    get: function () {
        var quotation = $("meta[name='quotation']").attr("content");
        var cart = Cookies.get('cart_' + quotation);
        if (typeof cart != 'undefined') {
            return JSON.parse(cart);
        }
        return false;
    },

    put: function (data) {
        var quotation = $("meta[name='quotation']").attr("content");
        Cookies.remove('cart_' + quotation);

        rr = Cookies.set('cart_' + quotation, JSON.stringify(data), {
            /* expires: 1 */
        });
        //console.log(rr);
    },
    interPhoneCode: function () {
        if ($('.inter-calling-code').length > 0) {
            $('.inter-calling-code').each(function () {
                var disable = false;
                var number = $(this).val();
                var code = $(this).data('code');
                disable = $(this).data('disable');
                var lang = $(this).data('lang');
                var countryCode = $(this).data('country-code');
                var countryCodeNumber = $(this).data('countrycode-number');
                if (code) {
                    number = number.replace(code, '');
                }
                console.log("countryCode");
                console.log(countryCode);
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
    },

    accountTypeChange: function (el) {
        AccountType = el.value;
        agency_name = $("input[name='agency_name']");
        agency_email = $("input[name='agency_email']");
        if (AccountType === 'agent') {
            $('.agency').show('fast');
            agency_name.prop('required', true);
            agency_email.prop('required', true);
            $('.agency_email')
        } else {
            $('.agency').hide('fast');
            agency_name.prop('required', false);
            agency_email.prop('required', false);
        }
    },

    showPromocodeInput: function () {
        $('#promo-container-link').hide('fast');
        $('#promo-container-input').show('fast');
    },
};


$(document).ready(function () {
    app.init();

    //slide toggle effect for list item
    $(".list-item .list-footer").click(function () {

        if ($(this).hasClass('disabled')) {
            return;
        }

        compileToggleScript($(this));
    });

    //Check box toggle
    $(".list-item .list-header input[type='checkbox']").click(function () {

        var parentListItem = $(this).closest('.list-item');

        if ((this.checked)) {
            //if checkbox is checked and current .list-item is not active toggle slide content
            if (!parentListItem.hasClass('active')) {
                compileToggleScript($(this));
            }
        } else {
            //if checkbox is not checked and current .list-item is active toggle slide content
            if (parentListItem.hasClass('active')) {
                compileToggleScript($(this));
            }

            //uncheck all
            var parentContentCheckbox = $(this).closest('.list-item').find('.list-content input[type="checkbox"]:checked');
            parentContentCheckbox.prop('checked', false);
        }
    });

    //Asign attribute check on header checkbox if one of the current list-item content-item checkbox is checked
    //Temporary list content checkbox array

    //Add class show btn to next btn if header checkbox is checked

    var arraySizeCounter = 0;
    $(".list-item .list-content input[type='checkbox']").click(function () {

        if ((this.checked)) {
            arraySizeCounter++;
        } else {
            if (arraySizeCounter > 0) {
                arraySizeCounter--;
            }
        }

        //get header checkbox of the parent list item
        var parentHeaderCheckbox = $(this).closest('.list-item').find('.list-header input[type="checkbox"]');

        if (arraySizeCounter > 0) {
            parentHeaderCheckbox.prop('checked', true);
            $('.section-footer-nav .next').addClass('show');
        } else {}
    });

    //slide toggle effect for list program-item
    $(".program-list-item .program-list-header").click(function () {

        //Declare iconset for + and -
        var iconSet = ['fa-plus', 'fa-minus'];


        //the current Program-list-content of the clicked .program-list-item header
        var currentProgramListContent = $(this).closest('.program-list-item').find(".program-list-content");

        if (currentProgramListContent.hasClass('active')) {
            //if clicked Program-list-content has an active class  - close it and remove class active
            currentProgramListContent.slideToggle('fast').toggleClass('active');
            toggleIcon($(this).find('svg'), iconSet);
        } else {
            //if clicked Program-list-content is not currently active check siblings

            //All the program list item inside program-list-container class
            var programListItems = $('.program-list-container').find('.program-list-item');

            //Check siblings program-list-item if there's a current active program-list-content
            programListItems.each(function () {
                if ($(this).find(".program-list-content").hasClass('active')) {
                    //Toggle close if there's an active program-list-content and remove class active
                    $(this).find(".program-list-content").slideToggle('fast').toggleClass('active');
                    toggleIcon($(this).closest('.program-list-item').find('.program-list-header svg'), iconSet);
                }
            });

            // Toggle the current Program-list-content of the clicked .program-list-item header
            currentProgramListContent.slideToggle('fast').toggleClass('active');
            toggleIcon(currentProgramListContent.closest('.program-list-item').find('.program-list-header svg'), iconSet);
        }


    });

    //Form toggle between Sendquote form and login/signup form
    $('.form-toggle-btngroup button').click(function () {
        var formID = $(this).data("form");
        $('.form-toggle-btngroup button.active').toggle();
        // Add active class to the button
        $(this).addClass('active');
        //toggle send request to email and book now base on toggle-form attribute
        $('.form-toggle .form-wrapper.active').toggle('fast').removeClass('active');
        $(`#${formID}`).toggleClass('active').toggle('fast');
        app.interPhoneCode();
    });

    //Form toggle login and signup form
    $('.log-form-btn').click(function () {
        $('.log-form-toggle').fadeToggle('fast').toggleClass('active');
    });
    //Script for deactivate active next prev btn
    var currentNavLocation = $('nav ul li.active').index();
});
