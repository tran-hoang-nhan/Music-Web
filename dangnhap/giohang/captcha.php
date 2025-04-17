<?php
$error_message = ""; // Biến lưu thông báo lỗi cho reCAPTCHA

// Xử lý thanh toán
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra token reCAPTCHA từ form
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $secret_key = '6LeeOHMqAAAAAM39DgkDSWD1HQNOi0Al_zZ1DMJl'; 

    // Gửi yêu cầu đến Google để xác minh reCAPTCHA
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$recaptcha_response");
    $response_keys = json_decode($response, true);

    // Kiểm tra kết quả từ Google
    if (intval($response_keys["success"]) === 1) {
        // reCAPTCHA thành công, chèn dữ liệu vào bảng don_hang
        $hoten = $conn->real_escape_string($_POST['hoten']);
        $email = $conn->real_escape_string($_POST['email']);
        $sdt = $conn->real_escape_string($_POST['sdt']);
        $birth = $conn->real_escape_string($_POST['birth']);
        $diachi = $conn->real_escape_string($_POST['diachi']);
        $trang_thai = "Đang chờ xử lý"; // Bạn có thể thay đổi trạng thái theo ý muốn

        $sql_insert = "INSERT INTO don_hang (hoten, email, sdt, birth, diachi, trang_thai) 
                       VALUES ('$hoten', '$email', '$sdt', '$birth', '$diachi', '$trang_thai')";

        if ($conn->query($sql_insert) === TRUE) {
            echo "<script>alert('Thanh toán thành công!'); window.location.href='trang_chu.php';</script>";
        } else {
            echo "Lỗi: " . $conn->error;
        }
    } else {
        // reCAPTCHA thất bại
        $error_message = "Hãy xác minh rằng bạn là người không phải là robot.";
    }
}