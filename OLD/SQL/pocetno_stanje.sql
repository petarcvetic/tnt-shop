-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 02, 2021 at 01:34 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

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

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `id_korisnika` int(11) NOT NULL,
  `status` enum('0','1','2','3','4','5') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `username`, `password`, `email`, `id_korisnika`, `status`) VALUES
(1, 'petar', '1c8d499982bdcaf14ad5d939e98f27d0110b45ee', 'petar.cvetic@gmail.com', 1, '3'),
(2, 'root', '1c8d499982bdcaf14ad5d939e98f27d0110b45ee', '', 1, '2');

-- --------------------------------------------------------

--
-- Table structure for table `fakture`
--

DROP TABLE IF EXISTS `fakture`;
CREATE TABLE IF NOT EXISTS `fakture` (
  `id_fakture` int(11) NOT NULL AUTO_INCREMENT,
  `broj_fakture` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `broj_izvoda` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `id_korisnika` int(11) NOT NULL,
  `kupac_id` int(11) NOT NULL,
  `datum_prometa` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `valuta` int(11) NOT NULL DEFAULT 0,
  `ukupno` decimal(12,2) NOT NULL DEFAULT 0.00,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `vreme_upisa` timestamp NOT NULL DEFAULT current_timestamp(),
  `uplata` decimal(12,2) NOT NULL DEFAULT 0.00,
  `izvod` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1','2','3','4','5') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_fakture`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `fakture`
--

INSERT INTO `fakture` (`id_fakture`, `broj_fakture`, `broj_izvoda`, `id_korisnika`, `kupac_id`, `datum_prometa`, `valuta`, `ukupno`, `username`, `vreme_upisa`, `uplata`, `izvod`, `status`) VALUES
(1, NULL, '1', 1, 1, '', 1, '1.00', 'petar', '2020-11-05 16:57:16', '0.00', '0', '1'),
(2, '1', NULL, 1, 1, '2018-02-24', 0, '1.00', 'root', '2021-02-24 21:44:02', '0.00', '0', '1'),
(121, '2', NULL, 1, 1, '2021-02-24', 0, '123.15', 'root', '2021-02-24 23:03:57', '0.00', '0', '1'),
(122, NULL, '2', 1, 1, '2021-02-24', 0, '0.00', 'root', '2021-02-24 23:05:25', '123.00', '0', '1'),
(123, NULL, '3', 1, 1, '2021-02-24', 0, '0.00', 'root', '2021-02-24 23:06:26', '123.23', '0', '1');

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

DROP TABLE IF EXISTS `korisnici`;
CREATE TABLE IF NOT EXISTS `korisnici` (
  `id_korisnika` int(11) NOT NULL AUTO_INCREMENT,
  `korisnik` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `adresa` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mesto` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `maticni_broj` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pib` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `sifra_delatnosti` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `telefon` varchar(26) COLLATE utf8_unicode_ci NOT NULL,
  `fax` varchar(26) COLLATE utf8_unicode_ci NOT NULL,
  `tekuci_racun` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `banka` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `template` enum('1','2','3','4') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `dodatak_broju` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('0','1','2','3','4','5') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_korisnika`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `korisnici`
--

INSERT INTO `korisnici` (`id_korisnika`, `korisnik`, `adresa`, `mesto`, `maticni_broj`, `pib`, `sifra_delatnosti`, `telefon`, `fax`, `tekuci_racun`, `banka`, `logo`, `template`, `dodatak_broju`, `status`) VALUES
(1, 'M MONT STROJ DOO', '	\nPartizanska 41', 'Ogar', '21487660', '111474130', '1', '+381 65 457 4687', '+381 65 457 4687', '330-0070100142714-94', 'Credit Agricole banka', 'images/korisnik1.jpg', '1', '2021', '1');

-- --------------------------------------------------------

--
-- Table structure for table `kupci`
--

DROP TABLE IF EXISTS `kupci`;
CREATE TABLE IF NOT EXISTS `kupci` (
  `id_kupca` int(11) NOT NULL AUTO_INCREMENT,
  `id_korisnika` int(11) NOT NULL,
  `naziv_kupca` varchar(140) COLLATE utf8_unicode_ci NOT NULL,
  `adresa_kupca` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `mesto_kupca` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pib_kupca` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `mat_broj` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `ziro_racun` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status_kupca` enum('0','1','2','3','4','5') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_kupca`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `kupci`
--

INSERT INTO `kupci` (`id_kupca`, `id_korisnika`, `naziv_kupca`, `adresa_kupca`, `mesto_kupca`, `pib_kupca`, `mat_broj`, `ziro_racun`, `email`, `status_kupca`) VALUES
(1, 1, 'POCETNO STANJE', '--', '--', '--', '--', '--', 'info@pocetno.com', '1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
