<div id="uploaded-posts">
    <div id="post-user-name">
        <?php
            $image = "images/male.png";
            if ($ROW_USER['gender'] == "Female") 
            {
                $image = "images/female.png";
            }
            
            if (file_exists($ROW_USER['profile_image'])) 
            {
                $image = $image_class->get_thumb_profile($ROW_USER['profile_image']);
            }
        ?>
        <img src="<?php echo $image ?>" id="post-user-image" alt="">
    </div>
    <div id="what-post">
        <div id="user-name-who-post">
            <br>
                <a href="profile.php?id=$ROW[user_id]" id="user-name";>
            <?php
                echo htmlspecialchars($ROW_USER['first_name']) . " " . htmlspecialchars($ROW_USER['last_name']);
            ?>
                </a>
            <?php
                if ($COMMENT['is_profile_image']) 
                {
                    $pronoun = "his";
                    if ($COMMENT_USER['gender'] == "female") 
                    {
                        $pronoun = "her";
                    }
                    echo "<span style='font-weight:normal; color: #E0A96D;'> -- Updated $pronoun Profile Image</span>";
                }

                if ($COMMENT['is_cover_image']) 
                {
                    $pronoun = "his";
                    if ($COMMENT_USER['gender'] == "female") 
                    {
                        $pronoun = "her";
                    }
                    echo "<span style='font-weight:normal; color: #E0A96D;'> -- Updated $pronoun Cover Image</span>";
                }
                
            ?>
        </div>
        <br>
        <?php
            echo check_tags($COMMENT['post']);
            
            if (file_exists($COMMENT['image'])) 
            {
                $post_image = $image_class->get_thumb_post($COMMENT['image']);
                echo "<img src='$post_image' style='width:95%; margin-top: 20px;' />";
            }
        ?>
        <br>
        <br>
        <div id="like-comment">
            <?php
                $likes = "";

                $likes = ($COMMENT['likes'] > 0) ? "(" . $COMMENT['likes'] . ")" : "" ;

            ?>
            <a href="like.php?type=post&id=<?php echo $COMMENT['post_id'] ?>" style="color: #DDC3A5; text-decoration: none;">Like<?php echo $likes?></a> . 
            
            <!-- <a href="single_post.php?id=<?php echo $COMMENT['post_id'] ?>" style="color: #DDC3A5; text-decoration: none;">Comment</a> . -->
            <span>
                <?php
                // echo Time::get_time($ROW['date']);
                echo $COMMENT['date'];
                ?>
            </span>

            <?php
                if ($COMMENT['has_image']) 
                {
                    echo "<a href='image_view.php?id=$COMMENT[post_id]' style='color: #DDC3A5; text-decoration: none;'>";
                    echo " . View Full Image";
                    echo "</a>";
                }

            ?>

            <span style="float: right;">
            <?php
            $post = new Post();
            if ($post->i_own_post($COMMENT['post_id'],$_SESSION['social_network_user_id'])) 
            {   
                echo "
                &nbsp;.
                <a href='edit.php?id=$COMMENT[post_id]' style='text-decoration: none; color: #DDC3A5;'>    
                Edit
                </a>";
            }
            ?>
            <?php
            
            if (i_own_content($COMMENT))
            {
                echo "
                &nbsp;.
                <a href='delete.php?id=$COMMENT[post_id]' style='text-decoration: none; color: #DDC3A5;'>
                Delete
                </a>";
            }
            ?>
            </span>
            <?php
                $i_liked = false;
                if (isset($_SESSION['social_network_user_id'])) 
                {
                    $DB = new Database();
                    $sql = "select likes from likes where type = 'post' &&  content_id = '$COMMENT[post_id]' limit 1 ";
                    $result = $DB->read($sql);
                    
                    if (is_array($result)) 
                    {
                        $likes = json_decode($result[0]['likes'], true);
                        
                        $user_ids = array_column($likes, "user_id");
                        
                        if (in_array($_SESSION['social_network_user_id'], $user_ids))
                        {
                            $i_liked = true;
                        }
                    }
                }
                if ($COMMENT['likes'] > 0) 
                {
                    echo "<br>";
                    echo "<a href='likes.php?type=post&id= $COMMENT[post_id]' style='text-decoration: none; color: #E0A96D;'>";
                    if ($COMMENT['likes'] == 1) 
                    {
                        if ($i_liked) 
                        {
                            echo "<div style='text-align: left;'>You Liked This Comment </div>";
                        }
                        else
                        {
                            echo "<div style='text-align: left;'>1 Person Liked This Comment </div>";
                        }
                    }
                    else
                    {
                        if ($i_liked)
                        {
                            $text = "others";
                            if ($COMMENT['likes'] - 1 == 1) 
                            {
                                $text = "other";
                            }
                            echo "<div style='text-align: left;'>You and " . ($COMMENT['likes'] - 1 ). " $text  Liked This Comment </div>";
                        }
                        else
                        {
                            echo "<div style='text-align: left;'>" . $COMMENT['likes'] . " Other Liked This Comment </div>";
                        }
                    }
                }

                echo "</a>";
            ?>
        </div> 
    </div>
</div>