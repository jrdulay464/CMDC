-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2024 at 07:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `equipment`
--

-- --------------------------------------------------------

--
-- Table structure for table `equipment_details`
--

CREATE TABLE `equipment_details` (
  `id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `serials` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `date_rcvd` date DEFAULT NULL,
  `in_used` enum('yes','no') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment_details`
--

INSERT INTO `equipment_details` (`id`, `equipment_id`, `serials`, `location`, `date_rcvd`, `in_used`) VALUES
(2, 1, '32561', '', '2024-09-10', 'no'),
(3, 1, '256236', '', '2024-09-10', 'no'),
(4, 1, '', 'room 4A', '2024-09-10', 'no'),
(5, 2, '15213521', 'room 4A', '2024-09-10', 'no'),
(6, 1, '35123', 'room 4A', '2024-09-10', 'no'),
(7, 3, '213515', 'room 4A', '2024-09-10', 'no'),
(8, 3, '352234', 'room 4A', '2024-09-10', 'no'),
(9, 3, '35r2552', 'room 4B', '2024-09-10', 'no'),
(10, 4, '97542', 'Rizal Building 203', '2024-09-11', 'yes');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_info`
--

CREATE TABLE `equipment_info` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_quantity` int(255) NOT NULL,
  `alert_level` int(255) NOT NULL,
  `description` text NOT NULL,
  `available` int(255) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `borrowed` int(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment_info`
--

INSERT INTO `equipment_info` (`id`, `name`, `total_quantity`, `alert_level`, `description`, `available`, `picture`, `borrowed`, `price`) VALUES
(1, 'Grinder', 16, 3, 'This is used for grinding', 12, 'grinder.png', 0, 299.00),
(2, 'Stove', 21, 5, 'Used for cooking', 20, 'stove.jpg', 0, 511.00),
(3, 'Drill', 15, 5, 'Used for Drilling', 15, 'drill.jpg', 0, 799.00),
(4, 'Hair Dryer', 1, 3, 'Used for Drying Hair', 1, 'e5d6ddad84cf4c77943e7a55247a777c.png', 0, 699.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `equipment_details`
--
ALTER TABLE `equipment_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `serial` (`serials`),
  ADD KEY `idx_equipment_id` (`equipment_id`);

--
-- Indexes for table `equipment_info`
--
ALTER TABLE `equipment_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `equipment_details`
--
ALTER TABLE `equipment_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `equipment_info`
--
ALTER TABLE `equipment_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `equipment_details`
--
ALTER TABLE `equipment_details`
  ADD CONSTRAINT `equipment_details_ibfk_1` FOREIGN KEY (`equipment_id`) REFERENCES `equipment_info` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
