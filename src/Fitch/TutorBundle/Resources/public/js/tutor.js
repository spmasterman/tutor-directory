// Stop Dropzone doing its automatic thing - do this in global scope, not in a document ready block
Dropzone.autoDiscover = false;

jQuery(document).ready(function() {
    "use strict";
    var tutorId = $('#tutorId').data('tutorPk');

    // Everything is managed by a TutorProfileCoordinator object that needs to be fed a bunch of stuff from the server
    // It parcels these bits of data out to other objects (Language, Competency etc) and they handle their own
    // little-bit-o-the page.
    $.getJSON(Routing.generate('profile_dynamic_data', { 'tutorId' : tutorId}), {}, function(data) {
        new TutorProfileCoordinator(tutorId, data)
    });
});