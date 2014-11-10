// Stop Dropzone doing its automatic thing
Dropzone.autoDiscover = false;

jQuery(document).ready(function() {
    "use strict";

    // Module wide variables
    var addressCountryData = [],
        phoneCountryData =[],
        // Default toolbar style for inline summer note editor
        defaultToolbar = [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
        ;

    // Build data for County selects in custom types (Phone and Address) - same data but slightly different display
    // format. Only initialise the x-editable elements which use the data, once the data has been retrieved
    $.getJSON(Routing.generate('all_countries'), {}, function(data) {
        $(data).each(function(i, k){
            addressCountryData.push(k);
            phoneCountryData.push({text: k['text'] + ' ('+ k['dialingCode']+')', value: k['value']});
        });

        $('.inline-address').each(function() {
            $(this).editable(getAddressOptions($(this)));
        });
        $('.inline-phone').each(function() {
            $(this).editable(getPhoneOptions($(this)));
        });
    });

    // Initialise the other x-editable elements
    $('.inline').editable();
    $('.inline-rate').editable({
        validate: function(value) {
            if (!$.isNumeric(value)) {
                return {newValue: '0.00', msg: 'Non Numeric values entered'}
            }
            if (Math.round(100 * value) != 100 * value) {
                return {newValue: Math.round(100 * value)/100, msg: 'Rate will be rounded to two decimal places'}
            }
        }
    });
    $('.inline-file-type').editable({
        success: function (response) {
            $(this).closest('.data-row').find('.details-holder').html(response.renderedFileRow);
            $('#avatar-container').html(response.renderedAvatar);
        }
    });
    $('.inline-note').each(function() {
        $(this).editable(getNoteOptions($(this)));
    });
    $('.inline-email').each(function() {
        $(this).editable(getEmailOptions($(this)));
    });

    // Setup DOM event handlers
    setupContactInfo($('.contact-info'));
    setupBio($('.bio'));
    setupNotes($('.notes-container'));
    setupFiles($('#files-container'));
    setupAvatar($('#avatar-container'));

    /**
     * Handlers for Add/Remove contact info
     * @param contactInfo
     */
    function setupContactInfo(contactInfo) {
        contactInfo.on('click', '.remove-address', function(e) {
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                addressPk = row.find('span.data-value a').attr('data-address-pk');

            if (addressPk != '0') {
                $.post(Routing.generate('address_ajax_remove'), {'pk' : addressPk }, function(data) {
                    if (data.success) {
                        row.remove();
                    }
                }, "json");
            } else {
                row.remove();
            }
        });

        contactInfo.on('click', '.add-address', function(e) {
            e.preventDefault();
            var tutorId = $(this).closest('.data-row').data('id'),
                newRow =
                    '    <p class="data-row" data-id="' + tutorId + '">                                     '+
                    '        <span class="data-name">New Address</span>                                     '+
                    '        <span class="data-value">                                                      '+
                    '            <a href="#"  id="address0" class="inline-address"                          '+
                    '                data-type="address"                                                    '+
                    '                data-pk="' + tutorId + '"                                              '+
                    '                data-address-pk="0"                                                    '+
                    '                data-url="' + Routing.generate('tutor_ajax_update') + '"               '+
                    '                data-title="Enter Address"                                             '+
                    '            ></a>                                                                      '+
                    '        </span>                                                                        '+
                    '        <span class="data-action">                                                     '+
                    '            <a href="#" data-pk="0" class="btn btn-danger btn-xs remove-address">      '+
                    '                <i class="fa fa-remove"></i>                                           '+
                    '            </a>                                                                       '+
                    '        </span>                                                                        '+
                    '    </p>                                                                               '
            ;
            $('.address-container').append(newRow);

            $('#address0').each(function(){
                $(this).editable(getAddressOptions($(this)));
            });
        });

        contactInfo.on('click', '.remove-email', function(e) {
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                emailPk = row.find('span.data-value a').attr('data-email-pk');

            if (emailPk != '0') {
                $.post(Routing.generate('email_ajax_remove'), {'pk' : emailPk }, function(data) {
                    if (data.success) {
                        row.remove();
                    }
                }, "json");
            } else {
                row.remove();
            }
        });

        contactInfo.on('click', '.add-email', function(e) {
            e.preventDefault();
            var tutorId = $(this).closest('.data-row').data('id'),
                newRow =
                    '    <p class="data-row" data-id="' + tutorId + '">                                               '+
                    '        <span class="data-name">New Email</span>                                                 '+
                    '        <span class="data-value">                                                                '+
                    '            <a href="#"  id="email0" class="inline-email"                                        '+
                    '                data-type="emailContact"                                                         '+
                    '                data-pk="' + tutorId + '"                                                        '+
                    '                data-email-pk="0"                                                                '+
                    '                data-url="' + Routing.generate('tutor_ajax_update') + '"                         '+
                    '                data-title="Enter Email"                                                         '+
                    '                data-value-type="' + ($('.inline-email').length > 0 ? 'other' : 'primary') + '"  '+
                    '            ></a>                                                                                '+
                    '        </span>                                                                                  '+
                    '        <span class="data-action">                                                               '+
                    '            <a href="#" data-pk="0" class="btn btn-danger btn-xs remove-email">                  '+
                    '                <i class="fa fa-remove"></i>                                                     '+
                    '            </a>                                                                                 '+
                    '        </span>                                                                                  '+
                    '    </p>                                                                                         '
                ;
            $('.email-container').append(newRow);

            $('#email0').each(function(){
                $(this).editable(getEmailOptions($(this)));
            });
        });

        contactInfo.on('click', '.remove-phone', function(e) {
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                phonePk = row.find('span.data-value a').attr('data-phone-pk');

            if (phonePk != '0') {
                $.post(Routing.generate('phone_ajax_remove'), {'pk' : phonePk }, function(data) {
                    if (data.success) {
                        row.remove();
                    }
                }, "json");
            } else {
                row.remove();
            }
        });

        contactInfo.on('click', '.add-phone', function(e) {
            e.preventDefault();
            var tutorId = $(this).closest('.data-row').data('id'),
                newRow =
                    '    <p class="data-row" data-id="' + tutorId + '">                                        '+
                    '        <span class="data-name">New Phone</span>                                          '+
                    '        <span class="data-value">                                                         '+
                    '            <a href="#"  id="phone0" class="inline-phone"                                 '+
                    '                data-type="phone"                                                         '+
                    '                data-pk="' + tutorId + '"                                                 '+
                    '                data-phone-pk="0"                                                         '+
                    '                data-url="' + Routing.generate('tutor_ajax_update') + '"                  '+
                    '                data-title="Enter Phone"                                                  '+
                    '            ></a>                                                                         '+
                    '        </span>                                                                           '+
                    '        <span class="data-action">                                                        '+
                    '            <a href="#" data-pk="0" class="btn btn-danger btn-xs remove-phone">           '+
                    '                <i class="fa fa-remove"></i>                                              '+
                    '            </a>                                                                          '+
                    '        </span>                                                                           '+
                    '    </p>                                                                                  '
                ;
            $('.phone-container').append(newRow);

            $('#phone0').each(function(){
                $(this).editable(getPhoneOptions($(this)));
            });
        });

    }

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
            notesContainer.append(newRow);

            $('#note0').each(function(){
                $(this).editable(getNoteOptions($(this)));
            });
        })
    }

    /**
     * Handler for interacting with Files, and dropzone initialisation
     */
    function setupFiles(filesContainer) {
        var tutorDropzone = new Dropzone("#file_upload");
        tutorDropzone.on("success", function(file, response) {
            $('#files-container').append(response.fileRow);

            var regex = /.*data-pk="(\d+)".*/gi,
                match = regex.exec(response.fileRow),
                id = match[1]
                ;
            $('#fileType' + id).editable();
        });

        filesContainer.on('click', '.remove-file', function(e){
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                filePk = row.data('id')
                ;
            $.post(Routing.generate('file_ajax_remove'), {'pk' : filePk}, function(data) {
                if (data.success) {
                    row.remove();
                } else {
                    console.log(data);
                }
            }, "json");
        });
    }

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
     * Get x-editable options for a given element, that is going to be made an x-editable Address (custom type)
     *
     * @param host
     * @returns {{value: {type: *, streetPrimary: *, streetSecondary: *, city: *, state: *, zip: *, country: *}, params: Function, success: Function, sourceCountry: Array}}
     */
    function getAddressOptions(host) {
        return {
            value: {
                type: host.data('valueType'),
                streetPrimary: host.data('valueStreetPrimary'),
                streetSecondary: host.data('valueStreetSecondary'),
                city: host.data('valueCity'),
                state: host.data('valueState'),
                zip: host.data('valueZip'),
                country: host.data('valueCountry')
            },
            params: function(params) {
                params.addressPk = host.attr('data-address-pk');
                return params;
            },
            success: function(response, newValue) {
                host.closest('.data-row').find('.data-name').text('Address (' + newValue.type +')');
                host.attr('data-address-pk', response.id);
                host.attr( "id", "Address" + response.id);
            },
            sourceCountry: addressCountryData
        }
    }

    /**
     * Get x-editable options for a given element, that is going to be made an x-editable Email (custom type)
     *
     * @param host
     * @returns {{value: {type: *, address: *}, params: Function, success: Function}}
     */
    function getEmailOptions(host) {
        return {
            value: {
                type: host.data('valueType'),
                address: host.data('valueAddress')
            },
            params: function(params) {
                params.emailPk = host.attr('data-email-pk');
                return params;
            },
            success: function(response, newValue) {
                host.closest('.data-row').find('.data-name').text('Email (' + newValue.type +')');
                host.attr('data-email-pk', response.id);
                host.attr( "id", "Email" + response.id);
            }
        }
    }

    /**
     * Get x-editable options for a given element, that is going to be made an x-editable Email (custom type)
     *
     * @param host
     * @returns {{value: {type: *, number: *, country: *}, params: Function, success: Function, sourceCountry: Array}}
     */
    function getPhoneOptions(host) {
        return {
            value: {
                type: host.data('valueType'),
                number: host.data('valueNumber'),
                country: host.data('valueCountry')
            },
            params: function(params) {
                params.phonePk = host.attr('data-phone-pk');
                return params;
            },
            success: function(response, newValue) {
                host.closest('.data-row').find('.data-name').text('Phone (' + newValue.type +')');
                host.attr('data-phone-pk', response.id);
                host.attr( "id", "Phone" + response.id);
            },
            sourceCountry: phoneCountryData
        }
    }

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
            sourceCountry: phoneCountryData
        }
    }
});