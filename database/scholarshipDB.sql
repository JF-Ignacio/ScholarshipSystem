-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2026 at 05:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scholarship_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_ID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `scholarship_type` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` varchar(100) NOT NULL,
  `status` enum('Active','Inactive','Pending') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scholars`
--

CREATE TABLE `scholars` (
  `id` int(11) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `course` varchar(100) NOT NULL,
  `year_level` varchar(50) NOT NULL,
  `scholarship_type` varchar(100) NOT NULL,
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scholars`
--

INSERT INTO `scholars` (`id`, `student_id`, `fullname`, `course`, `year_level`, `scholarship_type`, `status`, `created_at`, `email`) VALUES
(1, '0001', 'John Franz D. Ignacio', 'BTVTED - COMPRO', '2nd year', 'DICT-TVAM_Iskolar', 'pending', '2026-05-26 05:10:12', 'franz.tvam@gmail.com'),
(2, '1001', 'Belle Angelique Ducusin Mendez', 'BTVTED - FSM', '4th year', 'DOST-TVAM_Iskolar', 'pending', '2026-05-26 05:11:45', 'viel.tvam@gmail.com'),
(6, '5566', 'Alrahdin Siaman Muklows', 'BTVTED - COMPRO', '2nd year', 'DOST-TVAM_Iskolar', 'pending', '2026-05-28 15:37:56', 'rahdin.tvam@gmail.com'),
(7, '8975', 'Dr. Pedro Gil', 'BTVTED - IA', '1st year', 'DOST-TVAM_Iskolar', 'pending', '2026-05-29 06:37:37', 'gil.tvam@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','student') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Angelique Viel', 'bellemdendez@gmail.com', '$2y$10$vh9EDKFgWP58FqCSdrV2G.s73nQ9vhuQ9bf2cYAzCD0WmltNwuXsS', 'admin', '2026-05-21 17:27:52'),
(2, 'rai', 'rai.tvam@gmail.com', 'Chacha', 'student', '2026-05-30 17:19:18'),
(3, 'Pedro Gil', 'pedro.tvam@gmail.com', '$2y$10$wVH7pTm66E0ygqpZKAsuJe1qppTVJ5U./TBJyI/aw2OQdH8J.b01q', 'student', '2026-05-30 17:20:33'),
(4, 'Kyrie Drake Ignacio', 'kyrie.tvam@gmail.com', '$2y$10$4tGdEEnskf/XfnpyZj/jqezqvF6Ln4uN2Xz/cXgAzwNrGYbnk8ULq', 'student', '2026-05-31 05:47:04'),
(5, 'Stephen Curry', 'stephen.tvam@gmail.com', '$2y$10$DhVwgaCZgaHPUdPAwqZJQuFQ1dbYIhpGaFaU/rWO6ZJN7uE30cC1e', 'student', '2026-05-31 13:49:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_ID`);

--
-- Indexes for table `scholars`
--
ALTER TABLE `scholars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scholars`
--
ALTER TABLE `scholars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
