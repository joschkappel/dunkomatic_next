SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Dumping data for table `leagues`
--

INSERT INTO `leagues` (`id`, `region_id`, `shortname`, `name`, `league_size_id`, `schedule_id`, `age_type`, `gender_type`, `state`, `assignment_closed_at`, `registration_closed_at`, `selection_opened_at`, `selection_closed_at`, `generated_at`, `scheduling_closed_at`, `referees_closed_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'RIA', 'tempora sint', 5, 11, 1, 2, 1, '2022-09-02 14:19:33', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:33', '2022-09-02 14:19:33'),
(2, 2, 'SFU', 'voluptatem esse', 5, 12, 0, 0, 3, NULL, '2022-09-02 14:19:33', NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:33', '2022-09-02 14:19:33'),
(3, 2, 'KRS', 'sapiente hic', 5, 13, 2, 2, 4, NULL, NULL, NULL, NULL, '2022-09-02 14:19:34', NULL, NULL, '2022-09-02 14:19:34', '2022-09-02 14:19:34'),
(4, 2, 'GUC', 'blanditiis minus', 5, 14, 0, 2, 1, '2022-09-02 14:19:35', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:35', '2022-09-02 14:19:35'),
(5, 2, 'QBE', 'ducimus perspiciatis', 5, 15, 2, 0, 1, '2022-09-02 14:19:35', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:35', '2022-09-02 14:19:35'),
(6, 2, 'XFS', 'aspernatur est', 5, 16, 1, 0, 3, NULL, '2022-09-02 14:19:35', NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:36', '2022-09-02 14:19:36'),
(7, 2, 'MAK', 'ut inventore', 5, 17, 2, 1, 4, NULL, NULL, NULL, NULL, '2022-09-02 14:19:36', NULL, NULL, '2022-09-02 14:19:36', '2022-09-02 14:19:36'),
(8, 2, 'DPC', 'cum sed', 5, 18, 2, 1, 1, '2022-09-02 14:19:37', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:37', '2022-09-02 14:19:37'),
(9, 2, 'JWN', 'velit earum', 5, 19, 2, 2, 1, '2022-09-02 14:19:37', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:37', '2022-09-02 14:19:37'),
(10, 2, 'QIJ', 'alias voluptatem', 5, 20, 1, 1, 3, NULL, '2022-09-02 14:19:38', NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:38', '2022-09-02 14:19:38'),
(11, 2, 'UEH', 'et nihil', 5, 21, 0, 0, 4, NULL, NULL, NULL, NULL, '2022-09-02 14:19:38', NULL, NULL, '2022-09-02 14:19:38', '2022-09-02 14:19:38'),
(12, 2, 'RCA', 'dolores atque', 5, 22, 2, 1, 1, '2022-09-02 14:19:39', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:39', '2022-09-02 14:19:39'),
(13, 2, 'ZHW', 'et nobis', 5, 23, 1, 2, 1, '2022-09-02 14:19:39', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:40', '2022-09-02 14:19:40'),
(14, 2, 'PVS', 'aut voluptatem', 5, 24, 1, 2, 3, NULL, '2022-09-02 14:19:40', NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:40', '2022-09-02 14:19:40'),
(15, 2, 'KZP', 'sed recusandae', 5, 25, 1, 1, 4, NULL, NULL, NULL, NULL, '2022-09-02 14:19:40', NULL, NULL, '2022-09-02 14:19:41', '2022-09-02 14:19:41'),
(16, 2, 'PPI', 'quia voluptatem', 5, 26, 2, 0, 1, '2022-09-02 14:19:41', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:41', '2022-09-02 14:19:41'),
(17, 2, 'MSS', 'blanditiis sed', 5, 27, 0, 0, 1, '2022-09-02 14:19:42', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:42', '2022-09-02 14:19:42'),
(18, 2, 'KAL', 'possimus magni', 5, 28, 0, 1, 3, NULL, '2022-09-02 14:19:42', NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:42', '2022-09-02 14:19:42'),
(19, 2, 'UYS', 'iure sit', 5, 29, 0, 1, 4, NULL, NULL, NULL, NULL, '2022-09-02 14:19:43', NULL, NULL, '2022-09-02 14:19:43', '2022-09-02 14:19:43'),
(20, 2, 'KTD', 'consequuntur numquam', 5, 30, 1, 2, 1, '2022-09-02 14:19:44', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:44', '2022-09-02 14:19:44'),
(21, 2, 'FRN', 'assumenda ut', 5, 31, 2, 2, 1, '2022-09-02 14:19:44', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:44', '2022-09-02 14:19:44'),
(22, 2, 'EVD', 'dolorem aliquam', 5, 32, 2, 0, 3, NULL, '2022-09-02 14:19:45', NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:45', '2022-09-02 14:19:45'),
(23, 2, 'KYN', 'error ab', 5, 33, 2, 2, 4, NULL, NULL, NULL, NULL, '2022-09-02 14:19:45', NULL, NULL, '2022-09-02 14:19:45', '2022-09-02 14:19:45'),
(24, 2, 'LEJ', 'iusto beatae', 5, 34, 0, 0, 1, '2022-09-02 14:19:46', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:46', '2022-09-02 14:19:46'),
(25, 2, 'WVZ', 'porro consequuntur', 5, 35, 0, 2, 1, '2022-09-02 14:19:46', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:46', '2022-09-02 14:19:46'),
(26, 2, 'TKM', 'animi quis', 5, 36, 1, 0, 3, NULL, '2022-09-02 14:19:47', NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:47', '2022-09-02 14:19:47'),
(27, 2, 'FLX', 'totam voluptatem', 5, 37, 1, 0, 4, NULL, NULL, NULL, NULL, '2022-09-02 14:19:47', NULL, NULL, '2022-09-02 14:19:47', '2022-09-02 14:19:47'),
(28, 2, 'GCY', 'mollitia quam', 5, 38, 0, 1, 1, '2022-09-02 14:19:48', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:48', '2022-09-02 14:19:48'),
(29, 2, 'MCB', 'dolores unde', 5, 39, 0, 0, 1, '2022-09-02 14:19:49', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:49', '2022-09-02 14:19:49'),
(30, 2, 'TYU', 'voluptatem vel', 5, 40, 2, 0, 3, NULL, '2022-09-02 14:19:49', NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:49', '2022-09-02 14:19:49'),
(31, 2, 'UAC', 'nam est', 5, 41, 2, 1, 4, NULL, NULL, NULL, NULL, '2022-09-02 14:19:50', NULL, NULL, '2022-09-02 14:19:50', '2022-09-02 14:19:50'),
(32, 2, 'QOX', 'eius qui', 5, 42, 0, 2, 1, '2022-09-02 14:19:51', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:51', '2022-09-02 14:19:51'),
(33, 2, 'MUM', 'quis sit', 5, 43, 2, 1, 1, '2022-09-02 14:19:51', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:51', '2022-09-02 14:19:51'),
(34, 2, 'HGV', 'esse assumenda', 5, 44, 2, 2, 3, NULL, '2022-09-02 14:19:52', NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:52', '2022-09-02 14:19:52'),
(35, 2, 'CTB', 'repudiandae illo', 5, 45, 2, 1, 4, NULL, NULL, NULL, NULL, '2022-09-02 14:19:52', NULL, NULL, '2022-09-02 14:19:52', '2022-09-02 14:19:52'),
(36, 2, 'OCQ', 'voluptatem ipsum', 5, 46, 2, 1, 1, '2022-09-02 14:19:53', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:54', '2022-09-02 14:19:54'),
(37, 2, 'NUV', 'est sunt', 5, 47, 0, 2, 1, '2022-09-02 14:19:54', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:54', '2022-09-02 14:19:54'),
(38, 2, 'ZTG', 'magnam omnis', 5, 48, 0, 1, 3, NULL, '2022-09-02 14:19:55', NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:55', '2022-09-02 14:19:55'),
(39, 2, 'IZF', 'enim nemo', 5, 49, 1, 1, 4, NULL, NULL, NULL, NULL, '2022-09-02 14:19:55', NULL, NULL, '2022-09-02 14:19:55', '2022-09-02 14:19:55'),
(40, 2, 'GVK', 'voluptatem similique', 5, 50, 0, 0, 1, '2022-09-02 14:19:56', NULL, NULL, NULL, NULL, NULL, NULL, '2022-09-02 14:19:56', '2022-09-02 14:19:56');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
