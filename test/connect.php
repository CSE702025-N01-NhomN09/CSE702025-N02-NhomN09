<?php
$servername = "localhost";
$username = "root";
$password = "Duongtiachop30@";
$dbname = "kytucxa";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}
echo "Thành công"
?>
