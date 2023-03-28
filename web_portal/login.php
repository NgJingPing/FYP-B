<?php
    //Destroy session to prevent wrong login
    session_start();
    session_destroy();
    //The php below store php code for login and forgot password process
    require_once "include/controllerBeforeLogin.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "include/head.php";?>
  <title>ANPR - Login</title>
  <style>
    .alert {
        font-size: 18px;
        font-weight: bold;
        background-color: #f44336;
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

<!---JavaScript to close alert message--->
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

</body>
</html>
