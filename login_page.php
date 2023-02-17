<?php

session_start();

    include("classes/connection.php");
    include("classes/login.php");

    
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {

        $login = new Login();
        $result = $login->evaluate($_POST);

        if ($result != "") 
        {

            echo "<div style='text-align:center;font-size:20px;color:#E0A96D;'>";
            echo "<script>alert('Please Enter Correct Information !')</script>";
            echo $result;
            echo "</div>";
        }
        else
        {
            header("Location: profile.php");
            die;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style/login_page.css" />
    <title>Login</title>
</head>

<body>
    <div class="head">
        <div class="navbar">
            <div class="main-navbar"><a href="index.php">SOUL MEDIA</a></div>
            <div class="login-sign-up-main">
                <h4>
                    <form method="POST" action="">
                        <a href="login_page.php" class="login-sign-up">Login</a>
                        <a href="signup_page.php" class="login-sign-up">Sign UP</a>
                </h4>
            </div>
        </div>
        <div class="second-main">
            <hr class="hr" color="#E0A96D">
            <br>
            <div class="tried">
                E-mail
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="text" name="email" alt="Username" placeholder="name@gmail.com" class="input" required="required">
                <br><br>
                Password
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="password" name="password" alt="Password" placeholder="***********" class="input" required="required">
            </div>
            <br>
            <hr class="hr" color="#E0A96D">
            <button type="submit" class="login">LOGIN</button>
            <p class="new-one">If you don't have account <u><a href="signup_page.php"><b id="you-have">Signup</b></a></u> !</p>
        </form>
        </div>
    </div>
</body>

</html>