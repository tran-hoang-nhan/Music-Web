<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dsthanhvien";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ten_nhac = $_POST['ten_nhac'] ?? '';
    $tac_gia = $_POST['tac_gia'] ?? '';
    $so_luong = (int)($_POST['so_luong'] ?? 1);
    $loai = $_POST['loai'] ?? '';
    $username = $_SESSION['username'] ?? ''; // Giả sử người dùng đã đăng nhập
    $gia = 0;

    // Xác định giá theo loại
    switch ($loai) {
        case 'cd':
            $gia = 10000;
            $loai= 'Đĩa CD';
            break;
        case 'than':
            $gia = 2000000;
            $loai= 'Đĩa Than';
            break;
        case 'cat_xet':
            $gia = 200000;
            $loai= 'Đĩa Cát Sét';
            break;
        default:
            echo "<script>alert('Loại sản phẩm không hợp lệ!'); window.history.back();</script>";
            exit;
    }

    // Kiểm tra giá trị hợp lệ
    if (empty($ten_nhac) || empty($tac_gia) || empty($username) || $so_luong <= 0 || $gia <= 0) {
        echo "<script>alert('Dữ liệu không hợp lệ!'); window.history.back();</script>";
        exit;
    }

    $stmt = $conn->prepare("SELECT so_luong FROM gio_hang WHERE username = ? AND ten_nhac = ? AND loai = ?");
    $stmt->bind_param("sss", $username, $ten_nhac, $loai);
    $stmt->execute();
    $stmt->bind_result($current_quantity);

    if ($stmt->fetch()) {
        $new_quantity = $current_quantity + $so_luong;
        $stmt->close();

        $update_stmt = $conn->prepare("UPDATE gio_hang SET so_luong = ?, gia = ? WHERE username = ? AND ten_nhac = ? AND loai = ?");
        $update_stmt->bind_param("iisss", $new_quantity, $gia, $username, $ten_nhac, $loai);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        $stmt->close();

        $insert_stmt = $conn->prepare("INSERT INTO gio_hang (username, ten_nhac, tac_gia, loai, so_luong, gia) VALUES (?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("ssssii", $username, $ten_nhac, $tac_gia, $loai, $so_luong, $gia);
        $insert_stmt->execute();
        $insert_stmt->close();
    }

    echo "<script>alert('Thêm vào giỏ hàng thành công!'); window.history.back();</script>";
}
$conn->close();
?>
