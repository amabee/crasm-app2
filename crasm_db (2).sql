-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 02:41 AM
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
-- Database: `crasm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `name_of_applicant` varchar(255) NOT NULL,
  `provincial_office` int(11) NOT NULL,
  `date_received_by_po_from_so_applicant` date NOT NULL,
  `type_of_application` varchar(255) NOT NULL,
  `date_of_payment` date NOT NULL,
  `or_number` varchar(255) NOT NULL,
  `date_transmitted_to_ro` date NOT NULL,
  `date_received_by_ro` date NOT NULL,
  `ro_screener` date NOT NULL,
  `date_forwarded_to_the_office_of_oic` date NOT NULL,
  `oic_crasd` date NOT NULL,
  `feedbacks` text NOT NULL,
  `date_forwarded_to_ord` date NOT NULL,
  `date_application_approved_by_rd` date NOT NULL,
  `for_issuance_of_crasm` date NOT NULL,
  `for_transmittal_of_crasm` date NOT NULL,
  `date_crasm_generated` date NOT NULL,
  `date_forwarded_back_to_the_office_of_oic_cao` date NOT NULL,
  `date_reviewed_and_initialed_by_oic_crasd` date NOT NULL,
  `date_forwarded_back_to_ord` date NOT NULL,
  `date_crasm_approved_by_rd` date NOT NULL,
  `date_transmitted_back_to_po` date NOT NULL,
  `date_received_by_po` date NOT NULL,
  `date_released_to_so` date NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provincial_office`
--

CREATE TABLE `provincial_office` (
  `province_id` int(100) NOT NULL,
  `provincial_office` int(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Super Admin'),
(2, 'Admin'),
(3, 'Regional Director'),
(4, 'OIC / CAO'),
(5, 'Collecting Officer'),
(6, 'Provincial Worker');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `pk` int(11) NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `front_title` varchar(255) NOT NULL,
  `favicon` varchar(500) NOT NULL,
  `app_logo` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`pk`, `app_name`, `title`, `front_title`, `favicon`, `app_logo`) VALUES
(1, 'CRASM - Monitoring', 'CRASM - Monitoring', 'CRASM - Monitoring', '../uploads/favicon_1738735468_am-i-the-only-one-who-finds-the-default-reddit-profile-v0-xpcq1qpd8mea1.webp', '../uploads/logo_1738735883_logo.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `first_name`, `middle_name`, `last_name`, `role_id`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'johndoe', '$2y$10$6DAXZkeWGVhsncmQzJDlHOpucxpChp7Y4JSar6EoMNPGTSmIFKLrq', 'johndoe@email.com', 'John', 'Sam', 'Smith', 1, 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg', 'active', '2025-02-03 07:06:45', '2025-02-05 05:53:54'),
(48, 'admin', '$2y$10$6DAXZkeWGVhsncmQzJDlHOpucxpChp7Y4JSar6EoMNPGTSmIFKLrq', 'psar10innovations@gmail.com', 'Shan Lee', 'Owen', 'Bonita', 2, 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg', 'active', '2025-02-05 05:28:36', '2025-02-05 08:30:47'),
(51, 'ybonita815', '$2y$10$J.XePg1DCniulpvtGOlhpu1y/vEihxMeFal1xz1Ah4CgVT4BoVV..', 'psarox10@gmail.com', 'Yves', 'Owen', 'Bonita', 4, 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg', 'active', '2025-02-05 08:40:25', '2025-02-05 08:41:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `provincial_office` (`provincial_office`);

--
-- Indexes for table `provincial_office`
--
ALTER TABLE `provincial_office`
  ADD PRIMARY KEY (`province_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`pk`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provincial_office`
--
ALTER TABLE `provincial_office`
  MODIFY `province_id` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `pk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
