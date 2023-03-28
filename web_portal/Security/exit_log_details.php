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
		if($session_type != "Security") {
			header("Location: ../login.php");
		}
	}
?> 

<!DOCTYPE HTML>
<html lang="en">

<head>
	<?php include "../include/head.php";?>
    <title>ANPR - Exit Log Details</title>
</head>

<?php
	$id = $_GET["referenceID"];
	include "../include/config.php";
	// This SQL query retrieves information about a specific vehicle exit from the exitlog and vehicle tables, joining them on the vehicleID column and filtering the results based on the reference ID. 
	// The query returns the reference ID, license plate number, exit time, vehicle images, tenant lot number, brand, model, and color of the vehicle associated with the specified reference ID.
	$myquery = "SELECT exitlog.referenceID, vehicle.licensePlate, exitlog.exitTime, exitlog.image_2, vehicle.tenantLotNumber, vehicle.brand, vehicle.model, vehicle.colour FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID WHERE exitlog.referenceID = $id; ";
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
    $subpage = 'Exit Log';
    // Give user role
    $role = "Security"; include "../include/navbar.php";
?> 
<script src="script/log.js"></script>
<!--Sidebar ends here-->

<div class="content-container">
	<header>
		<h1>Exit Log Details</h1>
	</header>

	<section>
	<div class="main_container">
	<table class="table table-bordered">  
  
                    <tr>
						<td class="row-header">Tenant Lot Number</td>
						<td><?php echo $item["tenantLotNumber"]; ?></td>
					</tr>  
                    <tr>
						<td class="row-header">Vehicle Brand</td>
						<td><?php echo $item["brand"]; ?></td>
					</tr>  
                    <tr>
						<td class="row-header">Vehicle Model</td>
						<td><?php echo $item["model"]; ?></td>
					</tr>  
                    <tr>
						<td class="row-header">Vehicle Colour</td>
						<td><?php echo $item["colour"]; ?></td>
					</tr>  
                    <tr>
						<td class="row-header">License Plate Number</td>
						<td><?php echo $item["licensePlate"]; ?></td>
					</tr>  
					<tr>
						<td class="row-header">Exit Time</td>
						<td>
						<?php 
						$date = $item["exitTime"];
						$dateObject = new DateTime($date);
						$format = $dateObject->format('d M Y h:i A');
						echo $format; 
						?></td>
					</tr>
					<tr>
						<td class="row-header">Image</td>
						<td><?php echo '<img class="db_image" src="../../ANPR/images/'.$item["image_2"].'"/>';?></td>
					</tr>


		</table>
		</div>
	</section>
</div>
<div class="waves"></div>
</body>
</html>