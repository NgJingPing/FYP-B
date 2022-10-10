<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset = "utf-8">
	<meta name = "autor" content = "Sabrina Tan">
	<link type="text/css" rel="stylesheet" href="style/style.css">
    <title>ANPR - Exit Log Details</title>
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

	$myquery = "SELECT exitlog.referenceID, exitlog.licensePlate, exitlog.exitTime, exitlog.image, vehicle.tenantLotNumber, vehicle.brand, vehicle.model, vehicle.colour FROM exitlog INNER JOIN vehicle ON exitlog.licensePlate = vehicle.licensePlate WHERE exitlog.referenceID = $id; ";
	$result = $conn->query($myquery);
	if(mysqli_num_rows($result) == 1) {
		$item = $result->fetch_assoc();
	}
?>

<body>
	<header>
		<h1>Exit Log Details</h1>
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
			<p>Timestamp:  <?php echo $item["exitTime"] ?></p>
			<p>Mode: Exit</p>
		</div>
	</section>
</body>
</html>