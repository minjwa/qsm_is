-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 24, 2023 at 04:30 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qsm_is`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `description` int(11) NOT NULL,
  `date_booking` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `job_id`, `description`, `date_booking`, `status`) VALUES
(1, 13, 15, 3, '2023-07-22', 1),
(7, 14, 19, 3, '2023-07-19', 2),
(32, 13, 19, 3, '2023-07-27', 0),
(33, 13, 15, 2, '2005-12-26', 1),
(34, 13, 15, 2, '2005-12-26', 0),
(35, 13, 15, 2, '0000-00-00', 0),
(36, 13, 15, 2, '2023-07-17', 0),
(37, 13, 15, 2, '2023-08-01', 0),
(38, 13, 15, 3, '2023-07-12', 1),
(42, 13, 27, 2, '2023-08-31', 0),
(43, 13, 27, 2, '2023-08-29', 0),
(44, 13, 19, 3, '2023-06-19', 0),
(45, 15, 29, 5, '2023-07-25', 1),
(46, 13, 21, 4, '2023-07-04', 0),
(47, 16, 30, 1, '2023-07-25', 1);

-- --------------------------------------------------------

--
-- Table structure for table `description`
--

CREATE TABLE `description` (
  `id` int(11) NOT NULL,
  `name` varchar(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `description`
--

INSERT INTO `description` (`id`, `name`) VALUES
(1, 'Leave Management'),
(2, 'Financial Management'),
(3, 'Overtime Management'),
(4, 'User Management'),
(5, 'Training Management'),
(6, 'Claim Management'),
(7, 'Attendance Management');

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

CREATE TABLE `document` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `description` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `name`, `location_id`, `user_id`, `description`) VALUES
(15, 'Company 1', 2, NULL, 3),
(19, 'hr2eazy', 1, NULL, 3),
(21, 'Iscistech', 3, NULL, 4),
(27, 'Company 1', 2, NULL, 2),
(29, 'Petronas', 2, NULL, 5),
(30, 'MSNS', 1, NULL, 1),
(32, 'Dr. Ana', 2, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`) VALUES
(1, 'Shah Alam'),
(2, 'Kuala Lumpur'),
(3, 'Bangsar'),
(4, 'Selangor'),
(5, 'Ipoh');

-- --------------------------------------------------------

--
-- Table structure for table `logbooks`
--

CREATE TABLE `logbooks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `file_dir` longtext NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `mark` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `code`, `name`) VALUES
(1, 'student', 'Student'),
(3, 'supervisor', 'Supervisor'),
(4, 'company', 'Company'),
(5, 'admin', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` longtext NOT NULL,
  `role_id` int(11) NOT NULL,
  `resume` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role_id`, `resume`, `created_at`, `updated_at`) VALUES
(5, 'admin', 'admin@test.com', '$2y$10$S/M17sC6Gyh7QrNJP8zJ2.3kZP.Mg8h3msJXPpcA9XMnhwxzu4IjC', 5, NULL, '2022-06-29 14:20:16', '2023-07-11 14:17:53'),
(12, 'iscistech', 'iscistech@gmail.com', '$2y$10$uJ7f5x8mrpT1lphHl.nvNuzUaiX/EEafHR7Wus644vJLzqxSk8.g6', 4, NULL, '2023-07-05 13:30:40', '2023-07-11 14:17:53'),
(13, 'mina', 'mina@uitm.com', '$2y$10$h9sytq3BRB7OKQqvs7KgVuH.hF1ql4Ly6Q8SnY71Jm5b38h.wWTke', 1, NULL, '2023-07-05 14:39:20', '2023-07-11 14:17:53'),
(14, 'HR', 'hr2eazy@gmail.com', '$2y$10$eGN7NvBV3Fx7TwJFsMmdkOkiF.mJamjgSnoxusy92eXK0a4YxPsgC', 1, NULL, '2023-07-11 14:31:41', '2023-07-11 14:31:41'),
(15, 'danial', 'danial.ibs@gmail.com', '$2y$10$7.M9sSofeJDaBRxkidU1reaq4787avx1UYHf2ZQlEmP1Rkbn1AdeW', 1, NULL, '2023-07-11 15:26:22', '2023-07-21 14:32:15'),
(16, 'farah', 'farah.msns@gmail.com', '$2y$10$o967gHtNEiUZRajn7jp2LuiWQL.cxWHvYpTBLXJaU43gSg84yMYeG', 1, NULL, '2023-07-23 12:29:18', '2023-07-23 12:29:18'),
(17, 'lisa', 'lisa@gmail.com', '$2y$10$2oERdePtZ0umXI.0G1ZvbeGourJLPly9Z/NGnyvBiAU/Q9S8ARItu', 1, NULL, '2023-07-23 15:33:36', '2023-07-23 15:33:36'),
(18, 'afiqah', 'afiqah.cimb@gmail.com', '$2y$10$ylr0am0RdldBn9q72jKu0OvjWero8JlAYxUMSv609OywoQ7OxP1E2', 1, NULL, '2023-07-24 14:16:43', '2023-07-24 14:16:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `applications_ibfk_3` (`description`);

--
-- Indexes for table `description`
--
ALTER TABLE `description`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_foreign_key_user_document` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location_id` (`location_id`),
  ADD KEY `jobs_user_fk` (`user_id`),
  ADD KEY `jobs_description_fk` (`description`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logbooks`
--
ALTER TABLE `logbooks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logbooks_ibfk_1` (`user_id`),
  ADD KEY `supervisor_id` (`supervisor_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logbooks`
--
ALTER TABLE `logbooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `applications_ibfk_3` FOREIGN KEY (`description`) REFERENCES `description` (`id`);

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `fk_foreign_key_user_document` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_description_fk` FOREIGN KEY (`description`) REFERENCES `description` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `jobs_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `logbooks`
--
ALTER TABLE `logbooks`
  ADD CONSTRAINT `logbooks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `logbooks_ibfk_2` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
