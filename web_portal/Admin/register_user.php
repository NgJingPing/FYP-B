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

  include "../include/config.php";

  $emailErr = "";

  $email = $password = $user_type = $repassword = $error_msg = $msg = "";

  if(isset($_POST["register_button"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repassword = $_POST["repassword"];
    $user_type = $_POST["user_type"];
    $user_type = (int)$user_type;
    $advanced = FALSE;

    $email = mysqli_escape_string($conn, $email);
    $password = mysqli_escape_string($conn, $password);
    $repassword = mysqli_escape_string($conn, $repassword);

    $myquery2 = "SELECT email FROM users WHERE email = '$email';";
    $sql = mysqli_query($conn, $myquery2);
	$result = mysqli_num_rows($sql);
    if($result > 0) {
        $emailErr = "An account with the same email already existed";
        $email = "";

    }


    if($email != "" & $password != "" & $repassword != "" & $user_type != "") {
        if ($password == $repassword){
          $password = hash("sha256", $password);
          $myquery = "INSERT INTO users (email, password, role, isAdvanced)
          VALUES (?, ?, ?, ?)";
          $stmt = $conn->prepare($myquery);
          $stmt->bind_param("ssss", $email, $password, $user_type, $advanced);
          $stmt->execute();
          $conn->close();
          $msg = "New user is added. Record is saved.";
          $email = $password = $user_type = $repassword = $error_msg = "";
          $_POST["email"] = $_POST["password"] = $_POST["repassword"] = $_POST["user_type"] = "";
        } else {
          $error_msg = "<p>Password does not match</p>";
        }
    }
  }
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <?php include "../include/head.php";?>
    <title>ANPR - Registration</title>
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
            echo '<div class="navigation_links drop_down_btn"><a href="#" class="active_page"><i class="fa fa-users"></i>Management<i class="fa-solid fa-angle-right" style="margin-left: 0px; padding-left:8px;"></i></a></div>
            <div class="sub_menu">
                <div class="navigation_links"><a href="register_user.php" class="active_page"></i>Add User</a></div>
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
  <h1>Registration of New User</h1>
</header>

<?php
	if ($msg != ""){
		echo '<div class="alert success">
						<span class="closebtn">&times;</span>
						' . $msg . '
					</div>';
	}

	if ($emailErr != ""){
		echo '<div class="alert error">
						<span class="closebtn">&times;</span>
						' . $emailErr . '
					</div>';
	}

	if ($error_msg != ""){
		echo '<div class="alert error">
						<span class="closebtn">&times;</span>
						' . $error_msg . '
					</div>';
	}
?>

<section>
  <form action="register_user.php" method="POST">
  <php echo $user_type;
   echo $email;
    echo $advanced;?>
    <div class="com_con">
			<div class="form_container">
      <label for="user_type"><b>Choose the Type of New User</b></label><br>

      <input type="radio" id="html" name="user_type" value="1" required>
      <label for="html">Admin</label>
      <br/><input type="radio" id="css" name="user_type" value="2">
      <label for="css">Security</label><br>
      </div>

      <div class="form_container">
      <p><label for="email"><b>Enter New User Email</b></label><span class="error"> * </span><br>
      <input type="text" class="form_control" placeholder="Enter Email" name="email" required><span class="error"></p>

      <p><label for="psw"><b>Enter Password for New User</b></label><span class="error"> * </span><br>
      <input type="password" class="form_control" placeholder="Enter Password" name="password" required></p>

      <p><label for="psw"><b>Re-enter the Password</b></label><span class="error"> * </span><br>
      <input type="password" class="form_control" placeholder="Re-enter Password" name="repassword" required></p>
			</div>

			<div>
      <button class="button_submit" type="submit" value="Register" name="register_button">Register</button><br>
			</div>

    </div>
  </div>
  </form>
</section>

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
