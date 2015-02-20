// Stop Dropzone doing its automatic thing
Dropzone.autoDiscover = false;

jQuery(document).ready(function() {
    "use strict";

    // Module wide variables
    var countryData = [],

        ;

    // Build data for County selects in custom types (Phone and Address) - same data but slightly different display
    // format. Only initialise the x-editable elements which use the data, once the data has been retrieved
    $.getJSON(Routing.generate('active_countries'), {}, function(data) {
        countryData = data;



        $('.inline-rate').each(function() {
            $(this).editable(getRateOptions($(this)));
        });
    });

    // Initialise the other x-editable elements
    $('.inline').editable();
    $('.inline-file-type').editable({
        success: function (response) {
            $(this).closest('.data-row').find('.details-holder').html(response.renderedFileRow);
            $('#avatar-container').html(response.renderedAvatar);
        }
    });
    $('.inline-note').each(function() {
        $(this).editable(getNoteOptions($(this)));
    });


    // Setup DOM event handlers
    setupRates($('.rates-container'));
    setupNotes($('.notes-container'));
    setupFiles($('#files-container'));
    setupAvatar($('#avatar-container'));




    /**
     * Handlers for Edit/Save Notes
     *
     * @param notesContainer
     */
    function setupNotes(notesContainer) {
        $('.add-note').on('click', function(e) {
            e.preventDefault();
            var tutorId = $(this).closest('[data-id]').data('id'),
                newRow =
                    '    <div class="data-row">                                                     '+
                    '        <div class="note">                                                     '+
                    '           <a href="#" id="note0" class="inline-note"                          '+
                    '               data-inputclass="input-note"                                    '+
                    '               data-type="textarea"                                            '+
                    '               data-pk="' + tutorId + '"                                       '+
                    '               data-url="' + Routing.generate('tutor_ajax_update')+'"          '+
                    '               data-title="Enter Note"                                         '+
                    '               data-note-pk="0"                                                '+
                    '            ></a></div>                                                        '+
                    '        <div class="note-provenance pull-right"></div>                         '+
                    '    </div>                                                                     '
                ;
            notesContainer.prepend(newRow);

            $('#note0').each(function(){
                $(this).editable(getNoteOptions($(this)));
            });
        });

        notesContainer.on('click', '.remove-note', function(e){
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                notePk = row.data('id')
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
     * Handlers for Edit/Save rates
     *
     * @param ratesContainer
     */
    function setupRates(ratesContainer) {
        $('.add-rate').on('click', function(e) {
            e.preventDefault();
            var tutorId = $(this).closest('[data-id]').data('id'),
                newRow =
                    '    <div class="data-row" data-id="0">                                         '+
                    '        <span class="data-name">New Rate</span>                                '+
                    '        <span class="data-value">                                              '+
                    '           <a href="#" id="rate0" class="inline-rate"                          '+
                    '           data-type="rate"                                                    '+
                    '           data-pk="' + tutorId + '"                                           '+
                    '           data-rate-pk="0"                                                    '+
                    '           data-url="' + Routing.generate('tutor_ajax_update')+'"              '+
                    '           data-title="Enter Rate"                                             '+
                    '            ></a>                                                              '+
                    '        </span>                                                                '+
                    '        <span class="data-action">                                             '+
                    '            <a href="#" data-pk="0" class="btn btn-danger btn-xs remove-rate"> '+
                    '               <i class="fa fa-remove"></i>                                    '+
                    '            </a>                                                               '+
                    '        </span>                                                                '+
                    '    </div>                                                                     '
            ;
            ratesContainer.append(newRow);

            $('#rate0').each(function(){
                $(this).editable(getRateOptions($(this)));
            });
        });

        ratesContainer.on('click', '.remove-rate', function(e){
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                ratePk = row.find('span.data-value a').attr('data-rate-pk')
                ;

            if (ratePk != '0') {
                $.post(Routing.generate('rate_ajax_remove'), {'pk' : ratePk}, function(data) {
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

        ratesContainer.on('click', 'span.data-history', function(){
            $('.modal-body').html($(this).find('.data-history-content').html());
        });
    }

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
     * Get x-editable options for a given element, that is going to be made an x-editable Note (custom type)
     *
     * @param host
     * @returns {{params: Function, success: Function, sourceCountry: Array}}
     */
    function getNoteOptions(host) {
        return {
            params: function (params) {
                params.notePk = host.attr('data-note-pk');
                params.noteKey = host.closest('[data-note-key]').data('noteKey');
                return params;
            },
            success: function (response) {
                host.attr('data-phone-pk', response.id);
                host.attr("id", "note" + response.id);
                host.closest('.data-row').find('.note-provenance').text(response.detail);
            },
            sourceCountry: countryData,
            emptytext : 'New Note...'
        }
    }

    /**
     * Get x-editable options for a given element, that is going to be made an x-editable Rate (custom type)
     *
     * @param host
     * @returns {{value: {name: *, amount: *}, params: Function, success: Function}}
     */
    function getRateOptions(host) {
        return {
            value: {
                name: host.data('valueName'),
                amount: host.data('valueAmount')
            },
            params: function(params) {
                params.ratePk = host.attr('data-rate-pk');
                return params;
            },
            validate: function(value) {
                if (!$.isNumeric(value.amount)) {
                    return {newValue: {name: value.name, amount: '0.00'}, msg: 'Non Numeric values entered'}
                }
                if (Math.round(100 * value.amount) != 100 * value.amount) {
                    return {newValue: {name: value.name, amount: Math.round(100 * value.amount)/100}, msg: 'Rate will be rounded to two decimal places'}
                }
            },
            success: function(response, newValue) {
                host.closest('.data-row').find('.data-name').text(newValue.name + ' Rate');
                host.attr('data-rate-pk', response.id);
                host.attr( "id", "Rate" + response.id);
            }
        }
    }
});