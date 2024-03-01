-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2024 at 11:22 PM
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
-- Database: `todo`
--

-- --------------------------------------------------------

--
-- Table structure for table `folders`
--

CREATE TABLE `folders` (
  `folder_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `folderName` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `folders`
--

INSERT INTO `folders` (`folder_id`, `user_id`, `folderName`, `created_at`) VALUES
(1, 4, 'new folder', '2023-12-18 18:37:20'),
(2, 4, 'folder', '2023-12-18 18:38:37'),
(3, 4, 'School Folder', '2023-12-18 18:46:35'),
(4, 4, 'Final Folder', '2023-12-19 01:38:10'),
(8, 5, 'my folder', '2024-02-24 12:58:57'),
(17, 5, 'A NEW FOLDER', '2024-02-25 20:55:20'),
(18, 6, 'Folder 1', '2024-02-29 20:08:42'),
(19, 6, 'Folder 2', '2024-02-29 20:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `taskName` varchar(255) NOT NULL,
  `taskDescription` text DEFAULT NULL,
  `dueDate` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `folder_id`, `taskName`, `taskDescription`, `dueDate`, `created_at`, `completed`, `completed_at`) VALUES
(1, 3, 'First Task', 'First Task Description', NULL, '2023-12-19 01:36:23', 0, NULL),
(2, 3, 'Second Task', 'Second Task Desc', NULL, '2023-12-20 18:18:36', 0, NULL),
(3, 1, 'new folder task 1', 'this should be in a different folder', NULL, '2023-12-28 10:03:29', 0, NULL),
(4, 8, 'Test Users First Task', 'Created using the localhost - local', NULL, '2024-02-25 19:24:55', 0, NULL),
(5, NULL, 'second', 'form', NULL, '2024-02-25 20:11:16', 0, NULL),
(6, NULL, 'task', 'form', NULL, '2024-02-25 20:16:27', 0, NULL),
(7, NULL, 'furst', 'Created using form', NULL, '2024-02-25 20:20:20', 0, NULL),
(9, 8, 'first task', 'Created using form', NULL, '2024-02-25 20:52:30', 0, NULL),
(10, 8, 'IT WORKS?', 'dOES THIS ACTUALLY WORK', NULL, '2024-02-25 20:54:37', 0, NULL),
(11, 8, 'TESTING TASK LIMIT', 'TOO MANY TASKS BAD??', NULL, '2024-02-25 20:55:02', 0, NULL),
(12, 17, 'this should be different', 'new task', NULL, '2024-02-25 20:55:34', 0, NULL),
(23, 18, 'Final test', 'hopefully', NULL, '2024-03-01 17:06:24', 1, '2024-03-01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`) VALUES
(1, 'charlie', 'pass'),
(2, 'c', '$2y$10$XlFKKsL/VTuch4yI9LPbeeRCU6TzYSFst.8qkqfa0Av.z331AHXHu'),
(3, 'tester', '$2y$10$OsXgNdqhnyOAcWRcERuUPuo8/AsdR0WgEyBl06hIZKDnth02NpcHS'),
(4, 'chloe', '$2y$10$HPuIL9FKQDWLaJFQI27rCuAcfLQ6kJR0plIC0l1YTQtIZuAAf0UI6'),
(5, 'testuser', '$2y$10$RkXk105pAvxlXzHgQK.46u6BXSMy2gQ5ECjubN8kozpk1SIFqIfH6'),
(6, 'charlie-admin', '$2y$10$Zx8kvgxQbOOffJtygFi2VOkS3QXaJh7qc2LHeINCIIUz.J3xVihka');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`folder_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `folder_id` (`folder_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `folders`
--
ALTER TABLE `folders`
  MODIFY `folder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `folders`
--
ALTER TABLE `folders`
  ADD CONSTRAINT `folders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`folder_id`) REFERENCES `folders` (`folder_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
