var File = (function ($) {
    "use strict";

    var publicMembers = {
            //public variables
        },
        // private variables
        logToConsole = false,
        filesContainer = $('#files-container'),
        avatarContainer = $('#avatar-container')
    ;

    /**
     * constructor
     */
    var constructor = function() {
        $('.inline-file-type').editable({
            success: function (response) {
                $(this).closest('.data-row').find('.details-holder').html(response.renderedFileRow);
                $('#avatar-container').html(response.renderedAvatar);
            }
        });

        setupFiles(filesContainer);
        setupAvatar(avatarContainer);
    };

    /**
     * Handler for interacting with Files, and dropzone initialisation
     */
    function setupFiles(filesContainer) {
        var tutorDropzone = new Dropzone("#file_upload");
        tutorDropzone.on("success", function(file, response) {
            if (response.success) {
                $('#files-container').append(response.fileRow);

                var regex = /.*data-pk="(\d+)".*/gi;
                var match = regex.exec(response.fileRow);
                var id = match[1];

                $('#fileType' + id).editable();
            } else {
                console.log(response);
            }
        });

        filesContainer.on('click', '.remove-file', function(e){
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                filePk = row.data('id')
                ;
            $.post(Routing.generate('file_ajax_remove'), {'pk' : filePk}, function(data) {
                if (data.success) {
                    row.closest('.file-entry').remove();
                } else {
                    console.log(data);
                }
            }, "json");
        });
    }

    /**
     * Handle crop and Zoom settings for Avatar
     *
     */
    function setupAvatar(avatarContainer) {
        var jcrop_api = null,
            avatar = $('#avatar');

        avatarContainer.on('click', '#crop-zoom', function (e) {
            e.preventDefault();

            var btn = $(this),
                id = btn.data('id');

            if (jcrop_api == null) {
                $('.user-info-right').hide();
                avatar.attr('src', Routing.generate('get_file_stream', {id: id}));
                avatar.Jcrop({
                    boxWidth: 450,
                    boxHeight: 400,
                    onSelect: updateCropCoordinates,
                    bgColor: 'black',
                    bgOpacity: .4,
                    setSelect: [
                        btn.data('cropX'),
                        btn.data('cropY'),
                        btn.data('cropX') + btn.data('cropWidth'),
                        btn.data('cropY') + btn.data('cropHeight')
                    ],
                    aspectRatio: 1
                }, function () {
                    jcrop_api = this;
                });

                $(this).html('<i class="fa fa-save"></i> Save Crop/Zoom');
            } else {
                $.post(Routing.generate('file_ajax_crop'), {
                    pk: id,
                    originX: btn.data('cropX'),
                    originY: btn.data('cropY'),
                    width: btn.data('cropHeight'),
                    height: btn.data('cropWidth')
                }, function () {
                    jcrop_api.destroy();
                    avatar.attr({
                        src: Routing.generate('get_file_as_avatar', {
                            id: id
                        }),
                        style: {
                            width: '150px',
                            height: '150px'
                        }
                    });
                    $('.user-info-right').show();
                    btn.html('<i class="fa fa-crop"></i> Crop/Zoom Image');
                    jcrop_api = null;
                });
            }
        });

        function updateCropCoordinates(coordinates) {
            var cropInfoHolder = $('#crop-zoom');
            cropInfoHolder.data('cropX', coordinates.x);
            cropInfoHolder.data('cropY', coordinates.y);
            cropInfoHolder.data('cropWidth', coordinates.w);
            cropInfoHolder.data('cropHeight', coordinates.h);
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
    constructor.prototype = publicMembers;

    // return the object
    return constructor;
}(jQuery));