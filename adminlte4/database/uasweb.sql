-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2025 at 03:58 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uasweb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_activity_log`
--

CREATE TABLE `tbl_activity_log` (
  `log_record_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `date_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_activity_log`
--

INSERT INTO `tbl_activity_log` (`log_record_id`, `user_id`, `details`, `date_time`) VALUES
(1, 1, 'Added new user: User 1', '2025-02-20 10:30:27'),
(2, 1, 'Updated profile image for user #15', '2025-02-20 10:35:44'),
(3, 1, 'Deleted user #15 - Username: user1, Name: User 1, Designation: User 1', '2025-02-20 10:40:14'),
(4, 16, 'Added new user: rehan wangsaf', '2025-06-29 20:38:39'),
(5, 1, 'Updated profile image for user #1', '2025-06-29 20:48:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_backup`
--

CREATE TABLE `tbl_backup` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `backup_file` varchar(255) NOT NULL,
  `backup_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_backup`
--

INSERT INTO `tbl_backup` (`id`, `user_id`, `backup_file`, `backup_date`) VALUES
(1, 1, 'backup/database_backup_2024-06-15_13-12-35.sql', '2024-06-15 11:12:35'),
(2, 1, 'backup/database_backup_2024-06-15_14-55-29.sql', '2024-06-15 12:55:29'),
(3, 1, 'backup/database_backup_2024-06-15_14-55-37.sql', '2024-06-15 12:55:37'),
(4, 1, 'backup/database_backup_2024-06-15_15-08-50.sql', '2024-06-15 13:08:50'),
(5, 1, 'backup/database_backup_2024-06-15_15-08-52.sql', '2024-06-15 13:08:52'),
(6, 1, 'backup/database_backup_2024-06-15_15-08-56.sql', '2024-06-15 13:08:56');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `complete_name` varchar(255) DEFAULT NULL,
  `designation` text NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `user_type` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `password`, `complete_name`, `designation`, `profile_image`, `user_type`) VALUES
(1, 'admin', '$2y$10$k15A2LQgwB6lZVO40q9mFOEuqtK9Nag80jxEJS70CRBQVoQkrF68u', 'Admin', 'Admin memiliki akses keseluruhan fitur', 'Admin_6861442434ae8.jpg', 'admin'),
(16, 'rehan123', '$2y$10$d/nHvvcBPW/NDX7L3MJIYemT3lnQ.pFS9g2lDPcbCuS7AoI7x3wvi', 'rehan wangsaf', 'pengguna baru', 'rehan_wangsaf_686141df11f3e.jpg', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_activity_log`
--
ALTER TABLE `tbl_activity_log`
  ADD PRIMARY KEY (`log_record_id`);

--
-- Indexes for table `tbl_backup`
--
ALTER TABLE `tbl_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `complete_name` (`complete_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_activity_log`
--
ALTER TABLE `tbl_activity_log`
  MODIFY `log_record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_backup`
--
ALTER TABLE `tbl_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
