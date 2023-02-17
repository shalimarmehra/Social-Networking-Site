<?php
    $actor = $User->get_user($notif_row['user_id']);
    $owner = $User->get_user($notif_row['content_owner']);
    $id = esc($_SESSION['social_network_user_id']);

    $link = "";

    if ($notif_row['content_type'] == "post") 
    {
        $link = "single_post.php?id=" . $notif_row['content_id'] . "&notif=" . $notif_row['id'];
    }
    elseif ($notif_row['content_type'] == "profile") 
    {
        $link = "profile.php?id=" . $notif_row['user_id'] . "&notif=" . $notif_row['id'];
    }
    elseif ($notif_row['content_type'] == "comment") 
    {
        $link = "single_post.php?id=" . $notif_row['content_id'] . "&notif=" . $notif_row['id'];
    }

    $query = "select * from notification_seen where user_id = '$id' && notification_id = '$notif_row[id]' limit 1";
    $seen = $DB->read($query);

    if (is_array($seen)) 
    {
        $color = "#E0A96D";
    }
    else
    {
        $color = "#E0A96D";
    }
?>
<a href="<?php echo $link ?>" style="text-decoration: none;">
    <div id="notification" style="background-color: <?= $color ?>;">
        <?php
            $image_class = new Image();
                $image = "images/male.png";
                if ($actor['gender'] == "Female") 
                {
                    $image = "images/female.png";
                }
                
                if (file_exists($actor['profile_image'])) 
                {
                    $image = $image_class->get_thumb_profile($actor['profile_image']);
                }
                echo "<img src='$image' style='width: 55px; margin-right: 20px; float: left; border-radius: 10px;'/>";
        ?>
            <?php
            if (is_array($actor) && is_array($owner)) 
            {
                if ($actor['user_id'] != $id)
                {
                    echo $actor['first_name'] . " " . $actor['last_name'];
                }
                else
                {
                    echo "You ";
                }

                if ($notif_row['activity'] == "like") 
                {
                    echo " liked ";
                }
                elseif ($notif_row['activity'] == "follow") 
                {
                    echo " followed ";
                }
                elseif ($notif_row['activity'] == "comment") 
                {
                    echo " commented on ";
                }
                elseif ($notif_row['activity'] == "tag") 
                {
                    echo " tagged ";
                }

                if ($owner['user_id'] != $id && $notif_row['activity'] != "tag") 
                {
                    echo $owner['first_name'] . " " . $owner['last_name'] . "'s ";
                }
                elseif ($notif_row['activity'] == "tag")
                {
                    echo " you in a ";
                }
                else
                {
                    echo " your ";
                }

                $content_row = $Post->get_one_post($notif_row['content_id']);
                if ($notif_row['content_type'] == "post") 
                {
                    if ($content_row['has_image']) 
                    {
                        echo "image";
                        if (file_exists($content_row['image'])) 
                        {
                            $post_image = $image_class->get_thumb_post($content_row['image']);
                            echo "<img src='$post_image' style='width: 65px; margin-right: 20px; float: right; border-radius: 10px;' />";
                        }
                    }
                    else
                    {
                        echo $notif_row['content_type'];
                        echo "<b><span style='float: right; font-size: 17px; color: #201E20; background-color: #E0A96D; display: inline-block; margin-right: 10px; margin-top: 12px;'>'" .  htmlspecialchars(substr($content_row['post'],0,15)) . "'</span></b>";
                    }
                }
                else
                {
                    echo $notif_row['content_type'];
                    // echo "<b><span style='float: right; font-size: 17px; color: #201E20; background-color: #E0A96D; display: inline-block; margin-right: 10px; margin-top: 12px;'>" .  htmlspecialchars(substr($content_row['post'],0,15)) . "</span></b>";
                }

                $date = date("jS M Y H:i:s", strtotime($notif_row['date']));
                echo  "<br>
                    <b><span style='float: left; font-size: 17px; color: #201E20; background-color: #E0A96D; display: inline-block; margin-right: 10px; 
                    margin-top: -5px; padding-top: 5px;'>$date</span></b>
                ";
            }
        ?>
    </div>
</a>