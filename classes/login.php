<?php

class Login
{

    private $error = "";

    public function evaluate($data)
    {
        $email = addslashes($data['email']);
        $password = addslashes($data['password']);

        $query = "select * from users where email = '$email' limit 1";

        $DB = new Database();
        $result = $DB->read($query);

        if ($result) 
        {
            $row = $result[0];

            // Hashed Password
            if ($this->hashed_text($password == $row['password'])) 
            {
                // Create a session data
                $_SESSION['social_network_user_id'] = $row['user_id'];
                
            }
            else
            {
                $this->error .= "Wrong E-mail & Password<br>";
            }
        }
        else
        {
            $this->error .= "Wrong E-mail & Password<br>";
        }
        return $this->error;
    }

    // Hashed Password Function
    private function hashed_text($text)
    {
        $text = hash("sha1", $text);
        return $text;
    }

    public function check_login($id, $redirect = true)
    {
        if (is_numeric($id))
        {
            $query = "select * from users where user_id = '$id' limit 1";

            $DB = new Database();
            $result = $DB->read($query);

            if ($result) 
                {
                    $user_data = $result[0];
                    return $user_data;
                }
                else
                {
                    if ($redirect) 
                    {
                        header("Location: login_page.php");
                        die;
                    }
                    else
                    {
                        $_SESSION['social_network_user_id'] = 0;
                    }
                }
        }
        else
        {
            if ($redirect) 
            {
                header("Location: login_page.php");
                die;
            }
            else
            {
                $_SESSION['social_network_user_id'] = 0;
            }
        }
    }
}

?>