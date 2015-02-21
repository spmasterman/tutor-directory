var TutorProfileCompetency = (function ($) {
    "use strict";

    var publicMembers = {
            //public variables
        },
    // private variables
        logToConsole = false,
        prototypeRow,
        competencyTypes,
        competencyLevels,
        container = $('.competency-container')
        ;

    /**
     * constructor
     *
     * @param {string} prototypeRowFromServer
     * @param {Array} competencyTypesFromServer
     * @param {Array} competencyLevelsFromServer
     */
    var init = function(prototypeRowFromServer, competencyTypesFromServer, competencyLevelsFromServer) {
        prototypeRow = prototypeRowFromServer;
        competencyTypes = competencyTypesFromServer;
        competencyLevels = competencyLevelsFromServer;

        $('.inline-competency-level').each(function() {
            $(this).editable(getCompetencyLevelOptions($(this)));
        });
        $('.inline-competency-type').each(function() {
            $(this).editable(getCompetencyTypeOptions($(this)));
        });
        $('.inline-competency-note').each(function() {
            $(this).editable(getCompetencyNoteOptions($(this)));
        });

        setupCompetency(container);
    };

    /**
     * Handlers for Add/Remove competency
     *
     * @param competencyContainer
     */
    function setupCompetency(competencyContainer) {
        $('.add-competency').on('click', function(e) {
            e.preventDefault();
            competencyContainer.append(prototypeRow);
            competencyContainer.find('#competency-level0').each(function() {
                $(this).editable(getCompetencyLevelOptions($(this)));
            });
            competencyContainer.find('#competency-type0').each(function() {
                $(this).editable(getCompetencyTypeOptions($(this)));
            });
            competencyContainer.find('#competency-note0').each(function() {
                $(this).editable(getCompetencyNoteOptions($(this)));
            });
        });

        competencyContainer.on('click', '.remove-competency', function(e){
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                competencyPk = row.find('span.data-value a').attr('data-competency-pk')
                ;

            if (competencyPk != '0') {
                $.post(Routing.generate('competency_ajax_remove'), {'pk' : competencyPk}, function(data) {
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
     * @param {string} response.renderedCompetencyRow The newly rendered row
     */
    function reloadCompetencyRow(row, response) {
        row.html(response.renderedCompetencyRow);
        row.attr('data-competency-pk', response.id);
        row.find('.inline-competency-level').each(function() {
            $(this).editable(getCompetencyLevelOptions($(this)));
        });
        row.find('.inline-competency-type').each(function() {
            $(this).editable(getCompetencyTypeOptions($(this)));
        });
        row.find('.inline-competency-note').each(function() {
            $(this).editable(getCompetencyNoteOptions($(this)));
        });
    }

    /**
     * Options for the x-editable competencyLevel
     *
     * @param {jQuery} host
     * @returns {{params: Function, placeholder: string, validate: Function, typeahead: {name: string, local: *}, success: Function}}
     */
    function getCompetencyLevelOptions(host) {
        return {
            params: function(params) {
                params.competencyPk = host.attr('data-competency-pk');
                return params;
            },
            placeholder: 'Intern, Practitioner, etc',
            validate: function(value) {
                if($.trim(value) == '') {
                    return 'This field is required';
                }
            },
            typeahead: {
                name: 'Level',
                local: competencyLevels
            },
            success: function(response) {
                reloadCompetencyRow(host.closest('.data-row'), response)
            }
        }
    }

    /**
     * Options for the x-editable competencyType
     *
     * @param {jQuery} host
     * @returns {{params: Function, typeahead: {name: string, local: *}, validate: Function, success: Function}}
     */
    function getCompetencyTypeOptions(host) {
        return {
            params: function(params) {
                params.competencyPk = host.attr('data-competency-pk');
                return params;
            },
            typeahead: {
                name: 'Type',
                local: competencyTypes
            },
            validate: function(value) {
                if($.trim(value) == '') {
                    return 'This field is required';
                }
            },
            success: function(response) {
                reloadCompetencyRow(host.closest('.data-row'), response)
            }
        }
    }

    /**
     * Options for the x-editable note
     *
     * @param {jQuery} host
     * @returns {{params: Function, emptytext: string, emptyclass: string, success: Function}}
     */
    function getCompetencyNoteOptions(host) {
        return {
            params: function(params) {
                params.competencyPk = host.attr('data-competency-pk');
                return params;
            },
            emptytext : 'Add note...',
            emptyclass : 'empty-note',
            success: function(response) {
                reloadCompetencyRow(host.closest('.data-row'), response)
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

    // return the class
    return init;
}(jQuery));