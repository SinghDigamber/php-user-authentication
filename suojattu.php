<?php
$tunniste = "loggedin";
$loginsivu = "index.php";
/* Suojattujen sivujen alkuun */
if (!session_id()) session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION[$tunniste]) || $_SESSION[$tunniste] !== true){
   $_SESSION['next_page'] = $_SERVER['PHP_SELF'];
   header("location: $loginsivu");
   exit;
}
?>
