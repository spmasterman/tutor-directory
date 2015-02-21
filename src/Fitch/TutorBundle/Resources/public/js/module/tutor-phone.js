var TutorProfilePhone = (function ($) {
    "use strict";

    var publicMembers = {
            //public variables
        },
    // private variables
        logToConsole = false,
        allCountries,
        prototypeRow,
        container = $('.contact-info')
        ;

    /**
     * constructor
     *
     * @param {string} prototypeRowFromServer
     * @param {Array} allCountriesFromServer
     */
    var init = function(prototypeRowFromServer, allCountriesFromServer) {
        prototypeRow = prototypeRowFromServer;
        allCountries = allCountriesFromServer;

        $('.inline-phone').each(function() {
            $(this).editable(getPhoneOptions($(this)));
        });

        setupPhones(container);
    };

    /**
     * Handlers for Add/Remove phones
     *
     * Note that the container is the contact Info container (which holds the buttons) but the phones
     * get appended to the phones (sub-)container
     *
     * @param {jQuery} contactInfo
     */
    function setupPhones(contactInfo) {
        contactInfo.on('click', '.add-phone', function(e) {
            e.preventDefault();
            $('.phone-container').append(prototypeRow);
            $('#phone0').each(function(){
                $(this).editable(getPhoneOptions($(this)));
            });
        });

        contactInfo.on('click', '.remove-phone', function(e) {
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                phonePk = row.find('span.data-value a').attr('data-phone-pk');

            if (phonePk != '0') {
                $.post(Routing.generate('phone_ajax_remove'), {'pk' : phonePk }, function(data) {
                    if (data.success) {
                        row.remove();
                    }
                }, "json");
            } else {
                row.remove();
            }
        });
    }

    /**
     * Get x-editable options for a given element, that is going to be made an x-editable Email (custom type)
     *
     * @param host
     * @returns {{value: {type: *, number: *, country: *, isPreferred: *}, params: Function, success: Function, sourceCountry: Array}}
     */
    function getPhoneOptions(host) {
        return {
            value: {
                type: host.data('valueType'),
                number: host.data('valueNumber'),
                country: host.data('valueCountry'),
                isPreferred: host.data('valueIsPreferred')
            },
            params: function(params) {
                params.phonePk = host.attr('data-phone-pk');
                return params;
            },
            success: function(response, newValue) {
                host.closest('.data-row').find('.data-name').text('Phone (' + newValue.type +')');
                host.attr('data-phone-pk', response.id);
                host.attr( "id", "Phone" + response.id);
            },
            sourceCountry: allCountries
        }
    }

    /**
     * Log to console (if we are logging to console)
     * @param message
     */
    function log(message) {
        if (logToConsole) {
            console.log(message);
        }
    }

    /**
     * Expose log message as a public member
     * @param message
     */
    publicMembers.log = function(message) {
        log(message);
    };

    /**
     * Should we Log To Console?
     * @param log
     */
    publicMembers.setLogToConsole = function(log) {
        logToConsole = log;
    };

    // Add the public members to the prototype
    init.prototype = publicMembers;

    // return the object
    return init;
}(jQuery));