<?php

function pagination_link()
{

    $page_number = isset($_GET['page']) ? (int)$_GET['page'] : 1 ;
    $page_number = ($page_number < 1) ? 1 : $page_number ;

    $arr['next_page'] = "";
    $arr['prev_page'] = "";

    // Get Current URL
    $url = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];
    $url .= "?";
    
    $next_page_link = $url;
    $prev_page_link = $url;

    $page_found = false;
    
    $num = 0;
    foreach ($_GET as $key => $value) 
    {
        $num++;

        if ($num == 1) 
        {
            if ($key == "page") 
            {
                $next_page_link .= $key . "=" . ($page_number + 1);
                $prev_page_link .= $key . "=" . ($page_number - 1);
                $page_found = true;
            }
            else
            {
                $next_page_link .= $key . "=" . $value;
                $prev_page_link .= $key . "=" . $value;
            }
        }
        else
        {
            if ($key == "page")
            {
                $next_page_link .= "&" . $key . "=" . ($page_number + 1);
                $prev_page_link .= "&" . $key . "=" . ($page_number - 1);
                $page_found = true;
            }
            else
            {
                $next_page_link .= "&" . $key . "=" . $value;
                $prev_page_link .= "&" . $key . "=" . $value;
            }
        }
    }

    $arr['next_page'] = $next_page_link;
    $arr['prev_page'] = $prev_page_link;

    if (!$page_found) 
    {
        $arr['next_page'] = $next_page_link . "&page=2";
        $arr['prev_page'] = $prev_page_link . "&page=1";
    }
    else
    {

    }
    return $arr;
}

function i_own_content($row)
{
    $my_id = $_SESSION['social_network_user_id'];
    if (isset($row['gender']) && $my_id == $row['user_id'])
    {
        return true;
    }
    
    if (isset($row['post_id'])) 
    {
        if ($my_id == $row['user_id']) 
        {
            return true;
        }
        else
        {
            $Post = new Post();
            $one_post = $Post->get_one_post($row['parent']);

            if ($my_id == $one_post['user_id']) 
            {
                return true;
            }
        }
    }

    return false;
}

function tag($post_id, $new_post_text = "")
{
    $DB = new Database();
    $sql = "select * from posts where post_id = '$post_id' limit 1";
    $mypost = $DB->read($sql);

    if (is_array($mypost)) 
    {
        $mypost = $mypost[0];

        if ($new_post_text != "") 
        {
            $old_post = $mypost;
            $mypost['post'] = $new_post_text;
        }
        $tags = get_tags($mypost['post']);
        foreach($tags as $tag)
        {
            $sql = "select * from users where tag_name = '$tag' limit 1";
            $tagged_user = $DB->read($sql);
            if (is_array($tagged_user))
            {
                $tagged_user = $tagged_user[0];
                if ($new_post_text != "") 
                {
                    $old_tags = get_tags($old_post['post']);
                    if (!in_array($tagged_user['tag_name'], $old_tags)) 
                    {
                        add_notification($_SESSION['social_network_user_id'], "tag", $mypost, $tagged_user['user_id']);
                    }
                }
                else
                {
                    // tags
                    add_notification($_SESSION['social_network_user_id'], "tag", $mypost, $tagged_user['user_id']);
                }
            }
        }
    }
}

function add_notification($user_id, $activity, $row, $tagged_user = '')
{
    $row = (object)$row;
    $user_id = esc($user_id);
    $activity = esc($activity);
    $content_owner = $row->user_id;

    if ($tagged_user != "") 
    {
        $content_owner = $tagged_user;
    }

    $date = date("Y-m-d H:i:s");

    $content_id = 0;
    $content_type = "";

    if (isset($row->post_id)) 
    {
        $content_id = $row->post_id;
        $content_type = "post";

        if ($row->parent > 0)
        {
            $content_type = "comment";
        }
    }

    if (isset($row->gender))
    {
        $content_type = "profile";
        $content_id = $row->user_id;
    }

    $query = "insert into notification (user_id,activity,content_owner,date,content_id,content_type) 
    values ('$user_id','$activity','$content_owner','$date','$content_id','$content_type')";
    $DB = new Database();
    $DB->save($query);
}

function content_i_follow($user_id, $row)
{
    $row = (object)$row;
    $user_id = esc($user_id);
    $date = date("Y-m-d H:i:s");

    $content_id = 0;
    $content_type = "";

    if (isset($row->post_id)) 
    {
        $content_id = $row->post_id;
        $content_type = "post";

        if ($row->parent > 0)
        {
            $content_type = "comment";
        }
    }

    if (isset($row->gender))
    {
        $content_type = "profile";
    }

    $query = "insert into content_i_follow (user_id,date,content_id,content_type) 
    values ('$user_id','$date','$content_id','$content_type')";
    $DB = new Database();
    $DB->save($query);
}

function esc($value)
{
    return addslashes($value);
}

function notification_seen($id)
{
    $notification_id = addslashes($id);
    $user_id = $_SESSION['social_network_user_id'];
    $DB = new Database();

    $query = "select * from notification_seen where user_id = '$user_id' && notification_id = '$notification_id' limit 1";
    $check = $DB->read($query);

    if (!is_array($check)) 
    {
        $query = "insert into notification_seen (user_id,notification_id) 
        values ('$user_id','$notification_id')";

        $DB->save($query);
    }
}

function check_notifications()
{
    $number = 0;

    $user_id = $_SESSION['social_network_user_id'];
    $DB = new Database();
    $follow = array();

    $sql = "select * from content_i_follow where disabled = 0 && user_id = '$user_id' limit 100";
    $i_follow = $DB->read($sql);

    if (is_array($i_follow)) 
    {
        $follow = array_column($i_follow, "content_id");
    }

    if (count($follow) > 0) 
    {
        $str = "'" . implode("','", $follow) . "'";
        $query = "select * from notification where (user_id != '$user_id' && content_owner = '$user_id') || (content_id in ($str)) order by id desc limit 30";
    }
    else
    {
        $query = "select * from notification where user_id != '$user_id' && content_owner = '$user_id' order by id desc limit 30";
    }
    $data = $DB->read($query);

    if (is_array($data)) 
    {
        foreach ($data as $row) 
        {
            $query = "select * from notification_seen where user_id = '$user_id' && notification_id = '$row[id]' limit 1";
            $check = $DB->read($query);
            
            if (!is_array($check)) 
            {
                $number++;
            }
        }
    }   
    return $number;
}

if (isset($_SESSION['social_network_user_id'])) 
{
    set_online($_SESSION['social_network_user_id']);
}
function set_online($id)
{
    if (!is_numeric($id)) 
    {
        return;
    }

    $online = time();
    $query = "update users set online = '$online' where user_id = '$id' limit 1 ";

    $DB = new Database();
    $DB->save($query);
}

function check_tags($text)
{
    $str = "";         
    $words = explode(" ", $text);
    if (is_array($words) && count($words) > 0) 
    {
        $DB = new Database();
        foreach($words as $word)
        {
            if(preg_match("/@[a-zA-Z_0-9\Q,.\E]+/", $word))
            {
                $word = trim($word, '@');
                $word = trim($word, ',');
                $tag_name = esc(trim($word, '.'));

                $query = "select * from users where tag_name = '$tag_name' limit 1";
                $user_row = $DB->read($query);

                if(is_array($user_row)){
                    $user_row = $user_row[0];
                    $str .= "<a style='color: #DDC3A5;' href='profile.php?id=$user_row[user_id]'>@" . $word . "</a> ";
                }
                else
                {
                    $str .= htmlspecialchars($word) . " ";
                }
            }
            else
            {
                $str .= htmlspecialchars($word) . " ";
            }
        }
        
    }
    if ($str != "") 
    {
        return $str;
    }
    return htmlspecialchars($text) . " ";
}

function get_tags($text)
{
    $tags = array();         
    $words = explode(" ", $text);
    if (is_array($words) && count($words) > 0) 
    {
        $DB = new Database();
        foreach($words as $word)
        {
            if(preg_match("/@[a-zA-Z_0-9\Q,.\E]+/", $word))
            {
                $word = trim($word, '@');
                $word = trim($word, ',');
                $tag_name = esc(trim($word, '.'));

                $query = "select * from users where tag_name = '$tag_name' limit 1";
                $user_row = $DB->read($query);

                if(is_array($user_row))
                {
                    $tags[] = $word;
                }
            }
        }
    }
    return $tags;
}
?>