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
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
			<div class="navigation_links"><a href="report.php"></i>Report</a></div>
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
		$tenantLotNumber = $plateNumber = $tenantName = $contactNumber = $model = $brand = $color = "";
		$tenantLotNumberErr = $plateNumberErr = $tenantNameErr = $contactNumberErr = $modelErr = $brandErr = $colorErr = $activeErr = $msg = "";
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

			if(empty($_POST["plateNumber"])) {
				$plateNumberErr = "License Plate Number is required";
			} elseif (strlen($_POST["plateNumber"]) > 10 ){ 
				$plateNumberErr = "License plate number should not exceed 10 characters";
			} else {
				$plateNumber = test_input($_POST["plateNumber"]);
				$plateNumber = str_replace(' ', '', $plateNumber);
			}

			if(empty($_POST["tenantName"])) {
				$tenantNameErr = "Tenant name is required";
			} elseif (strlen($_POST["tenantName"]) > 50 ){ 
				$tenantNameErr = "Tenant name should not exceed 50 characters";
			} else {
				$tenantName = test_input($_POST["tenantName"]);
			}

			if(empty($_POST["contactNumber"])) {
				$contactNumberErr = "Contact Number is required";
			} elseif (strlen($_POST["contactNumber"]) > 15 ){ 
				$contactNumberErr = "Contact number should not exceed 15 characters";
			} else {
				$contactNumber = test_input($_POST["contactNumber"]);
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

			$check = isset($_POST['active']) ? "checked" : "unchecked"; 
			if($check == "checked") { 
				$active = TRUE;
			} else { 
				$active = FALSE; 
			}

			if($plateNumber != "" && $tenantName != "" && $contactNumber !="" && $brand != "" && $model != "" && $color != "")
			{
				$myquery = "UPDATE vehicle SET licensePlate = ?, brand = ?, model = ?, colour = ?, isActive = ? WHERE vehicleID = $vehicleID;";
				$stmt = $conn->prepare($myquery);
				$stmt->bind_param("sssss", $plateNumber, $brand, $model, $color, $active);
				$stmt->execute();
				$nums = $_SESSION['num'];
				$sql = "UPDATE tenant SET name = '$tenantName', phoneNumber = $contactNumber WHERE tenantLotNumber = '$nums'";
				$conn->query($sql);

				$conn->close();
				$msg = "Record is updated.";
				$tenantLotNumber = $plateNumber = $tenantName = $contactNumber = $model = $brand = $color = "";
				$tenantLotNumberErr = $plateNumberErr = $tenantNameErr = $contactNumberErr = $modelErr = $brandErr = $colorErr = "";
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

		$myquery = "SELECT vehicle.tenantLotNumber, vehicle.licensePlate, vehicle.brand, vehicle.model, vehicle.colour, vehicle.isActive, tenant.name, tenant.phoneNumber FROM vehicle JOIN tenant WHERE tenant.tenantLotNumber = vehicle.tenantLotNumber AND vehicleID = $vehicleID;";

		$result = $conn->query($myquery);

		if(mysqli_num_rows($result) == 1) {
			$item = $result->fetch_assoc();
			$_POST["tenantLotNumber"] = $item['tenantLotNumber'];
			$tenantLotNumber = $item['tenantLotNumber'];
			$_POST["plateNumber"] = $item["licensePlate"];
			$_POST["brand"] = $item["brand"];
			$_POST["model"] = $item["model"];
			$_POST["color"] = $item["colour"];
			$_POST["tenantName"] = $item["name"];
			$_POST["contactNumber"] = $item["phoneNumber"];
			if($item["isActive"] == 1) {
				$checked = "checked";
			} else {
				$checked = "unchecked";
			}
			$_SESSION['num'] = $tenantLotNumber;
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
					<label>Tenant Lot Number</label><input type="text" class="form_control" name="tenantLotNumber" placeholder="<?php echo $tenantLotNumber; ?>" disabled="disabled">
					</div>
					<div class="form_container">
					<label>License Plate Number</label><input type="text" class="form_control" name="plateNumber" value="<?php echo isset($_POST["plateNumber"]) ? $_POST["plateNumber"] : ''; ?>">
					</div>	
					</div>
					<div class="form_group">
					<div class="form_container">
					<label>Tenant Name</label><span class="error"> * <?php echo $tenantNameErr;?></span><input type="text" class="form_control" name="tenantName" value="<?php echo isset($_POST["tenantName"]) ? $_POST["tenantName"] : ''; ?>">
					</div>
					<div class="form_container">
					<label>Contact Number</label><span class="error"> * <?php echo $contactNumberErr;?></span><input type="text" class="form_control" name="contactNumber" value="<?php echo isset($_POST["contactNumber"]) ? $_POST["contactNumber"] : ''; ?>">
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
					<label>Active</label> 
					<label class="switch">
						<?php
							if($checked == "checked") {
								echo '<input type="checkbox" checked name="active" class="form_control">';
							} else {
								echo '<input type="checkbox" unchecked name="active" class="form_control">';
							}
						?>
						<span class="slider round"></span>
					</label>
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
	<div class="waves"><p>&</p></div>
</body>
</html>
