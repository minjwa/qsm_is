-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 11, 2023 at 01:57 PM
-- Server version: 8.0.25
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `qsm_is`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
CREATE TABLE IF NOT EXISTS `applications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `job_id` int NOT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `result` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `job_id`, `status`, `result`) VALUES
(1, 13, 15, 1, 'reject');

-- --------------------------------------------------------

--
-- Table structure for table `description`
--

DROP TABLE IF EXISTS `description`;
CREATE TABLE IF NOT EXISTS `description` (
  `id` int NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `description`
--

INSERT INTO `description` (`id`, `name`) VALUES
(1, 'Active'),
(2, 'Inactive');

-- --------------------------------------------------------

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
CREATE TABLE IF NOT EXISTS `document` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `name` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_foreign_key_user_document` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `document`
--

INSERT INTO `document` (`id`, `user_id`, `name`) VALUES
(7, 13, 'uploads/resume/RESUME FITRIE.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location_id` int NOT NULL,
  `description` int DEFAULT NULL,
  `date_booking` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `location_id` (`location_id`),
  KEY `description` (`description`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `name`, `location_id`, `description`, `date_booking`) VALUES
(15, 'Company 1', 1, 1, '2023-07-11');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`) VALUES
(1, 'Puncak Perdana'),
(2, 'Puncak Alam');

-- --------------------------------------------------------

--
-- Table structure for table `logbooks`
--

DROP TABLE IF EXISTS `logbooks`;
CREATE TABLE IF NOT EXISTS `logbooks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `supervisor_id` int NOT NULL,
  `file_dir` longtext NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `mark` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logbooks_ibfk_1` (`user_id`),
  KEY `supervisor_id` (`supervisor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` longtext NOT NULL,
  `role_id` int NOT NULL,
  `resume` longtext,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role_id`, `resume`, `created_at`) VALUES
(5, 'admin', 'admin@test.com', '$2y$10$S/M17sC6Gyh7QrNJP8zJ2.3kZP.Mg8h3msJXPpcA9XMnhwxzu4IjC', 5, NULL, '2022-06-29 14:20:16'),
(12, 'iscistech', 'iscistech@gmail.com', '$2y$10$uJ7f5x8mrpT1lphHl.nvNuzUaiX/EEafHR7Wus644vJLzqxSk8.g6', 4, NULL, '2023-07-05 13:30:40'),
(13, 'mina', 'mina@uitm.com', '$2y$10$h9sytq3BRB7OKQqvs7KgVuH.hF1ql4Ly6Q8SnY71Jm5b38h.wWTke', 1, NULL, '2023-07-05 14:39:20');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `document`
--
ALTER TABLE `document`
  ADD CONSTRAINT `fk_foreign_key_user_document` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`),
  ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`description`) REFERENCES `description` (`id`);

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
