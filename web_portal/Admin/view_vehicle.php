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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset = "utf-8">
	<meta name = "author" content = "Ng Jing Ping">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ANPR - Database</title>

    <!-- JQuery and Bootstrap CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <!-- ENDS HERE -->

    <!-- DataTables CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap.min.css" /> 
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>  
    <script src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap.min.js"></script>   
    <!-- ENDS HERE -->
    <!-- DataTables Buttons CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.5/css/buttons.dataTables.min.css" /> 

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>  
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
  	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.3.5/js/dataTables.buttons.min.js"></script>  
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.bootstrap.min.js"></script> 
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.print.min.js"></script> 
    <script src="https://cdn.datatables.net/buttons/2.3.5/js/buttons.html5.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <!-- ENDS HERE -->

    <!-- Fonts CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://kit.fontawesome.com/2ffaabbca0.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Hairline&display=swap" rel="stylesheet">
    <!-- ENDS HERE -->
    <link type="text/css" rel="stylesheet" href="style/style.css">
    <script src="script/navbar.js"></script>
    <script src="script/database.js"></script>
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
        <div class="navigation_links"><a href="view_vehicle.php" class="active_page"><i class="fa-solid fa-table"></i>Database</a></div>
        <div class="navigation_links drop_down_btn"><a href="#"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
            <div class="sub_menu">
                <div class="navigation_links"><a href="report.php"></i>Report</a></div>
                <div class="navigation_links"><a href="entry_log.php"></i>Entry Log</a></div>
                <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
                <div class="navigation_links"><a href="denied_access.php"></i>Denial Log</a></div>
            </div>
        <div class="navigation_links"><a href="analytic.php"><i class="fa fa-line-chart"></i>Analytics</a></div>
        <?php 
        
        if($session_type == "Super Admin") {
            echo '<div class="navigation_links drop_down_btn"><a href="#"><i class="fa fa-users"></i>Management<i class="fa-solid fa-angle-right" style="margin-left: 5px; padding-left:8px;"></i></a></div>
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
                            <option value="7">Active</option>
                            <option value="8">Action</option>
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
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <?php
                
                   include "../include/config.php";
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
                                echo '<span><a onClick="javascript:return confirm(\'Do you really want to delete this record? \n\nLicense Plate: '.$row["licensePlate"]. '\nTenant: '.$row["name"].'\')" href="remove_vehicle.php?vehicleID='.$row["vehicleID"].'"><i class="fa-solid fa-trash-can"></i></a></span>';
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
            </div>

    </section>
</div>
<div class="waves"></div>
</body>
</html>
