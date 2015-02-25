jQuery(document).ready(function() {
    "use strict";

    // If the user is readonly, they still need to be able to see the history - so this cant be done in the tutor
    // modules (which aren't loaded)
    $('.rates-container').on('click', 'span.data-history', function() {
        console.log('splonk');
        $('.modal-body').html($(this).find('.data-history-content').html());
    });
});