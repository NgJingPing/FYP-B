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
        <div id="table" onload = "table();"></div>
    </section>
  </div>
  <div class="waves"></div>
  </body>
</html>