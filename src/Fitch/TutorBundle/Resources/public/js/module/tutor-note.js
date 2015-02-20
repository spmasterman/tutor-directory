var Note = (function ($) {
    "use strict";

    var publicMembers = {
            //public variables
        },
    // private variables
        logToConsole = false,
        container = $('.languages-container')
        ;

    /**
     * constructor
     */
    var constructor = function() {

    };

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