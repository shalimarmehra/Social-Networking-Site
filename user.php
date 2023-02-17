<div id="friends-info" style="text-align: center;">
        <?php
            $image = "images/male.png";
            if ($FRIEND_ROW['gender'] == "Female") 
            {
                $image = "images/female.png";
            }

            if (file_exists($FRIEND_ROW['profile_image'])) 
            {
                $image_class = new Image();
                $image = $image_class->get_thumb_profile($FRIEND_ROW['profile_image']);
            }
            
        ?>

            <div id="search-image">
                <a href="profile.php?id=<?php echo $FRIEND_ROW['user_id']; ?>" id="friends-name" style="text-decoration: none;">
                <img id="friends-img" style="border-radius: 10px;" src="<?php echo $image ?>" >
                <br><br>
                <span id="search-users"><?php echo $FRIEND_ROW['first_name'] . " " . $FRIEND_ROW['last_name'];?></span>
                <br>
                <span id="followers" style="font-size: 14px; padding: 4px 30px 4px 30px; color: #201E20; background-color: #DDC3A5; border-radius: 20px;">@<?= $FRIEND_ROW['tag_name'] ?></span>
                </a>
                <br>
                <?php
                    $online = "Last seen : unknown";
                    if($FRIEND_ROW['online'] > 0)
                    {
                        $online = $FRIEND_ROW['online'];
                        $current_time = time();
                        $threshold = 60 * 2; // 2 Minutes
                        
                        if (($current_time - $online) < $threshold) 
                        {
                            
                            $online = "<span style=' color: #228B22; background-color: #DDC3A5; padding: 0px 5px;'>Online</span>";
                        }
                        else
                        {
                            $online = "Last seen : " . $FRIEND_ROW['date'] ;
                            // $online = "Last seen : " . Time::get_time(date("Y-m-d H:i:s",$online));
                        }
                    }
                ?>
                <span id="search-users" style="color: #201E20; background-color: #DDC3A5; border-radius: 20px; padding: 1px 20px; font-size: 15px;"><?php echo $online ?></span>
                <br><br>
            </div>
            
</div>