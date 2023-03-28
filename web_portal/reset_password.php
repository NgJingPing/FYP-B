<?php
    require_once "include/controllerBeforeLogin.php";
?>
<?php 
$email = $_SESSION['email'];
if($email == false){
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "include/head.php";?>
    <title>ANPR - Reset Code</title>
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

<?php
/*Alert Message */
    if (isset($_SESSION['info'])){
    echo '<div class="alert success">
            <span class="closebtn">&times;</span>
            ' . $_SESSION['info'] . '
            </div>';
    }
?>

<!---Script to close alert message--->
<script src="script/alert.js"></script>


    <form class="modal-content animate" action="reset_password.php" method="POST">

    <div class="container">
        <label for="email"><b>Enter Reset Code</b></label>
        <input type="text" placeholder="Enter code" name="otp" required>

        <button class="button_login" type="submit" value="Submit" name="check-reset-otp">Check Code</button>
    </div>

    </form>


</body>
</html>