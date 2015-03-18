var TutorProfileAdmin = (function ($) {
    "use strict";

    var publicMembers = {
            //public variables
        },
    // private variables
        logToConsole = false,
        container = $('.admin-function')
        ;

    /**
     * constructor
     */
    var init = function() {
        setupDeleteAdminFunction(container);
    };

    /**
     * @param {jQuery} container
     */
    function setupDeleteAdminFunction(container) {
        container.on('click', '.delete-tutor', function (e) {
            e.preventDefault();

            var deleteForm = $(this).closest('form');

            $.when(function () {
                return deleteForm.find('.delete-tutor').fadeOut(400);
            }()).done(function () {
                deleteForm.append(
                    '<div class="confirm-remove" style="display: none"><h4><i class="fa fa-warning red-font"></i> Are you sure? </h4>' +
                        '<button class="confirm-execute btn btn-large btn-danger"><i class="fa fa-trash-o"></i> Yes I understand what I am doing</button>' +
                        '<button class="confirm-cancel btn btn-large btn-default"><i class="fa fa-arrow-circle-o-left"></i> No, please cancel</button>' +
                    '</div>'
                );
                deleteForm.find('.confirm-remove').fadeIn(400);
            });

            deleteForm.on('click', '.confirm-cancel', function (e) {
                e.preventDefault();
                $(this).closest('.confirm-remove').remove();
                deleteForm.find('.delete-tutor').fadeIn(400);
            });
            deleteForm.on('click', '.confirm-execute', function (e) {
                e.preventDefault();
                $(this).closest('form').submit();
            });
        });
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