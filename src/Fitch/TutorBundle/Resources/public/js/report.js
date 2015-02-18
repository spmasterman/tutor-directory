$(document).ready( function () {

    var tableContainer = $('#report');
    var tableHolder = $('#report-holder');

    // before we initialise the table, set the th and td to no-wrap - this forces the
    // table to its required width with each column as wide as it needs to be
    tableHolder.addClass('nowrap');

    // Create the table
    var table = tableContainer.DataTable({
        "dom": 'RlfrJtip',
        "scrollX": true
    });

    // The remove the nowrap - so that the table columns can be manually resized, without the text
    // smooshing together
    tableHolder.removeClass('nowrap');


    tableHolder.on('click', '.btn-focus', function(e){
        $( $.fn.dataTable.tables( true ) ).DataTable().columns.adjust();
    });
});