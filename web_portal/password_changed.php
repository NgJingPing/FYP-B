<?php
    require_once "include/controllerBeforeLogin.php";
?>
<?php 
if($_SESSION['info'] == false){
    header('Location: login.php');  
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "include/head.php";?>
    <title>ANPR - Create Password Success</title>
    <style>
        /*Alert Message Css */
        .alert {
            font-size: 18px;
            font-weight: bold;
            background-color: #4DAC62;
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
/*Alert Message */
    if (isset($_SESSION['info'])){
    echo '<div class="alert">
            <span class="closebtn">&times;</span>
            ' . $_SESSION['info'] . '
            </div>';
    }
?>


    <form class="modal-content animate" action="login.php" method="POST">

    <div class="container">
        <button class="button_login" type="submit" value="Change" name="login_now">Login Now</button>
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