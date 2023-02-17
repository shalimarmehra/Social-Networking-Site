<?php

    $_SESSION['social_network_user_id'] = isset($_SESSION['social_network_user_id']) ? $_SESSION['social_network_user_id'] : 0;
    $login = new Login();
    $user_data = $login->check_login($_SESSION['social_network_user_id'], false);

    if ($_SESSION['social_network_user_id'] == 0) 
    {
        $obj = (object)[];
        $obj->action = "like_post";

        echo json_encode($obj);
        die;
    }

    $query_string = explode("?", $data->link);
    $query_string = end($query_string);

    $str = explode("&", $query_string);
    
    foreach ($str as $value) 
    {
        $value = explode("=", $value);
        $_GET[$value[0]] = $value[1];
    }

    $_GET['id'] == addslashes($_GET['id']);
    $_GET['type'] == addslashes($_GET['type']);

    if (isset($_GET['type']) && isset($_GET['id'])) 
    {
        $post = new Post();
        if (is_numeric($_GET['id'])) 
        {
            $allowed[] = 'post';
            $allowed[] = 'user';
            $allowed[] = 'comment';

            if (in_array($_GET['type'], $allowed)) 
            {
                $user_class = new User();
                $post->like_post($_GET['id'], $_GET['type'], $_SESSION['social_network_user_id']);

                if ($_GET['type'] == "user") 
                {
                    $user_class->follow_user($_GET['id'], $_GET['type'], $_SESSION['social_network_user_id']);
                }
            }
        }
        // Read Likes
        $likes = $post->get_likes($_GET['id'], $_GET['type']);

        // Create Info

                $likes = array();
                $info = "";

                $i_liked = false;
                if (isset($_SESSION['social_network_user_id'])) 
                {
                    $DB = new Database();
                    $sql = "select likes from likes where type = 'post' &&  content_id = '$_GET[id]' limit 1 ";
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

                $like_count = count($likes);
                if ($like_count > 0) 
                {
                    $info .= "<br>";
                    if ($like_count == 1) 
                    {
                        if ($i_liked) 
                        {
                            $info .= "<div style='text-align: left;'>You Liked This Post </div>";
                        }
                        else
                        {
                            $info .= "<div style='text-align: left;'>1 Person Liked This Post </div>";
                        }
                    }
                    else
                    {
                        if ($i_liked)
                        {
                            $text = "others";
                            if ($like_count - 1 == 1) 
                            {
                                $text = "other";
                            }
                            $info .= "<div style='text-align: left;'>You and " . ($like_count - 1 ). " $text  Liked This Post </div>";
                        }
                        else
                        {
                            $info .= "<div style='text-align: left;'>" . $like_count . " Other Liked This Post </div>";
                        }
                    }
                }

        $obj = (object)[];
        $obj->likes = count($likes);
        $obj->action = "like_post";
        $obj->info = $info;
        $obj->id = "info_$_GET[id]";

        echo json_encode($obj);
    }
?>