-- Database: laravel_eatera_fe
-- Generated on: 2026-01-11 14:53:51

SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE `articles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `admin_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `articles_admin_id_foreign` (`admin_id`),
  CONSTRAINT `articles_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `articles` (`id`, `title`, `content`, `cover_image`, `status`, `admin_id`, `created_at`, `updated_at`) VALUES
('1', '7 Tips Makan Sehat untuk Tubuh yang Ideal', 'Makan sehat bukan hanya tentang diet ketat, tetapi tentang memilih makanan yang tepat untuk tubuh Anda. Berikut adalah beberapa tips praktis untuk memulai perjalanan hidup sehat Anda.', NULL, 'published', '3', '2026-01-11 18:04:53', '2026-01-11 18:04:53'),
('2', 'Manfaat Olahraga Teratur untuk Kesehatan Mental', 'Selain manfaat fisik, olahraga ternyata memiliki dampak positif yang signifikan terhadap kesehatan mental kita. Mari kita pelajari lebih dalam tentang hubungan antara aktivitas fisik dan kesejahteraan mental.', NULL, 'published', '3', '2026-01-11 18:04:53', '2026-01-11 18:04:53'),
('3', 'Panduan Lengkap Nutrisi untuk Atlet', 'Atlet membutuhkan nutrisi yang tepat untuk memaksimalkan performa mereka. Artikel ini akan membahas kebutuhan nutrisi khusus, timing makan, dan suplemen yang tepat.', NULL, 'draft', '3', '2026-01-11 18:04:53', '2026-01-11 18:04:53');

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `comment_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL,
  `post_id` bigint(20) unsigned NOT NULL,
  `reported_by` bigint(20) unsigned NOT NULL,
  `reason` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comment_reports_comment_id_foreign` (`comment_id`),
  KEY `comment_reports_post_id_foreign` (`post_id`),
  KEY `comment_reports_reported_by_foreign` (`reported_by`),
  CONSTRAINT `comment_reports_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `community_comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comment_reports_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comment_reports_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `community_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `community_comments_post_id_foreign` (`post_id`),
  KEY `community_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `community_comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `community_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `community_likes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `community_likes_post_id_user_id_unique` (`post_id`,`user_id`),
  KEY `community_likes_user_id_foreign` (`user_id`),
  CONSTRAINT `community_likes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `community_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `community_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `content` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `community_posts_user_id_foreign` (`user_id`),
  CONSTRAINT `community_posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `foods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `usda_fdc_id` int(11) DEFAULT NULL,
  `calories` double DEFAULT NULL,
  `protein` double DEFAULT NULL,
  `fat` double DEFAULT NULL,
  `carbohydrates` double DEFAULT NULL,
  `fiber` double DEFAULT NULL,
  `source` varchar(255) NOT NULL DEFAULT 'usda',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `usda_food_id` varchar(255) DEFAULT NULL,
  `serving_size` double DEFAULT 100 COMMENT 'Serving size in grams',
  `serving_description` varchar(255) DEFAULT NULL COMMENT 'e.g., 1 medium apple, 1 slice',
  PRIMARY KEY (`id`),
  KEY `foods_usda_fdc_id_index` (`usda_fdc_id`),
  KEY `foods_usda_food_id_index` (`usda_food_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `foods` (`id`, `name`, `usda_fdc_id`, `calories`, `protein`, `fat`, `carbohydrates`, `fiber`, `source`, `created_at`, `updated_at`, `usda_food_id`, `serving_size`, `serving_description`) VALUES
('1', 'APEL', '454004', '52', '0', '0.6', '14.3', '3.2', 'usda', '2026-01-07 15:24:36', '2026-01-09 08:41:02', NULL, '154', '154 g'),
('2', 'STRAWBERRY', '2388788', '50', '3.33', '0', '113.33', '10', 'usda', '2026-01-07 15:24:57', '2026-01-07 15:24:57', NULL, '30', '30 g'),
('3', 'MELON', '2709227', '35', '0.6', '0.1', '8.3', '0.7', 'usda', '2026-01-07 15:25:25', '2026-01-09 08:38:59', NULL, '100', NULL),
('4', 'SEMANGKA', '2104697', '31', '0.7', '0.2', '7.5', '0.4', 'usda', '2026-01-07 15:37:07', '2026-01-09 08:41:32', NULL, '100', '100 g'),
('5', 'MANGGA', '2629151', '71', '0.7', '0', '17.1', '2.1', 'usda', '2026-01-07 16:02:25', '2026-01-09 08:41:48', NULL, '140', '140 GRM'),
('6', 'ANGGUR', '2085879', '54', '0', '0', '13.1', '0', 'usda', '2026-01-07 16:02:46', '2026-01-09 08:42:08', NULL, '296', '296 ml'),
('7', 'MOCHI', '1998937', '222', '4', '0', '51.9', '2', 'usda', '2026-01-07 16:05:16', '2026-01-09 08:39:26', NULL, '100', '100 g'),
('12', 'ACAR', '1863205', '167', '0', '0', '46.7', '3.3', 'usda', '2026-01-07 16:12:50', '2026-01-09 08:42:25', NULL, '30', '30 g'),
('13', 'BISTIK DAGING', '2034190', '214', '42.8', '3.6', '7.1', '0', 'usda', '2026-01-07 16:20:11', '2026-01-09 08:43:19', NULL, '28', '28 g'),
('14', 'AYAM REBUS', '2029648', '167', '6.7', '0', '33.3', '0', 'usda', '2026-01-07 16:25:35', '2026-01-09 08:44:02', NULL, '15', '15 g'),
('15', 'KARI AYAM', '2289446', '157', '13.6', '9.3', '5', '0', 'usda', '2026-01-07 16:26:08', '2026-01-09 08:44:31', NULL, '140', '140 g'),
('16', 'RENDANG', '2266168', '409', '9.1', '22.7', '54.5', '9.1', 'usda', '2026-01-07 16:26:47', '2026-01-09 08:39:48', NULL, '11', '11 g'),
('17', 'IKAN LELE GORENG', '2706238', '255', '13.5', '16.7', '11.7', '0.5', 'usda', '2026-01-07 16:49:27', '2026-01-09 08:40:14', NULL, '100', NULL),
('18', 'IKAN MUJAIR GORENG', '2706322', '237', '17.2', '13.5', '11.7', '0.5', 'usda', '2026-01-07 16:50:22', '2026-01-09 08:40:26', NULL, '100', NULL),
('19', 'CRACKERS', '2055568', '410', '10.26', '5.13', '76.92', '5.1', 'usda', '2026-01-07 16:51:19', '2026-01-07 16:51:19', NULL, '19.5', '19.5 g'),
('20', 'MALBI', NULL, '415', '22.5', '22.5', '12.5', '1.5', 'manual', '2026-01-09 08:47:45', '2026-01-09 08:47:45', NULL, '100', NULL),
('21', 'TEKWAN IKAN', NULL, '205', '15', '3.5', '25', '1.5', 'manual', '2026-01-09 08:49:32', '2026-01-09 08:49:32', NULL, '100', NULL),
('22', 'MODEL IKAN', NULL, '250', '17', '5.5', '30', '1.5', 'manual', '2026-01-09 08:50:18', '2026-01-09 08:50:18', NULL, '100', NULL),
('23', 'PEMPEK IKAN', NULL, '275', '7.5', '6.5', '25', '0', 'manual', '2026-01-09 08:52:22', '2026-01-09 08:52:22', NULL, '100', NULL),
('24', 'RUJAK MIE PALEMBANG', NULL, '500', '17.5', '18.5', '62.5', '4', 'manual', '2026-01-09 08:53:59', '2026-01-09 08:53:59', NULL, '100', NULL),
('25', 'PEMPEK KAPAL SELAM', NULL, '425', '18', '16', '50', '1.5', 'manual', '2026-01-09 08:54:45', '2026-01-09 08:54:45', NULL, '100', NULL),
('26', 'SAMBAL ATI AMPELA', NULL, '260', '27.5', '17.5', '7.5', '1.5', 'manual', '2026-01-09 08:55:54', '2026-01-09 08:55:54', NULL, '100', NULL),
('27', 'BUBUR AYAM', NULL, '275', '15', '7.5', '40', '1.5', 'manual', '2026-01-09 08:56:40', '2026-01-09 08:56:40', NULL, '100', NULL),
('28', 'SAYUR TUMIS KANGKUNG', NULL, '85', '2.5', '5', '7', '2.5', 'manual', '2026-01-09 08:57:27', '2026-01-09 08:57:27', NULL, '100', NULL),
('29', 'SAYUR TUMIS GENJER', NULL, '95', '2.5', '5', '8.5', '3.5', 'manual', '2026-01-09 08:58:13', '2026-01-09 08:58:13', NULL, '100', NULL),
('30', 'SAYUR BENING BAYAM', NULL, '37.5', '2.5', '0.5', '6', '2.5', 'manual', '2026-01-09 08:59:04', '2026-01-09 08:59:04', NULL, '100', NULL),
('31', 'PINDANG IKAN PATIN', NULL, '205', '20', '12', '4.5', '1.5', 'manual', '2026-01-09 09:02:04', '2026-01-09 09:02:04', NULL, '100', NULL),
('32', 'OPOR AYAM', NULL, '315', '20', '22.5', '6.5', '1', 'manual', '2026-01-09 09:03:14', '2026-01-09 09:03:14', NULL, '100', NULL),
('33', 'GULAI AYAM', NULL, '340', '20.5', '25', '6.5', '1.5', 'manual', '2026-01-09 09:03:58', '2026-01-09 09:03:58', NULL, '100', NULL),
('34', 'SEMUR AYAM', NULL, '285', '20', '15', '12.5', '1.5', 'manual', '2026-01-09 09:05:23', '2026-01-09 09:05:23', NULL, '100', NULL),
('35', 'OTAK-OTAK PANGGANG', NULL, '90', '7', '4', '7', '0.5', 'manual', '2026-01-09 09:05:58', '2026-01-09 09:05:58', NULL, '100', NULL),
('36', 'SALAD BUAH', NULL, '115', '1.5', '0.5', '25', '4', 'manual', '2026-01-09 09:06:49', '2026-01-09 09:06:49', NULL, '100', NULL),
('37', 'SALAD', '2637940', '151', '2.63', '4.61', '26.97', '2.6', 'usda', '2026-01-09 09:22:02', '2026-01-09 09:22:02', NULL, '152', '152 GRM'),
('40', 'ALPUKAT', '2709223', '160', '2', '14.7', '8.5', '6.7', 'usda', '2026-01-09 09:38:50', '2026-01-09 09:41:38', NULL, '100', NULL),
('41', 'KIWI', '2709239', '64', '1', '0.4', '14', '3', 'usda', '2026-01-09 09:42:19', '2026-01-09 09:43:14', NULL, '100', NULL),
('42', 'SMOOTHIE SAYUR', '2710152', '89', '2.7', '6.6', '5.8', '2.8', 'usda', '2026-01-09 09:42:50', '2026-01-09 09:43:36', NULL, '100', NULL),
('43', 'SMOOTHIE BUAH', '2709338', '53', '2.6', '0.6', '9.7', '1', 'usda', '2026-01-09 09:44:02', '2026-01-09 09:44:16', NULL, '100', NULL),
('44', 'DADA AYAM', '2187885', '165', '20.4', '8.1', '1', '0', 'usda', '2026-01-09 09:47:01', '2026-01-09 09:47:18', NULL, '284', '284 g'),
('45', 'NASI KUNING', NULL, '85', '1.8', '0.1', '40.5', '0.5', 'manual', '2026-01-09 09:55:05', '2026-01-09 09:55:05', NULL, '100', NULL),
('46', 'BUAH NAGA', '2376793', '57', '0.7', '0', '10.7', '3.6', 'usda', '2026-01-09 09:55:51', '2026-01-09 09:56:14', NULL, '140', '140 g'),
('47', 'MASHED POTATO', '1648568', '164', '2.14', '10.71', '15', '0.7', 'usda', '2026-01-09 10:11:32', '2026-01-09 10:11:32', NULL, '140', '140 g'),
('48', 'JERUK', '2709171', '50', '0.9', '0.1', '11.8', '2.2', 'usda', '2026-01-09 10:12:11', '2026-01-09 10:12:27', NULL, '100', NULL),
('49', 'LEMON', '2709168', '29', '1.1', '0.3', '9.3', '2.8', 'usda', '2026-01-09 10:12:45', '2026-01-09 10:12:57', NULL, '100', NULL),
('50', 'PEPAYA', '169926', '179', '0.4', '0.2', '10.8', '1.7', 'usda', '2026-01-09 10:50:25', '2026-01-09 10:50:59', NULL, '100', NULL),
('51', 'NASI LEMAK', NULL, '325', '4', '13', '32.8', '1', 'manual', '2026-01-09 10:54:00', '2026-01-09 10:54:00', NULL, '100', NULL),
('52', 'INFUSED WATER', NULL, '2.5', '0.7', '0', '0', '0', 'manual', '2026-01-10 06:55:55', '2026-01-10 06:55:55', NULL, '100', NULL),
('53', 'UDANG', '2442026', '106', '20.3', '1.7', '0.9', '0', 'usda', '2026-01-10 06:59:34', '2026-01-10 07:11:00', NULL, '113', '113 g'),
('54', 'IKAN TUNA', '2068771', '94', '5.7', '3.7', '10.4', '0.9', 'usda', '2026-01-10 07:01:00', '2026-01-10 07:01:22', NULL, '106', '106 g'),
('55', 'LOBAK', '2709803', '16', '0.7', '0.1', '3.4', '1.6', 'usda', '2026-01-10 07:02:58', '2026-01-10 07:03:21', NULL, '100', NULL),
('56', 'WORTEL REBUS', '170394', '147', '0.8', '0.2', '8.2', '3', 'usda', '2026-01-10 07:09:57', '2026-01-10 07:10:21', NULL, '100', NULL),
('57', 'FRENCH FRIES', '2093015', '179', '2.38', '8.33', '22.62', '2.4', 'usda', '2026-01-10 09:07:00', '2026-01-10 09:07:00', NULL, '84', '84 g'),
('58', 'JAMBU BIJI', '2295367', '49', '0.6', '0.2', '11.1', '5.2', 'usda', '2026-01-11 11:29:34', '2026-01-11 11:30:14', NULL, '100', '100 g'),
('59', 'SATE PADANG TANPA NASI', NULL, '161', '18', '12.5', '7', '0.2', 'manual', '2026-01-11 11:38:12', '2026-01-11 11:38:12', NULL, '100', NULL),
('60', 'BLUEBERRY', '2116605', '250', '0', '0', '65', NULL, 'usda', '2026-01-11 11:52:07', '2026-01-11 11:52:07', NULL, '20', '20 g'),
('61', 'SATE PADANG + NASI', NULL, '375', '25', '9.5', '40', '0.1', 'manual', '2026-01-11 11:54:06', '2026-01-11 11:54:06', NULL, '100', NULL);

CREATE TABLE `food_journals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `food_id` bigint(20) unsigned NOT NULL,
  `quantity` double NOT NULL DEFAULT 1,
  `unit` varchar(255) NOT NULL DEFAULT 'gram',
  `meal_type` enum('breakfast','lunch','dinner','snack') NOT NULL,
  `eaten_at` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `food_journals_food_id_foreign` (`food_id`),
  KEY `food_journals_user_id_eaten_at_meal_type_index` (`user_id`,`eaten_at`,`meal_type`),
  CONSTRAINT `food_journals_food_id_foreign` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE CASCADE,
  CONSTRAINT `food_journals_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `food_journals` (`id`, `user_id`, `food_id`, `quantity`, `unit`, `meal_type`, `eaten_at`, `created_at`, `updated_at`) VALUES
('13', '8', '1', '100', 'gram', 'lunch', '2026-01-11', '2026-01-11 18:32:37', '2026-01-11 18:32:37');

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
('1', '0001_01_01_000000_create_users_table', '1'),
('2', '0001_01_01_000001_create_cache_table', '1'),
('3', '0001_01_01_000002_create_jobs_table', '1'),
('4', '2025_11_25_081955_create_personal_access_tokens_table', '1'),
('5', '2025_11_25_082002_create_food_table', '1'),
('6', '2025_11_25_082005_add_usda_to_foods_table', '1'),
('7', '2025_11_25_082515_create_food_journals_table', '1'),
('8', '2025_12_15_001359_create_user_profiles_table', '1'),
('9', '2025_12_29_add_serving_to_foods', '1'),
('10', '2025_12_30_041154_create_community_posts_table', '1'),
('11', '2025_12_30_041255_create_community_comments_table', '1'),
('12', '2025_12_30_041349_create_community_likes_table', '1'),
('13', '2025_12_30_041426_create_post_reports_table', '1'),
('14', '2025_12_30_164105_create_articles_table', '1'),
('15', '2026_01_03_add_community_warn_to_users', '1'),
('16', '2026_01_04_create_comment_reports_table', '1'),
('17', '2026_01_08_add_unit_to_food_journals', '1'),
('36', '2026_01_11_update_diet_goal_values', '2');

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
('1', 'App\\Models\\User', '1', 'api-token', '04067a61d225042c8e78997d7059a7ad47f898bde209a7fa8dc156739d1f60ab', '[\"*\"]', '2026-01-11 16:46:36', NULL, '2026-01-11 16:45:07', '2026-01-11 16:46:36'),
('6', 'App\\Models\\User', '5', 'api-token', '333c8e1bc9e8a1784cb16044e2c4b34b683f738ab063fb5a966cb2a24ef0c3f0', '[\"*\"]', '2026-01-07 17:38:59', NULL, '2026-01-07 17:25:15', '2026-01-07 17:38:59'),
('89', 'App\\Models\\User', '2', 'api-token', '8c57b32c954777839c5283bd8734a01c77a189602fc301eb862801405890ffb3', '[\"*\"]', '2026-01-11 12:14:13', NULL, '2026-01-11 12:12:21', '2026-01-11 12:14:13'),
('90', 'App\\Models\\User', '2', 'api-token', '92579a6ed3746db07143e97fe56b0db855719b49298483885fbc4939d317df49', '[\"*\"]', '2026-01-11 12:22:27', NULL, '2026-01-11 12:22:12', '2026-01-11 12:22:27'),
('91', 'App\\Models\\User', '2', 'api-token', '2026ad95a9608767cd29b31cca1b8bf4b196eca3bcb903cd92fe13d95c12ae22', '[\"*\"]', '2026-01-11 12:26:28', NULL, '2026-01-11 12:26:15', '2026-01-11 12:26:28'),
('92', 'App\\Models\\User', '2', 'api-token', '4cc820364a51908749374840aa3cfd3ba9bf82fcc8ddc571318ce7345e1d77bf', '[\"*\"]', '2026-01-11 12:34:14', NULL, '2026-01-11 12:34:02', '2026-01-11 12:34:14'),
('93', 'App\\Models\\User', '2', 'api-token', 'aff576f999fb312e5705dd4ff71ec65092f466a8ab86a1fa96f759e8db35f579', '[\"*\"]', '2026-01-11 12:58:44', NULL, '2026-01-11 12:54:56', '2026-01-11 12:58:44'),
('94', 'App\\Models\\User', '2', 'api-token', '6fa7a70cdee431db9354ced241a8fc0c01075db04d868aca61caa46c6d47773d', '[\"*\"]', '2026-01-11 13:19:19', NULL, '2026-01-11 13:19:09', '2026-01-11 13:19:19'),
('95', 'App\\Models\\User', '2', 'api-token', '38b7f88cdbf9c261a5dd098174154aeee82fc7a5ed1b37022b8604b8ee1eb3c1', '[\"*\"]', '2026-01-11 13:36:22', NULL, '2026-01-11 13:35:47', '2026-01-11 13:36:22'),
('96', 'App\\Models\\User', '6', 'api-token', '3a33916b47e107e0eedfa09f15bf65030ea8c2546789c0c363dc54f4d51bb11c', '[\"*\"]', '2026-01-11 14:52:18', NULL, '2026-01-11 14:50:46', '2026-01-11 14:52:18'),
('97', 'App\\Models\\User', '6', 'api-token', 'e151174ae99e1f156271aed7ac7ef2ce8835a8a855c9f3d0ac91a3afa904cb75', '[\"*\"]', '2026-01-11 15:19:32', NULL, '2026-01-11 15:19:17', '2026-01-11 15:19:32'),
('100', 'App\\Models\\User', '8', 'api-token', '7f8a4b5f0a7ab83a7ea2ca1df13af8f328d5089245b604abd8c03dc8ee017f63', '[\"*\"]', '2026-01-11 18:49:59', NULL, '2026-01-11 18:49:42', '2026-01-11 18:49:59'),
('101', 'App\\Models\\User', '8', 'api-token', 'ef0fa3d7b66a863998844622dcc02bbbcd9e51b95562801421f9451691278505', '[\"*\"]', '2026-01-11 19:41:35', NULL, '2026-01-11 18:55:01', '2026-01-11 19:41:35');

CREATE TABLE `post_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `reported_by` bigint(20) unsigned NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_reports_post_id_foreign` (`post_id`),
  KEY `post_reports_reported_by_foreign` (`reported_by`),
  CONSTRAINT `post_reports_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `community_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_reports_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('jiO0kJfoak706kOGPRMdZs9CnIeQ5OkKqafnUn9R', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMXpGRVhDdVRTRU4wVzMybzJ3dm9DZVhCT3J2ekFSSkhyNlJ3dVBiYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWtvbWVuZGFzaSI7czo1OiJyb3V0ZSI7czoxNzoicmVrb21lbmRhc2kuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', '1768135287');

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `community_warn_until` datetime DEFAULT NULL,
  `community_warn_reason` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user' COMMENT 'user atau admin',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `community_warn_until`, `community_warn_reason`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
('1', 'Amoy', 'amoy@gmail.com', NULL, NULL, NULL, '$2y$12$QfIT4jd7AeRqDvIKkTrpxucODNBdAijcwiAyjioHJeZsOklQHFYlq', 'calomate', NULL, '2026-01-11 16:44:58', '2026-01-11 16:44:58'),
('2', 'Ajt User', 'ajt@gmail.com', '2026-01-11 18:04:52', NULL, NULL, '$2y$12$Rr7xlzAx07JwbvwbOjxF5uh8YoH.dUR/gASKNFgVMfQpT1GZVcobC', 'user', NULL, '2026-01-11 18:04:52', '2026-01-11 18:04:52'),
('3', 'Admin EATERA', 'admin@gmail.com', '2026-01-11 18:04:53', NULL, NULL, '$2y$12$ew76WClwLO8GKpr03JMM9ujSBnnMFaDV9stzthbVaOch1AJvUM2sW', 'admin', NULL, '2026-01-11 18:04:53', '2026-01-11 18:04:53'),
('8', 'michi', 'michi@gmail.com', NULL, NULL, NULL, '$2y$12$nzhsceFe1n2gJhs9trjfoOtDVtt4M6vBZhQJQJPt0ejasdKcd6S2O', 'calomate', NULL, '2026-01-11 18:30:20', '2026-01-11 18:30:20');

CREATE TABLE `user_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `date_of_birth` date NOT NULL,
  `weight` double NOT NULL,
  `height` double NOT NULL,
  `diet_goal` enum('deficit','maintain','bulking') NOT NULL,
  `target_calories` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_profiles_user_id_foreign` (`user_id`),
  CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user_profiles` (`id`, `user_id`, `gender`, `date_of_birth`, `weight`, `height`, `diet_goal`, `target_calories`, `created_at`, `updated_at`) VALUES
('1', '1', 'female', '2012-01-01', '50', '160', 'bulking', '1569', '2026-01-11 16:44:58', '2026-01-11 16:44:58'),
('4', '8', 'male', '2005-08-01', '60', '170', 'deficit', '1268', '2026-01-11 18:30:20', '2026-01-11 19:18:09');

SET FOREIGN_KEY_CHECKS=1;
