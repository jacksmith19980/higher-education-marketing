/*$(window).on('load',function () {*/
jQuery(document).ready(function ($) {

   
    //$('.select2').select2();

    /*Fix when you select the already active value*/
    $('.select2').on('select2:closing', function (evt) {
        $(this).trigger('change');
    });
    /*call select2 plugin*/
   // $('.settings_language').select2();

    /*Fix when you select the already active value*/
   /* $('.settings_language').on('select2:closing', function (evt) {
        $(this).trigger('change');
    });*/

    var startSettingsTour = function () {
        $('body').itour({
            showAbsentElement:true,
            tourMapPos:'right',                  //Tour Map Position
            tourMapVisible:true,
            tourTitle:'School Settings',
            introCover:'assets/images/itour/complete_intro2.png',
            introShow:true,

            steps:[
            {
                title:'Language',
                content:'Select the primary language of your school',
                name:'.settings_language + .select2',
                event:'click',
                loc:'/settings',
            },{
                title:'Choose a Language',
                /*Specify an element with pseudo-options for highlight*/
                name:'.select2-dropdown',
                content:'This are the available languages',
                /*Specify an event "change" and element for this event in array*/
                event:['change',$('.settings_language')],
                //event:'next',
                delayBefore:200,
                before:function () {
                    $('.settings_language').select2("open");
                },

            },{
                title:'Currency',
                content:'Change the default currency used for payment',
                name:'.settings_default_currency + .select2',
                event:'click',
                loc:'/settings'
            },{
                title:'Choose a Currency',
                /*Specify an element with pseudo-options for highlight*/
                name:'.select2-dropdown',
                content:'This are the available currencies',
                /*Specify an event "change" and element for this event in array*/
                //event:'next',
                event:['change', $('.settings_default_currency')],
                delayBefore:200,
                before:function () {
                    $('.settings_default_currency').select2("open");
                }
            },{
                title:'Send Emails From Name',
                content:'Verify if this is correct - it can be changed at a later time',
                name:'settings_email_name_from',
                skip: true,
                loc:'/settings'
            },{
                title:'Send Email From',
                content:'Verify if this is correct - it can be changed at a later time',
                name:'settings_email_address_from',
                loc:'/settings'
            },{
                title:'Save your Progress',
                content:'Click save & we\'ll move onto the next section',
                name:'settings_school_save_button',
                loc:'/settings'
            }],
            lang:{
                introTitle:'Welcome to your guided tour!',
                introContent:'This tour will take you through everything you need to setup your new account, step-by-step.',
            }
        });
        return false;
    };

    var brandingTour = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'Branding Settings',
            introShow:true,
            tourMapPos:'right',                  //Tour Map Position
            tourMapVisible:true,
            steps:[
            {
                title:'Branding Tab',
                content:'Select Branding Tab',
                name:'[href="#v-pills-branding"]',
                trigger:'click',
                loc:'/settings',
                after:function () {
                    var q1 = '<input type="hidden" id="hash" name="hash" value="' + location.hash.substr(1) + '">';
                    $('#brand_settings_div').append(q1);
                }
            },
            {
                title:'Logo',
                content:'Upload your institution\'s logo',
                name:'.settings_logo',
                loc:'/settings'
            },
            {
                title:'Save your Progress',
                content:'Click save & we\'ll move onto the next section',
                name:'settings_branding_save_button',
                trigger:'click',
                loc:'/settings'
            }],
            lang:{
                introTitle:'Branding Tour 2/5',
                introContent:'In this tour we\'re going to configure the branding settings',
            }
        });
        return false;
    };

    var loginTour = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'Login and Register Settings',
            introShow:true,
            tourMapPos:'right',                  //Tour Map Position
            tourMapVisible:true,
            steps:[
            {
                title:'Login and Register Tab',
                content:'Select Login and Register Tab',
                name:'[href="#v-pills-login-register"]',
                trigger:'click',
                loc:'/settings',
                after:function () {
                    var q1 = '<input type="hidden" id="hash" name="hash" value="' + location.hash.substr(1) + '">';
                    $('#login_settings_div').append(q1);
                }
            },
            {
                title:'Background',
                content:'Upload an image users will see on your login & register pages. Ideal size 1920x1080 pixels.',
                name:'.settings_login_background',
                loc:'/settings'
            },
            // {
            //     title:'Enable reCaptcha',
            //     content:'Added security that protects your forms from fraud and abuse',
            //     name:'.settings_enable_recaptcha + .select2',
            //     event:'click',
            //     loc:'/settings'
            // },
            // {
            //     title:'Select',
            //     /*Specify an element with pseudo-options for highlight*/
            //     name:'.select2-dropdown',
            //     content:'Select an option',
            //     /*Specify an event "change" and element for this event in array*/
            //     event:['change', $('.settings_enable_recaptcha')],
            //     delayBefore:200,
            //     before:function () {
            //         $('.settings_enable_recaptcha').select2("open");
            //     }
            // },
            {
                title:'Save your Progress',
                content:'Click save & we\'ll move onto the next section',
                name:'settings_login_save_button',
                trigger:'click',
                loc:'/settings'
            },
            ],
            lang:{
                introTitle:'Login Tour 3/5',
                introContent:'In this tour we\'re going to configure the Login settings',
            }
        });
        return false;
    };

    var usersTour = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'Users Settings',
            introShow:true,
            tourMapPos:'right',                  //Tour Map Position
            tourMapVisible:true,
            steps:[
            {
                title:'Users Tab',
                content:'Select users Tab',
                name:'[href="#v-pills-users"]',
                trigger:'click',
                loc:'/settings',
                after:function () {
                    var q1 = '<input type="hidden" id="hash" name="hash" value="' + location.hash.substr(1) + '">';
                    $('#users_settings_div').append(q1);
                }
            },
            {
                title:'Invite users',
                content:'Add colleagues to help manage this account (optional)',
                name:'settings_invite_user_form',
                loc:'/settings',
                during:function(){
                    $('html').removeClass('hNavOpen');
                    $('.hNavBtn').hide();
                },
            },
            {
                title:'Invite Users',
                content:'Click "Invite Users" to add them. They will receive an email with their login details. Also click in "Invite Users" if you leave the inputs empty',
                name:'settings_users_save_button',
                loc:'/settings'
            }],
            lang:{
                introTitle:'Users Tour 4-5/5',
                introContent:'In this tour we\'re going to configure the users and invitation settings and tracking settings',
            }
        });
        return false;
    };

    var inviteTour = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'Users Settings',
            introShow:false,
            steps:[
            // {
            //     title:'Users Tab',
            //     content:'Select users Tab',
            //     name:'[href="#v-pills-users"]',
            //     trigger:'click',
            //     loc:'/settings',
            // },
            // {
            //     title:'Invite users',
            //     content:'System users will be listed here',
            //     name:'settings_last_invited_user',
            //     loc:'/settings'
            // },
            // {
            //     title:'Invite users',
            //     content:'Modify user\'s account',
            //     name:'settings_last_invited_user_actions',
            //     loc:'/settings'
            // },
            {
                title:'Tracking Tab',
                content:'Select Tracking Tab',
                name:'[href="#v-pills-tracking"]',
                trigger:'click',
                loc:'/settings',
                after:function () {
                    var q1 = '<input type="hidden" id="hash" name="hash" value="' + location.hash.substr(1) + '">';
                    $('#users_settings_div').append(q1);
                }
            },
            {
                title:'Enable Google Tracking',
                content:'Click here to active Google Tracking',
                name:'settings_tracking_google_switch',
                loc:'/settings'
            },
            {
                title:'Enable Google Tracking',
                content:'If you activated the tracking here you must put the ID',
                name:'settings_tracking_google_gtm_id',
                loc:'/settings'
            },
            {
                title:'Enable Google Tracking',
                content:'If you activated the tracking here you must put analytics ID',
                name:'settings_tracking_google_analytics',
                loc:'/settings'
            },
            {
                title:'Enable Facebook Tracking',
                content:'Click here to active Facebook Tracking',
                name:'settings_tracking_facebook_switch',
                loc:'/settings'
            },
            {
                title:'Enable Facebook Tracking',
                content:'If you activated Facebook tracking here you must put the ID',
                name:'settings_tracking_facebook_id',
                loc:'/settings'
            },
            {
                title:'Save',
                content:'Click save',
                name:'settings_tracking_save_button',
                trigger:'click',
                loc:'/settings'
            }]
        });
        return false;
    };

    //Application tours
    var newApplication = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'New Application',
            introShow:true,
            steps:[
                {
                    title:'New Application',
                    content:'Select the type of application to create',
                    name:'dropdown-toggle',
                    event:'click',
                    loc:'/applications'
            },
                {
                    title:'Select Application Type',
                    name:'.student-application',
                    content:'Available application type(s)',
                    delayBefore:100,
            }
            ],
            lang:{
                introTitle:'Welcome to the new application guided tour!',
                introContent:'This tour will take you through everything you need to create a new application, step by step.',
            }
        });
        return false;
    };

    var addApplication = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'New Application',
            introShow:false,
            steps:[
                {
                    title:'Application Title',
                    content:'Set the application title',
                    name:'add_application_title',
                    after:function () {
                        var q1 = '<input type="hidden" id="hash" name="hash" value="application">';
                        $('#steps-uid-0-p-0').append(q1);
                    }
            },
                {
                    title:'Layout',
                    content:'Select the layout for your application',
                    name:'.application_layout + .select2',
                    event:'click',
            },
                {
                    title:'Choose a Layout',
                    /*Specify an element with pseudo-options for highlight*/
                    name:'.select2-results li:contains("Rounded Application")',
                    content:'These are the available Layouts',
                    /*Specify an event "change" and element for this event in array*/
                    event:'next',
                    //event:['change', $('.application_layout')],
                    //event: 'next',
                    delayBefore:400,
                    before:function () {
                        $('.application_layout').select2("open");
                    },
            },
                {
                    title:'Application description',
                    content:'Set the application description',
                    name:'add_application_description',
                    'checkNext': {
                        'func': function () {
                            if ($.trim($('#description').val()) == '') {
                                return false;
                            } else {
                                return true;
                            }
                        },
                        'messageError': 'The field can not be empty!'
                    }
            },
                {
                    title:'Create the application',
                    content:'Click Create Application and move to customize new application',
                    name:'a:contains(\'Create Application\')',
            },
            ]
        });
        return false;
    };

    var buildApplication = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'Build Application',
            introShow:true,
            overlayClickable:false,
            steps:[
            {
                title:'Section',
                content:'Click on Section to add a new section',
                name:'.p-b-20',
                event:'click',
                delayAfter:1500
            },
            {
                title:'Section Title',
                content:'Set section title',
                name:'.wizard-circle #title',
                event:'next',
                'checkNext': {
                    'func': function () {
                        if ($.trim($('.wizard-circle #title').val()) == '') {
                            return false;
                        } else {
                            return true;
                        }
                    },
                    'messageError': 'The field can not be empty!'
                }
            },
            {
                title:'Save Section',
                content:'Click on save',
                name:'a[id^=\'create-section-form-\']',
                event:'click',
            },
            {
                title:'Here is your new section',
                content:'Your new section has been added successfully.',
                name:'.application_section:last-child',
                event:'next',
                delayBefore:500
            },
            {
                title:'One Line Text',
                content:'Click on One Line Text to add a new text field',
                name:'[data-type="One Line Text"]',
                event:'click',
                delayAfter:500
            },
            {
                title:'Set the Title',
                content:'Set the title of text field',
                name:'input[name="title"]',
                event:'next',
                /* 'checkNext': {
                    'func': function(){
                        if($.trim($('#title').val()) == ''){
                            return false;
                        }else{
                            return true;
                        }
                    },
                    'messageError': 'The field can not be empty!'
                } */
            },
            {
                title:'Text Field Section',
                content:'Click to select section',
                name:'.section + .select2',
                event:'click',
            },
            {
                title:'Select Section',
                content:'Select the section, where you want to add this field.',
                name:'.select2-dropdown',
                event:'next',
                //event:['change', $('.section')],
                delayBefore:600,
                before:function () {
                    $('.section').select2("open");
                },
                //delayAfter:300,

            },
            {
                title:'Placeholder Text',
                content:'Set the placeholder text(for example: Enter Name)',
                //name:'input[name="properties[placeholder]"]',
                name:'.placeholder',
                event:'next',
            },
            {
                title:'Appearance',
                content:'Click Appearance to set the appearance of the field',
                name:'[href="#appearance"]',
                event:'click',
            },
            {
                title:'Label visitbilty',
                content:'Check this box if you want to show the label',
                name:'[id="properties[label_show]"] + .custom-control-label',
                event:'next',
            },
            {
                title:'Label Text',
                content:'Enter the text for the label which you want to show on the application form',
                name:'input[id="properties[label_text]"]',
                event:'next',
            },
            {
                title:'Validation',
                content:'Click on Validation',
                name:'[href="#validation"]',
                event:'click',
            },
            {
                title:'Set text field validity',
                content:'Click to select options',
                name:'[name="validation_rules_select"] + .select2',
                event:'click',

            },
            {
                title:'Select Validity Rule',
                content:'Select the rule',
                name:'.select2-dropdown',
                //delayBefore:100,
                event:'next',
                //event:['change',$('select[name="validation_rules_select"]')],
                delayBefore:100,
                before:function () {
                    $('select[name="validation_rules_select"]').select2("open");
                },
            },
            {
                title:'Validation Rules',
                content:'Edit the validation message',
                name:'.validationRuleWrapper',
                event:'next',
            },
            {
                title:'Save Field',
                content:'Click on Save',
                name:'a[id^=\'create-field-form\']',
                event:'click',
            },
            {
                title:'Here is your new field',
                content:'Your new field has been added successfully.',
                name:'.field-title:last',
                event:'next',

            },
            {
                title:'Select/List Field',
                content:'Click on List to add a new field.',
                name:'[data-type="List"]',
                event:'click',
                delayAfter:1500
            },
            {
                title:'Select/List Field Title',
                content:'Set the title of text field',
                name:'#general #title',
                event:'next',
                /* 'checkNext': {
                    'func': function(){
                        if($.trim($('#title').val()) == ''){
                            return false;
                        }else{
                            return true;
                        }
                    },
                    'messageError': 'The field can not be empty!'
                } */
            },
            {
                title:'Select/List Field Section',
                content:'Click to select section',
                name:'[name="section"] + .select2',
                event:'click',
            },
            {
                title:'Select Section',
                content:'Select the section, where you want to add this field.',
                name:'.select2-dropdown',
                //delayBefore:100,
                event:'next',
                //event:['change',$('select[name="section"]')],
                delayBefore:100,
                before:function () {
                    $('select[name="section"]').select2("open");
                },

            },
            {
                title:'Placeholder Text',
                content:'Set the placeholder text(for example: Enter Name)',
                name:'input[id="properties[placeholder]"]',
                event:'next',
            },
            {
                title:'Appearance',
                content:'Set the appearance of the field, click Appearance',
                name:'[href="#appearance"]',
                event:'click',
            },
            {
                title:'Label visitbilty',
                content:'Check this box if you want to show the label',
                name:'[id="properties[label_show]"] + .custom-control-label',
                event:'next',
            },
            {
                title:'Label Text',
                content:'Enter the text for label which you want to show on application form',
                name:'input[id="properties[label_text]"]',
                event:'next',
            },
            {
                title:'Validation',
                content:'Click on Validation',
                name:'[href="#validation"]',
                event:'click',
            },
            {
                title:'Set text field validity',
                content:'Click to select options',
                name:'[name="validation_rules_select"] + .select2',
                event:'click',
            },
            {
                title:'Select Validity Rule',
                content:'Select the rule',
                name:'.select2-dropdown',
                //delayBefore:100,
                event:'next',
                //event:['change',$('select[name="validation_rules_select"]')],
                delayBefore:100,
                before:function () {
                    $('select[name="validation_rules_select"]').select2("open");
                },
            },
            {
                title:'Validation Rules',
                content:'Edit the validation message',
                name:'.validationRuleWrapper',
                event:'next',
            },
            {
                title:'Data/Options for select values',
                content:'Click on Data',
                name:'[href="#data"]',
                event:'click',
            },
            {
                title:'List Options',
                content:'Click to select options for your select field. There are several dynamic list which helps you to populate the list on one click, you can also create your custom list.',
                name:'[name="data"] + .select2',
                event:'click',
            },
            {
                title:'Select any option',
                content:'Select any Option',
                name:'.select2-dropdown',
               // delayBefore:100,
                event:'next',
               // event:['change',$('select[name="data"]')],
                delayBefore:100,
                before:function () {
                    $('select[name="data"]').select2("open");
                },
            },
            {
                title:'Options',
                content:'Edit options',
                name:'.custom_list_wrapper',
                event:'next',
            },
            {
                title:'Save Field',
                content:'Click on save',
                name:'a[id^=\'create-field-form\']',
                event:'click',
            },
            {
                title:'Here is your new field',
                content:'Your new field has been added successfully.',
                name:'.field-title:last',
                event:'next',
            },
            {
                title:'Program Field',
                content:'Click to add Program Field',
                name:'[data-type="Programs"]',
                event:'click',
                delayAfter:1500
            },
            {
                title:'Program Field Title',
                content:'Set the title for the field',
                name:'input[name="title"]',
                event:'next',
            },
            {
                title:'Select/List Field Section',
                content:'Click to select section',
                name:'[name="section"] + .select2',
                event:'click',
            },
            {
                title:'Select Section',
                content:'Select the section, where you want to add this field.',
                name:'.select2-dropdown',
                //delayBefore:100,
                event:'next',
                //event:['change',$('select[name="section"]')],
                delayBefore:100,
                before:function () {
                    $('select[name="section"]').select2("open");
                },

            },
            {
                title:'Show Campus Select',
                content:'Check this checkbox if you want to allow user to select the programs with respect to campus',
                name:'.custom-checkbox',
                event:'next',
            },
            {
                title:'Appearance',
                content:'Set the appearance of the field, click Appearance',
                name:'[href="#appearance"]',
                event:'click',
                after: function () {
                    var elmnt = document.getElementById("properties[label_show]");
                    elmnt.scrollIntoView();

                }
            },
            {
                title:'Label visitbilty',
                content:'Check this box if you want to show the label',
                name:'[id="properties[label_show]"] + .custom-control-label',
                event:'next',
            },
            {
                title:'Label Text',
                content:'Enter the text for label which you want to show on application form',
                name:'input[id="properties[label_text]"]',
                event:'next',
            },
            {
                title:'Save Field',
                content:'Click on Save',
                name:'a[id^=\'create-field-form\']',
                event:'click',
            },
            {
                title:'See Actions',
                content:'Click on Actions button',
                name:'.actions-button .dropdown-toggle',
                event:'click',
            },
            {
                title:'View Application',
                content:'Click on view application to view your application',
                name:'.view-application',
                event:'click',
            }],
            lang:{
                introTitle:'Building the new application',
                introContent:'We will build a simple application, step by step.',
                endText: 'End Tour',
            }
        });
        return false;
    };

    //Create Campus
    var createCampus = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'Add New Campus',
            introShow:true,
            overlayClickable:false,
            steps:[{
                title:'Create Campus',
                content:'Click the Add New button to create a new campus',
                name:'.add_new_btn',
                loc:'/campuses',
                event:'click',
                before:function (){
                    $('html').removeClass('hNavOpen');
                    $('.hNavBtn').hide();
                },
                after:function (){
                    $('html').addClass('hNavOpen');
                    $('.hNavBtn').show();
                    var _href = $("a.add_new_btn").attr("href");
                    $("a.add_new_btn").attr("href", _href + '#addcampus');
                }
            }],
            lang:{
                introTitle:'Create Campus',
                introContent:'We will create a new campus, step-by-step.',
                endText: 'End',
            }
        });
        return false;
    };

    //Add Campus
    var addCampus = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'Add Campus',
            introShow:true,

            tourMapPos:'right',                  //Tour Map Position
            tourMapVisible:true,
            steps:[
            {
                title:'Campus Name/Title',
                content:'Enter the name/title of campus',
                name:'#title',
                event:'next',
                'checkNext': {
                    'func': function () {
                        if ($.trim($('#title').val()) == '') {
                            return false;
                        } else {
                            return true;
                        }
                    },
                    'messageError': 'The field can not be empty!'
                }
            },
            {
                title:'Click Next',
                content:'Click on Next button to add more details about the campus',
                name:'[href="#next"]',
                event:'click',
            },
            {
                title:'Campus Details',
                content:'On this step, we will add address details of campus, click next button to continue',
                name:'[href="#steps-uid-0-h-1"]',
                event:'next',
            },
            {
                title:'Address',
                content:'Enter the address of your campus, this field is optional, you can leave it blank',
                name:'[name="properties[address]"]',
                event:'next',
            },
            {
                title:'Province',
                content:'Enter the province, this field is optional, you can leave it blank',
                name:'[name="properties[province]"]',
                event:'next',
            },
            {
                title:'Country',
                content:'Enter the country, this field is optional, you can leave it blank',
                name:'.campus_country + .select2',
                event:'click',
                loc:'/campuses/create'
            },
            {
                title:'Select Country',
                name:'.select2-dropdown',
                content:'Enter the country, this field is optional, you can leave it blank',
                event:'next',
                //event:['change',$('.campus_country')],
                delayBefore:200,
                before:function () {
                    $('.campus_country').select2("open");
                },
            },
            {
                title:'Save Campus',
                content:'Click Add Campus button to add your new campus',
                name:'a[href="#finish"]',
                event:'click',
                loc:'/campuses/create'
            },],
            lang:{
                introTitle:'Add New Campus',
                introContent:'We will create a new campus, step-by-step.',
                endText: 'End',
            }
        });
        return false;
    };

    //Create Campus
    var createCourse = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'Add New Course',
            introShow:true,
            overlayClickable:false,
            steps:[{
                title:'Create Course',
                content:'Click the Add New button to create a new course',
                name:'.add_new_btn',
                loc:'/course',
                event:'click',
                after:function(){
                    $('html').addClass('hNavOpen');
                    $('.hNavBtn').show();
                    var _href = $("a.add_new_btn").attr("href");
                    $("a.add_new_btn").attr("href", _href + '#addcourse');
                }
            }],
            lang:{
                introTitle:'Create Course',
                introContent:'We will create a new course step-by-step.',
                endText: 'End',
            }
        });
        return false;
    };

    //Add Course
    var addCourse = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'Add Course',
            introShow:true,
            overlayClickable:false,
            tourMapPos:'right',                  //Tour Map Position
            tourMapVisible:true,
            steps:[{
                title:'Course Title',
                content:'Enter the name/title of course',
                name:'#title',
                event:'next',
                'checkNext': {
                    'func': function(){
                        if($.trim($('#title').val()) == ''){
                            return false;
                        }else{
                            return true;
                        }
                    },
                    'messageError': 'The field can not be empty!'
                }

            },{
                title:'Course Code',
                content:'Enter the code for course',
                name:'#slug',
                event:'next',
                'checkNext': {
                    'func': function(){
                        if($.trim($('#slug').val()) == ''){
                            return false;
                        }else{
                            return true;
                        }
                    },
                    'messageError': 'The field can not be empty!'
                }

            },{
                title:'Campus',
                content:'Select the campus where this course is offered',
                name:'.campus + .select2',
                event:'click',
            },{
                title:'Select Campus',
                name:'.select2-dropdown',
                content:'This is the list of campuses, if you want to select more than one campus, click previous else click next',
                event:'next',
            },{
                title:'Program',
                content:'Select the program under which this course is offered',
                name:'.programs + .select2',
                event:'click',
            },{
                title:'Select Program',
                content:'This is the list of programs, please select',
                name:'.select2-dropdown',
                //event:['change', $('.programs')],
                event:'next',
                delayBefore:200,
                before:function () {
                    $('.programs').select2("open");
                },
            },
            // {
            //     title:'Course Schedule Type',
            //     content:'Select the schedule type',
            //     name:'.schedule_type + .select2',
            //     event:'click',
            // },
            // {
            //     title:'Select schedule type',
            //     content:'This is the list of schedule type, please select',
            //     name:'.select2-dropdown',
            //     //event:['change',$('.schedule_type')],
            //     event:'next',
            //     delayBefore:100,
            //     before:function () {
            //         $('select[name="properties[dates_type]"]').select2("open");
            //     },
            // },
            {
                title:'Save Course',
                content:'Click the save button to save the course',
                name:'a[href="#finish"]',
                event:'click',
            },

            ],
            lang:{
                introTitle:'Add New Course',
                introContent:'We will create a new course, step-by-step.',
                endText: 'End',
            }
        });
        return false;
    };

    //Create Program
    var createProgram = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'Add New Course',
            introShow:true,
            overlayClickable:false,
            steps:[{
                title:'Create New Program',
                content:'Click the Add New button to create a new program',
                name:'.add_new_btn',
                loc:'/program',
                event:'click',
                after:function(){
                    $('html').addClass('hNavOpen');
                    $('.hNavBtn').show();
                    var _href = $("a.add_new_btn").attr("href");
                    $("a.add_new_btn").attr("href", _href + '#addprogram');
                }
            }],
            lang:{
                introTitle:'Create Program',
                introContent:'We will create a new program step-by-step.',
                endText: 'End',
            }
        });
        return false;
    };


    //Add Program
    var addProgram = function () {
        $('body').itour({
            showAbsentElement:true,
            tourTitle:'Add Program',
            introShow:true,
            overlayClickable:false,
            tourMapPos:'right',                  //Tour Map Position
            tourMapVisible:true,
            steps:[{
                title:'Program Title',
                content:'Enter the name/title of program',
                name:'#title',
                event:'next',
                'checkNext': {
                    'func': function(){
                        if($.trim($('#title').val()) == ''){
                            return false;
                        }else{
                            return true;
                        }
                    },
                    'messageError': 'The field can not be empty!'
                }

            },{
                title:'Program Code',
                content:'Enter the code for program',
                name:'#slug',
                event:'next',
                'checkNext': {
                    'func': function(){
                        if($.trim($('#slug').val()) == ''){
                            return false;
                        }else{
                            return true;
                        }
                    },
                    'messageError': 'The field can not be empty!'
                }

            },{
                title:'Campus',
                content:'Select the campus where this course is offered',
                name:'[name="campuses[]"] + .select2',
                event:'click',
            },{
                title:'Select Campus',
                name:'.select2-dropdown',
                content:'This is the list of campuses',
                event:'next',
            },{
                title:'Courses',
                content:'Select the courses (optional)',
                name:'[name="courses[]"] + .select2',
                event:'next',
            },{
                title:'Select Course',
                content:'This is the list of programs, please select',
                name:'.select2-dropdown',
                //event:['change', $('select[name="courses[]"]')],
                event:'next',
                delayBefore:100,
                before:function () {
                    $('select[name="courses[]"]').select2("open");
                },
            },{
                title:'Program Type',
                content:'Select the program type',
                name:'.program_type + .select2',
                event:'click',
            },{
                title:'Select program type',
                content:'This is the list of program type, please select',
                name:'.select2-dropdown',
                event:'next',
                delayBefore:100,
                before:function () {
                    $('select[name="program_type"]').select2("open");
                },
            },{
                title:'Save Program',
                content:'Click the save button to save the program',
                name:'a[href="#finish"]',
                event:'click',
            },

            ],
            lang:{
                introTitle:'Add New Program',
                introContent:'We will create a new program, step-by-step.',
                endText: 'End',
            }
        });
        return false;
    };

    /*Check hash link in page URL*/
    var detectHash = function () {
        var hash = location.hash;
        if (typeof hash !== 'undefined' && hash !== '' && hash !== null) {
            switch (location.hash) {
                case '#itour':
                    var h1 = (hash.substr(1));
                    var q1 = '<input type="hidden" id="hash" name="hash" value="' + h1 + '">';
                    document.querySelector('#school_settings_div').innerHTML = q1;
                    startSettingsTour();
                    break;
                case '#branding':
                    brandingTour();
                    break;
                case '#login':
                    loginTour();
                    break;
                case '#users':
                    usersTour();
                    break;
                case '#invite':
                    inviteTour();
                    break;
                case '#newapplication':
                    location.hash = 'addapplication';
                    newApplication();
                    break;
                case '#addapplication':
                    addApplication();
                    break;
                case '#buildapplication':
                    buildApplication();
                    break;
                case '#createcampus':
                    createCampus();
                    break;
                case '#addcampus':
                    addCampus();
                    break;
                case '#createcourse':
                    createCourse();
                    break;
                case '#addcourse':
                    addCourse();
                    break;
                case '#createprogram':
                    createProgram();
                    break;
                case '#addprogram':
                    addProgram();
                    break;
            }
        }
    };

    /*Add event to hyperlink with hash */
    $(window).on('hashchange', function () {
        detectHash();
    });
    detectHash();

});

