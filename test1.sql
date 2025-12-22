-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2025 at 05:01 PM
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
-- Database: `test1`
--

-- --------------------------------------------------------

--
-- Table structure for table `datas`
--

CREATE TABLE `datas` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `datas`
--

INSERT INTO `datas` (`id`, `from_id`, `to_id`) VALUES
(1, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `facul_name` int(11) NOT NULL,
  `dept_name` varchar(100) NOT NULL,
  `type` enum('casual','medical','earned') NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `data_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `facul_name`, `dept_name`, `type`, `from_date`, `to_date`, `status`, `data_id`) VALUES
(1, 4, 'ece', 'casual', '2025-12-22', '2025-12-31', 'pending', 1),
(2, 4, 'ece', 'casual', '2025-12-22', '2025-12-31', 'pending', 1);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_leave`
--

CREATE TABLE `faculty_leave` (
  `id` int(11) NOT NULL,
  `faculty_name` varchar(25) DEFAULT NULL,
  `employee_code` varchar(20) DEFAULT NULL,
  `department` enum('CSE','ECE') DEFAULT NULL,
  `leave_type` enum('Casual','Sick','On Duty') DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_leave`
--

INSERT INTO `faculty_leave` (`id`, `faculty_name`, `employee_code`, `department`, `leave_type`, `from_date`, `to_date`, `status`, `created_at`) VALUES
(1, 'CSE Staff 1', 'CSE101', 'CSE', 'Casual', '2025-12-24', '2025-12-24', 'Rejected', '2025-12-22 16:31:21'),
(3, 'CSE Staff 1', 'CSE101', 'CSE', 'Sick', '2025-12-25', '2025-12-26', 'Rejected', '2025-12-22 16:48:19'),
(4, 'CSE Staff 2', 'CSE102', 'CSE', 'Sick', '2025-12-24', '2025-12-25', 'Approved', '2025-12-22 16:51:23'),
(5, 'CSE Staff 2', 'CSE102', 'CSE', 'Casual', '2026-01-01', '2025-12-26', 'Approved', '2025-12-22 16:57:55'),
(6, 'CSE Staff 1', 'CSE101', 'CSE', 'On Duty', '2025-12-24', '2025-12-25', 'Approved', '2025-12-22 17:17:18'),
(7, 'ECE Staff 1', 'ECE101', 'ECE', 'Casual', '2025-12-24', '2025-12-26', 'Approved', '2025-12-22 17:22:25'),
(8, 'CSE Staff 1', 'CSE101', 'CSE', 'On Duty', '2025-12-24', '2025-12-26', 'Approved', '2025-12-22 17:33:49'),
(10, 'ECE Staff 2', 'ECE102', 'ECE', 'Sick', '2025-12-24', '2025-12-26', 'Rejected', '2025-12-22 18:03:23'),
(11, 'ECE Staff 1', 'ECE101', 'ECE', 'Casual', '2025-12-24', '2025-12-26', 'Approved', '2025-12-22 18:11:31');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `employee_code` varchar(20) DEFAULT NULL,
  `role` enum('staff','hod') DEFAULT NULL,
  `department` enum('CSE','ECE') DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `name`, `employee_code`, `role`, `department`, `username`, `password`) VALUES
(1, 'CSE Staff 1', 'CSE101', 'staff', 'CSE', 'cse1', '*23AE809DDACAF96AF0FD78ED'),
(2, 'CSE Staff 2', 'CSE102', 'staff', 'CSE', 'cse2', '*23AE809DDACAF96AF0FD78ED'),
(3, 'ECE Staff 1', 'ECE101', 'staff', 'ECE', 'ece1', '*23AE809DDACAF96AF0FD78ED'),
(4, 'ECE Staff 2', 'ECE102', 'staff', 'ECE', 'ece2', '*23AE809DDACAF96AF0FD78ED'),
(5, 'CSE HOD', 'CSEHOD', 'hod', 'CSE', 'csehod', '*23AE809DDACAF96AF0FD78ED'),
(6, 'ECE HOD', 'ECEHOD', 'hod', 'ECE', 'ecehod', '*23AE809DDACAF96AF0FD78ED');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','hod','staff') NOT NULL,
  `dept_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `role`, `dept_name`) VALUES
(1, 'hod_ramesh@college.edu', 'hod_ramesh@college.edu', 'hod', 'ECE'),
(2, 'staff_arun@college.edu', 'staff123', 'staff', 'ECE'),
(3, 'staff_priya@college.edu', 'staff', 'staff', 'ECE'),
(4, 'staff_karthik@college.edu', 'staff123', 'staff', 'ECE'),
(5, 'staff_meena@college.edu', 'staff123', 'staff', 'ECE');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `datas`
--
ALTER TABLE `datas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_datas_from_user` (`from_id`),
  ADD KEY `fk_datas_to_user` (`to_id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_faculty_user` (`facul_name`),
  ADD KEY `fk_faculty_data` (`data_id`);

--
-- Indexes for table `faculty_leave`
--
ALTER TABLE `faculty_leave`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `datas`
--
ALTER TABLE `datas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty_leave`
--
ALTER TABLE `faculty_leave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `datas`
--
ALTER TABLE `datas`
  ADD CONSTRAINT `fk_datas_from_user` FOREIGN KEY (`from_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_datas_to_user` FOREIGN KEY (`to_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `faculty`
--
ALTER TABLE `faculty`
  ADD CONSTRAINT `fk_faculty_data` FOREIGN KEY (`data_id`) REFERENCES `datas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_faculty_user` FOREIGN KEY (`facul_name`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
