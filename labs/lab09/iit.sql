-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 26, 2026 at 07:14 PM
-- Server version: 10.11.14-MariaDB-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `iit`
--

-- --------------------------------------------------------

--
-- Table structure for table `actors`
--

CREATE TABLE `actors` (
  `id` int(11) NOT NULL,
  `first` varchar(255) NOT NULL,
  `last` varchar(255) NOT NULL,
  `birth_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `actors`
--

INSERT INTO `actors` (`id`, `first`, `last`, `birth_date`) VALUES
(1, 'Tom', 'Cruise', '1962-07-03'),
(2, 'Morgan', 'Freeman', '1937-06-01'),
(3, 'Judi', 'Dench', '1934-12-09'),
(4, 'Chuck', 'Norris', '1940-03-10'),
(5, 'Tom', 'Hanks', '1956-07-09');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `year` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `year`) VALUES
(1, 'Mission Impossible', 2006),
(2, 'Skyfall', 2012),
(3, 'The Shawshank Redemption', 1994),
(4, 'Forrest Gump', 1994),
(5, 'Way of the Dragon', 1972);

-- --------------------------------------------------------

--
-- Table structure for table `movie_actors`
--

CREATE TABLE `movie_actors` (
  `movie_id` int(11) NOT NULL,
  `actor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movie_actors`
--

INSERT INTO `movie_actors` (`movie_id`, `actor_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `site_visits`
--

CREATE TABLE `site_visits` (
  `id` int(11) NOT NULL,
  `page_path` varchar(255) NOT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `referrer` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip_hash` char(64) DEFAULT NULL,
  `visited_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `site_visits`
--

INSERT INTO `site_visits` (`id`, `page_path`, `page_title`, `referrer`, `user_agent`, `ip_hash`, `visited_at`) VALUES
(1, '/iit/test', 'Test', NULL, 'curl/8.5.0', '644de525c22adf2ca18c1f51ae158df2e8aba6bcd5fa1fa9ea53876c57274b8c', '2026-04-24 01:34:30'),
(2, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:38:46'),
(3, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:38:47'),
(4, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:38:47'),
(5, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:38:47'),
(6, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:38:47'),
(7, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:38:48'),
(8, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:38:48'),
(9, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:38'),
(10, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:41'),
(11, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:41'),
(12, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:41'),
(13, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:41'),
(14, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:41'),
(15, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:42'),
(16, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:42'),
(17, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:42'),
(18, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:43'),
(19, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:43'),
(20, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:39:43'),
(21, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:55:43'),
(22, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 01:56:16'),
(23, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '128f3cece81c40d4e09884504c2587fa2a4f0d898b24a4dacf06e3e551913d2e', '2026-04-24 03:21:29'),
(24, '/iit/labs/lab04/homepage_updated.html', 'Nikhil Midda | ITWS 1100 (Updated)', NULL, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '6b8df818f5c39bb324bcdfa80110582d9b421f4f1efe7204c6730d54b8aed0cf', '2026-04-24 04:35:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actors`
--
ALTER TABLE `actors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movie_actors`
--
ALTER TABLE `movie_actors`
  ADD PRIMARY KEY (`movie_id`,`actor_id`),
  ADD KEY `actor_id` (`actor_id`);

--
-- Indexes for table `site_visits`
--
ALTER TABLE `site_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_visited_at` (`visited_at`),
  ADD KEY `idx_page_path` (`page_path`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actors`
--
ALTER TABLE `actors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `site_visits`
--
ALTER TABLE `site_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movie_actors`
--
ALTER TABLE `movie_actors`
  ADD CONSTRAINT `movie_actors_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movie_actors_ibfk_2` FOREIGN KEY (`actor_id`) REFERENCES `actors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
