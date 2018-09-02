-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 02, 2018 at 07:05 PM
-- Server version: 5.6.38
-- PHP Version: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `world`
--

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE `city` (
  `ID` varchar(100) NOT NULL,
  `CountryCode` varchar(10) NOT NULL,
  `Name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `city`
--

INSERT INTO `city` (`ID`, `CountryCode`, `Name`) VALUES
('Минск', 'BLR', 'Брест'),
('Москва', 'RUS', 'Нижний Новгород');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `Code` varchar(10) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Region` varchar(100) NOT NULL,
  `Population` bigint(11) NOT NULL,
  `Capital` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`Code`, `Name`, `Region`, `Population`, `Capital`) VALUES
('BLR', 'Беларусь', 'Европа', 9507000, 'Минск'),
('RUS', 'Россия', 'Европа', 144300000, 'Москва');

-- --------------------------------------------------------

--
-- Table structure for table `countrylanguage`
--

CREATE TABLE `countrylanguage` (
  `CountryCode` varchar(10) NOT NULL,
  `IsOfficial` varchar(10) NOT NULL,
  `Language` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countrylanguage`
--

INSERT INTO `countrylanguage` (`CountryCode`, `IsOfficial`, `Language`) VALUES
('BLR', 'T', 'Белорусский '),
('RUS', 'T', 'Русский');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD UNIQUE KEY `Code_UNIQUE` (`Code`);

--
-- Indexes for table `countrylanguage`
--
ALTER TABLE `countrylanguage`
  ADD UNIQUE KEY `CountryCode_UNIQUE` (`CountryCode`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
