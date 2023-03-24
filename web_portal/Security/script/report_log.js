$(document).ready(function(){
	
	var dataTable = $('#log_table').DataTable({
		"buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
	});

	var dataTable2 = $('#log_table2').DataTable({
		"buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
	});

	var dataTable3 = $('#log_table3').DataTable({
		"buttons": [
        'copy', 'csv', 'excel', 'pdf', 'print'
      ]
	});

	dataTable.buttons().container().appendTo('.entry');
	dataTable2.buttons().container().appendTo('.exit');
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