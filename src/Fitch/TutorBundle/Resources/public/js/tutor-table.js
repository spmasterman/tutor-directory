$(document).ready( function () {

    // Setup - add a text input to each footer cell
    $('#tutor_table tfoot th').each( function () {
        var title = $('#tutor_table thead th').eq( $(this).index() ).text();
        if (title != ''){
            $(this).html( '<input type="text" class="form-control input-sm" placeholder="Search '+title+'" />' );
        }
    } );

    var table = $('#tutor_table').DataTable({
        "ajax": Routing.generate('all_tutors'),
        "columns": [
            {
                "class":          'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": '',
                "width": "4%"
            },
            { "data": "fullname", "width": "24%" },
            { "data": "ttype", "width": "24%" },
            { "data": "region", "width": "24%" },
            { "data": "status" },
            { "data": "competency_details" }
        ],
        "columnDefs": [
            {
                "targets": [ 5 ],
                "visible": false,
                "searchable": true
            }
        ],
        "order": [[1, 'asc']],
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

    // Add event listener for opening and closing details
    $('#tutor_table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );

    /* Formatting function for sub-row */
    function format ( d ) {
        // `d` is the original data object for the row
        if (d.competency_details != null) {
            var subTable = '<table class="table" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'
                + '<tr><th>Competency</th><th>Level</th><th>Notes</th></tr>'
                ,
                rows = d.competency_details.split(' ~ '),
                fields,
                rowIndex,
                fieldIndex;

            for (rowIndex = 0; rowIndex < rows.length; ++rowIndex) {
                subTable += '<tr>';
                fields = rows[rowIndex].split('|');
                for (fieldsIndex = 0; fieldsIndex < fields.length; ++fieldsIndex) {
                    subTable += '<td>' + fields[fieldsIndex] + '</td>'
                }
                subTable += '</tr>';
            }
            subTable += '</table>';
            return subTable;
        } else {
            return "<p>There are no competencies setup for this tutor</p>"
        }
    }

} );