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
		if($session_type != "Super Admin") {
			header("Location: ../login.php");
		}
	}

	if(isset($_GET['userID'])) {
		$id = $_GET['userID'];

		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "anprdb";

		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if($conn->connect_error){
			die("Connection Failed: " . $conn->connect_error);
		}

		$query = "DELETE FROM users WHERE userID='$id'";
		$query_run = mysqli_query($conn, $query);

		if($query_run)
		{
			echo '<script> alert("Data Deleted"); </script>';
			header("Location:manage_user.php");
		}
		else
		{
			echo '<script> alert("Data Not Deleted"); </script>';
		}
	}
?> 




