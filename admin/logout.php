<?php

session_start();
session_destroy();
setcookie("loginKey", '', time()-3600);

header('Location: login.php');
?>
