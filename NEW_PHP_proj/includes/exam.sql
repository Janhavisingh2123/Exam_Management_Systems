-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 14, 2025 at 11:25 AM
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
-- Database: `exam`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE `assignments` (
  `assignment_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `invigilator_id` int(11) NOT NULL,
  `status` enum('Confirmed','Pending') DEFAULT 'Confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignments`
--

INSERT INTO `assignments` (`assignment_id`, `exam_id`, `invigilator_id`, `status`, `created_at`) VALUES
(9, 7, 6, 'Confirmed', '2025-09-10 18:59:11'),
(11, 8, 1, 'Confirmed', '2025-09-10 19:04:39'),
(12, 6, 3, 'Confirmed', '2025-09-10 19:06:14'),
(13, 2, 1, 'Confirmed', '2025-09-10 19:06:26');

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `exam_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `dept` varchar(100) NOT NULL,
  `exam_date` date NOT NULL,
  `exam_time` time NOT NULL,
  `hall` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`exam_id`, `subject`, `dept`, `exam_date`, `exam_time`, `hall`) VALUES
(1, 'Mathematics', 'Computer Science', '2025-01-15', '09:00:00', 'Room 315'),
(2, 'Physics', 'Physics', '2025-01-16', '10:00:00', 'Hall B'),
(6, 'Java', 'Computer Science', '2025-10-05', '10:30:00', 'Lab 201'),
(7, 'Python', 'I.T.', '2025-10-05', '10:30:00', 'Lab 205'),
(8, 'Java', 'I.T.', '2025-01-15', '09:00:00', 'Room 314');

-- --------------------------------------------------------

--
-- Table structure for table `invigilators`
--

CREATE TABLE `invigilators` (
  `invigilator_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `dept` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invigilators`
--

INSERT INTO `invigilators` (`invigilator_id`, `name`, `dept`, `email`, `phone`) VALUES
(1, 'Shreyash', 'Computer Science', 'sheru@example.com', '9876543210'),
(2, 'Aman', 'I.T.', 'aman@example.com', '9876543211'),
(3, 'Nitin', 'Commerce', 'nitin@mail.com', '9876543210'),
(4, 'Yash', 'Computer Science', 'yash@mail.com', '1234567890'),
(5, 'Yashvi', 'I.T.', 'yashvi@mail.com', '6547891230'),
(6, 'Vansh', 'Commerce', 'vansh@mail.com', '3698521407');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','invigilator') NOT NULL,
  `invigilator_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `invigilator_id`) VALUES
(1, 'admin', '$2y$10$3wxga29ruyiTfBKBuiT7cOaSLs86eDh5As6IthNKZ3jTsU/fhSXC2', 'admin', NULL),
(2, 'Yashvi', '$2y$10$Vv.jxXuLaEH2ajA5s7VQTeoa/V/b/F74XrI9qikWUzIJ1XgNN9SS2', 'invigilator', 1),
(3, 'janhavi', '$2y$10$MXIcu1FCwiygrxTB7NuH6eJS9e0qlnma37eonZgm7B2senBeBIoxa', 'admin', NULL),
(4, 'Shreya', '$2y$10$pOitLFEp.4seHeZrSpK56emjD81maAA6UsGAqkKQAUauxUdl5wWd6', 'invigilator', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD UNIQUE KEY `unique_assignment` (`exam_id`,`invigilator_id`),
  ADD KEY `fk_invigilator` (`invigilator_id`);

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`exam_id`);

--
-- Indexes for table `invigilators`
--
ALTER TABLE `invigilators`
  ADD PRIMARY KEY (`invigilator_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `invigilator_id` (`invigilator_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignments`
--
ALTER TABLE `assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invigilators`
--
ALTER TABLE `invigilators`
  MODIFY `invigilator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `fk_exam` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`exam_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_invigilator` FOREIGN KEY (`invigilator_id`) REFERENCES `invigilators` (`invigilator_id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `audit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`assignment_id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`invigilator_id`) REFERENCES `invigilators` (`invigilator_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
