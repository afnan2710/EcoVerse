<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "g10";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>