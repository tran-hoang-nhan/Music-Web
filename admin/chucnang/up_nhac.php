<?php
// Kết nối đến MySQL
$servername = "localhost";
$username = "root"; // Username mặc định của XAMPP
$password = ""; // Mật khẩu mặc định của XAMPP là rỗng
$dbname = "dsthanhvien"; // Tên cơ sở dữ liệu

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Bài Nhạc</title>
    <link rel="stylesheet" href="chitiet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
</head>
<body>
    <h2>Thêm Bài Nhạc Mới</h2>
    <a href="../admin.php" class="home-button">
        <i class="fas fa-home"></i>
    </a>
    <form action="up_nhac.php" method="post" enctype="multipart/form-data">
        <label for="song_name">Tên Bài Hát:</label>
        <input type="text" name="song_name" required>
        <label for="author">Tác Giả:</label>
        <input type="text" name="author" required>
        <label for="music_file">Chọn File Nhạc:</label>
        <input type="file" name="music_file" accept="audio/*" required>
        <label for="img_file">Chọn File Hình:</label>
        <input type="file" name="img_file" accept="image/*" required>
        <button type="submit" name="upload">Thêm Nhạc</button>
    </form>

    <?php
    // Kiểm tra nếu form được gửi
    if (isset($_POST['upload'])) {
        $song_name = $_POST['song_name'];
        $author = $_POST['author'];

        // Kiểm tra nếu file nhạc được chọn và không có lỗi khi tải lên
        if (isset($_FILES['music_file']) && $_FILES['music_file']['error'] == 0) {
            $music_target_dir = "../../dangnhap/music/"; // Đường dẫn thư mục lưu nhạc
            $music_file_name = basename($_FILES['music_file']['name']);
            $music_target_file = $music_target_dir . $music_file_name;
            $music_file_type = pathinfo($music_target_file, PATHINFO_EXTENSION);

            // Kiểm tra loại file nhạc
            if ($music_file_type == "mp3" || $music_file_type == "wav" || $music_file_type == "ogg") {
                // Di chuyển file nhạc vào thư mục đích
                if (move_uploaded_file($_FILES['music_file']['tmp_name'], $music_target_file)) {
                    echo "File nhạc đã được tải lên thành công.";

                    // Kiểm tra nếu file hình ảnh được chọn và không có lỗi khi tải lên
                    if (isset($_FILES['img_file']) && $_FILES['img_file']['error'] == 0) {
                        $image_target_dir = "../../dangnhap/image/"; // Đường dẫn thư mục lưu hình ảnh
                        $image_file_name = basename($_FILES['img_file']['name']);
                        $image_target_file = $image_target_dir . $image_file_name;
                        $image_file_type = pathinfo($image_target_file, PATHINFO_EXTENSION);

                        // Kiểm tra loại file hình ảnh
                        if ($image_file_type == "jpg" || $image_file_type == "jpeg" || $image_file_type == "png" || $image_file_type == "gif") {
                            // Di chuyển file hình vào thư mục đích
                            if (move_uploaded_file($_FILES['img_file']['tmp_name'], $image_target_file)) {
                                echo "<br>File hình đã được tải lên thành công.";
                                $song_name = $conn->real_escape_string($song_name);
                                $author = $conn->real_escape_string($author);
                                // Lưu thông tin bài hát vào cơ sở dữ liệu với đường dẫn music/ và image/
                                $music_file_path = $conn->real_escape_string("music/" . $music_file_name);
                                $image_file_path = $conn->real_escape_string("image/" . $image_file_name);
                                $sql = "INSERT INTO music (ten_nhac, tac_gia, file_nhac, file_hinh) VALUES ('$song_name', '$author', '$music_file_path', '$image_file_path')";
                                
                                if ($conn->query($sql) === TRUE) {
                                    echo "<br>Bài hát đã được thêm vào cơ sở dữ liệu.";
                                    // Chuyển hướng để reload lại trang
                                    header("Location: up_nhac.php");
                                    exit();
                                } else {
                                    echo "<br>Lỗi khi lưu vào cơ sở dữ liệu: " . $conn->error;
                                }
                            } else {
                                echo "<br>Lỗi khi tải file hình lên.";
                            }
                        } else {
                            echo "<br>Chỉ chấp nhận các file hình có định dạng .jpg, .jpeg, .png, .gif.";
                        }
                    } else {
                        echo "<br>Vui lòng chọn một file hình hợp lệ.";
                    }
                } else {
                    echo "Lỗi khi tải file nhạc lên.";
                }
            } else {
                echo "Chỉ chấp nhận các file nhạc có định dạng .mp3, .wav, hoặc .ogg.";
            }
        } else {
            echo "Vui lòng chọn một file nhạc hợp lệ.";
        }
    }

    // Đóng kết nối
    $conn->close();
    ?>

</body>
</html>
