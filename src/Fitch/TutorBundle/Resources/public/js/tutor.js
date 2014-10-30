jQuery(document).ready(function() {
    "use strict";

    var addressCountryData = [],
        phoneCountryData =[]
        ;

    $.getJSON(Routing.generate('all_countries'), {}, function(data) {
        // Build data for County selects in custom types (Phone and Address) - same data but slightly different display
        // format. Only initialise the x-editable elements once the data has been retrieved

        $(data).each(function(i, k){
            addressCountryData.push(k);
            phoneCountryData.push({text: k['text'] + ' ('+ k['dialingCode']+')', value: k['value']});
        });

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
        // These must all be instantiated passing the host in, so that the value etc can be set
        $('.inline-address').each(function() {
            $(this).editable(getAddressOptions($(this)));
        });
        $('.inline-email').each(function() {
            $(this).editable(getEmailOptions($(this)));
        });
        $('.inline-phone').each(function() {
            $(this).editable(getPhoneOptions($(this)));
        });
    });

    // Setup DOM event handlers
    setupContactInfo($('.contact-info'));
    setupBio($('.bio'));

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
                newRow = "<p class='data-row' data-id='" + tutorId + "'> " +
                "<span class='data-name'>New Address</span> "+
                "<span class='data-value'>"+
                    "<a href='#'  id='address0' class='inline-address' "+
                    "data-type='address' "+
                    "data-pk='" + tutorId + "' " +
                    "data-address-pk='0' " +
                    "data-url='" + Routing.generate('tutor_ajax_update') + "' "+
                    "data-title='Enter Address' "+
                    "></a>"+
                "</span> "+
                "<span class='data-action'>"+
                    "<a href='#' data-pk=0 class='btn btn-danger btn-xs remove-address'>"+
                        "<i class='fa fa-remove'></i>"+
                    "</a>"+
                "</span> "+
            "</p>";

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
                newRow = "<p class='data-row' data-id='" + tutorId + "'> " +
                    "<span class='data-name'>New Email</span> "+
                    "<span class='data-value'>"+
                    "<a href='#'  id='email0' class='inline-email' "+
                    "data-type='emailContact' "+
                    "data-pk='" + tutorId + "' " +
                    "data-email-pk='0' " +
                    "data-url='" + Routing.generate('tutor_ajax_update') + "' "+
                    "data-title='Enter Email' "+
                    "data-value-type='" + ($('.inline-email').length > 0 ? 'other' : 'primary') + "' "+
                    "></a>"+
                    "</span> "+
                    "<span class='data-action'>"+
                    "<a href='#' data-pk=0 class='btn btn-danger btn-xs remove-email'>"+
                    "<i class='fa fa-remove'></i>"+
                    "</a>"+
                    "</span> "+
                    "</p>";

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
                newRow = "<p class='data-row' data-id='" + tutorId + "'> " +
                    "<span class='data-name'>New Phone</span> "+
                    "<span class='data-value'>"+
                    "<a href='#'  id='phone0' class='inline-phone' "+
                    "data-type='phone' "+
                    "data-pk='" + tutorId + "' " +
                    "data-phone-pk='0' " +
                    "data-url='" + Routing.generate('tutor_ajax_update') + "' "+
                    "data-title='Enter Phone' "+
                    "></a>"+
                    "</span> "+
                    "<span class='data-action'>"+
                    "<a href='#' data-pk=0 class='btn btn-danger btn-xs remove-phone'>"+
                    "<i class='fa fa-remove'></i>"+
                    "</a>"+
                    "</span> "+
                    "</p>";

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
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']]
                ]
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
});