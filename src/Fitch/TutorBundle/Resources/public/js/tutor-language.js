jQuery(document).ready(function() {
    "use strict";

    // Module wide variables
    var languages = [],
        prototypeRow,
        languagesContainer = $('.languages-container'),
        tutorId = languagesContainer.find('[data-id]').data('id')
    ;

    // Build data for Language selects in custom type. Only initialise the x-editable elements which use the data,
    // once the data has been retrieved
    $.getJSON(Routing.generate('all_languages'), {}, function(data) {
        languages = data.languages;

        $('.inline-tutor-language').each(function() {
            $(this).editable(getTutorLanguageOptions($(this)));
        });
        $('.inline-tutor-language-note').each(function() {
            $(this).editable(getTutorLanguageNoteOptions($(this)));
        });
    });

    $.getJSON(Routing.generate('prototype_language', { 'tutorId' : tutorId}), { }, function(data) {
        prototypeRow = data.prototypeRow;
    });

    // Setup DOM event handlers
    setupLanguages(languagesContainer);

    /**
     * Handlers for Add/Remove language
     *
     * @param languageContainer
     */
    function setupLanguages(languageContainer) {
        $('.add-language').on('click', function(e) {
            e.preventDefault();
            languageContainer.append(prototypeRow);
            languageContainer.find('#tutor-language0').each(function() {
                $(this).editable(getTutorLanguageOptions($(this)));
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

    function reloadTutorLanguageRow(row, response) {
        row.html(response.renderedTutorLanguageRow);
        row.attr('data-tutor-language-pk', response.id);
        row.find('.inline-tutor-language').each(function() {
            $(this).editable(getTutorLanguageOptions($(this)));
        });
        row.find('.inline-tutor-language-note').each(function() {
            $(this).editable(getTutorLanguageNoteOptions($(this)));
        });
    }

    function getTutorLanguageOptions(host) {
        return {
            params: function (params) {
                params.tutorLanguagePk = host.attr('data-tutor-language-pk');
                return params;
            },
            typeahead: {
                name: 'Language',
                local: languages
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

});