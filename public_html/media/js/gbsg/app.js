 var app = {

    init : function(){
        $('[data-toggle="tooltip"]').tooltip();

        app.preloader();
        app.wizardForm();
        app.customSelect();
        app.inlineScrole();
        app.smartField();
        app.dateTimePicker();
        app.syncFiled();
        app.fileUploader();

       // app.customValidation();
    },

    preloader : function(){
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

    } ,

    inlineScrole : function(){
        //Inline Scroll Init
        $('.inline-scroll').perfectScrollbar();
    } ,

    dateTimePicker : function(){
        //Basic
        $('.pickadate').pickadate({
            // Escape any 'rule' characters with an exclamation mark (!).
            format: 'dddd, dd mmmm, yyyy',
            formatSubmit: 'yyyy-mm-dd',
        });
        //Year Selection
        var year =   ( moment().year() ) - 10  ;
        $('.pickadate-select-year').pickadate({
            max: [year, 12, 31],
            selectYears: 60,
            format: 'dddd, dd mmmm, yyyy',
            formatSubmit: 'yyyy-mm-dd',
        });
    },

    // Select 2 Init
    customSelect : function(){
        $(".select2").select2();
    },

    fileUploader : function(){
        // Files Uploader  
        if($(".fileuploader").length > 0){

            $(".fileuploader").each(function(){

                var allowed = $(this).data("allowed");
                var uploaderURL = $(this).data("upload");
                var deleteURL = $(this).data("destroy");
                var fileHolderName = $(this).data("name");

                $(this).uploadFile({
                    url       : uploaderURL ,
                    fileName  :"documents",
                    multiple  :false, 
                    dragDrop  :true,
                    maxFileSize : 2097152,
                    showFileCounter : false,
                    returnType: "json",
                    allowedTypes : allowed,
                    showDelete: true,
                    showDone : true,
                    
                    deleteCallback: function (data, pd) {
                        
                        swal({
                              title: "Are you sure?",
                              text: "You are going to delete this file permanently",
                              icon: "warning",
                              buttons: true,
                              dangerMode: true,
                            })
                            .then((willDelete) => {
                              if (willDelete) {
                                // Delete File  
                                app.deleteFile(data.extra.file , deleteURL , fileHolderName);
                                // Hide StatusBar
                                pd.statusbar.hide();      
                              }
                        });
                    },

                    onSelect:function(files)
                    {   
                        // Setup CSRF token
                        $.ajaxSetup({
                            headers: {
                                         'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                                     }
                        });
                        return true;
                    },

                    onSuccess:function(files,data,xhr,pd)
                    {
                        
                    },

                    afterUploadAll:function(obj)
                    {

                        var response = obj.getResponses();
                        var fileName = response[0].extra.file;

                        // Fill File Name Holder
                        $('input.'+fileHolderName).val(fileName);

                        //Hide File Uploader 
                        $('[data-name="'+fileHolderName+'"]').hide();

                    },
                });
            });
            
        } 
            

    },

    deleteUploadedFile : function(route , fileId){


    },

    deleteFile : function(fileName , route , fileHolderName){
      
        $.post( route, {file: fileName , key: key},

                function (response,textStatus, jqXHR) {
                   
                    if(response.status == 200){
                        $('input.'+fileHolderName).val("");
                         //Hide File Uploader 
                        $('[data-name="'+fileHolderName+'"]').show();
                    }
                    swal("File Deleted!", "File Deleted Successfully", "success");
                }
        );

    },

    wizardForm : function(){
        
        var form = $(".validation-wizard").show();
        var add_button = $(".validation-wizard").data('add-button')
        var next_button = $(".validation-wizard").data('next-button')

        $(".validation-wizard").steps({
            
            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            titleTemplate: '<div class="step_#index# step_icon"></div> #title#',
            labels: {
                finish : add_button,
                next   : next_button
            },
            onStepChanging: function(event, currentIndex, newIndex) {

                //submit Application
                app.submitApplication(event);

                //return true;
                return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden,:file", form.valid())
            },
            onFinishing: function(event, currentIndex) {
                return form.validate().settings.ignore = ":disabled,:hidden,:file", form.valid()
            },
            onFinished: function(event, currentIndex) {
                form.submit();
            }
        }), 

        /*$(".validation-wizard").validate({
            
            errorClass: "text-danger",
            
            successClass: "text-success",
            
            highlight: function(element, errorClass) {
                $(element).removeClass(errorClass)
            },
            
            unhighlight: function(element, errorClass) {
                $(element).removeClass(errorClass)
            },
            errorPlacement: function(error, element) {
                $(element).parent('.form-group').append(error[0]);
            },
            rules: {
                email: {
                    email: !0
                }
            }
        }).settings.ignore = ":disabled,:hidden,:file";*/


        // Tab Icon
        $('.form-wizard-section').each(function(i , el){
            var icon = $(el).data('icon');
            $('.step_'+ (i+1)).css({
                'background-image' : "url("+icon+")",
            })

        });

    },

    customValidation : function(){

       /* $.validator.addMethod("file", function(value, element) {
            return false;
                console.log(element);
                console.log(value);
        //            return this.optional(element) || /^http:\/\/mycorporatedomain.com/.test(value);
        });*/

    },

    submitApplication : function(event){
        
        var form = event.target;
        var route = $(form).attr('action');
        formData = new FormData($(form)[0]);
        app.appAjax('POST' , formData , route , 'file');
    },

    // Global Ajax Request
    appAjax : function(method , data , route , dataType ){
    
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
    },
    
    smartField : function(){

        // Hide Fields
        $('.smart-field').each(function(){

            app.smartFieldInit( $(this) , $(this).data('action'));
           
            // Process Smart Fields Logic
            app.processSmartField( $(this) );

        });

    },

    smartFieldInit : function( el , action  ){
        
        if(action =='show'){
            el.hide();
        }

    },

    processSmartField : function( el ){

        //get referance field
        var ref = $('[name="'+el.data('reference')+'"]');

        var field =$('[name="'+el.data('field')+'"]'); 
        
        // Get Referance field value
        var ref_value = ref.val();

        //get action
        var action = $(el).data('action');

        //get operator
        var operator = $(el).data('operator');
        //get value
        var value = $(el).data('value');

        // Call Action
        eval("app."+action+"SmartField(el , field , ref , ref_value , operator , value )");
        
        // run the action if referance field is changed
        ref.change(function(){
            var ref_value = ref.val();
            eval("app."+action+"SmartField(el , field ,  ref , ref_value ,operator , value )");
        });

        ref.keyup(function(){
            var ref_value = ref.val();
            eval("app."+action+"SmartField(el ,field ,  ref , ref_value ,operator , value )");
        });
    },

    showSmartField : function(el , field , ref  , ref_value , operator , value){

        if(operator == 'equal'){
            
            if(ref_value == value){
                el.show();
                field.removeAttr("disabled");
            }else{
                el.hide();
                field.attr("disabled" , "disabled");
            }

        }else if(operator == 'not_empty'){
            
            if(ref_value){
                el.show();
                field.removeAttr("disabled");
    
            }else{
                el.hide();
                field.attr("disabled" , "disabled");
            }

        }else if(operator == 'empty'){

            if(ref_value){
                el.hide();
                field.attr("disabled" , "disabled");
            }else{
                el.show();
                field.removeAttr("disabled");
            }
        }else if(operator == 'contain'){

            if(ref_value.search(value) >= 0){
                el.show();
                field.removeAttr("disabled");
            }else{
                el.hide();
                field.attr("disabled" , "disabled");
            }
        }

    },


    hideSmartField : function(el , field , ref , ref_value , operator , value){

        if(operator == 'equal'){
            
            if(ref_value == value){
                el.hide();
                field.attr("disabled" , "disabled");
            }else{
                el.show();
                field.removeAttr("disabled");
            }

        }else if(operator == 'not_empty'){
            
            if(ref_value){
                el.hide();
                field.attr("disabled" , "disabled");
            }else{
                el.show();
                field.removeAttr("disabled");
            }

        }else if(operator == 'empty'){

            if(ref_value){
                el.show();
                field.removeAttr("disabled");
            }else{
                el.hide();
                field.attr("disabled" , "disabled");
            }
        }else if(operator == 'contain'){
            if(ref_value.search(value) >= 0){
                el.hide();
                field.attr("disabled" , "disabled");
            }else{
                el.show();
                field.removeAttr("disabled");
            }
        }

    },

    requiredSmartField : function(el , field , ref, ref_value , operator , value){
        
        if(operator == 'equal'){
            
            if(ref_value == value){
                field.attr("required" , "required");
            }else{
                field.removeAttr("required");
            }

        }else if(operator == 'not_empty'){

            if(ref_value){
                field.attr("required" , "required");
            }else{
                 field.removeAttr("required");
            }

        }else if(operator == 'empty'){

            if(ref_value){
               field.removeAttr("required");
            
            }else{
                field.attr("required" , "required");
            }
        }else if(operator == 'contain'){
            
            if(ref_value.search(value) >= 0){
                field.attr("required" , "required");
            }else{
                field.removeAttr("required");
            }
        }
    },
    // @ Todo Sync. Field
    syncFiled : function(){
        if( $("[data-sync]").length > 0 ){
           
           $("[data-sync]").each(function(){

            var mainModel = $(this).data("sync");
            
                //$(this).attr('disabled' , 'disabled');

           });



        }
    },

 };

$(function() {
    app.init();
});