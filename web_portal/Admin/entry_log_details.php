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
		if($session_type != "Admin") {
			header("Location: ../login.php");
		}
	}
?> 
<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset = "utf-8">
	<meta name = "autor" content = "Sabrina Tan">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ANPR - Entry Log Details</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/2ffaabbca0.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Hairline&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="style/log_details.css">
</head>

<?php
	$id = $_GET["referenceID"];
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "anprdb";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if($conn->connect_error){
		die("Connection Failed: " . $conn->connect_error);
	}

	$myquery = "SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, entrylog.image, vehicle.tenantLotNumber, vehicle.brand, vehicle.model, vehicle.colour FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID WHERE entrylog.referenceID = $id; ";
	$result = $conn->query($myquery);
	if(mysqli_num_rows($result) == 1) {
		$item = $result->fetch_assoc();
	}
?>

<body>
	<!--Sidebar starts here-->
	<div class="navigation_bar">
  <div class="logo_container"> 
  <img src="../images/naim.png" class="naim_logo"></img>
  <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div> 
  <div class="logo_tail"><span>ANPR</span></div> 
  </div>
  <div class="navigation_links_container">

  <div class="navigation_links"><a href="dashboard.php"><i class="fa-solid fa-house"></i>Dashboard</a></div>
  <div class="navigation_links"><a href="register_vehicle.php"><i class="fa-solid fa-person-circle-plus"></i>Registration</a></div>
  <div class="navigation_links drop_down_btn"><a href="#" class="active_page"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
    <div class="sub_menu">
		<div class="navigation_links"><a href="report.php"></i>Report</a></div>
        <div class="navigation_links"><a href="entry_log.php" class="active_page"></i>Entry Log</a></div>
        <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
		<div class="navigation_links"><a href="denied_access.php"></i>Denial Log</a></div>
    </div>
  
  <div class="navigation_links"><a href="view_vehicle.php"><i class="fa-solid fa-table"></i>Database</a></div>
  <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>
  <div class="navigation_links"><a href="../login.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
  
</div>
</div>
</div>
<script src="script/log.js"></script>
<!--Sidebar ends here-->


	<div class="content-container">
	<header>
		<h1>Entry Log Details</h1>
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
						<td class="row-header">Entry Time</td>
						<td>
						<?php 
						$date = $item["entryTime"];
						$dateObject = new DateTime($date);
						$format = $dateObject->format('d M, Y h:i A');
						echo $format; 
						?></td>
					</tr>
					<tr>
						<td class="row-header">Image</td>
						<td><?php echo '<img class="db_image" src="../../ANPR/images/'.$item["image"].'"/>';?></td>
					</tr>


		</table>
		</div>
	</section>
</div>
<div class="waves"><p>&</p></div>
</body>
</html>