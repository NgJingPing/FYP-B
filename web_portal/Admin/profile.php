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

	include "../include/config.php";

  $msgErr = "";


	$oldpassword = $newpassword = $renewpassword = $error_msg = $msg = $successmsg = "";

	if(isset($_POST["submit"])) {
		$oldpassword = $_POST["oldpassword"];
		$oldpassword = mysqli_escape_string($conn, $oldpassword);
		$newpassword = $_POST["newpassword"];
		$newpassword = mysqli_escape_string($conn, $newpassword);
		$renewpassword = $_POST["renewpassword"];
		$renewpassword = mysqli_escape_string($conn, $renewpassword);
		$email = $session_email;
		$email = mysqli_escape_string($conn, $email);

		$myquery = "SELECT password, role, isAdvanced, userID FROM users WHERE email = '$email'";

		$sql = mysqli_query($conn, $myquery);
		$oldpassword = hash("sha256", $oldpassword);
		$dbpass = "";
		while($row = mysqli_fetch_assoc($sql)) {
			$dbpass = $row['password'];
			$role = $row['role'];
			$isAdvanced = $row['isAdvanced'];
			$userid = $row['userID'];
		}

		if($oldpassword != "" & $newpassword != "" & $renewpassword != "") {
			if ($oldpassword == $dbpass){
				if ($newpassword == $renewpassword){
          $newpassword = hash("sha256", $newpassword);
          $myquery = "UPDATE users set password = ? WHERE userID = ?";
          $stmt = $conn->prepare($myquery);
          $stmt->bind_param("si", $newpassword, $userid);
          $stmt->execute();
          $conn->close();
          $successmsg = "New Password is saved.";
          $email = $newpassword = $role = $isAdvanced = $renewpassword = $oldpassword = $error_msg = $userid ="";
          $_POST["oldpassword"] = $_POST["newpassword"] = $_POST["renewpassword"] = "";
        } else {
          $error_msg = "<p>New Password does not match</p>";
        }
			} else {
				$msg = "<p>Incorrect password entered</p>";
			}
    }

	}
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
	<?php include "../include/head.php";?>
	<title>ANPR - Profile</title>
    <style>
		.card {
			box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
			width: 80%;
			text-align: center;
			font-family: arial;
			padding: 20px 20px;
			margin: 10px;
			margin-left: auto;
			margin-right: auto;
			margin-top: 50px;
			opacity: 90%;
			border-radius: 5px;
		}

		.title {
			color: #4DAC62;
			font-size: 1.2rem;
		}

		.emailtext{
			font-size: 1.2rem;
		}

		button {
			border: none;
			outline: 0;
			display: inline-block;
			padding: 8px;
			color: white;
			background-color: #061C17;
			text-align: center;
			cursor: pointer;
			width: 60%;
			font-size: 18px;
		}

		button:hover, a:hover {
			opacity: 0.7;
		}

		.center {
			display: block;
			margin-left: auto;
			margin-right: auto;
			width: 240px;
		}

		input[type=password] {
			width: 100%;
			padding: 12px 20px;
			margin: 8px 0;
			display: inline-block;
			border: 1px solid #ccc;
			box-sizing: border-box;
			font-size: 1.25rem;
			box-shadow: 0px 3px 13px rgba(0, 0, 0, 0.3);
		}

		.cancelbtn {
			width: 60%;
			padding: 10px 18px;
			background-color: #f44336;
		}

		.imgcontainer {
			text-align: center;
			margin: 24px 0 12px 0;
			position: relative;
		}

		.input_container {
			padding: 16px;
			background-color: #f2f2f2;
		}

		.input_container label{
			font-weight: bold;
		}

		span.psw {
			float: right;
			padding-top: 16px;
		}

		.modal {
			display: none; /* Hidden by default */
			position: fixed; /* Stay in place */
			z-index: 1; /* Sit on top */
			left: 0;
			top: 0;
			width: 100%; /* Full width */
			height: 100%; /* Full height */
			overflow: hidden; /* Disable scroll if needed */
			background-color: #061C17; /* Fallback color */
			overflow-y: scroll; /* Enable scroll if needed */
			padding-top: 65px;
		}
		
		.modal::-webkit-scrollbar {
			width: 15px;
		}

		.modal::-webkit-scrollbar-track {
			background-color: #f1f1f1;
		}

		.modal::-webkit-scrollbar-thumb {
			background-color: #888;
			border-radius: 5px;
		}

		.modal::-webkit-scrollbar-thumb:hover {
			background-color: #555;
		}

		.modal-content {
			background-color: #f2f2f2;
			margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
			border: 1px solid #888;
			overflow: hidden;
			width: 80%; /* Could be more or less, depending on screen size */
		}

		.close {
			position: absolute;
			right: 25px;
			top: 0;
			color: #000;
			font-size: 35px;
			font-weight: bold;
		}

		.close:hover,
		.close:focus {
			color: red;
			cursor: pointer;
		}

		/* Add Zoom Animation */
		.animate {
			-webkit-animation: animatezoom 0.6s;
			animation: animatezoom 0.6s
		}

		@-webkit-keyframes animatezoom {
			from {-webkit-transform: scale(0)}
			to {-webkit-transform: scale(1)}
		}

		@keyframes animatezoom {
			from {transform: scale(0)}
			to {transform: scale(1)}
		}

		/* Change styles for span and cancel button on extra small screens */
		@media screen and (max-width: 300px) {
			span.psw {
				display: block;
				float: none;
			}
			.cancelbtn {
				width: 100%;
			}
		}

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

		.button_submit, .button_cancel {
			font-size: 1.25rem;
			padding: 5px;
			margin-top: 30px;
			margin-bottom: 30px;
			margin-left: auto;
			margin-right: auto;
			width: 60%;
			border: none;
			display: block;
			height: 45px;
			border-radius: 4px;
			box-shadow: 0px 3px 13px rgba(0, 0, 0, 0.3);
		}

		.button_submit {
			background-color: #061C17;
			color: #C5E5CC;
		}

		.button_cancel {
			background-color: #C5E5CC;
			color: #061C17;
		}

		.button_submit:hover, .button_cancel:hover {
		color: #4DAC62;
		}

		.form_group {
			display: flex;
			width: 100%;
		}


		.form_container {
			display: inline-block;
			width: 100%;
		}

		.form_container label{
			font-weight: normal;
			padding-top: 5px;
			margin-right: 10px;
			font-size: 1.25rem;
			white-space: nowrap;
		}

		@media only screen and (min-width: 800px) {
			.emailtext, .title{font-size: 2rem;}
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
            echo '<div class="navigation_links drop_down_btn"><a href="#"><i class="fa fa-users"></i>Management<i class="fa-solid fa-angle-right" style="margin-left: 0px; padding-left:8px;"></i></a></div>
            <div class="sub_menu">
                <div class="navigation_links"><a href="register_user.php"></i>Add User</a></div>
                <div class="navigation_links"><a href="manage_user.php"></i>View User</a></div>
            </div>';
        }
        ?>  
        <div class="navigation_links"><a href="profile.php" class="active_page"s><i class="fa-solid fa-user"></i>Profile</a></div>
        <div class="navigation_links" id="last_nav_link"><a href="../login.php" id="last_nav_link"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a></div>
    </div>
</div>
<script src="script/log.js"></script>
<!--Sidebar ends here-->

<?php
  include "../include/config.php";
  $referenceID = "";

  $myquery = "SELECT role, isAdvanced FROM users where email = '$session_email'";
  $sql = mysqli_query($conn,$myquery);
  $role = "";
  $isAdvanced = "";
  while($row = mysqli_fetch_assoc($sql)) {
    $role = $row['role'];
    $isAdvanced = $row['isAdvanced'];
  }
?>

<div class="content-container">
		<header>
		<h1>User Profile Card</h1>
		</header>


		<?php
			if ($successmsg != ""){
				echo '<div class="alert success">
								<span class="closebtn">&times;</span>
								' . $successmsg . '
							</div>';
			}

			if ($error_msg != ""){
				echo '<div class="alert error">
								<span class="closebtn">&times;</span>
								' . $error_msg . '
							</div>';
			}

			if ($msg != ""){
				echo '<div class="alert error">
								<span class="closebtn">&times;</span>
								' . $msg . '
							</div>';
			}
		?>

    <div class="card">
      <img src="../images/administrator.png" alt="User" class="center" style="width:240px;height:260px;">
      <br>
      <p class="emailtext"><?php echo $session_email;?></p>
      <br>
      <p class="title">
        <?php
          if ($role == 1 && $isAdvanced == 1){
            echo "Super Admin";
          } else if ($role == 1 && $isAdvanced == 0){
            echo "Admin";
          } else if ($role == 2 && $isAdvanced == 0){
            echo "Security";
          }
        ?>
      </p>
      <br>
      <p>NAIM Holdings Berhad</p>
      <br>
      <p><button onclick="openpopout()">Change Password</button><br></p>
      <br>
    </div>
</div>


<div id="id01" class="modal">

  <form class="modal-content animate" action="profile.php" method="post">
    <div class="imgcontainer">
      <span onclick="closepopout()" class="close" title="Close Modal">&times;</span>
    </div>

    <div class="input_container">
      <label for="oldpassword"><b>Enter Old Password</b></label><span class="error"> * </span><br>
      <input type="password" placeholder="Enter Old Password" name="oldpassword" required></p><br>

      <label for="newpassword"><b>Enter New Password</b></label><span class="error"> * </span><br>
      <input type="password" placeholder="Enter New Password" name="newpassword" required></p><br>

			<label for="renewpassword"><b>Re-Enter New Password</b></label><span class="error"> * </span><br>
      <input type="password" placeholder="Re-Enter New Password" name="renewpassword" required></p><br>


    </div>



		<div class="com_con">
		<div class="form_group">
		<div class="form_container">
		<button type="button" onclick="closepopout()" class="button_cancel">Cancel</button>
		</div>
		<div class="form_container">
		 <button type="submit" name = "submit" class="button_submit">Submit</button>
		</div>
		</div>
		</div>
  </form>
</div>

<script>
// Get the modal
var modal = document.getElementById('id01');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

var close = document.getElementsByClassName("closebtn");
var i;

for (i = 0; i < close.length; i++) {
  close[i].onclick = function(){
    var div = this.parentElement;
    div.style.opacity = "0";
    setTimeout(function(){ div.style.display = "none"; }, 600);
  }
}

manageModelScrollbar();

function openpopout(){
	document.getElementById('id01').style.display="block"; 
	document.querySelector("body").style.overflow = "hidden";
	window.addEventListener('resize', function() {
		manageModelScrollbar();
	})
}

function closepopout(){
	document.getElementById('id01').style.display="none"; 
	document.querySelector("body").style.overflow = "visible";
	window.addEventListener('resize', function() {
		manageModelScrollbar();
	})
}

function manageModelScrollbar(){
	var windowHeight = window.innerHeight;
	if (windowHeight > 690){
		document.getElementById('id01').style.overflowY ="hidden"; 
	}
	else{
		document.getElementById('id01').style.overflowY ="scroll"; 
	}
}
</script>

<div class="waves"></div>
</body>
</html>
