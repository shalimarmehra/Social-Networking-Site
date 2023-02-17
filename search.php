<?php

    include("classes/autoload.php");
    
    $login = new Login();
    $user_data = $login->check_login($_SESSION['social_network_user_id']);

    $USER = $user_data;

    if (isset($_GET['id']) && is_numeric($_GET['id'])) 
    {
        $profile = new Profile();
        $profile_data = $profile->get_profile($_GET['id']);
    
        if (is_array($profile_data)) 
        {
            $user_data = $profile_data[0];
        }
    }

    if (isset($_GET['find'])) 
    {
        $find = addslashes($_GET['find']);
        $sql = "select * from users where first_name like '%$find%' || last_name like '%$find%' limit 30 ";

        $DB = new Database();
        $result = $DB->read($sql);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/search.css">
    <link rel="stylesheet" href="style/header.css">
    <title>Find People | SOUL MEDIA</title>
</head>
<body>
    <!-- Main Header -->
    <?php 
        include("header.php");
    ?>

    <!-- Post Area -->
    <div id="timeline-section" style="margin: 20px 350px 0px 350px;">
        <div id="timeline-post" >
            <?php

                $User = new User();
                $image_class = new Image();
                if (is_array($result)) 
                {
                    foreach ($result as $row) 
                    {
                        $FRIEND_ROW = $User->get_user($row['user_id']);
                        include("user.php");
                    }
                }
                else
                {
                    echo "<div style='height: 50px; color: #DDC3A5; padding: 10px; text-align: center; font-size: 250%; text-shadow: 2px 2px 8px #E0A96D;'";
                    echo "</div>";
                    echo " No Result Found ";
                }
            ?>
        </div>            
    </div>
</body>
</html>