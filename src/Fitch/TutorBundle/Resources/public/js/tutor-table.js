$(document).ready( function () {

    // Setup - add a text input to each footer cell
    $('#tutor_table tfoot th').each( function () {
        var title = $('#tutor_table thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" class="form-control input-sm" placeholder="Search '+title+'" />' );
    } );

    var table = $('#tutor_table').DataTable({
        "ajax": Routing.generate('all_tutors'),
        "columns": [
            { "data": "fullname", "width": "15%" },
            { "data": "ttype", "width": "15%" },
            { "data": "region", "width": "15%" },
            { "data": "status", "width": "15%" },
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

    // Add handlers to search the table on a column
    table.columns().eq(0).each(function(colIdx) {
        $('input', table.column(colIdx).footer()).on('keyup change', function() {
            table
                .column(colIdx)
                .search(this.value)
                .draw();
        } );
    } );
} );