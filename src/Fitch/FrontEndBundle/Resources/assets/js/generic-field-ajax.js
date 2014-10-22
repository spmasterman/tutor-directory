/**
 * This file implements some generic ajax updating for "input". If you need anything more than simple handling
 * implement it as is done in inbox.js or recipe.js with a dedicated model module
 *
 * Requirements:
 *
 * Add attributes to any input field with
 *  data-ajax='true'
 *  data-ajax-field='<fieldname>'
 *  data-ajax-callback='<routename>'
 *
 * Add attributes to a parent container somewhere
 *  data-ajax-key-holder='true'
 *  data-ajax-key='<entity id>'
 *
 * Optionally Add hidden UI items within that parent container with attributes
 *  data-ajax-indicator='success'
 *  data-ajax-indicator='failure'
 *
 * These will get flashed
 *
 * Implement the routename in a controller and expose the route (options={"expose"=true})
 * The fieldname MUST be settable via a setter of the obvious name: setFieldName for fieldName
 *
 */

$(document).ready(function(){
    "use strict";

    setupHandlers($('body'));

    function setupHandlers(container) {

        // Handle changing input fields (Note: Delegate function as it must bind to inputs that don't exist yet)
        container.on("change", "input[data-ajax='true']", function(){
            var keyHolder = $(this).closest("[data-ajax-key-holder='true']")[0];

            var key = $(keyHolder).data('ajax-key');
            var field = $(this).data('ajax-field');
            var callbackUrl = Routing.generate($(this).data('ajax-callback'));
            var successIndicator = $(keyHolder).find("[data-ajax-indicator='success']");
            var failureIndicator = $(keyHolder).find("[data-ajax-indicator='failure']");

            var value;

            if ($(this).is(':checkbox')) {
                value = $(this).is(':checked') ? "1" : "";
            } else {
                value = $(this).val();
            }

            $.post(
                callbackUrl,
                {
                    id: key,
                    field: field,
                    value: value
                },
                function(data) {
                    if (data.success) {
                        flashIndicator(successIndicator);
                    } else {
                        flashIndicator(failureIndicator);
                       // log(data.message);
                    }
                },
                "json"
            )
            .fail(function() {
                flashIndicator(failureIndicator);
                //log('Posting failed:' + saveCallbackUrl);
            });
        });

        function flashIndicator(indicator) {
            indicator.fadeIn(function(){indicator.fadeOut()});
        }
    }

});