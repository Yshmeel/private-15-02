-- Adminer 4.8.1 MySQL 8.0.33 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `chat_messages`;
CREATE TABLE `chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `room_id` bigint unsigned NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_user_id_foreign` (`user_id`),
  KEY `chat_messages_room_id_foreign` (`room_id`),
  CONSTRAINT `chat_messages_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`),
  CONSTRAINT `chat_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1,	'1_create_rooms_table',	1),
(2,	'2019_12_14_000001_create_personal_access_tokens_table',	1),
(3,	'2_create_users_table',	1),
(4,	'3_create_chat_messages_table',	1);

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1,	'App\\Models\\User',	1,	'temp',	'f4d980139661388ce3289db89dcd8c33cae975dc37c91b578e2934b9d2cc611f',	'[\"*\"]',	'2024-02-03 21:15:36',	NULL,	'2024-02-03 21:05:29',	'2024-02-03 21:15:36');

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `max_users` int NOT NULL,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `width` int NOT NULL,
  `height` int NOT NULL,
  `layer` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rooms` (`id`, `name`, `max_users`, `x`, `y`, `width`, `height`, `layer`, `created_at`, `updated_at`) VALUES
(1,	'The Office',	4,	156,	28,	245,	217,	0,	'2024-02-03 21:05:25',	'2024-02-03 21:05:25'),
(2,	'Meeting Room',	15,	407,	28,	396,	217,	0,	'2024-02-03 21:05:25',	'2024-02-03 21:05:25'),
(3,	'Desk',	3,	19,	290,	215,	170,	1,	'2024-02-03 21:05:25',	'2024-02-03 21:05:25'),
(4,	'Open office 1',	9,	76,	286,	703,	450,	0,	'2024-02-03 21:05:25',	'2024-02-03 21:05:25'),
(5,	'Silent room 1',	1,	577,	566,	220,	162,	1,	'2024-02-03 21:05:25',	'2024-02-03 21:05:25'),
(6,	'Kitchen',	5,	807,	346,	465,	142,	0,	'2024-02-03 21:05:25',	'2024-02-03 21:05:25'),
(7,	'Silent room 2',	1,	914,	494,	124,	82,	0,	'2024-02-03 21:05:25',	'2024-02-03 21:05:25'),
(8,	'Breakroom',	5,	1423,	28,	247,	247,	0,	'2024-02-03 21:05:25',	'2024-02-03 21:05:25'),
(9,	'Open office 2',	4,	1285,	273,	383,	308,	0,	'2024-02-03 21:05:25',	'2024-02-03 21:05:25'),
(10,	'Silent room 3',	2,	1386,	593,	286,	148,	0,	'2024-02-03 21:05:25',	'2024-02-03 21:05:25');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_id` int NOT NULL,
  `selected_room_id` bigint unsigned NOT NULL,
  `last_updated_at` timestamp NOT NULL,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_selected_room_id_foreign` (`selected_room_id`),
  CONSTRAINT `users_selected_room_id_foreign` FOREIGN KEY (`selected_room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2024-02-03 21:15:52
