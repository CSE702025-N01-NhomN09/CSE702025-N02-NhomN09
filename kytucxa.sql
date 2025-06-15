-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 09:33 AM
-- Server version: 8.0.42
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kytucxa`
--

-- --------------------------------------------------------

--
-- Table structure for table `danhsacho`
--

CREATE TABLE `danhsacho` (
  `Maphong` int DEFAULT NULL,
  `TenSV` varchar(255) DEFAULT NULL,
  `MSV` int DEFAULT NULL,
  `Khoa` varchar(255) DEFAULT NULL,
  `Gioitinh` varchar(4) DEFAULT NULL,
  `Sodienthoai` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `danhsacho`
--

INSERT INTO `danhsacho` (`Maphong`, `TenSV`, `MSV`, `Khoa`, `Gioitinh`, `Sodienthoai`) VALUES
(101, 'Trịnh Tùng Dương', 23010832, 'CNTT', 'Nam', '0932981305'),
(109, 'Nguyễn Xuân Dương', 23010772, 'CNTT', 'Nam', '0932836405');

-- --------------------------------------------------------

--
-- Table structure for table `dichvu`
--

CREATE TABLE `dichvu` (
  `MSV` int DEFAULT NULL,
  `Tendichvu` varchar(255) DEFAULT NULL,
  `Chiphi` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giaodich`
--

CREATE TABLE `giaodich` (
  `Magiaodich` int NOT NULL,
  `Ngaygiaodich` date DEFAULT NULL,
  `Trangthai` varchar(255) DEFAULT NULL,
  `Sotien` int DEFAULT NULL,
  `MSV` int DEFAULT NULL,
  `Thang` int DEFAULT NULL,
  `Nam` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lichsuthanhtoan`
--

CREATE TABLE `lichsuthanhtoan` (
  `ID` int NOT NULL,
  `MSV` int DEFAULT NULL,
  `Tendichvu` varchar(255) DEFAULT NULL,
  `Chiphi` int DEFAULT NULL,
  `Thang` int DEFAULT NULL,
  `Nam` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phananh`
--

CREATE TABLE `phananh` (
  `Noidung` varchar(255) DEFAULT NULL,
  `Ngaygui` date DEFAULT NULL,
  `Trangthai` varchar(255) DEFAULT NULL,
  `Maphananh` varchar(20) NOT NULL,
  `MSV` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phong`
--

CREATE TABLE `phong` (
  `Loaiphong` varchar(255) DEFAULT NULL,
  `Maphong` int NOT NULL,
  `Songuoio` int DEFAULT NULL,
  `Succhua` int DEFAULT NULL,
  `Trangthai` varchar(255) DEFAULT NULL,
  `Gioitinh` varchar(4) DEFAULT NULL,
  `Giathue` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `phong`
--

INSERT INTO `phong` (`Loaiphong`, `Maphong`, `Songuoio`, `Succhua`, `Trangthai`, `Gioitinh`, `Giathue`) VALUES
('Phòng Nam', 101, 1, 4, 'Hoạt Động Tốt', 'Nam', 1000000),
('Phòng Nam', 102, 0, 4, 'Hoạt Động Tốt', 'Nam', 1000000),
('Phòng Nữ', 103, 0, 4, 'Hoạt Động Tốt', 'Nữ', 1000000),
('Phòng Nữ', 104, 0, 4, 'Hoạt Động Tốt', 'Nữ', 1000000),
('Phòng Nam', 105, 0, 6, 'Hoạt Động Tốt', 'Nam', 800000),
('Phòng Nữ', 106, 0, 6, 'Hoạt Động Tốt', 'Nữ', 800000),
('Phòng Nam', 107, 0, 6, 'Hoạt Động Tốt', 'Nam', 600000),
('Phòng Nữ', 108, 0, 6, 'Hoạt Động Tốt', 'Nữ', 600000),
('Phòng Nam', 109, 1, 8, 'Hoạt Động Tốt', 'Nam', 400000),
('Phòng Nữ', 110, 0, 8, 'Hoạt Động Tốt', 'Nữ', 400000);

-- --------------------------------------------------------

--
-- Table structure for table `sinhvien`
--

CREATE TABLE `sinhvien` (
  `Hoten` varchar(20) DEFAULT NULL,
  `MSV` int NOT NULL,
  `Sodienthoai` varchar(10) DEFAULT NULL,
  `Khoa` varchar(20) DEFAULT NULL,
  `Lop` varchar(10) DEFAULT NULL,
  `Gioitinh` varchar(4) DEFAULT NULL,
  `Quequan` varchar(20) DEFAULT NULL,
  `Ngaysinh` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sinhvien`
--

INSERT INTO `sinhvien` (`Hoten`, `MSV`, `Sodienthoai`, `Khoa`, `Lop`, `Gioitinh`, `Quequan`, `Ngaysinh`) VALUES
('Nguyễn Xuân Dương', 23010772, '0932836405', 'CNTT', 'CNTT_9', 'Nam', 'Hà Nội', '2005-12-25'),
('Trịnh Tùng Dương', 23010832, '0932981305', 'CNTT', 'CNTT_9', 'Nam', 'Hà Nội', '2005-02-15'),
('Vũ Hồng Phúc', 23011567, '0932297405', 'CNTT', 'CNTT_9', 'Nam', 'Hà Nội', '2005-06-08'),
('Nguyễn Ngọc Bích', 23012203, '0981397405', 'CNTT', 'CNTT_9', 'Nữ', 'Hà Nội', '2005-09-02');

-- --------------------------------------------------------

--
-- Table structure for table `taikhoan`
--

CREATE TABLE `taikhoan` (
  `Tentaikhoan` varchar(255) NOT NULL,
  `Matkhau` varchar(20) DEFAULT NULL,
  `Loaitaikhoan` varchar(10) DEFAULT NULL,
  `MSV` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `taikhoan`
--

INSERT INTO `taikhoan` (`Tentaikhoan`, `Matkhau`, `Loaitaikhoan`, `MSV`) VALUES
('23010772@st.phenikaa-uni.edu.vn', 'Baibai', 'Sinhvien', 23010772),
('23010832@st.phenikaa-uni.edu.vn', 'Baibai', 'Sinhvien', 23010832),
('23011567@st.phenikaa-uni.edu.vn', 'Baibai', 'Sinhvien', 23011567),
('23012203@st.phenikaa-uni.edu.vn', 'Baibai', 'Sinhvien', 23012203);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `danhsacho`
--
ALTER TABLE `danhsacho`
  ADD KEY `fk_danhsacho_sinhvien` (`MSV`),
  ADD KEY `fk_danhsacho_phong` (`Maphong`);

--
-- Indexes for table `dichvu`
--
ALTER TABLE `dichvu`
  ADD KEY `fk_dichvu_sinhvien` (`MSV`);

--
-- Indexes for table `giaodich`
--
ALTER TABLE `giaodich`
  ADD PRIMARY KEY (`Magiaodich`),
  ADD KEY `fk_giaodich_sinhvien` (`MSV`);

--
-- Indexes for table `lichsuthanhtoan`
--
ALTER TABLE `lichsuthanhtoan`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_lichsu_sinhvien` (`MSV`);

--
-- Indexes for table `phananh`
--
ALTER TABLE `phananh`
  ADD PRIMARY KEY (`Maphananh`),
  ADD KEY `fk_phananh_sinhvien` (`MSV`);

--
-- Indexes for table `phong`
--
ALTER TABLE `phong`
  ADD PRIMARY KEY (`Maphong`);

--
-- Indexes for table `sinhvien`
--
ALTER TABLE `sinhvien`
  ADD PRIMARY KEY (`MSV`);

--
-- Indexes for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`Tentaikhoan`),
  ADD KEY `fk_taikhoan_sinhvien` (`MSV`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `giaodich`
--
ALTER TABLE `giaodich`
  MODIFY `Magiaodich` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lichsuthanhtoan`
--
ALTER TABLE `lichsuthanhtoan`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `danhsacho`
--
ALTER TABLE `danhsacho`
  ADD CONSTRAINT `fk_danhsacho_phong` FOREIGN KEY (`Maphong`) REFERENCES `phong` (`Maphong`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_danhsacho_sinhvien` FOREIGN KEY (`MSV`) REFERENCES `sinhvien` (`MSV`) ON DELETE CASCADE;

--
-- Constraints for table `dichvu`
--
ALTER TABLE `dichvu`
  ADD CONSTRAINT `fk_dichvu_sinhvien` FOREIGN KEY (`MSV`) REFERENCES `sinhvien` (`MSV`) ON DELETE CASCADE;

--
-- Constraints for table `giaodich`
--
ALTER TABLE `giaodich`
  ADD CONSTRAINT `fk_giaodich_msv` FOREIGN KEY (`MSV`) REFERENCES `sinhvien` (`MSV`),
  ADD CONSTRAINT `fk_giaodich_sinhvien` FOREIGN KEY (`MSV`) REFERENCES `sinhvien` (`MSV`) ON DELETE CASCADE;

--
-- Constraints for table `lichsuthanhtoan`
--
ALTER TABLE `lichsuthanhtoan`
  ADD CONSTRAINT `fk_lichsu_sinhvien` FOREIGN KEY (`MSV`) REFERENCES `sinhvien` (`MSV`) ON DELETE CASCADE,
  ADD CONSTRAINT `lichsuthanhtoan_ibfk_1` FOREIGN KEY (`MSV`) REFERENCES `sinhvien` (`MSV`);

--
-- Constraints for table `phananh`
--
ALTER TABLE `phananh`
  ADD CONSTRAINT `fk_phananh_sinhvien` FOREIGN KEY (`MSV`) REFERENCES `sinhvien` (`MSV`) ON DELETE CASCADE;

--
-- Constraints for table `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `fk_taikhoan_msv` FOREIGN KEY (`MSV`) REFERENCES `sinhvien` (`MSV`),
  ADD CONSTRAINT `fk_taikhoan_sinhvien` FOREIGN KEY (`MSV`) REFERENCES `sinhvien` (`MSV`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
