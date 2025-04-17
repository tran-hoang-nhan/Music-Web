-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 21, 2024 lúc 01:29 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dsthanhvien`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `don_hang`
--

CREATE TABLE `don_hang` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `hoten` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `sdt` varchar(15) DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `diachi` varchar(255) DEFAULT NULL,
  `loai` varchar(50) DEFAULT '''đĩa CD''',
  `ten_nhac` varchar(255) DEFAULT NULL,
  `so_luong` int(11) DEFAULT NULL,
  `gia` int(11) DEFAULT NULL,
  `paytype` varchar(255) DEFAULT NULL,
  `trang_thai` varchar(50) DEFAULT 'Chưa xử lý'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `don_hang`
--

INSERT INTO `don_hang` (`id`, `order_id`, `hoten`, `email`, `sdt`, `birth`, `diachi`, `loai`, `ten_nhac`, `so_luong`, `gia`, `paytype`, `trang_thai`) VALUES
(6, 1731590327, 'Trần Hoàng Nhân', 'likeand2005@gmail.com', '0931373319', '2024-10-31', 'aaa', 'đĩa CD', 'Come As You Are', 1, 10000, 'Thanh toán COD', 'Đang tạo đơn'),
(7, 1731591533, 'Trần Hoàng Nhân', 'likeand2005@gmail.com', '0931373319', '2024-11-07', 'aaa', 'đĩa CD', 'Come As You Are', 1, 10000, 'Thanh toán COD', 'Đang tạo đơn'),
(9, 1731593189, 'Trần Hoàng Nhân', 'likeand2005@gmail.com', '0931373319', '2024-11-03', 'aaa', 'đĩa CD', 'Come As You Are', 1, 10000, 'Thanh toán MoMo ATM', 'Đã thanh toán '),
(12, 1732033767, 'Trần Hoàng Nhân', 'likeand2005@gmail.com', '0931373319', '2024-11-03', 'aaa', 'Đĩa CD', 'Come As You Are', 2, 20000, 'Thanh toán COD', 'Đang tạo đơn'),
(13, 1732033767, 'Trần Hoàng Nhân', 'likeand2005@gmail.com', '0931373319', '2024-11-03', 'aaa', 'Đĩa Than', 'Come As You Are', 1, 2000000, 'Thanh toán COD', 'Đang tạo đơn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gio_hang`
--

CREATE TABLE `gio_hang` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `ten_nhac` varchar(255) DEFAULT NULL,
  `tac_gia` varchar(255) DEFAULT NULL,
  `so_luong` int(11) DEFAULT NULL,
  `gia` int(11) DEFAULT NULL,
  `loai` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `gio_hang`
--

INSERT INTO `gio_hang` (`id`, `username`, `ten_nhac`, `tac_gia`, `so_luong`, `gia`, `loai`) VALUES
(22, 'thn2212', 'Come As You Are', 'Nirvana', 2, 10000, 'Đĩa CD'),
(23, 'thn2212', 'Come As You Are', 'Nirvana', 1, 2000000, 'Đĩa Than');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lien_he`
--

CREATE TABLE `lien_he` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `hoten_gui` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `noidung` text DEFAULT NULL,
  `ngay_gui` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `music`
--

CREATE TABLE `music` (
  `id` int(11) NOT NULL,
  `ten_nhac` varchar(255) DEFAULT NULL,
  `tac_gia` varchar(255) DEFAULT NULL,
  `file_nhac` varchar(255) DEFAULT NULL,
  `file_hinh` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `music`
--

INSERT INTO `music` (`id`, `ten_nhac`, `tac_gia`, `file_nhac`, `file_hinh`) VALUES
(1, 'Come As You Are', 'Nirvana', 'music/Come As You Are.mp3', 'image/Nevermind.jpg'),
(2, 'Dumb', 'Nirvana', 'music/Dumb.mp3', 'image/In Utero.jpg'),
(3, 'Heart-Shaped Box', 'Nirvana', 'music/Heart-Shaped Box.mp3', 'image/In Utero.jpg\r\n'),
(4, 'Chicago', 'Michael Jackson', 'music/Chicago.mp3', 'image/XSCAPE.jpg'),
(5, 'KEEP UP', 'Odetari', 'music/KEEP UP.mp3', 'image/KEEP UP.jpg'),
(6, 'Eleanor Rigby', 'The Beatles', 'music/Eleanor Rigby.mp3', 'image/Yellow Submarine.jpg'),
(7, 'Reptilia', 'The Strokes', 'music/Reptilia.mp3', 'image/Room On Fire.jpg'),
(8, 'Revenge', 'XXXTENTACION', 'music/Revenge.mp3', 'image/17.jpg'),
(9, 'Sky', 'Playboi Carti', 'music/Sky.mp3', 'image/Whole Lotta Red.jpg'),
(10, 'Stairway to Heaven', 'Led Zeppelin', 'music/Stairway to Heaven.mp3', 'image/Led Zeppelin IV.jpg'),
(11, 'The Morning', 'The Weeknd', 'music/The Morning.mp3', 'image/House Of Balloons.jpg\r\n'),
(12, 'You\'re Somebody Else', 'flora cash', 'music/You\'re Somebody Else.mp3', 'image/Baby,It\'s Okay.jpg'),
(13, 'All Apologies', 'Nirvana', 'music/All Apologies.mp3', 'image/In Utero.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thanhvien`
--

CREATE TABLE `thanhvien` (
  `id` int(11) NOT NULL,
  `hoten` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `matkhau` varchar(255) DEFAULT NULL,
  `fb` varchar(255) DEFAULT NULL,
  `sdt` varchar(255) DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `gioitinh` varchar(255) DEFAULT NULL,
  `theloai` varchar(255) DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `bai_nhac` varchar(255) DEFAULT NULL,
  `ngaydky` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thanhvien`
--

INSERT INTO `thanhvien` (`id`, `hoten`, `email`, `username`, `matkhau`, `fb`, `sdt`, `birth`, `gioitinh`, `theloai`, `hinh_anh`, `bai_nhac`, `ngaydky`) VALUES
(1, 'Trần Hoàng Nhân', 'likeand2005@gmail.com', 'thn2212', '123456', 'https://www.facebook.com/nhann.tran.370', '0931373319', '2024-11-03', 'Nam', 'Rap, Rock', 'images/Screenshot (62).png', 'music/Chicago.mp3', '2024-11-01');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `lien_he`
--
ALTER TABLE `lien_he`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `music`
--
ALTER TABLE `music`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `thanhvien`
--
ALTER TABLE `thanhvien`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `lien_he`
--
ALTER TABLE `lien_he`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `thanhvien`
--
ALTER TABLE `thanhvien`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
