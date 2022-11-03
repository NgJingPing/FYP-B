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

	$servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "anprdb";

  $msgErr = "";

	$conn = mysqli_connect($servername, $username, $password, $dbname); // Create DB connection object
      if($conn->connect_error){
          die("Connection Failed: " . $conn->connect_error);
      }

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
          $error_msg = "<p>New Password does not same</p>";
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
    <meta charset = "utf-8">
	<meta name = "author" content = "Jeffery Sia">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ANPR - Profile</title>

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


    <style>
    .card {
      box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
      width: 80%;
      text-align: center;
      font-family: arial;
      padding: 20px 20px;
      margin: 10px;
      margin-left: 100px;
      margin-right: 100px;
      margin-top: 50px;
      opacity: 90%;
      border-radius: 5px;
    }

    .title {
      color: #4DAC62;
      font-size: 28px;
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
		  width: 60%;
		  padding: 12px 20px;
		  margin: 8px 0;
		  display: inline-block;
		  border: 1px solid #ccc;
		  box-sizing: border-box;
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

		.container {
		  padding: 16px;
			background-color: #f2f2f2;
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
		  overflow: auto; /* Enable scroll if needed */
		  background-color: #061C17; /* Fallback color */
		  padding-top: 60px;
		}

		.modal-content {
		  background-color: #f2f2f2;
		  margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
		  border: 1px solid #888;
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

  <div class="navigation_links"><a href="dashboard.php" ><i class="fa-solid fa-house"></i>Dashboard</a></div>
  <div class="navigation_links"><a href="register_vehicle.php"><i class="fa-solid fa-person-circle-plus"></i>Registration</a></div>
  <div class="navigation_links drop_down_btn"><a href="#"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
    <div class="sub_menu">
        <div class="navigation_links"><a href="report.php"></i>Report</a></div>
        <div class="navigation_links"><a href="entry_log.php"></i>Entry Log</a></div>
        <div class="navigation_links"><a href="exit_log.php"></i>Exit Log</a></div>
        <div class="navigation_links"><a href="denied_access.php"></i>Denial Log</a></div>
    </div>

  <div class="navigation_links"><a href="view_vehicle.php"><i class="fa-solid fa-table"></i>Database</a></div>
  <div class="navigation_links"><a href="profile.php" class="active_page"><i class="fa-solid fa-user"></i>Profile</a></div>
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

    <div class="card">
      <img src="../images/administrator.png" alt="User" class="center" style="width:240px;height:260px;">
      <br>
      <h1><?php echo $session_email;?></h1>
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
      <p><button onclick="document.getElementById('id01').style.display='block'">Change Password</button><br>
				<span class="error"><?php echo $successmsg;?></span></p>
      <br>

      <?php
        if ($isAdvanced == 1){
          echo "<p><a href='register_user.php'><button>Register New Admin/Security</button></a></p>";
        }
      ?>
    </div>
</div>


<div id="id01" class="modal">

  <form class="modal-content animate" action="profile.php" method="post">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
    </div>

    <div class="container">
      <label for="oldpassword"><b>Enter Old Password</b></label><br>
      <input type="password" placeholder="Enter Old Password" name="oldpassword" required><span class="error"> * <?php echo $msg;?></span></p><br>

      <label for="newpassword"><b>Enter New Password</b></label><br>
      <input type="password" placeholder="Enter New Password" name="newpassword" required><span class="error"> * </span></p><br>

			<label for="renewpassword"><b>Re-Enter New Password</b></label><br>
      <input type="password" placeholder="Re-Enter New Password" name="renewpassword" required><span class="error"> * <?php echo $error_msg;?></span></p><br>

      <button type="submit" name = "submit">Submit</button>
    </div>

    <div class="container" style="background-color:#f1f1f1">
      <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
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
</script>

<div class="waves"><p>&</p></div>
</body>
</html>
