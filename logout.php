<?php

    session_start();
    if (isset($_SESSION['social_network_user_id'])) 
    {
        $_SESSION['social_network_user_id'] = NULL;
        unset($_SESSION['social_network_user_id']);
    }
        
        header("Location: login_page.php");
        die;

?>

