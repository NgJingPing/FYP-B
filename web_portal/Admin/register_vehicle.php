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
    <title>ANPR - Registration</title>
	<style>
		.alert {
			font-size: 18px;
			font-weight: bold;
			background-color: #FFFFFF;
			color: white;
			opacity: 1;
			transition: opacity 0.6s;
			width: 100%;
			margin-left: auto;
			margin-right: auto;
		}

		.alert.success {background-color: #4DAC62;}
		.alert.error {background-color: #f44336;}
		.alert.warning {background-color: #ff9800;}

		.closebtn {
			margin-left: auto;
			color: white;
			font-weight: bold;
			float: right;
			font-size: 28px;
			line-height: 22px;
			cursor: pointer;
			transition: 0.3s;
		}

		.closebtn:hover {
			color: black;
		}
	</style>
</head>

<body>
<!--Sidebar starts here-->
<div class="navigation_bar">
	<div class="logo_container"> 
        <img src="../images/naim.png" class="naim_logo"></img>
        <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div> 
        <div class="logo_tail"><span>ANPR</span></div> 
    </div>
    <div class="navigation_links_container">
        <div class="navigation_links"><a href="index.php"><i class="fa-solid fa-house"></i>Dashboard</a></div>
        <div class="navigation_links"><a href="register_vehicle.php" class="active_page"><i class="fa-solid fa-person-circle-plus"></i>Registration</a></div>
        <div class="navigation_links"><a href="view_vehicle.php"><i class="fa-solid fa-table"></i>Database</a></div>
        <div class="navigation_links drop_down_btn"><a href="#"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
            <div class="sub_menu">
                <div class="navigation_links"><a href="report.php"></i>Report</a></div>
                <div class="navigation_links"><a href="entry_log.php"></i>Entry Log</a></div>
                <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
                <div class="navigation_links"><a href="denied_access.php"></i>Denial Log</a></div>
            </div>
        <div class="navigation_links"><a href="analytic.php"><i class="fa fa-line-chart"></i>Analytics</a></div>
        <?php 
        
        if($session_type == "Super Admin") {
            echo '<div class="navigation_links drop_down_btn"><a href="#"><i class="fa fa-users"></i>Management<i class="fa-solid fa-angle-right" style="margin-left: 0px; padding-left:8px;"></i></a></div>
            <div class="sub_menu">
                <div class="navigation_links"><a href="register_user.php"></i>Add User</a></div>
                <div class="navigation_links"><a href="manage_user.php"></i>View User</a></div>
            </div>';
        }
        ?>  
        <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>
        <div class="navigation_links" id="last_nav_link"><a href="../login.php" id="last_nav_link"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
    </div>
</div>
<script src="script/log.js"></script>
<!--Sidebar ends here-->

	<?php
		//define variable and set to empty value
		$tenantLotNumber = $plateNumber = $tenantName = $contactNumber = $model = $brand = $color = "";
		$tenantLotNumberErr = $plateNumberErr = $tenantNameErr = $contactNumberErr = $modelErr = $brandErr = $colorErr = $activeErr = $msg = "";
		$active = TRUE;

		//treat the special characters and slashes as normal text
		function test_input($data) { 
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

		include "../include/config.php";

		//if the submit button is clicked
		if (isset($_POST["submit"])) {
			if(empty($_POST["tenantLotNumber"])) {
				$tenantLotNumberErr = "Tenant Lot Number is required";
			} elseif (strlen($_POST["tenantLotNumber"]) > 6 ){ //return error message when the tenantLotNumber is less than 6 characters
				$tenantLotNumberErr = "tenantLotNumber should not exceed 6 characters";
			} else {
				$tenantLotNumber = test_input($_POST["tenantLotNumber"]); //get the tenantLotNumber from the form
				$tenantLotNumber = str_replace(' ', '', $tenantLotNumber); //remove white space
				$tenantLotNumber = mysqli_escape_string($conn, $tenantLotNumber); //prevent sql injection
			}

			if(empty($_POST["plateNumber"])) {
				$plateNumberErr = "License Plate Number is required";
			} elseif (strlen($_POST["plateNumber"]) > 15 ){
				$plateNumberErr = "License plate number should not exceed 15 characters";
			} else {
				$plateNumber = test_input($_POST["plateNumber"]);
				$plateNumber = str_replace(' ', '', $plateNumber); //remove white space
				$plateNumber = mysqli_escape_string($conn, $plateNumber); //prevent sql injection
			}

			if(empty($_POST["tenantName"])) {
				$tenantNameErr = "Tenant name is required";
			} elseif (strlen($_POST["tenantName"]) > 50 ){
				$tenantNameErr = "Tenant name should not exceed 50 characters";
			} else {
				$tenantName = test_input($_POST["tenantName"]); //get the tenant name from the form
				$tenantName = mysqli_escape_string($conn, $tenantName); //prevent sql injection
			}

			if(empty($_POST["contactNumber"])) {
				$contactNumberErr = "Contact Number is required";
			} elseif (strlen($_POST["contactNumber"]) > 10 ){ //return error message when the contact number is less than 10 characters
				$contactNumberErr = "Contact number should not exceed 10 characters";
			} elseif (!preg_match('/^[0-9]*$/',$_POST["contactNumber"])){
				$contactNumberErr = "Contact number should only contain numbers";
			} else {
				$contactNumber = test_input($_POST["contactNumber"]); //get the contact number from the form
				$contactNumber = mysqli_escape_string($conn, $contactNumber); //prevent sql injection
			}

			if(empty($_POST["brand"])) {
				$brandErr = "Brand is required";
			} elseif (strlen($_POST["brand"]) > 20 ){ //return error message when the brand is more than 20 characters
				$brandErr = "Brand should not exceed 20 characters";
			} else {
				$brand = test_input($_POST["brand"]); //get the brand from the form
				$brand = mysqli_escape_string($conn, $brand); //prevent sql injection
			}

			if(empty($_POST["model"])) {
				$modelErr = "Model is required";
			} elseif (strlen($_POST["model"]) > 30 ){ //return error message when the model is more than 30 characters
				$modelErr = "Model should not exceed 30 characters";
			} else {
				$model = test_input($_POST["model"]); //get the model from the form
				$model = mysqli_escape_string($conn, $model); //prevent sql injection
			}

			if(empty($_POST["color"])) {
				$colorErr = "Colour is required";
			} elseif (strlen($_POST["color"]) > 20 ){ //return error message when the color is more than 20 characters
				$colorErr = "Colour should not exceed 20 characters";
			} else {
				$color = test_input($_POST["color"]); //get the color from the form
				$color = mysqli_escape_string($conn, $color); //prevent sql injection
			}

			$check = isset($_POST['active']) ? "checked" : "unchecked"; //get the toggle button value
			if($check == "checked") {
				$active = TRUE;
			} else {
				$active = FALSE;
			}

			if($tenantLotNumber != "" && $plateNumber != "" && $tenantName !="" && $contactNumber !="" && $brand != "" && $model != "" && $color != "")
			{
				$checkTenant = "SELECT tenantLotNumber FROM tenant WHERE tenantLotNumber = '$tenantLotNumber';";
				$sql = mysqli_query($conn, $checkTenant);
				$result = mysqli_num_rows($sql);
				if($result > 0) {
   					$msg = "An account with the same Tenant Number already exists, Record is saved";
				} else {
  					$createTenant = "INSERT INTO tenant (tenantLotNumber, name, phoneNumber) VALUES (?, ?, ?)";
   					$stmt = $conn->prepare($createTenant);
   					$stmt->bind_param("sss", $tenantLotNumber, $tenantName, $contactNumber);
   					$stmt->execute();
					$msg = "New Tenant added. Record is saved.";
				}
				$myquery = "INSERT INTO vehicle (tenantLotNumber, licensePlate, brand, model, colour, isActive) VALUES (?, ?, ?, ?, ?, ?);";
				$stmt = $conn->prepare($myquery);
				$stmt->bind_param("ssssss", $tenantLotNumber, $plateNumber, $brand, $model, $color, $active);
				$stmt->execute();
				$conn->close();
				$tenantLotNumber = $plateNumber = $tenantName = $contactNumber = $model = $brand = $color = "";
				$tenantLotNumberErr = $plateNumberErr = $tenantNameErr = $contactNumberErr = $modelErr = $brandErr = $colorErr = "";
				$_POST["plateNumber"] = $_POST["tenantLotNumber"] = $_POST["tenantName"] = $_POST["contactNumber"] = $_POST["brand"] = $_POST["model"] = $_POST["color"] = $_POST["active"] = "";
			}
		}
	?>
	<div class="content-container">
	<header>
		<h1>Registration</h1>
	</header>

	<?php
		if ($msg != ""){
			echo '<section><div class="alert success">
							<span class="closebtn">&times;</span>
							' . $msg . '
						</div></section>';
		}
	?>

	<section>
		 <form method="post" action="">
			<div class="com_con">
				<fieldset>
					<div class="form_group">
					<div class="form_container">
					<label><b>Tenant Lot Number</b></label><span class="error"> * <?php echo $tenantLotNumberErr;?></span><input type="text" class="form_control" name="tenantLotNumber" value="<?php echo isset($_POST["tenantLotNumber"]) ? $_POST["tenantLotNumber"] : ''; ?>">
					</div>
					<div class="form_container">
					<label><b>License Plate Number</b></label><span class="error"> * <?php echo $plateNumberErr;?></span><input type="text" class="form_control" name="plateNumber" value="<?php echo isset($_POST["plateNumber"]) ? $_POST["plateNumber"] : ''; ?>">
					</div>
					</div>
					<div class="form_group">
					<div class="form_container">
					<label><b>Tenant Name</b></label><span class="error"> * <?php echo $tenantNameErr;?></span><input type="text" class="form_control" name="tenantName" value="<?php echo isset($_POST["tenantName"]) ? $_POST["tenantName"] : ''; ?>">
					</div>
					<div class="form_container">
					<label><b>Contact Number</b></label><span class="error"> * <?php echo $contactNumberErr;?></span><input type="text" class="form_control" name="contactNumber" value="<?php echo isset($_POST["contactNumber"]) ? $_POST["contactNumber"] : ''; ?>">
					</div>
					</div>
					<div class="form_group">
					<div class="form_container">
					<label><b>Brand</b></label><span class="error"> * <?php echo $brandErr;?></span><input type="text" name="brand" class="form_control" list="brands" value="<?php echo isset($_POST["brand"]) ? $_POST["brand"] : ''; ?>">
					<datalist id="brands">
					<option value="Audi">Audi</option>
					<option value="BMW">BMW</option>
					<option value="Chevrolet">Chevrolet</option>
					<option value="Honda">Honda</option>
					<option value="Hyundai">Hyundai</option>
					<option value="Isuzu">Isuzu</option>
					<option value="Mazda">Mazda</option>
					<option value="Mercedes">Mercedes</option>
					<option value="Mercedes">Mini</option>
					<option value="Mitsubishi">Mitsubishi</option>
					<option value="Nissan">Nissan</option>
					<option value="Perodua">Perodua</option>
					<option value="Proton">Proton</option>
					<option value="Toyota">Toyota</option>
					<option value="Volkswagen">Volkswagen</option>
					<option value="Volvo">Volvo</option>
					</datalist>
					</div>
					<div class="form_container">
					<label><b>Model</b></label><span class="error"> * <?php echo $modelErr;?></span><input type="text" name="model" class="form_control" value="<?php echo isset($_POST["model"]) ? $_POST["model"] : ''; ?>">
					</div>
					</div>
					<div class="form_group">
					<div class="form_container">
					<label><b>Colour</b></label><span class="error"> * <?php echo $colorErr;?></span><input type="text" name="color" class="form_control" list="colors" value="<?php echo isset($_POST["color"]) ? $_POST["color"] : ''; ?>">
					<datalist id="colors">
					<option value="Black">Black</option>
					<option value="Blue">Blue</option>
					<option value="Grey">Grey</option>
					<option value="Red">Red</option>
					<option value="Silver">Silver</option>
					<option value="White">White</option>
					</datalist>
					</div>
					<div class="form_container">
					<label><b>Active</b></label><label class="switch"><input type="checkbox" checked="checked" name="active" class="form_control"><span class="slider round"></span></label>
					</div>
					</div>
					<div class="form_group">
					<div class="form_container">
					<button onclick="window.location='index.php';" type="button" class="button_cancel">Cancel</button>
					</div>
					<div class="form_container">
					 <button type="submit" class="button_submit" name ="submit" value="Submit">Submit</button>
					</div>
					</div>
					</div>
				</fieldset>
			</div>
		 </form>
	</section>
	</div>

	<script>

	var close = document.getElementsByClassName("closebtn");
	var i;

	for (i = 0; i < close.length; i++) {
	  close[i].onclick = function(){
	    var div = this.parentElement;
	    div.style.opacity = "0";
	    setTimeout(function(){ div.style.display = "none"; }, 600);
	  }
	}
	</script>
	<div class="waves"></div>
</body>

</html>
