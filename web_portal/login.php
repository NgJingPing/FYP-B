<?php
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
			header("location:dashboard.php");
		  } else {
			$error_msg = "<p>Invalid password or login id</p>";
		  }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset = "utf-8">
  <meta name = "author" content = "Jeffery SIa">
  <title>ANPR - Login</title>
<style>
  body {font-family: Arial, Helvetica, sans-serif;}

  /* Full-width input fields */
  input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    box-sizing: border-box;
  }

  /* Set a style for all buttons */
  button {
    background-color: #04AA6D;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
  }

  button:hover {
    opacity: 0.8;
  }

  /* Extra styles for the cancel button */
  .cancelbtn {
    width: auto;
    padding: 10px 18px;
    background-color: #f44336;
  }

  /* Center the image and position the close button */
  .imgcontainer {
    text-align: center;
    margin: 24px 0 12px 0;
    position: relative;
  }

  img.avatar {
    width: 40%;
    border-radius: 50%;
  }

  .container {
    padding: 16px;
  }

  span.psw {
    float: right;
    padding-top: 16px;
  }

  /* The Modal (background) */
  .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    padding-top: 60px;
  }

  /* Modal Content/Box */
  .modal-content {
    background-color: #fefefe;
    margin: 5% auto 15% auto; /* 5% from the top, 15% from the bottom and centered */
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
  }

  /* The Close Button (x) */
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

<h1 style="text-align:center">NAIM ANPR Login</h1>
<h2 style="text-align:center">Choose Your Method to Login</h2>

<div style="text-align:center">
<button onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Admin</button>

<button onclick="document.getElementById('id02').style.display='block'" style="width:auto;">Security</button>
</div>

<div id="id01" class="modal">

  <form class="modal-content animate" action="login.php" method="POST">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
      <img src="" alt="Avatar" class="avatar">
    </div>

    <div class="container">
      <input for="user_type" type="hidden" name="user_type" value="Admin">
      <label for="email"><b>Admin Email</b></label>
      <input type="text" placeholder="Enter Email" name="email" required>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" required>

      <button type="submit" value="Login" name="login_button">Login</button>
    </div>

    <div class="container" style="background-color:#f1f1f1">
      <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Cancel</button>
      <span class="psw">Forgot <a href="#">password?</a></span>
    </div>
    <?php echo $error_msg; ?>
  </form>
</div>

<div id="id02" class="modal">

  <form class="modal-content animate" action="login.php" method="POST">
    <div class="imgcontainer">
      <span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
      <img src="" alt="Avatar" class="avatar">
    </div>

    <div class="container">
      <input for="user_type" type="hidden" name="user_type" value="Security">
      <label for="email"><b>Security Email</b></label>
      <input type="text" placeholder="Enter Email" name="email" required>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" required>

      <button type="submit" value="Login" name="login_button">Login</button>
    </div>

    <div class="container" style="background-color:#f1f1f1">
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
