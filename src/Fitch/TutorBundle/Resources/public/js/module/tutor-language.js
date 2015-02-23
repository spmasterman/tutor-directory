var TutorProfileLanguage = (function ($) {
    "use strict";

    var publicMembers = {
            //public variables
        },
        // private variables
        logToConsole = false,
        prototypeRow,
        allLanguages,
        allProficiencies,
        container = $('.languages-container')
    ;

    /**
     * constructor
     *
     * @param {string} prototypeRowFromServer
     * @param {Array} allLanguagesFromServer
     * @param {Array} allProficienciesFromServer
     */
    var init = function(prototypeRowFromServer, allLanguagesFromServer, allProficienciesFromServer) {
        prototypeRow = prototypeRowFromServer;
        allLanguages = allLanguagesFromServer;
        allProficiencies = allProficienciesFromServer;

        $('.inline-tutor-language').each(function() {
            $(this).editable(getTutorLanguageOptions($(this)));
        });

        $('.inline-tutor-language-proficiency').each(function() {
            $(this).editable(getTutorLanguageProficiencyOptions($(this)));
        });

        $('.inline-tutor-language-note').each(function() {
            $(this).editable(getTutorLanguageNoteOptions($(this)));
        });

        // Setup DOM event handlers
        setupLanguages(container);
    };

    /**
     * Handlers for Add/Remove language
     *
     * @param {jQuery} languageContainer
     */
    function setupLanguages(languageContainer) {
        $('.add-language').on('click', function(e) {
            e.preventDefault();
            languageContainer.append(prototypeRow);
            languageContainer.find('#tutor-language0').each(function() {
                $(this).editable(getTutorLanguageOptions($(this)));
            });
            languageContainer.find('#tutor-language-proficiency0').each(function() {
                $(this).editable(getTutorLanguageProficiencyOptions($(this)));
            });
            languageContainer.find('#tutor-language-note0').each(function() {
                $(this).editable(getTutorLanguageNoteOptions($(this)));
            });
        });

        languageContainer.on('click', '.remove-tutor-language', function(e){
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                tutorLanguagePk = row.find('span.data-value a').attr('data-tutor-language-pk')
                ;

            if (tutorLanguagePk != '0') {
                $.post(Routing.generate('tutor_language_ajax_remove'), {'pk' : tutorLanguagePk}, function(data) {
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
    }

    /**
     * Reloads the Row in the DOM from the Response object
     *
     * @param {jQuery} row jQuery container element for new Row
     * @param {object} response The response object from the AJAX call
     * @param {int} response.id The ID of the object in the row
     * @param {string} response.renderedTutorLanguageRow The newly rendered row
     */
    function reloadTutorLanguageRow(row, response) {
        row.html(response.renderedTutorLanguageRow);
        row.attr('data-tutor-language-pk', response.id);

        row.find('.inline-tutor-language').each(function() {
            $(this).editable(getTutorLanguageOptions($(this)));
        });

        row.find('.inline-tutor-language-proficiency').each(function() {
            $(this).editable(getTutorLanguageProficiencyOptions($(this)));
        });

        row.find('.inline-tutor-language-note').each(function() {
            $(this).editable(getTutorLanguageNoteOptions($(this)));
        });
    }

    /**
     * Options for the x-editable Language
     *
     * @param {jQuery} host
     * @returns {{params: Function, typeahead: {name: string, local: *}, validate: Function, success: Function}}
     */
    function getTutorLanguageOptions(host) {
        return {
            params: function (params) {
                params.tutorLanguagePk = host.attr('data-tutor-language-pk');
                return params;
            },
            typeahead: {
                name: 'Language',
                local: allLanguages
            },
            validate: function (value) {
                if ($.trim(value) == '') {
                    return 'This field is required';
                }
            },
            success: function (response) {
                reloadTutorLanguageRow(host.closest('.data-row'), response)
            }
        }
    }

    /**
     * Options for the x-editable Language
     *
     * @param {jQuery} host
     * @returns {{params: Function, typeahead: {name: string, local: *}, validate: Function, success: Function}}
     */
    function getTutorLanguageProficiencyOptions(host) {
        return {
            params: function (params) {
                params.tutorLanguagePk = host.attr('data-tutor-language-pk');
                return params;
            },
            typeahead: {
                name: 'Proficiency',
                local: allProficiencies
            },
            validate: function (value) {
                if ($.trim(value) == '') {
                    return 'This field is required';
                }
            },
            success: function (response) {
                reloadTutorLanguageRow(host.closest('.data-row'), response)
            }
        }
    }

    /**
     * Options for the x-editable Note
     *
     * @param host
     * @returns {{params: Function, emptytext: string, emptyclass: string, success: Function}}
     */
    function getTutorLanguageNoteOptions(host) {
        return {
            params: function(params) {
                params.tutorLanguagePk = host.attr('data-tutor-language-pk');
                return params;
            },
            emptytext : 'Add note...',
            emptyclass : 'empty-note',
            success: function(response) {
                reloadTutorLanguageRow(host.closest('.data-row'), response)
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