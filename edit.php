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
            if ($ROW['user_id'] != $_SESSION['social_network_user_id']) 
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

    if (isset($_SERVER['HTTP_REFERER']) && !strstr($_SERVER['HTTP_REFERER'], "edit.php")) 
        {
            $_SESSION['$return_to'] = $_SERVER['HTTP_REFERER'];
        }

    // if Something was posted
    if ($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $Post->edit_post($_POST, $_FILES);
        
        $_SESSION['$return_to'] = "profile.php";
        
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
    <title>Edit Post | SOUL MEDIA</title>
</head>
<body>
    <!-- Main Header -->
    <?php 
        include("header.php");
    ?>

    <!-- Post Area -->
    <div id="timeline-section" style='margin: 20px 350px 0px 350px;'>
        <div id="timeline-post">
            <form method="POST" enctype="multipart/form-data">
                <?php
                if ($error != "") 
                {
                    echo $error;
                }
                else
                {
                    echo "<h5 style='color: #DDC3A5; font-size: xx-large; text-align: center; margin-bottom: -10px;'>Are You Sure You Want To Edit This Post ?</h5>";
                    echo "<br>";
                    echo '<textarea name="post" id="post-input" cols="84" rows="4" placeholder="What is in Your Mind ?"> ' . $ROW['post'] . '</textarea>
                    <input type="file" name="file" id="post-file">';
                    echo "<input type='submit' name='post_id' id='post_button' value='Save'>";
                    echo "<input type='hidden' name='post_id' id='post_button' value='$ROW[post_id]'>";

                    echo "<div style='margin: 20px; margin-left: 350px;'>";
                    if (file_exists($ROW['image'])) 
                    {
                        $image_class = new Image();

                        $ext = pathinfo($ROW['image'], PATHINFO_EXTENSION);
                        $ext = strtolower($ext);
                        
                        if ($ext == "mp4")
                        {
                            echo "<video controls style='width: 70%; border-radius: 20px;'>
                                        <source src='$ROW[image]'>
                                </video>";
                        }
                        elseif ($ext == "jpeg" || $ext = "jpg") 
                        {
                            $post_image = $image_class->get_thumb_post($ROW['image']);

                            echo "<img src='$post_image' style='width: 50%;'/>";
                        }
                    }
                    echo "</div>";
                }
                ?>
            </form>
        </div>            
    </div>
</body>
</html>