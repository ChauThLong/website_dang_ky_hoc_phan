<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: dangnhap.php");
    exit;
}

if (!isset($_GET['MaHP'])) {
    echo "Không có mã học phần.";
    exit;
}

$MaHP = $_GET['MaHP'];

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Thêm MaHP vào giỏ (sử dụng key để tránh trùng lặp)
$_SESSION['cart'][$MaHP] = true;

// Chuyển hướng về trang giỏ hàng
header("Location: hp_da_dangky.php");
exit;
