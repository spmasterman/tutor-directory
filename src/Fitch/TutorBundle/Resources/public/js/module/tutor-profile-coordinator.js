var TutorProfileCoordinator = (function ($) {
    "use strict";

    var publicMembers = {
        //public variables
            languagePrototypeRow : {},
            allLanguages: {},
//            groupedLanguages: {},
            groupedCountries: {}
        },
    // private variables
        logToConsole = false
    ;

    var constructor = function(tutorId, serverData) {
        publicMembers.languagePrototypeRow = serverData.languagePrototype;
        publicMembers.allLanguages = serverData.allLanguages;
//        publicMembers.groupedLanguages = serverData.groupedLanguages;
        publicMembers.groupedCountries = serverData.groupedCountries;

        new Language(
            publicMembers.languagePrototypeRow,
            publicMembers.allLanguages
        );
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

    constructor.prototype = publicMembers;

    // return the class
    return constructor;
}(jQuery));
