-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 26, 2026 at 02:25 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_uas_pbo_trpl1a_nadya_shafa_a_a`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_mahasiswa`
--

CREATE TABLE `tabel_mahasiswa` (
  `id_mahasiswa` int NOT NULL,
  `nama_mahasiswa` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `semester` int NOT NULL,
  `tarif_ukt_nominal` decimal(15,2) NOT NULL,
  `jenis_pembiayaan` enum('mandiri','bidikmisi','prestasi') NOT NULL,
  `golongan_ukt` varchar(10) DEFAULT NULL,
  `nama_wali` varchar(100) DEFAULT NULL,
  `nomor_kip_kuliah` varchar(50) DEFAULT NULL,
  `dana_saku_subsidi` decimal(15,2) DEFAULT NULL,
  `nama_instansi_beasiswa` varchar(100) DEFAULT NULL,
  `minimal_ipk_syarat` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_mahasiswa`
--

INSERT INTO `tabel_mahasiswa` (`id_mahasiswa`, `nama_mahasiswa`, `nim`, `semester`, `tarif_ukt_nominal`, `jenis_pembiayaan`, `golongan_ukt`, `nama_wali`, `nomor_kip_kuliah`, `dana_saku_subsidi`, `nama_instansi_beasiswa`, `minimal_ipk_syarat`) VALUES
(1, 'Andi Pratama', '202401001', 4, 7500000.00, 'mandiri', 'UKT V', 'Budi Santoso', NULL, NULL, NULL, NULL),
(2, 'Siti Aminah', '202401002', 2, 6000000.00, 'mandiri', 'UKT IV', 'Ahmad Yani', NULL, NULL, NULL, NULL),
(3, 'Bambang Pamungkas', '202401003', 6, 8500000.00, 'mandiri', 'UKT VI', 'Haryono', NULL, NULL, NULL, NULL),
(4, 'Dewi Sartika', '202401004', 1, 5000000.00, 'mandiri', 'UKT III', 'Supriyadi', NULL, NULL, NULL, NULL),
(5, 'Eko Susanto', '202401005', 4, 7500000.00, 'mandiri', 'UKT V', 'Slamet', NULL, NULL, NULL, NULL),
(6, 'Rina Melati', '202401006', 3, 6500000.00, 'mandiri', 'UKT IV', 'Santoso', NULL, NULL, NULL, NULL),
(7, 'Gatot Kaca', '202401007', 5, 8500000.00, 'mandiri', 'UKT VI', 'Purnomo', NULL, NULL, NULL, NULL),
(8, 'Lestariingsih', '202401008', 2, 6000000.00, 'mandiri', 'UKT IV', 'Wibowo', NULL, NULL, NULL, NULL),
(9, 'Joko Widodo', '202302001', 6, 2400000.00, 'bidikmisi', NULL, NULL, 'KIP2023001', 750000.00, NULL, NULL),
(10, 'Maria Ozawa', '202302002', 6, 2400000.00, 'bidikmisi', NULL, NULL, 'KIP2023002', 750000.00, NULL, NULL),
(11, 'Umar Bin Khatab', '202302003', 4, 2400000.00, 'bidikmisi', NULL, NULL, 'KIP2022045', 600000.00, NULL, NULL),
(12, 'Aisyah Putri', '202302004', 4, 2400000.00, 'bidikmisi', NULL, NULL, 'KIP2022046', 600000.00, NULL, NULL),
(13, 'Ali Imron', '202302005', 2, 2400000.00, 'bidikmisi', NULL, NULL, 'KIP2023101', 800000.00, NULL, NULL),
(14, 'Fatimah Az-Zahra', '202302006', 2, 2400000.00, 'bidikmisi', NULL, NULL, 'KIP2023102', 800000.00, NULL, NULL),
(15, 'Isaac Newton', '202203001', 8, 0.00, 'prestasi', NULL, NULL, NULL, NULL, 'Djarum Foundation', 3.50),
(16, 'Albert Einstein', '202203002', 8, 0.00, 'prestasi', NULL, NULL, NULL, NULL, 'Unggulan Kemdikbud', 3.75),
(17, 'Nikola Tesla', '202203003', 6, 0.00, 'prestasi', NULL, NULL, NULL, NULL, 'BUMN Scholarship', 3.60),
(18, 'Marie Curie', '202203004', 6, 0.00, 'prestasi', NULL, NULL, NULL, NULL, 'Pertamina Foundation', 3.80),
(19, 'Galileo Galilei', '202203005', 4, 0.00, 'prestasi', NULL, NULL, NULL, NULL, 'Tanoto Foundation', 3.65),
(20, 'Ada Lovelace', '202203006', 4, 0.00, 'prestasi', NULL, NULL, NULL, NULL, 'Google Generation', 3.70);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD KEY `idx_nim` (`nim`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_mahasiswa`
--
ALTER TABLE `tabel_mahasiswa`
  MODIFY `id_mahasiswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
