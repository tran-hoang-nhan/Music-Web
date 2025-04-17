    <?php 
        session_start();
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dsthanhvien";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Kết nối thất bại: " . $conn->connect_error);
        }

        
        $query= "SELECT id, ten_nhac, tac_gia, file_nhac, file_hinh FROM music";
        $playlist = mysqli_query($conn, $query);
        $playlistData = [];
        if ($playlist->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($playlist)) {
                $playlistData[] = $row;
            }
        }
        $_SESSION['playlist'] = $playlistData; // Lưu toàn bộ playlist vào session

        $cart_count = 0;
        if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['so_luong'];
        }
    }
?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thông tin tài khoản</title>
        <link rel="stylesheet" href="login.css">
        <link rel="icon" href="image/favicon.png" type="image/png">
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
        <script src="player.js"></script>
    </head>
    <body>
        <div class="container">
            <?php if (isset($_SESSION['username'])): ?>
                <div class="user-info">
                    <div class="column1">
                        <h1>Thông tin người dùng</h1>
                        <button class="toggle-info-btn"><i class="fa-solid fa-arrow-down"></i></button>
                    </div>
                    <form action="logout.php" method="POST" class="info">
                        <p><strong>Name: </strong> <?= htmlspecialchars($_SESSION['hoten']) ?></p>
                        <p><strong>Stage Name:</strong> <?= htmlspecialchars($_SESSION['username']) ?></p>
                        <p><strong>Phone Number:</strong> <?= htmlspecialchars($_SESSION['sdt']) ?></p>
                        <p><strong>Birth:</strong> <?= htmlspecialchars($_SESSION['birth']) ?></p>
                        <p><strong>Gender:</strong> <?= htmlspecialchars($_SESSION['gioitinh']) ?></p>
                        <p><strong>Genres:</strong> <?= htmlspecialchars($_SESSION['theloai']) ?></p>
                        <p><strong>Song: <a href="songs.php">Tất cả bài nhạc của bạn </a></strong></p>
                        <p><strong>Ngày đăng ký:</strong> <?= htmlspecialchars($_SESSION['ngaydky']) ?></p>
                        <button type="submit" class="logout-button"><i class="fa-solid fa-right-from-bracket"></i></button>
                    </form>
                    <div class="column1">
                        <h1>Đơn Hàng</h1>
                        <button class="toggle-orders-btn"><i class="fa-solid fa-arrow-down"></i></button>
                    </div>
                    <div class="orders">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên Nhạc</th>
                                    <th>Số Lượng</th>
                                    <th>Giá</th>
                                    <th>Trạng Thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Truy vấn để lấy thông tin đơn hàng
                                $sql = "SELECT order_id, ten_nhac, so_luong, gia, trang_thai FROM don_hang";
                                $orders = $conn->query($sql);

                                if ($orders->num_rows > 0) {
                                    while ($row = $orders->fetch_assoc()) {
                                        $order_id = htmlspecialchars($row['order_id']);
                                        $ten_nhac = htmlspecialchars($row['ten_nhac']);
                                        $so_luong = htmlspecialchars($row['so_luong']);
                                        $gia = htmlspecialchars($row['gia']);
                                        $trang_thai = htmlspecialchars($row['trang_thai']);
                                ?>        
                                        <tr>
                                                <td><?= htmlspecialchars($row['order_id'])?></td>
                                                <td><?= htmlspecialchars($row['ten_nhac'])?></td>
                                                <td><?= htmlspecialchars($row['so_luong'])?></td>
                                                <td><?= number_format(htmlspecialchars($row['gia']), 0, ',', '.')?></td>
                                                <td><?= htmlspecialchars($row['trang_thai'])?></td>
                                            </tr>
                                <?php }
                                } else {
                                    echo "<tr><td colspan='5'>Không có đơn hàng nào.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
            <?php else: ?>
                <div class="login-prompt">
                    <h2>Hãy đăng nhập vào tài khoản của bạn</h2>
                    <a href="dangnhap2.html" class="login-button">Đăng nhập</a>
                </div>
            <?php endif; ?>

            <div class="v1"></div>
            <div class="playlist">
                <div class="column">
                    <h1>Playlist</h1>
                    <div id="search">
                        <input type="text" id="search-input" placeholder="Tìm kiếm bài hát..." oninput="searchSongs()">
                        <button type="submit"><i class="fa fa-search"></i></button>
                    </div>
                    <a href="giohang/xem_giohang.php" >
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                    <a href="lienhe/lien_he.php" >
                        <i class="fas fa-envelope"></i>
                    </a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên Nhạc</th>
                            <th>Tác Giả</th>
                            <th>Thêm Vào Giỏ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($playlistData as $index => $row): ?>
                                <tr>
                                    <td class='song-id'>
                                        <div>
                                            <span class='id-number'><?=htmlspecialchars($row['id'])?></span>
                                            <button type='button' class='play-icon-button'>
                                                <i class='fas fa-play play-icon'></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($row['ten_nhac']) ?></td>
                                    <td><?= htmlspecialchars($row['tac_gia']) ?></td>
                                    <td>
                                        <form action='giohang/add_giohang.php' method='POST'>
                                            <input type='hidden' name='ten_nhac' value="<?= htmlspecialchars($row['ten_nhac']) ?>">
                                            <input type='hidden' name='tac_gia' value="<?= htmlspecialchars($row['tac_gia']) ?>">
                                            <select name='loai' required onchange='updatePrice()'>
                                                <option value='cd' data-price='10000'>Đĩa CD - 10,000 VNĐ</option>
                                                <option value='than' data-price='2000000'>Đĩa Than - 2,000,000 VNĐ</option>
                                                <option value='cat_xet' data-price='200000'>Đĩa Cát Sét - 200,000 VNĐ</option>
                                            </select>
                                            <input type='number' name='so_luong' value='1' min='1' required>
                                            <button type='submit'>Thêm vào giỏ</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                    </tbody>
                </table>
            </div>    
        <div id="fixed-player">
            <div id="outer-circle">
                <div id="circle">
                    <img id="song-image" src="<?= htmlspecialchars($_SESSION['current_song_image'] ?? '') ?>" alt="Song Image">
                </div>
            </div>
            <div id="current-song-info">
                <span id="name"><?= htmlspecialchars($_SESSION['current_song_name'] ?? 'Chưa chọn bài hát') ?></span>
                <span id="author"><?= htmlspecialchars($_SESSION['current_song_author'] ?? '') ?></span>
            </div>
            <div id="player-controls">
                <button id="prev-song"><i class="fa-solid fa-backward"></i></button>
                <audio id="audio-player" src="<?= htmlspecialchars($_SESSION['current_song_file'] ?? '') ?>" controls autoplay></audio>
                <button id="next-song"><i class="fa-sharp fa-solid fa-forward"></i></button>    
            </div>
            <div class="volume-controls">
                <button id="volume-btn" class="volume-btn">
                    <i class="fa fa-volume-up"></i>
                </button>
                <input id="volume-slider" type="range" min="0" max="1" step="0.1" value="1" class="volume-slider">
            </div>
        </div>
        <script>
            document.querySelectorAll('.play-icon-button').forEach((button, index) => {
                button.addEventListener('click', () => {
                    fetch('save_songs.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `index=${index}`,
                    })
                        .then(() => location.reload())
                        .catch(console.error);
                });
            });
        </script>
    </body>
    </html>
    <?php
    $conn->close();
    ?>
