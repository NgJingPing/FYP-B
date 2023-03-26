<?php
    require_once "include/controllerBeforeLogin.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "include/head.php";?>
    <title>ANPR - Forgot Password</title>
    <style>
      /*Alert Message Css */
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

<div class="logo_container">
  <div class="logo_group">
  <img src="images/naim.png" alt="NAIM" class="center" style="width:240px;height:60px;">
  <div class="logo"><span class="logo_initial">V</span><span>ISION</span></div>
  <div class="logo_tail"><span>ANPR</span></div>
  </div>
</div>

<?php
/*Alert Error */
    if ($errors != ""){
    echo '<div class="alert">
            <span class="closebtn">&times;</span>
            ' . $errors . '
            </div>';
    }
?>


    <form class="modal-content animate" action="forgot_password.php" method="POST">

    <div class="container">
        <label for="email"><b>Enter Email Address</b></label>
        <input type="text" placeholder="Enter Email" name="email" required>

        <button class="button_login" type="submit" value="Continue" name="check-email">Continue</button>
    </div>

    </form>

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