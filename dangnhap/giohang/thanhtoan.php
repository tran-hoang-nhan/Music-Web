<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Vui lòng đăng nhập trước khi thanh toán!'); window.location.href='../dangnhap2.html';</script>";
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'dsthanhvien');

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$username = $_SESSION['username'];
$sql_gio_hang = "SELECT * FROM gio_hang WHERE username = '$username'";
$result_gio_hang = $conn->query($sql_gio_hang);
$gio_hang_items = [];

if ($result_gio_hang->num_rows > 0) {
    while ($row = $result_gio_hang->fetch_assoc()) {
        $gio_hang_items[] = $row;
    }
}
$total_amount = 0; // Biến để lưu tổng số tiền
if (!empty($gio_hang_items)) {
    foreach ($gio_hang_items as $item) {
        $total_price_per_item = (float)$item['gia'] * (int)$item['so_luong'];
        $total_amount += (float)$item['gia'] * $item['so_luong']; // Tính tổng tiền cho mỗi sản phẩm
    }
}
function insertOrder($conn, $order_id, $hoten, $email, $sdt, $birth, $diachi, $ten_nhac, $loai, $so_luong, $gia_donvi) {
    $gia = $gia_donvi * $so_luong;
    $sql_insert = "INSERT INTO don_hang (order_id, hoten, email, sdt, birth, diachi, ten_nhac, loai, so_luong, gia, trang_thai)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Đang tạo đơn')";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("issssssssi", $order_id, $hoten, $email, $sdt, $birth, $diachi, $ten_nhac, $loai, $so_luong, $gia);
    $stmt_insert->execute();
    $stmt_insert->close(); 
}
if (isset($_POST['cod'])) {
    $paytype = 'Thanh toán COD';
    $payment_method = 'cod';
} elseif (isset($_POST['momo'])) {
    $paytype = 'Thanh toán MoMo';
    $_SESSION['payment_method'] = 'momo';
} elseif (isset($_POST['momo_atm'])) {
    $paytype = 'Thanh toán MoMo ATM';
    $_SESSION['payment_method'] = 'momo_atm';
} else {
    $payment_method = ''; 
}
if (isset($_POST['cod'])) {
    if (empty($_POST['hoten']) || empty($_POST['email']) || empty($_POST['sdt']) || empty($_POST['birth']) || empty($_POST['diachi'])) {
        echo "<script>alert('Vui lòng điền đầy đủ thông tin trước khi thanh toán!'); window.location.href='thanhtoan.php';</script>";
        exit;
    }
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $birth = $_POST['birth'];
    $diachi = $_POST['diachi'];
    $order_id = time(); 
    foreach ($gio_hang_items as $item) {
        $ten_nhac = $item['ten_nhac'];
        $loai = $item['loai'];
        $so_luong = $item['so_luong'];
        $gia = (float)$item['gia'];
        insertOrder($conn, $order_id, $hoten, $email, $sdt, $birth, $diachi, $ten_nhac, $loai, $so_luong, $gia);
    }
    // Cập nhật phương thức thanh toán vào bảng don_hang
    $sql_update_paytype = "UPDATE don_hang SET paytype = ? WHERE order_id = ?";
    $stmt_update = $conn->prepare($sql_update_paytype);
    $stmt_update->bind_param("si", $paytype, $order_id);
    $stmt_update->execute();

    $sql_delete_gio_hang = "DELETE FROM gio_hang WHERE username = ?";
    $stmt_delete = $conn->prepare($sql_delete_gio_hang);
    $stmt_delete->bind_param("s", $username);
    $stmt_delete->execute();
    if ($stmt_delete->execute()) {
        if ($payment_method == 'cod') {
            echo "<script>alert('Thanh toán COD thành công!'); window.location.href='../trang_user.php';</script>";
        } else {
            $sql_delete_order = "DELETE FROM don_hang WHERE order_id = ?";
            $stmt_delete = $conn->prepare($sql_delete_order);
            $stmt_delete->bind_param("i", $order_id);
            $stmt_delete->execute();
            echo "<script>alert('Thanh toán COD không thành công!'); window.location.href='../trang_user.php';</script>";
        }
    } else {
        echo "<script>alert('Lỗi khi xóa dữ liệu giỏ hàng: " . $conn->error . "');</script>";
    }
}

if (isset($_POST['momo_qr']) || isset($_POST['momo_atm'])) {
    if (empty($_POST['hoten']) || empty($_POST['email']) || empty($_POST['sdt']) || empty($_POST['birth']) || empty($_POST['diachi'])) {
        echo "<script>alert('Vui lòng điền đầy đủ thông tin trước khi thanh toán!'); window.location.href='thanhtoan.php';</script>";
        exit;
    }
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $birth = $_POST['birth'];
    $diachi = $_POST['diachi'];
    $order_id = time();
    
    foreach ($gio_hang_items as $item) {
        $ten_nhac = $item['ten_nhac'];
        $loai = $item['loai'];
        $so_luong = $item['so_luong'];
        $gia = (float)$item['gia'];
        insertOrder($conn, $order_id, $hoten, $email, $sdt, $birth, $diachi, $ten_nhac, $loai, $so_luong, $gia);
    }

    if (isset($_POST['momo_qr'])) {
        $paytype = 'Thanh toán MoMo QR';
        $_SESSION['payment_method'] = 'momo_qr';
    } elseif (isset($_POST['momo_atm'])) {
        $paytype = 'Thanh toán MoMo ATM';
        $_SESSION['payment_method'] = 'momo_atm';
    }
    $sql_update_paytype = "UPDATE don_hang SET paytype = ? WHERE order_id = ?";
    $stmt_update = $conn->prepare($sql_update_paytype);
    $stmt_update->bind_param("si", $paytype, $order_id);
    $stmt_update->execute();

    // Chuyển hướng tới trang MoMo (QR hoặc ATM)
    if ($_SESSION['payment_method'] == 'momo_qr') {
        header("Location: momo_QR.php");
    } else {
        header("Location: momo_atm.php");
    }
    exit;
}
if (isset($_GET['payment']) && $_GET['payment'] == 'success' && isset($_GET['orderId'])) {
    $order_id = $_GET['orderId'];

    // Cập nhật thông tin thanh toán sau khi MoMo trả về
    if (isset($_GET['resultCode']) && $_GET['resultCode'] == 0) {
        $paytype = ($_SESSION['payment_method'] == 'momo') ? 'Thanh toán MoMo QR' : 'Thanh toán MoMo ATM';
        $trang_thai = 'Đã thanh toán';

        // Cập nhật phương thức thanh toán và trạng thái đơn hàng
        $sql_update_paytype = "UPDATE don_hang SET paytype = ?, trang_thai = ? WHERE order_id = ?";
        $stmt_update = $conn->prepare($sql_update_paytype);
        $stmt_update->bind_param("ssi", $paytype, $trang_thai, $order_id);
        $stmt_update->execute();

        // Xóa giỏ hàng sau khi thanh toán thành công
        $sql_truncate_gio_hang = "DELETE FROM gio_hang WHERE username = ?";
        $stmt_truncate = $conn->prepare($sql_truncate_gio_hang);
        $stmt_truncate->bind_param("s", $username);
        if ($stmt_truncate->execute()) {
            echo "<script>alert('Thanh toán thành công!'); window.location.href='../trang_user.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi xóa dữ liệu giỏ hàng: " . $conn->error . "');</script>";
        }
    } else {
        // Thanh toán thất bại, xóa đơn hàng
        $sql_delete_order = "DELETE FROM don_hang WHERE order_id = ?";
        $stmt_delete = $conn->prepare($sql_delete_order);
        $stmt_delete->bind_param("i", $order_id);
        $stmt_delete->execute();
        echo "<script>alert('Thanh toán không thành công, đơn hàng đã bị xóa!'); window.location.href='thanhtoan.php';</script>";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THANH TOÁN ĐƠN HÀNG</title>
    <link rel="stylesheet" href="thanhtoan.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <section class="container">
        <header>Thanh toán đơn hàng</header>
        <form action="thanhtoan.php" method="POST" enctype="multipart/form-data" class="form1">
            <div class="column">
                <div class="input">
                    <label>Họ và tên: </label>
                    <input type="text" id="hoten" name="hoten" placeholder="Nhập họ tên của bạn" required>
                </div>
                <div class="input">
                    <label>Email: </label>
                    <input type="text" id="email" name="email" placeholder="Địa chỉ email của bạn" required>
                </div>
            </div>
            <div class="column">
                <div class="input">
                    <label>Số điện thoại: </label>
                    <input type="text" id="sdt" name="sdt" placeholder="Số điện thoại liên lạc" required>
                </div>
                <div class="input">
                    <label>Năm sinh: </label>
                    <input type="date" id="birth" name="birth" required>
                </div>
            </div>
            <div class="column">
                <div class="input">
                    <label>Địa chỉ: </label>
                    <input type="text" id="diachi" name="diachi" placeholder="Nhập địa chỉ hiện tại" required>
                </div>
            </div>

            <h3>Sản phẩm trong giỏ hàng:</h3>
            <table>
                <tr>
                    <th>Tên Nhạc</th>
                    <th>Loại</th>
                    <th>Số Lượng</th>
                    <th>Giá</th>
                </tr>
                <?php if (!empty($gio_hang_items)): ?>
                    <?php foreach ($gio_hang_items as $item): ?>
                        <?php
                            $total_price_per_item = (float)$item['gia'] * (int)$item['so_luong']; // Tính tổng tiền cho sản phẩm
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['ten_nhac']); ?></td>
                            <td><?php echo htmlspecialchars($item['loai']); ?></td>
                            <td><?php echo htmlspecialchars($item['so_luong']); ?></td>
                            <td><?php echo number_format($total_price_per_item, 0, ',', '.'); ?>đ</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Giỏ hàng trống!</td>
                    </tr>
                <?php endif; ?>
            </table>
            <h3>Tổng số tiền: <?php echo number_format($total_amount, 0, ',', '.'); ?>đ</h3>
            <div class="captcha">
                <div class="g-recaptcha" data-sitekey="6LeeOHMqAAAAAGwTDca8MBXsI-iZaqqdgx0MkZNy"></div>
            </div>
            <div class="button">
                <input type="submit" name="cod" value="Thanh toán COD" class="btn btn-success payment-button"> 
                <input type="submit" name="momo_qr" value="Thanh toán MOMO QR" class="btn btn-danger payment-button">
                <input type="submit" name="momo_atm" value="Thanh toán MOMO ATM" class="btn btn-danger payment-button">
            </div>
        </form>
    </section>
</body>
</html>


