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

    // Check connection
    if (!$conn) {
        //The die() function is an alias of the exit() function.
        die("Connection failed: " . mysqli_connect_error()); 
    }
?>