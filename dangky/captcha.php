<?php
session_start();

// Tạo mã CAPTCHA ngẫu nhiên
$captcha = substr(md5(mt_rand()), 0, 6);
$_SESSION['captcha'] = $captcha;

// Tạo hình ảnh
$width = 120;
$height = 40;
$image = imagecreate($width, $height);

// Màu sắc
$background_color = imagecolorallocate($image, 255, 255, 255); // Màu nền trắng
$text_color = imagecolorallocate($image, 0, 0, 0); // Màu chữ đen

// Đổ nền và thêm mã CAPTCHA vào hình ảnh
imagefill($image, 0, 0, $background_color);
imagestring($image, 5, 35, 10, $captcha, $text_color);

// Đầu ra là hình ảnh PNG
header("Content-type: image/png");
imagepng($image);

// Giải phóng bộ nhớ
imagedestroy($image);
?>
