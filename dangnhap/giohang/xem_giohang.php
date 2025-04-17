<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dsthanhvien";

// Kết nối đến cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra nếu người dùng đã đăng nhập
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Vui lòng đăng nhập trước khi xem giỏ hàng!'); window.location.href='../dangnhap2.html';</script>";
    exit;
}
$username = $_SESSION['username']; // Lấy tên người dùng từ session

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM gio_hang WHERE id = ? AND username = ?");
    $stmt->bind_param("is", $id, $_SESSION['username']);
    if ($stmt->execute()) {
        echo "<script>alert('Xóa sản phẩm thành công!'); window.location.href='xem_giohang.php';</script>";
    } else {
        echo "<script>alert('Xóa sản phẩm thất bại!'); window.location.href='xem_giohang.php';</script>";
    }
    $stmt->close();
}
// Lấy giỏ hàng từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT id, ten_nhac, tac_gia, so_luong, gia,loai FROM gio_hang WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

?> 
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

</head>
<body>
    <div class="container">
        <div class="column">
            <h1>Giỏ Hàng của <?= htmlspecialchars($_SESSION['username']) ?></h1>
            <a href="../trang_user.php" class="home-button">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <?php if ($result->num_rows > 0):  ?>
        <table>
            <thead>
                <tr>
                    <th>Tên Nhạc</th>
                    <th>Tác Giả</th>
                    <th>Số Lượng</th>
                    <th>Loại</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tong_tien = 0; // Biến tổng tiền
                while ($row = $result->fetch_assoc()): // Lặp qua các sản phẩm trong giỏ hàng
                    $tong_san_pham = $row['so_luong'] * $row['gia'];
                    $tong_tien += $tong_san_pham;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['ten_nhac']) ?></td>
                        <td><?= htmlspecialchars($row['tac_gia']) ?></td>
                        <td><?= htmlspecialchars($row['so_luong']) ?></td>
                        <td><?= htmlspecialchars($row['loai']); ?></td>
                        <td><?= number_format(htmlspecialchars($row['gia']), 0, ',', '.') ?>đ</td>
                        <td><?= number_format(htmlspecialchars($tong_san_pham), 0, ',', '.') ?>đ</td>
                        <td><a href="xem_giohang.php?id=<?= $row['id'] ?>" class="delete-link">Xóa</a></td>
                        </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="column">
            <h3>Tổng Tiền: <?= number_format(htmlspecialchars($tong_tien),0,',','.') ?>đ</h3>
            <form action="thanhtoan.php" method="post">
                <a href="thanhtoan.php">THANH TOÁN</a>
            </form>
            <a href="../admin.php">Quay lại</a></button>
        </div>
        <?php else: ?>
            <p>Giỏ hàng của bạn đang trống.</p>
        <?php endif; ?>
        <?php $conn->close(); ?>
    </div>
</body>
</html>


