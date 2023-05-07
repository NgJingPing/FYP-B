﻿<?php 
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
    <title>ANPR - Edit User</title>
    <style>
		.alert {
			font-size: 18px;
			font-weight: bold;
			background-color: #FFFFFF;
			color: white;
			opacity: 1;
			transition: opacity 0.6s;
			width: 100%;
			margin-left: auto;
	        margin-right: auto;
		}

		.alert.success {background-color: #4DAC62;}
		.alert.error {background-color: #f44336;}
		.alert.warning {background-color: #ff9800;}

		.closebtn {
			margin-left: auto;
			color: white;
			font-weight: bold;
			float: right;
			font-size: 28px;
			line-height: 22px;
			cursor: pointer;
			transition: 0.3s;
		}

		.closebtn:hover {
			color: black;
		}
	</style>
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

<div class="content-container">
<header>
  <h1>Edit User</h1>
</header>

<?php
	$msg = $error_msg = $advance = $role = "";

	$userID = 0;

	include "../include/config.php";

	if(isset($_GET["userID"])) {
			$userID = $_GET["userID"];
			$userID = (int)$userID;
		} else {
			header("Location: manage_user.php");
		}


	if(isset($_POST["submit"])) {
		$advance = FALSE;
		//get the type of user from the form
		if($_POST["user_type"] == "sadmin"){
			$role = 1;
			$advance = TRUE;
		} else if($_POST["user_type"] == "admin"){
			$role = 1;
		} else {
			$role = 2;
		}
		//This query will update the user infromation based on the input from the form
		$myquery = "UPDATE users SET role = ?, isAdvanced = ? WHERE userID = $userID;";
		$stmt = $conn->prepare($myquery);
		$stmt->bind_param("ii", $role, $advance);
		$stmt->execute();
		$msg = "Record is updated.";
		echo '<section><div class="alert success">
						<span class="closebtn">&times;</span>
						' . $msg . '
					</div></section>';

				
	}

	$myquery2 = "SELECT * FROM users WHERE userID = '$userID';";
	$result = $conn->query($myquery2); 

	if(mysqli_num_rows($result) == 1) {
		$item = $result->fetch_assoc();
		$_POST["email"] = $item['email'];
		$role = $item["role"];
		$advance = $item["isAdvanced"];
	} 
?>



<section>
    <form method="post" action="">
    <div class="com_con">
		<div class="form_container">
            <label for="user_type"><b>Choose the Type of User</b></label><br>

			<input type="radio" id="sadmin" name="user_type" value="sadmin" required <?php if($role == "1" && $advance == 1) echo ' checked="checked"'; ?>>
            <label for="sadmin">Super Admin</label>
            <br/><input type="radio" id="admin" name="user_type" value="admin" required <?php if($role == "1" && $advance != 1) echo ' checked="checked"'; ?>>
            <label for="admin">Admin</label>
            <br/><input type="radio" id="security" name="user_type" value="security" <?php if($role == "2") echo ' checked="checked"'; ?>>
            <label for="security">Security</label><br>
        </div>



        <div class="form_container">
            <p><label for="email"><b>Email</b></label><span class="error"> * </span><br>
            <input type="text" class="form_control" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : ''; ?>" name="email" disabled="disabled"></p>
        </div>

		<div>
			<button class="button_submit" type="submit" value="Submit" name="submit">Submit</button><br>
		</div>

		<div>
			<button onclick="window.location='manage_user.php';" type="button" class="button_cancel" style="margin-top:0px;">Cancel</button>
		</div>

    </div>
    </form>
</section>
</div>
<script>

var close = document.getElementsByClassName("closebtn");
var i;

for (i = 0; i < close.length; i++) {
  close[i].onclick = function(){
    var div = this.parentElement;
    div.style.opacity = "0";
    setTimeout(function(){ div.style.display = "none"; }, 600);
  }
}
</script>


<div class="waves"></div>
</body>

</html>