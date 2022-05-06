-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 
-- サーバのバージョン： 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pf`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_image` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `experience` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `members`
--

INSERT INTO `members` (`id`, `user_name`, `password`, `user_image`, `level`, `experience`, `created`, `modified`) VALUES
(1, 'test', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', '20220329140459', 0, 0, '2022-03-29 21:05:03', '2022-03-29 12:05:03'),
(2, 'test2', '109f4b3c50d7b0df729d299bc6f8e9ef9066971f', '20220329142814youngman_26.jpg', 0, 0, '2022-03-29 21:28:18', '2022-03-29 12:28:18'),
(3, 'tanaka', 'c82b6b6f2059dcdb3b2076b2af2ca81901aed3a4', '20220406115845', 0, 0, '2022-04-06 18:58:47', '2022-04-06 09:58:47');

-- --------------------------------------------------------

--
-- テーブルの構造 `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `member_id` int(11) NOT NULL,
  `reply_post_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `posts`
--

INSERT INTO `posts` (`id`, `message`, `member_id`, `reply_post_id`, `created`, `modified`) VALUES
(5, 'test2でぃす！', 2, 0, '2022-03-29 21:37:48', '2022-03-29 12:37:48'),
(6, 'testです', 1, 0, '2022-03-29 21:38:13', '2022-03-29 12:38:13'),
(7, 'test', 3, 0, '2022-04-08 22:41:01', '2022-04-08 13:41:01');

-- --------------------------------------------------------

--
-- テーブルの構造 `quiz_book`
--

CREATE TABLE `quiz_book` (
  `id` int(11) NOT NULL,
  `question` varchar(1000) DEFAULT NULL,
  `choice_a` varchar(500) DEFAULT NULL,
  `choice_b` varchar(500) DEFAULT NULL,
  `choice_c` varchar(500) DEFAULT NULL,
  `answer` varchar(500) DEFAULT NULL,
  `commentary` text,
  `genre` varchar(40) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- テーブルのデータのダンプ `quiz_book`
--

INSERT INTO `quiz_book` (`id`, `question`, `choice_a`, `choice_b`, `choice_c`, `answer`, `commentary`, `genre`, `member_id`, `created`, `modified`) VALUES
(1, '1+1は？', '1', '2', '3', '2', '特になし', '足し算', NULL, NULL, '2022-04-10 13:20:30'),
(2, '2+2は？', '1', '2', '4', '4', 'ないよ', '足し算', NULL, NULL, '2022-04-10 13:20:57'),
(3, 'aを選べ', 'a', 'a', 'a', 'a', 'a', 'a', NULL, '2022-04-09 20:42:59', '2022-04-10 13:22:57'),
(4, '9-4は？', '4', '5', '6', '5', '特になし', '引き算', NULL, '2022-04-09 21:31:04', '2022-04-10 13:22:01'),
(5, '17-9は？', '8', '7', '10', '8', '特になし', '引き算', NULL, '2022-04-09 21:31:04', '2022-04-10 13:23:25'),
(6, 'dd', 'dd', 'dd', 'dd', 'dd', 'dd', 'dd', NULL, '2022-04-09 22:08:01', '2022-04-09 13:08:01'),
(7, 'ee', 'ee', 'ee', 'ee', 'ee', 'ee', 'ee', NULL, '2022-04-10 20:17:36', '2022-04-10 11:17:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_book`
--
ALTER TABLE `quiz_book`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quiz_book`
--
ALTER TABLE `quiz_book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
