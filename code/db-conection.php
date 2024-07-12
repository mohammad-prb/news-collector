<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbName = "db_news";
$con = new mysqli($servername, $username, $password, $dbName);
$con->set_charset("utf8");
if ($con->connect_error) {
    header('HTTP/1.1 500 Internal Server Error');
    die();
}
