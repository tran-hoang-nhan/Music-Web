<?php
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

// Lấy danh sách liên hệ từ cơ sở dữ liệu
$sql = "SELECT id, username, hoten_gui, email, noidung, ngay_gui FROM lien_he";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Liên Hệ</title>
    <link rel="stylesheet" href="chitiet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
</head>
<body>
    <div class="lienhe">
        <header>Quản Lý Liên Hệ</header>
        <div class="h1">
            <a href="../admin.php" class="home-button">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Họ Tên</th>
                    <th>Email</th>
                    <th>Nội Dung</th>
                    <th>Thời Gian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['username']; ?></td>
                            <td><?= htmlspecialchars($row['hoten_gui']); ?></td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['noidung']); ?></td>
                            <td><?= $row['thoigian']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Không có liên hệ nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
