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

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset = "utf-8">
	<meta name = "autor" content = "Irwan Ngo">
	<title>ANPR - Dashboard</title>

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
  <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div> 
  <div class="logo_tail"><span>ANPR</span></div> 
  </div>
  <div class="navigation_links_container">

  <div class="navigation_links"><a href="dashboard.php" class="active_page"><i class="fa-solid fa-house"></i>Dashboard</a></div>
  <div class="navigation_links"><a href="register_vehicle.php"><i class="fa-solid fa-person-circle-plus"></i>Registration</a></div>
  <div class="navigation_links drop_down_btn"><a href="#"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
    <div class="sub_menu">
        <div class="navigation_links"><a href="entry_log.php"></i>Entry Log</a></div>
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
<div class="content-container">
    <header>
    <h1>Dashboard</h1>
    </header>
    <div class="widget_group">
    <div class="widget_container">
        <div class="widget_name"><p>Total Flow Today</p></div>
        <i class="fa-solid fa-right-left"></i>
        <div class="widget_value">
        <?php
        $totalflowquery = "SELECT(SELECT COUNT(*) FROM entrylog WHERE DATE(`entryTime`) = CURDATE()) + (SELECT COUNT(*) FROM exitlog WHERE DATE(`exitTime`) = CURDATE()) AS total";
        $result = $conn->query($totalflowquery);
        while($row = mysqli_fetch_array($result)){
        echo "<p>" . $row['total'] . "</p>";
        }
        ?>
        </div>
        

    </div>
    <div class="widget_container">
        <div class="widget_name"><p>Entries Today</p></div>
        <i class="fa-solid fa-arrow-right-to-bracket"></i>
        <div class="widget_value">
        <?php
        $totalentryquery = "SELECT COUNT(*) AS totalentry FROM entrylog WHERE DATE(`entryTime`) = CURDATE()";
        $result = $conn->query($totalentryquery);
        while($row = mysqli_fetch_array($result)){
        echo "<p>" . $row['totalentry'] . "</p>";
        }
        ?>
        </div>
        

    </div>
    <div class="widget_container">
        <div class="widget_name"><p>Exits Today</p></div>
        <i class="fa-solid fa-arrow-right-from-bracket"></i>
        <div class="widget_value">
        <?php
         $totalexitquery = "SELECT COUNT(*) AS totalexit FROM exitlog WHERE DATE(`exitTime`) = CURDATE()";
         $result = $conn->query($totalexitquery);
         while($row = mysqli_fetch_array($result)){
         echo "<p>" . $row['totalexit'] . "</p>";
         }
        ?>
        </div>
        

    </div>
    <div class="widget_container">
        <div class="widget_name"><p>Denied Entries</p></div>
        <i class="fa-solid fa-ban"></i>
        <div class="widget_value">
        <?php
        $totaldeniedquery = "SELECT COUNT(*) AS totaldenied FROM deniedaccess WHERE DATE(`deniedTime`) = CURDATE()";
        $result = $conn->query($totaldeniedquery);
        while($row = mysqli_fetch_array($result)){
        echo "<p>" . $row['totaldenied'] . "</p>";
        }
        ?>
        </div>
        

    </div>
    </div>
    <?php
    $entrylogquery = "SELECT entrylog.referenceID, entrylog.licensePlate, entrylog.entryTime, vehicle.tenantLotNumber FROM entrylog INNER JOIN vehicle ON entrylog.licensePlate = vehicle.licensePlate WHERE DATE(entryTime) = CURDATE()";
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
				while($row = mysqli_fetch_array($result))  
                {  
                    echo '  
                    <tr>  
                        <td>'.$row["entryTime"].'</td>  
                        <td>'.$row["licensePlate"].'</td>  
                        <td>'.$row["tenantLotNumber"].'</td>  
                    </tr>  
                    ';  
                } 
			?>
		</table>
            </div>

<?php

	$exitlogquery = "SELECT exitlog.referenceID, exitlog.licensePlate, exitlog.exitTime, vehicle.tenantLotNumber FROM exitlog INNER JOIN vehicle ON exitlog.licensePlate = vehicle.licensePlate WHERE DATE(exitTime) = CURDATE()";
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
				while($row = mysqli_fetch_array($result))  
                {  
                    echo '  
                    <tr>  
                        <td>'.$row["exitTime"].'</td>  
                        <td>'.$row["licensePlate"].'</td>  
                        <td>'.$row["tenantLotNumber"].'</td>  
                    </tr>  
                    ';  
                } 
			?>
		</table>
            </div>
	</div>
    
</div>
</body>
</html>