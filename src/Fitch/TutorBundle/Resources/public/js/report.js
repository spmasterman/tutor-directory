$(document).ready( function () {

    var tableContainer = $('#report');

    // Create the table
    var table = tableContainer.DataTable({
        dom: 'T<"clear">lfrtip',
        tableTools: {
            "sSwfPath": "/bundles/fitchfrontend/swf/copy_csv_xls_pdf.swf",
            "aButtons": [
                {
                    "sExtends": "copy",
                    "sButtonText": "Copy to clipboard",
                    "sButtonClass": "btn btn-xs btn-default"
                },
                {
                    "sExtends": "csv",
                    "sButtonText": "Save to CSV",
                    "sButtonClass": "btn btn-xs btn-default"
                },
                {
                    "sExtends": "print",
                    "sInfo": "Please press escape when done",
                    "sButtonClass": "btn btn-xs btn-default"
                },
                {
                    "sExtends": "pdf",
                    "sButtonText": "Save as PDF",
                    "sButtonClass": "btn btn-xs btn-default"
                },
                {
                    "sExtends": "xls",
                    "sButtonText": "Open in Excel",
                    "sButtonClass": "btn btn-xs btn-default"
                }
            ]
        }
    });
});