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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL,
  `user_ime` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_prezime` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `id_korisnika` int(11) NOT NULL,
  `status` enum('0','1','2','3','4','5') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `user_ime`, `user_prezime`, `username`, `password`, `email`, `id_korisnika`, `status`) VALUES
(1, 'Petar', 'Cvetic', 'petar', '4c154902214180d01f60ffe435332ee2e511f503', 'petar.cvetic', 1, '3'),
(2, 'Root', 'Root', 'root', '85b0a45eadd5f833c1c75a32c1e8556ada76ef27', '', 1, '3'),
(3, 'Dunja', 'Urbanovic', 'dunja', 'a3674070fbde44bfa8077ea8123d75ff3ad41e56', 'urbanovic.dunja@gmail.com', 1, '3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
