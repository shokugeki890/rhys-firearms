<?php
require_once 'auth.php';
//this is logout.php
logout();
header('Location: login.php');
exit();
?>