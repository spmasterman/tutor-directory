var Biography = (function ($) {
    "use strict";

    var publicMembers = {
            //public variables
        },
        // private variables
        logToConsole = false,
        defaultToolbar = [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ],
        container = $('.bio')
    ;

    /**
     * constructor
     */
    var constructor = function() {
        // Setup DOM event handlers
        setupBio(container);
    };

    /**
     * Handlers for Edit/Save Bio
     *
     * @param bioContainer
     */
    function setupBio(bioContainer) {
        bioContainer.on('click', '.edit-bio', function(e){
            e.preventDefault();
            $('#bio').summernote({
                toolbar: defaultToolbar
            });
            $('.bio').find('.save-bio').show();
            $(this).hide();
        });

        bioContainer.on('click', '.save-bio', function(e){
            e.preventDefault();
            var btn = $(this);
            $.post(Routing.generate('tutor_ajax_update'), {
                'pk' : $(this).closest('.data-row').data('id'),
                'name' : 'bio',
                'value' : $('#bio').code()
            }, function(data) {
                if (data.success) {
                    $('#bio').destroy();
                    $('.bio').find('.edit-bio').show();
                    btn.hide();
                }
            }, "json");
        });
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
    constructor.prototype = publicMembers;

    // return the object
    return constructor;
}(jQuery));