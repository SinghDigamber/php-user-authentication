<?php   
    /* JAu */  
    $projekti = "autentikointi";
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $polku = "https://";   
    else  
        $polku = "http://";   
    $polku.= $_SERVER['HTTP_HOST'] . "/" . $projekti;   
    // $polku = "http://localhost:8888/php-user-authentication//localhost:8888/autentikointi";
    session_start();
    $_SESSION = array();
    session_destroy();
      
    header("Location: $polku/index.php");
;?>