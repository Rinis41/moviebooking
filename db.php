<?php
$host = "localhost";
$user = "root"; // your db username
$pass = "";     // your db password
$db   = "auth_demo";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>