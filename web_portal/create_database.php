<!-- <h1>Connect to your phpMyAdmin</h1> -->
<?php
// set the servername,username and password
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
//The mysqli_connect() function attempts to open a connection to the MySQL Server 
//running on host which can be either a host name or an IP address. 
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
	//The die() function is an alias of the exit() function.
    die("Connection failed: " . mysqli_connect_error()); 
}
//echo "Connected successfully </br>";


// Create database
//mysqli_query() function performs a query against a database.
$sql = "CREATE DATABASE anprdb";
if (mysqli_query($conn, $sql)) {
    //echo "Database created successfully</br>";
} else {
    //echo "Error creating database: " . mysqli_error($conn) ."</br>";
}

mysqli_close($conn);

header("location:create_table.php");
exit();

?>