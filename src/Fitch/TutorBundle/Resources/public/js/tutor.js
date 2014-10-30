jQuery(document).ready(function() {
    "use strict";

    var countryData = [];

    $.getJSON(Routing.generate('all_countries'), {}, function(data) {
        countryData = data;

        $('.inline').editable();
        $('.inline-address').each(function() {
            $(this).editable(getAddressOptions($(this)));
        });
        $('.inline-email').each(function() {
            $(this).editable(getEmailOptions($(this)));
        });
    });

    setupContactInfo($('.contact-info'));

    $('.bio').on('click', '.edit-bio', function(e){
        e.preventDefault();
        $('#bio').summernote({
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
            ]
        });
        $('.bio').find('.save-bio').show();
        $(this).hide();
    });

    $('.bio').on('click', '.save-bio', function(e){
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
    }

    function getAddressOptions(addressHost) {
        return {
            value: {
                type: addressHost.data('valueType'),
                streetPrimary: addressHost.data('valueStreetPrimary'),
                streetSecondary: addressHost.data('valueStreetSecondary'),
                city: addressHost.data('valueCity'),
                state: addressHost.data('valueState'),
                zip: addressHost.data('valueZip'),
                country: addressHost.data('valueCountry')
            },
            params: function(params) {
                params.addressPk = addressHost.attr('data-address-pk');
                return params;
            },
            success: function(response, newValue) {
                addressHost.closest('.data-row').find('.data-name').text('Address (' + newValue.type +')');
                addressHost.attr('data-address-pk', response.id);
                addressHost.attr( "id", "Address" + response.id);
            },
            sourceCountry: countryData
        }
    }

    function getEmailOptions(emailHost) {
        return {
            value: {
                type: emailHost.data('valueType'),
                address: emailHost.data('valueAddress')
            },
            params: function(params) {
                params.emailPk = emailHost.attr('data-email-pk');
                return params;
            },
            success: function(response, newValue) {
                emailHost.closest('.data-row').find('.data-name').text('Email (' + newValue.type +')');
                emailHost.attr('data-email-pk', response.id);
                emailHost.attr( "id", "Email" + response.id);
            }
        }
    }
});