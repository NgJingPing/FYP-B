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

$vehicle = $_GET['vehicleID'];
#$del1 = "DELETE FROM entryLog WHERE licensePlate='$vehicle';";
#$del2 =  "DELETE FROM exitLog WHERE licensePlate='$vehicle';";
$del3 = "DELETE FROM vehicle WHERE vehicleID=$vehicle;";

$data = mysqli_query($conn,$del3);


if ($data) {
	header("location:view_vehicle.php");
	exit();
}
else{
	echo '<script>alert("Failed to delete.")</script>';
}

mysqli_close($conn);
?>
