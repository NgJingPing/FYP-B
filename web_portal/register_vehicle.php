<?php 
	$session_email = $ssession_type = "";
	// Resume the session 
	session_start();
	// If $_SESSION['email'] not set, force redirect to login page 
	if (!isset($_SESSION['email']) && !isset($_SESSION['type'])) { 
		header("Location: login.php");
	} else { // Otherwise, assign the values into $session_email & $ssession_type
		$session_email = $_SESSION['email'];
		$session_type = $_SESSION['type'];
		if($session_type != "Admin") {
			header("Location: login.php");
		}
	}
?> 

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset = "utf-8">
	<meta name = "autor" content = "Sabrina Tan">
    <title>ANPR - Register</title>

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
    <link type="text/css" rel="stylesheet" href="style/style.css">
</head>

<body>
	<!--Sidebar starts here-->
	<div class="navigation_bar">
  <div class="logo_container"> 
  <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div> 
  <div class="logo_tail"><span>ANPR</span></div> 
  </div>
  <div class="navigation_links_container">

  <div class="navigation_links"><a href="dashboard.php"><i class="fa-solid fa-house"></i>Dashboard</a></div>
  <div class="navigation_links"><a href="register_vehicle.php" class="active_page"><i class="fa-solid fa-person-circle-plus"></i>Registration</a></div>
  <div class="navigation_links drop_down_btn"><a href="#"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
    <div class="sub_menu">
        <div class="navigation_links"><a href="entry_log.php"></i>Entry Log</a></div>
        <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
		<div class="navigation_links"><a href="denied_access.php"></i>Denial Log</a></div>
    </div>
  
  <div class="navigation_links"><a href="view_vehicle.php"><i class="fa-solid fa-table"></i>Database</a></div>
  <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>
  <div class="navigation_links"><a href="#"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
  
</div>
</div>
</div>
<script src="script/log.js"></script>
<!--Sidebar ends here-->

	<?php
		//define variable and set to empty value
		$tenantLotNumber = $plateNumber = $model = $brand = $color = "";
		$tenantLotNumberErr = $plateNumberErr = $modelErr = $brandErr = $colorErr = $msg = "";

		function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "anprdb";

		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if($conn->connect_error){
			die("Connection Failed: " . $conn->connect_error);
		}

		if (isset($_POST["submit"])) {
			if(empty($_POST["tenantLotNumber"])) {
				$tenantLotNumberErr = "Tenant Lot Number is required";
			} elseif (strlen($_POST["tenantLotNumber"]) > 6 ){ 
				$tenantLotNumberErr = "tenantLotNumber should not exceed 6 characters";
			} else {
				$tenantLotNumber = test_input($_POST["tenantLotNumber"]);
				$tenantLotNumber = str_replace(' ', '', $tenantLotNumber);
				$myquery = "SELECT tenantLotNumber FROM tenant WHERE tenantLotNumber = '$tenantLotNumber';";
				$sql = mysqli_query($conn, $myquery);
				$result = mysqli_num_rows($sql);

				if($result == 0){
					$myquery2 = "INSERT INTO tenant (tenantLotNumber) VALUES (?);";
					$stmt = $conn->prepare($myquery2);
					$stmt->bind_param("s", $tenantLotNumber);
					$stmt->execute();
				}
			}

			if(empty($_POST["plateNumber"])) {
				$plateNumberErr = "License Plate Number is required";
			} elseif (strlen($_POST["plateNumber"]) > 20 ){ 
				$plateNumberErr = "License plate number should not exceed 20 characters";
			} else {
				$plateNumber = test_input($_POST["plateNumber"]);
				$plateNumber = str_replace(' ', '', $plateNumber);
				$myquery = "SELECT licensePlate FROM vehicle WHERE licensePlate = '$plateNumber';";
				$sql = mysqli_query($conn, $myquery);
				$result = mysqli_num_rows($sql);

				if($result > 0){
					$plateNumberErr = "A vehicle with the same License plate number already exist.";
					$plateNumber = "";
				}
			}

			if(empty($_POST["brand"])) {
				$brandErr = "Brand is required";
			} elseif (strlen($_POST["brand"]) > 20 ){ 
				$brandErr = "Brand should not exceed 20 characters";
			} else {
				$brand = test_input($_POST["brand"]);
			}

			if(empty($_POST["model"])) {
				$modelErr = "Model is required";
			} elseif (strlen($_POST["model"]) > 30 ){ 
				$modelErr = "Model should not exceed 30 characters";
			} else {
				$model = test_input($_POST["model"]);
			}

			if(empty($_POST["color"])) {
				$colorErr = "Colour is required";
			} elseif (strlen($_POST["color"]) > 20 ){ 
				$colorErr = "Colour should not exceed 20 characters";
			} else {
				$color = test_input($_POST["color"]);
			}

			if($tenantLotNumber != "" && $plateNumber != "" && $brand != "" && $model != "" && $color != "")
			{
				$myquery = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour) VALUES (?, ?, ?, ?, ?);";
				$stmt = $conn->prepare($myquery);
				$stmt->bind_param("sssss", $plateNumber, $tenantLotNumber, $brand, $model, $color);
				$stmt->execute();
				$conn->close();
				$msg = "Record is saved.";
				$tenantLotNumber = $plateNumber = $model = $brand = $color = "";
				$tenantLotNumberErr = $plateNumberErr = $modelErr = $brandErr = $colorErr = "";
				$_POST["plateNumber"] = $_POST["tenantLotNumber"] = $_POST["brand"] = $_POST["model"] = $_POST["color"] = "";
			}
		}
	?>
	<div class="content-container">
	<header>
		<h1>Register</h1>
	</header>

	<section>
		 <form method="post" action="">
			<div class="com_con">
				<fieldset>
					<legend>Vehicle Information</legend>
					<p><span class="error">* required field</span></p>

					<p>Tenant Lot Number: <input type="text" name="tenantLotNumber" value="<?php echo isset($_POST["tenantLotNumber"]) ? $_POST["tenantLotNumber"] : ''; ?>"><span class="error"> * <?php echo $tenantLotNumberErr;?></span></p>

					<p>License Plate Number: <input type="text" name="plateNumber" value="<?php echo isset($_POST["plateNumber"]) ? $_POST["plateNumber"] : ''; ?>"><span class="error"> * <?php echo $plateNumberErr;?></span></p>

					<p>Brand: <input type="text" name="brand" value="<?php echo isset($_POST["brand"]) ? $_POST["brand"] : ''; ?>"><span class="error"> * <?php echo $brandErr;?></span></p>


					<p>Model: <input type="text" name="model" value="<?php echo isset($_POST["model"]) ? $_POST["model"] : ''; ?>"><span class="error"> * <?php echo $modelErr;?></span></p>

					<p>Colour: <input type="text" name="color" value="<?php echo isset($_POST["color"]) ? $_POST["color"] : ''; ?>"><span class="error"> * <?php echo $colorErr;?></span></p>

					 <p><input type="submit" name="submit" value="Submit"></p>
				</fieldset>
				 <p class="message"><span class="successMsg"><?php echo $msg;?></span><p>
			</div>
		 </form>
	</section>
	</div>
</body>

</html>