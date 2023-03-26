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
		if($session_type != "Super Admin") {
			header("Location: ../login.php");
		}
	}
?> 

<!DOCTYPE HTML>
<html lang="en">

<head>
    <?php include "../include/head.php";?>
    <title>ANPR - Manage User</title>
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
            echo '<div class="navigation_links drop_down_btn"><a href="#" class="active_page"><i class="fa fa-users"></i>Management<i class="fa-solid fa-angle-right" style="margin-left: 0px; padding-left:8px;"></i></a></div>
            <div class="sub_menu">
                <div class="navigation_links"><a href="register_user.php"></i>Add User</a></div>
                <div class="navigation_links"><a href="manage_user.php" class="active_page"></i>View User</a></div>
            </div>';
        }
        ?>  
        <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>
        <div class="navigation_links" id="last_nav_link"><a href="../login.php" id="last_nav_link"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
    </div>
</div>
<script src="script/log.js"></script>
<!--Sidebar ends here-->

<?php
    include "../include/config.php";
    $referenceID = "";

     //This SQL query retrieves information about all users
	$myquery = "SELECT * FROM users;";
	$result = $conn->query($myquery);
?>

    <div class="content-container">
    <header>
		<h1>Account Management</h1>
	</header>

    <section>
	<div class="log_container">
    <div class="card-header">
		<div class="row">
			<div class="col-sm-2">Hide Column</div>
			    <div class="col-sm-4">
				    <select name="column_name" id="column_name" class="form-control selectpicker" data-icon-base="fas" data-tick-icon="fa fa-times" multiple>
					    <option value="0">User ID</option>
				        <option value="1">Email</option>
				        <option value="2">Role</option>
				        <option value="3">Action</option>
				    </select>
			    </div>
		</div>
	</div>
    <div class="table-responsive user">
		<table id="user_table" class="table table-striped table-bordered" style="width:100%">  
			<thead>  
                <tr>  
                    <th>User ID</th>   
                    <th>Email</th>  
                    <th>Role</th>  
                    <th>Action</th> 
                </tr>  
            </thead>  

			<?php
                if($result){
                    while($row = mysqli_fetch_array($result))  
                    {
                        // output data of each row
                        if($row["role"] == "1")
                        {
                            $role = "Admin";
                            if($row["isAdvanced"] == "1")
                        {
                            $role = "Super Admin";
                        } 
                        } else {
                            $role = "Security";
                        }

                        echo '  
                        <tr>  
                            <td>'.$row["userID"].'</td>  
                            <td>'.$row["email"].'</td>  
                            <td>'.$role.'</td> 
                            <td><a href="edit_user.php?userID='.$row["userID"].'"><i class="fa-solid fa-pen-to-square"></i></a> <a onClick="javascript:return confirm(\'Do you really want to delete this record? \n\nUser ID: '.$row["userID"]. '\nEmail: '.$row["email"].'\')" href="delete_user.php?userID='.$row["userID"].'"><i class="fa-solid fa-trash-can"></i></a></td> 
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
