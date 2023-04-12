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
    <?php include "../include/head.php";?>
    <title>ANPR - Database</title>
</head>

<body>
<!--Sidebar starts here-->
<?php 
    // Give active page  
    $page = 'Database';
    $subpage = '';
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
        <h1>Database</h1>
    </header>

    <section>
        <div class="log_container">
             <div class="card-header">
				<div class="row">
					<div class="col-sm-2">Hide Column</div>
					<div class="col-sm-4">
						<select name="column_name" id="column_name_database" class="form-control selectpicker" data-icon-base="fas" data-tick-icon="fa fa-times" multiple>
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
            <div class="table-responsive database" style="overflow-x: hidden;">
            <table id="db_table" class="table table-striped table-bordered display responsive nowrap" style="width:100%;">  
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
                    
                    //This SQL query retrieves information about all vehicles and their corresponding tenants by performing an inner join on the vehicle and tenant tables based on the tenantLotNumber column. 
                    //The query returns the vehicle ID, license plate number, tenant lot number, tenant name, tenant phone number, vehicle brand, model, colour, and active status for all vehicles associated with a tenant lot number.

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
                            $sql2 = "SELECT * FROM entrylog WHERE vehicleID = $id;";
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
