
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
                                                          (3,	'2024_02_15_182117_create_room_points_table',	1),
                                                          (4,	'2_create_users_table',	1),
                                                          (5,	'3_create_chat_messages_table',	1);

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
                                                                                                                                                                          (1,	'App\\Models\\User',	1,	'temp',	'c6bebc6d6fac37faad550c4daeb8e3f005c04075cd0e743aa0664bd14aafab9c',	'[\"*\"]',	'2024-02-15 20:46:35',	NULL,	'2024-02-15 20:31:58',	'2024-02-15 20:46:35'),
                                                                                                                                                                          (2,	'App\\Models\\User',	2,	'temp',	'99c46d1bee3abe933cfe112a83e2302ba7b25dadc4b796cc73c96e45745e15b8',	'[\"*\"]',	'2024-02-15 20:46:36',	NULL,	'2024-02-15 20:34:34',	'2024-02-15 20:46:36');

DROP TABLE IF EXISTS `room_points`;
CREATE TABLE `room_points` (
                               `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                               `room_id` bigint unsigned NOT NULL,
                               `x` int NOT NULL,
                               `y` int NOT NULL,
                               PRIMARY KEY (`id`),
                               KEY `room_points_room_id_foreign` (`room_id`),
                               CONSTRAINT `room_points_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `room_points` (`id`, `room_id`, `x`, `y`) VALUES
                                                          (1,	4,	50,	456),
                                                          (2,	4,	50,	697),
                                                          (3,	4,	542,	697),
                                                          (4,	4,	542,	509),
                                                          (5,	4,	749,	509),
                                                          (6,	4,	749,	245),
                                                          (7,	4,	212,	245),
                                                          (8,	4,	212,	456);

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
                                                                                                                      (1,	'The Office',	4,	140,	28,	230,	195,	0,	'2024-02-15 20:31:52',	'2024-02-15 20:31:52'),
                                                                                                                      (2,	'Meeting Room',	15,	370,	28,	375,	200,	0,	'2024-02-15 20:31:52',	'2024-02-15 20:31:52'),
                                                                                                                      (3,	'Desk',	3,	19,	250,	190,	170,	1,	'2024-02-15 20:31:52',	'2024-02-15 20:31:52'),
                                                                                                                      (4,	'Open office 1',	9,	0,	0,	0,	0,	0,	'2024-02-15 20:31:52',	'2024-02-15 20:31:52'),
                                                                                                                      (5,	'Silent room 1',	1,	535,	510,	200,	155,	1,	'2024-02-15 20:31:52',	'2024-02-15 20:31:52'),
                                                                                                                      (6,	'Kitchen',	5,	740,	316,	440,	130,	0,	'2024-02-15 20:31:52',	'2024-02-15 20:31:52'),
                                                                                                                      (7,	'Silent room 2',	1,	842,	445,	115,	75,	0,	'2024-02-15 20:31:52',	'2024-02-15 20:31:52'),
                                                                                                                      (8,	'Breakroom',	5,	1305,	28,	230,	225,	0,	'2024-02-15 20:31:52',	'2024-02-15 20:31:52'),
                                                                                                                      (9,	'Open office 2',	4,	1180,	250,	360,	280,	0,	'2024-02-15 20:31:52',	'2024-02-15 20:31:52'),
                                                                                                                      (10,	'Silent room 3',	2,	1275,	535,	260,	125,	0,	'2024-02-15 20:31:52',	'2024-02-15 20:31:52');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
                         `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                         `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `avatar_id` int NOT NULL,
                         `selected_room_id` bigint unsigned NOT NULL,
                         `last_updated_at` timestamp NOT NULL,
                         `x` int NOT NULL,
                         `y` int NOT NULL,
                         `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         PRIMARY KEY (`id`),
                         KEY `users_selected_room_id_foreign` (`selected_room_id`),
                         CONSTRAINT `users_selected_room_id_foreign` FOREIGN KEY (`selected_room_id`) REFERENCES `rooms` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `avatar_id`, `selected_room_id`, `last_updated_at`, `x`, `y`, `color`, `created_at`, `updated_at`) VALUES
                                                                                                                                          (1,	'd',	1,	2,	'2024-02-15 20:46:35',	500,	139,	'blue',	'2024-02-15 20:31:58',	'2024-02-15 20:46:35'),
                                                                                                                                          (2,	'fafsa',	1,	3,	'2024-02-15 20:46:36',	54,	282,	'green',	'2024-02-15 20:34:34',	'2024-02-15 20:46:36');

