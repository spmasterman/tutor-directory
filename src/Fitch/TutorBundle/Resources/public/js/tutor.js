// Stop Dropzone doing its automatic thing
Dropzone.autoDiscover = false;

jQuery(document).ready(function() {
    "use strict";

    var tutorId = $('#tutorId').data('tutorPk'),
        tutorProfileCoordinator
    ;

    $.getJSON(Routing.generate('profile_dynamic_data', { 'tutorId' : tutorId}), { }, function(data) {
        tutorProfileCoordinator = new TutorProfileCoordinator(tutorId, data)



        console.log(tutorProfileCoordinator);
    });
});