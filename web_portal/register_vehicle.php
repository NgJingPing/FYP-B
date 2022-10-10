<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset = "utf-8">
	<meta name = "autor" content = "Sabrina Tan">
	<link type="text/css" rel="stylesheet" href="style/style.css">
    <title>ANPR - Register</title>
</head>

<body>

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

</body>
</html>