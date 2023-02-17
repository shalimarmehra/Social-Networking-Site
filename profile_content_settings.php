<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Settings</title>
    <link rel="stylesheet" href="style/profile_content_settings.css">
</head>
<body>
        
        <h3>All Settings<br>
        <span style="font-size: 17px; padding: 4px 30px 4px 30px; color: #201E20; background-color: #DDC3A5; border-radius: 20px;">@<?= $user_data['tag_name'] ?></span>
        </h3>
        <div id="timeline-section-for-settings">

        <form action="" method="POST" enctype="multipart/form-data">
        <?php
            $settings_class = new Settings();

            $settings = $settings_class->get_settings($_SESSION['social_network_user_id']);

            if (is_array($settings)) 
            {
                echo "<input type='text' id='text_box' name='first_name' value='".htmlspecialchars($settings['first_name'])."' Placeholder='first_name' />";
                echo "<input type='text' id='text_box' name='last_name' value='".htmlspecialchars($settings['last_name'])."' Placeholder='last_name'/>";
                
                echo "<select type='radio' id='text_box' name='email' style='width: 46%;'/>
                
                <option>value=".htmlspecialchars($settings['gender'])."</option>
                <option>Male</option>
                <option>Female</option>
                </select>";
                
                echo "<input type='text' id='text_box' name='email' value='".htmlspecialchars($settings['email'])."' Placeholder='name@gmail.com'/>";
                echo "<input type='text' id='text_box' name='password' value='".htmlspecialchars($settings['password'])."' Placeholder='************'/>";
                echo "<input type='text' id='text_box' name='password2' value='".htmlspecialchars($settings['password'])."' Placeholder='************'/>";

                echo "<h5>About me:</h5><br>
                        <textarea id='text_box' style='height: 200px;' name='about'>".htmlspecialchars($settings['about'])."</textarea><br>
                    ";
                
                echo "<input type='submit' style='margin-left: 500px;' id='post_button' value='SAVE'>";
                
            }
        
        ?>
        </form>
    </div>
</body>
</html>