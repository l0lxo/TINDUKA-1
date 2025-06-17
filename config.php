<?php
$host = 'localhost';
$db = 'tinduka';
$user = 'root';
$pass = 'issafuad'; // default XAMPP password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
