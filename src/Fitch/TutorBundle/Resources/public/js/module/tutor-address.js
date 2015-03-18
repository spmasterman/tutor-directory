var TutorProfileAddress = (function ($) {
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

        $('.inline-address').each(function() {
            $(this).editable(getAddressOptions($(this)));
        });

        setupAddresses(container);
    };

    /**
     * Handlers for Add/Remove address
     *
     * Note that the container is the contact Info container (which holds the buttons) but the addresses
     * get appended to the address (sub-)container
     *
     * @param {jQuery} contactInfo
     */
    function setupAddresses(contactInfo) {
        contactInfo.on('click', '.add-address', function (e) {
            e.preventDefault();
            $('.address-container').append(prototypeRow);
            $('#address0').each(function () {
                $(this).editable(getAddressOptions($(this)));
            });
        });

        contactInfo.on('click', '.remove-address', function (e) {
            e.preventDefault();

            var row = $(this).closest('.data-row');

            $.when(function () {
                return row.find('span').fadeOut(400);
            }()).done(function () {
                row.append('<div class="confirm" style="display: none"><i class="fa fa-warning red-font"/> Are you sure? <button class="confirm-execute btn btn-xs btn-danger"><i class="fa fa-trash-o"/>Delete</button><button class="confirm-cancel btn btn-xs btn-default"><i class="fa fa-arrow-circle-o-left"/>Cancel</button></div>');
                row.find('.confirm').fadeIn(400);
            });

            row.on('click', '.confirm-cancel', function (e) {
                e.preventDefault();
                $(this).closest('.confirm').remove();
                row.find('span').fadeIn(400);
            });
            row.on('click', '.confirm-execute', function (e) {
                e.preventDefault();

                var addressPk = row.find('span.data-value a').attr('data-address-pk');

                if (addressPk != '0') {
                    $.post(Routing.generate('address_ajax_remove'), {'pk': addressPk}, function (data) {
                        if (data.success) {
                            row.remove();
                        }
                    }, "json");
                } else {
                    row.remove();
                }
            });
        });
    }

    /**
     * Get x-editable options for a given element, that is going to be made an x-editable Address (custom type)
     *
     * @param host
     * @returns {{value: {type: *, streetPrimary: *, streetSecondary: *, city: *, state: *, zip: *, country: *}, params: Function, success: Function, sourceCountry: Array}}
     */
    function getAddressOptions(host) {
        return {
            value: {
                type: host.data('valueType'),
                streetPrimary: host.data('valueStreetPrimary'),
                streetSecondary: host.data('valueStreetSecondary'),
                city: host.data('valueCity'),
                state: host.data('valueState'),
                zip: host.data('valueZip'),
                country: host.data('valueCountry')
            },
            params: function(params) {
                params.addressPk = host.attr('data-address-pk');
                return params;
            },
            success: function(response, newValue) {
                host.closest('.data-row').find('.data-name').text('Address (' + newValue.type +')');
                host.attr('data-address-pk', response.id);
                host.attr( "id", "Address" + response.id);
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