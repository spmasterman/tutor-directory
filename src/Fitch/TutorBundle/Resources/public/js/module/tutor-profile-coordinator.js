var TutorProfileCoordinator = (function ($) {
    "use strict";

    var publicMembers = {
        //public variables
        },
    // private variables
        logToConsole = false
    ;

    /**
     * constructor
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
    var init = function(tutorId, serverData) {
        new TutorProfileLanguage(
            serverData.languagePrototype,
            serverData.allLanguages
        );

        new TutorProfileCompetency(
            serverData.competencyPrototype,
            serverData.allCompetencyTypes,
            serverData.allCompetencyLevels
        );

        new TutorProfileBiography();

        new TutorProfileAddress(serverData.addressPrototype, serverData.groupedCountries);
        new TutorProfilePhone(serverData.phonePrototype, serverData.groupedCountries);
        new TutorProfileEmail(serverData.emailPrototype);
        new TutorProfileNote(serverData.notePrototype);
        new TutorProfileRate(serverData.ratePrototype);
        new TutorProfileFile();

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

    init.prototype = publicMembers;

    // return the class
    return init;
}(jQuery));
