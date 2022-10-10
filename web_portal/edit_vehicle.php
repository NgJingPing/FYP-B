<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset = "utf-8">
	<meta name = "autor" content = "Sabrina Tan">
	<link type="text/css" rel="stylesheet" href="style/style.css">
    <title>ANPR - Edit</title>
</head>

<body>

	<?php
		//define variable and set to empty value
		$tenantLotNumber = $model = $brand = $color = "";
		$tenantLotNumberErr = $modelErr = $brandErr = $colorErr = $msg = "";

		// get the plate number from the link
		/*if(isset($_POST["plateNumber"])) {
			//$plateNumber = $_POST["plateNumber"];
		} else {
			header("Location: database.php");
		}*/
		
		$plateNumber = "QAA1234";
	
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
				$myquery = "UPDATE vehicle SET tenantLotNumber = ?, brand = ?, model = ?, colour = ? WHERE licensePlate = '$plateNumber';";
				$stmt = $conn->prepare($myquery);
				$stmt->bind_param("ssss", $tenantLotNumber, $brand, $model, $color);
				$stmt->execute();
				$conn->close();
				$msg = "Record is updated.";
				$tenantLotNumber = $model = $brand = $color = "";
				$tenantLotNumberErr = $modelErr = $brandErr = $colorErr = "";
				$_POST["tenantLotNumber"] = $_POST["brand"] = $_POST["model"] = $_POST["color"] = "";
			}
		}

		if(isset($_POST["cancel"])) {
			header("Location: database.php");
		}

		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if($conn->connect_error){
			die("Connection Failed: " . $conn->connect_error);
		}

		$myquery = "SELECT tenantLotNumber, brand, model, colour FROM vehicle WHERE licensePlate = '$plateNumber';";

		$result = $conn->query($myquery);

		if(mysqli_num_rows($result) == 1) {
			$item = $result->fetch_assoc();
			$_POST["tenantLotNumber"] = $item['tenantLotNumber'];
			$_POST["brand"] = $item["brand"];
			$_POST["model"] = $item["model"];
			$_POST["color"] = $item["colour"];
		}
		$conn->close();
	?>

	<header>
		<h1>Edit</h1>
	</header>

	<section>
		 <form method="post" action="">
			<div class="com_con">
				<fieldset>
					<legend>Vehicle Information</legend>
					<p><span class="error">* required field</span></p>

					<p>Tenant Lot Number: <input type="text" name="tenantLotNumber" value="<?php echo isset($_POST["tenantLotNumber"]) ? $_POST["tenantLotNumber"] : ''; ?>"><span class="error"> * <?php echo $tenantLotNumberErr;?></span></p>

					<p>License Plate Number: <input type="text" placeholder="<?php echo $plateNumber; ?>" disabled="disabled"></p>

					<p>Brand: <input type="text" name="brand" value="<?php echo isset($_POST["brand"]) ? $_POST["brand"] : ''; ?>"><span class="error"> * <?php echo $brandErr;?></span></p>


					<p>Model: <input type="text" name="model" value="<?php echo isset($_POST["model"]) ? $_POST["model"] : ''; ?>"><span class="error"> * <?php echo $modelErr;?></span></p>

					<p>Colour: <input type="text" name="color" value="<?php echo isset($_POST["color"]) ? $_POST["color"] : ''; ?>"><span class="error"> * <?php echo $colorErr;?></span></p>

					 <p><input type="submit" name="submit" value="Submit">
					 <input type="submit" name="cancel" value="Cancel"></p>
				</fieldset>
				 <p class="message"><span class="successMsg"><?php echo $msg;?></span><p>
			</div>
		 </form>
	</section>

</body>
</html>
