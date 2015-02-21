var TutorProfileNote = (function ($) {
    "use strict";

    var publicMembers = {
            //public variables
        },
    // private variables
        logToConsole = false,
        prototypeRow,
        container = $('.notes-container')
        ;

    /**
     * constructor
     *
     * @param {string} prototypeRowFromServer
     */
    var init = function(prototypeRowFromServer) {
        prototypeRow = prototypeRowFromServer;

        $('.inline-note').each(function() {
            $(this).editable(getNoteOptions($(this)));
        });

        setupNotes(container);
    };

    /**
     * Handlers for Edit/Save Notes
     *
     * @param {jQuery} notesContainer
     */
    function setupNotes(notesContainer) {
        $('.add-note').on('click', function(e) {
            e.preventDefault();
            notesContainer.prepend(prototypeRow);

            $('#note0').each(function(){
                $(this).editable(getNoteOptions($(this)));
            });
        });

        notesContainer.on('click', '.remove-note', function(e){
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                notePk = $(this).data('pk')
                ;
            $.post(Routing.generate('note_ajax_remove'), {'pk' : notePk}, function(data) {
                if (data.success) {
                    row.remove();
                } else {
                    console.log(data);
                }
            }, "json");
        });
    }

    /**
     * Get x-editable options for a given element, that is going to be made an x-editable Note (custom type)
     *
     * @param {jQuery} host
     * @returns {{params: Function, success: Function}}
     */
    function getNoteOptions(host) {
        return {
            params: function (params) {
                params.notePk = host.attr('data-note-pk');
                params.noteKey = host.closest('[data-note-key]').data('noteKey');
                return params;
            },
            success: function (response) {
                host.attr('data-note-pk', response.id);
                host.attr("id", "note" + response.id);
                var provenance = host.closest('.data-row').find('.note-provenance');
                provenance.text(response.detail);
                provenance.append('<span class="data-action"><a href="#" data-pk="' + response.id + '" class="btn btn-danger btn-xs remove-note"><i class="fa fa-remove"></i></a></span>');
            },
            emptytext : 'New Note...'
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