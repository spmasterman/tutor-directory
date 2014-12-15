// Stop Dropzone doing its automatic thing
Dropzone.autoDiscover = false;

jQuery(document).ready(function() {
    "use strict";

    // Module wide variables
    var countryData = [],
        competencyTypes = [],
        competencyLevels = [],
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
        countryData = data;

        $('.inline-address').each(function() {
            $(this).editable(getAddressOptions($(this)));
        });
        $('.inline-phone').each(function() {
            $(this).editable(getPhoneOptions($(this)));
        });
        $('.inline-rate').each(function() {
            $(this).editable(getRateOptions($(this)));
        });
    });

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
    $('.inline-email').each(function() {
        $(this).editable(getEmailOptions($(this)));
    });

    // Setup DOM event handlers
    setupContactInfo($('.contact-info'));
    setupBio($('.bio'));
    setupRates($('.rates-container'));
    setupNotes($('.notes-container'));
    setupFiles($('#files-container'));
    setupAvatar($('#avatar-container'));
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
                    ' <div class="data-row" data-id="' + tutorId + '" data-competency-pk="0">          '+
                    '    <span class="data-name">                                                      '+
                    '        <span class="data-tag">&nbsp;</span>                                      '+
                    '       <a href="#" id="competency-level0" class="inline-competency-level"         '+
                    '          data-type="typeaheadjs"                                                 '+
                    '          data-pk="' + tutorId + '"                                               '+
                    '          data-competency-pk="0"                                                  '+
                    '          data-url="' + Routing.generate('competency_ajax_update')+'"             '+
                    '          data-title="Level of Competency"                                        '+
                    '       ></a>                                                                      '+
                    '    </span>                                                                       '+
                    '    <span class="data-value">                                                     '+
                    '       <a href="#" id="competency-type0" class="inline-competency-type"           '+
                    '          data-type="typeaheadjs"                                                 '+
                    '          data-pk="' + tutorId + '"                                               '+
                    '          data-competency-pk="0"                                                  '+
                    '          data-url="' + Routing.generate('competency_ajax_update')+'"             '+
                    '          data-title="Competency"                                                 '+
                    '        ></a>                                                                     '+
                    '    </span>                                                                       '+
                    '    <span class="data-action">                                                    '+
                    '        <a href="#" data-pk="0" class="btn btn-danger btn-xs remove-competency">  '+
                    '           <i class="fa fa-remove"></i>                                           '+
                    '        </a>                                                                      '+
                    '    </span>                                                                       '+
                    '    <span class="data-note">                                                      '+
                    '       <a href="#" id="competency-note0" class="inline-competency-note"           '+
                    '          data-type="textarea"                                                    '+
                    '          data-pk="' + tutorId + '"                                               '+
                    '          data-competency-pk="0"                                                  '+
                    '          data-url="' + Routing.generate('competency_ajax_update')+'"             '+
                    '          data-title="Note"                                                       '+
                    '       ></a>                                                                      '+
                    '    </span>                                                                       '+
                    ' </div>                                                                           '
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
            sourceCountry: countryData
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
     * @returns {{value: {type: *, number: *, country: *, isPreferred: *}, params: Function, success: Function, sourceCountry: Array}}
     */
    function getPhoneOptions(host) {
        return {
            value: {
                type: host.data('valueType'),
                number: host.data('valueNumber'),
                country: host.data('valueCountry'),
                isPreferred: host.data('valueIsPreferred')
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
            sourceCountry: countryData
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