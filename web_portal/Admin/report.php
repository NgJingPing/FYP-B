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
?>  

<!DOCTYPE HTML>
<html lang="en">

<head>
    <?php include "../include/head.php";?>
    <title>ANPR - Report</title>
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
        <div class="navigation_links"><a href="view_vehicle.php"><i class="fa-solid fa-table"></i>Database</a></div>
        <div class="navigation_links drop_down_btn"><a href="#" class="active_page"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
            <div class="sub_menu">
                <div class="navigation_links"><a href="report.php" class="active_page"></i>Report</a></div>
                <div class="navigation_links"><a href="entry_log.php"></i>Entry Log</a></div>
                <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
                <div class="navigation_links"><a href="denied_access.php"></i>Denial Log</a></div>
            </div>
        <div class="navigation_links"><a href="analytic.php"><i class="fa fa-line-chart"></i>Analytics</a></div>
        <?php 
        
        if($session_type == "Super Admin") {
            echo '<div class="navigation_links drop_down_btn"><a href="#"><i class="fa fa-users"></i>Management<i class="fa-solid fa-angle-right" style="margin-left: 0px; padding-left:8px;"></i></a></div>
            <div class="sub_menu">
                <div class="navigation_links"><a href="register_user.php"></i>Add User</a></div>
                <div class="navigation_links"><a href="manage_user.php"></i>View User</a></div>
            </div>';
        }
        ?>  
        <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>
        <div class="navigation_links" id="last_nav_link"><a href="../login.php" id="last_nav_link"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
    </div>
</div>
<script src="script/report_log.js"></script>
<!--Sidebar ends here-->

<div class="content-container">
    <header>
		<h1>Report</h1>
	</header>
<?php
    include "../include/config.php";
    $referenceID = $startdate = $enddate = "";
    $label = "";

    $count = $count2 = $count3 = 1;
    
	if(isset($_GET["label"])) {
		$label = $_GET["label"];
	}
    
    echo '
        <form method="post" action="" class="date_selector">
            <label class="date_selector_label">Start Date</label> <input type="date" id="start" name="start" class="date_input">
            <label class="date_selector_label">End date</label> <input type="date" id="end" name="end" class="date_input">
            <button type="submit" id="submit" class="button_submit" name ="submit" value="Submit">Search</button>
        </form>';

    if(isset($_POST["submit"])) {
        if(empty($_POST["start"])) {
            $startdate = date("Y-m-d");
            $startdate = new DateTime($startdate);
        } else {
            $startdate = new DateTime($_POST["start"]);
        }

        if(empty($_POST["end"])) {
            $enddate = date("Y-m-d");
            $enddate = new DateTime($enddate);
        } else {
            $enddate = new DateTime($_POST["end"]);
        }
        $enddate->modify('+1 day');
        $startdate = $startdate->format("Y-m-d");
        $enddate = $enddate->format("Y-m-d");

        $label = "";

        // This SQL query retrieves data from the entrylog and vehicle tables, joining them on the vehicleID column, and returning the reference ID, license plate number, entry time, and tenant lot number for all vehicles in the entry log between the selected dates. 
        $myquery = "SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID WHERE entrylog.entryTime BETWEEN '$startdate' AND '$enddate';";
	    $result = $conn->query($myquery);
        // This SQL query retrieves data from the exitlog and vehicle tables, joining them on the vehicleID column, and returning the reference ID, license plate number, exit time, and tenant lot number for all vehicles in the exit log between the selected dates. 
        $myquery2 = "SELECT exitlog.referenceID, vehicle.licensePlate, exitlog.exitTime, vehicle.tenantLotNumber FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID WHERE exitlog.exitTime BETWEEN '$startdate' AND '$enddate';";
	    $result2 = $conn->query($myquery2);
        // This SQL query retrieves data from the deniedAccess between the selected dates.
        $myquery3 = "SELECT * FROM deniedAccess WHERE deniedTime BETWEEN '$startdate' AND '$enddate';";
        $result3 = $conn->query($myquery3);
        
        
        echo '<h2 class="report_table_name">Entry Log</h2>';
        echo '<div class="log_container"> 
                <div class="card-header">
				    <div class="row">
					    <div class="col-sm-2">Hide Column</div>
					    <div class="col-sm-4">
						    <select name="column_name" id="column_name_entry" class="form-control selectpicker" data-icon-base="fas" data-tick-icon="fa fa-times" multiple>
							    <option value="0">No</option>
				                <option value="1">Timestamp</option>
				                <option value="2">License Plate Number</option>
				                <option value="3">Tenant Lot Number</option>
				                <option value="4">Actions</option>
						    </select>
					    </div>
				    </div>
			    </div>
                <div class="table-responsive entry">
                <table id="log_table" class="table table-striped table-bordered"  style="width:100%;">  
			    <thead>  
                    <tr>
                        <th>No</td>  
                        <th>Timestamp</th>  
                        <th>License Plate Number</th>  
                        <th>Tenant Lot Number</th>  
                        <th>Actions</th>  
                    </tr>  
                </thead>';

        while($row = mysqli_fetch_array($result))  
        {  
            //Display the queried data into table form
            $date = $row['entryTime'];
            $dateObject = new DateTime($date);
            $format = $dateObject->format('d M Y h:i A');
            echo '  
            <tr>  
                <td>'.$count.'</td>  
                <td>'.$format.'</td>  
                <td>'.$row["licensePlate"].'</td>  
                <td>'.$row["tenantLotNumber"].'</td>  
                <td><a href="entry_log_details.php?referenceID='.$row["referenceID"].'"><i class="fa fa-external-link"></i></a></td> 
            </tr>'; 
            $count += 1;
        } 
        echo '</table></div></div>';  


        echo '<h2 class="report_table_name">Exit Log</h2>';
        echo '<div class="log_container"> 
                <div class="card-header">
				    <div class="row">
					    <div class="col-sm-2">Hide Column</div>
					    <div class="col-sm-4">
						    <select name="column_name" id="column_name_exit" class="form-control selectpicker" data-icon-base="fas" data-tick-icon="fa fa-times" multiple>
							    <option value="0">No</option>
				                <option value="1">Timestamp</option>
				                <option value="2">License Plate Number</option>
				                <option value="3">Tenant Lot Number</option>
				                <option value="4">Actions</option>
						    </select>
					    </div>
				    </div>
			    </div>
                <div class="table-responsive exit">
                <table id="log_table2" class="table table-striped table-bordered"  style="width:100%;">  
			    <thead>  
                    <tr>
                        <th>No</th>  
                        <th>Timestamp</th>  
                        <th>License Plate Number</th>  
                        <th>Tenant Lot Number</th>  
                        <th>Actions</th>  
                    </tr>  
                </thead>';

        while($row = mysqli_fetch_array($result2))  
        {  
            //Display the queried data into table form
            $date = $row['exitTime'];
            $dateObject = new DateTime($date);
            $format = $dateObject->format('d M Y h:i A');
            echo '  
            <tr>  
                <td>'.$count2.'</td>  
                <td>'.$format.'</td>  
                <td>'.$row["licensePlate"].'</td>  
                <td>'.$row["tenantLotNumber"].'</td>  
                <td><a href="exit_log_details.php?referenceID='.$row["referenceID"].'"><i class="fa fa-external-link"></i></a></td> 
            </tr>';   
            $count2 += 1;
        }
        echo '</table></div></div>';  

        echo '<h2 class="report_table_name">Denied Access Log</h2>';
        echo '<div class="log_container"> 
                <div class="card-header">
				    <div class="row">
					    <div class="col-sm-2">Hide Column</div>
					    <div class="col-sm-4">
						    <select name="column_name" id="column_name_denied" class="form-control selectpicker" data-icon-base="fas" data-tick-icon="fa fa-times" multiple>
							    <option value="0">No</option>
				                <option value="1">Timestamp</option>
				                <option value="2">License Plate Number</option>
				                <option value="3">Actions</option>
						    </select>
					    </div>
				    </div>
			    </div>
                <div class="table-responsive denied">
                <table id="log_table3" class="table table-striped table-bordered"  style="width:100%;">   
			    <thead>  
                    <tr>
                        <th>No</th>  
                        <th>Timestamp</th>  
                        <th>License Plate Number</th>  
                        <th>Actions</th>  
                    </tr>  
                </thead>';

        while($row = mysqli_fetch_array($result3))  
        {  
            //Display the queried data into table form
            $date = $row['deniedTime'];
            $dateObject = new DateTime($date);
            $format = $dateObject->format('d M Y h:i A');
            echo '  
            <tr>  
                <td>'.$count3.'</td>  
                <td>'.$format.'</td>  
                <td>'.$row["licensePlate"].'</td>  
                    <td><a href="denied_details.php?referenceID='.$row["referenceID"].'"><i class="fa fa-external-link"></i></a></td> 
            </tr>';  
            $count3 += 1;
        }
        echo '</table></div></div>';  
    }

    //show the record based on the selected bar/point from the analytic page
    if($label != "") {
        if(strlen($label) == 10){
            // This SQL query retrieves data from the entrylog and vehicle tables, joining them on the vehicleID column, and returning the reference ID, license plate number, entry time, and tenant lot number for all vehicles in the entry log based on the selected dates 
            $myquery = "SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID WHERE DATE(entrylog.entryTime) = '$label';";
	        $result = $conn->query($myquery);
            // This SQL query retrieves data from the exitlog and vehicle tables, joining them on the vehicleID column, and returning the reference ID, license plate number, exit time, and tenant lot number for all vehicles in the exit log based on the selected dates.
            $myquery2 = "SELECT exitlog.referenceID, vehicle.licensePlate, exitlog.exitTime, vehicle.tenantLotNumber FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID WHERE DATE(exitlog.exitTime) = '$label';";
	        $result2 = $conn->query($myquery2);
            // This SQL query retrieves data from the deniedAccess based on the selected dates.
            $myquery3 = "SELECT * FROM deniedAccess WHERE DATE(deniedTime) = '$label';";
            $result3 = $conn->query($myquery3);
        }

        if(strlen($label) == 4) {
            // This SQL query retrieves data from the entrylog and vehicle tables, joining them on the vehicleID column, and returning the reference ID, license plate number, entry time, and tenant lot number for all vehicles in the entry log based on the selected dates 
            $myquery = "SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID WHERE YEAR(entrylog.entryTime) = '$label';";
	        $result = $conn->query($myquery);
            // This SQL query retrieves data from the exitlog and vehicle tables, joining them on the vehicleID column, and returning the reference ID, license plate number, exit time, and tenant lot number for all vehicles in the exit log based on the selected dates.
            $myquery2 = "SELECT exitlog.referenceID, vehicle.licensePlate, exitlog.exitTime, vehicle.tenantLotNumber FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID WHERE YEAR(exitlog.exitTime) = '$label';";
	        $result2 = $conn->query($myquery2);
            // This SQL query retrieves data from the deniedAccess based on the selected dates.
            $myquery3 = "SELECT * FROM deniedAccess WHERE YEAR(deniedTime) = '$label';";
            $result3 = $conn->query($myquery3);
        }

        if(strlen($label) == 7) {
            $month = substr($label, 5, 2);
            $year = substr($label, 0, 4);
            // This SQL query retrieves data from the entrylog and vehicle tables, joining them on the vehicleID column, and returning the reference ID, license plate number, entry time, and tenant lot number for all vehicles in the entry log based on the selected dates 
            $myquery = "SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID WHERE MONTH(entrylog.entryTime) = '$month' AND YEAR(entrylog.entryTime) =  '$year';";
	        $result = $conn->query($myquery);
            // This SQL query retrieves data from the exitlog and vehicle tables, joining them on the vehicleID column, and returning the reference ID, license plate number, exit time, and tenant lot number for all vehicles in the exit log based on the selected dates.
            $myquery2 = "SELECT exitlog.referenceID, vehicle.licensePlate, exitlog.exitTime, vehicle.tenantLotNumber FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID WHERE MONTH(exitlog.exitTime) = '$month' AND YEAR(exitlog.exitTime) = '$year';";
	        $result2 = $conn->query($myquery2);
            // This SQL query retrieves data from the deniedAccess based on the selected dates.
            $myquery3 = "SELECT * FROM deniedAccess WHERE MONTH(deniedTime) = '$month' AND YEAR(deniedTime) = '$year';";
            $result3 = $conn->query($myquery3);
        }

        if(strlen($label) >= 15) {
            $z = substr($label, -4);
            $y = substr($label, 0, 6);
            $y = str_replace("-", "", $y);
            $x = $y." ".$z;
            $x = strtotime($x);
            $format = 'Y-m-d';
            $week = date($format, $x);

            // This SQL query retrieves data from the entrylog and vehicle tables, joining them on the vehicleID column, and returning the reference ID, license plate number, entry time, and tenant lot number for all vehicles in the entry log based on the selected dates 
            $myquery = "SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID WHERE WEEK(entrylog.entryTime) = WEEK('$week') AND YEAR(entrylog.entryTime) =  YEAR('$week');";
	        $result = $conn->query($myquery);
            // This SQL query retrieves data from the exitlog and vehicle tables, joining them on the vehicleID column, and returning the reference ID, license plate number, exit time, and tenant lot number for all vehicles in the exit log based on the selected dates.
            $myquery2 = "SELECT exitlog.referenceID, vehicle.licensePlate, exitlog.exitTime, vehicle.tenantLotNumber FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID WHERE WEEK(exitlog.exitTime) = WEEK('$week') AND YEAR(exitlog.exitTime) = YEAR('$week');";
	        $result2 = $conn->query($myquery2);
            // This SQL query retrieves data from the deniedAccess based on the selected dates.
            $myquery3 = "SELECT * FROM deniedAccess WHERE WEEK(deniedTime) = WEEK('$week') AND YEAR(deniedTime) = YEAR('$week');";
            $result3 = $conn->query($myquery3);
        }


        
        echo '<h2 class="report_table_name">Entry Log</h2>';
        echo '<div class="log_container"> 
                <div class="card-header">
				    <div class="row">
					    <div class="col-sm-2">Hide Column</div>
					    <div class="col-sm-4">
						    <select name="column_name" id="column_name_entry" class="form-control selectpicker" data-icon-base="fas" data-tick-icon="fa fa-times" multiple>
							    <option value="0">No</option>
				                <option value="1">Timestamp</option>
				                <option value="2">License Plate Number</option>
				                <option value="3">Tenant Lot Number</option>
				                <option value="4">Actions</option>
						    </select>
					    </div>
				    </div>
			    </div>
                <div class="table-responsive entry">
                <table id="log_table" class="table table-striped table-bordered"  style="width:100%;">  
			    <thead>  
                    <tr>
                        <th>No</td>  
                        <th>Timestamp</th>  
                        <th>License Plate Number</th>  
                        <th>Tenant Lot Number</th>  
                        <th>Actions</th>  
                    </tr>  
                </thead>';

        while($row = mysqli_fetch_array($result))  
        {  
            //Display the queried data into table form
            $date = $row['entryTime'];
            $dateObject = new DateTime($date);
            $format = $dateObject->format('d M Y h:i A');
            echo '  
            <tr>  
                <td>'.$count.'</td>  
                <td>'.$format.'</td>  
                <td>'.$row["licensePlate"].'</td>  
                <td>'.$row["tenantLotNumber"].'</td>  
                <td><a href="entry_log_details.php?referenceID='.$row["referenceID"].'"><i class="fa fa-external-link"></i></a></td> 
            </tr>'; 
            $count += 1;
        } 
        echo '</table></div></div>';  


        echo '<h2 class="report_table_name">Exit Log</h2>';
        echo '<div class="log_container"> 
                <div class="card-header">
				    <div class="row">
					    <div class="col-sm-2">Hide Column</div>
					    <div class="col-sm-4">
						    <select name="column_name" id="column_name_exit" class="form-control selectpicker" data-icon-base="fas" data-tick-icon="fa fa-times" multiple>
							    <option value="0">No</option>
				                <option value="1">Timestamp</option>
				                <option value="2">License Plate Number</option>
				                <option value="3">Tenant Lot Number</option>
				                <option value="4">Actions</option>
						    </select>
					    </div>
				    </div>
			    </div>
                <div class="table-responsive exit">
                <table id="log_table2" class="table table-striped table-bordered"  style="width:100%;">  
			    <thead>  
                    <tr>
                        <th>No</th>  
                        <th>Timestamp</th>  
                        <th>License Plate Number</th>  
                        <th>Tenant Lot Number</th>  
                        <th>Actions</th>  
                    </tr>  
                </thead>';

        while($row = mysqli_fetch_array($result2))  
        {  
            //Display the queried data into table form
            $date = $row['exitTime'];
            $dateObject = new DateTime($date);
            $format = $dateObject->format('d M Y h:i A');
            echo '  
            <tr>  
                <td>'.$count2.'</td>  
                <td>'.$format.'</td>  
                <td>'.$row["licensePlate"].'</td>  
                <td>'.$row["tenantLotNumber"].'</td>  
                <td><a href="exit_log_details.php?referenceID='.$row["referenceID"].'"><i class="fa fa-external-link"></i></a></td> 
            </tr>';   
            $count2 += 1;
        }
        echo '</table></div></div>';  

        echo '<h2 class="report_table_name">Denied Access Log</h2>';
        echo '<div class="log_container"> 
                <div class="card-header">
				    <div class="row">
					    <div class="col-sm-2">Hide Column</div>
					    <div class="col-sm-4">
						    <select name="column_name" id="column_name_denied" class="form-control selectpicker" data-icon-base="fas" data-tick-icon="fa fa-times" multiple>
							    <option value="0">No</option>
				                <option value="1">Timestamp</option>
				                <option value="2">License Plate Number</option>
				                <option value="3">Actions</option>
						    </select>
					    </div>
				    </div>
			    </div>
                <div class="table-responsive denied">
                <table id="log_table3" class="table table-striped table-bordered"  style="width:100%;">   
			    <thead>  
                    <tr>
                        <th>No</th>  
                        <th>Timestamp</th>  
                        <th>License Plate Number</th>  
                        <th>Actions</th>  
                    </tr>  
                </thead>';

        while($row = mysqli_fetch_array($result3))  
        {  
            //Display the queried data into table form
            $date = $row['deniedTime'];
            $dateObject = new DateTime($date);
            $format = $dateObject->format('d M Y h:i A');
            echo '  
            <tr>  
                <td>'.$count3.'</td>  
                <td>'.$format.'</td>  
                <td>'.$row["licensePlate"].'</td>  
                    <td><a href="denied_details.php?referenceID='.$row["referenceID"].'"><i class="fa fa-external-link"></i></a></td> 
            </tr>';  
            $count3 += 1;
        }
        echo '</table></div></div>';  
    }
   
?>
</div>

</body>
</html>