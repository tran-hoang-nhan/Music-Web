<?php
    $servername = "localhost";
    $username = "root"; 
    $password = ""; 
    $dbname = "dsthanhvien";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }
    include 'chucnang/delete.php';
    $result = null; 
    $search_mode = false; 
    $selected_genre = ''; 
    if (isset($_POST['btn']) && !empty($_POST['noidung'])) {
        $search_mode = true; 
        $noidung = $_POST['noidung'];

        // Kiểm tra nếu nội dung tìm kiếm là số (tìm theo ID)
        if (is_numeric($noidung)) {
            // Tìm kiếm chính xác theo ID
            $sql = "SELECT * FROM thanhvien WHERE id = '$noidung'";
        } else {
            // Nếu không phải số, tìm kiếm theo username
            $sql = "SELECT * FROM thanhvien WHERE username LIKE '%$noidung%'";
        }

        $result = $conn->query($sql);
    } else if (isset($_POST['theloai']) && !empty($_POST['theloai'])) {
        // Kiểm tra và lọc theo thể loại
        $search_mode = true;
        $selected_genre = $_POST['theloai'];
        $sql = "SELECT * FROM thanhvien WHERE theloai = '$selected_genre'";
        $result = $conn->query($sql);
    }
    if (!$search_mode) {
        $results_per_page = 5;
        $query = "SELECT * FROM thanhvien";
        $result = mysqli_query($conn, $query);
        $number_of_result = mysqli_num_rows($result);
        $number_of_page = ceil($number_of_result / $results_per_page);

        // Kiểm tra số trang hiện tại
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }

        // Lấy kết quả đầu tiên trên mỗi trang
        $page_first_result = ($page - 1) * $results_per_page;
        $query = "SELECT * FROM thanhvien LIMIT $page_first_result, $results_per_page";
        $result = mysqli_query($conn, $query);
    }

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Lý Thành Viên</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    
    <div class="container">
        <div class="class1">
            <form id="search_genre" method="POST">
                <select name="theloai">
                    <option value="">All</option>
                    <option value="Rap" <?php if ($selected_genre == 'Rap') echo 'selected'; ?>>Rap</option>
                    <option value="Rock" <?php if ($selected_genre == 'Rock') echo 'selected'; ?>>Rock</option>
                    <option value="Pop" <?php if ($selected_genre == 'Pop') echo 'selected'; ?>>Pop</option>
                    <option value="Grunge" <?php if ($selected_genre == 'Grunge') echo 'selected'; ?>>Grunge</option>
                    <option value="RnB" <?php if ($selected_genre == 'RnB') echo 'selected'; ?>>RnB</option>
                </select>
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
            <button class="up_nhac">
                <a href="chucnang/up_nhac.php">Cập nhật thêm bài nhạc</a>
            </button>
            <button class="xem_lienhe">
                <a href="chucnang/xem_lienhe.php">Xem liên hệ từ người dùng</a>
            </button>
        </div>
        <div class="class2">
            <header>Danh Sách Thành Viên</header>
            <form id="search" method="POST">
                <input type="text" name="noidung" placeholder="Tìm kiếm theo ID hoặc tên thành viên">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
            <table border="1" cellpadding="4" cellspacing="0" width="1000px">
                <tr>
                    <th>ID Thành viên</th>
                    <th>Nghệ danh</th>
                    <th>Thể Loại</th>
                    <th>Bài Nhạc</th>
                    <th>Thông tin chi tiết</th>
                    <th>Thông tin đơn hàng</th>
                </tr>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['theloai']}</td>
                                <td><audio controls><source src='../dangky/{$row['bai_nhac']}' type='audio/mpeg'>Trình duyệt không hỗ trợ audio</audio></td>
                                <td class='chitiet-cell'><a href='chucnang/chitiet.php?id={$row['id']}'class='chitiet-link'>Xem chi tiết</a></td>
                                <td class='donhang-cell'>
                                    <a href='chucnang/donhang.php?id={$row['id']}'class='donhang-link'>Nhấn để xem đơn hàng</a>
                                </td>    
                                <td class='delete-cell'><a href='admin.php?delete_id={$row['id']}' onclick=\"return confirm('Bạn có chắc muốn xóa thành viên này không?')\" class='delete-link'>Xóa</a></td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Không có thành viên nào.</td></tr>";
                }
                ?>
            </table>

            <?php if (!$search_mode) { ?>
            <div class="pagination">
                <?php
                for ($page = 1; $page <= $number_of_page; $page++) {
                    echo '<a href="admin.php?page=' . $page . '">' . $page . '</a> ';
                }
                ?>
            </div>
            <?php } ?>
        </div>
        <div class="class3">
        <header>Danh sách bài nhạc</header>
            <?php include 'chucnang/music.php'; ?>
            <form id="search" method="POST">
                <input type="text" name="search_music" placeholder="Tìm kiếm theo tên nhạc hoặc tác giả" value="<?php echo htmlspecialchars($search_term_music); ?>">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
            <table border="1" cellpadding="4" cellspacing="0" width="1000px">
                <tr>
                    <th>ID </th>
                    <th>Bài Nhạc</th>
                    <th>Tác Giả</th>
                </tr>
                <?php
                if ($result_music && $result_music->num_rows > 0) {
                    while ($row = $result_music->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['ten_nhac']}</td>
                                <td>{$row['tac_gia']}</td>
                                <td><a href='admin.php?delete_music_id={$row['id']}' onclick=\"return confirm('Bạn có chắc muốn xóa bài nhạc này không?')\" class='delete-link'>Xóa</a></td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Không có thành viên nào.</td></tr>";
                }
                ?>
            </table>

            <div class="pagination">
                <?php
                for ($page = 1; $page <= $number_of_page_music; $page++) {
                    echo '<a href="admin.php?page_music=' . $page . '">' . $page . '</a> ';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php
// Đóng kết nối
$conn->close();
?>
