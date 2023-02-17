<?php

    include("classes/autoload.php");
    $login = new Login();
    $user_data = $login->check_login($_SESSION['social_network_user_id']);

    $USER = $user_data;

    if (isset($_GET['id']) && is_numeric($_GET['id'])) 
    {
        $profile = new Profile();
        $profile_data = $profile->get_profile($_GET['id']);
    
        if (is_array($profile_data)) 
        {
            $user_data = $profile_data[0];
        }
    }
    
    $Post = new Post();

    if (isset($_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], "delete.php")) 
        {
            $_SESSION['$return_to'] = $_SERVER['HTTP_REFERER'];
        }

    $error = "";
    if (isset($_GET['id'])) 
    {
        $ROW = $Post->get_one_post($_GET['id']);
        if (!$ROW)
        {
            $error = "";
            echo "<h5 style='color: #DDC3A5; font-size: x-large; text-align: center; margin-bottom: 10px;'>No such Post was Found !</h5>";
        }
        else
        {
            if (!i_own_content($ROW))
            {
                $error = ".";
                echo "<h5 style='color: #DDC3A5; font-size: x-large; text-align: center; margin-bottom: 10px;'>Access Denied ! You can't delete this post.</h5>";
            }
        }
    }
    else
    {
        $error = "";
        echo "<h5 style='color: #DDC3A5; font-size: x-large; text-align: center; margin-bottom: 10px;'>No such Post was Found !</h5>";
    }

    // if Something was posted
    if ($error == "" && $_SERVER['REQUEST_METHOD'] == "POST")
    {
        $Post->delete_post($_POST['post_id']);
        header("Location: ". $_SESSION['$return_to']);
        die;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/header.css">
    <title>Delete Post | SOUL MEDIA</title>
</head>
<body>
    <!-- Main Header -->
    <?php 
        include("header.php");
    ?>

    <!-- Post Area -->
    <div id="timeline-section" style='margin: 20px 350px 0px 350px;'>
        <div id="timeline-post">
            <form method="POST">
                <?php
                if ($error != "") 
                {
                    echo $error;
                }
                else
                {
                    echo "<h5 style='color: #DDC3A5; font-size: xx-large; text-align: center; margin-bottom: -10px;'>Are You Sure You Want To Delete This Post ?</h5>";
                    echo "<br>";
                    $user = new User();
                    $ROW_USER = $user->get_user($ROW['user_id']);
                    include("post_delete.php");
                    echo "<br>";
                    echo "<input type='submit' name='post_id' id='post_button' value='Delete'>";
                    echo "<input type='hidden' name='post_id' id='post_button' value='$ROW[post_id]'>";
                }
                ?>
            </form>
        </div>            
    </div>
</body>
</html>