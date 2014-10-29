jQuery(document).ready(function() {
    "use strict";

    $('.inline').editable();
    $('.inline-address').each(function(){
        console.log($(this));
       $(this).editable(getAddressOptions($(this)));
    });

    setupContactInfo($('.contact-info'));

    function setupContactInfo(contactInfo) {
        // Set underscore to mustache compatible handlebars {{ yummy }}
        //_.templateSettings = {
        //    interpolate: /\{\{(.+?)\}\}/g
        //};

        contactInfo.on('click', '.remove-address', function (e) {
            e.preventDefault();
            // ajax remove - onSuccess(
            $(this).closest('.data-row').remove();
        });

        contactInfo.on('click', '.add-address', function (e) {
            e.preventDefault();
            var tutorId = $(this).closest('.data-row').data('id'),
                newRow = "<p class='data-row' data-id='" + tutorId + "'>" +
                "<span class='data-name'>New Address</span>"+
                "<span class='data-value'>"+
                    "<a href='#'  id='address0' class='inline-address'"+
                    "data-type='address'"+
                    "data-pk='" + tutorId + "'" +
                    "data-address-pk='0'" +
                    "data-url='" + Routing.generate('tutor_ajax_update') + "'"+
                    "data-title='Enter Address'"+
                    "></a>"+
                "</span>"+
                "<span class='data-action'>"+
                    "<a href='#' data-pk=0 class='btn btn-danger btn-xs remove-address'>"+
                        "<i class='fa fa-remove'></i>"+
                    "Remove</a>"+
                "</span>"+
            "</p>";

            contactInfo.append(newRow);
            $('#address0').editable(getAddressOptions($(this)));
        });
    }

    function getAddressOptions(addressHost) {
        return {
            value: {
                type: addressHost.data('valueType'),
                    streetPrimary: addressHost.data('valuePrimaryStreet'),
                    streetSecondary: addressHost.data('valueSecondaryStreet'),
                    city: addressHost.data('valueCity'),
                    state: addressHost.data('valueState'),
                    zip: addressHost.data('valueZip')
            },
            params: function(params) {
                params.addressPk = addressHost.data('addressPk');
                return params;
            },
            success: function(response, newValue) {
                addressHost.closest('.data-row').find('.data-name').text('Address (' + newValue.type +')');
                addressHost.attr( "id", "Address" + newValue.id);
            }
        }
    }



        //jQuery('#add-another-address').click(function() {
    //
    //    var addressListWrapper = jQuery('#tutor-address-list-wrapper');
    //    var addressCount = 0;
    //
    //    // find a number that's not used - when deleting we can consume then remove numbers
    //    var found = true;
    //    var inputId = '';
    //    while (found) {
    //        // be nicer if I could pull this put the prototype to make it a bit more generic, but...
    //        inputId = '#fitch_cronbundle_crontask_commands_' + addressCount.toString() + '_commandLine';
    //        if (jQuery(inputId).length > 0) {
    //            addressCount++
    //        } else {
    //            found = false;
    //        }
    //    }
    //
    //    var newWidget = addressListWrapper.attr('data-prototype');
    //
    //    // replace the "__name__" used in the id and name of the prototype with a number
    //    newWidget = newWidget.replace(/__name__/g, addressCount);
    //
    //    // create a new list element and add it to the list
    //    var newItem = jQuery('<div class="col-xs-11"></div>').html(newWidget);
    //    var newRemoveBtn = jQuery('<div class="col-xs-1"><a href="#" class="btn btn-danger btn-xs remove-command">Remove</a></div>');
    //    var newRow = jQuery('<div class="row"></div>');
    //    newItem.appendTo(newRow);
    //    newRemoveBtn.appendTo(newRow);
    //    newRow.appendTo(jQuery('#tutor-address-list'));
    //
    //    return false;
    //});
    //
    //jQuery(document).on('click', '.remove-command',function(e) {
    //    e.preventDefault();
    //    jQuery(this).parent().parent().remove();
    //});
});