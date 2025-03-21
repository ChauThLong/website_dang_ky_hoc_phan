<?php
session_start();     // Bắt đầu (hoặc tiếp tục) session
session_unset();     // Xóa toàn bộ biến session
session_destroy();   // Hủy session
header("Location: index.php"); // Chuyển hướng về trang chủ (hoặc trang đăng nhập)
exit;
?>
