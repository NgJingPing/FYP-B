<?php
    session_start();
    $errors = "";
    $email = $pass = $user_type = "";

    include "config.php";

    //Use PHPMailer to send mail
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    // If login button is clicked, do the following
    if(isset($_POST["login_button"])) {
        $email = mysqli_escape_string($conn, $_POST["email"]);
        $pass = mysqli_escape_string($conn, $_POST["password"]);

        $myquery = "SELECT password, role, isAdvanced FROM users WHERE email = '$email';";

        $sql = mysqli_query($conn, $myquery);
        $pass = hash("sha256", $pass);
        $dbpass = "";
        $role = "";
        while($row = mysqli_fetch_assoc($sql)) {
            $dbpass = $row['password'];
            $role = $row['role'];
            $advanced = $row['isAdvanced'];
        }

        if($pass == $dbpass) {
            if($role == 1) {
                $user_type = "Admin";
                if($advanced == 1){
                    $user_type = "Super Admin";
                }
            } else {
                $user_type = "Security";
            }
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['type'] = $user_type;
            if($user_type == "Admin" || $user_type == "Super Admin") {
                header("location: Admin/index.php");
            } else {
                header("location: Security/index.php");
            }
        } else {
        $errors = "<p>Invalid password or login id</p>";
        }
    }

    //if user click continue button in forgot password form
    if(isset($_POST['check-email'])){
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $check_email = "SELECT * FROM users WHERE email='$email'";
        $run_sql = mysqli_query($conn, $check_email);
        if(mysqli_num_rows($run_sql) > 0){
            $code = rand(999999, 111111);
            $insert_code = "UPDATE users SET code = $code WHERE email = '$email'";
            $run_query =  mysqli_query($conn, $insert_code);
            if($run_query){

                //Send mail using Gmail service
                $mail = new PHPMailer(true);
                $mail->IsSMTP(); // telling the class to use SMTP
                $mail->SMTPAuth = true; // enable SMTP authentication
                $mail->SMTPSecure = "ssl"; // sets the prefix to the server
                $mail->Host = "smtp.gmail.com"; // sets GMAIL as the SMTP server
                $mail->Port = 465; // set the SMTP port for the GMAIL server
                $mail->Username = "visionnaim@gmail.com"; // GMAIL username
                $mail->Password = "psbtgxhnwogxjtba"; // GMAIL app password

                //Set variable value
                $email_from = "visionnaim@gmail.com";
                $name_from = "Vision Naim";
                //Email content
                $mail->AddAddress($email);
                $mail->SetFrom($email_from, $name_from);
                $mail->Subject = "Password Reset Code";
                $mail->Body = "Your password reset code is $code";

                try{
                    $mail->Send();
                    $info = "We've sent a password reset otp to your email - $email";
                    $_SESSION['info'] = $info;
                    $_SESSION['email'] = $email;
                    header('location: reset_password.php');
                    exit();
                } catch(Exception $e) {
                    $errors = "Failed while sending code!";
                }
            }else{
                $errors = "Something went wrong!";
            }
        }else{
            $errors = "This email address does not exist!";
        }
    }

    //if user click check reset otp button
    if(isset($_POST['check-reset-otp'])){
        unset($_SESSION['info']);
        if (is_numeric($_POST['otp'])) { 
            $otp_code = mysqli_real_escape_string($conn, $_POST['otp']);
            $check_code = "SELECT * FROM users WHERE code = $otp_code";
            $code_res = mysqli_query($conn, $check_code);
            if(mysqli_num_rows($code_res) > 0){
                $fetch_data = mysqli_fetch_assoc($code_res);
                $email = $fetch_data['email'];
                $_SESSION['email'] = $email;
                $info = "Please create a new password that you don't use on any other site.";
                $_SESSION['info'] = $info;
                header('location: new_password.php');
                exit();
            }else{
                $errors = "You've entered incorrect code!";
            }
        }else{
            $errors = "You should only enter number";
        }
    }

    //if user click change password button
    if(isset($_POST['change-password'])){
        unset($_SESSION['info']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
        if($password !== $cpassword){
            $errors = "New password and confirm password do not match! ";
        }else{
            $code = 0;
            $email = $_SESSION['email']; //getting this email using session
            $password = hash("sha256", $password);
            $update_pass = "UPDATE users SET code = $code, password = '$password' WHERE email = '$email'";
            $run_query = mysqli_query($conn, $update_pass);
            if($run_query){
                $info = "Your password changed. Now you can login with your new password.";
                $_SESSION['info'] = $info;
                header('Location: login.php');
            }else{
                $errors = "Failed to change your password!";
            }
        }
    }

    //if login now button click
    if(isset($_POST['login-now'])){
        header('Location: login.php');
    }


?>