//Icon Toggle for fontawesome
function toggleIcon(el,ar){
    var[iconDefault,iconHover] = ar;
    if(el.hasClass(iconDefault)){
        el.removeClass(iconDefault);
        el.addClass(iconHover);
    }else{
        el.removeClass(iconHover);
        el.addClass(iconDefault);
    }  
}

// toogle for .list-item collapse
function compileToggleScript(el){
    el.closest('.list-item').find(".list-content").slideToggle('fast');
    el.closest('.list-item').toggleClass('active');
    var footerIndicatorSvg = el.closest('.list-item').find(".list-footer svg");
    var iconSet = ['fa-angle-down','fa-angle-up'];
    toggleIcon(footerIndicatorSvg,iconSet);
}
var app = {
    init: function () {
        app.set();
        app.isActive();
    },
    goBack: function () {
        window.history.back();
    },
    debug: function () {
        var cart = this.get();
        var html = '';
        for (let i = 0; i < cart.courses.length; i++) {
            const el = cart.courses[i];
            html += "<li>"+el.title+"</li>";
        }
        $('.debug').html(html);
    },

    selectCampus: function (campus , course) {
        // Toggle Active Class;
        $('[data-campus="' + campus + '"]').toggleClass('active');
        var cart = this.get();
        
        // remove Campus from Cart if exist
        if (cart.campuses.indexOf(campus) >= 0) {
            cart.campuses.splice(cart.campuses.indexOf(campus), 1);
        }else{
            // Add Campus to Cart if Not Exist
            (cart.campuses).push(campus)
        }
        // Update Cart
        this.put(cart);
        
        // if the campus has one course only .. add the course to the selection
        if (typeof course != 'undefined') {
            this.selectCourse(course, campus);
        } else {
            // if Campus has Multiple Courses
            //console.log(cart)
        }
    },

    selectCourse: function (course, campus) {

        var title = $('[data-course="' + course + '"]').data('title');
        var slug = $('[data-course="' + course + '"]').data('slug');
        var cart = this.get();

        if ((cart.courses).length == 0) {
            cart.courses.push({
                id: course,
                slug: slug,
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
                    title: title,
                    campus: campus,
                    dates: []
                })
                var message = 'You have selected ' + title
                this.showMessage(message);

            } else {
                cart.courses = cart.courses.filter(function (item) {
                    if(item.id !== course){
                        return item;
                    }
                });
            }
        }
       
        
        this.put(cart);
        app.isActive();
        //this.debug();
    },
    
    selectDate: function(dateKey , course , price , start , end){
        var el = $('[data-date="' + dateKey + '"]');
        // retun if date is disabled
        if(el.hasClass('disabled')){
            return;
        }
        
        el.toggleClass('active');

        // Disable All Dates with the same strat date
        $('[data-start="' + start + '"]').toggleClass('disabled');
        el.removeClass('disabled');
        
        if (el.hasClass('active')) {
            app.toggleSubMenu(el , 'expand');
        } else {
            app.toggleSubMenu(el , 'collapse');
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
                        addons: [],
                    })
                    var message = "You have selected " + cart.courses[i].title + ' From ' + start + " to " + end;
                    this.showMessage(message);
                    
                    cart.dates.push(dateKey);

                } else {

                    if (cart.dates.indexOf(dateKey) < 0) {
                        cart.dates.push(dateKey);
                    } else {
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
                            addons: [],
                        })

                        var message = "You have selected " + cart.courses[i].title + ' From ' + start + " to " + end;
                        this.showMessage(message);

                    } else {
                        (cart.courses[i].dates) = (cart.courses[i].dates).filter(function (item) {
                            
                            if (item.key !== dateKey) {
                                return item;

                            } else {
                                
                                item.addons.forEach(function (addon) {
                                    cart.addons.splice(cart.addons.indexOf(addon.key), 1);
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
        $(el).toggleClass('active')
        var addonKey = $(el).data('addon-key');
        var dateKey = $(el).data('date-key');
        var addonTitle = $(el).data('addon-title');
        var addonPrice = $(el).data('addon-price');
        var addonPriceType = $(el).data('addon-price-type');
        var cart = this.get();

        if (cart.addons.indexOf(addonKey) <  0) {
            cart.addons.push(addonKey);
        }else{
            cart.addons.splice(cart.addons.indexOf(addonKey), 1);
        }

        cart.courses.forEach(function(course){
            course.dates.forEach(function (date) {
                if (date.key == dateKey) {

                    if (date.addons.length == 0) {
                        
                        date.addons.push({
                            key: addonKey,
                            title: addonTitle,
                            price: addonPrice,
                            price_type: addonPriceType,
                        })


                    } else {
                        
                        var addons = [];
                        for (let c = 0; c < (date.addons).length; c++) {
                            addons.push(date.addons[c].key);
                        }
                        if (addons.indexOf(addonKey) < 0) {
                            (date.addons).push({
                                key: addonKey,
                                title: addonTitle,
                                price: addonPrice,
                                price_type: addonPriceType,
                            })
                        } else {

                            date.addons = date.addons.filter(function (addon) {
                                if (addon.key !== addonKey) {
                                    return addon;
                                }
                            });
                        }
                    }
                }
            })
        });
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
    isValid(el){
        var route = $(el).data('route');
        var step = $(el).data('step');
        var message = $(el).data('message');
        var cart = this.get();
        var isValid = false;
        if (step == 'campus-course') {
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
        if ( step == 'transfer' || step == 'accommodation' ) {
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
    showErrors: function(message){
        new Noty({
            killer: true,
            type: 'error',
            layout   : 'topRight',
            theme    : 'mint',
            closeWith: ['click', 'button'],
            text: message,
        }).show();
    },
    isActive: function(namespace) {
        var cart = this.get();
        // Reset Active
        $('[data-course]').removeClass('active');
        $('[data-campus]').removeClass('active');
        $('[data-date]').removeClass('active');
        $('[data-addon-key]').removeClass('active');
        $('[data-transfer-option-key]').removeClass('active');
        
        if ((cart.courses).length) {
            for (let i = 0; i < (cart.courses).length; i++) {
                $('[data-course="' + cart.courses[i].id + '"]').addClass('active');
                $('[data-campus="' + cart.courses[i].campus + '"]').addClass('active');

                if (cart.courses[i].dates.length > 0) {
                    
                    for (let c = 0; c < (cart.courses[i].dates).length; c++) {
                        var date = $('[data-date="' + cart.courses[i].dates[c].key + '"]');
                        date.addClass('active'); 
                        $('[data-start="' + date.data('start') + '"]').addClass('disabled');
                        date.removeClass('disabled');
                    }
                }
            }
        }
        if ((cart.addons).length) {
            cart.addons.forEach(function (addon) {
                $('[data-addon-key="' + addon + '"]').addClass('active');
            });
        }
        if ((cart.transfer).length) {
            cart.transfer.forEach(function (transfer) {
                $('[data-transfer-option-key="' + transfer.key + '"]').addClass('active');
            });
        }
    },
    
    toggleSubMenu: function (el , action) {

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
            var cart = this.get();
            var quotation = $("meta[name='quotation']").attr("content");
            if (!cart) {
                Cookies.set('cart_' + quotation , JSON.stringify({
                    campuses: [],
                    courses: [],
                    dates: [],
                    addons: [],
                    accomodations: [],
                    transfer: [],
                    misc: [],
                    price: [],
                }), {
                    expires: 3600
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
        Cookies.set('cart_' + quotation, JSON.stringify(data), {
            expires: 3600
        });
    },

}




$(document).ready(function(){
    app.init();

    //slide toggle effect for list item
    $(".list-item .list-footer").click(function(){
        compileToggleScript($(this));
    });


    //Check box toggle 
    $(".list-item .list-header input[type='checkbox']").click(function(){

        var parentListItem = $(this).closest('.list-item');

        if((this.checked)){
            //if checkbox is checked and current .list-item is not active toggle slide content 
            if(!parentListItem.hasClass('active')){       
                compileToggleScript($(this));
            }
        }else{
            //if checkbox is not checked and current .list-item is active toggle slide content 
            if(parentListItem.hasClass('active')){
                compileToggleScript($(this));
            }

            //uncheck all 
            var parentContentCheckbox = $(this).closest('.list-item').find('.list-content input[type="checkbox"]:checked');
            parentContentCheckbox.prop('checked',false);
        }
    });

    //Asign attribute check on header checkbox if one of the current list-item content-item checkbox is checked
    //Temporary list content checkbox array
    
    //Add class show btn to next btn if header checkbox is checked
    
    

    var arraySizeCounter = 0;
    $(".list-item .list-content input[type='checkbox']").click(function(){
        
        if((this.checked)){
            arraySizeCounter++;
        }else{
            if(arraySizeCounter > 0){
                arraySizeCounter--;
            }
        }
        
        //get header checkbox of the parent list item
        var parentHeaderCheckbox = $(this).closest('.list-item').find('.list-header input[type="checkbox"]');
        
        if(arraySizeCounter > 0){
            parentHeaderCheckbox.prop('checked',true);
            $('.section-footer-nav .next').addClass('show');
        }else{
 
        }
    });


    

    //slide toggle effect for list program-item
    $(".program-list-item .program-list-header").click(function(){

        //Declare iconset for + and -
        var iconSet = ['fa-plus','fa-minus'];
    
     
        //the current Program-list-content of the clicked .program-list-item header
        var currentProgramListContent =  $(this).closest('.program-list-item').find(".program-list-content");

        if(currentProgramListContent.hasClass('active')){
            //if clicked Program-list-content has an active class  - close it and remove class active
            currentProgramListContent.slideToggle('fast').toggleClass('active');
            toggleIcon($(this).find('svg'),iconSet);

        }else{

            //if clicked Program-list-content is not currently active check siblings 

            //All the program list item inside program-list-container class
            var programListItems = $('.program-list-container').find('.program-list-item');

            //Check siblings program-list-item if there's a current active program-list-content
            programListItems.each(function (){
                if($(this).find(".program-list-content").hasClass('active')){
                  
                    //Toggle close if there's an active program-list-content and remove class active
                    $(this).find(".program-list-content").slideToggle('fast').toggleClass('active');
                    toggleIcon($(this).closest('.program-list-item').find('.program-list-header svg'),iconSet);

                }    
            }); 

            // Toggle the current Program-list-content of the clicked .program-list-item header 
            currentProgramListContent.slideToggle('fast').toggleClass('active');
            toggleIcon(currentProgramListContent.closest('.program-list-item').find('.program-list-header svg'),iconSet);
        } 
        
  
    });
    

    //Form toggle between Sendquote form and login/signup form
    $('.form-toggle-btngroup button').click(function(){

        var formID = $(this).data("form");
        $('.form-toggle-btngroup button.active').toggle("slide");
        
        // Add active class to the button
        $(this).addClass('active');

        //toggle send request to email and book now base on toggle-form attribute
        $('.form-toggle .form-wrapper.active').toggle('fast').removeClass('active');
        $(`#${formID}`).toggleClass('active').toggle('fast');

    });

    //Form toggle login and signup form
    $('.log-form-btn').click(function(){
        $('.log-form-toggle').fadeToggle('fast').toggleClass('active');
    });

    //Script for deactivate active next prev btn
    var currentNavLocation = $('nav ul li.active').index();


});