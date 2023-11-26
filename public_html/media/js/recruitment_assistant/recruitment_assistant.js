var app = {
    init: function () {
        app.set();
        app.isActive();
        // app.interPhoneCode();
    },
    statusCheck : null,
    conferenceDetailsChecker : null,
    conference_id : null,
    statusRoute: null,
    conferenceRoute : null,
    call_sid : null,
    set: function () {
        var assistantBuilder = $("meta[name='assistantBuilder']").attr("content");
        var step = $("meta[name='step']").attr("content");
        var firstStep = $("meta[name='first_step']").attr("content");
        var cart = false;

        if (firstStep !== step) {
            cart = this.get();
        }
        if (!cart) {
            Cookies.set('assistant_cart_' + assistantBuilder, JSON.stringify({
                campuses: [],
                courses: [],
                programs: [{
                    program:'',
                    start:'',
                    end:'',
                }],
                financials: [],
            }));
        }
    },

    get: function () {
        var assistantBuilder = $("meta[name='assistantBuilder']").attr("content");
        var cart = Cookies.get('assistant_cart_' + assistantBuilder);
        if (typeof cart !== 'undefined') {
            return JSON.parse(cart);
        }
        return false;
    },

    put: function (data) {
        var assistantBuilder = $("meta[name='assistantBuilder']").attr("content");
        Cookies.set('assistant_cart_' + assistantBuilder, JSON.stringify(data));
        //Cookies.remove('assistant_cart_' + assistantBuilder);
    },

    selectCampus: function (campus, multi , modal) {

        if(typeof modal == 'undefined'){
            modal = false;
        }

        // Toggle Active Class;
        $('[data-campus="' + campus + '"]').toggleClass('active');

        var cart = this.get();
        
        // remove Campus from Cart if exist
        if (cart.campuses.indexOf(campus) >= 0 && !modal) {

            console.log("Not Modal");
            
            cart.campuses.splice(cart.campuses.indexOf(campus), 1);
            
        } else {
            
            console.log("Modal");
            
            // Add Campus to Cart if Not Exist
            (cart.campuses).push(campus);
            
        }
        // Update Cart
        this.put(cart);
    },

    selectProgram: function (program, multi) {
        // Toggle Active Class;
        $('[data-program="' + program + '"]').toggleClass('active');
        var cart = this.get();

        if (typeof multi == 'undefined' || !multi) {
            cart.programs[0] = {
                program: program,
                dates: []
            };
            
            var message = 'You have selected';
            this.showMessage(message);
        } else {
            if ((cart.programs).length == 0) {
                cart.programs.push({
                    program: program,
                    dates: []
                });
                var message = 'You have selected';
                this.showMessage(message);
            } else {
                var programs = [];
                for (let i = 0; i < (cart.programs).length; i++) {
                    programs.push(cart.programs[i].id);
                }
                if (programs.indexOf(program) < 0) {
                    cart.programs.push({
                        program: program,
                        dates: []
                    })
                    var message = 'You have selected';
                    this.showMessage(message);
                } else {
                    cart.programs = cart.programs.filter(function (item) {
                        if (item.id !== program) {
                            return item;
                        }
                    });
                }
            }
        }
        
        app.isActive();

        // Update Cart
        this.put(cart);
    },

    selectCourse: function (course, multi) {
        // Toggle Active Class;
        $('[data-course="' + course + '"]').toggleClass('active');
        var cart = this.get();

        var data = {course: course, dates: []};

        // remove Campus from Cart if exist
        if (cart.courses.indexOf(data) >= 0) {
            cart.courses.splice(cart.courses.indexOf(data), 1);
        } else {
            // Add Campus to Cart if Not Exist
            (cart.courses).push(data);
        }
        // Update Cart
        this.put(cart);
    },

    selectFinancial: function (financial, multi) {
        // Toggle Active Class;
        $('[data-financial="' + financial + '"]').toggleClass('active');
        var cart = this.get();

        // remove Campus from Cart if exist
        if (cart.financials.indexOf(financial) >= 0) {
            cart.financials.splice(cart.financials.indexOf(financial), 1);
        } else {
            // Add Campus to Cart if Not Exist
            (cart.financials).push(financial);
        }
        // Update Cart
        this.put(cart);
    },

    selectProgramDate: function (program, start, end ,schudel,el) {

        var cart = this.get();
        cart.programs[0] = {
            program: program,
            start: start,
            end: end,
            schudel: schudel,
        };
        this.put(cart);
        app.isActive();
    },

    selectDate: function (dateKey, course, price, start, end) {
        var el = $('[data-date="' + dateKey + '"]');

        // retun if date is disabled
        if (el.hasClass('disabled')) {
            return;
        }
        el.toggleClass('active');

        var cart = this.get();


        for (let i = 0; i < cart.courses.length; i++) {
            if (cart.courses[i].course == course) {
                // Add First Date
                if (cart.courses[i].dates.length == 0) {
                    (cart.courses[i].dates).push({
                        key: dateKey,
                        price: price,
                        start: start,
                        end: end,
                        schudel: schudel,
                    });
                    var message = "You have selected From " + start + " to " + end;
                    this.showMessage(message);

                    //cart.dates.push(dateKey);
                } else {
                    if (cart.courses[i].dates.indexOf(start) < 0) {
                        (cart.courses[i].dates).push({
                            key: dateKey,
                            price: price,
                            start: start,
                            end: end,
                            schudel: schudel,
                        });
                        var message = "You have selected From " + start + " to " + end;
                        this.showMessage(message);
                    } else {
                        // remove date If Date is selected
                        cart.courses[i].dates.splice(cart.dates.indexOf(start), 1);
                    }
                }
            }
        }

        this.put(cart);
        // app.isActive();
        //app.debug();
    },
    /**
     * Requesting Call Back , Finding the admission or queue for later
     * @param {*} form 
     */
    requestCallBack: function(form){

        app.statusCheck = null;
        app.conferenceDetailsChecker = null;
        app.conference_id = null;
        app.call_sid = null;
        
        var route = $(form).attr('action');
        var statusRoute = app.statusRoute =  $(form).data('status-route');
        var conferenceRoute = app.conferenceRoute = $(form).data('conference-route');

        event.preventDefault();
        var formData = new FormData(form);

        app.appAjax('POST' , formData , route , 'form')
        .then(function (data) {
            
            if(data.response == 'success'){

               $('#modal-content-wrapper').html(data.extra.html);

                 
                if(data.extra.status == 'pending'){

                    if(data.extra.conferenceName){
                        
                        app.call_sid = data.extra.call_sid

                        $('.call-status strong').html('Connecting you with one of our advisors');

                        app.conferenceDetailsChecker = setInterval(function(){ 
                            app.getconferenceDetails(data.extra.conferenceName , statusRoute , formData , conferenceRoute);
                        }, 2000);



                        setTimeout(function(){ 
                            
                            if(!app.conference_id){
                                
                                $('.call-status strong').html('Sorry, It seems that all our advisors are busy.<br/>We will contact you shortly');
                            }
                            clearInterval( app.conferenceDetailsChecker ); 
                            
                        }, 20000);

                    }
                }
                
               
            }          
        }).catch(function (error) {
            var errors = $.parseJSON(error.responseText).errors
            app.displayErrors(errors, form);
        });


        $('#small-modal-dialog').on('hidden.bs.modal', function (e) {
            clearInterval(app.conferenceDetailsChecker);
        });

               
    },
    /**
     * Getting the Conference Details
     * @param {*} conferenceName 
     * @param {*} statusRoute 
     * @param {*} formData 
     * @param {*} conferenceRoute 
     */
    getconferenceDetails: function(conferenceName , statusRoute , formData , conferenceRoute){

        if(!conferenceName){
            return false;
        }
         var data = {
            conferenceName : conferenceName
        }

        app.appAjax('POST' , data , statusRoute , 'object')
        .then(function (data) {
           if(data.extra.conference_sid){

                $('.call-status').addClass('call-status-done').removeClass('call-status')
               
                $('.call-status-done strong').html('Calling you!');
                
                clearInterval(app.conferenceDetailsChecker);
                app.conference_id = data.extra.conference_sid;
                
                formData.append('conference',  data.extra.conference_sid);

                //Add Student to the conference
                app.addtoConference( formData, conferenceRoute);
                
            }

        }).catch(function (error) {
            var errors = $.parseJSON(error.responseText).errors
            app.displayErrors(errors, form);
        });

        $('#small-modal-dialog').on('hidden.bs.modal', function (e) {
            clearInterval(app.conferenceDetailsChecker);
        });

    },
    /**
     * Calling the Student and connecting him to the conferenace
     * @param {*} formData 
     * @param {*} conferenceRoute 
     */
    addtoConference: function(formData , conferenceRoute){

        app.appAjax('POST' , formData , conferenceRoute , 'form')
        .then(function (data) {
            
            // Check Call Status
            app.statusCheck = setInterval(function(){ 
                
                app.getCallStatus(app.call_sid , formData);

            }, 2000);

        }).catch(function (error) {
            var errors = $.parseJSON(error.responseText).errors
            app.displayErrors(errors, form);
        });
        
    },
    getCallStatus: function(callSid  , formData){
        if(!callSid){
            return false;
        }
        
        formData.append( 'callSid' , callSid);

        app.appAjax('POST' , formData , app.statusRoute , 'form')
        .then(function (data) {

            
            $('.call-status-done strong').html(data.extra.status);

            if(data.extra.status == 'completed' || data.extra.status == 'Completed'){
                clearInterval(app.statusCheck);
                $('#small-modal-dialog').modal('hide');
            }


        }).catch(function (error) {
            var errors = $.parseJSON(error.responseText).errors
            app.displayErrors(errors, form);
        });
        

        $('#small-modal-dialog').on('hidden.bs.modal', function (e) {
            clearInterval(app.statusCheck);
        });

    },

    displayErrors : function (errors , form) {
    },

    appAjax : function (method , data , route , dataType ) {
    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        var args = {
            url           : route,
            method        : method,
            data          : data,
            dataType      : "json",
            
        };

        if(dataType == 'form'){
            args.processData   = false;
            args.contentType   = false;
        }
        return $.ajax(args);
    },

    // Open Modal
    openSmallModal : function (route) {
        $('#small-modal-dialog .modal-body').load(route ,function () {
            $('#small-modal-dialog').modal('show');
        });
    },

    showCallMeBack: function (route ) {
        app.openSmallModal(route);
    },
    
    showCampusInformation: function (route ) {
        app.openModal('modal-dialog', route);
    },

    showProgramInformation: function (route ) {
        app.openModal('modal-dialog', route);
    },

    showCourseInformation: function (route ) {
        app.openModal('modal-dialog', route);
    },

    openModal: function (id, route ) {

        $('#modal-dialog .modal-content .btn-ok').attr('id' , id);


        $('#modal-dialog .modal-body').load(route ,function () {

            $('#form-modal').modal('show');
        });

    },

    dissmisHelper: function(){
        
        if($('.helper-teaser').is(':visible')){
            $('.helper-container').fadeOut('fast');
        }else{
            $('.helper-teaser').show();
            $('.helper-content').hide();
        }

    },
    showHelp: function(){
        if($('.helper-teaser').is(':visible')){
            $('.helper-teaser').hide();
        }

        if($('.helper-content').is(':visible')){
            $('.helper-container').fadeOut('fast');
        }else{
            $('.helper-container').fadeIn('fast')
            $('.helper-content').show();  
        }
    },
    dismissModal : function (modal, route, element) {
       
        var cart = this.get();
        var isValid = false;

        if (modal == 'apply') {
            
            for (let i = 0; i < cart.programs.length; i++) {
                const dates = cart.programs[i].start;
                if (dates.length == 0) {
                    this.showErrors("Please, Select start date");
                    return;
                }
            }
            isValid = true;
        }

        if (modal == 'campus') {
            app.selectCampus(element, false, true);
            isValid = true;
        }

        app.redirect(route);

        if (isValid) {
            $('.bs-example-modal-lg').modal('hide');
        }
    },

    isValid: function (el) {

        var route = $(el).data('route');
        var step = $(el).data('step');
        var message = $(el).data('message');
        var cart = this.get();
        
        if (step === 'campus') {
            if (cart.campuses.length == 0 && cart.courses.length == 0) {
                this.showErrors(message);
            } else {
                isValid = true;
            }
        }

        if (step === 'programs') {
            for (let i = 0; i < cart.programs.length; i++) {
                const dates = cart.programs[i].start;
                console.log("dates")
                console.log(dates)
                if (dates.length == 0) {
                    this.showErrors(message);
                    return;
                }
            }
            isValid = true;
        }

        if (step === 'courses') {
            // for (let i = 0; i < cart.courses.length; i++) {
            //     const dates = cart.courses[i].dates;
            //     if (dates.length == 0) {
            //         this.showErrors(message);
            //         return;
            //     }
            // }
            isValid = true;
        }

        if (step === 'financial') {
            isValid = true;
        }

        
        if (!isValid) {
            return false;
        }
        // if is valid redirect to the next page

        window.location = route;
    },

    isActive: function (namespace) {
        var cart = this.get();
       

        // Reset Active
        $('[data-course]').removeClass('active');
        $('[data-campus]').removeClass('active');
        $('[data-date]').removeClass('active');
        $('[data-program]').removeClass('active');

        if ((cart.campuses).length) {
            for (let i = 0; i < (cart.campuses).length; i++) {
                $('[data-campus="' + cart.campuses[i] + '"]').addClass('active');
            }
        }

        if ((cart.programs).length) {
            for (let i = 0; i < (cart.programs).length; i++) {
                $('[data-program="' + cart.programs[i].program + '"]').toggleClass('active');

                $('[data-program-start="' + cart.programs[i].start + '"]').toggleClass('active');
            }
        }
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

    findWithAttr: function (array, attr, value) {
        for (let i = 0; i < array.length; i += 1) {
            if (array[i][attr] === value) {
                return i;
            }
        }
        return -1;
    },

    redirect : function (link) {
        window.location.href = link;
        return false;
    },
};

$(document).ready(function () {

    app.init();


    

    //toggle send request to email and book now base on toggle-form attribute
    $('.form-toggle .form-wrapper.active').toggle('fast').removeClass('active');
    $('#bookNowFormWrapper').toggleClass('active').toggle('fast');

    // //Form toggle login and signup form
    $('.log-form-btn').click(function () {
        $('.log-form-toggle').fadeToggle('fast').toggleClass('active');
    });

    //Form toggle between Sendquote form and login/signup form
    $('.form-toggle-btngroup button').click(function () {

        var formID = $(this).data("form");
        $('.form-toggle-btngroup button.active').toggle("slide");

        // Add active class to the button
        $(this).addClass('active');

        //toggle send request to email and book now base on toggle-form attribute
        $('.form-toggle .form-wrapper.active').toggle('fast').removeClass('active');
        $(`#${formID}`).toggleClass('active').toggle('fast');

    });
});