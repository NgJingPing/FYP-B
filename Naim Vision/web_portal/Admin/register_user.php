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
          $error_msg = "<p>New password and re-enter password do not match!</p>";
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
    $subpage = 'Add User';
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
  <h1>Registration of New User</h1>
</header>

<?php
	if ($msg != ""){
		echo '<section><div class="alert success">
						<span class="closebtn">&times;</span>
						' . $msg . '
					</div></section>';
	}

	if ($emailErr != ""){
		echo '<section><div class="alert error">
						<span class="closebtn">&times;</span>
						' . $emailErr . '
					</div></section>';
	}

	if ($error_msg != ""){
		echo '<section><div class="alert error">
						<span class="closebtn">&times;</span>
						' . $error_msg . '
					</div></section>';
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
      <input type="text" class="form_control" name="email" required><span class="error"></p>

      <p><label for="psw"><b>Enter Password for New User</b></label><span class="error"> * </span><br>
      <input type="password" class="form_control" name="password" required></p>

      <p><label for="psw"><b>Re-enter the Password</b></label><span class="error"> * </span><br>
      <input type="password" class="form_control" name="repassword" required></p>
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
