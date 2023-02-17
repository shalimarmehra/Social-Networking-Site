<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | Photos</title>
    <link rel="stylesheet" href="style/profile_content_photos.css">
</head>
<body>
        
        <h3>All Photos<br>
        <span style="font-size: 17px; padding: 4px 30px 4px 30px; color: #201E20; background-color: #DDC3A5; border-radius: 20px;">@<?= $user_data['tag_name'] ?></span>
        </h3>
        <div id="timeline-section">
        <?php
        
            $DB = new Database();
            $sql = "select image, post_id from posts where has_image = 1 && user_id = $user_data[user_id] order by id desc limit 30";
            $images = $DB->read($sql);
        
            $image_class = new Image();
            if (is_array($images)) 
            {
                foreach ($images  as $image_row) 
                {
                    echo "<a href='single_post.php?id=$image_row[post_id]' >";
                    echo "<img src='" . $image_class->get_thumb_post($image_row['image']) . "' style='width:250px; margin: 20px; border-radius: 20px;' />";
                    echo "</a>";
                }
            }
            else
            {
                echo "<div style='color: #DDC3A5; text-align: center; font-size: 25px; padding: 20px; margin-left: 400px;'>No Image were Found !</div>";
            }
        
        
        ?>
        </div>
    
</body>
</html>