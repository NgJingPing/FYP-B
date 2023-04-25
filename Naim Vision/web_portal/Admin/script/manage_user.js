$(document).ready(function(){

  var dataTable = $('#user_table').DataTable({
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

  $('#column_name_user').selectpicker();

  $('#column_name_user').change(function(){

    var all_column = ["0", "1", "2", "3"];

    var remove_column = $('#column_name_user').val();

    if($('#column_name_user').val() == null){
      dataTable.columns(all_column).visible(true);
      sessionStorage.removeItem("user_table_hidden_columns"); // Remove session storage when all columns are visible
    } else{
      var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

      dataTable.columns(remove_column).visible(false);

      dataTable.columns(remaining_column).visible(true);

      sessionStorage.setItem("user_table_hidden_columns", JSON.stringify(remove_column)); // Set session storage with hidden columns
    }

    $('#column_name_user').val(remove_column); // set the selected options of the selectpicker
	$('#column_name_user').selectpicker('refresh'); // refresh the selectpicker to update the UI

  });

  // Check if there are any hidden columns in session storage and hide them on page load
  var hiddenColumns = JSON.parse(sessionStorage.getItem("user_table_hidden_columns"));
  if(hiddenColumns != null){
    var remaining_column = ["0", "1", "2", "3"].filter(function(obj) { return hiddenColumns.indexOf(obj) == -1; });
    dataTable.columns(hiddenColumns).visible(false);
    dataTable.columns(remaining_column).visible(true);
    $('#column_name_user').val(hiddenColumns); // set the selected options of the selectpicker
	$('#column_name_user').selectpicker('refresh'); // refresh the selectpicker to update the UI
  }

  dataTable.buttons().container().appendTo('.user');


});	