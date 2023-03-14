$(document).ready(function(){  
  $('#log_table').DataTable();
  $('#log_table2').DataTable();  
  $('#log_table3').DataTable();  
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