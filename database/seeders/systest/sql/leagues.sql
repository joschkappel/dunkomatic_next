SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


INSERT INTO `leagues` (`id`, `region_id`, `shortname`, `name`, `above_region`, `league_size_id`, `schedule_id`, `age_type`, `gender_type`, `state`, `assignment_closed_at`, `registration_closed_at`, `selection_opened_at`, `selection_closed_at`, `generated_at`, `scheduling_closed_at`, `referees_closed_at`, `created_at`, `updated_at`) VALUES
(9, 2, 'HBL', 'Herren Bezirksliga', 0, 5, 7, 0, 0, 6, '2021-11-12 12:48:16', '2021-11-12 12:48:17', NULL, NULL, '2021-11-12 12:48:17', '2021-11-12 12:48:18', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:18'),
(10, 2, 'HKA', 'Herren Kreisklasse A', 0, 5, 7, 0, 0, 6, '2021-11-12 12:48:18', '2021-11-12 12:48:19', NULL, NULL, '2021-11-12 12:48:20', '2021-11-12 12:48:21', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:21'),
(11, 2, 'HC1', 'Herren Kreisklasse C, Grp1', 0, 5, 7, 0, 0, 6, '2021-11-12 12:48:21', '2021-11-12 12:48:21', NULL, NULL, '2021-11-12 12:48:22', '2021-11-12 12:48:22', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:22'),
(12, 2, 'HC2', 'Herren Kreisklasse C, Grp2', 0, 5, 7, 0, 0, 6, '2021-11-12 12:48:22', '2021-11-12 12:48:22', NULL, NULL, '2021-11-12 12:48:23', '2021-11-12 12:48:23', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:23'),
(13, 2, 'DBL', 'Damen Bezirksliga', 0, 5, 7, 0, 1, 6, '2021-11-12 12:48:23', '2021-11-12 12:48:24', NULL, NULL, '2021-11-12 12:48:24', '2021-11-12 12:48:25', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:25'),
(14, 2, 'HB1', 'Herren Kreisklasse B, Grp1', 0, 5, 7, 0, 0, 6, '2021-11-12 12:48:25', '2021-11-12 12:48:26', NULL, NULL, '2021-11-12 12:48:26', '2021-11-12 12:48:27', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:27'),
(15, 2, 'HB2', 'Herren Kreisklasse B, Grp2', 0, 5, 7, 0, 0, 6, '2021-11-12 12:48:27', '2021-11-12 12:48:28', NULL, NULL, '2021-11-12 12:48:28', '2021-11-12 12:48:29', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:29'),
(17, 2, 'DK1', 'Damen Kreisklasse, Grp1', 0, 5, 7, 0, 1, 6, '2021-11-12 12:48:29', '2021-11-12 12:48:29', NULL, NULL, '2021-11-12 12:48:30', '2021-11-12 12:48:30', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:30'),
(21, 2, 'MU18 BZ1', 'Bezirksliga, männl. Jgd. U18, Grp 1', 0, 5, 7, 1, 0, 6, '2021-11-12 12:48:30', '2021-11-12 12:48:31', NULL, NULL, '2021-11-12 12:48:31', '2021-11-12 12:48:32', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:32'),
(22, 2, 'MU18 BZ2', 'Bezirkisliga, männl. Jgd. U18 Grp2', 0, 5, 7, 1, 0, 6, '2021-11-12 12:48:32', '2021-11-12 12:48:32', NULL, NULL, '2021-11-12 12:48:33', '2021-11-12 12:48:34', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:34'),
(24, 2, 'MU16 BZ', 'Bezirksliga, männl. Jgd. U16', 0, 5, 7, 1, 0, 6, '2021-11-12 12:48:34', '2021-11-12 12:48:34', NULL, NULL, '2021-11-12 12:48:35', '2021-11-12 12:48:36', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:36'),
(25, 2, 'MU16 KL1', 'Kreisliga, männl. Jgd. U16', 0, 5, 7, 1, 0, 6, '2021-11-12 12:48:36', '2021-11-12 12:48:36', NULL, NULL, '2021-11-12 12:48:36', '2021-11-12 12:48:37', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:37'),
(33, 2, 'MU14 BZ', 'Bezirksliga Jgd. U14', 0, 5, 7, 1, 0, 6, '2021-11-12 12:48:37', '2021-11-12 12:48:37', NULL, NULL, '2021-11-12 12:48:38', '2021-11-12 12:48:38', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:38'),
(34, 2, 'MU14 KL2', 'Kreisliga, männl. Jgd. U14 Gruppe 2', 0, 5, 7, 1, 0, 6, '2021-11-12 12:48:38', '2021-11-12 12:48:39', NULL, NULL, '2021-11-12 12:48:39', '2021-11-12 12:48:40', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:40'),
(35, 2, 'WU12 BZ', 'Bezirksliga, weibl. Jgd. U12', 0, 4, 3, 1, 1, 6, '2021-11-12 12:48:40', '2021-11-12 12:48:40', NULL, NULL, '2021-11-12 12:48:40', '2021-11-12 12:48:41', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:41'),
(36, 2, 'WU16 BZ', 'Bezirksliga weibl. Jgd. U16', 0, 5, 7, 1, 1, 6, '2021-11-12 12:48:41', '2021-11-12 12:48:41', NULL, NULL, '2021-11-12 12:48:42', '2021-11-12 12:48:42', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:42'),
(38, 2, 'MU12 KL2', 'Kreisliga, U12 Grp2', 0, 4, 3, 1, 1, 6, '2021-11-12 12:48:42', '2021-11-12 12:48:43', NULL, NULL, '2021-11-12 12:48:43', '2021-11-12 12:48:44', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:44'),
(41, 2, 'MU10 BZ', 'U10 Bezirksliga', 0, 4, 3, 1, 1, 6, '2021-11-12 12:48:44', '2021-11-12 12:48:45', NULL, NULL, '2021-11-12 12:48:45', '2021-11-12 12:48:46', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:46'),
(43, 2, 'MU12 BZ', 'Bezirksliga, mix Jgd., U12 Bezirksliga', 0, 4, 3, 1, 1, 6, '2021-11-12 12:48:46', '2021-11-12 12:48:46', NULL, NULL, '2021-11-12 12:48:46', '2021-11-12 12:48:47', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:47'),
(45, 1, 'LSD', 'Landesliga Süd Damen', 0, 6, 6, 0, 1, 6, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, NULL, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:47'),
(46, 1, 'LSH', 'Landesliga Süd Herren', 0, 5, 7, 0, 0, 6, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, NULL, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:47'),
(47, 1, 'OLH', 'Oberliga Herren', 0, 5, 7, 0, 0, 6, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, NULL, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:47'),
(48, 1, 'OLD', 'Oberliga Damen', 0, 5, 7, 0, 1, 6, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, NULL, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:47'),
(50, 1, 'RLD', 'Regionalliga Damen Südwest/Nord', 0, 6, NULL, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-12 12:47:59', NULL),
(53, 1, '2.RLSW/N', '2.Regionalliga Herren Südwest/Nord', 0, 6, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-12 12:47:59', NULL),
(54, 1, 'OM14 A', 'Oberliga männl. Jugend U14 A', 0, 4, 9, 1, 0, 6, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, NULL, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:47'),
(55, 1, 'OM16 A', 'Oberliga männl. Jugend U16 A', 0, 4, 14, 1, 0, 6, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, NULL, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:47'),
(56, 1, 'OM18 A', 'Oberliga männl. Jugend U18 A', 0, 5, 15, 1, 0, 6, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, NULL, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:47'),
(57, 1, 'OX12 A', 'Oberliga mixed Jugend U12 A', 0, 4, 16, 1, 0, 5, '2021-11-12 12:48:47', '2021-11-12 12:48:47', NULL, NULL, '2021-11-12 12:48:47', NULL, NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:47'),
(58, 1, 'OW16', 'Oberliga weibl. Jugend U16', 0, 5, 4, 1, 1, 6, '2021-11-12 12:48:48', '2021-11-12 12:48:48', NULL, NULL, '2021-11-12 12:48:48', '2021-11-12 12:48:48', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:48'),
(59, 1, 'OW18', 'Oberliga weibl. Jugend U18', 0, 5, 5, 1, 1, 6, '2021-11-12 12:48:48', '2021-11-12 12:48:48', NULL, NULL, '2021-11-12 12:48:48', '2021-11-12 12:48:48', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:48'),
(79, 1, 'OW14', 'Oberliga weibl. Jugend U14', 0, 4, 2, 1, 1, 6, '2021-11-12 12:48:48', '2021-11-12 12:48:48', NULL, NULL, '2021-11-12 12:48:48', '2021-11-12 12:48:48', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:48'),
(82, 1, 'RLSW-H', '1.Regionalliga Südwest Herren', 0, 8, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-12 12:47:59', NULL),
(116, 4, 'BH-KS', 'Bezirksliga Herren Kassel', 0, 5, 7, 0, 0, 6, '2021-11-12 12:48:49', '2021-11-12 12:48:49', NULL, NULL, '2021-11-12 12:48:49', '2021-11-12 12:48:49', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:49'),
(117, 4, 'BD-KS', 'Bezirksliga Damen Kassel', 0, 5, 7, 0, 1, 6, '2021-11-12 12:48:49', '2021-11-12 12:48:49', NULL, NULL, '2021-11-12 12:48:49', '2021-11-12 12:48:49', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:49'),
(158, 4, 'KH-KS1', 'Herren Kreisliga Gruppe 1', 0, 5, 7, 0, 0, 6, '2021-11-12 12:48:49', '2021-11-12 12:48:49', NULL, NULL, '2021-11-12 12:48:49', '2021-11-12 12:48:49', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:49'),
(161, 4, 'BM18-KS', 'MU18', 0, 5, 7, 1, 0, 6, '2021-11-12 12:48:49', '2021-11-12 12:48:49', NULL, NULL, '2021-11-12 12:48:49', '2021-11-12 12:48:49', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:49'),
(162, 4, 'BW18-KS', 'WU18', 0, 5, 7, 1, 1, 6, '2021-11-12 12:48:49', '2021-11-12 12:48:49', NULL, NULL, '2021-11-12 12:48:49', '2021-11-12 12:48:49', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:49'),
(163, 2, 'WU18 BZ', 'weibl. U18, Bezirksliga', 0, 5, 7, 1, 1, 6, '2021-11-12 12:48:49', '2021-11-12 12:48:50', NULL, NULL, '2021-11-12 12:48:51', '2021-11-12 12:48:51', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:51'),
(164, 2, 'MÜ35', 'Herren Ü35', 0, 4, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-12 12:47:59', NULL),
(165, 2, 'MÜ40', 'Herren Ü40', 0, 2, NULL, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-12 12:47:59', NULL),
(166, 2, 'WÜ35', 'Damen Ü35', 0, 2, NULL, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-12 12:47:59', NULL),
(167, 2, 'WÜ40', 'Damen Ü40', 0, 2, NULL, 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-11-12 12:47:59', NULL),
(168, 4, 'BM16-KS', 'MU16', 0, 2, 26, 1, 0, 6, '2021-11-12 12:48:51', '2021-11-12 12:48:51', NULL, NULL, '2021-11-12 12:48:51', '2021-11-12 12:48:51', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:51'),
(169, 4, 'U14-KS', 'U14', 0, 5, 7, 1, 0, 6, '2021-11-12 12:48:51', '2021-11-12 12:48:51', NULL, NULL, '2021-11-12 12:48:51', '2021-11-12 12:48:51', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:51'),
(189, 2, 'MU10 KL1', 'männl. U10 Kreisliga Gruppe 1', 0, 4, 3, 0, 0, 6, '2021-11-12 12:48:52', '2021-11-12 12:48:52', NULL, NULL, '2021-11-12 12:48:52', '2021-11-12 12:48:52', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:52'),
(190, 2, 'MU12 KL1', 'Kreisliga, U12 Grp1', 0, 4, 3, 1, 0, 6, '2021-11-12 12:48:52', '2021-11-12 12:48:53', NULL, NULL, '2021-11-12 12:48:53', '2021-11-12 12:48:54', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:54'),
(195, 1, 'LND', 'Landesliga Nord Damen', 0, 5, 7, 0, 1, 6, '2021-11-12 12:48:54', '2021-11-12 12:48:54', NULL, NULL, '2021-11-12 12:48:54', '2021-11-12 12:48:54', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:54'),
(196, 1, 'LNH', 'Landesliga Nord Herren', 0, 6, 6, 0, 0, 6, '2021-11-12 12:48:54', '2021-11-12 12:48:54', NULL, NULL, '2021-11-12 12:48:54', '2021-11-12 12:48:54', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:54'),
(199, 2, 'WU10 BZ', 'weibl. U10 Bezirksliga', 0, 4, 3, 1, 1, 6, '2021-11-12 12:48:54', '2021-11-12 12:48:55', NULL, NULL, '2021-11-12 12:48:55', '2021-11-12 12:48:55', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:55'),
(209, 2, 'WU14 BZ', 'weibliche U14 Bezirksliga', 0, 5, 7, 1, 1, 6, '2021-11-12 12:48:55', '2021-11-12 12:48:56', NULL, NULL, '2021-11-12 12:48:57', '2021-11-12 12:48:57', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:57'),
(211, 4, 'U12-KS', 'U12 Mixed', 0, 5, 7, 1, 1, 6, '2021-11-12 12:48:57', '2021-11-12 12:48:57', NULL, NULL, '2021-11-12 12:48:57', '2021-11-12 12:48:57', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:57'),
(212, 4, 'U10-KS', 'U10 Mixed', 0, 2, 26, 1, 1, 6, '2021-11-12 12:48:57', '2021-11-12 12:48:57', NULL, NULL, '2021-11-12 12:48:57', '2021-11-12 12:48:57', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:57'),
(219, 1, 'LW16', 'Landesliga weibl. Jugend U16', 0, 4, 28, 1, 1, 6, '2021-11-12 12:48:57', '2021-11-12 12:48:57', NULL, NULL, '2021-11-12 12:48:57', '2021-11-12 12:48:57', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:57'),
(242, 1, 'LM14', 'Landesliga MU14', 0, 5, 32, 1, 0, 5, '2021-11-12 12:48:58', '2021-11-12 12:48:58', NULL, NULL, '2021-11-12 12:48:58', NULL, NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:58'),
(250, 1, 'LM18', 'Landesliga männl. U18', 0, 5, 33, 1, 0, 6, '2021-11-12 12:48:58', '2021-11-12 12:48:58', NULL, NULL, '2021-11-12 12:48:58', '2021-11-12 12:48:58', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:58'),
(251, 2, 'MU10 KL2', 'U10 Kreisrunde Gruppe 2', 0, 4, 3, 1, 1, 6, '2021-11-12 12:48:58', '2021-11-12 12:48:58', NULL, NULL, '2021-11-12 12:48:58', '2021-11-12 12:48:59', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:59'),
(261, 1, 'LX 12', 'Landesliga mixed Jugend U12', 0, 4, 31, 1, 0, 6, '2021-11-12 12:48:59', '2021-11-12 12:48:59', NULL, NULL, '2021-11-12 12:48:59', '2021-11-12 12:48:59', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:59'),
(273, 1, 'LM16', 'Landesliga männl. U16', 0, 4, 29, 1, 0, 6, '2021-11-12 12:48:59', '2021-11-12 12:48:59', NULL, NULL, '2021-11-12 12:48:59', '2021-11-12 12:48:59', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:59'),
(288, 1, 'OX12 B', 'Oberliga mixed Jugend U12 B', 0, 4, 16, 1, 0, 6, '2021-11-12 12:48:59', '2021-11-12 12:48:59', NULL, NULL, '2021-11-12 12:48:59', '2021-11-12 12:48:59', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:59'),
(290, 1, 'OM14 B', 'Oberliga männl. Jugend  U14 B', 0, 4, 9, 1, 0, 6, '2021-11-12 12:48:59', '2021-11-12 12:48:59', NULL, NULL, '2021-11-12 12:48:59', '2021-11-12 12:48:59', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:59'),
(291, 1, 'OM16 B', 'Oberliga männl. Jugend U16 B', 0, 4, 14, 1, 0, 5, '2021-11-12 12:48:59', '2021-11-12 12:48:59', NULL, NULL, '2021-11-12 12:48:59', NULL, NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:59'),
(292, 1, 'OM18 B', 'Oberliga männl. Jugend U18 B', 0, 5, 15, 1, 0, 6, '2021-11-12 12:48:59', '2021-11-12 12:48:59', NULL, NULL, '2021-11-12 12:48:59', '2021-11-12 12:48:59', NULL, '2021-11-12 12:47:59', '2021-11-12 12:48:59'),
(295, 2, 'MU14 KL1', 'Kreisliga, männl. Jgd. U14 Gruppe 1', 0, 5, 7, 1, 0, 6, '2021-11-12 12:49:00', '2021-11-12 12:49:00', NULL, NULL, '2021-11-12 12:49:01', '2021-11-12 12:49:02', NULL, '2021-11-12 12:47:59', '2021-11-12 12:49:02');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;