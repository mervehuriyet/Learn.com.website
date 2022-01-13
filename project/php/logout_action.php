<?php

session_start();
$_SESSION = array();
session_destroy();
session_start();
$_SESSION["loggedin"] = false;
$_SESSION["user_id"] = '';
$_SESSION["user_mail"] = '';
header("location: /");
exit;
