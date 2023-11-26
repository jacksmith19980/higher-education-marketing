$(function() {
    "use strict";
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
       
    })
    // ============================================================== 
    //Popover
    // ============================================================== 
    $(function() {
        $('[data-toggle="popover"]').popover()
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


    
   
   
   

});


$(document).ready(function()
{       
        
dragula([document.getElementById("draggable-area")]),
            dragula([document.getElementById("card-colors")]).on("drag", function(e) {
                e.className = e.className.replace("card-moved", "")
            }).on("drop", function(e) {
                
                e.className += " card-moved"
                
               console.log(e);

            }).on("over", function(e, t) {
                t.className += " card-over"
            }).on("out", function(e, t) {
                t.className = t.className.replace("card-over", "")
            }), dragula([document.getElementById("copy-left"), document.getElementById("copy-right")], {
                copy: !0
            }), dragula([document.getElementById("left-handles"), document.getElementById("right-handles")], {
                moves: function(e, t, n) {
                    return n.classList.contains("handle")
                }
            }), dragula([document.getElementById("left-titleHandles"), document.getElementById("right-titleHandles")], {
                moves: function(e, t, n) {
                    return n.classList.contains("titleArea")
                }
            })


});


var room = 1
function repeat_fields(argument) {
    room++;
    var objTo = $('.repeated_fields');
    var clonedObj = objTo.clone();
    clonedObj.removeClass('repeated_fields').addClass('removeclass_'+room);

    
    // // Remove Add Button
    clonedObj.find('.action_button').remove();

    // Add Remove Button
    var removeButton = '<div class="form-group"><button class="btn btn-danger" type="button" onclick="remove_education_fields(' + room + ');"> <i class="fa fa-minus"></i> </button> </div>';
    clonedObj.find('.action_wrapper').append(removeButton);
    objTo.after(clonedObj);

}

function remove_education_fields(rid) {
    $('.removeclass_' + rid).remove();
}
