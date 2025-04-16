-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 15, 2025 at 02:25 PM
-- Server version: 10.6.21-MariaDB-cll-lve
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zkzfoakd_actpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `clicks`
--

CREATE TABLE `clicks` (
  `id` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  `offer_id` varchar(150) NOT NULL,
  `is_converted` int(11) NOT NULL DEFAULT 0 COMMENT '1= true, 0 = false',
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `clicks`
--
DELIMITER $$
CREATE TRIGGER `after_clicks_insert2` AFTER INSERT ON `clicks` FOR EACH ROW BEGIN
    UPDATE stats SET clicks = clicks + 1 WHERE date = NEW.date;
    UPDATE offers SET offers.clicks = offers.clicks + 1 WHERE offers.id = NEW.offer_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `conv`
--

CREATE TABLE `conv` (
  `id` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  `offer_id` varchar(150) NOT NULL,
  `payout` float NOT NULL,
  `network` varchar(100) NOT NULL,
  `source` varchar(50) NOT NULL,
  `medium` varchar(50) NOT NULL,
  `click_id` text NOT NULL,
  `zone_id` text NOT NULL,
  `ip` varchar(100) NOT NULL,
  `country` varchar(50) NOT NULL,
  `ua` text NOT NULL,
  `referer` varchar(100) NOT NULL COMMENT 'referer host',
  `date` date NOT NULL DEFAULT current_timestamp(),
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `conv`
--
DELIMITER $$
CREATE TRIGGER `after_conversion_insert2` AFTER INSERT ON `conv` FOR EACH ROW BEGIN
    -- Update or insert into stats table
    IF EXISTS (SELECT 1 FROM stats WHERE date = NEW.date) THEN
        -- Update existing row in stats
        UPDATE stats 
        SET conversion = conversion + 1,
            earnings = earnings + NEW.payout,
            cr = CONCAT(ROUND(conversion/visitor*100, 2)),
            epc = CONCAT(ROUND(earnings/clicks, 2))
        WHERE date = NEW.date;
    ELSE
        -- Insert new row in stats
        INSERT INTO stats (date, conversion, earnings, cr, epc)
        VALUES (NEW.date, 1, NEW.payout, 0, 0);
    END IF;
    
    -- Update offers table (added this part)
    UPDATE offers 
    SET conversion = conversion + 1,
        revenue = revenue + NEW.payout
    WHERE id = NEW.offer_id;
    UPDATE clicks SET is_converted = 1 WHERE id = NEW.click_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(50) NOT NULL,
  `icon` text DEFAULT NULL,
  `link` text NOT NULL,
  `time` varchar(50) NOT NULL,
  `network` varchar(50) NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT 0,
  `conversion` int(11) NOT NULL DEFAULT 0,
  `revenue` float NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0 COMMENT '0 = inactive, 1 = active',
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `offers`
--

INSERT INTO `offers` (`id`, `title`, `description`, `category`, `icon`, `link`, `time`, `network`, `clicks`, `conversion`, `revenue`, `status`, `date`) VALUES
(61, 'Quick Surveys = Rewards!', 'Complete quick surveys &amp; earn real money today!', 'Survey', 'bi-clipboard2-data', 'https://015467.shop/8a63d4ceb9bdc43c6dff/808f798eac/?placementName={{click_id}}', '10 seconds', 'advertica', 9, 963, 0, 1, '2025-04-15 02:35:37'),
(62, '2048 Challenge!', 'Score 1000 in 2048 &amp; unlock your reward now!', 'Games', 'bi-controller', 'https://aolgames.xyz/?click_id={{click_id}}', '30 seconds', 'actpro', 7, 543, 0.04, 1, '2025-04-15 02:34:38'),
(63, 'Enter &amp; Win Prizes!', 'Complete offers for a chance to win amazing prizes!', 'Sweepstakes', 'bi-gift', 'https://015467.shop/853e96bdd0466824d2eb/5999ae9da7/?placementName={{click_id}}', '30 seconds', 'advertica', 1, 232, 0, 1, '2025-04-15 02:34:42'),
(64, 'Contest Craze!', 'Compete now for cash, gifts &amp; more!', 'Contests', 'bi-trophy', 'https://015467.shop/5663f7ea32fe3a413b27/566ea9812a/?placementName={{click_id}}', '30 seconds', 'advertica', 1, 878, 0, 1, '2025-04-15 02:35:13'),
(65, 'Play Games &amp; Win!', 'Subscribe via SMS, play games &amp; claim rewards!', 'Games', 'bi-controller', 'https://015467.shop/1d109e38c0327a2eb254/e95ed0922f/?placementName={{click_id}}', '20 seconds', 'advertica', 1, 233, 0, 1, '2025-04-15 02:34:55'),
(66, 'Score &amp; Win!', 'SMS required - unlock exclusive sports prizes!', 'Sports', 'bi-trophy-fill', 'https://015467.shop/6b9522b5fd7e7c7a36d0/3510c9cfd4/?placementName={{click_id}}', '20 seconds', 'advertica', 1, 465, 0, 1, '2025-04-15 02:35:18'),
(67, 'Watch &amp;amp; Win!', 'Subscribe via SMS, watch videos &amp;amp; earn rewards!', 'Watch Videos', 'bi-play-circle', 'https://015467.shop/bb3f4cd782ac42b3f609/ac3db79c74/?placementName={{click_id}}', '20 seconds', 'advertica', 1, 327, 0, 1, '2025-04-15 02:35:27');

-- --------------------------------------------------------

--
-- Table structure for table `stats`
--

CREATE TABLE `stats` (
  `id` int(11) NOT NULL,
  `visitor` int(11) NOT NULL DEFAULT 0,
  `clicks` int(11) NOT NULL DEFAULT 0,
  `conversion` int(11) NOT NULL DEFAULT 0,
  `earnings` float NOT NULL DEFAULT 0,
  `cr` float NOT NULL DEFAULT 0,
  `epc` float NOT NULL DEFAULT 0,
  `cost` float NOT NULL DEFAULT 0,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor`
--

CREATE TABLE `visitor` (
  `id` int(11) NOT NULL,
  `source` varchar(50) NOT NULL,
  `medium` varchar(50) NOT NULL,
  `zone_id` text NOT NULL,
  `cost` float NOT NULL DEFAULT 0,
  `ip` varchar(100) NOT NULL,
  `country` varchar(50) NOT NULL,
  `ua` text NOT NULL,
  `referer` varchar(100) NOT NULL COMMENT 'referer host',
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Triggers `visitor`
--
DELIMITER $$
CREATE TRIGGER `after_visitor_insert` AFTER INSERT ON `visitor` FOR EACH ROW IF EXISTS (SELECT 1 FROM stats WHERE date = NEW.date) THEN
        -- Update existing row
        UPDATE stats 
        SET visitor = visitor + 1,
        cost = ROUND(cost + NEW.cost, 4)
        WHERE date = NEW.date;
    ELSE
        -- Insert new row
        INSERT INTO stats (date, visitor, cost)
        VALUES (NEW.date, 1, NEW.cost);
    END IF
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `clicks`
--
ALTER TABLE `clicks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conv`
--
ALTER TABLE `conv`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stats`
--
ALTER TABLE `stats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor`
--
ALTER TABLE `visitor`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clicks`
--
ALTER TABLE `clicks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63407;

--
-- AUTO_INCREMENT for table `conv`
--
ALTER TABLE `conv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9673;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `stats`
--
ALTER TABLE `stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `visitor`
--
ALTER TABLE `visitor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158180;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
