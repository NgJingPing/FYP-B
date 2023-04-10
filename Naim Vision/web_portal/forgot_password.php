<?php
    require_once "include/controllerBeforeLogin.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "include/head.php";?>
    <title>ANPR - Forgot Password</title>
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

<!---Script to close alert message--->
<script src="script/alert.js"></script>


    <form class="modal-content animate" action="forgot_password.php" method="POST">

    <div class="container">
        <label for="email"><b>Enter Email Address</b></label>
        <input type="text" placeholder="Enter Email" name="email" required>

        <button class="button_login" type="submit" value="Continue" name="check-email">Continue</button>
    </div>

    </form>


</body>
</html>