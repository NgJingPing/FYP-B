$(document).ready(function(){  
  var logTable = $('#log_table').DataTable({
    buttons: [
      'copy', 'csv', 'excel', 'pdf', 'print'
    ]
});  
logTable.buttons().container().appendTo('.table-responsive')

$('#entry_log_table').DataTable(); 
$('#exit_log_table').DataTable(); 
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

function table(){
  const xhttp = new XMLHttpRequest();
  xhttp.onload = function(){
    document.getElementById("table").innerHTML = this.responseText;
  }
  xhttp.open("GET", "dashboard.php");
  xhttp.send();
}

setInterval(function(){
  table();
}, 1500);