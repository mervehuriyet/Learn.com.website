<?php

try {
    $serverName = 'localhost';

    $dbName = 'project';

    $user = 'root';

    $pwd = '';


    $vt = new PDO("mysql:host=$serverName;dbname=$dbName;charset=utf8", $user, $pwd);
} catch (PDOException $e) {
    die('Connection Failed: ' . $e->getMessage());
    exit;
}
