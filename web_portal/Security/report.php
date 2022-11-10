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
    <meta charset = "utf-8">
	<meta name = "author" content = "Sabrina Tan">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ANPR - Report</title>

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
  <div class="navigation_links drop_down_btn"><a href="#" class="active_page"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
    <div class="sub_menu">
        <div class="navigation_links"><a href="report.php" class="active_page"></i>Report</a></div>
        <div class="navigation_links"><a href="entry_log.php" ></i>Entry Log</a></div>
        <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
        <div class="navigation_links"><a href="denied_access.php"></i>Denial Log</a></div>
    </div>
  
  <div class="navigation_links"><a href="view_vehicle.php"><i class="fa-solid fa-table"></i>Database</a></div>
  <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>
  <div class="navigation_links"><a href="../login.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
  
</div>
</div>
</div>
<script src="script/log.js"></script>
<!--Sidebar ends here-->
<div class="content-container">
    <header>
		<h1>Report</h1>
	</header>
<?php
    $servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "anprdb";
    $referenceID = $startdate = $enddate = "";

    $count = $count2 = $count3 = 1;

	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if($conn->connect_error){
		die("Connection Failed: " . $conn->connect_error);
	}
    
    echo '
        <form method="post" action="" class="date_selector">
            <label class="date_selector_label">Start Date</label> <input type="date" id="start" name="start" class="date_input">
            <label class="date_selector_label">End date</label> <input type="date" id="end" name="end" class="date_input">
            <button type="submit" class="button_submit" name ="submit" value="Submit">Search</button>
        </form>';

    if(isset($_POST["submit"])) {
        if(empty($_POST["start"])) {
            $startdate = date("Y-m-d");
        } else {
            $startdate = $_POST["start"];
        }

        if(empty($_POST["end"])) {
            $enddate = date("Y-m-d");
        } else {
            $enddate = $_POST["end"];
        }

        $myquery = "SELECT entrylog.referenceID, vehicle.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.vehicleID = vehicle.vehicleID WHERE entrylog.entryTime BETWEEN '$startdate' AND '$enddate';";
	    $result = $conn->query($myquery);

        $myquery2 = "SELECT exitlog.referenceID, vehicle.licensePlate, exitlog.exitTime, vehicle.tenantLotNumber FROM exitlog INNER JOIN vehicle ON exitlog.vehicleID = vehicle.vehicleID WHERE exitlog.exitTime BETWEEN '$startdate' AND '$enddate';";
	    $result2 = $conn->query($myquery2);

        $myquery3 = "SELECT * FROM deniedAccess WHERE deniedTime BETWEEN '$startdate' AND '$enddate';";
        $result3 = $conn->query($myquery3);
        
        
        echo '<h2 class="report_table_name">Entry Log</h2>';
        echo '<div class="log_container">
                <table id="log_table" class="table table-borderless">  
			    <thead>  
                    <tr>
                        <td>No</td>  
                        <td>Timestamp</td>  
                        <td>License Plate Number</td>  
                        <td>Tenant Lot Number</td>  
                        <td>Actions</td>  
                    </tr>  
                </thead>';

        while($row = mysqli_fetch_array($result))  
        {  
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
        echo '</table></div>';  


        echo '<h2 class="report_table_name">Exit Log</h2>';
        echo '<div class="log_container">
                <table id="log_table2" class="table table-borderless">  
			    <thead>  
                    <tr>
                        <td>No</td>  
                        <td>Timestamp</td>  
                        <td>License Plate Number</td>  
                        <td>Tenant Lot Number</td>  
                        <td>Actions</td>  
                    </tr>  
                </thead>';

        while($row = mysqli_fetch_array($result2))  
        {  
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
        echo '</table></div>';  

        echo '<h2 class="report_table_name">Denied Access Log</h2>';
        echo '<div class="log_container">
                <table id="log_table3" class="table table-borderless">   
			    <thead>  
                    <tr>
                        <td>No</td>  
                        <td>Timestamp</td>  
                        <td>License Plate Number</td>  
                        <td>Actions</td>  
                    </tr>  
                </thead>';

        while($row = mysqli_fetch_array($result3))  
        {  
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
        echo '</table></div>';  
    }
   
?>
</div>
</body>
</html>