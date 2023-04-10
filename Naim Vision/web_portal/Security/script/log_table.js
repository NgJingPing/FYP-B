$(document).ready(function(){
	
	var dataTable = $('#entry_table').DataTable({
		"buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
	});

	$('#column_name_entry').selectpicker();

	$('#column_name_entry').change(function(){

	var all_column = ["0", "1", "2", "3", "4"];

	var remove_column = $('#column_name_entry').val();

	if($('#column_name_entry').val() == null){
		dataTable.columns(all_column).visible(true);
	} else{
		var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

		dataTable.columns(remove_column).visible(false);

		dataTable.columns(remaining_column).visible(true);
	}

	});

	var dataTable2 = $('#exit_table').DataTable({
		"buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
	});

	$('#column_name_exit').selectpicker();

	$('#column_name_exit').change(function(){

	var all_column = ["0", "1", "2", "3", "4"];

	var remove_column = $('#column_name_exit').val();

	if($('#column_name_exit').val() == null){
		dataTable2.columns(all_column).visible(true);
	} else{
		var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

		dataTable2.columns(remove_column).visible(false);

		dataTable2.columns(remaining_column).visible(true);
	}

	});

	var dataTable3 = $('#denied_table').DataTable({
		"buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
	});

	$('#column_name_denied').selectpicker();

	$('#column_name_denied').change(function(){

	var all_column = ["0", "1", "2", "3"];

	var remove_column = $('#column_name_denied').val();

	if($('#column_name_denied').val() == null){
		dataTable3.columns(all_column).visible(true);
	} else{
		var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

		dataTable3.columns(remove_column).visible(false);

		dataTable3.columns(remaining_column).visible(true);
	}

	});

	dataTable.buttons().container().appendTo('.entry');
	dataTable2.buttons().container().appendTo('.exit');
	dataTable3.buttons().container().appendTo('.denied');


});	