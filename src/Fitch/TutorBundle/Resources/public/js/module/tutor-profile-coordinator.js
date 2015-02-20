var TutorProfileCoordinator = (function ($) {
    "use strict";

    var publicMembers = {
        //public variables
        },
    // private variables
        logToConsole = false
    ;

    /**
     *
     * @param {int} tutorId
     * @param {Object} serverData
     * @param {string} serverData.languagePrototype
     * @param {string} serverData.competencyPrototype
     * @param {string} serverData.addressPrototype
     * @param {string} serverData.phonePrototype
     * @param {string} serverData.emailPrototype
     * @param {string} serverData.notePrototype
     * @param {string} serverData.ratePrototype
     * @param {string[]} serverData.allLanguages
     * @param {string[]} serverData.allCompetencyTypes
     * @param {string[]} serverData.allCompetencyLevels
     * @param {Array} serverData.groupedCountries
     */
    var constructor = function(tutorId, serverData) {
        new Language(
            serverData.languagePrototype,
            serverData.allLanguages
        );

        new Competency(
            serverData.competencyPrototype,
            serverData.allCompetencyTypes,
            serverData.allCompetencyLevels
        );

        new Biography();

        new Address(serverData.addressPrototype, serverData.groupedCountries);
        new Phone(serverData.phonePrototype, serverData.groupedCountries);
        new Email(serverData.emailPrototype);
        new Note(serverData.notePrototype);
        new Rate(serverData.ratePrototype);
        new File();

        // Initialise the other x-editable elements
        $('.inline').editable();
    };

    /**
     * Log to console (if we are logging to console)
     * @param {string} message
     */
    function log(message) {
        if (logToConsole) {
            console.log(message);
        }
    }

    /**
     * Expose log message as a public member
     * @param {string} message
     */
    publicMembers.log = function(message) {
        log(message);
    };

    /**
     * Should we Log To Console?
     * @param {boolean} log
     */
    publicMembers.setLogToConsole = function(log) {
        logToConsole = log;
    };

    constructor.prototype = publicMembers;

    // return the class
    return constructor;
}(jQuery));
