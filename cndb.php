<?php
$host = 'localhost';
$user = 'root';
$pass = 'Duongtiachop30@';
$db = 'kytucxa';
$conn = new mysqli($host, $user, $pass, $db);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
