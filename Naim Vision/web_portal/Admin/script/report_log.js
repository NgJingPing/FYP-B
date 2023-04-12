$(document).ready(function(){

  var dataTable = $('#log_table').DataTable({
    "buttons": [
      'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "processing": true
  });

  $('#column_name_rentry').selectpicker();

  $('#column_name_rentry').change(function(){

    var all_column = ["0", "1", "2", "3", "4"];

    var remove_column = $('#column_name_rentry').val();

    if($('#column_name_rentry').val() == null){
      dataTable.columns(all_column).visible(true);
      sessionStorage.removeItem("rentry_table_hidden_columns"); // Remove session storage when all columns are visible
    } else{
      var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

      dataTable.columns(remove_column).visible(false);

      dataTable.columns(remaining_column).visible(true);

      sessionStorage.setItem("rentry_table_hidden_columns", JSON.stringify(remove_column)); // Set session storage with hidden columns
    }

    $('#column_name_rentry').val(remove_column); // set the selected options of the selectpicker
	$('#column_name_rentry').selectpicker('refresh'); // refresh the selectpicker to update the UI

  });

  // Check if there are any hidden columns in session storage and hide them on page load
  var hiddenColumns = JSON.parse(sessionStorage.getItem("rentry_table_hidden_columns"));
  if(hiddenColumns != null){
    var remaining_column = ["0", "1", "2", "3", "4"].filter(function(obj) { return hiddenColumns.indexOf(obj) == -1; });
    dataTable.columns(hiddenColumns).visible(false);
    dataTable.columns(remaining_column).visible(true);
    $('#column_name_rentry').val(hiddenColumns); // set the selected options of the selectpicker
	$('#column_name_rentry').selectpicker('refresh'); // refresh the selectpicker to update the UI
  }

  dataTable.buttons().container().appendTo('.entry');

  var dataTable2 = $('#log_table2').DataTable({
    "buttons": [
      'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "processing": true
  });

  $('#column_name_rexit').selectpicker();

  $('#column_name_rexit').change(function(){

    var all_column = ["0", "1", "2", "3", "4"];

    var remove_column = $('#column_name_rexit').val();

    if($('#column_name_rexit').val() == null){
      dataTable2.columns(all_column).visible(true);
      sessionStorage.removeItem("rexit_table_hidden_columns"); // Remove session storage when all columns are visible
    } else{
      var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

      dataTable2.columns(remove_column).visible(false);

      dataTable2.columns(remaining_column).visible(true);

      sessionStorage.setItem("rexit_table_hidden_columns", JSON.stringify(remove_column)); // Set session storage with hidden columns
    }

    $('#column_name_rexit').val(remove_column); // set the selected options of the selectpicker
	$('#column_name_rexit').selectpicker('refresh'); // refresh the selectpicker to update the UI

  });

  // Check if there are any hidden columns in session storage and hide them on page load
  var hiddenColumns2 = JSON.parse(sessionStorage.getItem("rexit_table_hidden_columns"));
  if(hiddenColumns2 != null){
    var remaining_column = ["0", "1", "2", "3", "4"].filter(function(obj) { return hiddenColumns2.indexOf(obj) == -1; });
    dataTable2.columns(hiddenColumns2).visible(false);
    dataTable2.columns(remaining_column).visible(true);
    $('#column_name_rexit').val(hiddenColumns2); // set the selected options of the selectpicker
	$('#column_name_rexit').selectpicker('refresh'); // refresh the selectpicker to update the UI
  }

  dataTable2.buttons().container().appendTo('.exit');

  var dataTable3 = $('#log_table3').DataTable({
    "buttons": [
      'copy', 'csv', 'excel', 'pdf', 'print'
    ],
    "processing": true
  });

  $('#column_name_rdenied').selectpicker();

  $('#column_name_rdenied').change(function(){

    var all_column = ["0", "1", "2", "3"];

    var remove_column = $('#column_name_rdenied').val();

    if($('#column_name_rdenied').val() == null){
      dataTable3.columns(all_column).visible(true);
      sessionStorage.removeItem("rdenied_table_hidden_columns"); // Remove session storage when all columns are visible
    } else{
      var remaining_column = all_column.filter(function(obj) { return remove_column.indexOf(obj) == -1; });

      dataTable3.columns(remove_column).visible(false);

      dataTable3.columns(remaining_column).visible(true);

      sessionStorage.setItem("rdenied_table_hidden_columns", JSON.stringify(remove_column)); // Set session storage with hidden columns
    }

    $('#column_name_rdenied').val(remove_column); // set the selected options of the selectpicker
	$('#column_name_rdenied').selectpicker('refresh'); // refresh the selectpicker to update the UI

  });

  // Check if there are any hidden columns in session storage and hide them on page load
  var hiddenColumns3 = JSON.parse(sessionStorage.getItem("rdenied_table_hidden_columns"));
  if(hiddenColumns3 != null){
    var remaining_column = ["0", "1", "2", "3"].filter(function(obj) { return hiddenColumns3.indexOf(obj) == -1; });
    dataTable3.columns(hiddenColumns3).visible(false);
    dataTable3.columns(remaining_column).visible(true);
    $('#column_name_rdenied').val(hiddenColumns3); // set the selected options of the selectpicker
	$('#column_name_rdenied').selectpicker('refresh'); // refresh the selectpicker to update the UI
  }

  dataTable3.buttons().container().appendTo('.denied');
});

//Navbar 
var dropdown = document.getElementsByClassName("drop_down_btn");
var i;

for (i = 0; i < dropdown.length; i++) {
dropdown[i].addEventListener("click", function() {
  var dropdownContent = this.nextElementSibling;
  if (dropdownContent.style.display === "block") {

    dropdownContent.style.display = "none";
  } else {
      
    dropdownContent.style.display = "block";
  }
});
}