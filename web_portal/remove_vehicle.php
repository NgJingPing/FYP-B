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

if (!$conn) {
    die("Connection Failed: ". msqli_connect_error());
}

error_reporting(0);

$vehicle = $_GET['vehicle'];
$del1 = "DELETE FROM entryLog WHERE licensePlate='$vehicle';";
$del2 =  "DELETE FROM exitLog WHERE licensePlate='$vehicle';";
$del3 = "DELETE FROM vehicle WHERE licensePlate='$vehicle';";

$remove_data = [$del1, $del2, $del3];

foreach($remove_data as $sql){
    $data = mysqli_query($conn,$sql);
}

if ($data) {
	header("location:view_vehicle.php");
	exit();
}
else{
	echo '<script>alert("Failed to delete.")</script>';
}

mysqli_close($conn);
?>
