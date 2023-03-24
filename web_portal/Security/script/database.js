$(document).ready(function(){
	
	var dataTable = $('#db_table').DataTable({
		"buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
	});

	$('#column_name').selectpicker();

	$('#column_name').change(function(){

	var all_column = ["0", "1", "2", "3", "4", "5", "6"];

	var remove_column = $('#column_name').val();

	var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

	dataTable.columns(remove_column).visible(false);

	dataTable.columns(remaining_column).visible(true);
	});

	dataTable.buttons().container().appendTo('.table-responsive');

});	