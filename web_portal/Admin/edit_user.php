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
    <meta charset = "utf-8">
	<meta name = "author" content = "Sabrina Tan">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ANPR - Edit User</title>

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
    <link type="text/css" rel="stylesheet" href="style/registration.css">

    <style>
		.alert {
			font-size: 18px;
			font-weight: bold;
			background-color: #FFFFFF;
			color: white;
			opacity: 1;
			transition: opacity 0.6s;
			width: 95%;
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
      echo '<div class="navigation_links drop_down_btn"><a href="#" class="active_page"><i class="fa fa-users"></i>Management<i class="fa-solid fa-angle-right" style="margin-left:0px; padding-left:8px;"></i></a></div>
    <div class="sub_menu">
        <div class="navigation_links"><a href="register_user.php"></i>Add User</a></div>
        <div class="navigation_links"><a href="manage_user.php" class="active_page"></i>View User</a></div>
    </div>';
  }
  ?>  
  <div class="navigation_links"><a href="profile.php"><i class="fa-solid fa-user"></i>Profile</a></div>

  <div class="navigation_links"><a href="../login.php"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
  
</div>
</div>
</div>
<script src="script/log.js"></script>
<!--Sidebar ends here-->

<div class="content-container">
<header>
  <h1>Edit User</h1>
</header>

<?php
	$msg = $error_msg = $advance = $role = "";

	$userID = 0;

	$servername = "localhost";
	$username = "root";
	$password = "";
    $dbname = "anprdb";

	if(isset($_GET["userID"])) {
			$userID = $_GET["userID"];
			$userID = (int)$userID;
		} else {
			header("Location: manage_user.php");
		}

    $conn = mysqli_connect($servername, $username, $password, $dbname); // Create DB connection object
		if($conn->connect_error){
			die("Connection Failed: " . $conn->connect_error);
        }

	if(isset($_POST["submit"])) {
		$advance = FALSE;
		if($_POST["user_type"] == "sadmin"){
			$role = 1;
			$advance = TRUE;
		} else if($_POST["user_type"] == "admin"){
			$role = 1;
		} else {
			$role = 2;
		}
		
		$myquery = "UPDATE users SET role = ?, isAdvanced = ? WHERE userID = $userID;";
		$stmt = $conn->prepare($myquery);
		$stmt->bind_param("ss", $role, $advance);
		$stmt->execute();
		$msg = "Record is updated.";
		echo '<div class="alert success">
						<span class="closebtn">&times;</span>
						' . $msg . '
					</div>';

				
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