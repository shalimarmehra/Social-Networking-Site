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

    if ($_SERVER['REQUEST_METHOD'] == "POST") 
    {
            $post = new Post();
            $id = $_SESSION['social_network_user_id'];
            $result = $post->create_post($id, $_POST, $_FILES);
            
            if ($result == "") 
            {
                header("Location: single_post.php?id=$_GET[id]");
                die;
            }
            else
            {
                echo "<div style='text-align:center;font-size:20px;color:#E0A96D;'>";
                echo "<script>alert('Please Enter Correct Information !')</script>";
                echo $result;
                echo "</div>";
        }
    }

    $Post = new Post();
    $ROW = false;

    $error = "";
    if (isset($_GET['id']))
    {
        $ROW = $Post->get_one_post($_GET['id']);
    }
    else
    {
        $error = "";
        echo "<h5 style='color: #DDC3A5; font-size: x-large; text-align: center; margin-bottom: 10px;'>No Post was Found !</h5>";
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/single_post.css">
    <title>Single Post | SOUL MEDIA</title>
</head>

<body>
    <!-- Main Header -->
    <?php 
        include("header.php");
    ?>

    <!-- Post Area -->
    <div id="timeline-section" style='margin: 20px 350px 0px 350px;'>
        <div id="timeline-post">
            <?php

                // check if this is from notification
                if (isset($_GET['notif'])) 
                {
                    notification_seen($_GET['notif']);
                }
                
                $User = new User();
                $image_class = new Image();
                if (is_array($ROW)) 
                {
                    $user = new user();
                    $ROW_USER = $user->get_user($ROW['user_id']);
                    if ($ROW['parent'] == 0) 
                    {
                        include("post.php");
                    }
                    else
                    {
                        $COMMENT = $ROW;
                        include("comment.php");
                    }
                }
            ?>
            <?php if ($ROW['parent'] == 0): ?>
                <div id="timeline-post">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <textarea name="post" id="post-input" cols="84" rows="4" placeholder="What is in Your Mind ?"></textarea>
                        <input type="hidden" name="parent" value="<?php echo $ROW['post_id'] ?>" id="post-file">
                        <input type="file" name="file" id="post-file">
                        <input type="submit" id="post_button" value="POST">
                    </form>
                </div>
            <?php else: ?>
                <div id="timeline-post">
                <a href="single_post.php?id=<?php echo $ROW['parent'] ?>">
                    <input id="post_button" style="float: left; cursor: pointer; margin-left: 990px" type="button"  value="Back To Main Post">
                </a>
                </div>
            <?php endif; ?>
            
            <?php
                    $comments = $Post->get_comments($ROW['post_id']);

                    if (is_array($comments)) 
                    {
                        foreach ($comments as $COMMENT) 
                        {
                            $ROW_USER = $user->get_user($COMMENT['user_id']);
                            include("comment.php");
                        }
                    }
                    // Get Current URL
                    $pg = pagination_link();
            ?>
                <?php if ($ROW['parent'] == 0):  ?>
                    <a href="<?php echo $pg['next_page'] ?>">
                        <input type="button" id="switch-posts" value="Next Page" style="float : right ;">
                    </a>
                    <a href="<?php echo $pg['prev_page'] ?>">
                        <input type="button" id="switch-posts" value="Previous Page" style="float : left ;">
                    </a>
                <?php endif;  ?>
        </div>
    </div>
</body>

</html>