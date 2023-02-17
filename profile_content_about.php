<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | About me</title>
    <link rel="stylesheet" href="style/profile_content_about.css">
</head>
<body>
        
        <h3>About me<br>
        <span style="font-size: 17px; padding: 4px 30px 4px 30px; color: #201E20; background-color: #DDC3A5; border-radius: 20px;">@<?= $user_data['tag_name'] ?></span>
        </h3>
        <div id="timeline-section-for-settings">

        <form action="" method="POST" enctype="multipart/form-data">
        <?php
            $settings_class = new Settings();

            $settings = $settings_class->get_settings($_SESSION['social_network_user_id']);

            if (is_array($settings)) 
            {
                echo "<div id='text_box' style='height: 200px;' name='about'>".htmlspecialchars($settings['about'])."</div><br>";
                
            }
        
        ?>
        </form>
    </div>
</body>
</html>