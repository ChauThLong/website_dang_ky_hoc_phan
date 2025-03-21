<?php
// Kết nối đến MySQL
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "test1";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>