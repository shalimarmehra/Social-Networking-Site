<?php

class Profile
{
    function get_profile($id)
    {
        $DB = new Database();
        $query = "select * from users where user_id = '$id' limit 1";
        return $DB->read($query);
    }
}

?>