var TutorProfileCoordinator = (function ($) {
    "use strict";

    var publicMembers = {
        //public variables
            languagePrototypeRow : {},
            competencyPrototypeRow : {},
            addressPrototypeRow : {},
            emailPrototypeRow : {},
            phonePrototypeRow : {},
            allLanguages: {},
            allCompetencyTypes: {},
            allCompetencyLevels: {},
//            groupedLanguages: {},
            groupedCountries: {}
        },
    // private variables
        logToConsole = false
    ;

    var constructor = function(tutorId, serverData) {
        publicMembers.languagePrototypeRow = serverData.languagePrototype;
        publicMembers.competencyPrototypeRow = serverData.competencyPrototype;
        publicMembers.addressPrototypeRow = serverData.addressPrototype;
        publicMembers.emailPrototypeRow = serverData.emailPrototype;
        publicMembers.phonePrototypeRow = serverData.phonePrototype;

        publicMembers.allLanguages = serverData.allLanguages;
        publicMembers.allCompetencyTypes = serverData.allCompetencyTypes;
        publicMembers.allCompetencyLevels = serverData.allCompetencyLevels;
//        publicMembers.groupedLanguages = serverData.groupedLanguages;
        publicMembers.groupedCountries = serverData.groupedCountries;

        new Language(
            publicMembers.languagePrototypeRow,
            publicMembers.allLanguages
        );

        new Competency(
            publicMembers.competencyPrototypeRow,
            publicMembers.allCompetencyTypes,
            publicMembers.allCompetencyLevels
        );

        new Biography();

        new Address(publicMembers.addressPrototypeRow, publicMembers.groupedCountries);
        new Phone(publicMembers.phonePrototypeRow, publicMembers.groupedCountries);
        new Email(publicMembers.emailPrototypeRow);
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
