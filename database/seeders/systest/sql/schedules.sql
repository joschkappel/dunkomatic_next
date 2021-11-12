SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


INSERT INTO `schedules` (`id`, `name`, `region_id`, `eventcolor`, `league_size_id`, `iterations`, `created_at`, `updated_at`, `custom_events`) VALUES
(1, '10er Runden', 2, 'green', 5, 1, '2021-11-12 12:47:58', NULL, 0),
(2, 'OW 14', 1, 'green', 4, 1, '2021-11-12 12:47:58', NULL, 0),
(3, '8er Runden', 2, 'green', 4, 1, '2021-11-12 12:47:58', NULL, 0),
(4, 'OW 16', 1, 'green', 5, 1, '2021-11-12 12:47:58', NULL, 0),
(5, 'OW 18', 1, 'green', 5, 1, '2021-11-12 12:47:58', NULL, 0),
(6, 'OL/LL Bezirk 12er', 1, 'green', 6, 1, '2021-11-12 12:47:58', NULL, 0),
(7, 'OL/LL Bezirk 10er', 1, 'green', 5, 1, '2021-11-12 12:47:58', NULL, 0),
(9, 'OM 14', 1, 'green', 4, 1, '2021-11-12 12:47:58', NULL, 0),
(14, 'OM 16', 1, 'green', 4, 1, '2021-11-12 12:47:58', NULL, 0),
(15, 'OM 18', 1, 'green', 5, 1, '2021-11-12 12:47:58', NULL, 0),
(16, 'OX 12', 1, 'green', 4, 1, '2021-11-12 12:47:58', NULL, 0),
(23, 'LW 14', 1, 'green', 5, 1, '2021-11-12 12:47:58', NULL, 0),
(26, 'Doppel 4er', 4, 'green', 2, 2, '2021-11-12 12:47:58', NULL, 0),
(28, 'LW 16', 1, 'green', 4, 1, '2021-11-12 12:47:58', NULL, 0),
(29, 'LM 16', 1, 'green', 4, 1, '2021-11-12 12:47:58', NULL, 0),
(31, 'LX 12', 1, 'green', 4, 1, '2021-11-12 12:47:58', NULL, 0),
(32, 'LM 14', 1, 'green', 5, 1, '2021-11-12 12:47:58', NULL, 0),
(33, 'LM 18', 1, 'green', 5, 1, '2021-11-12 12:47:58', NULL, 0),
(101, 'Custom', 1, 'red', 1, 1, '2021-11-12 12:47:58', NULL, 1),
(102, 'Custom', 2, 'red', 1, 1, '2021-11-12 12:47:58', NULL, 1),
(104, 'Custom', 4, 'red', 1, 1, '2021-11-12 12:47:58', NULL, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
