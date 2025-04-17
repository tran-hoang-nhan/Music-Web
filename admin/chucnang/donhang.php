<?php
    $conn = new mysqli('localhost', 'root', '', 'dsthanhvien');

    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
        $order_id = $_POST['order_id']; 
        $new_status = $_POST['new_status']; 

        // Cập nhật trạng thái đơn hàng trong cơ sở dữ liệu
        $update_sql = "UPDATE don_hang SET trang_thai = ? WHERE order_id = ?";
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("si", $new_status, $order_id);

        if ($stmt_update->execute()) {
            echo "<script>alert('Cập nhật trạng thái thành công!');</script>";
            header("Location: donhang.php");
            exit(); 
        } else {
            echo "<script>alert('Lỗi khi cập nhật trạng thái: " . $stmt_update->error . "');</script>";
        }
    }
    ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đơn Hàng</title>
    <link rel="stylesheet" href="chitiet.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
</head>
<body>
    <div class="orders">
        <div class="h1">
            <h2>Danh Sách Đơn Hàng</h2>
            <a href="../admin.php" class="home-button">
                <i class="fas fa-home"></i>
            </a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Nhạc</th>
                    <th>Số Lượng</th>
                    <th>Giá</th>
                    <th>Phương thức thanh toán</th>
                    <th>Trạng Thái</th>
                    <th>Cập Nhật Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Truy vấn để lấy thông tin đơn hàng
                $sql = "SELECT order_id, ten_nhac, so_luong, gia, paytype, trang_thai FROM don_hang";
                $orders = $conn->query($sql);

                if ($orders->num_rows > 0) {
                    while ($row = $orders->fetch_assoc()) {
                        $order_id = htmlspecialchars($row['order_id']);
                        $ten_nhac = htmlspecialchars($row['ten_nhac']);
                        $so_luong = htmlspecialchars($row['so_luong']);
                        $gia = htmlspecialchars($row['gia']);
                        $paytype = htmlspecialchars($row['paytype']);
                        $trang_thai = htmlspecialchars($row['trang_thai']);

                        echo "<tr>
                                <td>$order_id</td>
                                <td>$ten_nhac</td>
                                <td>$so_luong</td>
                                <td>$gia</td>
                                <td>$paytype</td>
                                <td>$trang_thai</td>
                                <td>
                                    <form action='donhang.php' method='POST'>
                                        <input type='hidden' name='order_id' value='$order_id'>
                                        <select name='new_status'>
                                            <option value='Chưa xử lý'>Chưa xử lý</option>
                                            <option value='Đang tạo đơn'>Đang tạo đơn</option>
                                            <option value='Đang vận chuyển'>Đang vận chuyển</option>
                                            <option value='Đã giao thành công'>Đã giao thành công</option>
                                            <option value='Đã hủy'>Đã hủy</option>
                                        </select>
                                        <button type='submit' name='update_status'>Cập nhật</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có đơn hàng nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
