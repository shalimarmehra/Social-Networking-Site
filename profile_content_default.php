<div class="after-menus">
        <div id="friends-section">
            <div id="friend-header">
                Following<br>
                <?php 
                    if ($friends) 
                    {
                        foreach ($friends as $friend) 
                        {
                            $FRIEND_ROW = $user->get_user($friend['user_id']);
                            include("user.php");
                        }
                    }
                ?>
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
                    if ($posts) 
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