-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: s579.loopia.se
-- Generation Time: Oct 13, 2022 at 08:56 AM
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
-- Table structure for table `saradnici`
--

CREATE TABLE `saradnici` (
  `id_saradnika` int(11) NOT NULL,
  `id_korisnika` int(11) NOT NULL,
  `ime_saradnika` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prezime_saradnika` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('0','1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `saradnici`
--

INSERT INTO `saradnici` (`id_saradnika`, `id_korisnika`, `ime_saradnika`, `prezime_saradnika`, `status`) VALUES
(1, 1, 'TNT', 'SHOP', '1'),
(2, 1, 'Maja', 'Milodanović', '1'),
(3, 1, 'Bibi', 'Molnar', '1'),
(4, 1, 'Elvira', 'Kuktin', '1'),
(5, 1, 'Nadica', 'Milovanović', '1'),
(6, 1, 'Danijela', 'Dimitrijević', '1'),
(7, 1, 'Manuela', 'Zdravković', '1'),
(8, 1, 'Manuela', '1', '1'),
(9, 1, 'Damir', 'Milodanović', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `saradnici`
--
ALTER TABLE `saradnici`
  ADD PRIMARY KEY (`id_saradnika`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `saradnici`
--
ALTER TABLE `saradnici`
  MODIFY `id_saradnika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
