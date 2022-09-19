-- phpMyAdmin SQL Dump
-- version 4.9.7deb1bionic1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 17, 2021 at 07:57 PM
-- Server version: 8.0.23-0ubuntu0.20.04.1
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
-- Database: `kartica_firme`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `username` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_korisnika` int NOT NULL,
  `status` enum('0','1','2','3','4','5') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `email`, `id_korisnika`, `status`) VALUES
(1, 'petar', '1c8d499982bdcaf14ad5d939e98f27d0110b45ee', '', 1, '3'),
(2, 'tanja', '717c5f9efd36fc73e0f8a26352a665a06f0f3a6e', '', 1, '1'),
(20, 'root', '1c8d499982bdcaf14ad5d939e98f27d0110b45ee', 'petar.cvetic@gmail.com', 2, '2'),
(23, 'tomic', '1c8d499982bdcaf14ad5d939e98f27d0110b45ee', 'pc@executive-digital.com', 1, '2');

-- --------------------------------------------------------

--
-- Table structure for table `fakture`
--

CREATE TABLE `fakture` (
  `id_fakture` int NOT NULL,
  `broj_fakture` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_korisnika` int NOT NULL,
  `kupac_id` int NOT NULL,
  `datumPrometa` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `valuta` int NOT NULL,
  `ukupno` decimal(12,2) NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vreme` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uplata` decimal(12,2) NOT NULL,
  `izvod` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1','2','3','4','5') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fakture`
--

INSERT INTO `fakture` (`id_fakture`, `broj_fakture`, `id_korisnika`, `kupac_id`, `datumPrometa`, `valuta`, `ukupno`, `username`, `vreme`, `uplata`, `izvod`, `status`) VALUES
(4, '1', 1, 1, '', 1, '1.00', 'petar', '2020-11-05 16:57:16', '1.00', '0', '1'),
(63, '3', 1, 6, '23.11.2020', 30, '24.00', 'petar', '2020-11-23 12:46:38', '0.00', '0', '1'),
(64, '3', 1, 1, '23.11.2020', 30, '120.00', 'petar', '2020-11-23 16:53:15', '0.00', '0', '1'),
(65, '3', 1, 4, '23.11.2020', 30, '144.00', 'petar', '2020-11-23 16:55:56', '0.00', '0', '1'),
(66, '6', 1, 6, '23.11.2020', 30, '24.00', 'petar', '2020-11-23 17:02:08', '0.00', '0', '1'),
(67, '7', 1, 1, '23.11.2020', 30, '5232.00', 'petar', '2020-11-23 17:04:50', '0.00', '0', '1'),
(68, '8', 1, 7, '23.11.2020', 30, '120.00', 'petar', '2020-11-23 17:27:24', '0.00', '0', '1'),
(69, '9', 1, 6, '23.11.2020', 30, '24.00', 'petar', '2020-11-23 17:33:53', '0.00', '0', '1'),
(72, '10', 1, 0, '', 1, '0.00', 'p', '2020-11-24 16:15:09', '0.00', '0', '0'),
(73, '11', 1, 6, '24.11.2020', 30, '1680.00', 'petar', '2020-11-24 18:24:54', '0.00', '0', '1'),
(74, '12', 1, 5, '24.11.2020', 30, '288.00', 'petar', '2020-11-24 18:25:16', '0.00', '0', '1'),
(75, '13', 1, 4, '24.11.2020', 30, '4080.00', 'petar', '2020-11-24 18:25:35', '0.00', '0', '1'),
(76, '14', 1, 4, '24.11.2020', 30, '13560.00', 'petar', '2020-11-24 18:25:57', '0.00', '0', '1'),
(77, '15', 1, 7, '24.11.2020', 30, '751512.00', 'petar', '2020-11-24 18:26:20', '0.00', '0', '1'),
(78, '16', 1, 1, '24.11.2020', 30, '24000.00', 'petar', '2020-11-24 18:26:51', '0.00', '0', '1'),
(79, '17', 1, 1, '25.11.2020', 15, '12000.00', 'root', '2020-11-25 17:51:33', '0.00', '0', '1'),
(80, '18', 1, 1, '23.11.2020', 30, '2856.00', 'petar', '2020-11-26 17:35:35', '0.00', '0', '1'),
(81, '19', 1, 1, '23.11.2020', 30, '122856.00', 'petar', '2020-11-26 17:51:40', '110570.40', '0', '1'),
(82, '20', 1, 1, '23.11.2020', 30, '5160.00', 'petar', '2020-11-26 17:53:15', '0.00', '0', '1'),
(85, '23', 1, 5, '01.12.2020', 30, '12000.00', 'petar', '2020-12-01 18:53:54', '12000.00', '0', '1'),
(94, '1', 1, 6, '13.01.2021', 30, '24.00', 'petar', '2021-01-13 20:05:15', '0.00', '0', '1'),
(95, '2', 1, 4, '13.01.2021', 30, '240.00', 'petar', '2021-01-13 20:07:15', '240.00', '0', '1'),
(96, '3', 1, 6, '13.01.2021', 30, '120.00', 'petar', '2021-01-13 20:08:22', '0.00', '0', '1'),
(97, '4', 1, 6, '13.01.2021', 30, '48.00', 'petar', '2021-01-13 20:09:54', '0.00', '0', '1'),
(98, '1-1', 2, 9, '09.02.2021', 30, '7128000.00', 'root', '2021-02-09 13:15:32', '0.00', '0', '1'),
(99, '2', 2, 10, '09.02.2021', 30, '14400000.00', 'root', '2021-02-09 13:21:20', '0.00', '0', '1'),
(100, '3', 2, 10, '09.02.2021', 30, '30000000.00', 'root', '2021-02-09 13:30:28', '27000000.00', '0', '1'),
(101, '5', 1, 7, '09.02.2021', 30, '12000.00', 'petar', '2021-02-09 22:07:14', '0.00', '0', '1'),
(102, '4', 2, 13, '10.02.2021', 30, '13200000.00', 'root', '2021-02-10 11:26:25', '0.00', '0', '1'),
(103, '5', 2, 13, '10.02.2021', 30, '8976000.00', 'root', '2021-02-10 14:17:10', '0.00', '0', '1'),
(104, '6', 2, 13, '10.02.2021', 30, '15840000.00', 'root', '2021-02-10 14:17:36', '0.00', '0', '1'),
(105, '7', 2, 13, '10.02.2021', 30, '14400000.00', 'root', '2021-02-10 14:17:56', '0.00', '0', '1'),
(106, '8', 2, 10, '10.02.2021', 30, '13200000.00', 'root', '2021-02-10 14:18:30', '0.00', '0', '1'),
(107, '9', 2, 8, '10.02.2021', 30, '6600000.00', 'root', '2021-02-10 14:20:16', '0.00', '0', '1'),
(108, '10', 2, 9, '10.02.2021', 30, '24000000.00', 'root', '2021-02-10 14:20:53', '0.00', '0', '1'),
(109, '11', 2, 10, '10.02.2021', 30, '7392000.00', 'root', '2021-02-10 18:35:21', '0.00', '0', '1'),
(110, '12', 2, 16, '10.02.2021', 30, '13200000.00', 'root', '2021-02-10 18:58:02', '0.00', '0', '1'),
(111, '13', 2, 16, '10.02.2021', 30, '96000.00', 'root', '2021-02-10 19:37:59', '0.00', '0', '1'),
(112, '14', 2, 16, '10.02.2021', 30, '192000.00', 'root', '2021-02-10 19:38:39', '0.00', '0', '1'),
(113, '15', 2, 18, '11.02.2021', 30, '9600.00', 'root', '2021-02-11 18:14:36', '0.00', '0', '1');

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

CREATE TABLE `korisnici` (
  `id_korisnika` int NOT NULL,
  `korisnik` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `adresa` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mesto` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `maticni_broj` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `pib` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `sifra_delatnosti` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `telefon` varchar(26) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(26) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tekuci_racun` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `banka` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `template` enum('1','2','3','4') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `dodatak_broju` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1','2','3','4','5') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`id_korisnika`, `korisnik`, `adresa`, `mesto`, `maticni_broj`, `pib`, `sifra_delatnosti`, `telefon`, `fax`, `tekuci_racun`, `banka`, `logo`, `template`, `dodatak_broju`, `status`) VALUES
(1, 'TomicDOO', 'Kralja Petra 10', 'Pecinci', '21487660', '111474130', '?????', '+381 65 457 4687', '+381 65 457 4687', '330-0070100142714-94', 'Banka Intesa', 'images/korisnik2.jpg', '1', '2020', '1'),
(2, 'M MONT STROJ DOO', '	\nPartizanska 41', 'Ogar', '21487660', '111474130', '1', '+381 65 457 4687', '+381 65 457 4687', '330-0070100142714-94', 'Credit Agricole banka', 'images/korisnik1.jpg', '1', '2021', '1');

-- --------------------------------------------------------

--
-- Table structure for table `kupci`
--

CREATE TABLE `kupci` (
  `id_kupca` int NOT NULL,
  `id_korisnika` int NOT NULL,
  `naziv_kupca` varchar(140) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `adresa_kupca` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mesto_kupca` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `pib_kupca` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mat_broj` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ziro_racun` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `status_kupca` enum('0','1','2','3','4','5') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `kupci`
--

INSERT INTO `kupci` (`id_kupca`, `id_korisnika`, `naziv_kupca`, `adresa_kupca`, `mesto_kupca`, `pib_kupca`, `mat_broj`, `ziro_racun`, `email`, `status_kupca`) VALUES
(1, 1, 'Farma Pilica', 'Djordja Cvarkova bb', 'Pejicevi Salasi', '2222222', '3333333', '', '', '1'),
(2, 1, 'Farma Svinja', 'Brace Brkic bb', 'Vrbas', '111111', '22222', '', '', '0'),
(4, 1, 'Novi Kupac', 'Djordja Cvarkova bb', 'Pejicevi Salasi', '2222222', '3333333', '', '', '1'),
(5, 1, 'Neki Novi Kupac', 'Djordja Cvarkova bb', 'Pejicevi Salasi', '2222222', '3333333', '', '', '1'),
(6, 1, 'Najnoviji Kupac', 'lalalalal', 'Lalala', '34343434', '3434344', '', '', '1'),
(7, 1, 'Silos', 'lalalalalalala', 'alallalalalala', '5454545', '545454', '', '', '1'),
(8, 2, 'Petar Petrovic', 'Pere Perica 14', 'Novi Sad', 'Fizicko Lice', '', '', '', '1'),
(9, 2, 'Djura Djakovic', 'Dunavska 3', 'Novi Sad', 'Fizicko lice', '.', '', '', '1'),
(10, 2, 'Neka Firma DOO', 'Bulevar Oslobdjenja 30', 'Novi Sad ', '10101010', '111111111', '150-12398123-55', '', '1'),
(12, 2, 'test', 'test', 'test', '5555555', '222222', '256-1234567890-25', 'neki@tekst.test', '0'),
(13, 2, 'Agenija za nekretnine', 'Bulevar Oslobodjenja 100', 'Novi Sad', '333333', '555555', '234-1234567-89', '', '1'),
(14, 2, 'Najnoviji Kupac DOO', 'Mokranjceva BB', 'Beograd', '101010101', '9292929292', ' ', ' ', '1'),
(15, 2, 'Sljunak DOO', 'Djure Djakovica 8', 'Gornji Milanovac', '10101010', '2222222', ' ', ' ', '1'),
(16, 2, 'Konstrukt DOO', 'Narodnog Fronta 22', 'Novi Sad', '123432', '876543321', ' ', ' ', '1'),
(17, 2, 'Dijagonala DOO', 'Primorska 10', 'Novi Sad', '909090', '82828282', '350-220808212-565', '', '1'),
(18, 2, 'Galens DOO', 'Kraljevica Marka 10', 'Novi Sad', '10101010', '2222222', '150-12398123-55', 'info@galens.com', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `fakture`
--
ALTER TABLE `fakture`
  ADD PRIMARY KEY (`id_fakture`);

--
-- Indexes for table `korisnici`
--
ALTER TABLE `korisnici`
  ADD PRIMARY KEY (`id_korisnika`);

--
-- Indexes for table `kupci`
--
ALTER TABLE `kupci`
  ADD PRIMARY KEY (`id_kupca`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `fakture`
--
ALTER TABLE `fakture`
  MODIFY `id_fakture` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `korisnici`
--
ALTER TABLE `korisnici`
  MODIFY `id_korisnika` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kupci`
--
ALTER TABLE `kupci`
  MODIFY `id_kupca` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;