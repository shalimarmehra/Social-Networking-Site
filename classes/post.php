<?php

class Post
{
    private $error = "";
    public function create_post($user_id, $data, $files)
    {
        if (!empty($data['post']) || !empty($files['file']['name']) || isset($data['is_profile_image']) || isset($data['is_cover_image'])) 
        {
            $my_image = "";
            $has_image = 0;
            $is_cover_image = 0;
            $is_profile_image = 0;

            if (isset($data['is_profile_image']) || isset($data['is_cover_image'])) 
            {
                $my_image = $files;
                $has_image = 1;
                if (isset($data['is_cover_image']))
                {
                    $is_cover_image = 1;
                }
                if (isset($data['is_profile_image']))
                {
                    $is_profile_image = 1;
                }
            }
            else
            {
                if (!empty($files['file']['name']))
                {
                    $folder = "uploads/" . $user_id . "/";
                    if (!file_exists($folder)) 
                    {
                        mkdir($folder, 0777, true);
                        file_get_contents($folder . "index.php", "");
                    }

                    $allowed[] = "image/jpeg";
                    $allowed[] = "video/mp4";

                    if (in_array($files['file']['type'], $allowed))
                    {
                        $image_class = new Image();

                        $ext = pathinfo($files['file']['name'], PATHINFO_EXTENSION);
                        $ext = strtolower($ext);

                        $my_image = $folder . $image_class->generate_filename(15) . "." . $ext;
                        move_uploaded_file($files['file']['tmp_name'], $my_image);

                        if ($ext == "jpg" ||  $ext == "jpeg") 
                        {
                            $image_class->resize_image($my_image, $my_image, 600, 600);
                        }
                        
                        $has_image = 1;
                    }
                    else
                    {
                        // $this->error .= "The Selected image is note a valid type !<br>";
                        // $this->error .= "
                        // echo '<script>alert('The Selected image is note a valid type !<br>')</script>';
                        // ";
                    }
                }
            }

            $post = "";
            if (isset($data['post'])) 
            {
                $post = addslashes($data['post']);
            }

            // add tagged user
            $tags = array();
            $tags = get_tags($post);
            $tags = json_encode($tags);

            if ($this->error == "") 
            {
                $post_id =  $this->create_post_id();
                $parent =  0;
                $DB = new Database();

                if (isset($data['parent']) && is_numeric($data['parent'])) 
                {
                    $parent = $data['parent'];
                    $my_post = $this->get_one_post($data['parent']);

                    if (is_array($my_post) && $my_post ['user_id'] != $user_id) 
                    {
                        // follow this item
                        content_i_follow($user_id, $my_post);

                        // Add Notification
                        add_notification($_SESSION['social_network_user_id'],"comment", $my_post);
                    }
                    $sql = "update posts set comments = comments + 1 where post_id = '$parent' limit 1";
                    $DB->save($sql);
                }

                $query = "insert into posts (user_id, post_id, post, image, has_image, is_profile_image, is_cover_image, parent, tags) values ('$user_id', '$post_id', '$post', '$my_image', '$has_image', '$is_profile_image', '$is_cover_image', '$parent', '$tags')";
                $DB->save($query);

                //notify those that were tagged
                tag($post_id);
            }
        }
        else
        {
            // $this->error .= "Please Type Something to Post<br>";
        }
        return $this->error;
    }
    
    public function edit_post($data, $files)
    {
        if (!empty($data['post']) || !empty($files['file']['name']))
        {
            $my_image = "";
            $has_image = 0;

                if (!empty($files['file']['name']))
                {
                    $user_id = "";
                    $folder = "uploads/" . $user_id . "/";
                    if (!file_exists($folder)) 
                    {
                        mkdir($folder, 0777, true);
                        file_get_contents($folder . "index.php", "");
                    }

                    $image_class = new Image();

                    $my_image = $folder . $image_class->generate_filename(15) . ".jpg";
                    move_uploaded_file($_FILES['file']['tmp_name'], $my_image);

                    $image_class->resize_image($my_image, $my_image, 600, 600);
                    
                    $has_image = 1;
                }

            $post = "";
            if (isset($data['post'])) 
            {
                $post = addslashes($data['post']);
            }

            $post_id =  addslashes($data['post_id']);

            if ($has_image) 
            {
                $query = "update posts set post = '$post', image = '$my_image' where post_id = '$post_id' limit 1";
            }
            else
            {
                $query = "update posts set post = '$post' where post_id = '$post_id' limit 1";
            }

            //notify those that were tagged
            tag($post_id, $post);
            
            $DB = new Database();
            $DB->save($query);
        }
        else
        {
            $this->error .= "Please Type Something to Post<br>";
        }

        return $this->error;
    }

    public function get_posts($id)
    {

        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
        $page_number = ($page_number < 1) ? 1 : $page_number ;

        $limit = 10;
        $offset = ($page_number - 1) * $limit;

        $query = "select * from posts where parent = 0 and user_id = '$id' order by id desc limit $limit offset $offset";
        $DB = new Database();
        $result = $DB->read($query);

        if ($result) 
        {
            return $result;
        }
        else
        {
            return false;
        }
    }

    public function get_comments($id)
    {

        $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
        $page_number = ($page_number < 1) ? 1 : $page_number ;

        $limit = 10;
        $offset = ($page_number - 1) * $limit;

        $query = "select * from posts where parent = '$id' order by id desc limit $limit offset $offset";
        $DB = new Database();
        $result = $DB->read($query);

        if ($result) 
        {
            return $result;
        }
        else
        {
            return false;
        }
    }

    public function get_one_post($post_id)
    {
        if (!is_numeric($post_id)) 
        {
            return false;
        }
        $query = "select * from posts where post_id = '$post_id' limit 1";
        $DB = new Database();
        $result = $DB->read($query);

        if ($result) 
        {
            return $result[0];
        }
        else
        {
            return false;
        }
    }
    
    public function delete_post($post_id)
    {

        if (!is_numeric($post_id)) 
        {
            return false;
        }

        $Post = new Post();
        $one_post = $Post->get_one_post($post_id);

        $DB = new Database();
        $sql = "select parent from posts where post_id = '$post_id' limit 1";
        $result = $DB->read($sql);

        if (is_array($result)) 
        {
            if ($result[0]['parent'] > 0)
                {
                    $parent = $result[0]['parent'];
    
                    $sql = "update posts set comments = comments - 1 where post_id = '$parent' limit 1";
                    $DB->save($sql);
                }
        }

        $query = "delete from posts where post_id = '$post_id' limit 1";
        $DB->save($query);

        // Delete images & thumbnails
        if ($one_post['image'] != "" && file_exists($one_post['image'])) 
            {
                unlink($one_post['image']);
            }
        
        if ($one_post['image'] != "" && file_exists($one_post['image'] . "_post_thumb")) 
            {
                unlink($one_post['image'] . "_post_thumb");
            }
            
        if ($one_post['image'] != "" && file_exists($one_post['image'] . "_cover_thumb")) 
            {
                unlink($one_post['image'] . "_cover_thumb");
            }

        // Delete all comments
        $query = "delete from posts where parent = '$post_id' ";
        $DB->save($query);
    }

    public function i_own_post($post_id,$social_network_user_id)
    {

        if (!is_numeric($post_id)) 
        {
            return false;
        }

        $query = "select * from posts where post_id = '$post_id' limit 1";

        $DB = new Database();
        $result = $DB->read($query);

        if (is_array($result)) 
        {
            if ($result[0]['user_id'] == $social_network_user_id) 
            {
                return true;
            }
        }
        return false;
    }

    public function get_likes($id, $type)
    {
        $DB = new Database();
        $type = addslashes($type);
        
        if (is_numeric($id))
        {
            // get likes details
            $sql = "select likes from likes where type = '$type' &&  content_id = '$id' limit 1 ";
            $result = $DB->read($sql);

            if (is_array($result)) 
            {
                $likes = json_decode($result[0]['likes'], true);
                return $likes;
            }
        }
        return false;
    }

    public function like_post($id, $type, $social_network_user_id)
    {
        $DB = new Database();

        // save likes details
        $sql = "select likes from likes where type = '$type' &&  content_id = '$id' limit 1 ";
        $result = $DB->read($sql);

        if (is_array($result)) 
        {
            $likes = json_decode($result[0]['likes'], true);

            $user_ids = array_column($likes, "user_id");

            if (!in_array($social_network_user_id, $user_ids))
            {
                $arr["user_id"] = $social_network_user_id;
                $arr["date"] = date("Y-m-d H:i:s");
                
                $likes[] = $arr;

                $likes_string = json_encode($likes);
                $sql = "update likes set likes = '$likes_string' where type = '$type' &&  content_id = '$id' limit 1 ";
                $DB->save($sql);

                //Increment the post table
                $sql = "update {$type}s set likes = likes + 1 where {$type}_id = '$id' limit 1 ";
                $DB->save($sql);

                if ($type != "user") 
                {   
                    $post= new Post();
                    $single_post = $post->get_one_post($id);
                    
                    // Add Notification
                    add_notification($_SESSION['social_network_user_id'],"like", $single_post);
                }
            }
            else
            {
                $key = array_search($social_network_user_id, $user_ids);
                unset($likes[$key]);

                $likes_string = json_encode($likes);
                $sql = "update likes set likes = '$likes_string' where type = '$type' &&  content_id = '$id' limit 1 ";
                $DB->save($sql);

                //Increment the post table

                $sql = "update {$type}s set likes = likes - 1 where {$type}_id = '$id' limit 1 ";
                $DB->save($sql);
            }
        }
        else
        {
            $arr["user_id"] = $social_network_user_id;
            $arr["date"] = date("Y-m-d H:i:s");
                    
            $arr2[] = $arr;
            $likes = json_encode($arr2);
            $sql = "insert into likes (type, content_id, likes) values ('$type', '$id', '$likes')";
            $DB->save($sql);

            //Increment the post table
            $sql = "update {$type}s set likes = likes + 1 where {$type}_id = '$id' limit 1 ";
            $DB->save($sql);

            if ($type != "user") 
            {
                $post= new Post();
                $single_post = $post->get_one_post($id);

                // Add Notification
                add_notification($_SESSION['social_network_user_id'],"like", $single_post);
            }
        }
    }

    private function create_post_id()
    {
        $length = rand(4, 19);
        $number = "";
        for ($i=1; $i < $length; $i++) 
        {
            $new_rand = rand(0, 9);
            $number = $number . $new_rand;
        }
        return $number;
    }
}

?>