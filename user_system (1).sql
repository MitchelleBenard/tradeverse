-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2025 at 04:36 PM
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
-- Database: `user_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'admin@example.com', 'admin123'),
(2, 'mitchellemueni524@gmail.com', '123');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `id_number` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('deposit','withdrawal') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `id_number`, `amount`, `type`, `created_at`, `status`) VALUES
(1, '45678', 500.00, 'deposit', '2025-05-03 08:52:54', 'pending'),
(2, '45678', 500.00, '', '2025-05-03 08:55:16', 'pending'),
(3, '45678', 5000.00, 'deposit', '2025-05-03 08:55:36', 'pending'),
(4, '45678', 5.00, '', '2025-05-03 08:56:29', 'pending'),
(5, '45678', 700.00, 'deposit', '2025-05-03 08:56:59', 'pending'),
(6, '45678', 500.00, 'deposit', '2025-05-03 09:00:30', 'pending'),
(7, '45678', 500.00, 'deposit', '2025-05-03 09:11:56', 'pending'),
(10, '45678', 5000.00, 'withdrawal', '2025-05-03 09:32:27', 'completed'),
(11, '45678', 689.00, 'withdrawal', '2025-05-03 09:32:41', 'completed'),
(12, '45678', 1000.00, 'withdrawal', '2025-05-03 09:32:48', 'completed'),
(13, '45678', 7000.00, 'deposit', '2025-05-03 09:33:10', 'pending'),
(14, '45678', 5000.00, 'withdrawal', '2025-05-03 09:33:22', 'completed'),
(15, '45678', 300.00, 'deposit', '2025-05-03 10:08:39', 'pending'),
(16, '45678', 2000.00, 'withdrawal', '2025-05-03 10:08:53', 'completed'),
(17, '45678', 400.00, 'deposit', '2025-05-03 12:43:04', 'pending'),
(18, '45678', 50.00, 'deposit', '2025-05-03 12:43:21', 'pending'),
(19, '45678', 20.00, 'withdrawal', '2025-05-03 12:46:35', 'completed'),
(20, '45678', 50.00, 'withdrawal', '2025-05-03 12:48:28', 'approved'),
(21, '45678', 200.00, 'deposit', '2025-05-03 13:36:40', 'pending'),
(22, '45678', 100.00, 'withdrawal', '2025-05-03 13:36:58', 'declined');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `id_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `id_number`, `email`, `password`, `balance`) VALUES
(1, 'Mitchelle', 'Mulinge', '45678', 'mitchellemueni524@gmail.com', '$2y$10$Xg3Kysj.59TNqDI/pxKFvOMa8QXKgXxrI5tXV/3F5af.Lp/rp5g7G', 80.00),
(3, 'john', 'doh', '2345', 'johndoh@gmail.com', '$2y$10$1w55TtyRY5og0ataq.NzweZ/2HZkPoZxDgh5i4vIK2sE105enoERi', 0.00),
(4, 'dude', 'wana', '09876', 'dudewana254@gmail.com', '$2y$10$hS8TxVamVPa/d2a8xuQw0eGiT0Vc/8huTcNbZRBhhW2MdcKbCwK8u', 0.00),
(5, 'salome', 'wanja', '34567', 'salomewanja524@gmail.com', '$2y$10$beHisGcK1W7sjiOmTKZr.OBxJReH5RKX9kKBRpYYu0W5tN.susihe', 0.00),
(6, 'Hagen', 'wamacho', '87654', 'hagenwam234@gmail.com', '$2y$10$jfSr4jUCPp2Ns2EwIl0g7.OnlONxMJKebWpRwUdm5vHOl7ztxpQuy', 0.00),
(9, 'marcus', 'murimi', '38976', 'hezimarcus343@gmail.com', '$2y$10$Q1237xT/PEkMTSwmlDcVT.EZsl1vvMpNcaB3P7fzvcoCV4P8egyGe', 0.00),
(11, 'don', 'wendo', '1000', 'don254@gmail.com', '$2y$10$gs8SeCkulm3URHoPZ8d9k.yxXrmaIfla/ep28CWIZdSKcnbySy3Xm', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(11) NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','completed','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `id_number`, `amount`, `transaction_date`, `status`) VALUES
(1, '45678', 5.00, '2025-05-03 09:00:51', 'pending'),
(2, '45678', 1.00, '2025-05-03 09:01:06', 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_number` (`id_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_number` (`id_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_id_number` (`id_number`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_number` (`id_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_id_number` FOREIGN KEY (`id_number`) REFERENCES `users` (`id_number`) ON DELETE CASCADE;

--
-- Constraints for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD CONSTRAINT `withdrawals_ibfk_1` FOREIGN KEY (`id_number`) REFERENCES `users` (`id_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
