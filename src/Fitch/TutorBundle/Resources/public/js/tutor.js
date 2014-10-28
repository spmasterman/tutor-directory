    jQuery(document).ready(function() {
        jQuery('#add-another-address').click(function() {

            var addressListWrapper = jQuery('#tutor-address-list-wrapper');
            var addressCount = 0;

            // find a number that's not used - when deleting we can consume then remove numbers
            var found = true;
            var inputId = '';
            while (found) {
                // be nicer if I could pull this put the prototype to make it a bit more generic, but...
                inputId = '#fitch_cronbundle_crontask_commands_' + addressCount.toString() + '_commandLine';
                if (jQuery(inputId).length > 0) {
                    addressCount++
                } else {
                    found = false;
                }
            }

            var newWidget = addressListWrapper.attr('data-prototype');

            // replace the "__name__" used in the id and name of the prototype with a number
            newWidget = newWidget.replace(/__name__/g, addressCount);

            // create a new list element and add it to the list
            var newItem = jQuery('<div class="col-xs-11"></div>').html(newWidget);
            var newRemoveBtn = jQuery('<div class="col-xs-1"><a href="#" class="btn btn-danger btn-xs remove-command">Remove</a></div>');
            var newRow = jQuery('<div class="row"></div>');
            newItem.appendTo(newRow);
            newRemoveBtn.appendTo(newRow);
            newRow.appendTo(jQuery('#tutor-address-list'));

            return false;
        });

        jQuery(document).on('click', '.remove-command',function(e) {
            e.preventDefault();
            jQuery(this).parent().parent().remove();
        });
    });