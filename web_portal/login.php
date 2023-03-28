<?php
    //Destroy session to prevent wrong login
    //Bring message from succes create password
    session_start();
    $info = "";
    if (isset($_SESSION['info'])){
      $info = $_SESSION['info'];
      session_destroy();
    }else{
      session_destroy();
    }

    //The php below store php code for login and forgot password process
    require_once "include/controllerBeforeLogin.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "include/head.php";?>
  <title>ANPR - Login</title>
</head>

<body>

<!---Logo and TItle of the website--->
<div class="logo_container">
  <div class="logo_group">
  <img src="images/naim.png" alt="NAIM" class="center" style="width:240px;height:60px;">
  <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div>
  <div class="logo_tail"><span>ANPR</span></div>
  </div>
</div>

<!---Alert Message for Wrong Login--->
<?php
  if ($errors != ""){
    echo '<div class="alert error">
            <span class="closebtn">&times;</span>
            ' . $errors . '
          </div>';
  }
?>

<?php
/*Alert Message */
    if ($info != ""){
    echo '<div class="alert success">
            <span class="closebtn">&times;</span>
            ' . $info . '
            </div>';
    }
?>

<!---Form to Login--->
  <form class="modal-content animate" action="login.php" method="POST">

    <div class="container">
      <label for="email"><b>Email</b></label>
      <input type="text" placeholder="Enter Email" name="email" required>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" required>

      <a href="forgot_password.php">Forgot Password?</a>

      <button class="button_login" type="submit" value="Login" name="login_button">Login</button>
    </div>

  </form>

</body>
</html>
