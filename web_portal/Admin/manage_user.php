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
<?php 
    // Give active page  
    $page = 'Management';
    $subpage = 'View User';
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
