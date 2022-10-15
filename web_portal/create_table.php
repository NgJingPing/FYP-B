<!-- <h1>Connect to your phpMyAdmin</h1> -->
<?php
// set the servername,username and password
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "anprdb";

// Create connection
//The mysqli_connect() function attempts to open a connection to the MySQL Server
//running on host which can be either a host name or an IP address.
$conn = mysqli_connect($servername, $username, $password, $dbname);

// sql to create Enquiry table
$sql1 = "CREATE TABLE tenant (
    tenantLotNumber VARCHAR(6) PRIMARY KEY NOT NULL
    );
";

$sql2 = "CREATE TABLE vehicle (
	licensePlate VARCHAR(20) PRIMARY KEY NOT NULL,
	tenantLotNumber VARCHAR(6) NOT NULL,
	brand VARCHAR(20) NOT NULL,
	model VARCHAR(20) NOT NULL,
	colour VARCHAR(20) NOT NULL,
	FOREIGN KEY(tenantLotNumber) REFERENCES tenant(tenantLotNumber)
	);
";

$sql3 = "CREATE TABLE entryLog (
   referenceID INT(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
   licensePlate VARCHAR(20) NOT NULL,
   entryTime DATETIME NOT NULL,
   image blob NOT NULL,
   FOREIGN KEY(licensePlate) REFERENCES vehicle(licensePlate)
   );
";

$sql4 = "CREATE TABLE exitLog (
   referenceID INT(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
   licensePlate VARCHAR(20) NOT NULL,
   exitTime DATETIME NOT NULL,
   image blob NOT NULL,
   FOREIGN KEY(licensePlate) REFERENCES vehicle(licensePlate)
   );
";

$sql5 = "CREATE TABLE deniedAccess(
   referenceID INT(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
   licensePlate VARCHAR(20) NOT NULL,
   deniedTime DATETIME NOT NULL,
   image blob NOT NULL
   );
";

$sql6 = "CREATE TABLE admin (
   adminID INT(4) PRIMARY KEY AUTO_INCREMENT NOT NULL,
   email VARCHAR(256) NOT NULL,
   password VARCHAR(256) NOT NULL,
   isAdvanced BOOLEAN NOT NULL
   );
";

$sql7 = "CREATE TABLE security (
   securityID INT(4) PRIMARY KEY AUTO_INCREMENT NOT NULL,
   email VARCHAR(256) NOT NULL,
   password VARCHAR(256) NOT NULL
   );
";


$tables = [$sql1, $sql2, $sql3, $sql4, $sql5, $sql6, $sql7];

foreach($tables as $sql){
	if (mysqli_query($conn, $sql)) {
		//echo "Table MyDetails created successfully";
	 } else {
		//echo "Error creating table: " . mysqli_error($conn);
	 }
}

//
//$sql = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour)
//VALUES ('".$_POST['plateNumber']."', '".$_POST['tenantLotNumber']."', '".$_POST['brand']."','".$_POST['model']."','".$_POST['colour']."')";
//
// Hardcoded
$sql1 = "INSERT INTO tenant (tenantLotNumber)
VALUES ('AB1234')";

$sql2 = "INSERT INTO tenant (tenantLotNumber)
VALUES ('AB2345')";

$sql3 = "INSERT INTO tenant (tenantLotNumber)
VALUES ('AB3456')";

$sql4 = "INSERT INTO tenant (tenantLotNumber)
VALUES ('AB4567')";

$sql5 = "INSERT INTO tenant (tenantLotNumber)
VALUES ('AB5678')";

$tenant_datas = [$sql1, $sql2, $sql3, $sql4, $sql5];

foreach($tenant_datas as $sql){
	if (mysqli_query($conn, $sql)) {
		//echo "New record created successfully";
	 } else {
		//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	 }
}


$sql1 = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour)
VALUES ('QAB1234', 'AB1234', 'Honda', 'City', 'Purple')";

$sql2 = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour)
VALUES ('QAB2345', 'AB2345', 'Honda', 'Civic', 'White')";

$sql3 = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour)
VALUES ('QAB3456', 'AB3456', 'Proton', 'Saga', 'Brown')";

$sql4 = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour)
VALUES ('QAB4567', 'AB4567', 'Proton', 'Waja', 'Grey')";

$sql5 = "INSERT INTO vehicle (licensePlate, tenantLotNumber, brand, model, colour)
VALUES ('QAB5678', 'AB5678', 'Toyota', 'Hilux', 'Black')";

$vehicle_datas = [$sql1, $sql2, $sql3, $sql4, $sql5];

foreach($vehicle_datas as $sql){
	if (mysqli_query($conn, $sql)) {
		//echo "New record created successfully";
	 } else {
		//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	 }
}

$password0 = hash("sha256", "naim000");
$password1 = hash("sha256", "naim001");
$password2 = hash("sha256", "naim002");
$sql1 = "INSERT INTO admin (email, password, isAdvanced)
VALUES ('naim000@naim.com.my', '$password0', 'TRUE')";
$sql2 = "INSERT INTO admin (email, password, isAdvanced)
VALUES ('naim001@naim.com.my', '$password1', 'FALSE')";
$sql3 = "INSERT INTO admin (email, password, isAdvanced)
VALUES ('naim002@naim.com.my', '$password2', 'FALSE')";

$admin_datas = [$sql1, $sql2, $sql3];

foreach($admin_datas as $sql){
	if (mysqli_query($conn, $sql)) {
		//echo "New record created successfully";
	 } else {
		//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	 }
}

$password0 = hash("sha256", "naim100");
$password1 = hash("sha256", "naim101");
$password2 = hash("sha256", "naim102");
$sql1 = "INSERT INTO security (email, password)
VALUES ('naim100@naim.com.my', '$password0')";
$sql2 = "INSERT INTO security (email, password)
VALUES ('naim101@naim.com.my', '$password1')";
$sql3 = "INSERT INTO security (email, password)
VALUES ('naim102@naim.com.my', '$password2')";

$security_datas = [$sql1, $sql2, $sql3];

foreach($security_datas as $sql){
	if (mysqli_query($conn, $sql)) {
		//echo "New record created successfully";
	 } else {
		//echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	 }
 }

mysqli_close($conn);
header("location:view_vehicle.php");
exit();

?>
