-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2026 at 02:35 PM
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
-- Database: `bulkupload`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `mark` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `phone`, `mark`, `created_at`, `updated_at`) VALUES
(1, 'Daniel Wilson', 'daniel.wilson1@gmail.com', '9876543101', 50, '2026-01-07 07:44:04', '2026-01-07 08:34:39'),
(2, 'Emma Davis', 'emma.davis2@gmail.com', '9876543102', 50, '2026-01-07 07:44:04', '2026-01-07 08:34:39'),
(3, 'Emily Anderson', 'emily.anderson3@gmail.com', '9876543103', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(4, 'Michael Jackson', 'michael.jackson4@gmail.com', '9876543104', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(5, 'John Smith', 'john.smith5@gmail.com', '9876543105', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(6, 'William Wilson', 'william.wilson6@gmail.com', '9876543106', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(7, 'Emily Wilson', 'emily.wilson7@gmail.com', '9876543107', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(8, 'Alice Wilson', 'alice.wilson8@gmail.com', '9876543108', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(9, 'James Taylor', 'james.taylor9@gmail.com', '9876543109', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(10, 'Emily Taylor', 'emily.taylor10@gmail.com', '9876543110', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(11, 'Daniel Jackson', 'daniel.jackson11@gmail.com', '9876543111', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(12, 'Olivia Anderson', 'olivia.anderson12@gmail.com', '9876543112', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(13, 'Emily Smith', 'emily.smith13@gmail.com', '9876543113', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(14, 'Michael Thomas', 'michael.thomas14@gmail.com', '9876543114', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(15, 'Alice Johnson', 'alice.johnson15@gmail.com', '9876543115', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(16, 'Michael Brown', 'michael.brown16@gmail.com', '9876543116', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(17, 'Sophia Taylor', 'sophia.taylor17@gmail.com', '9876543117', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(18, 'William Brown', 'william.brown18@gmail.com', '9876543118', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(19, 'John Brown', 'john.brown19@gmail.com', '9876543119', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(20, 'Michael Jackson', 'michael.jackson20@gmail.com', '9876543120', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(21, 'Michael Taylor', 'michael.taylor21@gmail.com', '9876543121', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(22, 'Olivia Brown', 'olivia.brown22@gmail.com', '9876543122', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(23, 'Olivia Brown', 'olivia.brown23@gmail.com', '9876543123', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(24, 'James Thomas', 'james.thomas24@gmail.com', '9876543124', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(25, 'William Thomas', 'william.thomas25@gmail.com', '9876543125', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(26, 'Olivia Anderson', 'olivia.anderson26@gmail.com', '9876543126', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(27, 'Olivia Taylor', 'olivia.taylor27@gmail.com', '9876543127', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(28, 'Michael Miller', 'michael.miller28@gmail.com', '9876543128', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(29, 'William Davis', 'william.davis29@gmail.com', '9876543129', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(30, 'Emily Miller', 'emily.miller30@gmail.com', '9876543130', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(31, 'Emma Anderson', 'emma.anderson31@gmail.com', '9876543131', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(32, 'Michael Smith', 'michael.smith32@gmail.com', '9876543132', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(33, 'Daniel Anderson', 'daniel.anderson33@gmail.com', '9876543133', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(34, 'Sophia Jackson', 'sophia.jackson34@gmail.com', '9876543134', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(35, 'Emily Jackson', 'emily.jackson35@gmail.com', '9876543135', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(36, 'Sophia Jackson', 'sophia.jackson36@gmail.com', '9876543136', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(37, 'John Miller', 'john.miller37@gmail.com', '9876543137', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(38, 'William Johnson', 'william.johnson38@gmail.com', '9876543138', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(39, 'John Thomas', 'john.thomas39@gmail.com', '9876543139', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(40, 'Emma Smith', 'emma.smith40@gmail.com', '9876543140', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(41, 'Alice Smith', 'alice.smith41@gmail.com', '9876543141', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(42, 'William Jackson', 'william.jackson42@gmail.com', '9876543142', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(43, 'Alice Davis', 'alice.davis43@gmail.com', '9876543143', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(44, 'Emma Thomas', 'emma.thomas44@gmail.com', '9876543144', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(45, 'William Thomas', 'william.thomas45@gmail.com', '9876543145', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(46, 'Michael Wilson', 'michael.wilson46@gmail.com', '9876543146', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(47, 'Emma Davis', 'emma.davis47@gmail.com', '9876543147', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(48, 'Alice Johnson', 'alice.johnson48@gmail.com', '9876543148', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(49, 'Alice Jackson', 'alice.jackson49@gmail.com', '9876543149', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(50, 'John Smith', 'john.smith50@gmail.com', '9876543150', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(51, 'James Brown', 'james.brown51@gmail.com', '9876543151', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(52, 'Emma Jackson', 'emma.jackson52@gmail.com', '9876543152', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(53, 'Emily Miller', 'emily.miller53@gmail.com', '9876543153', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(54, 'William Wilson', 'william.wilson54@gmail.com', '9876543154', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(55, 'Sophia Taylor', 'sophia.taylor55@gmail.com', '9876543155', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(56, 'John Wilson', 'john.wilson56@gmail.com', '9876543156', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:08'),
(57, 'Olivia Thomas', 'olivia.thomas57@gmail.com', '9876543157', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(58, 'John Taylor', 'john.taylor58@gmail.com', '9876543158', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(59, 'Emily Smith', 'emily.smith59@gmail.com', '9876543159', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(60, 'Alice Smith', 'alice.smith60@gmail.com', '9876543160', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(61, 'James Jackson', 'james.jackson61@gmail.com', '9876543161', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(62, 'Emma Wilson', 'emma.wilson62@gmail.com', '9876543162', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(63, 'Olivia Johnson', 'olivia.johnson63@gmail.com', '9876543163', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(64, 'Sophia Wilson', 'sophia.wilson64@gmail.com', '9876543164', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(65, 'Alice Johnson', 'alice.johnson65@gmail.com', '9876543165', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(66, 'Olivia Anderson', 'olivia.anderson66@gmail.com', '9876543166', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(67, 'William Smith', 'william.smith67@gmail.com', '9876543167', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(68, 'William Anderson', 'william.anderson68@gmail.com', '9876543168', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(69, 'Olivia Jackson', 'olivia.jackson69@gmail.com', '9876543169', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(70, 'Alice Anderson', 'alice.anderson70@gmail.com', '9876543170', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(71, 'Michael Davis', 'michael.davis71@gmail.com', '9876543171', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(72, 'Olivia Brown', 'olivia.brown72@gmail.com', '9876543172', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(73, 'Emma Johnson', 'emma.johnson73@gmail.com', '9876543173', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(74, 'Emma Wilson', 'emma.wilson74@gmail.com', '9876543174', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(75, 'Emma Smith', 'emma.smith75@gmail.com', '9876543175', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(76, 'Emma Miller', 'emma.miller76@gmail.com', '9876543176', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(77, 'Michael Smith', 'michael.smith77@gmail.com', '9876543177', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(78, 'Alice Davis', 'alice.davis78@gmail.com', '9876543178', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(79, 'Alice Johnson', 'alice.johnson79@gmail.com', '9876543179', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(80, 'Sophia Smith', 'sophia.smith80@gmail.com', '9876543180', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(81, 'Emily Smith', 'emily.smith81@gmail.com', '9876543181', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(82, 'Sophia Davis', 'sophia.davis82@gmail.com', '9876543182', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(83, 'Olivia Brown', 'olivia.brown83@gmail.com', '9876543183', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(84, 'John Smith', 'john.smith84@gmail.com', '9876543184', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(85, 'William Miller', 'william.miller85@gmail.com', '9876543185', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(86, 'Michael Anderson', 'michael.anderson86@gmail.com', '9876543186', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(87, 'Emma Jackson', 'emma.jackson87@gmail.com', '9876543187', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(88, 'Emma Jackson', 'emma.jackson88@gmail.com', '9876543188', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(89, 'John Miller', 'john.miller89@gmail.com', '9876543189', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(90, 'Olivia Taylor', 'olivia.taylor90@gmail.com', '9876543190', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(91, 'Daniel Johnson', 'daniel.johnson91@gmail.com', '9876543191', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(92, 'John Davis', 'john.davis92@gmail.com', '9876543192', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(93, 'Daniel Brown', 'daniel.brown93@gmail.com', '9876543193', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(94, 'Olivia Taylor', 'olivia.taylor94@gmail.com', '9876543194', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(95, 'Daniel Davis', 'daniel.davis95@gmail.com', '9876543195', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(96, 'Michael Davis', 'michael.davis96@gmail.com', '9876543196', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(97, 'John Jackson', 'john.jackson97@gmail.com', '9876543197', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(98, 'Michael Davis', 'michael.davis98@gmail.com', '9876543198', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(99, 'Olivia Taylor', 'olivia.taylor99@gmail.com', '9876543199', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09'),
(100, 'Michael Wilson', 'michael.wilson100@gmail.com', '9876543200', 0, '2026-01-07 07:44:04', '2026-01-07 08:33:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
