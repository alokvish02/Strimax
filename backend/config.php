<?php
$servername = "127.0.0.1";
$username = "root"; 
$password = ""; 
$dbname = "strimax";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}
?>
