<?php
// Bắt đầu session
session_start();

// Hủy session hiện tại
session_unset(); // Xóa tất cả các biến session
session_destroy(); // Hủy session

// Chuyển hướng về trang đăng nhập
header("Location: dangnhap2.html");
exit();

