jQuery(document).ready(function() {
    "use strict";

    // Module wide variables
    var competencyTypes = [],
        competencyLevels = []
        ;

    // Build data for Competency selects in custom type. Only initialise the x-editable elements which use the data,
    // once the data has been retrieved
    $.getJSON(Routing.generate('competency_lookups'), {}, function(data) {
        competencyTypes = data.type;
        competencyLevels = data.level;

        $('.inline-competency-level').each(function() {
            $(this).editable(getCompetencyLevelOptions($(this)));
        });
        $('.inline-competency-type').each(function() {
            $(this).editable(getCompetencyTypeOptions($(this)));
        });
        $('.inline-competency-note').each(function() {
            $(this).editable(getCompetencyNoteOptions($(this)));
        });
    });

    setupCompetency($('.competency-container'));

    /**
     * Handlers for Add/Remove competency
     *
     * @param competencyContainer
     */
    function setupCompetency(competencyContainer) {
        $('.add-competency').on('click', function(e) {
            e.preventDefault();
            var tutorId = $(this).closest('[data-id]').data('id'),
                newRow =
                    ' <div class="data-row" data-id="' + tutorId + '" data-competency-pk="0">         '+
                    '    <span class="data-name">                                                     '+
                    '        <span class="data-tag">&nbsp;</span>                                     '+
                    '       <a href="#" id="competency-level0" class="inline-competency-level"        '+
                    '          data-type="typeaheadjs"                                                '+
                    '          data-pk="' + tutorId + '"                                              '+
                    '          data-competency-pk="0"                                                 '+
                    '          data-url="' + Routing.generate('competency_ajax_update')+'"            '+
                    '          data-title="Level of Competency"                                       '+
                    '       ></a>                                                                     '+
                    '    </span>                                                                      '+
                    '    <span class="data-value">                                                    '+
                    '       <a href="#" id="competency-type0" class="inline-competency-type"          '+
                    '          data-type="typeaheadjs"                                                '+
                    '          data-pk="' + tutorId + '"                                              '+
                    '          data-competency-pk="0"                                                 '+
                    '          data-url="' + Routing.generate('competency_ajax_update')+'"            '+
                    '          data-title="Competency"                                                '+
                    '        ></a>                                                                    '+
                    '    </span>                                                                      '+
                    '    <span class="data-action">                                                   '+
                    '        <a href="#" data-pk="0" class="btn btn-danger btn-xs remove-competency"> '+
                    '           <i class="fa fa-remove"></i>                                          '+
                    '        </a>                                                                     '+
                    '    </span>                                                                      '+
                    '    <span class="data-note">                                                     '+
                    '       <a href="#" id="competency-note0" class="inline-competency-note"          '+
                    '          data-type="textarea"                                                   '+
                    '          data-pk="' + tutorId + '"                                              '+
                    '          data-competency-pk="0"                                                 '+
                    '          data-url="' + Routing.generate('competency_ajax_update')+'"            '+
                    '          data-title="Note"                                                      '+
                    '       ></a>                                                                     '+
                    '    </span>                                                                      '+
                    ' </div>                                                                          '
                ;

            competencyContainer.append(newRow);

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
});