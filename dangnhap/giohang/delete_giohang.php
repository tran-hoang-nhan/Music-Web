<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Xóa sản phẩm khỏi giỏ hàng
    $stmt = $conn->prepare("DELETE FROM gio_hang WHERE id = ? AND username = ?");
    $stmt->bind_param("is", $id, $_SESSION['username']);
    
    if ($stmt->execute()) {
        // Sau khi xóa, chuyển hướng về trang giỏ hàng
        echo "<script>alert('Sản phẩm đã được xóa khỏi giỏ hàng.'); window.location.href='xem_giohang.php';</script>";
    } else {
        echo "<script>alert('Có lỗi xảy ra, không thể xóa sản phẩm.'); window.location.href='xem_giohang.php';</script>";
    }
    $stmt->close();
} else {
    // Nếu không có id, chuyển hướng về giỏ hàng
    echo "<script>window.location.href='xem_giohang.php';</script>";
}
?>
