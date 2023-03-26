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
		if($session_type != "Security") {
			header("Location: ../login.php");
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <?php include "../include/head.php";?>
    <title>ANPR - Database</title>
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
        <div class="navigation_links"><a href="view_vehicle.php" class="active_page"><i class="fa-solid fa-table"></i>Database</a></div>
        <div class="navigation_links drop_down_btn"><a href="#"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
            <div class="sub_menu">
                <div class="navigation_links"><a href="report.php"></i>Report</a></div>
                <div class="navigation_links"><a href="entry_log.php"></i>Entry Log</a></div>
                <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
                <div class="navigation_links"><a href="denied_access.php"></i>Denial Log</a></div>
            </div>
        <div class="navigation_links"><a href="analytic.php"><i class="fa fa-line-chart"></i>Analytics</a></div>
        <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>
        <div class="navigation_links" id="last_nav_link"><a href="../login.php" id="last_nav_link"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
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
             <div class="card-header">
				<div class="row">
					<div class="col-sm-2">Hide Column</div>
					<div class="col-sm-4">
						<select name="column_name" id="column_name" class="form-control selectpicker" data-icon-base="fas" data-tick-icon="fa fa-times" multiple>
							<option value="0">License Plate</option>
				            <option value="1">Tenant Lot Number</option>
				            <option value="2">Tenant Name</option>
				            <option value="3">Contact Number</option>
				            <option value="4">Brand</option>
                            <option value="5">Model</option>
                            <option value="6">Colour</option>
						</select>
					</div>
				</div>
			</div>
            <div class="table-responsive database">
            <table id="db_table" class="table table-striped table-bordered" style="width:100%;">
                <thead>
                    <tr>
                        <th>License Plate</th>
                        <th>Tenant Lot Number</th>
                        <th>Tenant Name</th>
                        <th>Contact Number</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Colour</th>
                    </tr>
                </thead>

                <?php

                   include "../include/config.php";
                    //echo "Connected successfully </br>";

                    //This SQL query retrieves information about all vehicles and their corresponding tenants by performing an inner join on the vehicle and tenant tables based on the tenantLotNumber column. 
                    //The query returns the vehicle ID, license plate number, tenant lot number, tenant name, tenant phone number, vehicle brand, model, colour, and active status for all vehicles associated with a tenant lot number.
                    $sql = "SELECT vehicle.vehicleID, vehicle.licensePlate, vehicle.tenantLotNumber, name, phoneNumber, vehicle.brand, vehicle.model, vehicle.colour, vehicle.isActive FROM vehicle JOIN tenant WHERE vehicle.tenantLotNumber = tenant.tenantLotNumber;";
                    $result = mysqli_query($conn, $sql);

                    if (!$result) {
                        echo '<script>alert("Empty Result!")</script>';
                    }

                    $sumprice = 0;

                    if (mysqli_num_rows($result)) {
                        // output data of each row
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr><td>".$row["licensePlate"]."</td><td>".$row["tenantLotNumber"]."</td><td>".$row["name"]."</td><td>".$row["phoneNumber"]."</td><td>".$row["brand"]."</td><td>".$row["model"].
                            "</td><td>".$row["colour"]."</td>";
                        }
                    } else {
                        echo '<script>alert("Empty Result!")</script>';
                    }


                    mysqli_close($conn);


                ?>

                </table>
            </div>
            </div>

    </section>
</div>

<div class="waves"></div>
</body>
</html>
