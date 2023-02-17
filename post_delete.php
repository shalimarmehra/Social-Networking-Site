<div id="uploaded-posts" style='padding: 50px 50px 50px 100px;'>
    <div id="post-user-name">
        <?php
            $image = "images/male.png";
            if ($ROW_USER['gender'] == "female") 
            {
                $image = "images/female.png";
            }
            $image_class = new Image();
            if (file_exists($ROW_USER['profile_image'])) 
            {
                $image = $image_class->get_thumb_profile($ROW_USER['profile_image']);
            }
        ?>
        <img src="<?php echo $image ?>" id="post-user-image" alt="">
    </div>
    <div id="what-post">
        <div id="user-name-who-post">
                <a href="profile.php?id=$ROW[user_id]" id="user-name";>
            <?php
                echo htmlspecialchars($ROW_USER['first_name']) . " " . htmlspecialchars($ROW_USER['last_name']);
            ?>
                </a>
            <?php
                if ($ROW['is_profile_image']) 
                {
                    $pronoun = "his";
                    if ($ROW_USER['gender'] == "female") 
                    {
                        $pronoun = "her";
                    }
                    echo "<span style='font-weight:normal; color: #E0A96D;'> -- Updated $pronoun Profile Image</span>";
                }

                if ($ROW['is_cover_image']) 
                {
                    $pronoun = "his";
                    if ($ROW_USER['gender'] == "female") 
                    {
                        $pronoun = "her";
                    }
                    echo "<span style='font-weight:normal; color: #E0A96D;'> -- Updated $pronoun Cover Image</span>";
                }
                
            ?>
        </div>
        <?php
            echo htmlspecialchars($ROW['post']);
            
            if (file_exists($ROW['image'])) 
            {
                $ext = pathinfo($ROW['image'], PATHINFO_EXTENSION);
                $ext = strtolower($ext);
                
                if ($ext == "mp4")
                {
                    echo '<a href="single_post.php?id=' .  $ROW['post_id'] . '">';
                    echo "<video controls style='width: 750px; border-radius: 20px;'>
                                <source src='$ROW[image]'>
                        </video>";
                    echo '</a>';
                }
                elseif ($ext == "jpeg" || $ext = "jpg") 
                {
                    $post_image = $image_class->get_thumb_post($ROW['image']);

                    echo '<a href="single_post.php?id=' .  $ROW['post_id'] . '">';

                    echo "<img src='$post_image' style='width:95%; margin-top: 20px; border-radius: 20px;' />";
                    echo '</a>';
                }
            }
        ?>
        <br> 
    </div>
</div>