<?php
header('Content-type: text/html; charset=utf-8');

// Hàm gửi yêu cầu POST
function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ));
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    
    // Thực thi yêu cầu POST
    $result = curl_exec($ch);
    // Đóng kết nối
    curl_close($ch);
    return $result;
}
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dsthanhvien";
// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'dsthanhvien');
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Tính tổng giá trị giỏ hàng từ cơ sở dữ liệu
$username = $_SESSION['username'];  // Giả sử bạn có tên người dùng trong session
$amount = 0;
$sql = "SELECT gia, so_luong FROM gio_hang WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($gia, $so_luong);

while ($stmt->fetch()) {
    $amount += $gia * $so_luong;  // Tính tổng giá trị giỏ hàng
}

$stmt->close();

$endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

// Thông tin xác thực MoMo
$partnerCode = 'MOMOBKUN20180529';
$accessKey = 'klm05TvNBzhg7h7j';
$secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';    
$orderInfo = "Thanh toán qua MoMo ATM";
$orderId = time() . ""; // Mã đơn hàng
$redirectUrl = "http://localhost/Source_code/baitap_detai/dangnhap/giohang/thanhtoan.php?payment=success&orderId=" . $orderId;
$ipnUrl = "http://localhost/Source_code/baitap_detai/dangnhap/giohang/thanhtoan.php?payment=success&orderId=" . $orderId;
$extraData = "";

// Tạo hash chữ ký
$requestId = time() . "";
$requestType = "payWithATM"; // Kiểu thanh toán qua ATM
$extraData = isset($_POST['extraData']) ? $_POST['extraData'] : "";

// Tạo chuỗi dữ liệu để tạo chữ ký
$rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
$signature = hash_hmac("sha256", $rawHash, $secretKey);

// Dữ liệu gửi đi trong yêu cầu
$data = array(
    'partnerCode' => $partnerCode,
    'partnerName' => "Test",
    "storeId" => "MomoTestStore",
    'requestId' => $requestId,
    'amount' => $amount,
    'orderId' => $orderId,
    'orderInfo' => $orderInfo,
    'redirectUrl' => $redirectUrl,
    'ipnUrl' => $ipnUrl,
    'lang' => 'vi',
    'extraData' => $extraData,
    'requestType' => $requestType,
    'signature' => $signature
);

// Gửi yêu cầu POST tới MoMo API
$result = execPostRequest($endpoint, json_encode($data));
$jsonResult = json_decode($result, true);  // Giải mã kết quả JSON

// Nếu kết quả thành công, chuyển hướng người dùng đến trang thanh toán MoMo
if ($jsonResult['resultCode'] == 0) {
    header('Location: ' . $jsonResult['payUrl']);
} else {
    echo "Lỗi trong quá trình thanh toán: " . $jsonResult['message'];
    exit;
}