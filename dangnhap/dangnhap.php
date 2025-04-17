<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dsthanhvien";
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra phương thức yêu cầu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? ''; 
    $matkhau = $_POST['matkhau'] ?? ''; 

    // Truy vấn kiểm tra tên đăng nhập
    $stmt = $conn->prepare("SELECT * FROM thanhvien WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra kết quả truy vấn
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Kiểm tra mật khẩu
        if ($matkhau == $row['matkhau']) { 
            // Lưu thông tin người dùng vào session
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['hoten'] = $row['hoten'];
            $_SESSION['sdt'] = $row['sdt'];
            $_SESSION['birth'] = $row['birth'];
            $_SESSION['gioitinh'] = $row['gioitinh'];
            $_SESSION['theloai'] = $row['theloai'];
            $_SESSION['bai_nhac'] = $row['bai_nhac'];
            $_SESSION['ngaydky'] = $row['ngaydky'];

            // Chuyển hướng tới trang_user.php
            header("Location: trang_user.php");
            exit;
        } else {
            echo "<script>alert('Sai mật khẩu. Vui lòng thử lại!'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Tên đăng nhập không tồn tại. Vui lòng thử lại!'); window.history.back();</script>";
        exit;
    }
}

// Đóng kết nối
$conn->close();
?>
