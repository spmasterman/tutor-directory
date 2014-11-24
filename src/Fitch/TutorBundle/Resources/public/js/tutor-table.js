$(document).ready( function () {

    var tableContainer = $('#tutor_table');

    // Setup - add a text input to each footer cell
    tableContainer.find('tfoot th').each( function () {
        var title = tableContainer.find('thead th').eq($(this).index()).text();
        if (title != ''){
            $(this).html( '<input type="text" class="form-control input-sm" placeholder="Search '+title+'" />' );
        }
    } );

    // Create the table
    var table = tableContainer.DataTable({
        ajax: Routing.generate('all_tutors'),
        columns: [
            {
                class: 'details-control',
                orderable: false,
                data: null,
                defaultContent: '',
                width: "4%"
            },
            { data: "fullname", width: "24%" },
            { data: "ttype", width: "24%" },
            { data: "region", width: "24%" },
            { data: "status"},
            { data: "competency_details" },
            { data: "search_dump" }
        ],
        columnDefs: [
            {
                targets: [1],
                /**
                 * @param data
                 * @param type
                 * @param {{fullname: string}} full
                 * @returns {string}
                 */
                render: function(data, type, full/*, meta*/) {
                    return '<a href="' + Routing.generate('tutor_profile',{id: full.id}) + '">'+full.fullname+'</a>';
                }
            },
            {
                targets: [5, 6],
                visible: false,
                searchable: true
            }
        ],
        order: [[1, 'asc']],
        dom: "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        renderer: {
            header: "bootstrap",
            pageButton: "bootstrap"
        },
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        pagingType: "simple_numbers",
        drawCallback: function() {
            applyHighlight(tableContainer)
        }
    });

    // Add handlers to search the table on a column
    table.columns().flatten().each(function(colIdx) {
        $('input', table.column(colIdx).footer()).on('keyup change', function() {
            table
                .column(colIdx)
                .search(this.value)
                .draw();
        });
    });

    // Add event listener for opening and closing details
    tableContainer.find('tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
            applyHighlight(row.child());
        }
    });

    // Add handler for opening closing ALL rows
    tableContainer.on('click', '#open-all', function(){
        if ($(this).hasClass('fa-plus-square')) {
            $(this).removeClass('fa-plus-square');
            $(this).addClass('fa-minus-square');
            tableContainer.find('tr').each(function(){
                if (!$(this).hasClass('shown')) {
                    $(this).find('td.details-control').trigger('click');
                }
            });
        } else {
            $(this).removeClass('fa-minus-square');
            $(this).addClass('fa-plus-square');
            tableContainer.find('tr').each(function(){
                if ($(this).hasClass('shown')) {
                    $(this).find('td.details-control').trigger('click');
                }
            });
        }
    });

    function applyHighlight(selector) {
        var filter = $('.dataTables_filter input');
        selector.unhighlight();
        if (filter.val() != "") {
            selector.highlight(filter.val().split(" "));
        }
    }

    /**
     * Formatting function for sub-row
     *
     * @param {{competency_details: string|null}} data
     * @returns {string}
     */
    function format (data) {
        if (data.competency_details != null) {
            var subTable = '<table class="table sub-table">'
                + '<tr><th>Skill</th><th>Level</th><th>Notes</th></tr>'
                ,
                rows = data.competency_details.split(' ~ '),
                fields,
                rowIndex,
                fieldIndex;

            for (rowIndex = 0; rowIndex < rows.length; ++rowIndex) {
                subTable += '<tr>';
                fields = rows[rowIndex].split('|');
                for (fieldIndex = 0; fieldIndex < fields.length; ++fieldIndex) {
                    subTable += '<td>' + fields[fieldIndex] + '</td>'
                }
                subTable += '</tr>';
            }
            subTable += '</table>';
            return subTable;
        } else {
            return "<p>There are no skills setup for this trainer</p>"
        }
    }
} );