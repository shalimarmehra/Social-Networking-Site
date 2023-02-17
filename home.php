<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style/style.css" />
    <!-- <link rel="stylesheet" href="index.php"> -->
    <title>SOUL MEDIA</title>
</head>

<body>
    <div>
        <div class="navbar">
            <div class="main-navbar"><a href="index.php">SOUL MEDIA</a></div>
            <div class="login-sign_up">
                <h4>
                    <form method="POST" action="">
                        <button name="login" class="login-sign_up">LOGIN</button>
                        <?php
                            if(isset($_POST['login'])){
                                echo "<script>window.open('login_page.php','_self')</script>";
                            }
                        ?>
                        <button name="signup" class="login-sign_up">SIGN UP</button>
                        <?php
                            if(isset($_POST['signup'])){
                                echo "<script>window.open('signup_page.php','_self')</script>";
                            }
                        ?>
                    </form>
                </h4>
            </div>
        </div>
        <hr width="90%" color="#DDC3A5"/>
        <div class="second-stage">
            <h1 id="one">Connect With Someone Who Special for you <br> & Build your thoughts & share with them.</h1>
        </div>
        <div class="footer">
            <h5>© 2022 SOUL MEDIA</h5>
        </div>
    </div>
</body>

</html>