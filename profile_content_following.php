<?php
    error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Following</title>
    <link rel="stylesheet" href="style/profile_content_following.css">
</head>
<body>
        
        <h3>All Followings<br>
        <span style="font-size: 17px; padding: 4px 30px 4px 30px; color: #201E20; background-color: #DDC3A5; border-radius: 20px;">@<?= $user_data['tag_name'] ?></span>
        </h3>
        <div id="timeline-section-for-following">
        <?php
        
            $image_class = new Image();
            $post_class = new Post();
            $user_class = new User();
            $following = $user_class->get_following($user_data['user_id'], "user");
            
            if (is_array($following))
            {
                foreach ($following  as $follower)
                {
                    $FRIEND_ROW = $user_class->get_user($follower['user_id']);
                    include("user.php");
                }
            }
            else
            {
                echo "<div style='color: #DDC3A5; text-align: center; font-size: 25px; padding: 20px; margin-left: 400px;'>This user isn't Following anyone !</div>";
            }
        
        
        ?>
        </div>
    
</body>
</html>