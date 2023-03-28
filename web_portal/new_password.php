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
    <title>ANPR - Create A New Password</title>
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

        .alert.success {background-color: #4DAC62;}
        .alert.error {background-color: #f44336;}

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

<?php
/*Alert Message */
    if (isset($_SESSION['info'])){
    echo '<div class="alert success">
            <span class="closebtn">&times;</span>
            ' . $_SESSION['info'] . '
            </div>';
    }
?>


    <form class="modal-content animate" action="new_password.php" method="POST">

    <div class="container">
        <label for="email"><b>Create New Password</b></label>
        <input type="password" placeholder="Enter password" name="password" required>

        <label for="email"><b>Re-enter New Password</b></label>
        <input type="password" placeholder="Enter password" name="cpassword" required>

        <button class="button_login" type="submit" value="Change" name="change-password">Change</button>
    </div>

    </form>

</body>
</html>