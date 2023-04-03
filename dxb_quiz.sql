-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2023 at 04:50 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dxb_quiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `topics` enum('PHP','AJAX','JQUERY','HTML') NOT NULL,
  `question` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `topics`, `question`, `created_at`, `updated_at`) VALUES
(1, 'PHP', 'Question NO 1 of PHP', '2023-03-31 14:14:06', NULL),
(2, 'PHP', 'Question NO 2 of PHP', '2023-03-31 14:14:06', NULL),
(3, 'PHP', 'Question NO 3 of PHP', '2023-03-31 14:14:06', NULL),
(4, 'PHP', 'Question NO 4 of PHP', '2023-03-31 14:14:06', NULL),
(5, 'AJAX', 'Question NO 1 of AJAX', '2023-03-31 14:14:06', NULL),
(6, 'AJAX', 'Question NO 2 of AJAX', '2023-03-31 14:14:06', NULL),
(7, 'AJAX', 'Question NO 3 of AJAX', '2023-03-31 14:14:06', NULL),
(8, 'AJAX', 'Question NO 4 of AJAX', '2023-03-31 14:14:06', NULL),
(9, 'JQUERY', 'Question NO 1 of jQuery', '2023-03-31 14:14:06', NULL),
(10, 'JQUERY', 'Question NO 2 of jQuery', '2023-03-31 14:14:06', NULL),
(11, 'JQUERY', 'Question NO 3 of jQuery', '2023-03-31 14:14:06', NULL),
(12, 'JQUERY', 'Question NO 4 of jQuery', '2023-03-31 14:14:06', NULL),
(13, 'HTML', 'Question NO 1 of Html', '2023-03-31 14:14:06', NULL),
(14, 'HTML', 'Question NO 2 of Html', '2023-03-31 14:14:06', NULL),
(15, 'HTML', 'Question NO 3 of Html', '2023-03-31 14:14:06', NULL),
(16, 'HTML', 'Question NO 4 of Html', '2023-03-31 14:14:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option` varchar(255) NOT NULL,
  `is_correct_answer` enum('YES','NO') NOT NULL DEFAULT 'NO'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `option`, `is_correct_answer`) VALUES
(1, 1, 'Option 1', ''),
(2, 1, 'Option 2', ''),
(3, 1, 'Option 3', ''),
(4, 1, 'Option 4', 'YES'),
(5, 2, 'Option 1', ''),
(6, 2, 'Option 2', 'YES'),
(7, 2, 'Option 3', ''),
(8, 2, 'Option 4', ''),
(9, 3, 'Option 1', ''),
(10, 3, 'Option 2', ''),
(11, 3, 'Option 3', ''),
(12, 3, 'Option 4', 'YES'),
(13, 4, 'Option 1', ''),
(14, 4, 'Option 2', ''),
(15, 4, 'Option 3', 'YES'),
(16, 4, 'Option 4', ''),
(17, 5, 'Option 1', ''),
(18, 5, 'Option 2', 'YES'),
(19, 5, 'Option 3', ''),
(20, 5, 'Option 4', ''),
(21, 6, 'Option 1', 'YES'),
(22, 6, 'Option 2', ''),
(23, 6, 'Option 3', ''),
(24, 6, 'Option 4', ''),
(25, 7, 'Option 1', ''),
(26, 7, 'Option 2', ''),
(27, 7, 'Option 3', 'YES'),
(28, 7, 'Option 4', ''),
(29, 8, 'Option 1', ''),
(30, 8, 'Option 2', ''),
(31, 8, 'Option 3', 'YES'),
(32, 8, 'Option 4', ''),
(33, 9, 'Option 1', ''),
(34, 9, 'Option 2', ''),
(35, 9, 'Option 3', 'YES'),
(36, 9, 'Option 4', ''),
(37, 10, 'Option 1', ''),
(38, 10, 'Option 2', 'YES'),
(39, 10, 'Option 3', ''),
(40, 10, 'Option 4', ''),
(41, 11, 'Option 1', 'YES'),
(42, 11, 'Option 2', ''),
(43, 11, 'Option 3', ''),
(44, 11, 'Option 4', ''),
(45, 12, 'Option 1', 'YES'),
(46, 12, 'Option 2', ''),
(47, 12, 'Option 3', ''),
(48, 12, 'Option 4', ''),
(49, 13, 'Option 1', ''),
(50, 13, 'Option 2', 'YES'),
(51, 13, 'Option 3', ''),
(52, 13, 'Option 4', ''),
(53, 14, 'Option 1', ''),
(54, 14, 'Option 2', 'YES'),
(55, 14, 'Option 3', ''),
(56, 14, 'Option 4', ''),
(57, 15, 'Option 1', ''),
(58, 15, 'Option 2', ''),
(59, 15, 'Option 3', ''),
(60, 15, 'Option 4', 'YES'),
(61, 16, 'Option 1', ''),
(62, 16, 'Option 2', ''),
(63, 16, 'Option 3', ''),
(64, 16, 'Option 4', 'YES');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'fuad', NULL, '2023-03-31 23:26:23', '2023-03-31 23:26:23'),
(2, 'topu', NULL, '2023-03-31 23:28:31', '2023-03-31 23:28:31'),
(3, 'asdfa', NULL, '2023-03-31 23:34:45', '2023-03-31 23:34:45'),
(4, 'Siam', NULL, '2023-04-02 12:39:19', '2023-04-02 12:39:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_quiz_stats`
--

CREATE TABLE `user_quiz_stats` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `given_answer` int(11) DEFAULT NULL,
  `is_correct` enum('YES','NO') NOT NULL,
  `is_skipped` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_quiz_stats`
--

INSERT INTO `user_quiz_stats` (`id`, `user_id`, `question_id`, `given_answer`, `is_correct`, `is_skipped`, `created_at`, `updated_at`) VALUES
(20, 1, 1, 1, 'NO', 'NO', '2023-04-02 09:10:17', '2023-04-02 09:10:17'),
(21, 1, 2, 6, 'YES', 'NO', '2023-04-02 09:10:20', '2023-04-02 09:10:20'),
(22, 1, 3, NULL, 'NO', 'YES', '2023-04-02 09:10:25', '2023-04-02 09:10:25'),
(23, 1, 4, 16, 'NO', 'NO', '2023-04-02 09:10:29', '2023-04-02 09:10:29'),
(24, 1, 5, NULL, 'NO', 'YES', '2023-04-02 09:35:03', '2023-04-02 09:35:03'),
(25, 1, 6, NULL, 'NO', 'YES', '2023-04-02 09:35:07', '2023-04-02 09:35:07'),
(26, 1, 7, 28, 'NO', 'NO', '2023-04-02 09:35:11', '2023-04-02 09:35:11'),
(27, 1, 8, 31, 'YES', 'NO', '2023-04-02 09:35:15', '2023-04-02 09:35:15'),
(28, 1, 9, 33, 'NO', 'NO', '2023-04-02 12:37:53', '2023-04-02 12:37:53'),
(29, 1, 10, 40, 'NO', 'NO', '2023-04-02 12:37:58', '2023-04-02 12:37:58'),
(30, 1, 11, 41, 'YES', 'NO', '2023-04-02 12:38:02', '2023-04-02 12:38:02'),
(31, 1, 12, 48, 'NO', 'NO', '2023-04-02 12:38:06', '2023-04-02 12:38:06'),
(32, 4, 1, 1, 'NO', 'NO', '2023-04-02 12:39:35', '2023-04-02 12:39:35'),
(33, 4, 2, 6, 'YES', 'NO', '2023-04-02 12:39:39', '2023-04-02 12:39:39'),
(34, 4, 3, 11, 'NO', 'NO', '2023-04-02 12:39:43', '2023-04-02 12:39:43'),
(35, 4, 4, 16, 'NO', 'NO', '2023-04-02 12:39:47', '2023-04-02 12:39:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_quiz_stats`
--
ALTER TABLE `user_quiz_stats`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_quiz_stats`
--
ALTER TABLE `user_quiz_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
