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
		if($session_type != "Admin") {
			header("Location: ../login.php");
		}
	}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "utf-8">
	<meta name = "author" content = "Ng Jing Ping">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ANPR - Database</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>  
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>            
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/2ffaabbca0.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Hairline&display=swap" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="style/style.css">
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
  <div class="navigation_links"><a href="register_vehicle.php"><i class="fa-solid fa-person-circle-plus"></i>Registration</a></div>
  <div class="navigation_links drop_down_btn"><a href="#"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
    <div class="sub_menu">
        <div class="navigation_links"><a href="report.php"></i>Report</a></div>
        <div class="navigation_links"><a href="entry_log.php"></i>Entry Log</a></div>
        <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
        <div class="navigation_links"><a href="denied_access.php"></i>Denial Log</a></div>
    </div>
  
  <div class="navigation_links"><a href="view_vehicle.php" class="active_page"><i class="fa-solid fa-table"></i>Database</a></div>
  <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>
  <div class="navigation_links"><a href="../login.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
  
</div>
</div>
</div>
<script src="script/log.js"></script>
<!--Sidebar ends here-->
<div class="content-container">
    <header>
        <h1>Database</h1>
    </header>

    <section>
        <div class="log_container">
            <table id="log_table" class="table table-borderless">  
                <thead>
                    <tr>
                        <th>License Plate</th>
                        <th>Tenant Lot Number</th>
                        <th>Tenant Name</th>
                        <th>Contact Number</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Colour</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>

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
                    //echo "Connected successfully </br>";

                    $sql = "SELECT vehicle.vehicleID, vehicle.licensePlate, vehicle.tenantLotNumber, name, phoneNumber, vehicle.brand, vehicle.model, vehicle.colour, vehicle.isActive FROM vehicle JOIN tenant WHERE vehicle.tenantLotNumber = tenant.tenantLotNumber;";
                    $result = mysqli_query($conn, $sql);

                    if (!$result) {
                        echo '<script>alert("Empty Result!")</script>';
                    }

                    $sumprice = 0;
                    $active = "";
                    if (mysqli_num_rows($result)) {
                        // output data of each row
                        while($row = mysqli_fetch_assoc($result)) {
                            if($row['isActive'] == 1) {
                                $active = "True";
                            } else {
                                $active = "False";
                            }
                            echo "<tr><td>".$row["licensePlate"]."</td><td>".$row["tenantLotNumber"]."</td><td>".$row["name"]."</td><td>".$row["phoneNumber"]."</td><td>".$row["brand"]."</td><td>".$row["model"].
                            "</td><td>".$row["colour"]."</td><td>".$active."</td><td><span><a href='edit_vehicle.php?vehicleID=$row[vehicleID]'><i class='fa-solid fa-pen-to-square'></i></a>
                            </span>";
                            $id = $row['vehicleID'];
                            $sql2 = "SELECT * FROM entryLog WHERE vehicleID = $id;";
                            $result2 = mysqli_query($conn, $sql2);
                            if(mysqli_num_rows($result2) == 0){
                                echo "<span><a href='remove_vehicle.php?vehicleID=$row[vehicleID]'><i class='fa-solid fa-trash-can'></i></a>
                            </span>";
                            }
                            
                            echo "</td></tr>";
                        }
                    } else {
                        echo '<script>alert("Empty Result!")</script>';
                    }


                    mysqli_close($conn);
            
            
                ?>

                </table>
            </div>

    </section>
</div>
<div class="waves"><p>&</p></div>
</body>
</html>
