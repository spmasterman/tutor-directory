/**
 * This file is responsible for widgets (Content 'boxes')
 */

$(document).ready(function(){

    // widget remove
    $('.widget .btn-remove').click(function(e){

        e.preventDefault();
        $(this).parents('.widget').fadeOut(300, function(){
            $(this).remove();
        });
    });

    // widget toggle expand
    $('.widget .btn-toggle-expand').clickToggle(
        function(e) {
            e.preventDefault();
            $(this).parents('.widget').find('.widget-content').slideUp(300);
            $(this).find('i.fa-chevron-up').toggleClass('fa-chevron-down');
        },
        function(e) {
            e.preventDefault();
            $(this).parents('.widget').find('.widget-content').slideDown(300);
            $(this).find('i.fa-chevron-up').toggleClass('fa-chevron-down');
        }
    );

    // widget focus
    $('.widget .btn-focus').clickToggle(
        function(e) {
            e.preventDefault();
            $(this).find('i.fa-eye').toggleClass('fa-eye-slash');
            $(this).parents('.widget').find('.btn-remove').addClass('link-disabled');
            $(this).parents('.widget').addClass('widget-focus-enabled');
            $('<div id="focus-overlay"></div>').hide().appendTo('body').fadeIn(300);

        },
        function(e) {
            e.preventDefault();
            $theWidget = $(this).parents('.widget');

            $(this).find('i.fa-eye').toggleClass('fa-eye-slash');
            $theWidget.find('.btn-remove').removeClass('link-disabled');
            $('body').find('#focus-overlay').fadeOut(function(){
                $(this).remove();
                $theWidget.removeClass('widget-focus-enabled');
            });
        }
    );

});