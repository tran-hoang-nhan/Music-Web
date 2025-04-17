<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dsthanhvien";

    // Connect to database
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    // Fetch songs from the thanhvien table
    $query = "SELECT bai_nhac FROM thanhvien WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();

    $songs = [];
    while ($row = $result->fetch_assoc()) {
        $songs[] = $row['bai_nhac'];
    }

    $stmt->close();
    $conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tất cả bài nhạc của bạn</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="songs">
        <?php if (isset($_SESSION['username'])): ?>
            <div class="h1">
                <h1>Tất cả bài nhạc của bạn</h1>
                <a href="trang_user.php" class="home-button">
                    <i class="fas fa-home"></i>
                </a>
            </div>
            <div class="ul">
                <ul>
                    <?php if (count($songs) > 0): ?>
                        <?php foreach ($songs as $song): ?>
                            <li>
                                <audio controls>
                                    <source src="<?= htmlspecialchars($song) ?>" type="audio/mp3">
                                    Your browser does not support the audio element.
                                </audio>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Chưa có bài hát nào.</li>
                    <?php endif; ?>
                </ul>
            </div>
        <?php else: ?>
            <p>Vui lòng đăng nhập để xem các bài hát của bạn.</p>
        <?php endif; ?>
    </div>
</body>
</html>
