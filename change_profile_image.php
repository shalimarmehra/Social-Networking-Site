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

    if ($_SERVER['REQUEST_METHOD'] == "POST") 
    {

        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") 
        {
            if ($_FILES['file']['type'] == "image/jpeg")
            {
                $allowed_size = (1024 * 1024) * 3;
                if ($_FILES['file']['size'] < $allowed_size) 
                {
                    // Everything is fine

                    $folder = "uploads/" . $user_data['user_id'] . "/";

                    // Create folder

                    if (!file_exists($folder)) 
                    {
                        mkdir($folder, 7777, true);
                    }

                    $image = new Image();

                    $filename = $folder . $image->generate_filename(15) . ".jpg";
                    move_uploaded_file($_FILES['file']['tmp_name'], $filename);

                    // $filename = "uploads/" . $_FILES['file']['name'];
                    // move_uploaded_file($_FILES['file']['tmp_name'], $filename);

                    $change = "profile";

                    // Check for Mode
                    if (isset($_GET['change'])) 
                    {
                        $change = $_GET['change'];
                    }

                    // Cropping Image

                    if ($change == "cover") 
                    {
                            if (file_exists($user_data['cover_image'])) 
                            {
                                unlink($user_data['cover_image']);
                            }
                        $image->resize_image($filename, $filename, 800, 1158); // 1366, 588
                    }
                    else
                    {
                            if (file_exists($user_data['profile_image'])) 
                            {
                                unlink($user_data['profile_image']);
                            }
                        $image->resize_image($filename, $filename, 800, 800); // 800, 800
                    }

                    if (file_exists($filename)) 
                    {

                        $user_id = $user_data['user_id'];
                        
                        $image = new Image();
                        if ($change == "cover") 
                        {
                            $query = "update users set cover_image = '$filename' where user_id = '$user_id' limit 1";
                            $_POST['is_cover_image'] = 1;
                        }
                        else 
                        {
                            $query = "update users set profile_image = '$filename' where user_id = '$user_id' limit 1";
                            $_POST['is_profile_image'] = 1;
                        }
                        
                        $DB = new Database();
                        $DB->save($query);

                        // Create a Post

                        $post = new Post();
                        $post->create_post($user_id, $_POST, $filename);

                        header("Location: profile.php");
                        die;
                    }
                }
                else
                {
                    echo "<div style='text-align:center;font-size:20px;color:#E0A96D;'>";
                    echo "<script>alert('The image of the size is allowed only 3MB !')</script>";
                    echo "</div>";
                }
            }
            else
            {
                echo "<div style='text-align:center;font-size:20px;color:#E0A96D;'>";
                echo "<script>alert('Please Select a jpeg type image !')</script>";
                echo "</div>";
            }
            
        }
        else
        {
            echo "<div style='text-align:center;font-size:20px;color:#E0A96D;'>";
            echo "<script>alert('Please Select a Valid image !')</script>";
            echo "</div>";
        }        
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/change-profile-image.css">
    <link rel="stylesheet" href="style/header.css">
    <title>Change Profile Image | SOUL MEDIA</title>
</head>

<body>
    <!-- Main Header -->

    <?php 
        include("header.php");
    ?>

    <!-- Post Area -->
    <form method="POST" enctype="multipart/form-data">
        <div id="timeline-section">
            <div id="timeline-post">
                <input type="file" name="file" id="file_upload">
                <input type="submit" id="post_button" value="CHANGE">

                <?php

                    if (isset($_GET['change']) && $_GET['change'] == "cover") 
                    {
                        $change = "cover";
                        echo "<img src='$user_data[cover_image]' style='max-width:600px; margin-top:100px;' >";
                    }
                    else
                    {
                        echo "<img src='$user_data[profile_image]' style='max-width:600px; margin-top:80px;' >";
                    }

                
                ?>
            </div>
        </div>
    </form>
</body>

</html>