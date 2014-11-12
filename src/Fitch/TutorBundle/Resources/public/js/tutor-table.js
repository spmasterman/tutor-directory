$(document).ready( function () {
    $('#tutor_table').DataTable({
        "ajax": Routing.generate('all_tutors'),
        "columns": [
            { "data": "fullname" },
            { "data": "ttype" },
            { "data": "region" },
            { "data": "status" },
            { "data": "competency" },
            { "data": "competency_search" }
        ],
        "columnDefs": [
            {
                "targets": [ 5 ],
                "visible": false,
                "searchable": true
            }
        ],
        dom: "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        renderer: {
            "header": "bootstrap",
            "pageButton": "bootstrap"
        },
        "pagingType": "simple_numbers"
    });
} );