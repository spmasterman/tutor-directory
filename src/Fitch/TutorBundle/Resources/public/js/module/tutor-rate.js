var TutorProfileRate = (function ($) {
    "use strict";

    var publicMembers = {
            //public variables
        },
        // private variables
        logToConsole = false,
        prototypeRow,
        container = $('.rates-container')
    ;

    /**
     * constructor
     *
     * @param {string} prototypeRowFromServer
     */
    var init = function(prototypeRowFromServer) {
        prototypeRow = prototypeRowFromServer;

        $('.inline-rate').each(function() {
            $(this).editable(getRateOptions($(this)));
        });

        setupRates(container);
    };

    /**
     * Handlers for Edit/Save rates
     *
     * @param ratesContainer
     */
    function setupRates(ratesContainer) {
        $('.add-rate').on('click', function(e) {
            e.preventDefault();
            ratesContainer.append(prototypeRow);

            $('#rate0').each(function(){
                $(this).editable(getRateOptions($(this)));
            });
        });

        ratesContainer.on('click', '.remove-rate', function(e) {
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
                var ratePk = row.find('span.data-value a').attr('data-rate-pk');

                if (ratePk != '0') {
                    $.post(Routing.generate('rate_ajax_remove'), {'pk': ratePk}, function (data) {
                        if (data.success) {
                            row.remove();
                        } else {
                            console.log(data);
                        }
                    }, "json");
                } else {
                    row.remove();
                }
            });
        });
    }

    /**
     * Get x-editable options for a given element, that is going to be made an x-editable Rate (custom type)
     *
     * @param {jQuery} host
     * @returns {{value: {name: *, amount: *}, params: Function, success: Function}}
     */
    function getRateOptions(host) {
        return {
            value: {
                name: host.data('valueName'),
                amount: host.data('valueAmount')
            },
            params: function(params) {
                params.ratePk = host.attr('data-rate-pk');
                return params;
            },
            validate: function(value) {
                if (!value.name) {
                    return {newValue: {name: 'Day', amount: value.amount}, msg: 'Name\\Type set to \'Day\''}
                }
                if (!$.isNumeric(value.amount)) {
                    return {newValue: {name: value.name, amount: '0.00'}, msg: 'Non Numeric values entered'}
                }
                if (value.amount < 0 ) {
                    return {newValue: {name: value.name, amount: 0.00}, msg: 'Rate Minimum is 0.00'}
                }
                if (Math.round(100 * value.amount) != 100 * value.amount) {
                    return {newValue: {name: value.name, amount: Math.round(100 * value.amount)/100}, msg: 'Rate will be rounded to two decimal places'}
                }

            },
            success: function(response, newValue) {
                host.closest('.data-row').find('.data-name').text(newValue.name + ' Rate');
                host.attr('data-rate-pk', response.id);
                host.attr( "id", "Rate" + response.id);
            }
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