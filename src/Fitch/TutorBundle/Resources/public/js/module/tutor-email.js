var Email = (function ($) {
    "use strict";

    var publicMembers = {
            //public variables
        },
    // private variables
        logToConsole = false,
        prototypeRow,
        container = $('.contact-info')
        ;

    /**
     * constructor
     */
    var constructor = function(prototypeRowFromServer) {
        prototypeRow = prototypeRowFromServer;

        $('.inline-email').each(function() {
            $(this).editable(getEmailOptions($(this)));
        });

        setupEmails(container);
    };

    /**
     * Handlers for Add/Remove email
     *
     * Note that the container is the contact Info container (which holds the buttons) but the emails
     * get appended to the email (sub-)container
     *
     * @param {jQuery} contactInfo
     */
    function setupEmails(contactInfo) {
        contactInfo.on('click', '.add-email', function (e) {
            e.preventDefault();
            $('.email-container').append(prototypeRow);

            $('#email0').each(function () {
                $(this).editable(getEmailOptions($(this)));
            });
        });


        contactInfo.on('click', '.remove-email', function (e) {
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                emailPk = row.find('span.data-value a').attr('data-email-pk');

            if (emailPk != '0') {
                $.post(Routing.generate('email_ajax_remove'), {'pk': emailPk}, function (data) {
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
     * @returns {{value: {type: *, address: *}, params: Function, success: Function}}
     */
    function getEmailOptions(host) {
        return {
            value: {
                type: host.data('valueType'),
                address: host.data('valueAddress')
            },
            params: function(params) {
                params.emailPk = host.attr('data-email-pk');
                return params;
            },
            success: function(response, newValue) {
                host.closest('.data-row').find('.data-name').text('Email (' + newValue.type +')');
                host.attr('data-email-pk', response.id);
                host.attr( "id", "Email" + response.id);
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
    constructor.prototype = publicMembers;

    // return the object
    return constructor;
}(jQuery));