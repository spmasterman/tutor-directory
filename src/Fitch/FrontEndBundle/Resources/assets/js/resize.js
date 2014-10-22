/**
 * This file is responsible for reacting to screen size changes
 */

$(document).ready(function () {
    $(window).bind("resize", resizeResponse);

    setContentWrapperHeight();

    function resizeResponse() {
        if ($(window).width() < (992 - 15)) {
            if ($('.left-sidebar').hasClass('minified')) {
                $('.left-sidebar').removeClass('minified');
                $('.left-sidebar').addClass('init-minified');
            }

        } else {
            if ($('.left-sidebar').hasClass('init-minified')) {
                $('.left-sidebar')
                    .removeClass('init-minified')
                    .addClass('minified');
            }
        }
        setContentWrapperHeight();
    }

    // increases the content height to squash the footer to a decent size
    function setContentWrapperHeight() {
        var viewportHeight = Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
        var h = viewportHeight - $('.top-bar').height() - 50;
        $('.content-wrapper').css('min-height', h);
    }
});