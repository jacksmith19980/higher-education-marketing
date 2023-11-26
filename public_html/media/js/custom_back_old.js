var app = {};

// Ajax Route

app.ajaxRoute = window.ajaxRoute;

app.uploaderUrl = window.uploaderUrl 

app.ajaxResponse = {};

app.order = 0; 

app.initApplicantTable = function(){
    $('#applicant_table').DataTable({
    dom: 'Bfrtip',
    paging: false,
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    });
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');
}


app.inlineScrole = function(){

    //Inline Scroll Init

    $('.inline-scroll').perfectScrollbar();

}

app.radioSwitch = function(){
    
    $('.switch').bootstrapSwitch();

}

app.cssEditor = function (){
    
   /*  if( $('.css-editor').length > 0 ){
            $('.css-editor').each(function(){
                var id = $(this).data('id');        
                CodeMirror.fromTextArea( document.getElementById(id) , {
                    autofocus: true,
                    lineNumbers: true
                });
                
            })  
    } */
}

app.colorPicker = function(){
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
                if (!value) return;
                if (opacity) value += ', ' + opacity;
                if (typeof console === 'object') {
                    console.log(value);
                }
            },
            theme: 'bootstrap'
        });
    });
}

app.changeDefaultCurrencyHint = function (el) {
    var currency = $(el).val();
    
    $('.with-currency').next('span.input-group-text').text(currency);

}

app.dateTimePicker = function(){

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
    
}


app.enableOptions = function (el){
    
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

app.customSelect = function(){
    if ($(".select2").length > 0) {
        $(".select2").select2();
    }
}

app.dragElements = function(){

    dragula([document.getElementById("sortable")] , {



        moves: function (el, container, handle) {

             return handle.classList.contains('handle');

        }

    });

}



app.dismissModal = function(el){

    var modal = $(el).closest('.form_modal');

    modal.modal('hide');

    /* tinyMCE.remove(); */

}





app.getThemeCustomization = function(el){

    var applicationTheme = $(el).val();

    // get Application Customization
    var data = {
        action  : 'application.getApplicationThemeCustomization' ,
        payload :
            {
                theme : applicationTheme,
            }
        };    
    app.appAjax('POST' , data , app.ajaxRoute).then(function(data){
            if(data.response == 'success' && data.status == 200){
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
    
    var data = {

        action: action,

        payload: {}

    };

    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        if (data.response == 'success' && data.status == 200) {
            $(container).append(data.extra.html);
            app.dateTimePicker();
            app.customSelect();
        }
    });
}

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


}

app.getApplicationActionDetails = function(el){
    
    var application_action = $(el).val();
    var wrapper = $('.applicationActionDetails');

     var data = {

            action  : 'application_action.getApplicationActionDetails' ,

                payload :  {

                    application_action : application_action,

                }

           };

    app.appAjax('POST' , data , app.ajaxRoute).then(function(data){

        if(data.response == 'success' && data.status == 200){

           wrapper.append(data.extra.html);

        }

    });


}



app.applicationEdit = function(){
    
    var sections = $("#draggable-area")[0];

    dragula([sections] , {

    moves: function (el, container, handle) {

        return handle.classList.contains('handle');

    }

    }).on("drag", function (e) { }).on("drop", function (e) {

            // Update Section Order            

            var sectionsOrder = [];

            var applicationSlug = "";

            $('.application_section').each(function(){

                    var sectionId = $(this).data("sectionid");

                    sectionsOrder.push(sectionId);

                    applicationSlug = $(this).data("application-slug");



            });

            app.updateSectionsOrder(sectionsOrder , applicationSlug);



        }).on("over", function(e, t) {

        



        }).on("out", function(e, t) {

        

           //t.className = t.className.replace("card-over", "")

        });



    // Fileds Drag

    $('.fields-wrapper').each(function(){

        dragula([$(this)[0]],{

        }).on("drag", function(e) {

        }).on("drop", function(e) {

            var parentSection = $(e).closest('.application_section');

            // Update Field Order
            var fieldsOrder = [];
            var sectionId = "";

            parentSection.find('.field-row').each(function(){



                var fieldId = $(this).data("fieldid");

                fieldsOrder.push(fieldId);

                sectionsId = $(this).data('section');



            });



            app.updateFieldsOrder(fieldsOrder , sectionsId);



        }).on("over", function(e, t) {

        }).on("out", function(e, t) {

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








app.formBuilder = function (){

       

}





app.updateFieldsOrder = function(order , section){

    

    var data = {

            action  : 'section.fieldsOrder' ,

            payload :  {

                order   : order,

                section : section

            }

           };    

    app.appAjax('POST' , data , app.ajaxRoute).then(function(data){

          

          // Fire Notification

          if(data.response == 'success' && data.status == 200){



            // @Todo Fire Notification



          }

          

    });



}



app.updateSectionsOrder = function(order , application){

    

    var data = {

            action  : 'application.sectionsOrder' ,

            payload :  {

                order : order,

                application : application

            }

           };    

    app.appAjax('POST' , data , app.ajaxRoute).then(function(data){

          

          // Fire Notification

          if(data.response == 'success' && data.status == 200){



            // @Todo Fire Notification



          }

          

    });



}





// Create New Section

app.createSection = function(route , title){

    

    var i = 0;

    var id = 'create-section-form-' +( 1 + Math.floor(Math.random() * 1000) );

    

    app.openModal(id , route , title);

    

    $('.btn-ok').click(function(e){



        var form = $('#ajax-form');



        formData = new FormData(form[0]);



        // to prevent duplicate request caused by the modal

        if(i == 0){

            i++ ;

            e.preventDefault();

            

            var actionRoute = $('#form-modal .modal-body form').attr('action');



            app.appAjax('POST' , formData , actionRoute , 'file').then(function(data){

                // success

                if(data.response == "success"){

                    $('#form-modal').modal('hide');

                    $('.sections-container').append(data.extra.html);

                    return true;

                }

            });

        }

    });

}  



app.editSection = function(route , data , title , el){

    var i = 0;

    var id = 'edit-section-form-' +( 1 + Math.floor(Math.random() * 1000) );

    app.openModal(id , route , title);



    $('.btn-ok').click(function(e){



        var form = $('#ajax-form');



        var formData = new FormData(form[0]);

      

        // to prevent duplicate request caused by the modal

        if(i == 0){

            i++;

            e.preventDefault();

            

            var actionRoute = $('#form-modal .modal-body form').attr('action');

            

            app.appAjax('POST' , formData , actionRoute , 'file').then(function(data){



                // success

                if(data.response == "success"){

                   $('[data-sectionid='+data.extra.sectionId+'] .card-header h4').text( data.extra.title);

                   $('#form-modal').modal('hide');

                    return true;

                }



            }).catch(function(error){



                var errors = $.parseJSON(error.responseText).errors

                app.displayErrors(error , form);



            });

        }

    });

}



/* Create A New Field */ 

app.createField = function(route ,title){

    var i = 0;

    var id = 'create-field-form-' +( 1 + Math.floor(Math.random() * 1000) );

    

    app.openModal(id , route , title);



    $('.btn-ok#'+id).click(function(e){



            // to prevent duplicate request caused by the modal

            if(i == 0){



                e.preventDefault();

                var formData = {};



                $('.ajax-form-field').each(function(){

                    var name = $(this).attr("name");

                    var value = app.getFieldValue($(this));

                    if($(this).is(':required') && !value){

                        $(this).addClass('is-invalid');

                    }

                    formData[name] = value;

                }); 



                var actionRoute = $('#form-modal .modal-body form').attr('action');



                app.appAjax('POST' , formData , actionRoute ).then(function(data){

                    // success

                    if(data.response == "success"){

                        $('#form-modal').modal('hide');

                        // Get Wrapper

                        $('div[data-parent-section="'+data.extra.section_id+'"]').append(data.extra.html);

                    }

                    $('#'+id+' .modal-body form').remove();

                    //tinymce.execCommand('mceRemoveControl', true, 'mymce');



                    i++ ;



                    return false;



                }).catch(function(error){

                    

                    var errors = $.parseJSON(error.responseText).errors

                    app.displayErrors(error , form);





                });

               // Remove Form

            }

        });

}  

/** Edit Application Field */

app.editIntegration = function(route , data , title , el){

    var i = 0;

    var id = 'edit-integration-' +( 1 + Math.floor(Math.random() * 1000) );

    app.openModal(id , route , title);
    
    $('.btn-ok#'+id).click(function(e){



           // to prevent duplicate request caused by the modal

            if(i == 0){

                i++ ;

                e.preventDefault();

                var formData = {};

                

                $('.ajax-form-field').each(function(){

                    var name = $(this).attr("name");

                    formData[name] = app.getFieldValue($(this));

                }); 



                var actionRoute = $('#form-modal .modal-body form').attr('action');



                app.appAjax('PUT' , formData , actionRoute).done(function(data){

                    

                    // success

                    if(data.response == "success"){

                       

                        // get New HTML

                        //var html = data.extra.html;

                        //var innerHtml = $(html).html();



                        // Replace integration with new HTML

                        $('[data-integration-id = '+data.extra.integrationID+'] .integration-title').html(data.extra.title);



                       $('#form-modal').modal('hide');

                       

                       // Remove Form

                       /* tinyMCE.remove();

                       tinymce.remove();

                       tinymce.execCommand('mceRemoveControl', true, 'mymce'); */

                       $('#'+id+' .modal-body form').remove();



                                              

                    }

                    return false;



                }).catch(function(errors){



                });

            }



    });

}


app.editPaymentGateWay = function (route, data, title, el) {
  
    var i = 0;

    var id = 'edit-payment-gateway-' +( 1 + Math.floor(Math.random() * 1000) );

    app.openModal(id , route , title);
    
    $('.btn-ok#'+id).click(function(e){



           // to prevent duplicate request caused by the modal

            if(i == 0){

                i++ ;

                e.preventDefault();

                var formData = {};

                

                $('.ajax-form-field').each(function(){

                    var name = $(this).attr("name");

                    formData[name] = app.getFieldValue($(this));

                }); 



                var actionRoute = $('#form-modal .modal-body form').attr('action');



                app.appAjax('PUT' , formData , actionRoute).done(function(data){

                    // success
                    if(data.response == "success"){
                        $('[data-payment-id = '+data.extra.paymentID+'] .payment-title').html(data.extra.title);
                       $('#form-modal').modal('hide');

                       // Remove Form
                       /* tinyMCE.remove();
                       tinymce.remove();
                       tinymce.execCommand('mceRemoveControl', true, 'mymce'); */
                       $('#'+id+' .modal-body form').remove();
                    }
                    return false;
                }).catch(function(errors){



                });

            }



    });

}
app.editaction = function (route, data, title, el) {
  
    var i = 0;

    var id = 'edit-action-' +( 1 + Math.floor(Math.random() * 1000) );
    
    app.openModal(id , route , title);
    
    $('.btn-ok#'+id).click(function(e){

           // to prevent duplicate request caused by the modal
            if(i == 0){
                i++ ;
                e.preventDefault();
                var formData = {};

                $('.ajax-form-field').each(function(){
                    var name = $(this).attr("name");
                    formData[name] = app.getFieldValue($(this));
                }); 


                var actionRoute = $('#form-modal .modal-body form').attr('action');

                app.appAjax('PUT' , formData , actionRoute).done(function(data){
                    // success
                    if(data.response == "success"){
                        $('[data-payment-id = '+data.extra.paymentID+'] .payment-title').html(data.extra.title);
                       $('#form-modal').modal('hide');

                       // Remove Form
                       /* tinyMCE.remove();
                       tinymce.remove();
                       tinymce.execCommand('mceRemoveControl', true, 'mymce'); */
                       $('#'+id+' .modal-body form').remove();
                    }
                    return false;
                }).catch(function(errors){

                });
            }
    });

}





app.editField = function(route , data , title , el){
    var i = 0;
    var id = 'edit-field-form-' +( 1 + Math.floor(Math.random() * 1000) );
    app.openModal(id , route , title);
    $('.btn-ok#'+id).click(function(e){
            // to prevent duplicate request caused by the modal
            if(i == 0){
                i++ ;
                e.preventDefault();
                var formData = {};
                 $('.ajax-form-field').each(function(){
                    var name = $(this).attr("name");
                    formData[name] = app.getFieldValue($(this));
                }); 

                var actionRoute = $('#form-modal .modal-body form').attr('action');
                app.appAjax('PUT' , formData , actionRoute).done(function(data){

                    // success
                    if(data.response == "success"){

                       $('#form-modal').modal('hide');
                       // Remove Form
                        /* tinyMCE.remove();
                        tinymce.remove();
                        tinymce.execCommand('mceRemoveControl', true, 'mymce'); */
                        $('#'+id+' .modal-body form').remove();

                        //Check if field is pushed to a different section
                        var orgSection = $(el).closest('.list-group-item').data('section');    
                        if(orgSection != data.extra.section_id){

                            //Hide the Original Filed
                            $(el).closest('.list-group-item').hide();
                            // Get Wrapper
                            $('div[data-parent-section="'+data.extra.section_id+'"]').append(data.extra.html);
                            return false;

                        }else{
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

    app.openModal(id, route, title);

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

app.constructFieldName = function(el){

    var value = $(el).val().toLowerCase();

    if(!$('[name="name"]').is(":disabled")){

        $('[name="name"]').val(app.stringReplace(value));

    }

}





app.stringReplace = function(string){
    return string.replace(/\?/g , "_")
             .replace(/\-/g , "_")
             .replace(/\//g , "_")
             .replace(/\\/g , "_")
             .replace(/\)/g , "_")
             .replace(/\(/g, "_")
             .replace(/\'/g , "_")
             .replace(/\,/g , "_")
             .replace(/\./g , "_")
             .replace(/\{/g , "_")
             .replace(/\}/g , "_")
             .replace(/\[/g , "_")
             .replace(/\]/g , "_")
             .replace(/\@/g , "_")
             .replace(/\:/g , "_")
             .replace(/\#/g , "_")
             .replace(/\$/g , "_")
             .replace(/\%/g , "_")
             .replace(/\^/g , "_")
             .replace(/\&/g , "_")
             .replace(/\*/g , "_")
             .replace(/\ /g, "_");
}



app.resetValidation = function(){



    $('.ajax-form-field').keyup(function(){

        $(this).removeClass('in-invalid');

    }); 



}



/**

 * Load Fields to customize integration field names

 */


app.loadApplicationFields = function(el){

    

    if($(el).is(':checked')){

        app.loadApplicationFieldsAction($(el));

    }else{

        $('#applicationFieldsWrapper').html('');

    }
}
app.loadMoreApplicationFields = function (el) {

    app.spin($(el));
    app.loadApplicationFieldsAction($('[name="custom_field_names"]'));
}


app.loadApplicationFieldsAction = function(el){

    var applicationid = el.data('application-id');

    var username = $('[name="username"]').val();

    var password = $('[name="password"]').val();

    var mauticUrl = $('[name="url"]').val();



    var data = {

        action: 'application.getFieldsList',

        payload: {

            'applicationid': applicationid,

            'username': username,

            'password': password,

            'mauticUrl': mauticUrl,

        }

    };
    app.appAjax('POST', data, app.ajaxRoute).then(function (data) {

        $('#applicationFieldsWrapper').prepend(data.extra.html);
        
        // Stop Spinning
        $('.spinner').attr('disabled', 'disabled');
        app.stopSpinning($('.spinner'));
    });
}


app.loadMauticEmails = function(el){

    if ($(el).is(':checked')) {
        
        $('#MauticEmailsList').html('<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>');

        var data = {
            action: 'applicationAction.loadMauticEmails',
            payload: {
                'email' : 'test'
            }
        }
        
        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
           
            $('#MauticEmailsList').html(data.extra.html);

        });

    }else{
        
        $('#MauticEmailsList').html("");
    }
    
    
}

app.addCustomFieldName = function(el){
    var field_name_label = $('[name="field_name"] option:selected').text();
    var field_name_value = $('[name="field_name"]').val();
    var custom_field_name_label = $('[name="mautic_field_alias"] option:selected').text();
    var custom_field_name_value = $('[name="mautic_field_alias"]').val();
    var mautic_contact_type = $('[name="mautic_contact_type"] option:selected').text();
    app.updateFieldPair(field_name_value, custom_field_name_value, mautic_contact_type);

    var data = {
                action  : 'application.getCustomizedFieldName' ,
                payload :  {
                    field_name : field_name_label,
                    custom_field_name : custom_field_name_label,
                    mautic_contact_type: mautic_contact_type,
                    key: Math.random(1000,100000) * 1000
                }
           };    
        app.appAjax('POST' , data , app.ajaxRoute).then(function(data){
            $('#applicationFieldsWrapper').append(data.extra.html);
    });
}

app.updateFieldPair = function (field_name_value, custom_field_name_value, mautic_contact_type) {

    var fieldsPair = $('[name="mautic_field_pairs"]').val();


    if(fieldsPair  ){

        fieldsPair = $.parseJSON(fieldsPair);

    }else{
        var fieldsPair = [];
    }

    fieldsPair.push({


        'field' : field_name_value,

        'mautic_field' : custom_field_name_value,
        
        'mautic_contact_type': mautic_contact_type,



    });

    

    $('[name="mautic_field_pairs"]').val( JSON.stringify(fieldsPair) );

}



app.displayErrors = function(errors , form){



}





app.getFieldValue = function(el){

    // get the value of select 

    if(el.is("select")){

        //return el.find('option:selected').val();
        return el.val();

    }else if(el.is("textarea")){

        /* if(el.attr("id") == 'mymce'){
            return tinymce.activeEditor.getContent();
        } */

        return el.val();

    }else{
        //get the of input
        var type = el.attr('type');
        var name = el.attr('name');

            //text, email, etc.       
            if ( $.inArray( type , ['text' , 'email' , 'hidden' , 'phone' , 'date']) >= 0 ) {
                   return el.val();
            }
            //checkbox, radio
            if ( $.inArray(type , ['checkbox' , 'radio']) >= 0 ){
                if(el.is(":checked")){
                    return el.val();
                }
            }   
    }
    return " ";     
}



app.cloneField = function(fieldId){

}



//Delete Fields , Delete Section

app.deleteElement = function(route , data , removedElement){
    var i = 0;
    $('#confirm-delete').modal('show');
    $('.btn-ok').click(function(e){

        // to prevent duplicate request caused by the modal
        if(i == 0){
            i++;
            e.preventDefault();

            app.appAjax('DELETE' , data , route).then(function(data){
                // success
                if(data.response == "success"){
                    if(removedElement){
                        // Hide Deleted Row
                        $('['+removedElement+'="'+data.extra.removedId+'"]').fadeOut(function(){
                            $(this).remove();
                        });
                    }
                    //close Modal
                    $('#confirm-delete').modal('hide');
                    return true;
                }
            }).catch(function(){
                // Error
            });
       }
});


}
app.dataListChange = function(el){

    var value = $(el).find($('option:selected')).val();
    $('.list_name').val(value).change();

        if(value == 'custom_list'){

            $('.add-values').show();
            app.toggleListSync('hide');

        }else if(value == "program" || value == "campus" || value == "intake" ){

            app.toggleListSync('show');

        }else{
            $('.custom_list_wrapper').html("");
            $('.add-values').hide()
            app.toggleListSync('hide');

            // Get A list of Avaliable options
            var data = {
                action  : 'field.getOptionsList' ,
                payload :  {
                    list : value
                }
               };    
            app.appAjax('POST', data, app.ajaxRoute).then(function (data) { 
                //custom_list_wrapper
                $('.custom_list_wrapper').html(data.extra.html);
            });
        }
}



app.toggleListSync = function(action){

    if(action == 'show'){

        $('.sync_with_campus').show();  

        $('.add-values').hide();

    }else{

        $('.sync_with_campus').hide();

        $('.sync_field').prop('checked' , false).change();

    }

}



app.addValidationRule = function(el){

   

    if($(el).val() != 0){



        app.order ++;

            var data = {

                action  : 'validationRules.create' ,

                payload :  {

                    type : $(el).val()

                }

               };    



        app.appAjax('POST' , data , app.ajaxRoute).then(function(data){

            

            $(el).val(0).change();

            

            $('.ValidationRules').append(data.extra.html);    



            $(el).find('option[value="'+data.extra.type+'"]').attr('disabled' , 'disabled');                 





        });



    }

   

}



app.removeValidationRule = function(el , type){
    // Find the Parent Row
    $(el).closest('.validationRuleWrapper').remove();
    $('select[name="validation_rules_select"] option[value="'+type+'"]').removeAttr('disabled'); 
}



app.smartFieldSwitch = function (el) {



    var applicationId = $(el).data('application');

    

    if($(el).is(":checked")){

        

        var data = {

            action  : 'field.getIntelligenceRule' ,

            

            payload :  {

                applicationId :applicationId

            }



        };    



        app.appAjax('POST' , data , app.ajaxRoute).then(function(data){



            $('.intelligence_rules').append(data.extra.html);

            app.customSelect();



        });

    }else{

        $('.intelligence_rules').html(" ");

    }



    



}



// Not Used

app.uploadFile = function(el){ 

    fileData = new FormData();
    if($(el).prop('files').length > 0)
    {
        file =$(el).prop('files')[0];
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



}

app.syncWithSettings = function(el){

    if ($(el).is(":checked")) {
        
        var application = $(el).data('application');

        var data = {
            action  : 'application.showSynSettings' ,
                payload :  {
                    application     : application,
                }
           };    
        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            $('.sync-settings-wrapper').html(data.extra.html);
            app.customSelect();
        });
    }

}

app.smartFieldChanged = function(el , condition){
    var fieldName = $(el).val();
    var applicationId = $(el).data("applicationid");

    if (typeof condition == 'undefined') {
        condition = 'equals'
    }

    if(fieldName){
        var data = {
            action  : 'field.fieldData' ,
                payload :  {
                    fieldName       : fieldName,
                    applicationId   : applicationId,
                    condition       : condition,
                }
           };    
        app.appAjax('POST', data, app.ajaxRoute).then(function (data) {
            app.customSelect();
            $('.fieldValue').html(data.extra.html);
        });
    }
}

app.smartFieldConditionChanged = function (el) {
    var value = $(el).val();
    
    if(value == 'empty' ||  value == 'not_empty' ){

        $('.logic_value').attr('disabled', 'disabled');
    
    }else if(value == 'contain'){
        
        app.smartFieldChanged($('.logic_field') , 'contain');
    
    }else{

        $('.logic_value').removeAttr('disabled');
    }





}



app.resetSmartFieldLogic = function(el){

    $(el).closest('.fields-wrapper.list-group').hide();

    $('.is_smart_field').attr("checked" , false).change();

}



app.addCourse = function(route , data , title , el){



    var i = 0;

    var id = 'create-course-form-' +( 1 + Math.floor(Math.random() * 1000) );

    

    app.openModal(id , route , title );



        $('.btn-ok#'+id).click(function(e){

            

            // to prevent duplicate request caused by the modal

            if(i == 0){

                i++ ;

                e.preventDefault();

                var formData = {};

                 $('.ajax-form-field').each(function(){

                    var name = $(this).attr("name");

                    formData[name] = app.getFieldValue($(this));

                }); 



                var actionRoute = $('#form-modal .modal-body form').attr('action');



                app.appAjax('POST' , formData , actionRoute).done(function(data){

                    // success

                    if(data.response == "success"){

                        $('#form-modal').modal('hide');

                        // Get Wrapper

                        $('div[data-section="'+data.extra.section_id+'"]').append(data.extra.html);

                    }

                    return false;

                });

               // Remove Form

               $('#'+id+' .modal-body form').remove();

            }

        });

}







app.addPaymentGateway = function(route , title){

    var i = 0;

    var id = 'add-payment-gateway-' +( 1 + Math.floor(Math.random() * 1000) );

    // Open Modal

    app.openModal(id , route , title);





     $('.btn-ok#'+id).click(function(e){

            

            // to prevent duplicate request caused by the modal

            if(i == 0){

                e.preventDefault();

                var formData = {};

                

                 $('.ajax-form-field').each(function(){

                   

                    var name = $(this).attr("name");

                    var value = app.getFieldValue($(this));

                    

                    if($(this).is(':required') && !value){

                        $(this).addClass('is-invalid');

                    }



                    formData[name] = value;

                

                }); 



                var actionRoute = $('#form-modal .modal-body form').attr('action');



                app.appAjax('POST' , formData , actionRoute).done(function(data){



                    // success

                    if(data.response == "success"){

                        $('#form-modal').modal('hide');

                        // Get Wrapper
                        if (typeof data.extra.section_id != 'undefined' )
                        {
                            
                            $('div[data-parent-section="' + data.extra.section_id + '"]').append(data.extra.html);

                        }else{

                            $('#draggable-area').append(data.extra.html);
                            
                        }

                        i++ ; 

                    }

                    return false;

                }).catch(function(error){



                    console.log(error);



                });

               // Remove Form

               $('#'+id+' .modal-body form').remove();

            }

        });

     

}



app.addIntegration = function(route , title){

    var i = 0;

    var id = 'add-integration-' +( 1 + Math.floor(Math.random() * 1000) );

    // Open Modal

    app.openModal(id , route , title);



    $('.btn-ok#'+id).click(function(e){

            

            // to prevent duplicate request caused by the modal

            if(i == 0){

                

                e.preventDefault();

                var formData = {};

                

                 $('.ajax-form-field').each(function(){

                   

                    var name = $(this).attr("name");

                    var value = app.getFieldValue($(this));

                    

                    if($(this).is(':required') && !value){

                        $(this).addClass('is-invalid');

                    }



                    formData[name] = value;

                

                }); 



                var actionRoute = $('#form-modal .modal-body form').attr('action');



                app.appAjax('POST' , formData , actionRoute).done(function(data){



                    // success

                    if(data.response == "success"){

                        $('#form-modal').modal('hide');

                        // Get Wrapper

                        //$('div[data-parent-section="'+data.extra.section_id+'"]').append(data.extra.html);

                        $('#draggable-area').append(data.extra.html);

           

                    }

                    i++ ;

                    return false;

               

                }).catch(function(error){

               

                    console.log(error);

               

                });

               // Remove Form

               $('#'+id+' .modal-body form').remove();

            }

        });

     

}
app.addApplicationAction = function (route, title) {
    var i = 0;

    var id = 'add-application-action-' +( 1 + Math.floor(Math.random() * 1000) );

    // Open Modal
    app.openModal(id , route , title);

    $('.btn-ok#'+id).click(function(e){

            

            // to prevent duplicate request caused by the modal

            if(i == 0){

                

                e.preventDefault();

                var formData = {};

                

                 $('.ajax-form-field').each(function(){

                   

                    var name = $(this).attr("name");

                    var value = app.getFieldValue($(this));

                    

                    if($(this).is(':required') && !value){

                        $(this).addClass('is-invalid');

                    }



                    formData[name] = value;

                

                }); 



                var actionRoute = $('#form-modal .modal-body form').attr('action');



                app.appAjax('POST' , formData , actionRoute).done(function(data){



                    // success

                    if(data.response == "success"){

                        $('#form-modal').modal('hide');

                        // Get Wrapper

                        //$('div[data-parent-section="'+data.extra.section_id+'"]').append(data.extra.html);

                        $('#draggable-area').append(data.extra.html);

           

                    }

                    i++ ;

                    return false;

               

                }).catch(function(error){

               

                    console.log(error);

               

                });

               // Remove Form

               $('#'+id+' .modal-body form').remove();

            }

        });

     

}

app.toggleSchoolIntegration = function(el){
    var platform = $(el).data('integration');

            if( $(el).is(':checked') ){
               app.settingsToggle(platform , 'enable');
            }else{
                app.settingsToggle(platform , 'disable');
           }

    $('#'+platform+' input').each(function(){
        
        if( $(el).is(':checked') ){
     
                $(this).removeAttr('disabled');
        
         }else{
        
     
                $(this).attr('disabled', 'disabled');
        }


    });

}

app.toggleTracking = function(el){

    var platform = $(el).data('tracking');

           if( $(el).is(':checked') ){
               app.settingsToggle(platform , 'enable');
           }else{
                app.settingsToggle(platform , 'disable');
           }

    $('#'+platform+' input').each(function(){
        
        if( $(el).is(':checked') ){
                $(this).removeAttr('disabled');
            }else{
                $(this).attr('disabled', 'disabled');
        }


    });

}


app.settingsToggle = function(item , status){

    var action = 'setting.toggle';
    var data = {
            action  : action ,
            payload :  {
                item : item,
                status : status,
            }
    };  

    app.appAjax('POST' , data , app.ajaxRoute).then(function(data){
        console.log(data);
    });


}


app.sendInvoiceReminder = function(route , data , title , el){
    var i = 0;
    var id = 'send-reminder-email-form-' +( 1 + Math.floor(Math.random() * 1000) );
    app.openModal(id , route , title);
    $('.btn-ok').click(function(e){

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

            app.appAjax('POST' , formData , actionRoute ).then(function(data){

                // success
                if(data.response == "success"){
                   $('#form-modal').modal('hide');
                    return true;
                }
            }).catch(function(error){
                var errors = $.parseJSON(error.responseText).errors
                app.displayErrors(error , form);
            });
        }
    });
}




// Open Modal

app.openModal = function(id , route , title ){

    $('#form-modal .modal-content .btn-ok').attr('id' , id );

    $('#form-modal .modal-title').html(title);

    

    $('#form-modal .modal-body').load( route ,function(){

        $('#form-modal').modal('show');

        app.customSelect();
        app.initTextEditor();
        //app.tinyMCE();

        app.resetValidation();

        app.fileInput();

        app.radioSwitch();

        app.dateTimePicker();

    });

}





// Global Ajax Request

app.appAjax = function(method , data , route , dataType ){

    

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

    if(dataType == 'file'){

       args.processData = false; 

       args.contentType = false; 

    }



    return $.ajax(args);

}



app.repeatElement  = function(action , wrapperClass){

        var count = $('.' + wrapperClass + " .repeated_fields").length;
        var order = app.order++;

        if (count > 0) {
            order = count + 1;            
        }


        
        var data = {

            action  : action ,

            payload :  {

                order : order

            }

           };    

        app.appAjax('POST' , data , app.ajaxRoute).then(function(data){

              $('.'+wrapperClass).append(data.extra.html);                    



              return false;

        });

        return false;

}



app.removeRepeatedElement = function (el , order) {

    $('[data-repeated="'+order+'"]').fadeOut('meduim' , function(){
        $(this).remove();
    })
    

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

    var removeButton = `<div class="form-group"><button class="btn waves-effect waves-light btn-outline-danger btn-lg" type="button" onclick="app.removeRepeatedElement( '${removeClass}' );"><i class="fa fa-minus"></i> </button> </div>`;

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
    var removeButton = '<div class="form-group m-t-25"><button class="btn btn-danger" type="button" onclick="remove_education_fields(' + app.room + ');"> <i class="fa fa-minus"></i> </button> </div>';
    clonedObj.find('.action_wrapper').append(removeButton);

    var clonedHTML = clonedObj.html();

    // Container
    var container = $('#' + parent + "_wrapper");
    container.append(clonedHTML);
    app.dateTimePicker();
}


app.accordion = function(){
}


app.fileInput = function(){

    $('.custom-file-input').change(function(){

        var id = $(this).attr('id');

        var file = $(this).prop('files');



        $('.label_'+id).text(file[0].name);

    });

}

app.initTextEditor = function(className){
    
    if( $('.text_editor').length > 0 ){
    
        $('.text_editor').each(function () {
            var editor = new Jodit( this , {
                autofocus: true,
                uploader: {
                    insertImageAsBase64URI: true
                },
                enter: "BR",
                buttonsSM: "bold,image,|,brush,paragraph,|,align,,undo,redo,|,eraser,dots,strikethrough,underline,superscript,subscript,outdent,indent,font,file,video,cut,hr,symbol",
                
                buttonsMD: "bold,image,|,brush,paragraph,|,align,,undo,redo,|,eraser,dots,strikethrough,underline,superscript,subscript,outdent,indent,font,file,video,cut,hr,symbol",
                
                buttonsXS: "bold,image,|,brush,paragraph,|,align,,undo,redo,|,eraser,dots,strikethrough,underline,superscript,subscript,outdent,indent,font,file,video,cut,hr,symbol"

            });

        })
    }
   
}

app.tinyMCE = function(className){

    if(className){
        var selector ='textarea'+'.'+className;
    }else{
        
        var selector ='textarea';
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



app.redirect = function(link , newWindow ){

    window.open(link , '_blank');
    
    /* if(typeof newWindow == 'undefined'){
        
        window.location.href = link;

    }else{
       
    } */

    return false;

}



app.codeHighLight = function(){

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
        action: 'agency.resendActivationEmail',
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

app.toggleAdminPrivileges = function (el) {
    var agentId = $(el).data('id');


    var container = $('a.admin-icon[data-agent-container="' + agentId + '"]');
    var wasAdmin = container.data('is-admin');

    console.log(wasAdmin);

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

                var color = ' text-success';
                $('a.admin-icon[data-agent-container="' + agentId + '"]').removeClass('fas fa-spin fa-spinner text-muted control-icon').addClass('fas fa-star control-icon admin-icon' + color).attr('data-is-admin', 1).change();
            }



        }

    });
}





// Theme Functions

$(function() {

    "use strict";



    $('.accordion-head').click(function(){

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

    window.addEventListener('load', function() {

            // Fetch all the forms we want to apply custom Bootstrap validation styles to

            var forms = document.getElementsByClassName('needs-validation');

            // Loop over them and prevent submission

            var validation = Array.prototype.filter.call(forms, function(form) {

                form.addEventListener('submit', function(event) {

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

        function() {

            $(".navbar-header").addClass("expand-logo");

        },

        function() {

            $(".navbar-header").removeClass("expand-logo");

        }

    );

    // this is for close icon when navigation open in mobile view

    $(".nav-toggler").on('click', function() {

        $("#main-wrapper").toggleClass("show-sidebar");

        $(".nav-toggler i").toggleClass("ti-menu");

    });

    $(".nav-lock").on('click', function() {

        $("body").toggleClass("lock-nav");

        $(".nav-lock i").toggleClass("mdi-toggle-switch-off");

        $("body, .page-wrapper").trigger("resize");

    });

    $(".search-box a, .search-box .app-search .srh-btn").on('click', function() {

        $(".app-search").toggle(200);

        $(".app-search input").focus();

    });



    // ============================================================== 

    // Right sidebar options

    // ==============================================================

    $(function() {

        $(".add_field_toggle").on('click', function(e) {

            e.preventDefault();

            $(".customizer").toggleClass('show-service-panel');



        });

    });

    // ============================================================== 

    // This is for the floating labels

    // ============================================================== 

    $('.floating-labels .form-control').on('focus blur', function(e) {

        $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));

    }).trigger('blur');



    // ============================================================== 

    //tooltip

    // ============================================================== 

    $(function() {

        $('[data-toggle="tooltip"]').tooltip()

    })

    // ============================================================== 

    //Popover

    // ============================================================== 

    $(function() {

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

    $(".list-task li label").click(function() {

        $(this).toggleClass("task-done");

    });

    // ============================================================== 

    // Collapsable cards

    // ==============================================================

    $('a[data-action="collapse"]').on('click', function(e) {

        e.preventDefault();

        $(this).closest('.card').find('[data-action="collapse"] i').toggleClass('ti-minus ti-plus');

        $(this).closest('.card').children('.card-body').collapse('toggle');

    });

    // Toggle fullscreen

    $('a[data-action="expand"]').on('click', function(e) {

        e.preventDefault();

        $(this).closest('.card').find('[data-action="expand"] i').toggleClass('mdi-arrow-expand mdi-arrow-compress');

        $(this).closest('.card').toggleClass('card-fullscreen');

    });

    // Close Card

    $('a[data-action="close"]').on('click', function() {

        $(this).closest('.card').removeClass().slideUp('fast');

    });

    // ============================================================== 

    // LThis is for mega menu

    // ==============================================================

    $(document).on('click', '.mega-dropdown', function(e) {

        e.stopPropagation()

    });

    // ============================================================== 

    // Last month earning

    // ==============================================================

    var sparklineLogin = function() {

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

    sparklineLogin();

    

    // ============================================================== 

    // This is for the innerleft sidebar

    // ==============================================================

    $(".show-left-part").on('click', function() {

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

        onStepChanging: function(event, currentIndex, newIndex) {
            
            app.dateTimePicker();

            return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())

        },

        onFinishing: function(event, currentIndex) {

            return form.validate().settings.ignore = ":disabled", form.valid()

        },

        onFinished: function(event, currentIndex) {

            form.submit();

            //swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");

        }

    }), $(".validation-wizard").validate({

        ignore: "input[type=hidden]",

        errorClass: "text-danger",

        successClass: "text-success",

        highlight: function(element, errorClass) {

            $(element).removeClass(errorClass)

        },

        unhighlight: function(element, errorClass) {

            $(element).removeClass(errorClass)

        },

        errorPlacement: function(error, element) {

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
    app.initTextEditor()
    
    app.codeHighLight();

    app.radioSwitch();

    app.colorPicker();

    app.cssEditor();

    app.initApplicantTable();

   // app.dateTimePicker();



});





$(document).ready(function()
{    

});

function remove_education_fields(rid) {
    $('.removeclass_' + rid).remove();
}

