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

<?php
    $servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "anprdb";
    $referenceID = "";

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if($conn->connect_error){
		die("Connection Failed: " . $conn->connect_error);
	}
?> 
   
    <?php
    $entrylogquery = "SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID WHERE DATE(entryTime) = CURDATE()";
	$result = $conn->query($entrylogquery);
    ?>
    <div class="dashboard_logs">
        <div class="dashboard_logs_container">
        <h1>Recent Entries</h1>
		<table id="entry_log_table" class="table table-borderless">  
			<thead>  
                <tr>  
                    <td>Timestamp</td>  
                    <td>License Plate Number</td>  
                    <td>Tenant Lot Number</td>  
                </tr>  
            </thead>  

			<?php
				if($result){
                    while($row = mysqli_fetch_array($result))  
                    {
                        $date = $row['entryTime'];
                        $dateObject = new DateTime($date);
                        $format = $dateObject->format('d M Y h:i A');
                        echo '  
                        <tr>  
                            <td>'.$format.'</td>  
                            <td>'.$row["licensePlate"].'</td>  
                            <td>'.$row["tenantLotNumber"].'</td>  
                        </tr>  
                        ';  
                    } 
                }
			?>
		</table>
            </div>

<?php

	$exitlogquery = "SELECT exitlog.referenceID, vehicle.licensePlate, exitlog.exitTime, vehicle.tenantLotNumber FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID WHERE DATE(exitTime) = CURDATE()";
	$result = $conn->query($exitlogquery);
?>
        <div class="dashboard_logs_container">
        <h1>Recent Exits</h1>
		<table id="exit_log_table" class="table table-borderless">  
			<thead>  
                <tr>  
                    <td>Timestamp</td>  
                    <td>License Plate Number</td>  
                    <td>Tenant Lot Number</td>  
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
                            <td>'.$format.'</td>  
                            <td>'.$row["licensePlate"].'</td>  
                            <td>'.$row["tenantLotNumber"].'</td>  
                        </tr>  
                        ';  
                    } 
                }
			?>
		</table>
            </div>
	</div>
