<?php 
if (isset($_GET['payment']) && $_GET['payment'] == 'success' && isset($_GET['orderId'])) {
    // Lấy thông tin đơn hàng từ query string (orderId)
    $order_id = $_GET['orderId']; 
    
    // Kết nối cơ sở dữ liệu
    $conn = new mysqli('localhost', 'root', '', 'dsthanhvien');
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Cập nhật phương thức thanh toán vào cột paytype
    $sql_update_paytype = "UPDATE don_hang SET paytype = 'Thanh toán qua MoMo ATM' WHERE order_id = ?";
    $stmt_update = $conn->prepare($sql_update_paytype);
    $stmt_update->bind_param("s", $order_id); // Giả sử orderId là chuỗi
    $stmt_update->execute();

    // Kiểm tra nếu cập nhật thành công
    if ($stmt_update->affected_rows > 0) {
        echo "<script>alert('Thanh toán thành công qua MoMo ATM!'); window.location.href='../trang_user.php';</script>";
    } else {
        echo "<script>alert('Cập nhật trạng thái thanh toán thất bại.'); window.location.href='thanhtoan.php';</script>";
    }

    // Đóng kết nối
    $conn->close();
}