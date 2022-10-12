<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset = "utf-8">
	<meta name = "autor" content = "Sabrina Tan">
	<link type="text/css" rel="stylesheet" href="style/style.css">
	<script src="https://kit.fontawesome.com/2ffaabbca0.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <title>ANPR - Entry Log Details</title>
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

	$myquery = "SELECT entrylog.referenceID, entrylog.licensePlate, entrylog.entryTime, entrylog.image, vehicle.tenantLotNumber, vehicle.brand, vehicle.model, vehicle.colour FROM entrylog INNER JOIN vehicle ON entrylog.licensePlate = vehicle.licensePlate WHERE entrylog.referenceID = $id; ";
	$result = $conn->query($myquery);
	if(mysqli_num_rows($result) == 1) {
		$item = $result->fetch_assoc();
	}
?>

<body>
	<!--Sidebar starts here-->
<div class="navigation_bar">
  <div class="logo"><img src="images/grab-logo.png"></div> 
  <div class="navigation_links_container">

  <div class="navigation_links"><a href="dashboard.php"><i class="fa-solid fa-house"></i>Dashboard</a></div>
  <div class="navigation_links"><a href="register_vehicle.php"><i class="fa-solid fa-person-circle-plus"></i>Registration</a></div>
  <div class="navigation_links drop_down_btn"><a href="#" class="active_page"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
    <div class="sub_menu">
        <div class="navigation_links"><a href="entry_log.php" class="active_page"></i>Entry Log</a></div>
        <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
    </div>
  
  <div class="navigation_links"><a href="database.php"><i class="fa-solid fa-table"></i>Database</a></div>
  <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>
  <div class="navigation_links"><a href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
  
</div>
</div>
</div>
<script src="script/navbar.js"></script>
<!--Sidebar ends here-->


	<div class="content-container">
	<header>
		<h1>Entry Log Details</h1>
	</header>

	<section>
		<div class="container_left">
			<p>Tenant Lot Number: <?php echo $item["tenantLotNumber"]; ?></p>
			<p>Vehicle Brand: <?php echo $item["brand"]; ?></p>
			<p>Vehicle Model: <?php echo $item["model"]; ?></p>
			<p>Vehicle Colour: <?php echo $item["colour"]; ?></p>
		</div>
		<div class="container_right">
			<p> <?php echo '<img height = "250" width = "250" src="data:image/jpeg;base64,'.base64_encode( $item['image'] ).'"/>';?></p>
			<p>License Plate Number: <?php echo $item["licensePlate"] ?></p>
			<p>Timestamp:  <?php echo $item["entryTime"] ?></p>
			<p>Mode: Entry</p>
		</div>
	</section>
</div>
</body>
</html>