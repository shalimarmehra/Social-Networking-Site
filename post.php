<div id="uploaded-posts">
    <div id="post-user-name">
        <?php
            $image_class = new Image();
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
                <a href="profile.php?id=$ROW[user_id]" id="user-name";>
            <?php
                echo htmlspecialchars($ROW_USER['first_name']) . " " . htmlspecialchars($ROW_USER['last_name']);
                echo "&nbsp;&nbsp;";
                echo "<span style='font-size: 17px; padding: 2px 15px 2px 15px; color: #201E20; background-color: #DDC3A5; border-radius: 10px;'>@$ROW_USER[tag_name] </span>";
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
            echo check_tags($ROW['post']);
            
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
        <br><br>
        <div id="like-comment">
            <?php
                $likes = "";

                $likes = ($ROW['likes'] > 0) ? "(" . $ROW['likes'] . ")" : "" ;

            ?>
            <a onclick="like_post(event)" href="like.php?type=post&id=<?php echo $ROW['post_id'] ?>" style="color: #DDC3A5; text-decoration: none;">Like<?php echo $likes?></a> . 
            
            <?php
            $comments ="";

            if ($ROW['comments'] > 0) 
            {
                $comments = "(" . $ROW['comments'] . ")";
            }

            ?>
            <a href="single_post.php?id=<?php echo $ROW['post_id'] ?>" style="color: #DDC3A5; text-decoration: none;">Comment<?php echo $comments ?></a> .
            <span>
                <?php
                $date = date("jS M Y H:i:s", strtotime($ROW['date']));
                echo $date;
                // echo $ROW['date']
                // echo Time::get_time($ROW['date']);
                ?>
            </span>

            <?php
                $ext = pathinfo($ROW['image'], PATHINFO_EXTENSION);
                $ext = strtolower($ext);

                if ($ROW['has_image'] && ($ext == "jpeg" || $ext == "jpg")) 
                {
                    echo "<a href='image_view.php?id=$ROW[post_id]' style='color: #DDC3A5; text-decoration: none;'>";
                    echo " . View Full Image";
                    echo "</a>";
                }

            ?>

            <span style="float: right;">
            <?php
            $post = new Post();
            if ($post->i_own_post($ROW['post_id'],$_SESSION['social_network_user_id'])) 
            {   
                echo "
                &nbsp;.
                <a href='edit.php?id=$ROW[post_id]' style='text-decoration: none; color: #DDC3A5;'>
                Edit
                </a>
                &nbsp;.
                <a href='delete.php?id=$ROW[post_id]' style='text-decoration: none; color: #DDC3A5; '>
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
                    $sql = "select likes from likes where type = 'post' &&  content_id = '$ROW[post_id]' limit 1 ";
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
                echo "<a id='info_$ROW[post_id]' href='likes.php?type=post&id= $ROW[post_id]' style='text-decoration: none; color: #E0A96D;'>";
                if ($ROW['likes'] > 0) 
                {
                    echo "<br>";
                    if ($ROW['likes'] == 1) 
                    {
                        if ($i_liked) 
                        {
                            echo "<div style='text-align: left;'>You Liked This Post </div>";
                        }
                        else
                        {
                            echo "<div style='text-align: left;'>1 Person Liked This Post </div>";
                        }
                    }
                    else
                    {
                        if ($i_liked)
                        {
                            $text = "others";
                            if ($ROW['likes'] - 1 == 1) 
                            {
                                $text = "other";
                            }
                            echo "<div style='text-align: left;'>You and " . ($ROW['likes'] - 1 ). " $text  Liked This Post </div>";
                        }
                        else
                        {
                            echo "<div style='text-align: left;'>" . $ROW['likes'] . " Other Liked This Post </div>";
                        }
                    }
                }
                echo "</a>";
            ?>
        </div> 
    </div>
</div>

<script type="text/javascript">

    function ajax_send(data, element)
    {
        var ajax = new XMLHttpRequest();

        ajax.addEventListener('readystatechange', function(){

            if (ajax.readyState == 4 && ajax.status == 200) 
            {
                response(ajax.responseText, element);
            }
        });

        data = JSON.stringify(data);

        ajax.open("post","ajax.php",true);
        ajax.send(data);
    }

    function response(result, element)
    {
        if (result != "") 
        {
            var obj = JSON.parse(result);

            if (typeof obj.action != 'undefined') 
            {
                if (obj.action == 'like_post') 
                {
                    var likes = "";

                    if (typeof obj.likes != 'undefined')
                    {
                        likes = (parseInt(obj.likes) > 0) ? "Like(" +obj.likes+ ")" : "Like" ;
                        element.innerHTML = likes;
                    }

                    if (typeof obj.info != 'undefined')
                    {
                        var info_element = document.getElementById(obj.id);
                        info_element.innerHTML = obj.info;
                    }
                }
            }
        }
    }

    function like_post(e)
    {
        e.preventDefault();

        var link = e.target.href;

        var data = {};
        data.link = link;
        data.action = "like_post";

        ajax_send(data, e.target);
    }

</script>