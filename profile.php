<?php

    include("classes/autoload.php");

    // Check if user is logged in
    $login = new Login();
    $_SESSION['social_network_user_id'] = isset($_SESSION['social_network_user_id']) ? $_SESSION['social_network_user_id'] : 0;

    $user_data = $login->check_login($_SESSION['social_network_user_id'], false);

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

    // For Posting
    if ($_SERVER['REQUEST_METHOD'] == "POST") 
    {
        if (isset($_POST['first_name'])) 
        {
            $settings_class = new Settings();
            $settings_class->save_settings($_POST ,$_SESSION['social_network_user_id']);
        }
        else
        {
            $post = new Post();
            $id = $_SESSION['social_network_user_id'];
            $result = $post->create_post($id, $_POST, $_FILES);
            
            if ($result == "") 
            {
                header("Location: profile.php");
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
    }

    // Collect Posts
    $post = new Post();
    $id = $user_data['user_id'];
    $posts = $post->get_posts($id);

    // Collect Friends

    $user = new User();
    $friends = $user->get_following($user_data['user_id'], "user");

    $image_class = new Image();

    if (isset($_GET['notif'])) 
    {
        notification_seen($_GET['notif']);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/profile.css">
    <link rel="stylesheet" href="style/header.css">
    <title>Profile | SOUL MEDIA</title>
</head>

<body>
    <!-- Main Header -->

    <?php 
        include("header.php");
    ?>

    <!-- Body Part -->
    <div class="body-part">
        <?php
            $image = "images/cover.jpg";
            if (file_exists($user_data['cover_image']))
            {
                $image = $image_class->get_thumb_cover($user_data['cover_image']);
            }
        ?>
        <img src="<?php echo $image ?>" alt="404 error" id="cover-image">
        <?php
            $image = "images/Male.png";
            if ($user_data['gender'] == 'Female') 
            {
                $image = "images/female.png";
            }

            if (file_exists($user_data['profile_image']))
            {
                $image = $image_class->get_thumb_profile($user_data['profile_image']);
            }
        ?>
        <img src="<?php echo $image ?>" alt="404 error" id="profile-image">

        <br>
        <?php if(i_own_content($user_data)):?>
        <span style="font-size: 12px; color: #DDC3A5;">
                <a href="change_profile_image.php?change=profile" id="change-profile-cover-image">Change Profile Image</a>&nbsp;|
                <a href="change_profile_image.php?change=cover" id="change-profile-cover-image">Change Cover Image</a>
        </span>
        <?php endif; ?>
        <h1 id="profile-name">
            <a href="profile.php?id=<?php echo $user_data['user_id']?>" id="user-name">
            <?php echo  $user_data['first_name'] . " " . $user_data['last_name']; ?>
        </h1>
            <span style="font-size: 17px; padding: 4px 30px 4px 30px; color: #201E20; background-color: #DDC3A5; border-radius: 20px;">@<?= $user_data['tag_name'] ?></span>
            </a>
    </div>
        <br>
        <?php
            $my_likes = "";
            if ($user_data['likes'] > 0) 
            {
                $my_likes = "(" . $user_data['likes'] . " Followers)";
            }
        ?>
        <a href="like.php?type=user&id=<?php echo $user_data['user_id']; ?>">
            <input id="like-button" type="submit" value="Follow <?php echo $my_likes ?>">
        </a>
    <!-- Profiles Options -->

    <div class="profile-option">
        <a href="index.php" style='text-decoration: none;'><div id="menu-button">
        <img src='images/icons/timeline.png' style='width: 30px; margin-bottom: -5px; margin-right: 7px;'>Timeline</div></a>
        <a href="profile.php?section=about&id=<?php echo $user_data['user_id']?>" style='text-decoration: none;'><div id="menu-button">
        <img src='images/icons/about.png' style='width: 30px; margin-bottom: -5px; margin-right: 7px;'>About</div></a>
        <a href="profile.php?section=followers&id=<?php echo $user_data['user_id']?>" style='text-decoration: none;'><div id="menu-button">
        <img src='images/icons/follower.png' style='width: 30px; margin-bottom: -5px; margin-right: 7px;'>Followers</div></a>
        <a href="profile.php?section=following&id=<?php echo $user_data['user_id']?>" style='text-decoration: none;'><div id="menu-button">
        <img src='images/icons/following.png' style='width: 30px; margin-bottom: -5px; margin-right: 7px;'>Following</div></a>
        <a href="profile.php?section=photos&id=<?php echo $user_data['user_id']?>" style='text-decoration: none;'><div id="menu-button">
        <img src='images/icons/image.png' style='width: 30px; margin-bottom: -5px; margin-right: 7px;'>Photos</div></a>
        <?php
        if ($user_data['user_id'] == $_SESSION['social_network_user_id']) 
        {
            echo "<a href='profile.php?section=settings&id='. $user_data[user_id] .' style='text-decoration: none;'><div id='menu-button'>
            <img src='images/icons/settings.png' style='width: 30px; margin-bottom: -5px; margin-right: 7px;'>Settings</div></a>";
        }
        ?>
        <?php  if(isset($USER)) : ?>
        <a href="logout.php" style='text-decoration: none;'><input type="button" id="logout-menu-button" value="Logout"></a>
        <?php endif; ?>
    </div>

    <!-- Below Profiles Options -->

    <?php
            $section = "default";
            if (isset($_GET['section'])) 
            {
                $section = $_GET['section'];
            }
            if ($section == "default") 
            {
                include("profile_content_default.php");
            }
            elseif ($section == "about") 
            {
                include("profile_content_about.php");
            }
            elseif ($section == "followers") 
            {
                include("profile_content_followers.php");
            }
            elseif ($section == "following")
            {
                include("profile_content_following.php");
            }
            elseif ($section == "photos") 
            {
                include("profile_content_photos.php");
            }
            elseif ($section == "settings") 
            {
                include("profile_content_settings.php");
            }
    ?>
</body>
</html>