-- phpMyAdmin SQL Dump
-- version 4.9.7deb1bionic1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 23, 2022 at 10:34 AM
-- Server version: 8.0.30-0ubuntu0.20.04.2
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tnt_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `magacini`
--

CREATE TABLE `magacini` (
  `id_magacinia` int NOT NULL,
  `id_korisnika` int NOT NULL,
  `naziv_magacinia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresa_magacinia` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_magacinia` enum('0','1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `magacini`
--
ALTER TABLE `magacini`
  ADD PRIMARY KEY (`id_magacinia`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `magacini`
--
ALTER TABLE `magacini`
  MODIFY `id_magacinia` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
