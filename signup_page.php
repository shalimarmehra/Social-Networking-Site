<?php

    include("classes/connection.php");
    include("classes/signup.php");

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {

        $signup = new Signup();
        $result = $signup->evaluate($_POST);

        if ($result != "") 
        {

            echo "<div style='text-align:center;font-size:20px;color:#E0A96D;'>";
            echo "<script>alert('The Following errors occurred !')</script>";
            echo $result;
            echo "</div>";
        }
        else
        {
            header("Location: login_page.php");
            die;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/sign_up.css">
    <title>Sign up</title>
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
                        </form>
                    </h4>
                </div>
            </div>
            <br><br>
            <hr color="#E0A96D" width="70%">
            <br>
        <form method="POST" action="">
        <div class="second-main">
                <table class="fill-field">
                    <tr>
                        <td>
                            <h3 class="internal-first-field">First Name </h3>
                        </td>
                        <td>
                            <h3 color="#DDC3A5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</h3>
                        </td>
                        <td>
                            <h3 class="internal-last-field"><input type="text" alt="First Name" placeholder="First Name"
                                    name="first_name" class="input" required="required"></h3>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3 class="internal-first-field">Last Name </h3>
                        </td>
                        <td>
                            <h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</h3>
                        </td>
                        <td>
                            <h3 class="internal-last-field"><input type="text" alt="Last Name" placeholder="Last Name"
                                    name="last_name" class="input" required="required"></h3>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3 class="internal-first-field">Enter E-mail </h3>
                        </td>
                        <td>
                            <h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</h3>
                        </td>
                        <td>
                            <h3 class="internal-last-field"><input type="email" alt="email" placeholder="name@gmail.com"
                                    name="email" class="input" required="required"></h3>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3 class="internal-first-field">Select Your Country </h3>
                        </td>
                        <td>
                            <h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</h3>
                        </td>
                        <td>
                            <h3 class="internal-last-field">
                                <select name="country" id="select-country" required="required">
                                    <option>India</option>
                                    <option>Pakistan</option>
                                    <option>USA</option>
                                    <option>Canada</option>
                                    <option>Russia</option>
                                    <option>Ukraine</option>
                                </select>
                            </h3>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3 class="internal-first-field">Select DOB </h3>
                        </td>
                        <td>
                            <h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</h3>
                        </td>
                        <td>
                            <h3 class="internal-last-field"><input type="date" alt="email" placeholder="E-mail"
                                    name="birthday" class="input" required="required"></h3>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3 class="internal-first-field">Select Your Gender </h3>
                        </td>
                        <td>
                            <h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</h3>
                        </td>
                        <td>
                            <h3 class="internal-last-field">
                                <select name="gender" id="select-gender" required="required">
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Others</option>
                                </select>
                            </h3>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3 class="internal-first-field">Enter Password </h3>
                        </td>
                        <td>
                            <h3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;</h3>
                        </td>
                        <td>
                            <h3 class="internal-last-field"><input type="password" alt="Password"
                                    placeholder="***********" name="password" class="input" required="required"></h3>
                        </td>
                    </tr>
                </table>
            </div>
                <br>
                <hr color="#E0A96D" width="70%">
                <br>
            <div id="create-account">
                <input type="submit" class="sign-up"></input>
                <p class="new-one">If you have account <u><a href="login_page.php"><b id="you-have">Login</b></a></u> !</p>
                <br><br>
            </div>
            </form>
    </div>
</body>

</html>