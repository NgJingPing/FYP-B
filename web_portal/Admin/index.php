<?php 
	$session_email = $ssession_type = "";
	// Resume the session 
	session_start();
	// If $_SESSION['email'] not set, force redirect to login page 
	if (!isset($_SESSION['email']) && !isset($_SESSION['type'])) { 
		header("Location: ../login.php");
	} else { // Otherwise, assign the values into $session_email & $ssession_type
		$session_email = $_SESSION['email'];
		$session_type = $_SESSION['type'];
		if($session_type != "Admin" && $session_type != "Super Admin") {
			header("Location: ../login.php");
		}
	}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../include/head.php";?>
	<title>ANPR - Dashboard</title>
</head>

<body>
<!--Sidebar starts here-->
<?php 
    // Give active page  
    $page = 'Dashboard';
    $subpage = '';
    // Give user role
    if($session_type == "Super Admin") {
        $role = "Super admin"; include "../include/navbar.php";
    }
    else{
        $role = "Admin"; include "../include/navbar.php";
    }
?> 
<script src="script/log.js"></script>
<!--Sidebar ends here-->

<?php
    include "../include/config.php";
    $referenceID = "";
?>
  <div class="content-container">
    <header>
    <h1>Dashboard</h1>
    </header>
    <!-- Loads dashboard.php into the inner HTML -->
    <section>
        <div id="table"></div>
		<div class="dashboard_logs">
			<div id="show_entry" style="width:100%;"></div>
			<div id="show_exit" style="width:100%;"></div>
		</div>

    </section>
  </div>
  <div class="waves"></div>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){  
			$("#table").load("./widget_loader.php");

			setInterval(function(){
				$.ajax({
				url: "./widget_checker.php",
				success: function(response){
					let curr = $(".totalflow").text();
					let curr2 = $(".entryflow").text();
					let curr3 = $(".exitflow").text();
					let curr4 = $(".deniedflow").text();
					var count = response.split(',');
					console.log(count[0]);
					if(count[0].text() != curr){
						$("#table").load("./widget_loader.php");
					} 
					if(count[1].text() != curr2){
						$("#table").load("./widget_loader.php");
					} 
					if(count[2].text() != curr3){
						$("#table").load("./widget_loader.php");
					} 
					if(count[3].text() != curr4){
						$("#table").load("./widget_loader.php");
					} 
				}
			});
			}, 1500);


			$("#show_entry").load("./entry_loader.php");

			setInterval(function(){
				$.ajax({
				url: "./entry_checker.php",
				success: function(response){
					let curr = $(".curr_val").text();
					console.log(response);
					console.log(curr);
					if(response != curr){
						$("#show_entry").load("./entry_loader.php");
					}
				}
			});
			}, 1500);

			$("#show_exit").load("./exit_loader.php");

			setInterval(function(){
				$.ajax({
				url: "./exit_checker.php",
				success: function(response){
					let curr = $(".curr_val2").text();
					console.log(response);
					console.log(curr);
					if(response != curr){
						$("#show_exit").load("./exit_loader.php");
					}
				}
			});
			}, 1500);
		});
	</script>
	<?php
		include "../include/config.php";
		include "../include/head.php";
	?>
  </body>
</html>