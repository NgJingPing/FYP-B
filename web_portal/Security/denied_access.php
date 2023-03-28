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

<!DOCTYPE HTML>
<html lang="en">

<head>
    <?php include "../include/head.php";?>
	<title>ANPR - Denied Access Log</title>
</head>

<!-- Returns every data from deniedAccess table from database-->
<?php
    include "../include/config.php";

	$myquery = "SELECT * FROM deniedAccess ORDER BY referenceID DESC; ";
	$result = $conn->query($myquery);
?>
<body>
<!--Sidebar starts here-->
<?php 
    // Give active page  
    $page = 'Log';
    $subpage = 'Denial Log';
    // Give user role
    $role = "Security"; include "../include/navbar.php";
?> 
<script src="script/log.js"></script>
<!--Sidebar ends here-->
    <div class="content-container">
    <header>
		<h1>Denied Access Log</h1>
	</header>

    <section>
	<div class="log_container">
        <div class="card-header">
			<div class="row">
				<div class="col-sm-2">Hide Column</div>
				<div class="col-sm-4">
					<select name="column_name" id="column_name_denied" class="form-control selectpicker" data-icon-base="fas" data-tick-icon="fa fa-times" multiple>
						<option value="0">Reference ID</option>
				        <option value="1">Timestamp</option>
				        <option value="2">License Plate Number</option>
				        <option value="3">Actions</option>
					</select>
				</div>
			</div>
		</div>
        <div class="table-responsive denied">
        <table id="denied_table" class="table table-striped table-bordered"  style="width:100%;">   
			<thead>  
                <tr>  
                    <th>Reference ID</th>  
                    <th>Timestamp</th>  
                    <th>License Plate Number</th>  
                    <th>Actions</th>  
                </tr>  
            </thead>  
            <!-- Display the queried data into table form -->
			<?php
                if($result){
                    while($row = mysqli_fetch_array($result))  
                    {  
                        $date = $row['deniedTime'];
                        $dateObject = new DateTime($date);
                        $format = $dateObject->format('d M, Y h:i A');
                        echo '  
                        <tr>  
                            <td>'.$row["referenceID"].'</td>  
                            <td>'.$format.'</td>  
                            <td>'.$row["licensePlate"].'</td>  
                            <td><a href="denied_details.php?referenceID='.$row["referenceID"].'"><i class="fa fa-external-link"></i></a></td> 
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
