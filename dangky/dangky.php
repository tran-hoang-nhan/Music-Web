
<?php
    session_start();
    $servername = "localhost"; 
    $username = "root";
    $password = "";
    $dbname = "dsthanhvien";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Lấy dữ liệu từ form
    $hoten = $_POST['hoten'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $fb = $_POST['fb']; 
    $birth = $_POST['birth'];
    $matkhau = $_POST['matkhau'];
    $matkhau2 = $_POST['matkhau2'];  
    $gioitinh = $_POST['gioitinh'];
    $music = $_POST['music'];
    // Xử lý mật khẩu không khớp
    if ($matkhau != $matkhau2) {
        echo "<script>alert('Mật khẩu không khớp. Vui lòng nhập lại.'); window.history.back();</script>";
        exit;
    }

    // Tạo captcha
    $captcha = $_POST['captcha'];
    if ($captcha != $_SESSION['captcha']) {
        echo "<script>alert('CAPTCHA không chính xác. Vui lòng thử lại.'); window.history.back();</script>";
        exit;
    }
    $ngaydky = date("Y-m-d"); // Lấy ngày hiện tại
    // Xử lý thể loại được chọn
    $genres = isset($_POST['options']) ? implode(", ", $_POST['options']) : "Không chọn";

    // Xử lý file hình ảnh
    $target_dir = "images/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["pic"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Giới hạn định dạng ảnh
    $allowed_image_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_image_types)) {
        echo "Chỉ cho phép định dạng JPG, JPEG, PNG, GIF.";
        exit;
    }

    // Kiểm tra file hình ảnh
    if ($_FILES["pic"]["error"] === UPLOAD_ERR_OK) {
        if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
            $pic = $target_file; // Lưu đường dẫn hình ảnh
        } else {
            echo "Có lỗi khi tải lên hình ảnh.";
            exit;
        }
    } else {
        echo "Lỗi tải ảnh: " . $_FILES["pic"]["error"];
        exit;
    }

    // Xử lý file nhạc
    $music_dir = "music/";
    if (!is_dir($music_dir)) {
        mkdir($music_dir, 0777, true);
    }
    $music_file = $music_dir . basename($_FILES["music"]["name"]);
    $musicFileType = strtolower(pathinfo($music_file, PATHINFO_EXTENSION));

    // Giới hạn định dạng nhạc
    $allowed_music_types = ['mp3', 'wav', 'ogg'];
    if (!in_array($musicFileType, $allowed_music_types)) {
        echo "Chỉ cho phép định dạng MP3, WAV, OGG.";
        exit;
    }

    // Kiểm tra file nhạc
    if (move_uploaded_file($_FILES["music"]["tmp_name"], $music_file)) {
        $music = $music_file;
    } else {
        echo "Có lỗi khi tải lên file nhạc.";
        exit;
    }

    // Câu lệnh SQL để chèn dữ liệu vào bảng thanhvien
    $sql_insert = "INSERT INTO thanhvien (hoten, username, email, sdt, matkhau, birth, gioitinh, ngaydky, fb, theloai, hinh_anh, bai_nhac) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssssssssssss", $hoten,$username, $email , $sdt, $matkhau, $birth,  $gioitinh, $ngaydky, $fb, $genres, $pic, $music);

    // Thực hiện câu lệnh SQL
    if ($stmt_insert->execute()) {
        echo "Đăng ký thành công!";
        header("Location: ../dangnhap/dangnhap2.html");
    } else {
        echo "Đã có lỗi xảy ra, vui lòng thử lại.";
    }

    // Đóng kết nối  
    $conn->close();
