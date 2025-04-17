<?php
    session_start();

    // Kết nối cơ sở dữ liệu
    $servername = "localhost";
    $username_db = "root";
    $password_db = "";
    $dbname = "dsthanhvien";

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Kiểm tra nếu người dùng chưa đăng nhập
    if (!isset($_SESSION['username'])) {
        echo "<script>alert('Vui lòng đăng nhập trước khi liên hệ!'); window.location.href='dangnhap2.html';</script>";
        exit;
    }

    $username = $_SESSION['username']; // Lấy username từ session

    // Xử lý form liên hệ
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ho_ten = htmlspecialchars($_POST['ho_ten']);
        $email = htmlspecialchars($_POST['email']);
        $noi_dung = htmlspecialchars($_POST['noi_dung']);
    
        if (!empty($ho_ten) && !empty($email) && !empty($noi_dung)) {
            // Lưu vào cơ sở dữ liệu
            $sql = "INSERT INTO lien_he (username, hoten_gui, email, noidung) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $ho_ten, $email, $noi_dung);
    
            if ($stmt->execute()) {
                echo "<script>alert('Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.'); window.location.href='../trang_user.php';</script>";
            } else {
                echo "<script>alert('Đã xảy ra lỗi, vui lòng thử lại sau.'); window.location.href='lien_he.php';</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Vui lòng điền đầy đủ thông tin.'); window.location.href='trang_user.php';</script>";
        }
    }
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ</title>
    <link rel="stylesheet" href="lienhe.css">
</head>
<body>
    <div class="container">
        <div class="column">
            <header>Liên Hệ</header>
            <a href="../trang_user.php" class="home-button">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <?php if (isset($thanh_cong) && $thanh_cong): ?>
            <p class="success">Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất.</p>
        <?php elseif (isset($thanh_cong) && !$thanh_cong): ?>
            <p class="error">Vui lòng điền đầy đủ thông tin để gửi liên hệ!</p>
        <?php endif; ?>
        <p style="color:brown">Nếu bạn có bất kỳ câu hỏi nào, vui lòng điền vào biểu mẫu dưới đây. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.</p>
        <form action="lien_he.php" method="POST" class="form_lienhe">
            <div class="column">
                <div class="input">
                    <label for="name">Họ và Tên:</label>
                    <input type="text" id="ho_ten" name="ho_ten" placeholder="Nhập họ và tên" required>
                </div>
                <div class="input">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email" required>
                </div>
            </div>
            <div class="noidung">
                <label for="message">Nội Dung:</label>
                <textarea id="noi_dung" name="noi_dung" placeholder="Nhập nội dung" rows="5" required></textarea>
            </div>
            <input type="submit" value="Gửi" class="submit-btn">
            
        </form>
    </div>
</body>
</html>
