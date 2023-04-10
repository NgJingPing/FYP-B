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

<!DOCTYPE HTML>
<html lang="en">

<head>
	<?php include "../include/head.php";?>
    <title>ANPR - Denied Access Log Details</title>
</head>

<!-- Returns data from deniedAccess table from with the corresponding referenceID from database-->
<?php
	$id = $_GET["referenceID"];
	include "../include/config.php";

	$myquery = "SELECT * FROM deniedAccess WHERE deniedAccess.referenceID = $id; ";
	$result = $conn->query($myquery);
	if(mysqli_num_rows($result) == 1) {
		$item = $result->fetch_assoc();
	}
?>

<body>
<!--Sidebar starts here-->
<?php 
    // Give active page  
    $page = 'Log';
    $subpage = 'Denial Log';
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

<div class="content-container">
	<header>
		<h1>Denied Access Log Details</h1>
	</header>

	<section>
	<div class="main_container">
		<div class="table-responsive">
		<table class="table table-bordered">  
                    <tr>
						<td class="row-header">License Plate Number</td>
						<td><?php echo $item["licensePlate"]; ?></td>
					</tr>  
					<tr>
						<td class="row-header">Time</td>
						<td>
						<?php 
						$date = $item["deniedTime"];
						$dateObject = new DateTime($date);
						$format = $dateObject->format('d M, Y h:i A');
						echo $format; 
						?></td>
					</tr>
					<tr>
						<td class="row-header">Image</td>
						<td><?php echo '<img class="db_image" src="../../ANPR/images/'.$item["image_2"].'"/>';?></td>
					</tr>
					<tr>
						<td class="row-header">Image with detection box</td>
						<td><?php echo '<img class="db_image" src="../../ANPR/images/'.$item["image"].'"/>';?></td>
					</tr>
		</table>
		</div>
		</div>
	</section>
</div>
<div class="waves"></div>
</body>
</html>