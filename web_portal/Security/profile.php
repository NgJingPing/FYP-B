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
		if($session_type != "Security") {
			header("Location: ../login.php");
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
      background-color: #000;
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
    </style>
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
    <div class="navigation_links drop_down_btn"><a href="#"><i class="fa-solid fa-clipboard-list"></i>Log<i class="fa-solid fa-angle-right"></i></a></div>
      <div class="sub_menu">
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
      <img src="../images/security.png" alt="User" class="center" style="width:240px;height:260px;">
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
      <p><button>Change Password</button></p>
    </div>
	</div>

<div class="waves"><p>&</p></div>
</body>
</html>
