<?php
  session_start();
  session_destroy();
  $error_msg = "";
  $email = $pass = "";

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "anprdb";

  $conn = mysqli_connect($servername, $username, $password, $dbname); // Create DB connection object
    if($conn->connect_error){
      die("Connection Failed: " . $conn->connect_error);
    }

    if(isset($_POST["login_button"])) {// If login button is clicked, do the following
      $user_type = $_POST["user_type"];
  		$email = mysqli_escape_string($conn, $_POST["email"]);
  		$pass = mysqli_escape_string($conn, $_POST["password"]);
      if ($user_type == "Admin"){
        $myquery = "SELECT password FROM admin WHERE email = '$email';";
      }
      elseif ($user_type == "Security") {
        $myquery = "SELECT password FROM security WHERE email = '$email';";
      }
  		//$myquery = "SELECT password FROM admin WHERE email = '$email';";
  		$sql = mysqli_query($conn, $myquery);
  		$pass = hash("sha256", $pass);
  		$dbpass = "";
  		while($row = mysqli_fetch_assoc($sql)) {
  			$dbpass = $row['password'];
  		}

      if($pass == $dbpass) {
			session_start();
			$_SESSION['email'] = $email;
            $_SESSION['type'] = $user_type;
            if($user_type == "Admin") {
                header("location: Admin/dashboard.php");
            } else {
                header("location: Security/dashboard.php");
            }
		  } else {
			$error_msg = "<p>Invalid password or login id</p>";
		  }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset = "utf-8">
  <meta name = "author" content = "Jeffery Sia">
  <title>ANPR - Login</title>

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
    <link type="text/css" rel="stylesheet" href="style/login.css">
<style>
  
</style>
</head>
<body>

<div class="logo_container"> 
  <div class="logo_group">
  <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div> 
  <div class="logo_tail"><span>ANPR</span></div> 
  </div>
  </div>
<h2 class="user_lvl">Select User Level</h2>

<div class="user_lvl_buttons">
<button onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Admin</button>

<button onclick="document.getElementById('id02').style.display='block'" style="width:auto;">Security</button>
</div>

<div id="id01" class="modal">

  <form class="modal-content animate" action="login.php" method="POST">

    <div class="container">
      <input for="user_type" type="hidden" name="user_type" value="Admin">
      <label for="email"><b>Admin Email</b></label>
      <input type="text" placeholder="Enter Email" name="email" required>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" required>

      <button class="button_login" type="submit" value="Login" name="login_button">Login</button>
    </div>

    <div class="container">
      <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
      <span class="psw">Forgot <a href="#">password?</a></span>
    </div>
    <?php echo $error_msg; ?>
  </form>
</div>

<div id="id02" class="modal">

  <form class="modal-content animate" action="login.php" method="POST">

    <div class="container">
      <input for="user_type" type="hidden" name="user_type" value="Security">
      <label for="email"><b>Security Email</b></label>
      <input type="text" placeholder="Enter Email" name="email" required>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" required>

      <button class="button_login" type="submit" value="Login" name="login_button">Login</button>
    </div>

    <div class="container">
      <button type="button" onclick="document.getElementById('id02').style.display='none'" class="cancelbtn">Cancel</button>
      <span class="psw">Forgot <a href="#">password?</a></span>
    </div>
    <?php echo $error_msg; ?>
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

</body>
</html>
