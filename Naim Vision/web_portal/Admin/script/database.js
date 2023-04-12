$(document).ready(function(){
  var dataTable = $('#db_table').DataTable({
    "buttons": [
      'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "processing": true
  });

  $('#column_name_database').selectpicker();

  $('#column_name_database').change(function(){

    var all_column = ["0", "1", "2", "3", "4", "5", "6", "7", "8"];

    var remove_column = $('#column_name_database').val();

    if($('#column_name_database').val() == null){
      dataTable.columns(all_column).visible(true);
      sessionStorage.removeItem("database_table_hidden_columns"); // Remove session storage when all columns are visible
    } else{
      var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

      dataTable.columns(remove_column).visible(false);

      dataTable.columns(remaining_column).visible(true);

      sessionStorage.setItem("database_table_hidden_columns", JSON.stringify(remove_column)); // Set session storage with hidden columns
    }

    $('#column_name_database').val(remove_column); // set the selected options of the selectpicker
	$('#column_name_database').selectpicker('refresh'); // refresh the selectpicker to update the UI

  });

  // Check if there are any hidden columns in session storage and hide them on page load
  var hiddenColumns = JSON.parse(sessionStorage.getItem("database_table_hidden_columns"));
  if(hiddenColumns != null){
    var remaining_column = ["0", "1", "2", "3", "4", "5", "6", "7", "8"].filter(function(obj) { return hiddenColumns.indexOf(obj) == -1; });
    dataTable.columns(hiddenColumns).visible(false);
    dataTable.columns(remaining_column).visible(true);
    $('#column_name_database').val(hiddenColumns); // set the selected options of the selectpicker
	$('#column_name_database').selectpicker('refresh'); // refresh the selectpicker to update the UI
  }

  dataTable.buttons().container().appendTo('.table-responsive');

});	