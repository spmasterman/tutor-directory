jQuery(document).ready(function() {
    "use strict";

    var countryData = [];

    $.getJSON(Routing.generate('all_countries'), {}, function(data) {
        countryData = data;

        $('.inline').editable();
        $('.inline-address').each(function() {
            $(this).editable(getAddressOptions($(this)));
        });
    });

    setupContactInfo($('.contact-info'));

    function setupContactInfo(contactInfo) {
        contactInfo.on('click', '.remove-address', function(e) {
            e.preventDefault();
            var row = $(this).closest('.data-row'),
                addressPk = row.find('span.data-value a').attr('data-address-pk');

            if (addressPk) {
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
                    "Remove</a>"+
                "</span> "+
            "</p>";

            $('.address-container').append(newRow);

            $('#address0').each(function(){
                $(this).editable(getAddressOptions($(this)));
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
                console.log(addressHost);
                addressHost.attr('data-address-pk', response.id);
                addressHost.attr( "id", "Address" + response.id);
            },
            sourceCountry: countryData
        }
    }
});