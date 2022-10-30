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

  $conn = mysqli_connect($servername, $username, $password, $dbname); // Create DB connection object
      if($conn->connect_error){
          die("Connection Failed: " . $conn->connect_error);
      }

  $email = $password = $user_type = $repassword = $error_msg = $msg = "";

  if(isset($_POST["register_button"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $repassword = $_POST["repassword"];
    $user_type = $_POST["user_type"];

    if ($password == $repassword){
      $password = hash("sha256", $password);
      $myquery = "INSERT INTO users (email, password, role, isAdvanced)
      VALUES ('$email', '$password', '$user_type', FALSE)";
      $stmt = $conn->prepare($myquery);
      $stmt->bind_param("ssssss", $email, $password, $repassword, $user_type);
      $stmt->execute();
      $conn->close();
      $msg = "Record is saved.";
      $email = $password = $user_type = $repassword = $error_msg = "";
      $_POST["email"] = $_POST["password"] = $_POST["repassword"] = $_POST["user_type"] = "";
    } else {
      $error_msg = "<p>Password does not same</p>";
    }
  }
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset = "utf-8">
	<meta name = "autor" content = "Sabrina Tan">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ANPR - Registration</title>

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
</head>

<body>
	<!--Sidebar starts here-->
	<div class="navigation_bar">
  <div class="logo_container">
  <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div>
  <div class="logo_tail"><span>ANPR</span></div>
  </div>
  <div class="navigation_links_container">

  <div class="navigation_links"><a href="dashboard.php"><i class="fa-solid fa-house"></i>Dashboard</a></div>
  <div class="navigation_links"><a href="register_vehicle.php" class="active_page"><i class="fa-solid fa-person-circle-plus"></i>Registration</a></div>
  <div class="navigation_links drop_down_btn"><a href="#"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
    <div class="sub_menu">
		<div class="navigation_links"><a href="report.php"></i>Report</a></div>
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

<div class="content-container">
<header>
  <h1>Registration of New User</h1>
</header>
<section>
  <form action="register_user.php" method="POST">

    <div class="container">
      <label for="user_type"><b>Choose the Type of New User</b></label><br>
      <div class="form_container">
      <input type="radio" id="html" name="user_type" value="1" required>
      <label for="html">Admin</label><br>
      <input type="radio" id="css" name="user_type" value="2">
      <label for="css">Security</label><br>
      </div>

      <div class="form_container">
      <label for="email"><b>Enter New User Email</b></label>
      <input type="text" placeholder="Enter Email" name="email" required><br>

      <label for="psw"><b>Enter Password for New User</b></label>
      <input type="password" placeholder="Enter Password" name="password" required><br>

      <label for="psw"><b>Re-enter the Password</b></label>
      <input type="password" placeholder="Re-enter Password" name="repassword" required><br>
      </div>

      <button class="button_login" type="submit" value="Register" name="register_button">Register</button>
    </div>

    <p class="message"><span class="successMsg"><?php echo $msg;?></span><p>
  </div>
  </form>
</section>


<div class="waves"><p>&</p></div>
</body>

</html>
