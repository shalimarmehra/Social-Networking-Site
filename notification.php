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
    $User = new User();
    $image_class = new Image();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/notification.css">
    <title>Notifications | SOUL MEDIA</title>
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
                $DB = new Database();
                $id = esc($_SESSION['social_network_user_id']);
                $follow = array();

                // check content i follow
                $sql = "select * from content_i_follow where disabled = 0 && user_id = '$id' limit 100";
                $i_follow = $DB->read($sql);

                if (is_array($i_follow)) 
                {
                    $follow = array_column($i_follow, "content_id");
                }

                if (count($follow) > 0) 
                {
                    $str = "'" . implode("','", $follow) . "'";
                    $query = "select * from notification where (user_id != '$id' && content_owner = '$id') || (content_id in ($str)) order by id desc limit 30";
                }
                else
                {
                    $query = "select * from notification where user_id != '$id' && content_owner = '$id' order by id desc limit 30";
                }

                $data = $DB->read($query);
            ?>

            <?php if(is_array($data)): ?>
                <?php foreach ($data as $notif_row):
                    include("single_notification.php");
                endforeach; ?>
                <?php else: ?>
                    <h5 style="color: #DDC3A5; font-size: x-large; text-align: center; margin-bottom: 10px;">No Notification were found</h5>
            <?php endif; ?>
        </div>            
    </div>
</body>
</html>