-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: s579.loopia.se
-- Generation Time: Oct 13, 2022 at 08:57 AM
-- Server version: 10.3.36-MariaDB-log
-- PHP Version: 8.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mojeposlovanje_online_db_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `troskovi`
--

CREATE TABLE `troskovi` (
  `id_troska` int(11) NOT NULL,
  `id_korisnika` int(5) NOT NULL,
  `datum_troska` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `namena_troska` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iznos_troska` decimal(10,2) NOT NULL,
  `user` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('0','1','2','3','4') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `troskovi`
--

INSERT INTO `troskovi` (`id_troska`, `id_korisnika`, `datum_troska`, `namena_troska`, `iznos_troska`, `user`, `time`, `status`) VALUES
(1, 1, '2022-10-11', 'Lom', '1000.00', 'root', '2022-10-11 08:46:05', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `troskovi`
--
ALTER TABLE `troskovi`
  ADD PRIMARY KEY (`id_troska`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `troskovi`
--
ALTER TABLE `troskovi`
  MODIFY `id_troska` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
