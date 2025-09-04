-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 03 Eyl 2025, 16:08:02
-- Sunucu sürümü: 9.1.0
-- PHP Sürümü: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `villa_rental`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `admin_users`
--

INSERT INTO `admin_users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@villakiralama.com', '2025-09-03 03:22:38', '$2y$12$PcpPoAETs8ucIndUeBEZy.p8YXqSBACjIieA90cLEbNfnOqU6NzRi', NULL, '2025-09-03 03:22:38', '2025-09-03 03:22:38');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `villa_id` bigint UNSIGNED NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `guests` int NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `guest_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest_email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest_phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_requests` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_user_id_index` (`user_id`),
  KEY `bookings_villa_id_index` (`villa_id`),
  KEY `bookings_check_in_check_out_index` (`check_in`,`check_out`),
  KEY `bookings_status_index` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `villa_id`, `check_in`, `check_out`, `guests`, `total_amount`, `status`, `guest_name`, `guest_email`, `guest_phone`, `special_requests`, `created_at`, `updated_at`) VALUES
(1, 2, 18, '2025-09-23', '2025-09-25', 3, 4200.00, 'cancelled', 'Villa Sahibi', 'owner@villarentals.com', NULL, NULL, '2025-09-02 18:36:15', '2025-09-02 18:45:01'),
(2, 2, 14, '2025-09-30', '2025-10-04', 2, 8800.00, 'cancelled', 'Villa Sahibi', 'owner@villarentals.com', NULL, NULL, '2025-09-02 18:37:25', '2025-09-02 18:45:07'),
(3, 2, 14, '2025-09-21', '2025-09-27', 3, 13200.00, 'cancelled', 'Villa Sahibi', 'owner@villarentals.com', NULL, NULL, '2025-09-02 18:39:40', '2025-09-02 18:45:10'),
(4, 2, 16, '2025-09-21', '2025-09-26', 2, 10000.00, 'completed', 'Villa Sahibi', 'owner@villarentals.com', NULL, NULL, '2025-09-02 18:44:21', '2025-09-02 18:44:21'),
(5, 1, 14, '2025-09-23', '2025-09-28', 3, 11000.00, 'cancelled', 'Test User', 'test@example.com', NULL, NULL, '2025-09-02 18:49:58', '2025-09-03 05:02:41'),
(6, 1, 17, '2025-09-21', '2025-09-27', 4, 15600.00, 'cancelled', 'Test User', 'test@example.com', NULL, NULL, '2025-09-02 18:51:14', '2025-09-03 05:02:49'),
(7, 1, 14, '2025-09-28', '2025-09-30', 3, 4400.00, 'cancelled', 'Test User', 'test@example.com', NULL, NULL, '2025-09-02 19:00:34', '2025-09-02 19:00:44'),
(8, 1, 22, '2025-09-21', '2025-09-26', 5, 16000.00, 'cancelled', 'Test User', 'test@example.com', NULL, NULL, '2025-09-03 05:02:18', '2025-09-03 05:02:54'),
(9, 1, 29, '2025-09-21', '2025-10-05', 2, 21000.00, 'cancelled', 'Test User', 'test@example.com', NULL, NULL, '2025-09-03 05:03:40', '2025-09-03 07:50:18'),
(10, 1, 1, '2025-09-14', '2025-09-17', 4, 7500.00, 'confirmed', 'Test User', 'test@example.com', NULL, NULL, '2025-09-03 05:46:01', '2025-09-03 07:57:34'),
(11, 1, 21, '2025-09-14', '2025-09-17', 3, 7500.00, 'cancelled', 'Test User', 'test@example.com', NULL, NULL, '2025-09-03 05:47:57', '2025-09-03 07:50:22'),
(12, 1, 22, '2025-09-21', '2025-09-26', 2, 16000.00, 'cancelled', 'Test User', 'test@example.com', NULL, NULL, '2025-09-03 07:49:57', '2025-09-03 07:50:20');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `booking_status_logs`
--

DROP TABLE IF EXISTS `booking_status_logs`;
CREATE TABLE IF NOT EXISTS `booking_status_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint UNSIGNED NOT NULL,
  `old_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `new_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` bigint UNSIGNED NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_status_logs_changed_by_foreign` (`changed_by`),
  KEY `booking_status_logs_booking_id_index` (`booking_id`),
  KEY `booking_status_logs_created_at_index` (`created_at`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `booking_status_logs`
--

INSERT INTO `booking_status_logs` (`id`, `booking_id`, `old_status`, `new_status`, `changed_by`, `reason`, `created_at`, `updated_at`) VALUES
(1, 10, 'pending', 'confirmed', 1, 'Approved by admin', '2025-09-03 07:57:34', '2025-09-03 07:57:34');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('villa-rental-system-cache-settings.general', 'a:11:{s:8:\"category\";s:7:\"general\";s:8:\"settings\";a:9:{s:9:\"site_name\";s:19:\"Villa Rental System\";s:8:\"site_url\";N;s:16:\"site_description\";s:29:\"Premium villa rental platform\";s:13:\"contact_email\";s:22:\"info@villakiralama.com\";s:13:\"contact_phone\";s:16:\"+90 555 123 4567\";s:7:\"address\";s:16:\"Istanbul, Turkey\";s:8:\"currency\";s:3:\"TRY\";s:8:\"timezone\";s:3:\"UTC\";s:4:\"logo\";s:59:\"/storage/logos/QSyKhQRg1MthfLmWm47IU087yHmNn8HrItLTp4QZ.png\";}s:9:\"site_name\";s:19:\"Villa Rental System\";s:8:\"site_url\";N;s:16:\"site_description\";s:29:\"Premium villa rental platform\";s:13:\"contact_email\";s:22:\"info@villakiralama.com\";s:13:\"contact_phone\";s:16:\"+90 555 123 4567\";s:7:\"address\";s:16:\"Istanbul, Turkey\";s:8:\"currency\";s:3:\"TRY\";s:8:\"timezone\";s:3:\"UTC\";s:4:\"logo\";s:59:\"/storage/logos/QSyKhQRg1MthfLmWm47IU087yHmNn8HrItLTp4QZ.png\";}', 1759494180);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_02_045652_create_villas_table', 1),
(5, '2025_09_02_045711_create_villa_images_table', 1),
(6, '2025_09_02_045722_create_villa_amenities_table', 1),
(7, '2025_09_02_045732_create_bookings_table', 1),
(8, '2025_09_02_045743_create_payments_table', 1),
(9, '2025_09_02_045755_create_reviews_table', 1),
(10, '2025_09_03_061355_create_admin_users_table', 2),
(12, '2025_09_03_061421_create_booking_status_logs_table', 3),
(13, '2025_09_03_061747_create_permission_tables', 3),
(14, '2025_09_03_061848_create_activity_log_table', 3),
(15, '2025_09_03_061849_add_event_column_to_activity_log_table', 3),
(16, '2025_09_03_061850_add_batch_uuid_column_to_activity_log_table', 3),
(17, '2025_09_03_071917_add_refund_fields_to_payments_table', 4);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\AdminUser', 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_payment_intent_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `refund_reason` text COLLATE utf8mb4_unicode_ci,
  `refund_amount` decimal(10,2) DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_booking_id_index` (`booking_id`),
  KEY `payments_transaction_id_index` (`transaction_id`),
  KEY `payments_status_index` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `amount`, `payment_method`, `transaction_id`, `stripe_payment_intent_id`, `status`, `processed_at`, `created_at`, `updated_at`, `refund_reason`, `refund_amount`, `refunded_at`) VALUES
(1, 7, 4400.00, 'bank_transfer', '6157accd-db90-48e1-8f4e-4b16ce95fa59', NULL, 'pending', NULL, '2025-09-02 19:00:35', '2025-09-02 19:00:35', NULL, NULL, NULL),
(2, 8, 16000.00, 'paypal', 'PayPal payment failed', NULL, 'failed', NULL, '2025-09-03 05:02:20', '2025-09-03 05:02:20', NULL, NULL, NULL),
(3, 9, 21000.00, 'paypal', 'pp_q1HAkHoH5SJoFMyX', NULL, 'completed', NULL, '2025-09-03 05:03:40', '2025-09-03 05:03:40', NULL, NULL, NULL),
(4, 10, 7500.00, 'paypal', 'PayPal payment failed', NULL, 'failed', NULL, '2025-09-03 05:46:01', '2025-09-03 05:46:01', NULL, NULL, NULL),
(5, 11, 7500.00, 'paypal', 'PayPal payment failed', NULL, 'failed', NULL, '2025-09-03 05:47:57', '2025-09-03 05:47:57', NULL, NULL, NULL),
(6, 12, 16000.00, 'paypal', 'pp_0ERbwgKTooKJAxmR', NULL, 'completed', NULL, '2025-09-03 07:49:58', '2025-09-03 07:49:58', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view-dashboard', 'admin', '2025-09-03 03:21:03', '2025-09-03 03:21:03'),
(2, 'manage-villas', 'admin', '2025-09-03 03:21:03', '2025-09-03 03:21:03'),
(3, 'manage-bookings', 'admin', '2025-09-03 03:21:03', '2025-09-03 03:21:03'),
(4, 'manage-users', 'admin', '2025-09-03 03:21:03', '2025-09-03 03:21:03'),
(5, 'manage-payments', 'admin', '2025-09-03 03:21:03', '2025-09-03 03:21:03'),
(6, 'view-reports', 'admin', '2025-09-03 03:21:03', '2025-09-03 03:21:03'),
(7, 'manage-settings', 'admin', '2025-09-03 03:21:03', '2025-09-03 03:21:03');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `villa_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `rating` int UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_index` (`user_id`),
  KEY `reviews_villa_id_index` (`villa_id`),
  KEY `reviews_booking_id_index` (`booking_id`),
  KEY `reviews_rating_index` (`rating`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'admin', '2025-09-03 03:21:03', '2025-09-03 03:21:03'),
(2, 'admin', 'admin', '2025-09-03 03:21:03', '2025-09-03 03:21:03'),
(3, 'moderator', 'admin', '2025-09-03 03:21:03', '2025-09-03 03:21:03');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(3, 3),
(4, 1),
(5, 1),
(6, 1),
(6, 2),
(7, 1);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4nCPL8nya9V3WKQNvgBFJfNbYRSqE9nLA7CoJN7Q', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Trae/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiU1QxNXNacThuWDNrcHo4djFYcEx2SEJFakFlSjhmMGhOYlhtbENoNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi91c2Vycy8xIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1756904163),
('qVbNr3WFYJ5XgzpP749Qjt6lJ1XQBhLHRj63WEzA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiZlBtNUtkeFJKMmhQUm56ajNMNjQ0dWx2M0tEaDdKbmxJMmVoQllUMiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1756897742),
('HUdADpfnDybYKfDfgYbMp0AZ1RsZnPRg3Itwlgrs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36 Edg/139.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUdiWmtKSHRVdjIycDJ4U2tZVWU3dlR4MEx0SU4zY0d2dmR3UUhWSyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC92aWxsYXMvMSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1756845869),
('1rCahJlampYwa16Dl3fUWMGR2fsHZzduV936huJ0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Trae/1.100.3 Chrome/132.0.6834.210 Electron/34.5.1 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicGUwSjlPTXZ2Y2V3ZktYejFYM3J4UHVBTnVaNE5UMjFzMkF6TmZRdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/aWRlX3dlYnZpZXdfcmVxdWVzdF90aW1lPTE3NTY4OTcyNTg0ODgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1756897258),
('P1A11F4VwsALp9s3ZJhc6tB9grwZfxZsZFQq53l7', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoibzl6eWRHRXd5Rk51aHkwaWZLaXZjQURjdXlhY05BQzY1Wm9Ob2tGdyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7czozOiJ1cmwiO2E6MDp7fXM6NTI6ImxvZ2luX2FkbWluXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1756899436);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('guest','owner','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'guest',
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `role`, `avatar`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', NULL, '2025-09-02 15:33:25', '$2y$12$2LbPkZ.fCcoYQTxh09MyKe0sGIIunY1iYZWA2P6q/z57bk8t1SFAu', 'guest', NULL, 1, 'BnGKkTi4A7', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(2, 'Villa Sahibi', 'owner@villarentals.com', NULL, '2025-09-02 15:33:25', '$2y$12$bmuZIMKeZBn0wXMmr/PewO.cPdxxqalhsHT7y9wwWMv0xCvJbth.e', 'owner', NULL, 1, NULL, '2025-09-02 15:33:25', '2025-09-02 15:33:25');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `villas`
--

DROP TABLE IF EXISTS `villas`;
CREATE TABLE IF NOT EXISTS `villas` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` bigint UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `max_guests` int NOT NULL,
  `bedrooms` int NOT NULL,
  `bathrooms` int NOT NULL,
  `area_sqm` int DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `villas_owner_id_foreign` (`owner_id`),
  KEY `villas_city_index` (`city`),
  KEY `villas_price_per_night_index` (`price_per_night`),
  KEY `villas_latitude_longitude_index` (`latitude`,`longitude`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `villas`
--

INSERT INTO `villas` (`id`, `owner_id`, `title`, `description`, `address`, `city`, `country`, `latitude`, `longitude`, `price_per_night`, `max_guests`, `bedrooms`, `bathrooms`, `area_sqm`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 2, 'Lüks Deniz Manzaralı Villa - Bodrum', 'Muhteşem deniz manzaralı, özel havuzlu ve modern tasarımlı lüks villa. Bodrum\'un en prestijli bölgelerinden birinde yer alan bu villa, unutulmaz bir tatil deneyimi sunar.', 'Yalıkavak Mahallesi, Tilkicik Caddesi No:15', 'Bodrum', 'Türkiye', 37.10810000, 27.26120000, 2500.00, 8, 4, 3, 350, 1, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(2, 2, 'Modern Villa - Antalya Kalkan', 'Kalkan\'ın eşsiz manzarasına sahip modern villa. Infinity havuz, geniş teraslar ve lüks iç mekan tasarımı ile mükemmel bir tatil.', 'Kalkan Mahallesi, Yalıboyu Mevkii', 'Antalya', 'Türkiye', 36.26670000, 29.63330000, 3200.00, 10, 5, 4, 450, 1, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(3, 2, 'Şık Villa - Çeşme Alaçatı', 'Alaçatı\'nın rüzgar ve sörf cennetinde, geleneksel Ege mimarisi ile modern konforu birleştiren şık villa.', 'Alaçatı Mahallesi, Kemalpaşa Caddesi No:42', 'Çeşme', 'Türkiye', 38.30000000, 26.36670000, 1800.00, 6, 3, 2, 280, 1, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(4, 2, 'Butik Villa - Kaş', 'Kaş\'ın sakin atmosferinde, Akdeniz\'in turkuaz sularına nazır butik villa. Doğa ile iç içe huzurlu bir tatil.', 'Kaş Merkez, Hastane Caddesi No:28', 'Kaş', 'Türkiye', 36.20200000, 29.64170000, 2200.00, 7, 3, 3, 320, 1, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(5, 2, 'Premium Villa - Fethiye Göcek', 'Göcek\'in eşsiz doğası içinde, yat limanına yakın premium villa. Lüks ve konfor bir arada.', 'Göcek Mahallesi, Marina Caddesi No:8', 'Fethiye', 'Türkiye', 36.75000000, 28.93330000, 2800.00, 9, 4, 4, 400, 1, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(6, 2, 'Lüks Dağ Evi - Kapadokya', 'Kapadokya\'nın büyülü manzarasında, geleneksel taş mimarisi ile modern lüksü birleştiren eşsiz villa.', 'Göreme Mahallesi, Müze Caddesi No:12', 'Nevşehir', 'Türkiye', 38.64310000, 34.83310000, 2000.00, 6, 3, 2, 250, 1, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(7, 2, 'Sahil Villa - Datça', 'Datça\'nın sakin koylarında, denize sıfır konumda özel plajlı villa. Doğa ile baş başa huzur.', 'Datça Merkez, Kargı Koyu Mevkii', 'Datça', 'Türkiye', 36.73330000, 27.68330000, 2600.00, 8, 4, 3, 380, 1, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(8, 2, 'Modern Villa - Marmaris', 'Marmaris\'in canlı atmosferinde, marina manzaralı modern villa. Gece hayatına yakın, lüks konaklama.', 'Marmaris Merkez, Barbaros Caddesi No:55', 'Marmaris', 'Türkiye', 36.85000000, 28.26670000, 2100.00, 7, 3, 3, 300, 1, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(9, 2, 'Tarihi Villa - Şirince', 'Şirince\'nin tarihi dokusunda, restore edilmiş geleneksel Rum evi. Bağ manzaralı otantik deneyim.', 'Şirince Köyü, Çamlık Sokak No:7', 'İzmir', 'Türkiye', 37.95000000, 27.45000000, 1500.00, 5, 2, 2, 200, 1, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(10, 2, 'Lüks Koy Villa - Akyaka', 'Akyaka\'nın sakin koyunda, rüzgar sörfü merkezine yakın lüks villa. Doğa sporları tutkunları için ideal.', 'Akyaka Mahallesi, Azmak Nehri Yanı', 'Muğla', 'Türkiye', 37.05000000, 28.31670000, 1900.00, 6, 3, 2, 270, 1, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(11, 2, 'Lüks Deniz Manzaralı Villa - Bodrum', 'Muhteşem deniz manzaralı, özel havuzlu ve modern tasarımlı lüks villa. Bodrum\'un en prestijli bölgelerinden birinde yer alan bu villa, unutulmaz bir tatil deneyimi sunar.', 'Yalıkavak Mahallesi, Tilkicik Caddesi No:15', 'Bodrum', 'Türkiye', 37.10810000, 27.26120000, 2500.00, 8, 4, 3, 350, 1, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(12, 2, 'Modern Villa - Antalya Kalkan', 'Kalkan\'ın eşsiz manzarasına sahip modern villa. Infinity havuz, geniş teraslar ve lüks iç mekan tasarımı ile mükemmel bir tatil.', 'Kalkan Mahallesi, Yalıboyu Mevkii', 'Antalya', 'Türkiye', 36.26670000, 29.63330000, 3200.00, 10, 5, 4, 450, 1, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(13, 2, 'Şık Villa - Çeşme Alaçatı', 'Alaçatı\'nın rüzgar ve sörf cennetinde, geleneksel Ege mimarisi ile modern konforu birleştiren şık villa.', 'Alaçatı Mahallesi, Kemalpaşa Caddesi No:42', 'Çeşme', 'Türkiye', 38.30000000, 26.36670000, 1800.00, 6, 3, 2, 280, 1, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(14, 2, 'Butik Villa - Kaş', 'Kaş\'ın sakin atmosferinde, Akdeniz\'in turkuaz sularına nazır butik villa. Doğa ile iç içe huzurlu bir tatil.', 'Kaş Merkez, Hastane Caddesi No:28', 'Kaş', 'Türkiye', 36.20200000, 29.64170000, 2200.00, 7, 3, 3, 320, 1, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(15, 2, 'Premium Villa - Fethiye Göcek', 'Göcek\'in eşsiz doğası içinde, yat limanına yakın premium villa. Lüks ve konfor bir arada.', 'Göcek Mahallesi, Marina Caddesi No:8', 'Fethiye', 'Türkiye', 36.75000000, 28.93330000, 2800.00, 9, 4, 4, 400, 1, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(16, 2, 'Lüks Dağ Evi - Kapadokya', 'Kapadokya\'nın büyülü manzarasında, geleneksel taş mimarisi ile modern lüksü birleştiren eşsiz villa.', 'Göreme Mahallesi, Müze Caddesi No:12', 'Nevşehir', 'Türkiye', 38.64310000, 34.83310000, 2000.00, 6, 3, 2, 250, 1, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(17, 2, 'Sahil Villa - Datça', 'Datça\'nın sakin koylarında, denize sıfır konumda özel plajlı villa. Doğa ile baş başa huzur.', 'Datça Merkez, Kargı Koyu Mevkii', 'Datça', 'Türkiye', 36.73330000, 27.68330000, 2600.00, 8, 4, 3, 380, 1, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(18, 2, 'Modern Villa - Marmaris', 'Marmaris\'in canlı atmosferinde, marina manzaralı modern villa. Gece hayatına yakın, lüks konaklama.', 'Marmaris Merkez, Barbaros Caddesi No:55', 'Marmaris', 'Türkiye', 36.85000000, 28.26670000, 2100.00, 7, 3, 3, 300, 1, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(19, 2, 'Tarihi Villa - Şirince', 'Şirince\'nin tarihi dokusunda, restore edilmiş geleneksel Rum evi. Bağ manzaralı otantik deneyim.', 'Şirince Köyü, Çamlık Sokak No:7', 'İzmir', 'Türkiye', 37.95000000, 27.45000000, 1500.00, 5, 2, 2, 200, 1, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(20, 2, 'Lüks Koy Villa - Akyaka', 'Akyaka\'nın sakin koyunda, rüzgar sörfü merkezine yakın lüks villa. Doğa sporları tutkunları için ideal.', 'Akyaka Mahallesi, Azmak Nehri Yanı', 'Muğla', 'Türkiye', 37.05000000, 28.31670000, 1900.00, 6, 3, 2, 270, 1, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(21, 2, 'Lüks Deniz Manzaralı Villa - Bodrum', 'Muhteşem deniz manzaralı, özel havuzlu ve modern tasarımlı lüks villa. Bodrum\'un en prestijli bölgelerinden birinde yer alan bu villa, unutulmaz bir tatil deneyimi sunar.', 'Yalıkavak Mahallesi, Tilkicik Caddesi No:15', 'Bodrum', 'Türkiye', 37.10810000, 27.26120000, 2500.00, 8, 4, 3, 350, 1, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(22, 2, 'Modern Villa - Antalya Kalkan', 'Kalkan\'ın eşsiz manzarasına sahip modern villa. Infinity havuz, geniş teraslar ve lüks iç mekan tasarımı ile mükemmel bir tatil.', 'Kalkan Mahallesi, Yalıboyu Mevkii', 'Antalya', 'Türkiye', 36.26670000, 29.63330000, 3200.00, 10, 5, 4, 450, 1, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(23, 2, 'Şık Villa - Çeşme Alaçatı', 'Alaçatı\'nın rüzgar ve sörf cennetinde, geleneksel Ege mimarisi ile modern konforu birleştiren şık villa.', 'Alaçatı Mahallesi, Kemalpaşa Caddesi No:42', 'Çeşme', 'Türkiye', 38.30000000, 26.36670000, 1800.00, 6, 3, 2, 280, 1, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(24, 2, 'Butik Villa - Kaş', 'Kaş\'ın sakin atmosferinde, Akdeniz\'in turkuaz sularına nazır butik villa. Doğa ile iç içe huzurlu bir tatil.', 'Kaş Merkez, Hastane Caddesi No:28', 'Kaş', 'Türkiye', 36.20200000, 29.64170000, 2200.00, 7, 3, 3, 320, 1, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(25, 2, 'Premium Villa - Fethiye Göcek', 'Göcek\'in eşsiz doğası içinde, yat limanına yakın premium villa. Lüks ve konfor bir arada.', 'Göcek Mahallesi, Marina Caddesi No:8', 'Fethiye', 'Türkiye', 36.75000000, 28.93330000, 2800.00, 9, 4, 4, 400, 1, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(26, 2, 'Lüks Dağ Evi - Kapadokya', 'Kapadokya\'nın büyülü manzarasında, geleneksel taş mimarisi ile modern lüksü birleştiren eşsiz villa.', 'Göreme Mahallesi, Müze Caddesi No:12', 'Nevşehir', 'Türkiye', 38.64310000, 34.83310000, 2000.00, 6, 3, 2, 250, 1, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(27, 2, 'Sahil Villa - Datça', 'Datça\'nın sakin koylarında, denize sıfır konumda özel plajlı villa. Doğa ile baş başa huzur.', 'Datça Merkez, Kargı Koyu Mevkii', 'Datça', 'Türkiye', 36.73330000, 27.68330000, 2600.00, 8, 4, 3, 380, 1, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(28, 2, 'Modern Villa - Marmaris', 'Marmaris\'in canlı atmosferinde, marina manzaralı modern villa. Gece hayatına yakın, lüks konaklama.', 'Marmaris Merkez, Barbaros Caddesi No:55', 'Marmaris', 'Türkiye', 36.85000000, 28.26670000, 2100.00, 7, 3, 3, 300, 1, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(29, 2, 'Tarihi Villa - Şirince', 'Şirince\'nin tarihi dokusunda, restore edilmiş geleneksel Rum evi. Bağ manzaralı otantik deneyim.', 'Şirince Köyü, Çamlık Sokak No:7', 'İzmir', 'Türkiye', 37.95000000, 27.45000000, 1500.00, 5, 2, 2, 200, 1, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(30, 2, 'Lüks Koy Villa - Akyaka', 'Akyaka\'nın sakin koyunda, rüzgar sörfü merkezine yakın lüks villa. Doğa sporları tutkunları için ideal.', 'Akyaka Mahallesi, Azmak Nehri Yanı', 'Muğla', 'Türkiye', 37.05000000, 28.31670000, 1900.00, 6, 3, 2, 270, 1, '2025-09-03 04:45:46', '2025-09-03 04:45:46');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `villa_amenities`
--

DROP TABLE IF EXISTS `villa_amenities`;
CREATE TABLE IF NOT EXISTS `villa_amenities` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `villa_id` bigint UNSIGNED NOT NULL,
  `amenity_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amenity_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `villa_amenities_villa_id_index` (`villa_id`),
  KEY `villa_amenities_amenity_type_index` (`amenity_type`)
) ENGINE=MyISAM AUTO_INCREMENT=190 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `villa_amenities`
--

INSERT INTO `villa_amenities` (`id`, `villa_id`, `amenity_type`, `amenity_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'pool', 'pool', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(2, 1, 'wifi', 'wifi', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(3, 1, 'parking', 'parking', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(4, 1, 'sea_view', 'sea_view', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(5, 1, 'air_conditioning', 'air_conditioning', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(6, 1, 'kitchen', 'kitchen', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(7, 2, 'pool', 'pool', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(8, 2, 'wifi', 'wifi', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(9, 2, 'parking', 'parking', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(10, 2, 'sea_view', 'sea_view', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(11, 2, 'air_conditioning', 'air_conditioning', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(12, 2, 'kitchen', 'kitchen', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(13, 2, 'garden', 'garden', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(14, 3, 'wifi', 'wifi', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(15, 3, 'parking', 'parking', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(16, 3, 'air_conditioning', 'air_conditioning', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(17, 3, 'kitchen', 'kitchen', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(18, 3, 'garden', 'garden', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(19, 3, 'terrace', 'terrace', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(20, 4, 'pool', 'pool', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(21, 4, 'wifi', 'wifi', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(22, 4, 'parking', 'parking', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(23, 4, 'sea_view', 'sea_view', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(24, 4, 'air_conditioning', 'air_conditioning', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(25, 4, 'kitchen', 'kitchen', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(26, 5, 'pool', 'pool', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(27, 5, 'wifi', 'wifi', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(28, 5, 'parking', 'parking', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(29, 5, 'sea_view', 'sea_view', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(30, 5, 'air_conditioning', 'air_conditioning', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(31, 5, 'kitchen', 'kitchen', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(32, 5, 'garden', 'garden', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(33, 6, 'wifi', 'wifi', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(34, 6, 'parking', 'parking', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(35, 6, 'air_conditioning', 'air_conditioning', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(36, 6, 'kitchen', 'kitchen', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(37, 6, 'fireplace', 'fireplace', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(38, 6, 'terrace', 'terrace', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(39, 7, 'pool', 'pool', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(40, 7, 'wifi', 'wifi', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(41, 7, 'parking', 'parking', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(42, 7, 'sea_view', 'sea_view', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(43, 7, 'air_conditioning', 'air_conditioning', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(44, 7, 'kitchen', 'kitchen', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(45, 7, 'private_beach', 'private_beach', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(46, 8, 'pool', 'pool', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(47, 8, 'wifi', 'wifi', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(48, 8, 'parking', 'parking', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(49, 8, 'sea_view', 'sea_view', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(50, 8, 'air_conditioning', 'air_conditioning', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(51, 8, 'kitchen', 'kitchen', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(52, 9, 'wifi', 'wifi', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(53, 9, 'parking', 'parking', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(54, 9, 'air_conditioning', 'air_conditioning', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(55, 9, 'kitchen', 'kitchen', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(56, 9, 'garden', 'garden', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(57, 9, 'vineyard_view', 'vineyard_view', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(58, 10, 'wifi', 'wifi', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(59, 10, 'parking', 'parking', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(60, 10, 'air_conditioning', 'air_conditioning', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(61, 10, 'kitchen', 'kitchen', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(62, 10, 'garden', 'garden', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(63, 10, 'river_view', 'river_view', '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(64, 11, 'pool', 'pool', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(65, 11, 'wifi', 'wifi', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(66, 11, 'parking', 'parking', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(67, 11, 'sea_view', 'sea_view', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(68, 11, 'air_conditioning', 'air_conditioning', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(69, 11, 'kitchen', 'kitchen', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(70, 12, 'pool', 'pool', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(71, 12, 'wifi', 'wifi', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(72, 12, 'parking', 'parking', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(73, 12, 'sea_view', 'sea_view', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(74, 12, 'air_conditioning', 'air_conditioning', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(75, 12, 'kitchen', 'kitchen', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(76, 12, 'garden', 'garden', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(77, 13, 'wifi', 'wifi', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(78, 13, 'parking', 'parking', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(79, 13, 'air_conditioning', 'air_conditioning', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(80, 13, 'kitchen', 'kitchen', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(81, 13, 'garden', 'garden', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(82, 13, 'terrace', 'terrace', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(83, 14, 'pool', 'pool', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(84, 14, 'wifi', 'wifi', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(85, 14, 'parking', 'parking', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(86, 14, 'sea_view', 'sea_view', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(87, 14, 'air_conditioning', 'air_conditioning', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(88, 14, 'kitchen', 'kitchen', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(89, 15, 'pool', 'pool', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(90, 15, 'wifi', 'wifi', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(91, 15, 'parking', 'parking', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(92, 15, 'sea_view', 'sea_view', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(93, 15, 'air_conditioning', 'air_conditioning', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(94, 15, 'kitchen', 'kitchen', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(95, 15, 'garden', 'garden', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(96, 16, 'wifi', 'wifi', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(97, 16, 'parking', 'parking', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(98, 16, 'air_conditioning', 'air_conditioning', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(99, 16, 'kitchen', 'kitchen', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(100, 16, 'fireplace', 'fireplace', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(101, 16, 'terrace', 'terrace', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(102, 17, 'pool', 'pool', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(103, 17, 'wifi', 'wifi', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(104, 17, 'parking', 'parking', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(105, 17, 'sea_view', 'sea_view', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(106, 17, 'air_conditioning', 'air_conditioning', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(107, 17, 'kitchen', 'kitchen', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(108, 17, 'private_beach', 'private_beach', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(109, 18, 'pool', 'pool', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(110, 18, 'wifi', 'wifi', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(111, 18, 'parking', 'parking', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(112, 18, 'sea_view', 'sea_view', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(113, 18, 'air_conditioning', 'air_conditioning', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(114, 18, 'kitchen', 'kitchen', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(115, 19, 'wifi', 'wifi', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(116, 19, 'parking', 'parking', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(117, 19, 'air_conditioning', 'air_conditioning', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(118, 19, 'kitchen', 'kitchen', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(119, 19, 'garden', 'garden', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(120, 19, 'vineyard_view', 'vineyard_view', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(121, 20, 'wifi', 'wifi', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(122, 20, 'parking', 'parking', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(123, 20, 'air_conditioning', 'air_conditioning', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(124, 20, 'kitchen', 'kitchen', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(125, 20, 'garden', 'garden', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(126, 20, 'river_view', 'river_view', '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(127, 21, 'pool', 'pool', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(128, 21, 'wifi', 'wifi', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(129, 21, 'parking', 'parking', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(130, 21, 'sea_view', 'sea_view', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(131, 21, 'air_conditioning', 'air_conditioning', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(132, 21, 'kitchen', 'kitchen', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(133, 22, 'pool', 'pool', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(134, 22, 'wifi', 'wifi', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(135, 22, 'parking', 'parking', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(136, 22, 'sea_view', 'sea_view', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(137, 22, 'air_conditioning', 'air_conditioning', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(138, 22, 'kitchen', 'kitchen', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(139, 22, 'garden', 'garden', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(140, 23, 'wifi', 'wifi', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(141, 23, 'parking', 'parking', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(142, 23, 'air_conditioning', 'air_conditioning', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(143, 23, 'kitchen', 'kitchen', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(144, 23, 'garden', 'garden', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(145, 23, 'terrace', 'terrace', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(146, 24, 'pool', 'pool', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(147, 24, 'wifi', 'wifi', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(148, 24, 'parking', 'parking', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(149, 24, 'sea_view', 'sea_view', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(150, 24, 'air_conditioning', 'air_conditioning', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(151, 24, 'kitchen', 'kitchen', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(152, 25, 'pool', 'pool', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(153, 25, 'wifi', 'wifi', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(154, 25, 'parking', 'parking', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(155, 25, 'sea_view', 'sea_view', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(156, 25, 'air_conditioning', 'air_conditioning', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(157, 25, 'kitchen', 'kitchen', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(158, 25, 'garden', 'garden', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(159, 26, 'wifi', 'wifi', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(160, 26, 'parking', 'parking', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(161, 26, 'air_conditioning', 'air_conditioning', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(162, 26, 'kitchen', 'kitchen', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(163, 26, 'fireplace', 'fireplace', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(164, 26, 'terrace', 'terrace', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(165, 27, 'pool', 'pool', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(166, 27, 'wifi', 'wifi', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(167, 27, 'parking', 'parking', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(168, 27, 'sea_view', 'sea_view', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(169, 27, 'air_conditioning', 'air_conditioning', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(170, 27, 'kitchen', 'kitchen', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(171, 27, 'private_beach', 'private_beach', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(172, 28, 'pool', 'pool', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(173, 28, 'wifi', 'wifi', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(174, 28, 'parking', 'parking', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(175, 28, 'sea_view', 'sea_view', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(176, 28, 'air_conditioning', 'air_conditioning', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(177, 28, 'kitchen', 'kitchen', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(178, 29, 'wifi', 'wifi', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(179, 29, 'parking', 'parking', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(180, 29, 'air_conditioning', 'air_conditioning', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(181, 29, 'kitchen', 'kitchen', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(182, 29, 'garden', 'garden', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(183, 29, 'vineyard_view', 'vineyard_view', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(184, 30, 'wifi', 'wifi', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(185, 30, 'parking', 'parking', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(186, 30, 'air_conditioning', 'air_conditioning', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(187, 30, 'kitchen', 'kitchen', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(188, 30, 'garden', 'garden', '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(189, 30, 'river_view', 'river_view', '2025-09-03 04:45:46', '2025-09-03 04:45:46');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `villa_images`
--

DROP TABLE IF EXISTS `villa_images`;
CREATE TABLE IF NOT EXISTS `villa_images` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `villa_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `villa_images_villa_id_index` (`villa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `villa_images`
--

INSERT INTO `villa_images` (`id`, `villa_id`, `image_path`, `is_primary`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'villas/sample-villa-1.jpg', 1, 0, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(2, 2, 'villas/sample-villa-2.jpg', 1, 0, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(3, 3, 'villas/sample-villa-3.jpg', 1, 0, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(4, 4, 'villas/sample-villa-4.jpg', 1, 0, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(5, 5, 'villas/sample-villa-5.jpg', 1, 0, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(6, 6, 'villas/sample-villa-6.jpg', 1, 0, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(7, 7, 'villas/sample-villa-7.jpg', 1, 0, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(8, 8, 'villas/sample-villa-8.jpg', 1, 0, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(9, 9, 'villas/sample-villa-9.jpg', 1, 0, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(10, 10, 'villas/sample-villa-10.jpg', 1, 0, '2025-09-02 15:33:25', '2025-09-02 15:33:25'),
(11, 11, 'villas/sample-villa-11.jpg', 1, 0, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(12, 12, 'villas/sample-villa-12.jpg', 1, 0, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(13, 13, 'villas/sample-villa-13.jpg', 1, 0, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(14, 14, 'villas/sample-villa-14.jpg', 1, 0, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(15, 15, 'villas/sample-villa-15.jpg', 1, 0, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(16, 16, 'villas/sample-villa-16.jpg', 1, 0, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(17, 17, 'villas/sample-villa-17.jpg', 1, 0, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(18, 18, 'villas/sample-villa-18.jpg', 1, 0, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(19, 19, 'villas/sample-villa-19.jpg', 1, 0, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(20, 20, 'villas/sample-villa-20.jpg', 1, 0, '2025-09-02 18:03:07', '2025-09-02 18:03:07'),
(21, 21, 'villas/sample-villa-21.jpg', 1, 0, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(22, 22, 'villas/sample-villa-22.jpg', 1, 0, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(23, 23, 'villas/sample-villa-23.jpg', 1, 0, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(24, 24, 'villas/sample-villa-24.jpg', 1, 0, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(25, 25, 'villas/sample-villa-25.jpg', 1, 0, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(26, 26, 'villas/sample-villa-26.jpg', 1, 0, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(27, 27, 'villas/sample-villa-27.jpg', 1, 0, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(28, 28, 'villas/sample-villa-28.jpg', 1, 0, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(29, 29, 'villas/sample-villa-29.jpg', 1, 0, '2025-09-03 04:45:46', '2025-09-03 04:45:46'),
(30, 30, 'villas/sample-villa-30.jpg', 1, 0, '2025-09-03 04:45:46', '2025-09-03 04:45:46');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
