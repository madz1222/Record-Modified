-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2023 at 03:22 AM
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
-- Database: `recordtest`
--

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `id` int(200) NOT NULL,
  `folder_name` varchar(200) NOT NULL,
  `parent_folder_id` int(200) NOT NULL,
  `created_at` timestamp(6) NULL DEFAULT current_timestamp(6),
  `updated_at` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`id`, `folder_name`, `parent_folder_id`, `created_at`, `updated_at`) VALUES
(74, 'BSED', 0, '2023-06-18 06:48:21.889381', '2023-06-26 10:53:04.581512'),
(77, 'BTVTE', 0, '2023-06-18 06:48:51.446904', '2023-06-26 10:52:56.104339'),
(92, 'BSIT', 0, '2023-06-18 10:39:07.651770', '2023-06-26 10:53:15.433670');

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

CREATE TABLE `record` (
  `id` int(1) UNSIGNED ZEROFILL NOT NULL,
  `last_name` varchar(200) NOT NULL,
  `clerk_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(15) NOT NULL,
  `middle_name` varchar(15) NOT NULL,
  `course_name` varchar(50) NOT NULL,
  `year_graduate` varchar(12) NOT NULL,
  `year_entry` varchar(12) NOT NULL,
  `grad_hd` varchar(20) NOT NULL,
  `record_status` varchar(15) NOT NULL,
  `folder_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `record`
--

INSERT INTO `record` (`id`, `last_name`, `clerk_id`, `date_created`, `first_name`, `middle_name`, `course_name`, `year_graduate`, `year_entry`, `grad_hd`, `record_status`, `folder_id`) VALUES
(30755, 'Biasura', 1, '2023-06-26 15:38:38', 'Jella Marie', 'Geneta', 'BTVTE', '2024', '2023', 'UnderGraduate', 'notdeleted', 1),
(30756, 'Azucenas', 2, '2023-06-26 18:48:54', 'Mark Piolo', 'Pasa', 'fff', '2023', '2019', 'UnderGraduate', 'notdeleted', 1),
(30757, 'Azucenas', 5, '2023-06-28 18:24:02', 'Mark Piolo', 'Pasass', 'BTVTE', '2024', '2019', 'Graduated', 'notdeleted', 1),
(30758, 'F', 13, '2023-06-28 18:27:29', 'Jolina', 'G', 'd', '2023', '2023', 'Graduated', 'notdeleted', 1),
(30759, 'Biasura', 3, '2023-06-28 19:24:56', 'Universidad', 'Pasa', 'kajfkqje', '2023', '2023', 'Graduated', 'notdeleted', 74),
(30760, 'Azucenas', 5, '2023-06-28 22:00:45', 'Jella Marie', 'Pasa', 'BTVTE', '2024', '2019', 'Graduated', 'notdeleted', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `middlename` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `is_logged_in` tinyint(1) DEFAULT 0,
  `type` tinyint(1) NOT NULL DEFAULT 3 COMMENT '1=Admin,2= users,3=Admin2',
  `avatar` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `token` varchar(65) NOT NULL,
  `token_expiration` datetime DEFAULT NULL,
  `user_session_id` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `middlename`, `email`, `password`, `is_logged_in`, `type`, `avatar`, `date_created`, `token`, `token_expiration`, `user_session_id`) VALUES
(1, 'Registrar', 'Admin', 'Office', 'pioloazucenas@gmail.com', '$2y$10$T5killCVf89L3djyxA9N3ePz5Jskii.tjv0zTcCC.i8h4oZMZ1Jvi', 0, 1, '1685923405_imagelogin.png', '2023-05-18 02:00:23', '274941550b2c7c58473b8ef696c9edae', '2023-06-01 17:52:01', 'b005b61719c2b94307510dd778796e3b'),
(2, ' Registrar', 'Admin2', 'Office', 'admin2', '$2y$10$ttY6lRX4/2LgiyJwZajzzu2XCIH/tH0in/WcqQypm4MYa/3RE7LHK', 1, 3, '', '2023-06-28 17:44:28', '', NULL, 'e01d07fbcf2f7a0794011785a2bacf08'),
(3, 'Registrar ', ' Office', ' Clerk no. 3', 'clerk03', '$2y$10$.2yVJg2/KcC0NQWvABhSQe/t.ArHVtvwtJ9zyVxAVxqKr/qIUVz0.', 0, 2, '', '2023-06-02 13:14:51', '', NULL, 'ed776b549fa7c52df9ad612a653b1c1d'),
(4, 'Registrar ', 'Office', 'Clerk no. 4', 'clerk04', '$2y$10$DPa3HfNVTvrfs.tT3P.D/OCN9YyRs.AB8AsxHaQ8F1dWUHNilC5YS', 0, 2, '', '2023-06-02 14:29:23', '', NULL, '51a3b417b868f298f6487434edfc6e87'),
(5, 'Registrar ', 'Office', 'Clerk no. 5', 'clerk05', '$2y$10$AEn0uSd84EHvDT0oK3675e7HAyByrb1soRNiA0elnEKDRu8EYn9nu', 0, 2, '', '2023-06-02 14:47:43', '', NULL, ''),
(6, 'Registrar', 'Office', 'clerk no. 6', 'clerk06', '$2y$10$AsbiiqI87WD3nIcYHTzvcOZSiEs8GNN7rmmiftgzJKb9BObgbleRG', 0, 2, '', '2023-06-04 20:00:49', '', NULL, ''),
(7, 'Registrar', 'Office', 'clerk no. 7', 'clerk07', '$2y$10$4veRmgnu32fTRA5Uf7MOtOHmz2aFIgSURXyvak1TUDgn.245hdGq.', 0, 2, '', '2023-06-04 20:01:10', '', NULL, ''),
(8, 'Registrar', 'Office', 'clerk no. 8', 'clerk08', '$2y$10$yQ9UBgeRFRmLQvDaMGY21OL3riOPLT4knbtd83TltPzUkTWLYtoLm', 0, 2, '', '2023-06-04 20:02:06', '', NULL, ''),
(9, 'Registrar', 'Office', 'Clerk no. 9', 'clerk09', '$2y$10$IBsQfjZuMMIo8ZWanzDTX.xxiVzRMAL/Djd6H7bw1hIDit80QEsM2', 0, 2, '', '2023-06-04 20:02:30', '', NULL, ''),
(10, ' Registrar', ' Office', 'Clerk no. 10', 'clerk10', '$2y$10$pBKO.ATnJtxHgwODJmCi2eKxW5tnAsuOCUr.0eUjXwEd6sRw2/xIq', 0, 2, '', '2023-06-04 20:02:45', '', NULL, ''),
(11, ' Registrar', ' Office', 'Clerk no. 11', 'clerk11', '$2y$10$GNvK8Xki6GfGk2u47Tkbh.10l50Nu9kbmY3GvV106sPqVQYBK4cte', 0, 2, '', '2023-06-04 20:03:21', '', NULL, ''),
(12, ' Registrar', ' Office', ' Clerk no. 12', 'clerk12', '$2y$10$Rx1Hcb3a78eEGDop5XnajeMYE7z.1FDfiaOPpxgwZGHw1oq6Dcjsy', 0, 2, '', '2023-06-04 20:04:03', '', NULL, ''),
(13, '  Registrar', '  Office', '  Clerk no. 13', 'clerk13', '$2y$10$OWnczIIevJiD44.y0nxryOnJIfuhqZf4I2Rdmm42OGd.MmZjuc8wa', 0, 2, '', '2023-06-04 20:04:16', '', NULL, '158f88dee634193aeab097fc6d467f3c'),
(14, ' Registrar', ' Office', ' Clerk no. 14', 'clerk14', '$2y$10$Mlqjc2K7TMXaQqvjExUBhuMF5KUp7rAzohYSoOb7OE7p80VrpyFe6', 0, 2, '', '2023-06-04 20:04:25', '', NULL, ''),
(15, ' Registrar', 'Office', 'De', 'clerk15', '$2y$10$2PpgZtPH5DnvsX.yhkJRAe0asVk2XEFkeAdR0o/WyekzOjp6bYQHy', 0, 2, '', '2023-06-28 18:01:57', '', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `user_file`
--

CREATE TABLE `user_file` (
  `file_id` int(10) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `file_type` varchar(200) NOT NULL,
  `date_uploaded` varchar(200) NOT NULL,
  `clerk_id` int(10) NOT NULL,
  `student_no` int(10) NOT NULL,
  `folder_id` int(10) NOT NULL,
  `file_status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_file`
--

INSERT INTO `user_file` (`file_id`, `file_name`, `file_type`, `date_uploaded`, `clerk_id`, `student_no`, `folder_id`, `file_status`) VALUES
(432, '1687819118_coc regs.pdf', 'pdf', '2023-06-27, 06:38 AM', 14, 30755, 1, 'notdeleted'),
(433, '1687819118_JELLA PORTAL.pdf', 'pdf', '2023-06-27, 06:38 AM', 14, 30755, 1, 'notdeleted'),
(434, '1687819118_JELLAUPDATEDRESUME.pdf', 'pdf', '2023-06-27, 06:38 AM', 14, 30755, 1, 'notdeleted'),
(435, '1687819118_MIDTERM EVAL SYSTEM REGS.pdf', 'pdf', '2023-06-27, 06:38 AM', 14, 30755, 1, 'notdeleted'),
(436, '1687819118_newwww.pdf', 'pdf', '2023-06-27, 06:38 AM', 14, 30755, 1, 'notdeleted'),
(437, '1687819118_PIOLO PORTAL.pdf', 'pdf', '2023-06-27, 06:38 AM', 14, 30755, 1, 'notdeleted'),
(438, '1687819118_PIOLOUPDATEDRESUME-2.pdf', 'pdf', '2023-06-27, 06:38 AM', 14, 30755, 1, 'notdeleted'),
(439, '1687819118_UPDATEDRecordManagementSystem_Documentation.pdf', 'pdf', '2023-06-27, 06:38 AM', 14, 30755, 1, 'notdeleted'),
(440, '1687830534_coc regs.pdf', 'pdf', '2023-06-27, 09:48 AM', 2, 30756, 1, 'notdeleted'),
(441, '1687830534_JELLA PORTAL.pdf', 'pdf', '2023-06-27, 09:48 AM', 2, 30756, 1, 'notdeleted'),
(442, '1687830534_JELLAUPDATEDRESUME.pdf', 'pdf', '2023-06-27, 09:48 AM', 2, 30756, 1, 'notdeleted'),
(443, '1687830534_MIDTERM EVAL SYSTEM REGS.pdf', 'pdf', '2023-06-27, 09:48 AM', 2, 30756, 1, 'notdeleted'),
(444, '1687830534_newwww.pdf', 'pdf', '2023-06-27, 09:48 AM', 2, 30756, 1, 'notdeleted'),
(445, '1687830534_PIOLO PORTAL.pdf', 'pdf', '2023-06-27, 09:48 AM', 2, 30756, 1, 'notdeleted'),
(446, '1687830534_PIOLOUPDATEDRESUME-2.pdf', 'pdf', '2023-06-27, 09:48 AM', 2, 30756, 1, 'notdeleted'),
(447, '1687830534_UPDATEDRecordManagementSystem_Documentation.pdf', 'pdf', '2023-06-27, 09:48 AM', 2, 30756, 1, 'notdeleted');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `record`
--
ALTER TABLE `record`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_file`
--
ALTER TABLE `user_file`
  ADD PRIMARY KEY (`file_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `record`
--
ALTER TABLE `record`
  MODIFY `id` int(1) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30761;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_file`
--
ALTER TABLE `user_file`
  MODIFY `file_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=448;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
