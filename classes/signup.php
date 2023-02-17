<?php

class Signup
{

    private $error = "";

    public function evaluate($data)
    {
        foreach ($data as $key => $value) {
            if (empty($value)) 
            {
                $this->error .= $key . " is empty !<br>";
            }

            if ($key == "first_name") {
                if (is_numeric($value)) 
                {
                    $this->error .= "First number can't be a number !<br>";
                }

                if (strstr($value, " ")) 
                {
                    $this->error .= "First number can't contain spaces !<br>";
                }
            }

            if ($key == "last_name") {
                if (is_numeric($value)) 
                {
                    $this->error .= "Last number can't be a number !";
                }

                if (strstr($value, " ")) 
                {
                    $this->error .= "Last number can't contain spaces !<br>";
                }
            }
        }

        $DB = new Database();
        $data['tag_name'] = strtolower($data['first_name'] . $data['last_name']);

        // check tag name
        $sql = "select id from users where tag_name = '$data[tag_name]' limit 1";
        $check = $DB->read($sql);
        while (is_array($check)) 
        {
            $data['tag_name'] = strtolower($data['first_name'] . $data['last_name']) . rand(0,9999);
            $sql = "select id from users where tag_name = '$data[tag_name]' limit 1";
            $check = $DB->read($sql);
        }

        $data['user_id'] = $user_id = $this->create_user_id();
        // check user id
        $sql = "select id from users where user_id = '$data[user_id]' limit 1";
        $check = $DB->read($sql);
        while (is_array($check)) 
        {
            $data['user_id'] = $user_id = $this->create_user_id();
            $sql = "select id from users where user_id = '$data[user_id]' limit 1";
            $check = $DB->read($sql);
        }

        // check email
        $sql = "select id from users where email = '$data[email]' limit 1";
        $check = $DB->read($sql);
        while (is_array($check)) 
        {
            $this->error .= "another user is already using that email !<br>";
        }

        if ($this->error == "") 
        {
            // No Error
            $this->create_user($data);
        }
        else
        {
            return $this->error;
        }
    }

    public function create_user($data)
    {
        $first_name = ucfirst($data['first_name']);
        $last_name = ucfirst($data['last_name']);
        $email = $data['email'];
        $country = $data['country'];
        $gender = $data['gender'];
        $birthday = $data['birthday'];
        $password = $data['password'];
        $user_id = $data['user_id'];
        $tag_name = $data['tag_name'];

        $tag_name = strtolower($first_name . $last_name);
        $password = hash("sha1", $password);

        // Create These
        $url_address = strtolower($first_name) . "." . strtolower($last_name);

        $query = "insert into users (user_id, first_name, last_name, email, country, gender, birthday, password, url_address, tag_name) 
        
        values ('$user_id', '$first_name', '$last_name', '$email', '$country', '$gender', '$birthday', '$password', '$url_address', '$tag_name')";
        

        // return $query;
        $DB = new Database();
        $DB->save($query);
    }

    private function create_user_id()
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