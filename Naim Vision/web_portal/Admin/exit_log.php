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
    <title>ANPR - Exit Log</title>
</head>

<?php
    include "../include/config.php";
    // This SQL query retrieves data from the exitlog and vehicle tables, joining them on the vehicleID column, and returning the reference ID, license plate number, exit time, and tenant lot number for all vehicles in the entry log. 
    //The results are sorted in descending order based on the reference ID.
	$myquery = "SELECT exitlog.referenceID, vehicle.licensePlate, exitlog.exitTime, vehicle.tenantLotNumber FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID ORDER BY referenceID DESC; ";
	$result = $conn->query($myquery);
?>

<body>
<!--Sidebar starts here-->
<?php 
    // Give active page  
    $page = 'Log';
    $subpage = 'Exit Log';
    // Give user role
    if($session_type == "Super Admin") {
        $role = "Super admin"; include "../include/navbar.php";
    }
    else{
        $role = "Admin"; include "../include/navbar.php";
    }
?> 
<script src="script/log.js"></script>
<!--Sidebar ends here-->

<div class="content-container">
    <header>
		<h1>Exit Log</h1>
	</header>

    <section>
	<div class="log_container">
        <div class="card-header">
			<div class="row">
				<div class="col-sm-2">Hide Column</div>
				<div class="col-sm-4">
					<select name="column_name" id="column_name_exit" class="form-control selectpicker" data-icon-base="fas" data-tick-icon="fa fa-times" multiple>
						<option value="0">Reference ID</option>
				        <option value="1">Timestamp</option>
				        <option value="2">License Plate Number</option>
				        <option value="3">Tenant Lot Number</option>
				        <option value="4">Actions</option>
					</select>
				</div>
			</div>
		</div>
        <div class="table-responsive exit" style="overflow-x: hidden;">
            <table id="exit_table" class="table table-striped table-bordered display responsive nowrap"  style="width:100%;">  
			<thead>  
                <tr>  
                    <th>Reference ID</th>  
                    <th>Timestamp</th>  
                    <th>License Plate Number</th>  
                    <th>Tenant Lot Number</th>  
                    <th>Actions</th>  
                </tr>  
            </thead>  

			<?php
                if($result){
                    while($row = mysqli_fetch_array($result))  
                    {  
                        $date = $row['exitTime'];
                        $dateObject = new DateTime($date);
                        $format = $dateObject->format('d M Y h:i A');
                        echo '  
                        <tr>  
                            <td>'.$row["referenceID"].'</td>  
                            <td>'.$format.'</td>  
                            <td>'.$row["licensePlate"].'</td>  
                            <td>'.$row["tenantLotNumber"].'</td>  
                            <td><a href="exit_log_details.php?referenceID='.$row["referenceID"].'"><i class="fa fa-external-link"></i></a></td> 
                        </tr>  
                        ';  
                    } 
                }
			?>
		</table>
	</div>
    </div>
    </section>
            </div>
            <div class="waves"></div>
</body>
</html>
