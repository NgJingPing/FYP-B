$(document).ready(function(){

  var dataTable = $('#entry_table').DataTable({
      "buttons": [
          {
              extend: 'copy',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'csv',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'excel',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'pdf',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'print',
              exportOptions: {
                  columns: ':visible'
              }
          }
      ],
    "processing": true
  });

  $('#column_name_entry').selectpicker();

  $('#column_name_entry').change(function(){

    var all_column = ["0", "1", "2", "3", "4"];

    var remove_column = $('#column_name_entry').val();

    if($('#column_name_entry').val() == null){
      dataTable.columns(all_column).visible(true);
      sessionStorage.removeItem("entry_table_hidden_columns"); // Remove session storage when all columns are visible
    } else{
      var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

      dataTable.columns(remove_column).visible(false);

      dataTable.columns(remaining_column).visible(true);

      sessionStorage.setItem("entry_table_hidden_columns", JSON.stringify(remove_column)); // Set session storage with hidden columns
    }

    $('#column_name_entry').val(remove_column); // set the selected options of the selectpicker
	$('#column_name_entry').selectpicker('refresh'); // refresh the selectpicker to update the UI

  });

  // Check if there are any hidden columns in session storage and hide them on page load
  var hiddenColumns = JSON.parse(sessionStorage.getItem("entry_table_hidden_columns"));
  if(hiddenColumns != null){
    var remaining_column = ["0", "1", "2", "3", "4"].filter(function(obj) { return hiddenColumns.indexOf(obj) == -1; });
    dataTable.columns(hiddenColumns).visible(false);
    dataTable.columns(remaining_column).visible(true);
    $('#column_name_entry').val(hiddenColumns); // set the selected options of the selectpicker
	$('#column_name_entry').selectpicker('refresh'); // refresh the selectpicker to update the UI
  }

  dataTable.buttons().container().appendTo('.entry');

  var dataTable2 = $('#exit_table').DataTable({
      "buttons": [
          {
              extend: 'copy',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'csv',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'excel',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'pdf',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'print',
              exportOptions: {
                  columns: ':visible'
              }
          }
      ],
    "processing": true
  });

  $('#column_name_exit').selectpicker();

  $('#column_name_exit').change(function(){

    var all_column = ["0", "1", "2", "3", "4"];

    var remove_column = $('#column_name_exit').val();

    if($('#column_name_exit').val() == null){
      dataTable2.columns(all_column).visible(true);
      sessionStorage.removeItem("exit_table_hidden_columns"); // Remove session storage when all columns are visible
    } else{
      var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

      dataTable2.columns(remove_column).visible(false);

      dataTable2.columns(remaining_column).visible(true);

      sessionStorage.setItem("exit_table_hidden_columns", JSON.stringify(remove_column)); // Set session storage with hidden columns
    }

    $('#column_name_exit').val(remove_column); // set the selected options of the selectpicker
	$('#column_name_exit').selectpicker('refresh'); // refresh the selectpicker to update the UI

  });

  // Check if there are any hidden columns in session storage and hide them on page load
  var hiddenColumns2 = JSON.parse(sessionStorage.getItem("exit_table_hidden_columns"));
  if(hiddenColumns2 != null){
    var remaining_column = ["0", "1", "2", "3", "4"].filter(function(obj) { return hiddenColumns2.indexOf(obj) == -1; });
    dataTable2.columns(hiddenColumns2).visible(false);
    dataTable2.columns(remaining_column).visible(true);
    $('#column_name_exit').val(hiddenColumns2); // set the selected options of the selectpicker
	$('#column_name_exit').selectpicker('refresh'); // refresh the selectpicker to update the UI
  }

  dataTable2.buttons().container().appendTo('.exit');

  var dataTable3 = $('#denied_table').DataTable({
      "buttons": [
          {
              extend: 'copy',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'csv',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'excel',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'pdf',
              exportOptions: {
                  columns: ':visible'
              }
          },
          {
              extend: 'print',
              exportOptions: {
                  columns: ':visible'
              }
          }
      ],
    "processing": true
  });

  $('#column_name_denied').selectpicker();

  $('#column_name_denied').change(function(){

    var all_column = ["0", "1", "2", "3"];

    var remove_column = $('#column_name_denied').val();

    if($('#column_name_denied').val() == null){
      dataTable3.columns(all_column).visible(true);
      sessionStorage.removeItem("denied_table_hidden_columns"); // Remove session storage when all columns are visible
    } else{
      var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

      dataTable3.columns(remove_column).visible(false);

      dataTable3.columns(remaining_column).visible(true);

      sessionStorage.setItem("denied_table_hidden_columns", JSON.stringify(remove_column)); // Set session storage with hidden columns
    }

    $('#column_name_denied').val(remove_column); // set the selected options of the selectpicker
	$('#column_name_denied').selectpicker('refresh'); // refresh the selectpicker to update the UI

  });

  // Check if there are any hidden columns in session storage and hide them on page load
  var hiddenColumns3 = JSON.parse(sessionStorage.getItem("denied_table_hidden_columns"));
  if(hiddenColumns3 != null){
    var remaining_column = ["0", "1", "2", "3"].filter(function(obj) { return hiddenColumns3.indexOf(obj) == -1; });
    dataTable3.columns(hiddenColumns3).visible(false);
    dataTable3.columns(remaining_column).visible(true);
    $('#column_name_denied').val(hiddenColumns3); // set the selected options of the selectpicker
	$('#column_name_denied').selectpicker('refresh'); // refresh the selectpicker to update the UI
  }

  dataTable3.buttons().container().appendTo('.denied');
});