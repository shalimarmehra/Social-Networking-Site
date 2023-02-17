    <?php
        $corner_image = "images/male.png";
        if (isset($USER))
        {
            if(file_exists($USER['profile_image']))
            {
                $image_class = new Image();
                $corner_image = $image_class->get_thumb_profile($USER['profile_image']);
            }
            else
            {
                if ($USER['gender'] == "Female") 
                {
                    $corner_image = "images/female.png";
                }
            }
        } 
    ?>
        <form method="get" action="search.php">
        <div class="main">
            <div class="header-one"><a href="index.php" id="main-logo">SOUL MEDIA</a></div>
            <div class="header-two"><input type="text" name='find' id="search" placeholder="search for people"></div>

            <a href="notification.php">
                <span style="display: inline-block; position: relative;">
                    <?php
                        $notif = check_notifications();
                    ?>
                    <?php if($notif > 0): ?>
                        <b><div style="background-color: red; color: #DDC3A5; position: absolute; width: 20px; height: 20px; border-radius: 50%; padding: 5px; font-size: 18px; margin-left: 60px; margin-top: 15px;">&nbsp;<?= $notif ?></div></b>
                    <?php endif; ?>
                        <img id="notification-btn" src="images/notif.svg">
                </span>
            </a>
            <?php  if(isset($USER)) : ?>
                <div class="header-three"><a href="profile.php"><img id="user_image" src="<?php echo $corner_image ?>" alt=""></a></div>
            <?php else: ?>
                <a href="login_page.php" class="login-sign-up">Login</a>
            <?php endif; ?>
        </div>
</div>
</form>
