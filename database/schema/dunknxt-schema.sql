/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint(20) unsigned NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(1023) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  KEY `audits_user_id_user_type_index` (`user_id`,`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clubs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortname` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `club_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clubs_region_club_no_unique` (`region`,`club_no`),
  UNIQUE KEY `clubs_region_shortname_unique` (`region`,`shortname`),
  CONSTRAINT `clubs_region_foreign` FOREIGN KEY (`region`) REFERENCES `regions` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `games` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `league_id` int(10) unsigned NOT NULL,
  `region` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `game_no` smallint(6) NOT NULL,
  `game_plandate` date NOT NULL,
  `game_date` date NOT NULL,
  `game_time` time DEFAULT NULL,
  `club_id_home` int(10) unsigned DEFAULT NULL,
  `team_id_home` int(10) unsigned DEFAULT NULL,
  `team_home` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `team_char_home` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `club_id_guest` int(10) unsigned DEFAULT NULL,
  `team_id_guest` int(10) unsigned DEFAULT NULL,
  `team_guest` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `team_char_guest` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gym_no` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gym_id` int(10) unsigned DEFAULT NULL,
  `referee_1` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referee_2` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `games_league_id_game_no_unique` (`league_id`,`game_no`),
  KEY `games_gym_id_foreign` (`gym_id`),
  KEY `games_league_id_index` (`league_id`),
  KEY `games_club_id_home_index` (`club_id_home`),
  KEY `games_club_id_guest_index` (`club_id_guest`),
  KEY `games_team_id_home_index` (`team_id_home`),
  KEY `games_team_id_guest_index` (`team_id_guest`),
  KEY `games_region_index` (`region`),
  CONSTRAINT `games_club_id_guest_foreign` FOREIGN KEY (`club_id_guest`) REFERENCES `clubs` (`id`),
  CONSTRAINT `games_club_id_home_foreign` FOREIGN KEY (`club_id_home`) REFERENCES `clubs` (`id`),
  CONSTRAINT `games_gym_id_foreign` FOREIGN KEY (`gym_id`) REFERENCES `gyms` (`id`),
  CONSTRAINT `games_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`),
  CONSTRAINT `games_region_foreign` FOREIGN KEY (`region`) REFERENCES `regions` (`code`),
  CONSTRAINT `games_team_id_guest_foreign` FOREIGN KEY (`team_id_guest`) REFERENCES `teams` (`id`),
  CONSTRAINT `games_team_id_home_foreign` FOREIGN KEY (`team_id_home`) REFERENCES `teams` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gyms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `club_id` int(10) unsigned NOT NULL,
  `gym_no` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `directions` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gyms_club_id_gym_no_unique` (`club_id`,`gym_no`),
  CONSTRAINT `gyms_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `league_clubs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `league_id` int(10) unsigned NOT NULL,
  `club_id` int(10) unsigned NOT NULL,
  `league_char` char(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `league_no` smallint(6) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `league_clubs_league_id_foreign` (`league_id`),
  KEY `league_clubs_club_id_foreign` (`club_id`),
  CONSTRAINT `league_clubs_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`),
  CONSTRAINT `league_clubs_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `league_team_chars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `size` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_char` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `league_team_chars_size_foreign` (`size`),
  CONSTRAINT `league_team_chars_size_foreign` FOREIGN KEY (`size`) REFERENCES `league_team_sizes` (`size`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `league_team_schemes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `size` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `game_day` smallint(6) NOT NULL,
  `game_no` smallint(6) NOT NULL,
  `team_home` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `team_guest` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `league_team_schemes_size_foreign` (`size`),
  CONSTRAINT `league_team_schemes_size_foreign` FOREIGN KEY (`size`) REFERENCES `league_team_sizes` (`size`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `league_team_sizes` (
  `size` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  UNIQUE KEY `league_team_sizes_size_unique` (`size`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leagues` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortname` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `above_region` tinyint(1) NOT NULL DEFAULT 0,
  `schedule_id` int(10) unsigned DEFAULT NULL,
  `age_type` int(10) unsigned DEFAULT NULL,
  `gender_type` int(10) unsigned DEFAULT NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `leagues_shortname_unique` (`shortname`),
  KEY `leagues_region_foreign` (`region`),
  KEY `leagues_schedule_id_foreign` (`schedule_id`),
  CONSTRAINT `leagues_region_foreign` FOREIGN KEY (`region`) REFERENCES `regions` (`code`),
  CONSTRAINT `leagues_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  `unit_id` int(10) unsigned DEFAULT NULL,
  `unit_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `function` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `member_roles_member_id_foreign` (`member_id`),
  KEY `member_roles_role_id_foreign` (`role_id`),
  KEY `member_roles_unit_id_index` (`unit_id`),
  CONSTRAINT `member_roles_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  CONSTRAINT `member_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zipcode` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone1` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone2` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email2` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax1` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax2` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `regions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hq` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `regions_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortname` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule_events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `schedule_id` int(10) unsigned NOT NULL,
  `game_day` smallint(6) NOT NULL,
  `game_date` datetime NOT NULL,
  `full_weekend` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedule_events_schedule_id_foreign` (`schedule_id`),
  CONSTRAINT `schedule_events_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region_id` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eventcolor` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'green',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `schedules_region_id_foreign` (`region_id`),
  KEY `schedules_size_foreign` (`size`),
  CONSTRAINT `schedules_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`code`),
  CONSTRAINT `schedules_size_foreign` FOREIGN KEY (`size`) REFERENCES `league_team_sizes` (`size`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `team_no` smallint(6) NOT NULL,
  `league_id` int(10) unsigned DEFAULT NULL,
  `club_id` int(10) unsigned NOT NULL,
  `league_char` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `league_no` smallint(6) DEFAULT NULL,
  `league_prev` char(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `training_day` smallint(6) DEFAULT NULL,
  `training_time` time DEFAULT NULL,
  `preferred_game_day` smallint(6) DEFAULT NULL,
  `preferred_game_time` time DEFAULT NULL,
  `shirt_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coach_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coach_phone1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coach_phone2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coach_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changeable` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_league_id_foreign` (`league_id`),
  KEY `teams_club_id_foreign` (`club_id`),
  CONSTRAINT `teams_club_id_foreign` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`id`),
  CONSTRAINT `teams_league_id_foreign` FOREIGN KEY (`league_id`) REFERENCES `leagues` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `useables` (
  `user_id` int(10) unsigned NOT NULL,
  `useable_id` int(10) unsigned NOT NULL,
  `useable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_old` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `reason_join` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason_reject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `region` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `club_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `league_ids` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0,
  `regionadmin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_region_foreign` (`region`),
  CONSTRAINT `users_region_foreign` FOREIGN KEY (`region`) REFERENCES `regions` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` VALUES (1,'2014_10_12_100000_create_password_resets_table',1);
INSERT INTO `migrations` VALUES (2,'2019_08_10_092939_create_regions_table',1);
INSERT INTO `migrations` VALUES (3,'2019_08_18_000000_create_users_table',1);
INSERT INTO `migrations` VALUES (4,'2019_08_19_000000_create_failed_jobs_table',1);
INSERT INTO `migrations` VALUES (5,'2020_05_28_105916_create_clubs_table',1);
INSERT INTO `migrations` VALUES (6,'2020_06_05_132400_create_gyms_table',1);
INSERT INTO `migrations` VALUES (7,'2020_06_09_062538_create_league_team_size_table',1);
INSERT INTO `migrations` VALUES (8,'2020_06_09_063704_create_league_team_char_table',1);
INSERT INTO `migrations` VALUES (9,'2020_06_09_070036_create_league_team_scheme_table',1);
INSERT INTO `migrations` VALUES (10,'2020_06_10_092056_create_schedules_table',1);
INSERT INTO `migrations` VALUES (11,'2020_06_10_092151_create_schedule_events_table',1);
INSERT INTO `migrations` VALUES (12,'2020_06_22_070708_create_leagues_table',1);
INSERT INTO `migrations` VALUES (13,'2020_06_22_075101_create_league_clubs_table',1);
INSERT INTO `migrations` VALUES (14,'2020_06_24_070840_create_teams_table',1);
INSERT INTO `migrations` VALUES (15,'2020_07_02_152002_create_roles_table',1);
INSERT INTO `migrations` VALUES (16,'2020_07_02_152310_create_members_table',1);
INSERT INTO `migrations` VALUES (17,'2020_07_02_152312_create_member_roles_table',1);
INSERT INTO `migrations` VALUES (18,'2020_07_09_081037_create_games_table',1);
INSERT INTO `migrations` VALUES (19,'2020_08_25_074605_create_audits_table',1);
INSERT INTO `migrations` VALUES (20,'2020_08_25_090915_create_useables_table',1);
INSERT INTO `migrations` VALUES (21,'2020_09_01_081029_create_settings_table',1);
