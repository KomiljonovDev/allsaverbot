-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 12, 2023 at 09:52 PM
-- Server version: 5.7.21-20-beget-5.7.21-20-1-log
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Database`
--

-- --------------------------------------------------------

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `fromid` int(11) NOT NULL,
  `menu` varchar(20) NOT NULL DEFAULT '',
  `step` varchar(20) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT 'admin',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `fromid`, `menu`, `step`, `status`, `created_at`) VALUES
(1, 931026030, '', '', 'supperadmin', '2023-01-05 08:13:35'),
(7, 1112755577, '', '', 'admin', '2023-01-10 21:17:28'),
(8, 1726396949, '', '', 'admin', '2023-01-10 21:17:51');

-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE `channels` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '1-Kanal',
  `target` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `channels`
--
INSERT INTO `admins` (`id`, `name`, `target`) VALUES
(1, 'status', 'on');

-- --------------------------------------------------------

--
-- Table structure for table `ref_user_videos`
--

CREATE TABLE `ref_user_videos` (
  `user_id` int(11) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `extension` varchar(20) NOT NULL DEFAULT 'mp4'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Table structure for table `sendAd`
--

CREATE TABLE `sendAd` (
  `id` int(11) NOT NULL,
  `chat_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `reply_markup` json NOT NULL,
  `toRus` tinyint(1) NOT NULL DEFAULT '1',
  `toUs` tinyint(1) NOT NULL DEFAULT '1',
  `toUz` tinyint(1) NOT NULL DEFAULT '1',
  `toNotSelectedLang` tinyint(1) NOT NULL DEFAULT '1',
  `toGroup` tinyint(1) NOT NULL DEFAULT '1',
  `sended_count` int(11) NOT NULL DEFAULT '0',
  `sended_user_count` varchar(255) NOT NULL DEFAULT '0',
  `send_confirm` tinyint(1) NOT NULL DEFAULT '0',
  `sending_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sendAd`
--

INSERT INTO `sendAd` (`id`, `chat_id`, `message_id`, `reply_markup`, `toRus`, `toUs`, `toUz`, `toNotSelectedLang`, `toGroup`, `sended_count`, `sended_user_count`, `send_confirm`, `sending_at`) VALUES
(1, 0, 0, 'false', 1, 1, 1, 1, 1, 0, '0', 0, '2023-01-12 23:52:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fromid` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(100) NOT NULL DEFAULT '',
  `chat_type` varchar(255) NOT NULL DEFAULT '',
  `lang` varchar(20) NOT NULL,
  `del` varchar(5) NOT NULL DEFAULT '',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Table structure for table `vid`
--

CREATE TABLE `vid` (
  `id` int(11) NOT NULL,
  `fromid` varchar(255) NOT NULL,
  `url` varchar(500) NOT NULL,
  `file_id` varchar(500) NOT NULL,
  `description` varchar(500) NOT NULL,
  `source` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `down` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `channels`
--
ALTER TABLE `channels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sendAd`
--
ALTER TABLE `sendAd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vid`
--
ALTER TABLE `vid`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `channels`
--
ALTER TABLE `channels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `sendAd`
--
ALTER TABLE `sendAd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `vid`
--
ALTER TABLE `vid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
