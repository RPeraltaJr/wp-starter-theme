// * NOTE: remember to include in gulpfile.js in paths.scripts.inputs order to compile
// * import modules as needed for page
//  import { InputMask } from './modules/form';

$(document).ready( function () {

    $.fn.dataTable.ext.search.push(
        function (settings, data, dataIndex) {
            var min = $('#min').datepicker("getDate");
            var max = $('#max').datepicker("getDate");
            var startDate = new Date(data[1]); // 1 is the order of the timestamp column
            if (min == null && max == null) { return true; }
            if (min == null && startDate <= max) { return true;}
            if (max == null && startDate >= min) {return true;}
            if (startDate <= max && startDate >= min) { return true; }
            return false;
        }
    );
    
    $("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
    $("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });

    let submissionsTable = $('#submissions_table');
    let table = submissionsTable.DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // Event listener to the two range filtering inputs to redraw on input
    $('#min, #max').change(function () {
        table.draw();
    });

});
