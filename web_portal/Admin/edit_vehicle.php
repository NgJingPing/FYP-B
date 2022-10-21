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
	<meta name = "author" content = "Sabrina Tan">
    <title>ANPR - Edit</title>

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
    <link type="text/css" rel="stylesheet" href="style/registration.css">
    
    
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
  <div class="navigation_links"><a href="register_vehicle.php"><i class="fa-solid fa-person-circle-plus"></i>Registration</a></div>
  <div class="navigation_links drop_down_btn"><a href="#"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
    <div class="sub_menu">
        <div class="navigation_links"><a href="entry_log.php"></i>Entry Log</a></div>
        <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
		<div class="navigation_links"><a href="denied_access.php"></i>Denial Log</a></div>
    </div>
  
  <div class="navigation_links"><a href="view_vehicle.php" class="active_page"><i class="fa-solid fa-table"></i>Database</a></div>
  <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>
  <div class="navigation_links"><a href="../login.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
  
</div>
</div>
</div>
<script src="script/log.js"></script>
<!--Sidebar ends here-->

	<?php
		//define variable and set to empty value
		$tenantLotNumber = $plateNumber = $model = $brand = $color = $checked = "";
		$tenantLotNumberErr = $plateNumberErr = $modelErr = $brandErr = $colorErr = $activeErr = $msg = "";
		$active = TRUE;
		$vehicleID = 0;

		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "anprdb";

		// get the plate number from the link
		if(isset($_GET["vehicleID"])) {
			$vehicleID = $_GET["vehicleID"];
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if($conn->connect_error){
				die("Connection Failed: " . $conn->connect_error);
			}

			/*$myquery = "SELECT vehicleID FROM vehicle WHERE licensePlate = '$plateNumber';";
			$sql = mysqli_query($conn, $myquery);
			$result = $conn->query($myquery);
			if(mysqli_num_rows($result) == 1) {
				$item = $result->fetch_assoc();
				$id = $item['vehicleID'];
			}*/

		} else {
			header("Location: view_vehicle.php");
		}


		function test_input($data) {
		    $data = trim($data);
		    $data = stripslashes($data);
		    $data = htmlspecialchars($data);
		    return $data;
		}

		if (isset($_POST["submit"])) {
			$conn = mysqli_connect($servername, $username, $password, $dbname);
			if($conn->connect_error){
				die("Connection Failed: " . $conn->connect_error);
			}
			
			if(empty($_POST["tenantLotNumber"])) {
				$tenantLotNumberErr = "Tenant Lot Number is required";
			} elseif (strlen($_POST["tenantLotNumber"]) > 6 ){ 
				$tenantLotNumberErr = "tenantLotNumber should not exceed 6 characters";
			} else {
				$tenantLotNumber = test_input($_POST["tenantLotNumber"]);
				$tenantLotNumber = str_replace(' ', '', $tenantLotNumber);
				/*$myquery = "SELECT tenantLotNumber FROM tenant WHERE tenantLotNumber = '$tenantLotNumber';";
				$sql = mysqli_query($conn, $myquery);
				$result = mysqli_num_rows($sql);

				if($result == 0){
					$myquery2 = "INSERT INTO tenant (tenantLotNumber) VALUES (?);";
					$stmt = $conn->prepare($myquery2);
					$stmt->bind_param("s", $tenantLotNumber);
					$stmt->execute();
				}*/
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
				$myquery = "UPDATE vehicle SET tenantLotNumber = ?, licensePlate = ?, brand = ?, model = ?, colour = ?, isActive = ? WHERE vehicleID = $vehicleID;";
				$stmt = $conn->prepare($myquery);
				$stmt->bind_param("ssssss", $tenantLotNumber, $plateNumber, $brand, $model, $color, $active);
				$stmt->execute();
				$conn->close();
				$msg = "Record is updated.";
				$tenantLotNumber = $model = $brand = $color = "";
				$tenantLotNumberErr = $modelErr = $brandErr = $colorErr = "";
				$_POST["tenantLotNumber"] = $_POST["brand"] = $_POST["plateNumber"] = $_POST["model"] = $_POST["color"] = "";
			}
		}

		if(isset($_POST["cancel"])) {
			header("Location: database.php");
		}

		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if($conn->connect_error){
			die("Connection Failed: " . $conn->connect_error);
		}

		$myquery = "SELECT tenantLotNumber, licensePlate, brand, model, colour, isActive FROM vehicle WHERE vehicleID = $vehicleID;";

		$result = $conn->query($myquery);

		if(mysqli_num_rows($result) == 1) {
			$item = $result->fetch_assoc();
			$_POST["tenantLotNumber"] = $item['tenantLotNumber'];
			$_POST["plateNumber"] = $item["licensePlate"];
			$_POST["brand"] = $item["brand"];
			$_POST["model"] = $item["model"];
			$_POST["color"] = $item["colour"];
			if($item["isActive"] == 1) {
				$checked = "checked";
			} else {
				$checked = "unchecked";
			}
		}
		$conn->close();
	?>
	<div class="content-container">
	<header>
		<h1>Edit</h1>
	</header>

	<section>
		 <form method="post" action="">
			<div class="com_con">
				<fieldset>
					<legend>Vehicle Information</legend>

					<div class="form_group">
					<div class="form_container">
					<label>Tenant Lot Number</label><span class="error"> * <?php echo $tenantLotNumberErr;?></span><input type="text" class="form_control" name="tenantLotNumber" value="<?php echo isset($_POST["tenantLotNumber"]) ? $_POST["tenantLotNumber"] : ''; ?>">
					</div>
					<div class="form_container">
					<label>License Plate Number</label><input type="text" class="form_control" name="plateNumber" value="<?php echo isset($_POST["plateNumber"]) ? $_POST["plateNumber"] : ''; ?>">
					</div>	
					</div>
					<div class="form_group">
					<div class="form_container">
					<label>Brand</label><span class="error"> * <?php echo $brandErr;?></span><input type="text" name="brand" class="form_control" value="<?php echo isset($_POST["brand"]) ? $_POST["brand"] : ''; ?>">
					</div>
					<div class="form_container">
					<label>Model</label><span class="error"> * <?php echo $modelErr;?></span><input type="text" name="model" class="form_control" value="<?php echo isset($_POST["model"]) ? $_POST["model"] : ''; ?>">
					</div>
					</div>
					<div class="form_group">
					<div class="form_container">
					<label>Colour</label><span class="error"> * <?php echo $colorErr;?></span><input type="text" name="color" class="form_control" value="<?php echo isset($_POST["color"]) ? $_POST["color"] : ''; ?>">
					</div>
					<div class="form_container">
					<label>Active</label><span class="error"> * <?php echo $activeErr;?></span> 
					<?php
						if($checked == "checked") {
							echo '<input type="checkbox" checked name="active" class="form_control">';
						} else {
							echo '<input type="checkbox" unchecked name="active" class="form_control">';
						}
					?>
					</div>
					</div>
					<div class="form_group">
					<div class="form_container">
					 <button type="submit" class="button_submit" name ="submit" value="Submit">Submit</button>
					</div>
					<div class="form_container">
					<button onclick="window.location='view_vehicle.php';" type="button" class="button_cancel">Cancel</button>
					</div>
					</div>
					</div>
				</fieldset>
				 <p class="message"><span class="successMsg"><?php echo $msg;?></span><p>
			</div>
		 </form>
	</section>
	</div>
</body>
</html>
