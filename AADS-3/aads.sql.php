-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 16, 2018 at 02:51 PM
-- Server version: 5.7.23-log
-- PHP Version: 7.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aads`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id_category` int(11) UNSIGNED NOT NULL,
  `category_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id_category`, `category_name`) VALUES
(1, 'Каталог'),
(2, 'Одежда'),
(3, 'Продукты'),
(4, 'Верхняя одежда'),
(5, 'Молочные продуткы');

-- --------------------------------------------------------

--
-- Table structure for table `category_links`
--

CREATE TABLE `category_links` (
  `parent_id` int(11) UNSIGNED NOT NULL,
  `child_id` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `nearest_parent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category_links`
--

INSERT INTO `category_links` (`parent_id`, `child_id`, `level`, `nearest_parent`) VALUES
(1, 1, 0, 0),
(1, 2, 1, 1),
(1, 3, 1, 1),
(1, 4, 2, 2),
(1, 5, 2, 3),
(2, 2, 1, 1),
(2, 4, 2, 2),
(3, 3, 1, 1),
(3, 5, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `nested`
--

CREATE TABLE `nested` (
  `id` int(100) NOT NULL,
  `l` int(100) NOT NULL,
  `r` int(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `depth` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nested`
--

INSERT INTO `nested` (`id`, `l`, `r`, `value`, `depth`) VALUES
(0, 0, 13, '1', 0),
(1, 1, 8, '1.1', 1),
(2, 9, 12, '1.2', 1),
(3, 2, 5, '1.1.1', 2),
(4, 6, 7, '1.1.2', 2),
(5, 3, 4, '1.1.1.1', 3),
(6, 10, 11, '1.2.1', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id_category`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id_category` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
