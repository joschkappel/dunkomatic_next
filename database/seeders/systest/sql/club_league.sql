SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


INSERT INTO `club_league` (`league_id`, `club_id`, `league_char`, `league_no`, `created_at`, `updated_at`) VALUES
(9, 52, 'A', 1, '2021-11-12 12:47:59', NULL),
(9, 25, 'B', 2, '2021-11-12 12:47:59', NULL),
(9, 26, 'C', 3, '2021-11-12 12:47:59', NULL),
(9, 34, 'D', 4, '2021-11-12 12:47:59', NULL),
(9, 36, 'E', 5, '2021-11-12 12:47:59', NULL),
(9, 49, 'F', 6, '2021-11-12 12:47:59', NULL),
(9, 202, 'G', 7, '2021-11-12 12:47:59', NULL),
(9, 23, 'H', 8, '2021-11-12 12:47:59', NULL),
(9, 50, 'I', 9, '2021-11-12 12:47:59', NULL),
(9, 33, 'K', 10, '2021-11-12 12:47:59', NULL),
(10, 26, 'A', 1, '2021-11-12 12:47:59', NULL),
(10, 25, 'B', 2, '2021-11-12 12:47:59', NULL),
(10, 28, 'C', 3, '2021-11-12 12:47:59', NULL),
(10, 29, 'D', 4, '2021-11-12 12:47:59', NULL),
(10, 27, 'E', 5, '2021-11-12 12:47:59', NULL),
(10, 41, 'F', 6, '2021-11-12 12:47:59', NULL),
(10, 59, 'G', 7, '2021-11-12 12:47:59', NULL),
(10, 60, 'H', 8, '2021-11-12 12:47:59', NULL),
(10, 241, 'I', 9, '2021-11-12 12:47:59', NULL),
(10, 58, 'K', 10, '2021-11-12 12:47:59', NULL),
(11, 59, 'A', 1, '2021-11-12 12:47:59', NULL),
(11, 27, 'C', 3, '2021-11-12 12:47:59', NULL),
(11, 185, 'F', 6, '2021-11-12 12:47:59', NULL),
(11, 29, 'G', 7, '2021-11-12 12:47:59', NULL),
(11, 40, 'I', 9, '2021-11-12 12:47:59', NULL),
(11, 50, 'K', 10, '2021-11-12 12:47:59', NULL),
(12, 25, 'A', 1, '2021-11-12 12:47:59', NULL),
(12, 30, 'B', 2, '2021-11-12 12:47:59', NULL),
(12, 28, 'C', 3, '2021-11-12 12:47:59', NULL),
(12, 42, 'E', 5, '2021-11-12 12:47:59', NULL),
(12, 217, 'F', 6, '2021-11-12 12:47:59', NULL),
(13, 33, 'A', 1, '2021-11-12 12:47:59', NULL),
(13, 247, 'B', 2, '2021-11-12 12:47:59', NULL),
(13, 26, 'C', 3, '2021-11-12 12:47:59', NULL),
(13, 247, 'D', 4, '2021-11-12 12:47:59', NULL),
(13, 23, 'E', 5, '2021-11-12 12:47:59', NULL),
(13, 58, 'F', 6, '2021-11-12 12:47:59', NULL),
(13, 42, 'G', 7, '2021-11-12 12:47:59', NULL),
(13, 59, 'H', 8, '2021-11-12 12:47:59', NULL),
(14, 25, 'A', 1, '2021-11-12 12:47:59', NULL),
(14, 49, 'B', 2, '2021-11-12 12:47:59', NULL),
(14, 37, 'C', 3, '2021-11-12 12:47:59', NULL),
(14, 23, 'D', 4, '2021-11-12 12:47:59', NULL),
(14, 52, 'E', 5, '2021-11-12 12:47:59', NULL),
(14, 33, 'F', 6, '2021-11-12 12:47:59', NULL),
(14, 48, 'G', 7, '2021-11-12 12:47:59', NULL),
(14, 32, 'H', 8, '2021-11-12 12:47:59', NULL),
(14, 235, 'I', 9, '2021-11-12 12:47:59', NULL),
(14, 34, 'K', 10, '2021-11-12 12:47:59', NULL),
(15, 26, 'A', 1, '2021-11-12 12:47:59', NULL),
(15, 33, 'B', 2, '2021-11-12 12:47:59', NULL),
(15, 261, 'C', 3, '2021-11-12 12:47:59', NULL),
(15, 36, 'D', 4, '2021-11-12 12:47:59', NULL),
(15, 56, 'E', 5, '2021-11-12 12:47:59', NULL),
(15, 60, 'F', 6, '2021-11-12 12:47:59', NULL),
(15, 241, 'G', 7, '2021-11-12 12:47:59', NULL),
(15, 42, 'H', 8, '2021-11-12 12:47:59', NULL),
(15, 261, 'I', 9, '2021-11-12 12:47:59', NULL),
(17, 28, 'A', 1, '2021-11-12 12:47:59', NULL),
(17, 52, 'B', 2, '2021-11-12 12:47:59', NULL),
(17, 261, 'C', 3, '2021-11-12 12:47:59', NULL),
(17, 27, 'D', 4, '2021-11-12 12:47:59', NULL),
(17, 60, 'E', 5, '2021-11-12 12:47:59', NULL),
(21, 26, 'B', 2, '2021-11-12 12:47:59', NULL),
(21, 29, 'C', 3, '2021-11-12 12:47:59', NULL),
(21, 42, 'D', 4, '2021-11-12 12:47:59', NULL),
(21, 48, 'E', 5, '2021-11-12 12:47:59', NULL),
(21, 40, 'I', 9, '2021-11-12 12:47:59', NULL),
(21, 253, 'K', 10, '2021-11-12 12:47:59', NULL),
(22, 25, 'A', 1, '2021-11-12 12:47:59', NULL),
(22, 27, 'B', 2, '2021-11-12 12:47:59', NULL),
(22, 40, 'C', 3, '2021-11-12 12:47:59', NULL),
(22, 235, 'D', 4, '2021-11-12 12:47:59', NULL),
(22, 261, 'E', 5, '2021-11-12 12:47:59', NULL),
(22, 52, 'F', 6, '2021-11-12 12:47:59', NULL),
(22, 60, 'G', 7, '2021-11-12 12:47:59', NULL),
(22, 32, 'H', 8, '2021-11-12 12:47:59', NULL),
(22, 59, 'I', 9, '2021-11-12 12:47:59', NULL),
(24, 25, 'A', 1, '2021-11-12 12:47:59', NULL),
(24, 26, 'B', 2, '2021-11-12 12:47:59', NULL),
(24, 27, 'C', 3, '2021-11-12 12:47:59', NULL),
(24, 29, 'D', 4, '2021-11-12 12:47:59', NULL),
(24, 30, 'E', 5, '2021-11-12 12:47:59', NULL),
(24, 42, 'G', 7, '2021-11-12 12:47:59', NULL),
(24, 235, 'H', 8, '2021-11-12 12:47:59', NULL),
(24, 52, 'I', 9, '2021-11-12 12:47:59', NULL),
(24, 261, 'L', 11, '2021-11-12 12:47:59', NULL),
(25, 32, 'A', 1, '2021-11-12 12:47:59', NULL),
(25, 48, 'D', 4, '2021-11-12 12:47:59', NULL),
(25, 59, 'E', 5, '2021-11-12 12:47:59', NULL),
(25, 60, 'F', 6, '2021-11-12 12:47:59', NULL),
(25, 253, 'G', 7, '2021-11-12 12:47:59', NULL),
(33, 23, 'A', 1, '2021-11-12 12:47:59', NULL),
(33, 33, 'B', 2, '2021-11-12 12:47:59', NULL),
(33, 46, 'C', 3, '2021-11-12 12:47:59', NULL),
(33, 52, 'D', 4, '2021-11-12 12:47:59', NULL),
(33, 26, 'E', 5, '2021-11-12 12:47:59', NULL),
(33, 235, 'F', 6, '2021-11-12 12:47:59', NULL),
(33, 50, 'G', 7, '2021-11-12 12:47:59', NULL),
(33, 27, 'H', 8, '2021-11-12 12:47:59', NULL),
(34, 29, 'A', 1, '2021-11-12 12:47:59', NULL),
(34, 27, 'B', 2, '2021-11-12 12:47:59', NULL),
(34, 32, 'C', 3, '2021-11-12 12:47:59', NULL),
(34, 48, 'D', 4, '2021-11-12 12:47:59', NULL),
(34, 59, 'E', 5, '2021-11-12 12:47:59', NULL),
(35, 247, 'A', 1, '2021-11-12 12:47:59', NULL),
(35, 33, 'B', 2, '2021-11-12 12:47:59', NULL),
(35, 42, 'C', 3, '2021-11-12 12:47:59', NULL),
(35, 49, 'E', 5, '2021-11-12 12:47:59', NULL),
(35, 58, 'F', 6, '2021-11-12 12:47:59', NULL),
(35, 60, 'G', 7, '2021-11-12 12:47:59', NULL),
(36, 29, 'B', 2, '2021-11-12 12:47:59', NULL),
(36, 42, 'C', 3, '2021-11-12 12:47:59', NULL),
(36, 46, 'D', 4, '2021-11-12 12:47:59', NULL),
(36, 261, 'E', 5, '2021-11-12 12:47:59', NULL),
(36, 58, 'F', 6, '2021-11-12 12:47:59', NULL),
(36, 60, 'G', 7, '2021-11-12 12:47:59', NULL),
(36, 30, 'I', 9, '2021-11-12 12:47:59', NULL),
(38, 29, 'B', 2, '2021-11-12 12:47:59', NULL),
(38, 30, 'C', 3, '2021-11-12 12:47:59', NULL),
(38, 40, 'D', 4, '2021-11-12 12:47:59', NULL),
(38, 50, 'E', 5, '2021-11-12 12:47:59', NULL),
(38, 56, 'F', 6, '2021-11-12 12:47:59', NULL),
(38, 253, 'G', 7, '2021-11-12 12:47:59', NULL),
(38, 261, 'H', 8, '2021-11-12 12:47:59', NULL),
(41, 23, 'A', 1, '2021-11-12 12:47:59', NULL),
(41, 33, 'B', 2, '2021-11-12 12:47:59', NULL),
(41, 60, 'C', 3, '2021-11-12 12:47:59', NULL),
(41, 27, 'D', 4, '2021-11-12 12:47:59', NULL),
(41, 26, 'E', 5, '2021-11-12 12:47:59', NULL),
(41, 28, 'F', 6, '2021-11-12 12:47:59', NULL),
(41, 49, 'G', 7, '2021-11-12 12:47:59', NULL),
(43, 26, 'A', 1, '2021-11-12 12:47:59', NULL),
(43, 28, 'B', 2, '2021-11-12 12:47:59', NULL),
(43, 27, 'C', 3, '2021-11-12 12:47:59', NULL),
(43, 60, 'D', 4, '2021-11-12 12:47:59', NULL),
(45, 49, 'B', 2, '2021-11-12 12:47:59', NULL),
(45, 261, 'D', 4, '2021-11-12 12:47:59', NULL),
(45, 42, 'F', 6, '2021-11-12 12:47:59', NULL),
(45, 247, 'G', 7, '2021-11-12 12:47:59', NULL),
(45, 60, 'H', 8, '2021-11-12 12:47:59', NULL),
(45, 59, 'K', 10, '2021-11-12 12:47:59', NULL),
(45, 58, 'L', 11, '2021-11-12 12:47:59', NULL),
(46, 60, 'E', 5, '2021-11-12 12:47:59', NULL),
(46, 261, 'F', 6, '2021-11-12 12:47:59', NULL),
(46, 23, 'H', 8, '2021-11-12 12:47:59', NULL),
(46, 52, 'K', 10, '2021-11-12 12:47:59', NULL),
(47, 26, 'D', 4, '2021-11-12 12:47:59', NULL),
(47, 68, 'F', 6, '2021-11-12 12:47:59', NULL),
(47, 50, 'K', 10, '2021-11-12 12:47:59', NULL),
(48, 28, 'A', 1, '2021-11-12 12:47:59', NULL),
(48, 23, 'B', 2, '2021-11-12 12:47:59', NULL),
(48, 58, 'C', 3, '2021-11-12 12:47:59', NULL),
(48, 68, 'G', 7, '2021-11-12 12:47:59', NULL),
(48, 26, 'K', 10, '2021-11-12 12:47:59', NULL),
(54, 49, 'A', 1, '2021-11-12 12:47:59', NULL),
(54, 68, 'H', 8, '2021-11-12 12:47:59', NULL),
(55, 60, 'C', 3, '2021-11-12 12:47:59', NULL),
(56, 30, 'A', 1, '2021-11-12 12:47:59', NULL),
(56, 60, 'C', 3, '2021-11-12 12:47:59', NULL),
(56, 68, 'E', 5, '2021-11-12 12:47:59', NULL),
(56, 26, 'F', 6, '2021-11-12 12:47:59', NULL),
(57, 26, 'A', 1, '2021-11-12 12:47:59', NULL),
(57, 60, 'B', 2, '2021-11-12 12:48:00', NULL),
(58, 42, 'C', 3, '2021-11-12 12:48:00', NULL),
(58, 247, 'D', 4, '2021-11-12 12:48:00', NULL),
(58, 49, 'H', 8, '2021-11-12 12:48:00', NULL),
(58, 60, 'I', 9, '2021-11-12 12:48:00', NULL),
(59, 58, 'A', 1, '2021-11-12 12:48:00', NULL),
(59, 247, 'C', 3, '2021-11-12 12:48:00', NULL),
(59, 76, 'F', 6, '2021-11-12 12:48:00', NULL),
(59, 60, 'H', 8, '2021-11-12 12:48:00', NULL),
(79, 247, 'A', 1, '2021-11-12 12:48:00', NULL),
(79, 49, 'B', 2, '2021-11-12 12:48:00', NULL),
(79, 60, 'F', 6, '2021-11-12 12:48:00', NULL),
(116, 68, 'A', 1, '2021-11-12 12:48:00', NULL),
(116, 68, 'B', 2, '2021-11-12 12:48:00', NULL),
(116, 76, 'C', 3, '2021-11-12 12:48:00', NULL),
(116, 84, 'D', 4, '2021-11-12 12:48:00', NULL),
(116, 70, 'E', 5, '2021-11-12 12:48:00', NULL),
(116, 71, 'F', 6, '2021-11-12 12:48:00', NULL),
(116, 78, 'G', 7, '2021-11-12 12:48:00', NULL),
(116, 72, 'H', 8, '2021-11-12 12:48:00', NULL),
(116, 85, 'I', 9, '2021-11-12 12:48:00', NULL),
(117, 68, 'A', 1, '2021-11-12 12:48:00', NULL),
(117, 68, 'B', 2, '2021-11-12 12:48:00', NULL),
(117, 76, 'C', 3, '2021-11-12 12:48:00', NULL),
(117, 75, 'D', 4, '2021-11-12 12:48:00', NULL),
(117, 72, 'E', 5, '2021-11-12 12:48:00', NULL),
(158, 76, 'A', 1, '2021-11-12 12:48:00', NULL),
(158, 70, 'B', 2, '2021-11-12 12:48:00', NULL),
(158, 68, 'C', 3, '2021-11-12 12:48:00', NULL),
(158, 68, 'D', 4, '2021-11-12 12:48:00', NULL),
(158, 71, 'E', 5, '2021-11-12 12:48:00', NULL),
(158, 72, 'F', 6, '2021-11-12 12:48:00', NULL),
(158, 82, 'H', 8, '2021-11-12 12:48:00', NULL),
(158, 74, 'I', 9, '2021-11-12 12:48:00', NULL),
(158, 74, 'K', 10, '2021-11-12 12:48:00', NULL),
(161, 73, 'A', 1, '2021-11-12 12:48:00', NULL),
(161, 78, 'B', 2, '2021-11-12 12:48:00', NULL),
(161, 68, 'C', 3, '2021-11-12 12:48:00', NULL),
(161, 72, 'D', 4, '2021-11-12 12:48:00', NULL),
(161, 85, 'E', 5, '2021-11-12 12:48:00', NULL),
(161, 76, 'F', 6, '2021-11-12 12:48:00', NULL),
(162, 68, 'A', 1, '2021-11-12 12:48:00', NULL),
(162, 73, 'B', 2, '2021-11-12 12:48:00', NULL),
(162, 72, 'C', 3, '2021-11-12 12:48:00', NULL),
(162, 85, 'D', 4, '2021-11-12 12:48:00', NULL),
(162, 76, 'E', 5, '2021-11-12 12:48:00', NULL),
(163, 23, 'A', 1, '2021-11-12 12:48:00', NULL),
(163, 33, 'B', 2, '2021-11-12 12:48:00', NULL),
(163, 29, 'C', 3, '2021-11-12 12:48:00', NULL),
(163, 26, 'E', 5, '2021-11-12 12:48:00', NULL),
(163, 42, 'F', 6, '2021-11-12 12:48:00', NULL),
(163, 261, 'G', 7, '2021-11-12 12:48:00', NULL),
(163, 49, 'H', 8, '2021-11-12 12:48:00', NULL),
(163, 59, 'I', 9, '2021-11-12 12:48:00', NULL),
(168, 68, 'A', 1, '2021-11-12 12:48:00', NULL),
(168, 72, 'B', 2, '2021-11-12 12:48:00', NULL),
(168, 74, 'C', 3, '2021-11-12 12:48:00', NULL),
(168, 84, 'D', 4, '2021-11-12 12:48:00', NULL),
(169, 68, 'A', 1, '2021-11-12 12:48:00', NULL),
(169, 68, 'B', 2, '2021-11-12 12:48:00', NULL),
(169, 84, 'C', 3, '2021-11-12 12:48:00', NULL),
(169, 76, 'D', 4, '2021-11-12 12:48:00', NULL),
(169, 72, 'E', 5, '2021-11-12 12:48:00', NULL),
(169, 72, 'F', 6, '2021-11-12 12:48:00', NULL),
(189, 60, 'A', 1, '2021-11-12 12:48:00', NULL),
(189, 33, 'C', 3, '2021-11-12 12:48:00', NULL),
(189, 56, 'E', 5, '2021-11-12 12:48:00', NULL),
(189, 60, 'F', 6, '2021-11-12 12:48:00', NULL),
(190, 25, 'A', 1, '2021-11-12 12:48:00', NULL),
(190, 27, 'B', 2, '2021-11-12 12:48:00', NULL),
(190, 33, 'C', 3, '2021-11-12 12:48:00', NULL),
(190, 48, 'D', 4, '2021-11-12 12:48:00', NULL),
(190, 52, 'E', 5, '2021-11-12 12:48:00', NULL),
(190, 60, 'G', 7, '2021-11-12 12:48:00', NULL),
(190, 42, 'H', 8, '2021-11-12 12:48:00', NULL),
(195, 72, 'B', 2, '2021-11-12 12:48:00', NULL),
(196, 73, 'A', 1, '2021-11-12 12:48:00', NULL),
(196, 68, 'B', 2, '2021-11-12 12:48:00', NULL),
(196, 68, 'C', 3, '2021-11-12 12:48:00', NULL),
(199, 247, 'A', 1, '2021-11-12 12:48:00', NULL),
(199, 33, 'B', 2, '2021-11-12 12:48:00', NULL),
(199, 49, 'D', 4, '2021-11-12 12:48:00', NULL),
(199, 58, 'E', 5, '2021-11-12 12:48:00', NULL),
(199, 60, 'F', 6, '2021-11-12 12:48:00', NULL),
(209, 247, 'A', 1, '2021-11-12 12:48:00', NULL),
(209, 29, 'B', 2, '2021-11-12 12:48:00', NULL),
(209, 33, 'C', 3, '2021-11-12 12:48:00', NULL),
(209, 42, 'E', 5, '2021-11-12 12:48:00', NULL),
(209, 261, 'F', 6, '2021-11-12 12:48:00', NULL),
(209, 58, 'G', 7, '2021-11-12 12:48:00', NULL),
(209, 60, 'H', 8, '2021-11-12 12:48:00', NULL),
(209, 59, 'I', 9, '2021-11-12 12:48:00', NULL),
(209, 63, 'K', 10, '2021-11-12 12:48:00', NULL),
(211, 84, 'A', 1, '2021-11-12 12:48:00', NULL),
(211, 68, 'B', 2, '2021-11-12 12:48:00', NULL),
(211, 68, 'C', 3, '2021-11-12 12:48:00', NULL),
(211, 72, 'D', 4, '2021-11-12 12:48:00', NULL),
(211, 72, 'E', 5, '2021-11-12 12:48:00', NULL),
(211, 78, 'F', 6, '2021-11-12 12:48:00', NULL),
(211, 76, 'G', 7, '2021-11-12 12:48:00', NULL),
(212, 68, 'A', 1, '2021-11-12 12:48:00', NULL),
(212, 72, 'B', 2, '2021-11-12 12:48:00', NULL),
(212, 68, 'C', 3, '2021-11-12 12:48:00', NULL),
(219, 58, 'A', 1, '2021-11-12 12:48:00', NULL),
(219, 33, 'E', 5, '2021-11-12 12:48:00', NULL),
(242, 60, 'F', 6, '2021-11-12 12:48:00', NULL),
(242, 42, 'G', 7, '2021-11-12 12:48:00', NULL),
(250, 56, 'A', 1, '2021-11-12 12:48:01', NULL),
(250, 25, 'B', 2, '2021-11-12 12:48:01', NULL),
(250, 28, 'C', 3, '2021-11-12 12:48:01', NULL),
(251, 25, 'A', 1, '2021-11-12 12:48:01', NULL),
(251, 27, 'B', 2, '2021-11-12 12:48:01', NULL),
(251, 29, 'C', 3, '2021-11-12 12:48:01', NULL),
(251, 42, 'D', 4, '2021-11-12 12:48:01', NULL),
(251, 50, 'E', 5, '2021-11-12 12:48:01', NULL),
(261, 33, 'A', 1, '2021-11-12 12:48:01', NULL),
(261, 42, 'B', 2, '2021-11-12 12:48:01', NULL),
(273, 56, 'A', 1, '2021-11-12 12:48:01', NULL),
(273, 49, 'B', 2, '2021-11-12 12:48:01', NULL),
(273, 23, 'H', 8, '2021-11-12 12:48:01', NULL),
(288, 23, 'A', 1, '2021-11-12 12:48:01', NULL),
(288, 49, 'B', 2, '2021-11-12 12:48:01', NULL),
(288, 68, 'E', 5, '2021-11-12 12:48:01', NULL),
(290, 60, 'C', 3, '2021-11-12 12:48:01', NULL),
(290, 23, 'E', 5, '2021-11-12 12:48:01', NULL),
(290, 26, 'G', 7, '2021-11-12 12:48:01', NULL),
(291, 26, 'A', 1, '2021-11-12 12:48:01', NULL),
(291, 33, 'B', 2, '2021-11-12 12:48:01', NULL),
(291, 49, 'C', 3, '2021-11-12 12:48:01', NULL),
(292, 33, 'A', 1, '2021-11-12 12:48:01', NULL),
(292, 49, 'B', 2, '2021-11-12 12:48:01', NULL),
(295, 25, 'A', 1, '2021-11-12 12:48:01', NULL),
(295, 30, 'B', 2, '2021-11-12 12:48:01', NULL),
(295, 36, 'C', 3, '2021-11-12 12:48:01', NULL),
(295, 40, 'D', 4, '2021-11-12 12:48:01', NULL),
(295, 261, 'E', 5, '2021-11-12 12:48:01', NULL),
(295, 60, 'F', 6, '2021-11-12 12:48:01', NULL),
(295, 56, 'G', 7, '2021-11-12 12:48:01', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
