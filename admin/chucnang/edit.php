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

// Lấy ID thành viên
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Lấy thông tin hiện tại của thành viên
    $sql = "SELECT * FROM thanhvien WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_info = $result->fetch_assoc();

    // Kiểm tra nếu form được submit để cập nhật dữ liệu
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $hoten = $_POST['hoten'];
        $username = $_POST['username'];
        $theloai = $_POST['theloai'];
        $fb = $_POST['fb'];
        $sdt = $_POST['sdt'];
        $birth = $_POST['birth'];
        $gioitinh = $_POST['gioitinh'];

        // Câu lệnh cập nhật thông tin thành viên
        $update_sql = "UPDATE thanhvien SET hoten = ?, username = ?, theloai = ?, fb = ?, sdt = ?, birth = ?, gioitinh = ? WHERE id = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("sssssssi", $hoten, $username, $theloai, $fb, $sdt, $birth, $gioitinh, $id);

        if ($stmt_update->execute()) {
            echo "<p>Thông tin đã được cập nhật thành công!</p>";
            header("Location: chitiet.php?id=" . $id); // Quay lại trang chi tiết sau khi cập nhật
            exit;
        } else {
            echo "<p>Có lỗi xảy ra: " . $conn->error . "</p>";
        }
    }

    // Đóng kết nối
    $stmt->close();
    $conn->close();
} else {
    echo "Không tìm thấy ID thành viên.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thành viên</title>
    <link rel="stylesheet" href="chitiet.css">
</head>
<body>
    <h1>Chỉnh sửa thông tin thành viên</h1>
    <form method="POST" action="">
        <label for="hoten">Name</label>
        <input type="text" name="hoten" value="<?= htmlspecialchars($user_info['hoten']) ?>" required>
        <br>
        <label for="username">Stage Name</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user_info['username']) ?>" required>
        <br>
        <label for="theloai">Genres</label>
        <input type="text" name="theloai" value="<?= htmlspecialchars($user_info['theloai']) ?>" required>
        <br>
        <label for="fb">Facebook:</label>
        <input type="text" name="fb" value="<?= htmlspecialchars($user_info['fb']) ?>">
        <br>
        <label for="sdt">Số điện thoại:</label>
        <input type="text" name="sdt" value="<?= htmlspecialchars($user_info['sdt']) ?>" required>
        <br>
        <label for="birth">Ngày sinh:</label>
        <input type="date" name="birth" value="<?= htmlspecialchars($user_info['birth']) ?>" required>
        <br>
        <label for="gioitinh">Giới tính:</label>
        <input type="text" name="gioitinh" value="<?= htmlspecialchars($user_info['gioitinh']) ?>" required>
        <br>
        <button type="submit">Cập nhật</button>
    </form>

    <!-- Nút quay lại -->
    <p><a href="chitiet.php?id=<?= $id ?>">Quay lại</a></p>
</body>
</html>
