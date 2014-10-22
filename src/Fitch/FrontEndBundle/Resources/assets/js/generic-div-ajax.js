/**
 * This file implements some generic ajax updating for Divs.
 *
 * Requirements:
 *
 * Add attributes to any div field with
 *  data-ajax='true'
 *  data-ajax-callback='<routename>'
 *
 * Add attributes for any required parameters
 *  data-ajax-param-id=1
 *  data-ajax-param-offset=10
 * etc
 *
 * Implement the routename in a controller and expose the route (options={"expose"=true})
 */

$(document).ready(function(){
    "use strict";

    // reload when any ajax call fails (forces a bounce to login if we have expired session etc)
    $(document).ajaxError(function (event, jqXHR) {
        if (403 === jqXHR.status) {
            window.location.reload();
        }
    });

    timeout();

    function timeout() {
        setTimeout(function () {
            getData();
            timeout();
        }, 5000);
    }

    function getData() {
        var paramIdentifier = 'ajaxParam',
            containerIdentfier = "div[data-ajax='true']"
            ;

        $(containerIdentfier).map(function(){
            var routeParameters = [];
            $.each($(this).data(), function(i, v) {
                if (i.substr(0, paramIdentifier.length) == paramIdentifier) {
                    routeParameters[i.substr(paramIdentifier.length).toLowerCase()] = v;
                }
            });
            var callbackUrl = Routing.generate($(this).data('ajax-callback'), routeParameters);
            $(this).load(callbackUrl);
        });
    }

    $('body').on("click", "button[data-ajax-off]", function() {
        var container = $(this).closest("div[data-ajax]")[0];

        if ($(container).data('ajax')) {
            $(container).removeAttr('data-ajax');
            $(this).remove();
        }
    });
});