-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: s579.loopia.se
-- Generation Time: Oct 13, 2022 at 08:55 AM
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
-- Table structure for table `proizvodi`
--

CREATE TABLE `proizvodi` (
  `id_proizvoda` int(11) NOT NULL,
  `id_korisnika` int(11) NOT NULL,
  `naziv_proizvoda` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cena_proizvoda` decimal(10,2) NOT NULL,
  `tezina_proizvoda` decimal(6,2) NOT NULL,
  `cena_saradnika` decimal(8,2) NOT NULL,
  `id_magacina` int(11) NOT NULL,
  `kolicina_u_magacinu` int(11) NOT NULL,
  `sifra_proizvoda` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `broj_paketa` int(11) NOT NULL DEFAULT 1,
  `status` enum('0','1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `proizvodi`
--

INSERT INTO `proizvodi` (`id_proizvoda`, `id_korisnika`, `naziv_proizvoda`, `cena_proizvoda`, `tezina_proizvoda`, `cena_saradnika`, `id_magacina`, `kolicina_u_magacinu`, `sifra_proizvoda`, `broj_paketa`, `status`) VALUES
(1, 1, 'Kućica velika zelena', '8500.00', '10.00', '600.00', 2, 26, 'ZK', 1, '1'),
(2, 1, 'Kućica velika roza', '8500.00', '10.00', '600.00', 2, 29, 'RK', 1, '1'),
(3, 1, 'Kućica velika braon', '8500.00', '10.00', '600.00', 2, 6, 'BRAON', 1, '1'),
(4, 1, 'Kućica velika magenda', '8500.00', '10.00', '600.00', 2, 18, 'MAGENDA', 1, '1'),
(5, 1, 'Kućica velika plava', '8500.00', '10.00', '600.00', 2, 11, 'PLK', 1, '1'),
(6, 1, 'Tobogan 2m crveni', '7500.00', '10.00', '600.00', 2, 58, 'VT', 1, '1'),
(7, 1, 'Tobogan 2m zeleni', '7500.00', '10.00', '600.00', 2, 19, 'ZT', 1, '1'),
(8, 1, 'Tobogan 2m plavi', '7500.00', '10.00', '600.00', 2, 8, 'PLT', 1, '1'),
(9, 1, 'Tobogan 2m rozi', '7500.00', '10.00', '600.00', 2, 43, 'RT', 1, '1'),
(10, 1, 'Kućica mala zelena', '6700.00', '5.00', '600.00', 2, 2, 'MZK', 1, '1'),
(11, 1, 'Kućica mala roze', '6700.00', '5.00', '600.00', 2, 3, 'MRK', 1, '1'),
(12, 1, 'Kućica mala plava', '6700.00', '5.00', '600.00', 2, 2, 'MPLK', 1, '1'),
(13, 1, 'Tobogan mali crveni', '3700.00', '2.00', '300.00', 2, 4, 'MCT', 1, '1'),
(14, 1, 'Tobogan mali zeleni', '3700.00', '2.00', '300.00', 2, 6, 'MZT', 1, '1'),
(15, 1, 'Tobogan mali rozi', '3700.00', '2.00', '300.00', 2, 4, 'MRT', 1, '1'),
(16, 1, 'Tobogan mali plavi', '3700.00', '2.00', '300.00', 2, 6, 'MPLT', 1, '1'),
(17, 1, 'Tobogan mali žuti', '3700.00', '2.00', '300.00', 2, 6, 'MŽT', 1, '1'),
(18, 1, 'Speed tobogan crveni', '5400.00', '5.00', '600.00', 2, 4, 'SPEEDC', 1, '1'),
(19, 1, 'Speed tobogan plavi', '5400.00', '5.00', '600.00', 2, 2, 'SPEEDP', 1, '1'),
(20, 1, 'Straus Kompresor 50L', '16000.00', '20.00', '1200.00', 2, 11, 'KOM.50', 1, '1'),
(21, 1, 'Speed tobogan plavi', '5400.00', '5.00', '600.00', 1, 4, 'SPEEDP', 1, '1'),
(22, 1, 'Jaje crno', '18500.00', '10.00', '1200.00', 1, 202, 'JAJECRNO', 3, '1'),
(23, 1, 'Jaje belo', '18500.00', '10.00', '1200.00', 1, 111, 'JAJEBELO', 3, '1'),
(24, 1, 'Gaming stolica crvena', '14500.00', '10.00', '1200.00', 1, 5, 'G264', 1, '1'),
(25, 1, 'Gaming stolica plava', '14500.00', '10.00', '1200.00', 1, 0, 'G264', 1, '1'),
(26, 1, 'Tricikl plavi', '4500.00', '5.00', '800.00', 1, 7, 'G226', 1, '1'),
(27, 1, 'Tricikl beli', '4500.00', '5.00', '800.00', 1, 1, 'G226', 1, '1'),
(28, 1, 'Kuhinja 65 elemenata roza', '9600.00', '5.00', '1000.00', 1, 10, 'G318', 1, '1'),
(29, 1, 'Kuhinja 65 elemenata siva', '9600.00', '5.00', '1000.00', 1, 26, 'G318', 1, '1'),
(30, 1, 'Ograda za pse i mačke', '2400.00', '0.50', '500.00', 1, 27, 'G307', 1, '1'),
(31, 1, 'Kuhinja 36 elemenata roza', '4000.00', '2.00', '350.00', 1, 10, 'G301', 1, '1'),
(32, 1, 'Kuhinja 36 elemenata siva', '4000.00', '2.00', '350.00', 1, 26, 'G301', 1, '1'),
(33, 1, 'Balans bicikla plava', '4500.00', '2.00', '800.00', 1, 6, 'G202', 1, '1'),
(34, 1, 'Balans bicikla roza', '4500.00', '2.00', '800.00', 1, 6, 'G203', 1, '1'),
(35, 1, 'Auto na akumulator G21 rozi', '14000.00', '10.00', '1200.00', 1, 3, 'G21', 1, '1'),
(36, 1, 'Auto na akumulator G21 crveni', '14000.00', '10.00', '1200.00', 1, 0, 'G21', 1, '1'),
(37, 1, 'Auto na akumulator G21 plavi', '14000.00', '10.00', '1200.00', 1, 0, 'G21', 1, '1'),
(38, 1, 'Bager na akumulator G11 crveni', '9500.00', '5.00', '1000.00', 1, 8, 'G11', 1, '1'),
(39, 1, 'Auto Mercedes G382 crveni', '18000.00', '10.00', '1200.00', 1, 3, 'G382', 1, '1'),
(40, 1, 'Auto Mercedes G382 zeleni', '18000.00', '10.00', '1200.00', 1, 14, 'G382', 1, '1'),
(41, 1, 'Auto na akumulator G248 crveni', '7000.00', '5.00', '1000.00', 1, 2, 'G248', 1, '1'),
(42, 1, 'Auto na akumulator G248 beli', '7000.00', '5.00', '1000.00', 1, 1, 'G248', 1, '1'),
(43, 1, 'Auto na akumulator G248 plavi', '7000.00', '5.00', '1000.00', 1, 3, 'G248', 1, '1'),
(44, 1, 'Auto na akumulator džip G10 crveni', '31000.00', '15.00', '1800.00', 1, 0, 'G10', 1, '1'),
(45, 1, 'BMW Serija 7 G24 crveni', '20000.00', '10.00', '1500.00', 1, 6, 'G24', 1, '1'),
(46, 1, 'BMW Serija 7 G24 plavi', '20000.00', '10.00', '1500.00', 1, 2, 'G24', 1, '1'),
(47, 1, 'BMW Serija 7 G24 beli', '20000.00', '10.00', '1500.00', 1, 4, 'G24', 1, '1'),
(48, 1, 'Audi TT G336 crveni', '26000.00', '15.00', '1500.00', 1, 3, 'G336', 1, '1'),
(49, 1, 'Audi TT G336 crni', '26000.00', '15.00', '1500.00', 1, 3, 'G336', 1, '1'),
(50, 1, 'Auto BMW G23 crveni', '14000.00', '10.00', '1200.00', 2, 7, 'G23', 1, '1'),
(51, 1, 'Auto BMW G23 beli', '14000.00', '10.00', '1200.00', 1, 9, 'G23', 1, '1'),
(52, 1, 'Vojni džip G18 crveni', '30000.00', '20.00', '1800.00', 1, 1, 'G18', 1, '1'),
(53, 1, 'Quad motor G333', '14000.00', '10.00', '1200.00', 1, 3, 'G333', 1, '1'),
(54, 1, 'Mercedes CLA G16', '33000.00', '20.00', '18000.00', 1, 3, 'G16', 1, '1'),
(55, 1, 'Gaming stolica roza/bela', '15000.00', '10.00', '1200.00', 1, 7, 'G390', 1, '1'),
(56, 1, 'Gaming stolica roza/crna', '15000.00', '10.00', '1200.00', 1, 8, 'G390', 1, '1'),
(57, 1, 'Toaletni stočić G259', '15500.00', '15.00', '1200.00', 1, 21, 'G259', 2, '1'),
(58, 1, 'Toaletni stočić G330', '15500.00', '15.00', '1200.00', 1, 27, 'G330', 2, '1'),
(59, 1, 'Kuhinja 42 elementa roza', '4300.00', '2.00', '400.00', 1, 7, 'G298', 1, '1'),
(60, 1, 'Kuhinja 42 elementa siva', '4300.00', '2.00', '400.00', 1, 11, 'G298', 1, '1'),
(61, 1, 'Kuhinja 29 elemenata roza', '3700.00', '1.00', '300.00', 1, 12, 'G308', 1, '1'),
(62, 1, 'Kuhinja 29 elemenata siva', '3700.00', '1.00', '300.00', 1, 11, 'G308', 1, '1'),
(63, 1, 'Kuhinja 38 elemenata roza', '4100.00', '2.00', '350.00', 1, 6, 'G300', 1, '1'),
(64, 1, 'Kuhinja 38 elemenata siva', '4100.00', '2.00', '350.00', 1, 6, 'G300', 1, '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `proizvodi`
--
ALTER TABLE `proizvodi`
  ADD PRIMARY KEY (`id_proizvoda`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `proizvodi`
--
ALTER TABLE `proizvodi`
  MODIFY `id_proizvoda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
