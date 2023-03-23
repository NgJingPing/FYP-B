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

    $dbname = "anprdb";

    // Create connection
    //The mysqli_connect() function attempts to open a connection to the MySQL Server
    //running on host which can be either a host name or an IP address.
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // sql to create Enquiry table
    $sql1 = "CREATE TABLE tenant (
        tenantLotNumber VARCHAR(6) PRIMARY KEY NOT NULL,
        name VARCHAR(256) NOT NULL,
        phoneNumber VARCHAR(10) NOT NULL
        );
    ";

    $sql2 = "CREATE TABLE vehicle (
        vehicleID INT(6) PRIMARY KEY AUTO_INCREMENT NOT NULL,
        tenantLotNumber VARCHAR(6) NOT NULL, 
        licensePlate VARCHAR(20) NOT NULL,
        brand VARCHAR(20) NOT NULL,
        model VARCHAR(20) NOT NULL,
        colour VARCHAR(20) NOT NULL,
        isActive BOOLEAN NOT NULL,
        FOREIGN KEY(tenantLotNumber) REFERENCES tenant(tenantLotNumber)
	    );
    ";

    $sql3 = "CREATE TABLE entryLog (
        referenceID INT(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
        vehicleID INT(6) NOT NULL, 
        entryTime DATETIME NOT NULL,
        image VARCHAR(100) NOT NULL,
        image_2 VARCHAR(100) NOT NULL,
        FOREIGN KEY(vehicleID) REFERENCES vehicle(vehicleID)
       );
    ";

    $sql4 = "CREATE TABLE exitLog (
        referenceID INT(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
        vehicleID INT(6) NOT NULL, 
        exitTime DATETIME NOT NULL,
        image VARCHAR(100) NOT NULL,
        image_2 VARCHAR(100) NOT NULL,
        FOREIGN KEY(vehicleID) REFERENCES vehicle(vehicleID)
       );
    ";

    $sql5 = "CREATE TABLE deniedAccess(
        referenceID INT(10) PRIMARY KEY AUTO_INCREMENT NOT NULL,
        licensePlate VARCHAR(20) NOT NULL, 
        deniedTime DATETIME NOT NULL,
        image VARCHAR(100) NOT NULL,
        image_2 VARCHAR(100) NOT NULL
       );
    ";

    $sql6 = "CREATE TABLE users (
        userID INT(4) PRIMARY KEY AUTO_INCREMENT NOT NULL,
        email VARCHAR(256) NOT NULL,
        password VARCHAR(256) NOT NULL,
        role INT(1) NOT NULL,
        isAdvanced BOOLEAN NOT NULL,
        code MEDIUMINT(50) NULL
        );
    ";

    $tables = [$sql1, $sql2, $sql3, $sql4, $sql5, $sql6];

    foreach($tables as $sql){
	    if (mysqli_query($conn, $sql)) {
		    //echo "Table MyDetails created successfully";
	     } else {
		    //echo "Error creating table: " . mysqli_error($conn);
	     }
    }

    # Admin
    $password0 = hash("sha256", "naim000");
    $password1 = hash("sha256", "naim001");
    $password2 = hash("sha256", "naim002");
    $sql1 = "INSERT INTO users (email, password, role, isAdvanced)
    VALUES ('naim000@naim.com.my', '$password0', 1, TRUE)";
    $sql2 = "INSERT INTO users (email, password, role, isAdvanced)
    VALUES ('naim001@naim.com.my', '$password1', 1, FALSE)";
    $sql3 = "INSERT INTO users (email, password, role, isAdvanced)
    VALUES ('naim002@naim.com.my', '$password2', 1, FALSE)";

    $admin_datas = [$sql1, $sql2, $sql3];

    foreach($admin_datas as $sql){
	    if (mysqli_query($conn, $sql)) {
		    //echo "New record created successfully";
	     } else {
		    //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	     }
    }

    #Security
    $password0 = hash("sha256", "naim100");
    $password1 = hash("sha256", "naim101");
    $password2 = hash("sha256", "naim102");
    $sql1 = "INSERT INTO users (email, password, role, isAdvanced)
    VALUES ('naim100@naim.com.my', '$password0', 2, FALSE)";
    $sql2 = "INSERT INTO users (email, password, role, isAdvanced)
    VALUES ('naim101@naim.com.my', '$password1', 2, FALSE)";
    $sql3 = "INSERT INTO users (email, password, role, isAdvanced)
    VALUES ('naim102@naim.com.my', '$password2', 2, FALSE)";

    $security_datas = [$sql1, $sql2, $sql3];

    foreach($security_datas as $sql){
	    if (mysqli_query($conn, $sql)) {
		    //echo "New record created successfully";
	     } else {
		    //echo "Error: " . $sql . "<br>" . mysqli_error($conn);
	     }
     }


    mysqli_close($conn);


?>