<?php
// Kết nối đến MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dsthanhvien";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Truy vấn thông tin chi tiết thành viên
    $sql = "SELECT * FROM thanhvien WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_info = $result->fetch_assoc();
} else {
    echo "Không có ID thành viên.";
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoten = $_POST['hoten'];
    $username = $_POST['username'];
    $theloai = $_POST['theloai'];
    $fb = $_POST['fb'];
    $sdt = $_POST['sdt'];
    $birth = $_POST['birth'];
    $gioitinh = $_POST['gioitinh'];

    $update_sql = "UPDATE thanhvien SET hoten = ?, username = ?, theloai = ?, fb = ?, sdt = ?, birth = ?, gioitinh = ? WHERE id = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("sssssssi", $hoten, $username, $theloai, $fb, $sdt, $birth, $gioitinh, $id);
    if ($stmt_update->execute()) {
        echo "<p>Thông tin đã được cập nhật thành công!</p>";
        // Cập nhật lại dữ liệu hiển thị sau khi lưu
        header("Location: chitiet.php?id=$id");
        exit();
    } else {
        echo "<p>Có lỗi xảy ra: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Thành Viên</title>
    <link rel="stylesheet" href="chitiet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
</head>
<body>
    <h1>Chi Tiết Thành Viên</h1>
    <a href="../admin.php" class="home-button">
        <i class="fas fa-home"></i>
    </a>
    <form method="POST">
        <table border="1" cellpadding="5" cellspacing="0" width="50%">
            <tr>
                <th>Thông Tin</th>
                <th>Chi Tiết</th>
            </tr>
            <tr>
                <td>Họ Tên</td>
                <td>
                    <span><?= htmlspecialchars($user_info['hoten']) ?></span>
                    <input type="text" name="hoten" value="<?= htmlspecialchars($user_info['hoten']) ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <td>Tên Đăng Nhập</td>
                <td>
                    <span><?= htmlspecialchars($user_info['username']) ?></span>
                    <input type="text" name="username" value="<?= htmlspecialchars($user_info['username']) ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <td>Thể Loại</td>
                <td>
                    <span><?= htmlspecialchars($user_info['theloai']) ?></span>
                    <input type="text" name="theloai" value="<?= htmlspecialchars($user_info['theloai']) ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <td>Facebook</td>
                <td>
                    <span><?= htmlspecialchars($user_info['fb']) ?></span>
                    <input type="text" name="fb" value="<?= htmlspecialchars($user_info['fb']) ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <td>Số Điện Thoại</td>
                <td>
                    <span><?= htmlspecialchars($user_info['sdt']) ?></span>
                    <input type="text" name="sdt" value="<?= htmlspecialchars($user_info['sdt']) ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <td>Năm Sinh</td>
                <td>
                    <span><?= htmlspecialchars($user_info['birth']) ?></span>
                    <input type="date" name="birth" value="<?= htmlspecialchars($user_info['birth']) ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <td>Giới Tính</td>
                <td>
                    <span><?= htmlspecialchars($user_info['gioitinh']) ?></span>
                    <input type="text" name="gioitinh" value="<?= htmlspecialchars($user_info['gioitinh']) ?>" style="display:none;">
                </td>
            </tr>
            <tr>
                <td>Ngày đăng ký</td>
                <td>
                    <span><?= htmlspecialchars($user_info['ngaydky']) ?></span>
                    <input type="text" name="ngaydky" value="<?= htmlspecialchars($user_info['ngaydky']) ?>" style="display:none;">
                </td>
            </tr>
        </table>

        
    </form>
    <a href="edit.php?id=<?= $id ?>"><button>Chỉnh sửa</button></a>
</body>
</html>
