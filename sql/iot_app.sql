-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2025 at 08:23 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iot_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `commands`
--

CREATE TABLE `commands` (
  `id` bigint(20) NOT NULL,
  `device_id` varchar(100) NOT NULL,
  `command` varchar(100) NOT NULL,
  `payload` text DEFAULT NULL,
  `status` enum('pending','executed','cancelled') DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `executed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commands`
--

INSERT INTO `commands` (`id`, `device_id`, `command`, `payload`, `status`, `created_at`, `executed_at`) VALUES
(1, 'esp32-unit-001', 'relay_on', 'relay_on_ok', 'executed', '2025-10-25 15:58:11', '2025-10-25 15:58:17'),
(2, 'esp32-unit-001', 'relay_off', 'relay_off_ok', 'executed', '2025-10-25 15:59:06', '2025-10-25 15:59:14');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `device_id` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `last_seen` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `device_id`, `description`, `last_seen`) VALUES
(1, 'esp32-unit-001', NULL, '2025-10-25 15:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `sensor_data`
--

CREATE TABLE `sensor_data` (
  `id` bigint(20) NOT NULL,
  `device_id` varchar(100) NOT NULL,
  `sensor_type` varchar(50) DEFAULT 'unknown',
  `value` float DEFAULT NULL,
  `raw_value` text DEFAULT NULL,
  `recorded_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensor_data`
--

INSERT INTO `sensor_data` (`id`, `device_id`, `sensor_type`, `value`, `raw_value`, `recorded_at`) VALUES
(1, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=86.00', '2025-10-25 15:46:24'),
(2, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=85.00', '2025-10-25 15:47:24'),
(3, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=85.00', '2025-10-25 15:48:24'),
(4, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=85.00', '2025-10-25 15:49:24'),
(5, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=85.00', '2025-10-25 15:50:27'),
(6, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=84.00', '2025-10-25 15:51:24'),
(7, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=84.00', '2025-10-25 15:52:24'),
(8, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=84.00', '2025-10-25 15:53:24'),
(9, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=84.00', '2025-10-25 15:54:24'),
(10, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=84.00', '2025-10-25 15:55:24'),
(11, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=84.00', '2025-10-25 15:56:24'),
(12, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=84.00', '2025-10-25 15:57:24'),
(13, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=84.00', '2025-10-25 15:58:24'),
(14, 'esp32-unit-001', 'DHT11_temp', 32.3, 'h=83.00', '2025-10-25 15:59:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commands`
--
ALTER TABLE `commands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_id` (`device_id`);

--
-- Indexes for table `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_id` (`device_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commands`
--
ALTER TABLE `commands`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
