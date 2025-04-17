<?php
    $servername = "localhost";
    $username = "root"; 
    $password = ""; 
    $dbname = "dsthanhvien"; 
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];

        $stmt = $conn->prepare("DELETE FROM thanhvien WHERE id = ?");
        $stmt->bind_param("i", $delete_id);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Đã xóa thành viên có ID: $delete_id thành công.</p>";
        } else {
            echo "<p style='color: red;'>Lỗi khi xóa thành viên: " . $stmt->error . "</p>";
        }
        $stmt->close();
        header("Location: ../admin/admin.php"); 
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_music_id'])) {
        $delete_music_id = $_GET['delete_music_id'];
        $sql_music = "SELECT file_nhac FROM music WHERE id = ?";
        $stmt_music = $conn->prepare($sql_music);
        $stmt_music->bind_param("i", $delete_music_id);
        $stmt_music->execute();
        $result_music = $stmt_music->get_result();
    
        if ($result_music->num_rows > 0) {
            $music_row = $result_music->fetch_assoc();
            $file_path = 'C:\xampp\htdocs\Source_code\baitap_detai\dangnhap\music' . DIRECTORY_SEPARATOR . $music_row['file_nhac'];
            $stmt = $conn->prepare("DELETE FROM music WHERE id = ?");
            $stmt->bind_param("i", $delete_music_id);
    
            if ($stmt->execute()) {
                if (file_exists($file_path)) {
                    if (unlink($file_path)) {
                        echo "<p style='color: green;'>Đã xóa bài nhạc và tệp âm thanh có ID: $delete_music_id thành công.</p>";
                    } else {
                        echo "<p style='color: red;'>Không thể xóa tệp âm thanh!</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Không tìm thấy tệp âm thanh để xóa!</p>";
                }
                $reset_autoincrement = "ALTER TABLE music AUTO_INCREMENT = 1";
                $conn->query($reset_autoincrement);
            } else {
                echo "<p style='color: red;'>Lỗi khi xóa bài nhạc: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color: red;'>Không tìm thấy bài nhạc để xóa!</p>";
        }
        $stmt_music->close();
        // Chỉ chuyển hướng sau khi đã hoàn tất tất cả các thông báo
        header("Location: ../admin/admin.php"); 
        exit;
    }
