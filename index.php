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

    // Posting Start Here
    if ($_SERVER['REQUEST_METHOD'] == "POST") 
    {
        $post = new Post();
        $id = $_SESSION['social_network_user_id'];
        $result = $post->create_post($id, $_POST, $_FILES);
        
        if ($result == "") 
        {
            header("Location: index.php");
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/index.css">
    <link rel="stylesheet" href="style/header.css">
    <title>Timeline | SOUL MEDIA</title>
</head>

<body>
    <!-- Main Header -->

    <?php 
        include("header.php");
    ?>

    <!-- Below Profiles Options -->

    <div class="after-menus">
        <div id="timeline-profile-section">
            <div id="timeline-profile-header">
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
                    <a href="profile.php" id="profile-name">
                    <img src="<?php echo $image ?>" alt="404 error" id="profile-image">
                    <br>
                        <?php echo $user_data['first_name'] . " " . $user_data['last_name']; ?>
                        <span style="font-size: 17px; padding: 4px 30px 4px 30px; color: #201E20; background-color: #DDC3A5; border-radius: 20px;">@<?= $user_data['tag_name'] ?></span>
                    </a>
                    <br><br>
                    <a href="index.php" style='text-decoration: none;'><div id="menu-button" >
                    <img src='images/icons/timeline.png' style='width: 40px; margin-bottom: -5px; margin-right: 7px; float: left; padding-left: 50px;'>Timeline</div></a>
                    <br><br>
                    <a href="profile.php?section=about&id=<?php echo $user_data['user_id']?>" style='text-decoration: none;'><div id="menu-button">
                    <img src='images/icons/about.png' style='width: 40px; margin-bottom: -5px; margin-right: 7px; float: left; padding-left: 50px;'>About</div></a>
                    <br><br>
                    <a href="profile.php?section=followers&id=<?php echo $user_data['user_id']?>" style='text-decoration: none;'><div id="menu-button">
                    <img src='images/icons/follower.png' style='width: 40px; margin-bottom: -5px; margin-right: 7px; float: left; padding-left: 50px;'>Followers</div></a>
                    <br><br>
                    <a href="profile.php?section=following&id=<?php echo $user_data['user_id']?>" style='text-decoration: none;'><div id="menu-button">
                    <img src='images/icons/following.png' style='width: 40px; margin-bottom: -5px; margin-right: 7px; float: left; padding-left: 50px;'>Following</div></a>
                    <br><br>
                    <a href="profile.php?section=photos&id=<?php echo $user_data['user_id']?>" style='text-decoration: none;'><div id="menu-button">
                    <img src='images/icons/image.png' style='width: 40px; margin-bottom: -5px; margin-right: 7px; float: left; padding-left: 50px;'>Photos</div></a>
                    <br><br>
                    <?php
                    if ($user_data['user_id'] == $_SESSION['social_network_user_id']) 
                    {
                        echo '<a href="profile.php?section=settings&id='. $user_data['user_id'] .'" style="text-decoration: none;"><div id="menu-button">
                        <img src="images/icons/settings.png" style="width: 40px; margin-bottom: -5px; margin-right: 7px; float: left; padding-left: 50px;">Settings</div></a>';
                    }
                    ?>
                    <br><br>
                    <a href="logout.php" style='text-decoration: none;'><input type="button" id="logout-menu-button" value="Logout"></a>
                    <br><br>
            </div>
        </div>

        <!-- Post Area -->

        <div id="timeline-section">
            <div id="timeline-post">
            <form action="" method="POST" enctype="multipart/form-data">
                    <textarea name="post" id="post-input" cols="84" rows="4" placeholder="What's on Your Mind ?"></textarea>
                    <input type="file" name="file" id="post-file">
                    <input type="submit" id="post_button" value="POST">
                </form>
            </div>

            <!-- Posts -->
            <div id="post-bar">
                <?php 

                    $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
                    $page_number = ($page_number < 1) ? 1 : $page_number ;

                    $limit = 10;
                    $offset = ($page_number - 1) * $limit;

                    $DB = new Database();
                    $user_class = new User();
                    $image_class = new User();
                    $posts = new Post();

                    $followers = $user_class->get_following($_SESSION['social_network_user_id'], "user");
                    $follower_ids = false;
                    if (is_array($followers)) 
                    {
                        $follower_ids = array_column($followers, "user_id");
                        $follower_ids = implode("','", $follower_ids);
                    }

                    if ($follower_ids) 
                    {
                        $my_user_id = $_SESSION['social_network_user_id'];
                        $sql = "select * from posts where parent = 0 and (user_id = '$my_user_id' || user_id in('" . $follower_ids . "')) order by id desc limit $limit offset $offset ";
                        $posts = $DB->read($sql);
                    }
                    if (isset($posts) && $posts) 
                    {
                        foreach ($posts as $ROW) 
                        {
                            $user = new user();
                            $ROW_USER = $user->get_user($ROW['user_id']);
                            include("post.php");
                        }
                    }
                    // Get Current URL
                    $pg = pagination_link();
                ?>

                    <a href="<?php echo $pg['next_page'] ?>">
                        <input type="button" id="switch-posts" value="Next Page" style="float : right ;">
                    </a>
                    <a href="<?php echo $pg['prev_page'] ?>">
                        <input type="button" id="switch-posts" value="Previous Page" style="float : left ;">
                    </a>
            </div>
        </div>
    </div>
</body>

</html>