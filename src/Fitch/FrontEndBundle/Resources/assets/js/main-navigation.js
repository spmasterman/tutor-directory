/**
 * This file is responsible for the dynamic behavior of the left (main) menu
 */

$(document).ready(function(){

    /**
     * Main Menu/Sub Menu opening/closing
     */
    $('.main-menu .js-sub-menu-toggle').click( function(e){

        e.preventDefault();

        $li = $(this).parents('li');
        if( !$li.hasClass('active')){
            $li.find('.toggle-icon').removeClass('fa-angle-left').addClass('fa-angle-down');
            $li.addClass('active');
        }
        else {
            $li.find('.toggle-icon').removeClass('fa-angle-down').addClass('fa-angle-left');
            $li.removeClass('active');
        }

        $li.find('.sub-menu').slideToggle(300);
    });

    /**
     * Main Menu Minify/Open
     */
    $('.js-toggle-minified').on("click", function(){
        var sidebar = $('.left-sidebar');
        if (sidebar.hasClass('minified')) {
            sidebar.removeClass('minified');
            $('.content-wrapper').removeClass('expanded');
            $('.sidebar-minified').find('i').removeClass('fa-angle-right').addClass('fa-angle-left');
        } else {
            sidebar.addClass('minified');
            $('.content-wrapper').addClass('expanded');

            sidebar.find('.sub-menu')
                .css('display', 'none')
                .css('overflow', 'hidden');

            $('.sidebar-minified').find('i').removeClass('fa-angle-left').addClass('fa-angle-right');
        }
        $.getJSON(Routing.generate('toggle_sidebar'), {}, function() {});
    });

    /**
     * Main "responsive" nav toggle - when the responsive menu is displayed, react to the hamburger icon getting clicked
     */
    $('.main-nav-toggle').clickToggle(
        function() {
            $('.left-sidebar').slideDown(300)
        },
        function() {
            $('.left-sidebar').slideUp(300);
        }
    );
});