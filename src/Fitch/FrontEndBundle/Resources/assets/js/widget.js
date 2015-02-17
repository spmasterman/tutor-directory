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
    $('.widget .btn-toggle-expand').on('click', {}, function(e) {
        e.preventDefault();
        var content = $(this).parents('.widget').find('.widget-content');
        var key = $(this).parents('.widget-header-toolbar').data('key');
        if (content.is(':visible')) {
            content.slideUp(300);
            $(this).find('i.fa-chevron-up').removeClass('fa-chevron-up').addClass('fa-chevron-down');
            if (key) {
                $.post(Routing.generate('widget_control'), {
                    'key': key,
                    'state': 'closed'
                });
            }
        } else {
            content.slideDown(300);
            $(this).find('i.fa-chevron-down').removeClass('fa-chevron-down').addClass('fa-chevron-up');
            if (key) {
                $.post(Routing.generate('widget_control'), {
                    'key': key,
                    'state': 'open'
                });
            }
        }
    });

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