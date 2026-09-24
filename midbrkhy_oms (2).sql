-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 04, 2026 at 02:56 PM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `midbrkhy_oms`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_on_tasks`
--

CREATE TABLE `add_on_tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `interrupted_task_id` bigint(20) UNSIGNED NOT NULL,
  `add_on_task_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `interrupted_at` datetime NOT NULL,
  `reason` text,
  `resumed_at` datetime DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`setting_key`, `setting_value`) VALUES
('tl_can_create_content_types', '1');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `auditable_type` varchar(191) NOT NULL,
  `auditable_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(191) NOT NULL COMMENT 'created, updated, status_changed, assigned, reassigned, delay_requested, delay_approved, delay_rejected, comment_added, attachment_uploaded, add_on_flagged, ...',
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(191) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}', 1788587640);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(191) NOT NULL,
  `owner` varchar(191) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `company` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `status` enum('active','hold','inactive') NOT NULL DEFAULT 'active',
  `is_priority` tinyint(1) NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `company`, `email`, `phone`, `status`, `is_priority`, `start_date`, `end_date`, `department_id`, `created_by`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(74, 'Star Auto', NULL, NULL, NULL, 'active', 1, NULL, NULL, 4, 1, NULL, '2026-08-07 07:38:10', '2026-08-10 00:03:50', NULL),
(75, 'Casa deco', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(76, 'Kanti jewellers', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(77, 'Parekh jewellers', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(78, 'Mrunali diet', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(79, 'TATC', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(80, 'BUE Interior Designer', NULL, NULL, NULL, 'active', 0, NULL, NULL, 1, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 06:38:55', NULL),
(81, 'Flounce & Flare', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(82, 'Srushti IVF', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(83, 'Venust Clinic', NULL, NULL, NULL, 'hold', 0, NULL, NULL, 4, 1, NULL, '2026-08-07 07:38:10', '2026-08-19 03:16:56', NULL),
(84, 'KK Hosiery', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(85, 'Caringhood', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(86, 'NBD Pathology', NULL, NULL, NULL, 'inactive', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(87, 'H&M', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(88, 'CITC', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(89, 'Vishwam', NULL, NULL, NULL, 'hold', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(90, 'Dr. Digish Thakkar', NULL, NULL, NULL, 'active', 0, NULL, NULL, 4, 1, NULL, '2026-08-07 07:38:10', '2026-08-19 03:15:31', NULL),
(91, 'Blue Dumond', NULL, NULL, NULL, 'hold', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(92, 'Bharari', NULL, NULL, NULL, 'active', 0, NULL, NULL, 4, 1, NULL, '2026-08-07 07:38:10', '2026-08-19 03:15:39', NULL),
(93, 'CGV', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(94, 'Chirayu', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(95, 'Candid', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(96, 'Lifecare Homeopathy', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(97, 'Moshi', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(98, 'OM Dental', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(99, 'Trupti Ayurveda', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(100, 'Dr. Giri', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(101, 'Guru Global', NULL, NULL, NULL, 'inactive', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(102, 'Kesarsai Developer', NULL, NULL, NULL, 'inactive', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(103, 'Celebrino Villa', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(104, 'Enhance', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(105, 'Learning Curve (Viman Nagar)', NULL, NULL, NULL, 'active', 0, NULL, NULL, 4, 1, NULL, '2026-08-07 07:38:10', '2026-08-19 03:15:58', NULL),
(106, 'Glory School', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(107, 'Pathshala', NULL, NULL, NULL, 'active', 0, NULL, NULL, 2, 1, NULL, '2026-08-07 07:38:10', '2026-08-07 07:38:10', NULL),
(108, 'Tamara SPA', NULL, NULL, NULL, 'active', 0, NULL, NULL, 4, 1, NULL, '2026-08-07 07:38:10', '2026-08-19 03:15:50', NULL),
(109, 'Renuka Hydraulics', 'Renuka Hydraulics', NULL, NULL, 'active', 0, NULL, NULL, 7, 1, NULL, '2026-08-26 18:56:19', '2026-08-26 18:56:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_plan_items`
--

CREATE TABLE `client_plan_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `platform` varchar(191) NOT NULL COMMENT 'e.g. Facebook, Instagram, LinkedIn, YouTube',
  `content_type_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `notes` varchar(191) DEFAULT NULL,
  `setup_done` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client_plan_items`
--

INSERT INTO `client_plan_items` (`id`, `client_id`, `platform`, `content_type_id`, `quantity`, `notes`, `setup_done`, `created_at`, `updated_at`) VALUES
(63, 75, 'Facebook', 1, 7, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(64, 75, 'Instagram', 1, 7, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(65, 75, 'LinkedIn', 1, 7, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(66, 75, 'YouTube', 1, 7, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(67, 76, 'Instagram', 1, 8, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(68, 76, 'LinkedIn', 1, 8, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(69, 77, 'LinkedIn', 1, 0, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(70, 78, 'Facebook', 1, 8, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(71, 78, 'Instagram', 1, 8, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(72, 78, 'LinkedIn', 1, 8, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(73, 78, 'YouTube', 1, 8, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(74, 79, 'Facebook', 1, 7, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(75, 79, 'Instagram', 1, 7, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(76, 79, 'LinkedIn', 1, 7, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(77, 79, 'YouTube', 1, 7, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(82, 81, 'Facebook', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(83, 81, 'Instagram', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(84, 81, 'LinkedIn', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(85, 81, 'YouTube', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(86, 82, 'Facebook', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(87, 82, 'Instagram', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(88, 82, 'LinkedIn', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(89, 82, 'YouTube', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(94, 84, 'Facebook', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(95, 84, 'Instagram', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(96, 84, 'LinkedIn', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(97, 85, 'Facebook', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(98, 85, 'Instagram', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(99, 85, 'LinkedIn', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(100, 86, 'Facebook', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(101, 86, 'Instagram', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(102, 86, 'LinkedIn', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(103, 87, 'Facebook', 1, 6, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(104, 87, 'Instagram', 1, 6, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(105, 87, 'LinkedIn', 1, 6, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(106, 88, 'Facebook', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(107, 88, 'Instagram', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(108, 88, 'LinkedIn', 1, 4, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(115, 93, 'Facebook', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(116, 93, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(117, 93, 'LinkedIn', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(118, 94, 'Facebook', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(119, 94, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(120, 95, 'Facebook', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(121, 95, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(122, 96, 'Facebook', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(123, 96, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(124, 97, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(125, 98, 'Facebook', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(126, 98, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(127, 99, 'Facebook', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(128, 99, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(129, 102, 'Facebook', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(130, 102, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(131, 103, 'Facebook', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(132, 103, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(133, 104, 'Facebook', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(134, 104, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(137, 106, 'Facebook', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(138, 106, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(139, 107, 'Facebook', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(140, 107, 'Instagram', 1, 2, NULL, 0, '2026-08-07 07:41:03', '2026-08-07 07:41:03'),
(298, 74, 'Facebook', 3, 2, '', 0, '2026-08-14 01:31:07', '2026-08-14 01:31:07'),
(299, 74, 'Facebook', 2, 4, '', 0, '2026-08-14 01:31:07', '2026-08-14 01:31:07'),
(300, 74, 'Facebook', 1, 4, '', 0, '2026-08-14 01:31:07', '2026-08-14 01:31:07'),
(301, 74, 'Instagram', 3, 1, '', 0, '2026-08-14 01:31:07', '2026-08-14 01:31:07'),
(302, 74, 'Instagram', 2, 4, '', 0, '2026-08-14 01:31:07', '2026-08-14 01:31:07'),
(303, 74, 'Instagram', 1, 4, '', 0, '2026-08-14 01:31:07', '2026-08-14 01:31:07'),
(309, 90, 'Facebook', 1, 3, NULL, 0, '2026-08-19 03:15:31', '2026-08-19 03:15:31'),
(310, 90, 'Instagram', 1, 3, NULL, 0, '2026-08-19 03:15:31', '2026-08-19 03:15:31'),
(311, 92, 'Facebook', 1, 3, NULL, 0, '2026-08-19 03:15:39', '2026-08-19 03:15:39'),
(312, 92, 'Instagram', 1, 3, NULL, 0, '2026-08-19 03:15:39', '2026-08-19 03:15:39'),
(313, 92, 'LinkedIn', 1, 3, NULL, 0, '2026-08-19 03:15:39', '2026-08-19 03:15:39'),
(314, 92, 'YouTube', 1, 3, NULL, 0, '2026-08-19 03:15:39', '2026-08-19 03:15:39'),
(315, 108, 'Facebook', 1, 4, NULL, 0, '2026-08-19 03:15:50', '2026-08-19 03:15:50'),
(316, 108, 'Instagram', 1, 4, NULL, 0, '2026-08-19 03:15:50', '2026-08-19 03:15:50'),
(317, 105, 'Facebook', 1, 2, NULL, 0, '2026-08-19 03:15:58', '2026-08-19 03:15:58'),
(318, 105, 'Instagram', 1, 2, NULL, 0, '2026-08-19 03:15:58', '2026-08-19 03:15:58'),
(319, 83, 'Facebook', 1, 4, NULL, 0, '2026-08-19 03:16:56', '2026-08-19 03:16:56'),
(320, 83, 'Facebook', 2, 0, '', 0, '2026-08-19 03:16:56', '2026-08-19 03:16:56'),
(321, 83, 'Instagram', 1, 4, NULL, 0, '2026-08-19 03:16:56', '2026-08-19 03:16:56'),
(322, 83, 'LinkedIn', 1, 4, NULL, 0, '2026-08-19 03:16:56', '2026-08-19 03:16:56'),
(323, 83, 'YouTube', 1, 4, NULL, 0, '2026-08-19 03:16:56', '2026-08-19 03:16:56');

-- --------------------------------------------------------

--
-- Table structure for table `client_service`
--

CREATE TABLE `client_service` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client_service`
--

INSERT INTO `client_service` (`id`, `client_id`, `service_id`, `created_at`, `updated_at`) VALUES
(9, 74, 4, '2026-08-17 01:51:27', '2026-08-17 01:51:27'),
(11, 74, 8, '2026-08-17 01:51:40', '2026-08-17 01:51:40'),
(12, 74, 9, '2026-08-17 01:51:43', '2026-08-17 01:51:43');

-- --------------------------------------------------------

--
-- Table structure for table `content_types`
--

CREATE TABLE `content_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `content_types`
--

INSERT INTO `content_types` (`id`, `name`, `is_system`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Reel', 1, NULL, '2026-08-13 07:01:04', '2026-08-13 07:01:04', NULL),
(2, 'Post', 1, NULL, '2026-08-13 07:01:04', '2026-08-13 07:01:04', NULL),
(3, 'AI Video', 0, 1, '2026-08-13 07:26:56', '2026-08-13 07:26:56', NULL),
(4, 'Podcast', 0, 6, '2026-08-14 01:40:14', '2026-08-14 01:40:14', NULL),
(5, 'Festival GIF', 0, 6, '2026-08-14 01:40:32', '2026-08-14 01:40:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `delay_requests`
--

CREATE TABLE `delay_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `reason` text NOT NULL,
  `requested_new_due_date` datetime NOT NULL,
  `original_due_date` datetime NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `review_comment` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `code` varchar(191) NOT NULL,
  `parent_department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `parent_department_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Management', 'MGMT', NULL, 1, '2026-07-27 02:52:09', '2026-07-27 02:52:09', NULL),
(2, 'Engineering', 'ENG', NULL, 1, '2026-07-28 00:32:54', '2026-07-28 00:32:54', NULL),
(3, 'Sales', 'SALES', NULL, 1, '2026-07-28 00:32:54', '2026-08-19 00:33:36', '2026-08-19 00:33:36'),
(4, 'Social Media', 'SM', NULL, 1, '2026-07-28 05:10:02', '2026-07-28 05:10:02', NULL),
(5, 'Video Editor', 'VE', NULL, 1, '2026-07-28 05:10:18', '2026-08-19 00:33:30', '2026-08-19 00:33:30'),
(6, 'Digital Marketing', 'DM', NULL, 1, '2026-08-25 19:30:03', '2026-08-25 19:30:03', NULL),
(7, 'Developer', 'DEV', NULL, 1, '2026-08-25 19:30:21', '2026-08-25 19:30:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `menu_permissions`
--

CREATE TABLE `menu_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `module_key` varchar(100) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menu_permissions`
--

INSERT INTO `menu_permissions` (`id`, `module_key`, `role_name`, `department_id`, `is_visible`, `created_at`, `updated_at`) VALUES
(1, 'dashboard', 'Super Admin', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(2, 'dashboard', 'Manager', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(3, 'dashboard', 'Team Lead', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(4, 'dashboard', 'Employee', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(5, 'employees', 'Super Admin', NULL, 1, '2026-08-27 00:05:51', '2026-08-26 18:49:56'),
(6, 'employees', 'Manager', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(7, 'employees', 'Team Lead', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(8, 'employees', 'Employee', NULL, 0, '2026-08-27 00:05:51', '2026-08-26 18:50:07'),
(9, 'departments', 'Super Admin', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(10, 'departments', 'Manager', NULL, 0, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(11, 'departments', 'Team Lead', NULL, 0, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(12, 'departments', 'Employee', NULL, 0, '2026-08-27 00:05:51', '2026-08-26 23:41:39'),
(13, 'tasks', 'Super Admin', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(14, 'tasks', 'Manager', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(15, 'tasks', 'Team Lead', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(16, 'tasks', 'Employee', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(17, 'clients', 'Super Admin', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(18, 'clients', 'Manager', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(19, 'clients', 'Team Lead', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(20, 'clients', 'Employee', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(21, 'work_log', 'Super Admin', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(22, 'work_log', 'Manager', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(23, 'work_log', 'Team Lead', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(24, 'work_log', 'Employee', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(25, 'settings', 'Super Admin', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(26, 'settings', 'Manager', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(27, 'settings', 'Team Lead', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(28, 'settings', 'Employee', NULL, 1, '2026-08-27 00:05:51', '2026-08-27 00:05:51'),
(29, 'general_clients', 'Super Admin', NULL, 1, '2026-08-26 20:17:11', '2026-08-26 20:17:18'),
(30, 'general_clients', 'Manager', NULL, 1, '2026-08-26 20:19:14', '2026-08-26 20:19:14'),
(31, 'general_clients', 'Team Lead', NULL, 1, '2026-08-26 20:19:14', '2026-08-26 20:19:14'),
(32, 'general_clients', 'Employee', NULL, 0, '2026-08-26 20:19:14', '2026-08-26 20:19:14'),
(36, 'work_log', 'Employee', 4, 0, '2026-08-26 23:47:43', '2026-08-26 23:47:43'),
(37, 'work_log', 'Team Lead', 4, 0, '2026-08-27 01:58:35', '2026-08-27 01:58:35'),
(38, 'tasks', 'Manager', 7, 0, '2026-08-28 05:54:52', '2026-08-28 05:54:52'),
(39, 'tasks', 'Team Lead', 7, 0, '2026-08-28 05:54:53', '2026-08-28 05:54:53'),
(40, 'tasks', 'Employee', 7, 0, '2026-08-28 05:54:55', '2026-08-28 05:54:55'),
(41, 'clients', 'Super Admin', 7, 1, '2026-08-28 05:55:00', '2026-08-31 02:00:01'),
(42, 'clients', 'Manager', 7, 0, '2026-08-28 05:55:01', '2026-08-28 05:55:01'),
(43, 'clients', 'Team Lead', 7, 0, '2026-08-28 05:55:02', '2026-08-28 05:55:02'),
(44, 'clients', 'Employee', 7, 0, '2026-08-28 05:55:03', '2026-08-28 05:55:03'),
(45, 'tasks', 'Super Admin', 2, 1, '2026-08-28 05:55:18', '2026-08-28 05:55:44'),
(46, 'tasks', 'Manager', 2, 0, '2026-08-28 05:55:41', '2026-08-28 05:55:41'),
(47, 'tasks', 'Team Lead', 2, 0, '2026-08-28 05:55:42', '2026-08-28 05:55:42'),
(48, 'tasks', 'Employee', 2, 0, '2026-08-28 05:55:43', '2026-08-28 05:55:43'),
(49, 'tasks', 'Employee', 6, 0, '2026-08-31 01:59:18', '2026-08-31 01:59:18'),
(50, 'clients', 'Employee', 6, 0, '2026-08-31 01:59:35', '2026-08-31 01:59:35'),
(51, 'tasks', 'Team Lead', 6, 0, '2026-08-31 01:59:40', '2026-08-31 01:59:40'),
(52, 'clients', 'Team Lead', 6, 0, '2026-08-31 01:59:41', '2026-08-31 01:59:41'),
(53, 'employees', 'Team Lead', 4, 0, '2026-08-31 01:59:50', '2026-08-31 01:59:50');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2026_08_27_103950_add_department_id_to_menu_permissions_table', 1),
(3, '2026_08_27_104559_update_menu_permissions_unique_constraint', 2),
(4, '2026_08_27_120722_add_deleted_by_to_work_log_clients_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 2),
(1, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 6),
(4, 'App\\Models\\User', 7),
(4, 'App\\Models\\User', 8),
(4, 'App\\Models\\User', 9),
(4, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 11),
(4, 'App\\Models\\User', 12),
(4, 'App\\Models\\User', 13),
(4, 'App\\Models\\User', 14);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0384a900-be93-4266-9dad-0cdbb2d8dc37', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 7, '{\"type\":\"task_assigned\",\"task_id\":76,\"task_title\":\"diamond rose reel\",\"message\":\"Anjali assigned you to \\\"diamond rose reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/76\"}', NULL, '2026-08-23 23:33:36', '2026-08-23 23:33:36'),
('0645123e-403c-426e-ae19-366c57d887e5', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":140,\"task_title\":\"jnmashtmi \",\"message\":\"Anjali assigned you to \\\"jnmashtmi \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/140\"}', '2026-09-03 02:22:49', '2026-09-03 00:47:37', '2026-09-03 02:22:49'),
('068aa1f5-0f98-4f12-b799-6a2fbeaa3b10', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":94,\"task_title\":\"sports day \",\"message\":\"Anjali assigned you to \\\"sports day \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/94\"}', NULL, '2026-08-25 00:11:06', '2026-08-25 00:11:06'),
('0734c8e4-4054-4159-8e3a-66f9e874b861', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":127,\"task_title\":\"creative\",\"message\":\"Anjali assigned you to \\\"creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/127\"}', '2026-09-01 01:50:23', '2026-09-01 01:24:03', '2026-09-01 01:50:23'),
('0791f455-ed63-443b-96d0-fcd3d3ffd037', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":36,\"task_title\":\"pending AI reel \",\"message\":\"Anjali assigned you to \\\"pending AI reel \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/36\"}', '2026-08-19 23:43:42', '2026-08-18 23:35:56', '2026-08-19 23:43:42'),
('080602d9-0202-4257-849d-979dcf94a044', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":109,\"task_title\":\"Flex\",\"message\":\"Anjali assigned you to \\\"Flex\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/109\"}', '2026-08-27 01:59:14', '2026-08-25 07:57:00', '2026-08-27 01:59:14'),
('0b8aebcb-98cd-408a-897c-75545f58c06b', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":54,\"task_title\":\"Creative \",\"message\":\"Anjali assigned you to \\\"Creative \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/54\"}', '2026-08-20 05:59:29', '2026-08-20 02:02:37', '2026-08-20 05:59:29'),
('0dd97856-9f29-4e63-a80d-db4f72562512', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":85,\"task_title\":\"reel\",\"message\":\"Anjali assigned you to \\\"reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/85\"}', NULL, '2026-08-24 00:08:38', '2026-08-24 00:08:38'),
('0ef37152-0c85-4937-b4e5-27def8811ccc', 'App\\Notifications\\TaskSentBackNotification', 'App\\Models\\User', 8, '{\"type\":\"task_sent_back\",\"task_id\":32,\"task_title\":\"try\",\"message\":\"Anjali sent \\\"try\\\" back for changes: Incorrect Content: Check wp\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\"}', '2026-08-18 01:48:33', '2026-08-18 01:48:23', '2026-08-18 01:48:33'),
('153d8bc9-2585-491a-976b-17d1efbf81e8', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":77,\"task_title\":\"women\'s equality day\",\"message\":\"Anjali assigned you to \\\"women\'s equality day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/77\"}', NULL, '2026-08-23 23:40:24', '2026-08-23 23:40:24'),
('16a7e466-7eb4-401a-960a-ff5b18c8f218', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":147,\"task_title\":\"Exibition Reel\",\"message\":\"Anjali assigned you to \\\"Exibition Reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/147\"}', NULL, '2026-09-03 06:08:46', '2026-09-03 06:08:46'),
('16cc77ee-ce2c-4a17-86f3-fc8acf1f661c', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":160,\"task_title\":\"Teachers Day\",\"message\":\"Anjali assigned you to \\\"Teachers Day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/160\"}', NULL, '2026-09-04 05:36:50', '2026-09-04 05:36:50'),
('177c891f-3b1c-4e9a-87a5-4fe187c3e89a', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":66,\"task_title\":\"creative\",\"message\":\"Anjali assigned you to \\\"creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/66\"}', NULL, '2026-08-21 04:10:40', '2026-08-21 04:10:40'),
('17ce479d-63e3-4661-a041-a38fdc96a8d3', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":101,\"task_title\":\"Raksha bandhan\",\"message\":\"Anjali assigned you to \\\"Raksha bandhan\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/101\"}', '2026-08-25 07:35:02', '2026-08-25 01:28:04', '2026-08-25 07:35:02'),
('17d30962-c8da-42a2-a6a1-8b9aac89f4c1', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":159,\"task_title\":\"Teachers Day\",\"message\":\"Anjali assigned you to \\\"Teachers Day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/159\"}', NULL, '2026-09-04 05:35:23', '2026-09-04 05:35:23'),
('1df4101d-1dfd-4a17-9701-091f8cd00ec6', 'App\\Notifications\\TaskSentBackNotification', 'App\\Models\\User', 8, '{\"type\":\"task_sent_back\",\"task_id\":32,\"task_title\":\"try\",\"message\":\"Anjali sent \\\"try\\\" back for changes: Changes from client\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\"}', '2026-08-18 01:42:58', '2026-08-18 01:42:43', '2026-08-18 01:42:58'),
('1e14ffdc-2dd4-4eb4-9d9c-a845d0c184f3', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":20,\"task_title\":\"sadsad\",\"message\":\"Midbrains Superadmin assigned you to \\\"sadsad\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/20\"}', '2026-08-07 06:50:36', '2026-08-05 02:04:55', '2026-08-07 06:50:36'),
('1eb6daaa-8c73-4720-ac77-d92bb2a06e6a', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":115,\"task_title\":\"Creative\",\"message\":\"Anjali assigned you to \\\"Creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/115\"}', NULL, '2026-08-26 07:51:09', '2026-08-26 07:51:09'),
('1f4fc468-a08a-494f-9aff-736d1473ee74', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":141,\"task_title\":\"Teachers day\",\"message\":\"Anjali assigned you to \\\"Teachers day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/141\"}', '2026-09-03 02:22:49', '2026-09-03 00:48:13', '2026-09-03 02:22:49'),
('20df24de-0a7a-4525-a5f3-bd10a57b626b', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":142,\"task_title\":\"Teachers Day \",\"message\":\"Anjali assigned you to \\\"Teachers Day \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/142\"}', '2026-09-03 02:22:49', '2026-09-03 00:50:41', '2026-09-03 02:22:49'),
('22d1a497-ea68-41f2-9bcd-85dd62488aaf', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 7, '{\"type\":\"task_assigned\",\"task_id\":17,\"task_title\":\"15th Aug Reel\",\"message\":\"Sakshi assigned you to \\\"15th Aug Reel\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/17\"}', NULL, '2026-08-05 01:30:39', '2026-08-05 01:30:39'),
('2319fb42-87ea-4280-bdd2-bbfb6f977124', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":125,\"task_title\":\"creative\",\"message\":\"Anjali assigned you to \\\"creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/125\"}', '2026-09-01 01:50:23', '2026-09-01 01:14:57', '2026-09-01 01:50:23'),
('23d115bd-dd55-4793-8f7c-936b418988e8', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 7, '{\"type\":\"task_assigned\",\"task_id\":16,\"task_title\":\"Teacher\'s Day Post\",\"message\":\"Midbrains Superadmin assigned you to \\\"Teacher\'s Day Post\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/16\"}', NULL, '2026-08-03 02:50:35', '2026-08-03 02:50:35'),
('284ca9ef-07bb-4c7a-824e-4919cfd0171d', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":88,\"task_title\":\"Best ou of waste reel\",\"message\":\"Anjali assigned you to \\\"Best ou of waste reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/88\"}', NULL, '2026-08-24 04:49:16', '2026-08-24 04:49:16'),
('2927fe8f-93b2-4da8-a1bb-1822ce544efc', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":99,\"task_title\":\"Raksha bandhan\",\"message\":\"Anjali assigned you to \\\"Raksha bandhan\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/99\"}', '2026-08-25 07:35:02', '2026-08-25 01:26:00', '2026-08-25 07:35:02'),
('29415c0d-baac-4645-9dba-2c846d229243', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":48,\"task_title\":\"informative creative\",\"message\":\"Anjali assigned you to \\\"informative creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/48\"}', '2026-08-19 00:04:06', '2026-08-18 23:56:37', '2026-08-19 00:04:06'),
('2b8ff914-767e-4c25-ad68-2c849f59a3cb', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":56,\"task_title\":\"Botox Treatment \",\"message\":\"Anjali assigned you to \\\"Botox Treatment \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/56\"}', '2026-08-20 07:01:19', '2026-08-20 05:34:03', '2026-08-20 07:01:19'),
('2bc650ae-09ae-4490-941b-41fa08a1e068', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":39,\"task_title\":\"You tube cover image -Tamara Spa\",\"message\":\"Anjali assigned you to \\\"You tube cover image -Tamara Spa\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/39\"}', NULL, '2026-08-18 23:43:20', '2026-08-18 23:43:20'),
('2c8aa63c-ad7f-4690-9a08-44d6b8d2c3fd', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":165,\"task_title\":\"Reel Thumbnail-podcaste TREX\",\"message\":\"Anjali assigned you to \\\"Reel Thumbnail-podcaste TREX\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/165\"}', NULL, '2026-09-04 07:16:07', '2026-09-04 07:16:07'),
('2ec9ef87-56c4-4091-b4ad-b8e28dab65fd', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":46,\"task_title\":\"Dr.Digish -creative \",\"message\":\"Anjali assigned you to \\\"Dr.Digish -creative \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/46\"}', '2026-08-19 00:04:06', '2026-08-18 23:53:17', '2026-08-19 00:04:06'),
('30f74b77-c1d0-4b33-b68b-ad155e687a13', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":33,\"task_title\":\"Dr.Digish \",\"message\":\"Anjali assigned you to \\\"Dr.Digish \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/33\"}', '2026-08-19 23:43:42', '2026-08-18 23:31:49', '2026-08-19 23:43:42'),
('31f3104b-bd67-4e44-b3d8-63a1385cfdcd', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":103,\"task_title\":\"Video Edit\",\"message\":\"Anjali assigned you to \\\"Video Edit\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/103\"}', NULL, '2026-08-25 04:51:56', '2026-08-25 04:51:56'),
('32ac73c3-a21f-4783-b8ca-0697641c79e7', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":60,\"task_title\":\"check instagram account\",\"message\":\"Anjali assigned you to \\\"check instagram account\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/60\"}', '2026-08-21 01:58:46', '2026-08-20 06:06:00', '2026-08-21 01:58:46'),
('3606dfd5-57f3-4bd8-8dc6-ca20b502b05f', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":71,\"task_title\":\"next reel\",\"message\":\"Anjali assigned you to \\\"next reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/71\"}', NULL, '2026-08-22 01:18:11', '2026-08-22 01:18:11'),
('37fa4a50-9d64-4b1d-a398-0139db698e3b', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":155,\"task_title\":\"Teachers day\",\"message\":\"Anjali assigned you to \\\"Teachers day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/155\"}', NULL, '2026-09-04 05:26:22', '2026-09-04 05:26:22'),
('38a9c5bc-e9be-4023-8e56-93f8438c5a21', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":132,\"task_title\":\"Candid \",\"message\":\"Sakshi assigned you to \\\"Candid \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/132\"}', NULL, '2026-09-02 05:56:37', '2026-09-02 05:56:37'),
('38c27944-195b-4d44-8cf7-05ee954e7ae4', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":67,\"task_title\":\"Gold rate increase AI video\",\"message\":\"Anjali assigned you to \\\"Gold rate increase AI video\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/67\"}', NULL, '2026-08-21 05:08:28', '2026-08-21 05:08:28'),
('38d0a83c-34f9-4d1e-8e86-d3540397ff52', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":44,\"task_title\":\"ganpati festival standee\",\"message\":\"Anjali assigned you to \\\"ganpati festival standee\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/44\"}', '2026-08-19 00:04:06', '2026-08-18 23:51:13', '2026-08-19 00:04:06'),
('39378279-8d47-4090-bbda-925f3a92b720', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":131,\"task_title\":\"Enhance Dental \",\"message\":\"Sakshi assigned you to \\\"Enhance Dental \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/131\"}', NULL, '2026-09-02 05:33:19', '2026-09-02 05:33:19'),
('3ddf9518-d762-4ef0-9f7e-a14204b08775', 'App\\Notifications\\TaskSentBackNotification', 'App\\Models\\User', 8, '{\"type\":\"task_sent_back\",\"task_id\":113,\"task_title\":\"Offer Creative -1\",\"message\":\"Anjali sent \\\"Offer Creative -1\\\" back for changes: Needs Changes\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/113\"}', NULL, '2026-09-04 05:17:25', '2026-09-04 05:17:25'),
('3f789f61-b88d-4b6e-aba9-b71af8c15185', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":37,\"task_title\":\"casa deco reel\",\"message\":\"Anjali assigned you to \\\"casa deco reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/37\"}', '2026-08-19 23:43:42', '2026-08-18 23:38:08', '2026-08-19 23:43:42'),
('3ff16b7a-730f-44bd-a6f9-3efc2902c920', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":124,\"task_title\":\"creative\",\"message\":\"Anjali assigned you to \\\"creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/124\"}', '2026-09-01 01:50:23', '2026-09-01 01:09:42', '2026-09-01 01:50:23'),
('40196430-a113-4ffa-b801-62827e1c0aa2', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":55,\"task_title\":\"Creative \",\"message\":\"Anjali assigned you to \\\"Creative \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/55\"}', '2026-08-20 05:59:27', '2026-08-20 04:53:44', '2026-08-20 05:59:27'),
('40c02d5e-6386-40d9-991a-49c5b1ecc4e0', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":143,\"task_title\":\"Teachers day\",\"message\":\"Anjali assigned you to \\\"Teachers day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/143\"}', NULL, '2026-09-03 00:51:35', '2026-09-03 00:51:35'),
('41bc803e-36b5-4add-a0b8-ddc642277f04', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":83,\"task_title\":\"reel\",\"message\":\"Anjali assigned you to \\\"reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/83\"}', NULL, '2026-08-24 00:05:32', '2026-08-24 00:05:32'),
('424c9722-668e-41db-bc40-cbbee9a2c0ac', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":40,\"task_title\":\"Creative for Trupti Ayurveda\",\"message\":\"Anjali assigned you to \\\"Creative for Trupti Ayurveda\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/40\"}', NULL, '2026-08-18 23:46:08', '2026-08-18 23:46:08'),
('4918e388-4648-4308-a306-368e10f4e53e', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":68,\"task_title\":\"video nanoplasticity\",\"message\":\"Anjali assigned you to \\\"video nanoplasticity\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/68\"}', NULL, '2026-08-21 07:38:52', '2026-08-21 07:38:52'),
('4a144b3b-80b4-4b7e-95f1-53d7c28ed957', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":112,\"task_title\":\"Creative \",\"message\":\"Anjali assigned you to \\\"Creative \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/112\"}', '2026-08-27 01:59:14', '2026-08-26 06:04:31', '2026-08-27 01:59:14'),
('5095f573-46cf-4314-afc5-13123acacc22', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":122,\"task_title\":\"creative\",\"message\":\"Anjali assigned you to \\\"creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/122\"}', '2026-09-01 01:50:23', '2026-09-01 01:02:17', '2026-09-01 01:50:23'),
('51d51b75-dd69-4ae9-a080-c8e0e8607611', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":97,\"task_title\":\"Raksha bandhan\",\"message\":\"Anjali assigned you to \\\"Raksha bandhan\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/97\"}', '2026-08-25 07:35:02', '2026-08-25 01:25:13', '2026-08-25 07:35:02'),
('5299bcff-d8ad-4f88-ba5b-11ae61ccca93', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":120,\"task_title\":\"BUE Youtube Video \",\"message\":\"Sakshi assigned you to \\\"BUE Youtube Video \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/120\"}', NULL, '2026-09-01 00:06:20', '2026-09-01 00:06:20'),
('56251601-7472-459a-bc5a-aa9c32233819', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":92,\"task_title\":\"sports day \",\"message\":\"Anjali assigned you to \\\"sports day \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/92\"}', NULL, '2026-08-25 00:07:17', '2026-08-25 00:07:17'),
('57e242ad-b49d-46a3-920d-d88e060fa893', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":90,\"task_title\":\"Thumnail\",\"message\":\"Anjali assigned you to \\\"Thumnail\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/90\"}', NULL, '2026-08-24 23:50:51', '2026-08-24 23:50:51'),
('580481fd-7ae7-4691-9635-48f42c96de63', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":130,\"task_title\":\"Highlights Video\",\"message\":\"Anjali assigned you to \\\"Highlights Video\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/130\"}', NULL, '2026-09-01 07:51:21', '2026-09-01 07:51:21'),
('580a5c1a-683d-4efd-95c8-5f7c886f0038', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":59,\"task_title\":\"saturday \",\"message\":\"Anjali assigned you to \\\"saturday \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/59\"}', '2026-08-21 01:58:46', '2026-08-20 06:04:57', '2026-08-21 01:58:46'),
('5de5d29a-182f-4e72-a495-ee8ec4d35f0c', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":35,\"task_title\":\"AI reel - Moshi Hospital\",\"message\":\"Anjali assigned you to \\\"AI reel - Moshi Hospital\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/35\"}', '2026-08-19 23:43:42', '2026-08-18 23:34:52', '2026-08-19 23:43:42'),
('5f289178-86aa-47b5-8d5d-da8287da1250', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":135,\"task_title\":\"Same Offer Creatives\",\"message\":\"Anjali assigned you to \\\"Same Offer Creatives\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/135\"}', '2026-09-03 02:22:49', '2026-09-02 23:56:36', '2026-09-03 02:22:49'),
('5f54a921-43a4-4186-8315-f50824946149', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":58,\"task_title\":\"BTS \",\"message\":\"Anjali assigned you to \\\"BTS \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/58\"}', '2026-08-20 07:01:19', '2026-08-20 05:35:27', '2026-08-20 07:01:19'),
('5f56d48b-8225-4c5b-a033-36daea24f11c', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":113,\"task_title\":\"Offer Creative -1\",\"message\":\"Anjali assigned you to \\\"Offer Creative -1\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/113\"}', '2026-08-27 01:59:14', '2026-08-26 06:05:24', '2026-08-27 01:59:14'),
('5fffb98b-72e2-4baf-8f90-9cb95ec1954b', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":144,\"task_title\":\"teachers day \",\"message\":\"Anjali assigned you to \\\"teachers day \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/144\"}', NULL, '2026-09-03 00:53:13', '2026-09-03 00:53:13'),
('610c1810-616f-4648-8fcb-54e47d7851a2', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":53,\"task_title\":\"Thumbnail\",\"message\":\"Anjali assigned you to \\\"Thumbnail\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/53\"}', NULL, '2026-08-19 23:05:23', '2026-08-19 23:05:23'),
('6255a7b3-fdd0-4600-ad76-80e76ae6129e', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":98,\"task_title\":\"Raksha bandhan\",\"message\":\"Anjali assigned you to \\\"Raksha bandhan\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/98\"}', '2026-08-25 07:35:02', '2026-08-25 01:25:37', '2026-08-25 07:35:02'),
('63e7a25a-67ba-434a-9a4a-1582f07cd7f2', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":157,\"task_title\":\"Perl Resort-creative\",\"message\":\"Anjali assigned you to \\\"Perl Resort-creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/157\"}', NULL, '2026-09-04 05:30:17', '2026-09-04 05:30:17'),
('65df5b55-22fc-47ba-a894-8850a1f73166', 'App\\Notifications\\TaskSentBackNotification', 'App\\Models\\User', 8, '{\"type\":\"task_sent_back\",\"task_id\":44,\"task_title\":\"ganpati festival standee\",\"message\":\"Anjali sent \\\"ganpati festival standee\\\" back for changes: Needs Changes\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/44\"}', '2026-08-25 07:35:02', '2026-08-24 01:21:57', '2026-08-25 07:35:02'),
('6beb937c-aba8-40b2-bc40-56bb63e456bb', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":89,\"task_title\":\"client treatment and review\",\"message\":\"Anjali assigned you to \\\"client treatment and review\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/89\"}', NULL, '2026-08-24 23:44:27', '2026-08-24 23:44:27'),
('76cb26db-1a16-4232-8f5e-ae57585cc7ad', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":72,\"task_title\":\"Video\",\"message\":\"Anjali assigned you to \\\"Video\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/72\"}', NULL, '2026-08-22 01:59:55', '2026-08-22 01:59:55'),
('775321e4-febe-4229-b3bd-95772c6c547e', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":114,\"task_title\":\"offer reel\",\"message\":\"Anjali assigned you to \\\"offer reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/114\"}', NULL, '2026-08-26 07:00:02', '2026-08-26 07:00:02'),
('7b4a3110-ef95-4196-80da-902ad93d97ab', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":87,\"task_title\":\"Video\",\"message\":\"Anjali assigned you to \\\"Video\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/87\"}', NULL, '2026-08-24 02:22:38', '2026-08-24 02:22:38'),
('7e99fdce-ceb6-46e7-b8ad-05073b28d0b0', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":108,\"task_title\":\"raksha bandhan \",\"message\":\"Anjali assigned you to \\\"raksha bandhan \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/108\"}', NULL, '2026-08-25 07:50:23', '2026-08-25 07:50:23'),
('7f196528-a52b-4d9a-aaa6-c57ba705ee5b', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":105,\"task_title\":\"sports day\",\"message\":\"Anjali assigned you to \\\"sports day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/105\"}', '2026-08-27 01:59:14', '2026-08-25 07:47:44', '2026-08-27 01:59:14'),
('7f9cc314-df8a-425c-bb06-cf637deb9b4c', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":119,\"task_title\":\"H&M Reel \",\"message\":\"Sakshi assigned you to \\\"H&M Reel \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/119\"}', NULL, '2026-09-01 00:03:25', '2026-09-01 00:03:25'),
('83298bdd-8714-4f07-8a48-6d46079531c2', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":146,\"task_title\":\"Jnmashtmi\",\"message\":\"Anjali assigned you to \\\"Jnmashtmi\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/146\"}', '2026-09-03 02:22:49', '2026-09-03 01:10:28', '2026-09-03 02:22:49'),
('836a60fa-b8c2-476c-a6db-7c67de6456ef', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":133,\"task_title\":\"testimonial \",\"message\":\"Anjali assigned you to \\\"testimonial \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/133\"}', NULL, '2026-09-02 23:50:43', '2026-09-02 23:50:43'),
('851060f1-0277-4239-bd30-929f07834a80', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":86,\"task_title\":\"creative\",\"message\":\"Anjali assigned you to \\\"creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/86\"}', '2026-08-25 07:35:02', '2026-08-24 01:02:32', '2026-08-25 07:35:02'),
('852dfb5d-6ad8-4ac0-b222-df51c81f02ad', 'App\\Notifications\\TaskSentBackNotification', 'App\\Models\\User', 9, '{\"type\":\"task_sent_back\",\"task_id\":37,\"task_title\":\"casa deco reel\",\"message\":\"Anjali sent \\\"casa deco reel\\\" back for changes: Needs Changes\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/37\"}', '2026-08-19 23:43:42', '2026-08-19 06:52:11', '2026-08-19 23:43:42'),
('86d7695a-a8c7-4f35-85fa-3d60c202d0df', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":149,\"task_title\":\"Jnmashtmi\",\"message\":\"Anjali assigned you to \\\"Jnmashtmi\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/149\"}', NULL, '2026-09-03 13:07:18', '2026-09-03 13:07:18'),
('88c9d69b-2775-4ddf-bb07-10af8b4f0542', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 7, '{\"type\":\"task_assigned\",\"task_id\":21,\"task_title\":\"try\",\"message\":\"Midbrains Superadmin assigned you to \\\"try\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/21\"}', NULL, '2026-08-07 01:22:11', '2026-08-07 01:22:11'),
('89f174b3-5dd3-470f-8a4a-69940fc69e0b', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":129,\"task_title\":\"Exibition Creative -18,19,20\",\"message\":\"Anjali assigned you to \\\"Exibition Creative -18,19,20\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/129\"}', '2026-09-02 02:10:17', '2026-09-01 05:28:44', '2026-09-02 02:10:17'),
('8cb77613-ef28-4cb3-90a5-d297b4700361', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":79,\"task_title\":\"festive- Eid\",\"message\":\"Anjali assigned you to \\\"festive- Eid\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/79\"}', '2026-08-25 07:35:02', '2026-08-23 23:42:38', '2026-08-25 07:35:02'),
('8d859c95-fbcc-4baa-9781-ba7e10ebc76e', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 7, '{\"type\":\"task_assigned\",\"task_id\":16,\"task_title\":\"Teacher\'s Day Post\",\"message\":\"Midbrains Superadmin assigned you to \\\"Teacher\'s Day Post\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/16\"}', NULL, '2026-08-03 02:41:01', '2026-08-03 02:41:01'),
('8ed42c85-656a-4196-b006-a77ebda6b0e3', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":16,\"task_title\":\"Teacher\'s Day Post\",\"message\":\"Midbrains Superadmin assigned you to \\\"Teacher\'s Day Post\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/16\"}', '2026-08-07 06:50:36', '2026-08-03 02:52:06', '2026-08-07 06:50:36'),
('8fd3f660-a075-44cd-a5d7-02e686af9b93', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":162,\"task_title\":\"GIF-jnmashtmi\",\"message\":\"Anjali assigned you to \\\"GIF-jnmashtmi\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/162\"}', NULL, '2026-09-04 07:14:15', '2026-09-04 07:14:15'),
('8feced5e-3232-47ff-b2a1-d309fe3cdf85', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":63,\"task_title\":\"any jewelly reel\",\"message\":\"Anjali assigned you to \\\"any jewelly reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/63\"}', NULL, '2026-08-20 23:09:30', '2026-08-20 23:09:30'),
('90becd13-0af4-40ef-863f-05f9b4d7a154', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":154,\"task_title\":\"Teachers day\",\"message\":\"Anjali assigned you to \\\"Teachers day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/154\"}', NULL, '2026-09-04 05:25:45', '2026-09-04 05:25:45'),
('95f46266-a06f-4822-815c-226df4cad217', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":91,\"task_title\":\"informative creative\",\"message\":\"Anjali assigned you to \\\"informative creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/91\"}', NULL, '2026-08-24 23:56:31', '2026-08-24 23:56:31'),
('97b58d49-1c66-4ced-b426-327adfd076d6', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":61,\"task_title\":\"Raw data given \",\"message\":\"Anjali assigned you to \\\"Raw data given \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/61\"}', '2026-08-20 07:01:19', '2026-08-20 06:09:49', '2026-08-20 07:01:19'),
('9baf3f05-3125-49e7-a3eb-ce8c8ad5b653', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":43,\"task_title\":\"Creative for Tamara Spa \",\"message\":\"Anjali assigned you to \\\"Creative for Tamara Spa \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/43\"}', '2026-08-19 00:04:06', '2026-08-18 23:49:38', '2026-08-19 00:04:06'),
('9d660508-6094-4958-a16f-447a42b58137', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 7, '{\"type\":\"task_assigned\",\"task_id\":69,\"task_title\":\"Video Changes -Swift car\",\"message\":\"Anjali assigned you to \\\"Video Changes -Swift car\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/69\"}', NULL, '2026-08-21 23:59:20', '2026-08-21 23:59:20'),
('9dc45f96-931a-4dbb-a19d-ed4d1543a749', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":75,\"task_title\":\"GIF-festive -Eid-E-milad\",\"message\":\"Anjali assigned you to \\\"GIF-festive -Eid-E-milad\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/75\"}', '2026-08-22 07:04:07', '2026-08-22 02:22:13', '2026-08-22 07:04:07'),
('9e514a70-893e-497a-b953-caf9d449f221', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":95,\"task_title\":\"Nail art\",\"message\":\"Anjali assigned you to \\\"Nail art\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/95\"}', NULL, '2026-08-25 00:29:21', '2026-08-25 00:29:21'),
('a239deae-a12a-45c5-9ec9-83e982263ca3', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":52,\"task_title\":\"Reel changes \",\"message\":\"Anjali assigned you to \\\"Reel changes \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/52\"}', '2026-08-19 23:43:42', '2026-08-19 23:03:39', '2026-08-19 23:43:42'),
('a2b3680e-e25a-44f9-8950-1bbb3000c76f', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":152,\"task_title\":\"reel\",\"message\":\"Anjali assigned you to \\\"reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/152\"}', NULL, '2026-09-04 05:21:05', '2026-09-04 05:21:05'),
('a330de83-b9df-4b06-b179-860e4436fbe5', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":110,\"task_title\":\"AI video \",\"message\":\"Anjali assigned you to \\\"AI video \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/110\"}', '2026-08-27 01:59:14', '2026-08-25 07:57:26', '2026-08-27 01:59:14'),
('a4ff6768-f1d1-46c8-8860-e6e42c72b382', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":47,\"task_title\":\"Creative -parekh jewellers\",\"message\":\"Anjali assigned you to \\\"Creative -parekh jewellers\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/47\"}', '2026-08-19 00:04:06', '2026-08-18 23:54:49', '2026-08-19 00:04:06'),
('a54fd14f-50be-4cb8-9ff3-30e5f0fd5c97', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":164,\"task_title\":\"Glory-teachers day\",\"message\":\"Anjali assigned you to \\\"Glory-teachers day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/164\"}', NULL, '2026-09-04 07:14:53', '2026-09-04 07:14:53'),
('a7b01cfb-f644-480e-a322-f2ecb01a9fe2', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":96,\"task_title\":\"rakshabandhan \",\"message\":\"Anjali assigned you to \\\"rakshabandhan \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/96\"}', NULL, '2026-08-25 00:30:48', '2026-08-25 00:30:48'),
('a824e714-0d8e-4c1c-8a95-934c1bc38bbb', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":136,\"task_title\":\"Jnmashtmi\",\"message\":\"Anjali assigned you to \\\"Jnmashtmi\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/136\"}', NULL, '2026-09-02 23:57:13', '2026-09-02 23:57:13'),
('a8ade7b4-48b1-401d-bbea-378655382642', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":145,\"task_title\":\"Jnmashtmi\",\"message\":\"Anjali assigned you to \\\"Jnmashtmi\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/145\"}', '2026-09-03 02:22:49', '2026-09-03 01:08:55', '2026-09-03 02:22:49'),
('a8dad8ab-36c0-4853-9965-f6e810f1fd02', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":104,\"task_title\":\"Rakshabandan \",\"message\":\"Anjali assigned you to \\\"Rakshabandan \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/104\"}', NULL, '2026-08-25 07:45:43', '2026-08-25 07:45:43'),
('a905bb03-3ddd-4593-aee3-50ce02c8b577', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":138,\"task_title\":\"Janmashtmi post\",\"message\":\"Anjali assigned you to \\\"Janmashtmi post\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/138\"}', NULL, '2026-09-03 00:26:58', '2026-09-03 00:26:58'),
('aa85b83a-1b3e-4285-8dd1-59187a935efe', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":74,\"task_title\":\"women\'s equality day\",\"message\":\"Anjali assigned you to \\\"women\'s equality day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/74\"}', NULL, '2026-08-22 02:15:38', '2026-08-22 02:15:38'),
('ab003d86-947d-4391-9102-ed551d1f7b5d', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":156,\"task_title\":\"Teachers day\",\"message\":\"Anjali assigned you to \\\"Teachers day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/156\"}', NULL, '2026-09-04 05:27:03', '2026-09-04 05:27:03'),
('aba8b0d6-6828-450d-9d0b-9c77df7ae843', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":62,\"task_title\":\"Thumbnail\",\"message\":\"Anjali assigned you to \\\"Thumbnail\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/62\"}', NULL, '2026-08-20 07:26:07', '2026-08-20 07:26:07'),
('ad9b84be-4cc4-4351-adce-a9dec3ee8c9d', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 7, '{\"type\":\"task_assigned\",\"task_id\":18,\"task_title\":\"LC\",\"message\":\"Midbrains Superadmin assigned you to \\\"LC\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/18\"}', NULL, '2026-08-05 01:44:17', '2026-08-05 01:44:17'),
('aeab2888-9085-46df-9437-634a1674ccfd', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":57,\"task_title\":\"GIF Of Glory School\",\"message\":\"Anjali assigned you to \\\"GIF Of Glory School\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/57\"}', '2026-08-20 07:01:19', '2026-08-20 05:34:53', '2026-08-20 07:01:19'),
('b28bd006-11b8-4dd1-9f21-f8843aaf86d2', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":45,\"task_title\":\"Carousel post\",\"message\":\"Anjali assigned you to \\\"Carousel post\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/45\"}', '2026-08-19 00:04:06', '2026-08-18 23:52:04', '2026-08-19 00:04:06'),
('b2ed3cc0-7126-4090-9516-fe8811a28302', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":126,\"task_title\":\"creative\",\"message\":\"Anjali assigned you to \\\"creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/126\"}', '2026-09-01 01:50:23', '2026-09-01 01:22:35', '2026-09-01 01:50:23'),
('b2f33fb0-a3de-4571-ac1e-28efc0a15c79', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":134,\"task_title\":\"Reel\",\"message\":\"Anjali assigned you to \\\"Reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/134\"}', NULL, '2026-09-02 23:51:51', '2026-09-02 23:51:51'),
('b3b778d0-2d5c-4481-a974-a29d855251b6', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":19,\"task_title\":\"sad\",\"message\":\"Midbrains Superadmin assigned you to \\\"sad\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/19\"}', '2026-08-07 06:50:36', '2026-08-05 01:45:08', '2026-08-07 06:50:36'),
('b683f8c6-f309-426f-8ddf-6edef4c5160d', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":137,\"task_title\":\"Creative -pearl resort(check group)\",\"message\":\"Anjali assigned you to \\\"Creative -pearl resort(check group)\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/137\"}', '2026-09-03 02:22:49', '2026-09-03 00:00:14', '2026-09-03 02:22:49'),
('b79567b0-19e0-4c79-a285-3ea06b884037', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":106,\"task_title\":\"sports day\",\"message\":\"Anjali assigned you to \\\"sports day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/106\"}', NULL, '2026-08-25 07:49:20', '2026-08-25 07:49:20'),
('bf612f75-8919-4102-b126-7ba67fc793a9', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":102,\"task_title\":\"Raksha bandhan\",\"message\":\"Anjali assigned you to \\\"Raksha bandhan\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/102\"}', '2026-08-25 07:35:01', '2026-08-25 01:29:09', '2026-08-25 07:35:01'),
('c025bc05-b099-480e-acb2-49fe8898478c', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":41,\"task_title\":\"Creative For Om dental\",\"message\":\"Anjali assigned you to \\\"Creative For Om dental\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/41\"}', NULL, '2026-08-18 23:48:13', '2026-08-18 23:48:13'),
('c0c19e9f-ecd3-4fda-aa15-45df1deed315', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":65,\"task_title\":\"festive post-eid e milad\",\"message\":\"Anjali assigned you to \\\"festive post-eid e milad\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/65\"}', NULL, '2026-08-21 03:26:02', '2026-08-21 03:26:02'),
('cbd63872-2f22-41e5-9673-e0b02fbd4697', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":82,\"task_title\":\"Rakshbandhan  post \",\"message\":\"Anjali assigned you to \\\"Rakshbandhan  post \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/82\"}', NULL, '2026-08-24 00:02:27', '2026-08-24 00:02:27'),
('cd1cfc6d-7bc8-4ae4-97c4-c8a91a646651', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":78,\"task_title\":\"women\'s equality day\",\"message\":\"Anjali assigned you to \\\"women\'s equality day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/78\"}', NULL, '2026-08-23 23:41:44', '2026-08-23 23:41:44'),
('cd2884cc-df17-43b2-adb6-9a1f5d1cf4cf', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":51,\"task_title\":\"Testimonial \",\"message\":\"Anjali assigned you to \\\"Testimonial \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/51\"}', '2026-08-19 23:43:42', '2026-08-19 23:03:02', '2026-08-19 23:43:42'),
('cdb0d2cc-fe9c-4c7f-8f1c-72ff64497240', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":38,\"task_title\":\"Creative For Celebrino Villa \",\"message\":\"Anjali assigned you to \\\"Creative For Celebrino Villa \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/38\"}', NULL, '2026-08-18 23:40:52', '2026-08-18 23:40:52'),
('d0ae3e6c-1e8d-47d0-a4a6-967980bf377a', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":93,\"task_title\":\"Sports Day\",\"message\":\"Anjali assigned you to \\\"Sports Day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/93\"}', NULL, '2026-08-25 00:10:41', '2026-08-25 00:10:41'),
('d0c8567e-617a-4098-9594-bee0c17971d8', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":81,\"task_title\":\"Rakshbandhan  post \",\"message\":\"Anjali assigned you to \\\"Rakshbandhan  post \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/81\"}', NULL, '2026-08-24 00:02:09', '2026-08-24 00:02:09'),
('d185e8ef-1740-4ccb-be72-2970cd4519cf', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":150,\"task_title\":\"Jnmashtmi\",\"message\":\"Anjali assigned you to \\\"Jnmashtmi\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/150\"}', NULL, '2026-09-04 05:19:15', '2026-09-04 05:19:15'),
('d34450bf-7fb3-4259-bb44-9a852c8fb431', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":16,\"task_title\":\"Teacher\'s Day Post\",\"message\":\"Midbrains Superadmin assigned you to \\\"Teacher\'s Day Post\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/16\"}', '2026-08-07 06:50:36', '2026-08-03 02:47:10', '2026-08-07 06:50:36'),
('d3e4c097-76e1-441d-8469-ab3eb9b4ef2c', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":163,\"task_title\":\"Teachers Day\",\"message\":\"Anjali assigned you to \\\"Teachers Day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/163\"}', NULL, '2026-09-04 07:14:35', '2026-09-04 07:14:35'),
('d62c6fc1-6eed-4b28-a99c-99de5a1995e5', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":111,\"task_title\":\"Testimonial\",\"message\":\"Anjali assigned you to \\\"Testimonial\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/111\"}', NULL, '2026-08-26 01:15:37', '2026-08-26 01:15:37'),
('d6337c93-f422-43c1-887e-6e9998e9cb64', 'App\\Notifications\\TaskSentBackNotification', 'App\\Models\\User', 9, '{\"type\":\"task_sent_back\",\"task_id\":103,\"task_title\":\"Video Edit\",\"message\":\"Anjali sent \\\"Video Edit\\\" back for changes: Needs Changes\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/103\"}', NULL, '2026-08-25 07:59:46', '2026-08-25 07:59:46'),
('d6e12454-d8ce-4ce2-8f0c-0ba9ba6e0ef1', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":148,\"task_title\":\"Jnmashtmi\",\"message\":\"Anjali assigned you to \\\"Jnmashtmi\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/148\"}', NULL, '2026-09-03 12:24:19', '2026-09-03 12:24:19'),
('d7e4cfb6-904a-44e2-860c-d582469cc2e0', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":151,\"task_title\":\"Story Video \",\"message\":\"Anjali assigned you to \\\"Story Video \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/151\"}', NULL, '2026-09-04 05:20:29', '2026-09-04 05:20:29'),
('d8b3a49f-78ea-4d59-a406-344bc26937c8', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":64,\"task_title\":\"offer creative\",\"message\":\"Anjali assigned you to \\\"offer creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/64\"}', '2026-08-21 01:58:46', '2026-08-20 23:11:12', '2026-08-21 01:58:46'),
('da376b72-30d2-42fb-81ee-ae0941969566', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":84,\"task_title\":\"creative \",\"message\":\"Anjali assigned you to \\\"creative \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/84\"}', '2026-08-25 07:35:02', '2026-08-24 00:07:21', '2026-08-25 07:35:02'),
('da6adcc0-e62a-4bf6-a050-ad54e7b156f0', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":128,\"task_title\":\"insta highlights cover\",\"message\":\"Anjali assigned you to \\\"insta highlights cover\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/128\"}', NULL, '2026-09-01 03:50:38', '2026-09-01 03:50:38'),
('dac99eb3-894a-4432-82ff-3b744ae98ea1', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":42,\"task_title\":\"Creative fior Candid school\",\"message\":\"Anjali assigned you to \\\"Creative fior Candid school\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/42\"}', NULL, '2026-08-18 23:49:00', '2026-08-18 23:49:00'),
('de212885-9919-40b8-bedf-0fd93698e3ee', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":118,\"task_title\":\"Mrunalini\'s Diet Hub\",\"message\":\"Sakshi assigned you to \\\"Mrunalini\'s Diet Hub\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/118\"}', NULL, '2026-08-31 23:57:22', '2026-08-31 23:57:22');
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('e042e09e-71b4-4cf9-a366-b2fcd8975dd6', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":80,\"task_title\":\"Raksha bandhan-post\",\"message\":\"Anjali assigned you to \\\"Raksha bandhan-post\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/80\"}', NULL, '2026-08-24 00:00:51', '2026-08-24 00:00:51'),
('e16f9afe-14e2-4dc7-88bf-a64102a9fbd7', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":117,\"task_title\":\"Creative -if rakshabandhan \",\"message\":\"Anjali assigned you to \\\"Creative -if rakshabandhan \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/117\"}', NULL, '2026-08-26 07:52:06', '2026-08-26 07:52:06'),
('e1e56c1b-adc2-4383-a5b4-0f5506b8c35e', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":49,\"task_title\":\"Linked in cover -H&M \",\"message\":\"Anjali assigned you to \\\"Linked in cover -H&M \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/49\"}', '2026-08-19 07:56:25', '2026-08-19 00:09:56', '2026-08-19 07:56:25'),
('e20466bf-4ad0-44ff-9bb5-f023c629b81f', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":32,\"task_title\":\"try\",\"message\":\"Midbrains Superadmin assigned you to \\\"try\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/32\"}', '2026-08-18 01:36:44', '2026-08-18 01:35:56', '2026-08-18 01:36:44'),
('e46915d0-32f2-42c5-9c9b-fd888af708fa', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":107,\"task_title\":\"rakshabandhan \",\"message\":\"Anjali assigned you to \\\"rakshabandhan \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/107\"}', '2026-08-27 01:59:14', '2026-08-25 07:49:53', '2026-08-27 01:59:14'),
('e4efd15c-d774-4f87-890d-0bf7ac66296e', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":139,\"task_title\":\"jnmashtmi \",\"message\":\"Anjali assigned you to \\\"jnmashtmi \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/139\"}', NULL, '2026-09-03 00:34:43', '2026-09-03 00:34:43'),
('e7561d07-b778-437a-915b-1c6f28353d2c', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 7, '{\"type\":\"task_assigned\",\"task_id\":70,\"task_title\":\"Video changes-second hand cars \",\"message\":\"Anjali assigned you to \\\"Video changes-second hand cars \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/70\"}', NULL, '2026-08-22 00:00:19', '2026-08-22 00:00:19'),
('e9189ff6-6d76-4dc1-a7dc-2a778528f5f7', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":22,\"task_title\":\"Star Auto - Post\",\"message\":\"Sakshi assigned you to \\\"Star Auto - Post\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/22\"}', '2026-08-17 02:12:48', '2026-08-10 06:26:21', '2026-08-17 02:12:48'),
('ea5399a8-cd72-43de-9536-230d766e9c80', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":158,\"task_title\":\"GopalKala\",\"message\":\"Anjali assigned you to \\\"GopalKala\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/158\"}', NULL, '2026-09-04 05:33:17', '2026-09-04 05:33:17'),
('eafe7111-6ae2-4f88-bb26-d619b745ec74', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":23,\"task_title\":\"Try \",\"message\":\"Midbrains Superadmin assigned you to \\\"Try \\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/23\"}', '2026-08-17 02:12:48', '2026-08-17 00:53:17', '2026-08-17 02:12:48'),
('ecd8dc6f-2175-484c-8275-3d8d88e2e76e', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":100,\"task_title\":\"Raksha bandhan\",\"message\":\"Anjali assigned you to \\\"Raksha bandhan\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/100\"}', '2026-08-25 07:35:02', '2026-08-25 01:26:48', '2026-08-25 07:35:02'),
('f16db845-24f6-4344-b1b4-c4d533b10d14', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":161,\"task_title\":\"Jnmashtmi\",\"message\":\"Anjali assigned you to \\\"Jnmashtmi\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/161\"}', NULL, '2026-09-04 05:51:36', '2026-09-04 05:51:36'),
('f27d3f71-83ef-474a-94c3-9609bfdcaf3c', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":50,\"task_title\":\"Before-After Reel\",\"message\":\"Anjali assigned you to \\\"Before-After Reel\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/50\"}', '2026-08-19 23:43:42', '2026-08-19 23:02:24', '2026-08-19 23:43:42'),
('f7b0780b-94e3-460e-885a-38d179d3dc6d', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":24,\"task_title\":\"Star Auto \\u2014 Facebook Post #1\",\"message\":\"Midbrains Superadmin assigned you to \\\"Star Auto \\u2014 Facebook Post #1\\\".\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/tasks\\/24\"}', '2026-08-17 02:12:48', '2026-08-17 01:26:47', '2026-08-17 02:12:48'),
('f9671711-4a62-45b9-a931-d113a0f0ec34', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":73,\"task_title\":\"Festive post-Ed-E-milad\",\"message\":\"Anjali assigned you to \\\"Festive post-Ed-E-milad\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/73\"}', NULL, '2026-08-22 02:03:04', '2026-08-22 02:03:04'),
('fb67e106-6dd2-4017-92af-f070ede1f8bd', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":121,\"task_title\":\"creative\",\"message\":\"Anjali assigned you to \\\"creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/121\"}', '2026-09-01 01:50:23', '2026-09-01 00:59:39', '2026-09-01 01:50:23'),
('fd4fd04c-e3e0-4ec7-9f7e-1d0009008b79', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 10, '{\"type\":\"task_assigned\",\"task_id\":116,\"task_title\":\"Creative \",\"message\":\"Anjali assigned you to \\\"Creative \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/116\"}', NULL, '2026-08-26 07:51:24', '2026-08-26 07:51:24'),
('fdbc1633-7eed-4282-bb03-23ef1e2bfe56', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":153,\"task_title\":\"Teachers Day\",\"message\":\"Anjali assigned you to \\\"Teachers Day\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/153\"}', NULL, '2026-09-04 05:24:17', '2026-09-04 05:24:17'),
('fea39b00-cb4c-4fb2-a98a-de2409c64f4d', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 8, '{\"type\":\"task_assigned\",\"task_id\":123,\"task_title\":\"creative\",\"message\":\"Anjali assigned you to \\\"creative\\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/123\"}', '2026-09-01 01:50:23', '2026-09-01 01:07:56', '2026-09-01 01:50:23'),
('ff7dda37-4792-4a58-b35c-10f3aadea448', 'App\\Notifications\\TaskAssignedNotification', 'App\\Models\\User', 9, '{\"type\":\"task_assigned\",\"task_id\":34,\"task_title\":\"Korean or Hydra Facial \",\"message\":\"Anjali assigned you to \\\"Korean or Hydra Facial \\\".\",\"url\":\"https:\\/\\/manage.midbrains.in\\/tasks\\/34\"}', '2026-08-19 23:43:42', '2026-08-18 23:33:48', '2026-08-19 23:43:42');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `guard_name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2026-07-27 02:52:02', '2026-07-27 02:52:02'),
(2, 'Manager', 'web', '2026-07-27 02:52:02', '2026-07-27 02:52:02'),
(3, 'Team Lead', 'web', '2026-07-27 02:52:02', '2026-07-27 02:52:02'),
(4, 'Employee', 'web', '2026-07-27 02:52:02', '2026-07-27 02:52:02'),
(5, 'HR', 'web', '2026-07-28 04:06:34', '2026-07-28 04:06:34');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Website Development', '2026-07-31 06:50:33', '2026-07-31 06:50:33'),
(2, 'SEO', '2026-07-31 06:50:39', '2026-07-31 06:50:39'),
(3, 'GMB', '2026-07-31 07:08:13', '2026-07-31 07:08:13'),
(4, 'Shoot', '2026-07-31 07:08:24', '2026-07-31 07:08:24'),
(5, 'gm', '2026-07-31 07:14:07', '2026-07-31 07:14:07'),
(6, 'as', '2026-07-31 07:14:10', '2026-07-31 07:14:10'),
(7, 'AI Videos', '2026-08-17 01:51:35', '2026-08-17 01:51:35'),
(8, 'Shoot Edit', '2026-08-17 01:51:40', '2026-08-17 01:51:40'),
(9, 'Reels', '2026-08-17 01:51:43', '2026-08-17 01:51:43');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(191) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('E1sGxu9trU85jru5EOvFXHPjrqag9MGtyc5rzonC', 11, '103.133.159.141', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQ3BFNWVveG1SaXJCMFo5MWkzRVQ5SHE5dmI2Q25iN2l0S2xkT3Z0QiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vbWFuYWdlLm1pZGJyYWlucy5pbi93b3JrLWxvZyI7czo1OiJyb3V0ZSI7czoxNDoid29yay1sb2cuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTE7fQ==', 1788513991),
('FgJv3X8MOaXgBXfSQdj2txtXU6o71gmnS5dozcqK', 6, '103.133.159.141', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSzN4R256am5Yek1pVEtManZOd3BTRnBHWkNKeFd2RVg4bXJ0ODlsOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vbWFuYWdlLm1pZGJyYWlucy5pbi90YXNrcyI7czo1OiJyb3V0ZSI7czoxMToidGFza3MuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1788513368),
('QcT1Sp9gWJ3zMB7smLKGKOFyp4fafqhAoDVwCy3I', 1, '103.133.159.141', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoicjllZE1sQU40a3JlVFlqNUU2dUFUQ1dDeGdDQVNKUHRoZWd6aUhsQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vbWFuYWdlLm1pZGJyYWlucy5pbi93b3JrLWxvZy9yZXBvcnQiO3M6NToicm91dGUiO3M6MTU6IndvcmstbG9nLnJlcG9ydCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1788513969),
('R4cZybcZiBnlPvKY8diHs3JHVFVhJ8mfPQjmvcVn', 9, '103.133.159.141', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN0l0c1g3cms3MGk4NjJpVzlEZGlFZHl5eFpaTmV1Y0xiU2kwMDZwdSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6OTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMzoiaHR0cHM6Ly9tYW5hZ2UubWlkYnJhaW5zLmluL3Rhc2tzIjtzOjU6InJvdXRlIjtzOjExOiJ0YXNrcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1788513981),
('S8v2u3uXX4YOfDeQn5p0WT8xhtac7ZdjsMNTjh5Y', 10, '103.249.243.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieHB2MEhoN2FQQnpGQlJIWTB5eUZTU2pEQ1RKUzNMVU9SN2llV25LYSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTA7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8vbWFuYWdlLm1pZGJyYWlucy5pbi90YXNrcyI7czo1OiJyb3V0ZSI7czoxMToidGFza3MuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1788506593);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `description` text,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `content_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_extra_delivery` tinyint(1) NOT NULL DEFAULT '0',
  `project_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Reserved for Phase 2 Projects module',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `priority_auto_escalated` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('pending','in_progress','on_hold','delayed','pending_review','completed','cancelled') NOT NULL DEFAULT 'pending',
  `task_type` enum('normal','add_on') NOT NULL DEFAULT 'normal',
  `parent_interrupted_task_id` bigint(20) UNSIGNED DEFAULT NULL,
  `due_date` datetime NOT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `estimated_hours` decimal(6,2) DEFAULT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deletion_reason` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `created_by`, `department_id`, `client_id`, `content_type_id`, `is_extra_delivery`, `project_id`, `priority`, `priority_auto_escalated`, `status`, `task_type`, `parent_interrupted_task_id`, `due_date`, `started_at`, `completed_at`, `estimated_hours`, `is_archived`, `created_at`, `updated_at`, `deleted_at`, `deleted_by`, `deletion_reason`) VALUES
(23, 'Try ', 'try', 1, 1, 80, NULL, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-08-17 18:23:00', '2026-08-17 06:59:36', '2026-08-17 07:18:38', NULL, 0, '2026-08-17 00:53:15', '2026-08-18 07:24:38', '2026-08-18 07:24:38', 1, 'try'),
(24, 'Star Auto — Facebook Post #1', NULL, 1, 1, 74, 2, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-08-31 23:59:00', NULL, NULL, NULL, 0, '2026-08-17 01:26:47', '2026-08-17 01:28:31', '2026-08-17 01:28:31', 1, 'try'),
(25, 'Star Auto — Facebook Post #2', NULL, 1, 1, 74, 2, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-08-31 23:59:00', NULL, NULL, NULL, 0, '2026-08-17 01:26:47', '2026-08-17 01:27:48', '2026-08-17 01:27:48', 1, 'try'),
(26, 'Star Auto — Facebook Post #3', NULL, 1, 1, 74, 2, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-08-31 23:59:00', NULL, NULL, NULL, 0, '2026-08-17 01:26:47', '2026-08-17 01:27:53', '2026-08-17 01:27:53', 1, 'try'),
(27, 'Star Auto — Facebook Post #4', NULL, 1, 1, 74, 2, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-08-31 23:59:00', NULL, NULL, NULL, 0, '2026-08-17 01:26:47', '2026-08-17 01:27:57', '2026-08-17 01:27:57', 1, 'try'),
(28, 'Star Auto — Instagram Post #1', NULL, 1, 1, 74, 2, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-08-31 23:59:00', NULL, NULL, NULL, 0, '2026-08-17 01:26:47', '2026-08-17 01:28:02', '2026-08-17 01:28:02', 1, 'try'),
(29, 'Star Auto — Instagram Post #2', NULL, 1, 1, 74, 2, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-08-31 23:59:00', NULL, NULL, NULL, 0, '2026-08-17 01:26:47', '2026-08-17 01:28:15', '2026-08-17 01:28:15', 1, 'try'),
(30, 'Star Auto — Instagram Post #3', NULL, 1, 1, 74, 2, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-08-31 23:59:00', NULL, NULL, NULL, 0, '2026-08-17 01:26:47', '2026-08-17 01:28:11', '2026-08-17 01:28:11', 1, 'try'),
(31, 'Star Auto — Instagram Post #4', NULL, 1, 1, 74, 2, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-08-31 23:59:00', NULL, NULL, NULL, 0, '2026-08-17 01:26:47', '2026-08-17 01:28:27', '2026-08-17 01:28:27', 1, 'try'),
(32, 'try', 'try', 1, 4, 80, NULL, 0, NULL, 'high', 0, 'in_progress', 'normal', NULL, '2026-08-18 19:05:00', '2026-08-18 07:06:40', NULL, NULL, 0, '2026-08-18 01:35:54', '2026-08-18 07:23:53', '2026-08-18 07:23:53', 1, 'try'),
(33, 'Dr.Digish ', 'used next video ', 6, 4, NULL, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-22 05:00:00', '2026-08-19 07:22:51', '2026-08-21 04:35:37', NULL, 0, '2026-08-18 23:31:49', '2026-08-20 23:05:37', NULL, NULL, NULL),
(34, 'Korean or Hydra Facial ', 'Search the raw data and use it in the reel.', 6, 4, NULL, NULL, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-08-22 05:02:00', '2026-08-19 06:57:43', '2026-08-20 04:29:08', NULL, 0, '2026-08-18 23:33:48', '2026-08-19 22:59:08', NULL, NULL, NULL),
(35, 'AI reel - Moshi Hospital', 'script will be shared ', 6, 4, NULL, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-22 05:03:00', '2026-08-19 10:04:32', '2026-08-19 12:49:35', NULL, 0, '2026-08-18 23:34:52', '2026-08-19 07:19:35', NULL, NULL, NULL),
(36, 'pending AI reel ', '', 6, 4, 82, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-22 05:04:00', '2026-08-19 06:57:30', '2026-08-19 11:20:56', NULL, 0, '2026-08-18 23:35:56', '2026-08-19 05:50:56', NULL, NULL, NULL),
(37, 'casa deco reel', 'raw data used from you tube channel', 6, 4, 75, 1, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-08-22 05:06:00', '2026-08-19 12:20:31', '2026-08-20 10:24:05', NULL, 0, '2026-08-18 23:38:08', '2026-08-20 04:54:05', NULL, NULL, NULL),
(38, 'Creative For Celebrino Villa ', 'use original Images ', 6, 4, 103, 1, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-08-22 05:08:00', NULL, NULL, NULL, 0, '2026-08-18 23:40:52', '2026-08-20 06:06:13', '2026-08-20 06:06:13', 6, 'given to rohan '),
(39, 'You tube cover image -Tamara Spa', 'For business details -https://tamaraspapune.in/', 6, 4, 108, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-22 05:10:00', '2026-08-22 13:30:40', '2026-08-24 04:54:39', NULL, 0, '2026-08-18 23:43:20', '2026-08-23 23:24:39', NULL, NULL, NULL),
(40, 'Creative for Trupti Ayurveda', 'Content will be shared ', 6, 4, 99, NULL, 0, NULL, 'high', 1, 'pending', 'normal', NULL, '2026-08-22 05:13:00', NULL, NULL, NULL, 0, '2026-08-18 23:46:08', '2026-09-02 23:44:33', '2026-09-02 23:44:33', 5, 'Content not received from client '),
(41, 'Creative For Om dental', 'Content will be shared ', 6, 4, 98, NULL, 0, NULL, 'high', 1, 'pending', 'normal', NULL, '2026-08-22 05:17:00', NULL, NULL, NULL, 0, '2026-08-18 23:48:13', '2026-09-02 23:45:27', '2026-09-02 23:45:27', 5, 'Required creative video '),
(42, 'Creative for Candid school', 'Content will be shared ', 6, 4, 95, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-08-22 05:18:00', NULL, NULL, NULL, 0, '2026-08-18 23:49:00', '2026-08-20 04:52:58', '2026-08-20 04:52:58', 6, 'given to rohan '),
(43, 'Creative for Tamara Spa ', '', 6, 4, NULL, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-22 05:19:00', '2026-08-19 05:29:17', '2026-09-02 06:51:04', NULL, 0, '2026-08-18 23:49:38', '2026-09-02 01:21:04', NULL, NULL, NULL),
(44, 'ganpati festival standee', 'Check group', 6, 4, 93, NULL, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-08-22 05:20:00', '2026-08-19 10:37:47', '2026-09-02 07:01:36', NULL, 0, '2026-08-18 23:51:13', '2026-09-02 01:31:36', NULL, NULL, NULL),
(45, 'Carousel post', 'check group', 6, 4, 84, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-22 05:21:00', '2026-08-20 05:02:10', '2026-08-21 04:40:12', NULL, 0, '2026-08-18 23:52:04', '2026-08-20 23:10:12', NULL, NULL, NULL),
(46, 'Dr.Digish -creative ', '', 6, 4, NULL, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-22 05:22:00', '2026-08-19 06:58:44', '2026-09-01 06:54:35', NULL, 0, '2026-08-18 23:53:17', '2026-09-01 01:24:35', NULL, NULL, NULL),
(47, 'Creative -parekh jewellers', '', 6, 4, 77, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-22 05:24:00', '2026-08-19 07:23:50', '2026-08-20 10:35:10', NULL, 0, '2026-08-18 23:54:49', '2026-08-20 05:05:10', NULL, NULL, NULL),
(48, 'informative creative', '', 6, 4, 85, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-22 05:25:00', '2026-08-19 12:01:51', '2026-08-21 05:17:55', NULL, 0, '2026-08-18 23:56:37', '2026-08-20 23:47:55', NULL, NULL, NULL),
(49, 'Linked in cover -H&M ', '', 6, 4, 87, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-22 05:39:00', '2026-08-20 07:10:26', '2026-08-20 10:35:21', NULL, 0, '2026-08-19 00:09:56', '2026-08-20 05:05:21', NULL, NULL, NULL),
(50, 'Before-After Reel', 'raw data provided', 6, 4, 96, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-21 04:30:00', '2026-08-20 07:21:19', '2026-08-24 12:43:43', NULL, 0, '2026-08-19 23:02:24', '2026-08-24 07:13:43', NULL, NULL, NULL),
(51, 'Testimonial ', 'raw data given ', 6, 4, 82, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-23 04:32:00', '2026-08-20 07:47:39', '2026-08-25 09:13:15', NULL, 0, '2026-08-19 23:03:02', '2026-08-25 03:43:15', NULL, NULL, NULL),
(52, 'Reel changes ', '', 6, 4, 92, 1, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-23 04:33:00', '2026-08-21 05:27:59', '2026-08-21 05:28:30', NULL, 0, '2026-08-19 23:03:39', '2026-08-20 23:58:30', NULL, NULL, NULL),
(53, 'Thumbnail', 'Dr.digish\'s video ', 6, 4, 90, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-23 04:34:00', '2026-08-22 06:46:15', '2026-08-22 06:47:31', NULL, 0, '2026-08-19 23:05:23', '2026-08-22 01:17:31', NULL, NULL, NULL),
(54, 'Creative ', '', 6, 4, 92, NULL, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-08-23 07:32:00', '2026-08-24 04:55:25', '2026-08-24 04:55:33', NULL, 0, '2026-08-20 02:02:37', '2026-08-23 23:25:33', NULL, NULL, NULL),
(55, 'Creative ', '', 6, 4, 95, NULL, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-08-23 10:23:00', '2026-08-20 11:45:21', '2026-08-21 04:36:02', NULL, 0, '2026-08-20 04:53:44', '2026-08-20 23:06:02', NULL, NULL, NULL),
(56, 'Botox Treatment ', '', 6, 4, 87, 1, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-23 11:03:00', '2026-08-20 11:46:08', '2026-09-03 05:12:35', NULL, 0, '2026-08-20 05:34:03', '2026-09-02 23:42:35', NULL, NULL, NULL),
(57, 'GIF Of Glory School', '', 6, 4, 106, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-23 11:04:00', '2026-08-21 05:28:15', '2026-08-21 09:55:10', NULL, 0, '2026-08-20 05:34:53', '2026-08-21 04:25:10', NULL, NULL, NULL),
(58, 'BTS ', '', 6, 4, 80, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-23 11:04:00', '2026-08-21 06:24:38', '2026-08-24 12:44:30', NULL, 0, '2026-08-20 05:35:27', '2026-08-24 07:14:30', NULL, NULL, NULL),
(59, 'saturday ', '', 6, 4, 95, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-23 11:33:00', '2026-08-20 12:46:49', '2026-08-21 04:40:41', NULL, 0, '2026-08-20 06:04:57', '2026-08-20 23:10:41', NULL, NULL, NULL),
(60, 'check instagram account', '', 6, 4, 103, NULL, 0, NULL, 'high', 1, 'pending_review', 'normal', NULL, '2026-08-23 11:35:00', '2026-08-24 04:54:00', NULL, NULL, 0, '2026-08-20 06:06:00', '2026-08-23 23:26:39', '2026-08-23 23:26:39', 6, 'completed by rani '),
(61, 'Raw data given ', '', 6, 4, 105, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-23 11:39:00', '2026-08-21 08:20:29', '2026-08-24 04:53:51', NULL, 0, '2026-08-20 06:09:49', '2026-08-23 23:23:51', NULL, NULL, NULL),
(62, 'Thumbnail', '', 6, 4, 90, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-23 12:55:00', '2026-08-22 06:45:54', '2026-08-22 06:47:29', NULL, 0, '2026-08-20 07:26:07', '2026-08-22 01:17:29', NULL, NULL, NULL),
(63, 'any jewelly reel', '', 6, 4, 93, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-24 04:39:00', '2026-08-22 12:30:28', '2026-09-03 05:12:52', NULL, 0, '2026-08-20 23:09:30', '2026-09-02 23:42:52', NULL, NULL, NULL),
(64, 'offer creative', '', 6, 4, 87, NULL, 0, NULL, 'critical', 0, 'completed', 'normal', NULL, '2026-08-24 04:40:00', '2026-08-21 07:28:04', '2026-08-22 07:36:20', NULL, 0, '2026-08-20 23:11:12', '2026-08-22 02:06:20', NULL, NULL, NULL),
(65, 'festive post-eid e milad', '', 6, 4, 105, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-24 08:55:00', '2026-08-22 13:37:10', '2026-08-24 04:53:10', NULL, 0, '2026-08-21 03:26:02', '2026-08-23 23:23:10', NULL, NULL, NULL),
(66, 'creative', 'use original images', 6, 4, 103, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-24 09:40:00', '2026-08-22 06:45:31', '2026-08-22 06:47:24', NULL, 0, '2026-08-21 04:10:40', '2026-08-22 01:17:24', NULL, NULL, NULL),
(67, 'Gold rate increase AI video', '', 6, 4, 77, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-24 10:37:00', '2026-08-22 08:21:39', '2026-08-24 04:52:41', NULL, 0, '2026-08-21 05:08:28', '2026-08-23 23:22:41', NULL, NULL, NULL),
(68, 'video nanoplasticity', '', 6, 4, 87, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-24 13:08:00', '2026-08-21 13:19:32', '2026-08-24 12:44:02', NULL, 0, '2026-08-21 07:38:52', '2026-08-24 07:14:02', NULL, NULL, NULL),
(69, 'Video Changes -Swift car', '', 6, 4, 74, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-25 05:28:00', '2026-08-22 05:33:30', '2026-08-24 04:52:47', NULL, 0, '2026-08-21 23:59:20', '2026-08-23 23:22:47', NULL, NULL, NULL),
(70, 'Video changes-second hand cars ', '', 6, 4, 74, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-25 05:29:00', '2026-08-22 05:32:13', '2026-08-24 04:52:50', NULL, 0, '2026-08-22 00:00:19', '2026-08-23 23:22:50', NULL, NULL, NULL),
(71, 'next reel', '', 6, 4, 90, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-25 06:47:00', '2026-08-22 12:30:26', '2026-09-03 05:16:05', NULL, 0, '2026-08-22 01:18:11', '2026-09-02 23:46:05', NULL, NULL, NULL),
(72, 'Video', 'raw data given ', 6, 4, 80, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-25 07:29:00', '2026-08-22 10:46:09', '2026-08-24 04:52:58', NULL, 0, '2026-08-22 01:59:55', '2026-08-23 23:22:58', NULL, NULL, NULL),
(73, 'Festive post-Ed-E-milad', '', 6, 4, 85, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-25 07:32:00', '2026-08-22 13:29:53', '2026-08-26 06:34:02', NULL, 0, '2026-08-22 02:03:04', '2026-08-26 01:04:02', NULL, NULL, NULL),
(74, 'women\'s equality day', '', 6, 4, 104, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-25 07:44:00', '2026-08-22 10:33:16', '2026-08-26 06:33:46', NULL, 0, '2026-08-22 02:15:38', '2026-08-26 01:03:46', NULL, NULL, NULL),
(75, 'GIF-festive -Eid-E-milad', '', 6, 4, 97, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-25 07:51:00', '2026-08-24 07:50:37', '2026-08-24 12:44:13', NULL, 0, '2026-08-22 02:22:13', '2026-08-24 07:14:13', NULL, NULL, NULL),
(76, 'diamond rose reel', '', 6, 4, NULL, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-08-27 05:02:00', '2026-08-24 08:10:16', NULL, NULL, 0, '2026-08-23 23:33:35', '2026-08-24 02:40:21', NULL, NULL, NULL),
(77, 'women\'s equality day', '', 6, 4, 75, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-27 05:09:00', '2026-08-24 09:03:32', '2026-08-24 12:41:24', NULL, 0, '2026-08-23 23:40:24', '2026-08-24 07:11:24', NULL, NULL, NULL),
(78, 'women\'s equality day', '', 6, 4, 90, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-27 05:10:00', '2026-08-24 05:18:33', '2026-08-24 12:41:20', NULL, 0, '2026-08-23 23:41:44', '2026-08-24 07:11:20', NULL, NULL, NULL),
(79, 'festive- Eid', '', 6, 4, 95, NULL, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-08-27 05:11:00', '2026-08-25 07:00:59', '2026-09-03 05:30:40', NULL, 0, '2026-08-23 23:42:38', '2026-09-03 00:00:40', NULL, NULL, NULL),
(80, 'Raksha bandhan-post', '', 6, 4, 98, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-27 05:30:00', '2026-08-25 04:01:17', '2026-08-25 05:21:14', NULL, 0, '2026-08-24 00:00:51', '2026-08-24 23:51:14', NULL, NULL, NULL),
(81, 'Rakshbandhan  post ', '', 6, 4, 104, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-27 05:31:00', '2026-08-24 11:22:16', '2026-08-26 06:34:09', NULL, 0, '2026-08-24 00:02:09', '2026-08-26 01:04:09', NULL, NULL, NULL),
(82, 'Rakshbandhan  post ', '', 6, 4, 105, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-27 05:32:00', '2026-08-24 10:17:23', '2026-08-24 12:41:13', NULL, 0, '2026-08-24 00:02:27', '2026-08-24 07:11:13', NULL, NULL, NULL),
(83, 'reel', '', 6, 4, 75, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-27 05:35:00', '2026-08-25 11:34:44', '2026-09-03 11:39:00', NULL, 0, '2026-08-24 00:05:32', '2026-09-03 06:09:00', NULL, NULL, NULL),
(84, 'creative ', 'check group', 6, 4, 76, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-27 05:37:00', '2026-08-25 09:22:49', '2026-09-04 10:46:15', NULL, 0, '2026-08-24 00:07:21', '2026-09-04 05:16:15', NULL, NULL, NULL),
(85, 'reel', '', 6, 4, 84, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-08-27 05:38:00', '2026-08-24 07:50:48', NULL, NULL, 0, '2026-08-24 00:08:38', '2026-08-25 04:56:38', NULL, NULL, NULL),
(86, 'creative', 'use given topics', 6, 4, 79, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-08-27 06:31:00', '2026-08-25 09:56:00', NULL, NULL, 0, '2026-08-24 01:02:32', '2026-08-25 04:59:54', NULL, NULL, NULL),
(87, 'Video', '', 6, 4, 78, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-27 07:52:00', '2026-08-24 11:21:57', '2026-08-24 12:53:06', NULL, 0, '2026-08-24 02:22:37', '2026-08-24 07:23:06', NULL, NULL, NULL),
(88, 'Best out of waste reel', '', 6, 4, 85, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-27 10:18:00', '2026-08-24 12:33:13', '2026-08-25 09:26:49', NULL, 0, '2026-08-24 04:49:16', '2026-08-25 03:56:49', NULL, NULL, NULL),
(89, 'client treatment and review', '', 6, 4, 98, NULL, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-08-28 05:05:00', '2026-08-25 05:46:45', '2026-09-03 11:39:10', NULL, 0, '2026-08-24 23:44:27', '2026-09-03 06:09:10', NULL, NULL, NULL),
(90, 'Thumnail', '', 6, 4, 90, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 05:18:00', '2026-08-25 10:06:31', '2026-08-26 06:36:17', NULL, 0, '2026-08-24 23:50:51', '2026-08-26 01:06:17', NULL, NULL, NULL),
(91, 'Raksha Bandhan', '', 6, 4, 107, NULL, 0, NULL, 'high', 1, 'pending', 'normal', NULL, '2026-08-28 05:25:00', NULL, NULL, NULL, 0, '2026-08-24 23:56:31', '2026-09-03 00:23:25', '2026-09-03 00:23:25', 6, 'Done by rohan'),
(92, 'sports day ', '', 6, 4, 98, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 05:37:00', '2026-08-26 04:09:54', '2026-09-04 10:40:55', NULL, 0, '2026-08-25 00:07:17', '2026-09-04 05:10:55', NULL, NULL, NULL),
(93, 'Sports Day', '', 6, 4, 104, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 05:40:00', '2026-08-25 10:53:44', '2026-08-26 06:33:39', NULL, 0, '2026-08-25 00:10:41', '2026-08-26 01:03:39', NULL, NULL, NULL),
(94, 'sports day ', '', 6, 4, 105, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 05:40:00', '2026-08-25 10:04:59', '2026-09-04 10:41:00', NULL, 0, '2026-08-25 00:11:06', '2026-09-04 05:11:00', NULL, NULL, NULL),
(95, 'Nail art', '', 6, 4, 87, NULL, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-08-28 05:59:00', '2026-08-25 07:27:08', '2026-08-25 13:29:38', NULL, 0, '2026-08-25 00:29:21', '2026-08-25 07:59:38', NULL, NULL, NULL),
(96, 'rakshabandhan ', '', 6, 4, 97, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 06:00:00', '2026-08-25 07:27:23', '2026-08-25 10:03:48', NULL, 0, '2026-08-25 00:30:48', '2026-08-25 04:33:48', NULL, NULL, NULL),
(97, 'Raksha bandhan', '', 6, 4, 75, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 06:54:00', '2026-08-25 11:45:08', '2026-08-25 13:16:53', NULL, 0, '2026-08-25 01:25:13', '2026-08-25 07:46:53', NULL, NULL, NULL),
(98, 'Raksha bandhan', '', 6, 4, 76, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 06:55:00', '2026-08-27 11:25:40', '2026-09-04 10:46:31', NULL, 0, '2026-08-25 01:25:37', '2026-09-04 05:16:31', NULL, NULL, NULL),
(99, 'Raksha bandhan', '', 6, 4, 77, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 06:55:00', '2026-08-27 11:25:50', '2026-09-04 10:46:34', NULL, 0, '2026-08-25 01:26:00', '2026-09-04 05:16:34', NULL, NULL, NULL),
(100, 'Raksha bandhan', '', 6, 4, 79, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 06:56:00', '2026-08-26 07:47:17', '2026-09-04 10:46:39', NULL, 0, '2026-08-25 01:26:48', '2026-09-04 05:16:39', NULL, NULL, NULL),
(101, 'Raksha bandhan', '', 6, 4, 80, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 06:57:00', '2026-08-26 08:22:18', '2026-09-04 10:46:43', NULL, 0, '2026-08-25 01:28:04', '2026-09-04 05:16:43', NULL, NULL, NULL),
(102, 'Raksha bandhan', '', 6, 4, 84, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 06:58:00', '2026-08-26 07:04:40', '2026-09-04 10:46:47', NULL, 0, '2026-08-25 01:29:09', '2026-09-04 05:16:47', NULL, NULL, NULL),
(103, 'Video Edit', 'data is given', 6, 4, 77, NULL, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-08-28 10:21:00', '2026-08-25 10:35:51', '2026-09-04 10:44:08', NULL, 0, '2026-08-25 04:51:55', '2026-09-04 05:14:08', NULL, NULL, NULL),
(104, 'Rakshabandan ', '', 6, 4, 95, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 13:15:00', '2026-08-26 06:52:12', '2026-09-03 11:39:19', NULL, 0, '2026-08-25 07:45:43', '2026-09-03 06:09:19', NULL, NULL, NULL),
(105, 'sports day', '', 6, 4, 80, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 13:17:00', '2026-08-26 09:11:13', '2026-09-04 10:47:00', NULL, 0, '2026-08-25 07:47:44', '2026-09-04 05:17:00', NULL, NULL, NULL),
(106, 'sports day', '', 6, 4, 85, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-08-28 13:19:00', NULL, NULL, NULL, 0, '2026-08-25 07:49:20', '2026-08-26 03:33:05', '2026-08-26 03:33:05', 6, 'client not active '),
(107, 'rakshabandhan ', '', 6, 4, 93, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 13:19:00', '2026-08-27 09:31:48', '2026-09-04 10:47:04', NULL, 0, '2026-08-25 07:49:53', '2026-09-04 05:17:04', NULL, NULL, NULL),
(108, 'raksha bandhan ', '', 6, 4, 82, NULL, 0, NULL, 'high', 1, 'completed', 'normal', NULL, '2026-08-28 13:20:00', '2026-09-03 05:31:44', '2026-09-04 10:41:04', NULL, 0, '2026-08-25 07:50:23', '2026-09-04 05:11:04', NULL, NULL, NULL),
(109, 'Flex', '', 6, 4, 92, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-28 13:26:00', '2026-08-26 06:59:20', '2026-09-04 10:47:09', NULL, 0, '2026-08-25 07:57:00', '2026-09-04 05:17:09', NULL, NULL, NULL),
(110, 'AI video ', '', 6, 4, 74, NULL, 0, NULL, 'high', 1, 'pending_review', 'normal', NULL, '2026-08-28 13:27:00', '2026-08-29 10:09:51', NULL, NULL, 0, '2026-08-25 07:57:25', '2026-08-29 04:40:00', NULL, NULL, NULL),
(111, 'Testimonial', '', 6, 4, 104, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-08-29 06:45:00', '2026-08-26 07:40:07', NULL, NULL, 0, '2026-08-26 01:15:36', '2026-08-26 04:03:47', NULL, NULL, NULL),
(112, 'Creative ', '', 6, 4, 92, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-29 11:34:00', '2026-08-27 07:21:08', '2026-09-04 10:47:16', NULL, 0, '2026-08-26 06:04:30', '2026-09-04 05:17:16', NULL, NULL, NULL),
(113, 'Offer Creative -1', '', 6, 4, 87, NULL, 0, NULL, 'high', 0, 'in_progress', 'normal', NULL, '2026-08-29 11:34:00', '2026-08-27 05:31:46', NULL, NULL, 0, '2026-08-26 06:05:24', '2026-09-04 05:17:24', NULL, NULL, NULL),
(114, 'offer reel', '', 6, 4, 99, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-08-29 12:29:00', '2026-08-26 13:15:31', '2026-09-03 11:39:31', NULL, 0, '2026-08-26 07:00:02', '2026-09-03 06:09:31', NULL, NULL, NULL),
(115, 'Creative', '', 6, 4, 94, NULL, 0, NULL, 'high', 1, 'completed', 'normal', NULL, '2026-08-29 13:20:00', '2026-09-03 05:55:27', '2026-09-03 05:55:33', NULL, 0, '2026-08-26 07:51:09', '2026-09-03 00:25:33', NULL, NULL, NULL),
(116, 'Creative ', '', 6, 4, 103, NULL, 0, NULL, 'high', 1, 'pending', 'normal', NULL, '2026-08-29 13:21:00', NULL, NULL, NULL, 0, '2026-08-26 07:51:24', '2026-08-31 00:00:17', NULL, NULL, NULL),
(117, 'Creative -if rakshabandhan ', '', 6, 4, 106, NULL, 0, NULL, 'high', 1, 'pending', 'normal', NULL, '2026-08-29 13:21:00', NULL, NULL, NULL, 0, '2026-08-26 07:52:06', '2026-09-03 00:25:14', '2026-09-03 00:25:14', 6, 'done by rohan'),
(118, 'Mrunalini\'s Diet Hub', 'Changes ', 5, 4, 78, NULL, 0, NULL, 'high', 1, 'completed', 'normal', NULL, '2026-09-01 11:15:00', '2026-09-03 17:37:01', '2026-09-04 10:43:44', NULL, 0, '2026-08-31 23:57:22', '2026-09-04 05:13:44', NULL, NULL, NULL),
(119, 'H&M Reel ', 'Raw data provided ', 5, 4, 87, NULL, 0, NULL, 'high', 1, 'pending_review', 'normal', NULL, '2026-09-01 12:15:00', '2026-09-03 17:37:05', NULL, NULL, 0, '2026-09-01 00:03:25', '2026-09-03 12:07:07', NULL, NULL, NULL),
(120, 'BUE Youtube Video ', 'Check DM mail ', 5, 4, 80, NULL, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-09-01 01:30:00', '2026-09-01 12:57:05', '2026-09-03 11:39:42', NULL, 0, '2026-09-01 00:06:20', '2026-09-03 06:09:42', NULL, NULL, NULL),
(121, 'creative', '', 6, 4, 75, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-09-04 06:29:00', '2026-09-01 06:48:04', '2026-09-04 10:47:32', NULL, 0, '2026-09-01 00:59:39', '2026-09-04 05:17:32', NULL, NULL, NULL),
(122, 'creative', '', 6, 4, 76, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-09-04 06:31:00', '2026-09-01 07:20:27', NULL, NULL, 0, '2026-09-01 01:02:17', '2026-09-01 02:14:24', NULL, NULL, NULL),
(123, 'creative', '', 6, 4, 80, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-09-04 06:37:00', '2026-09-01 09:41:49', NULL, NULL, 0, '2026-09-01 01:07:56', '2026-09-01 04:56:14', NULL, NULL, NULL),
(124, 'creative', '', 6, 4, 90, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-09-04 06:39:00', '2026-09-01 07:47:27', '2026-09-04 10:53:34', NULL, 0, '2026-09-01 01:09:42', '2026-09-04 05:23:34', NULL, NULL, NULL),
(125, 'creative', '', 6, 4, 84, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-04 06:44:00', NULL, NULL, NULL, 0, '2026-09-01 01:14:57', '2026-09-01 01:14:57', NULL, NULL, NULL),
(126, 'creative', '', 6, 4, 108, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-04 06:52:00', NULL, NULL, NULL, 0, '2026-09-01 01:22:35', '2026-09-01 01:22:35', NULL, NULL, NULL),
(127, 'creative', '', 6, 4, 79, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-04 06:53:00', NULL, NULL, NULL, 0, '2026-09-01 01:24:03', '2026-09-01 01:24:03', NULL, NULL, NULL),
(128, 'insta highlights cover', '', 6, 4, 103, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-09-04 09:20:00', '2026-09-02 05:41:20', '2026-09-04 10:42:03', NULL, 0, '2026-09-01 03:50:38', '2026-09-04 05:12:03', NULL, NULL, NULL),
(129, 'Exibition Creative ', '', 6, 4, 92, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-04 10:58:00', NULL, NULL, NULL, 0, '2026-09-01 05:28:44', '2026-09-03 01:08:26', NULL, NULL, NULL),
(130, 'Highlights Video', '', 6, 4, 87, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-09-04 13:20:00', '2026-09-03 17:38:40', '2026-09-04 10:46:00', NULL, 0, '2026-09-01 07:51:21', '2026-09-04 05:16:00', NULL, NULL, NULL),
(131, 'Enhance Dental ', 'Janmashtami & Teachers Day creative ', 5, 4, 104, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-09-02 06:30:00', '2026-09-02 12:48:22', '2026-09-04 10:41:11', NULL, 0, '2026-09-02 05:33:19', '2026-09-04 05:11:11', NULL, NULL, NULL),
(132, 'Candid ', 'Janmashtami Creative ', 5, 4, 95, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-09-02 06:30:00', '2026-09-02 12:47:58', NULL, NULL, 0, '2026-09-02 05:56:37', '2026-09-04 07:36:51', '2026-09-04 07:36:51', 6, 'given to the vishal'),
(133, 'testimonial ', '', 6, 4, 79, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-09-06 05:20:00', '2026-09-03 17:36:49', NULL, NULL, 0, '2026-09-02 23:50:43', '2026-09-03 12:06:51', NULL, NULL, NULL),
(134, 'Reel', '', 6, 4, 84, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-09-06 05:20:00', '2026-09-03 17:36:53', '2026-09-04 10:43:16', NULL, 0, '2026-09-02 23:51:51', '2026-09-04 05:13:16', NULL, NULL, NULL),
(135, 'Same Offer Creatives', '', 6, 4, 87, NULL, 0, NULL, 'critical', 0, 'pending', 'normal', NULL, '2026-09-06 05:26:00', NULL, NULL, NULL, 0, '2026-09-02 23:56:36', '2026-09-02 23:56:36', NULL, NULL, NULL),
(136, 'Jnmashtmi', '', 6, 4, 87, NULL, 0, NULL, 'high', 0, 'completed', 'normal', NULL, '2026-09-06 05:26:00', '2026-09-03 11:06:23', '2026-09-04 10:41:43', NULL, 0, '2026-09-02 23:57:13', '2026-09-04 05:11:43', NULL, NULL, NULL),
(137, 'Creative -pearl resort(check group)', '', 6, 4, NULL, NULL, 0, NULL, 'high', 0, 'pending', 'normal', NULL, '2026-09-06 05:29:00', NULL, NULL, NULL, 0, '2026-09-03 00:00:14', '2026-09-04 05:29:41', '2026-09-04 05:29:41', 6, 'given to rani'),
(138, 'Janmashtmi post', '', 6, 4, 75, NULL, 0, NULL, 'high', 0, 'pending', 'normal', NULL, '2026-09-06 05:55:00', NULL, NULL, NULL, 0, '2026-09-03 00:26:58', '2026-09-03 00:27:31', '2026-09-03 00:27:31', 6, 'asigning to rohan'),
(139, 'jnmashtmi ', '', 6, 4, 95, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-09-06 06:04:00', '2026-09-03 11:06:45', NULL, NULL, 0, '2026-09-03 00:34:43', '2026-09-04 07:36:39', '2026-09-04 07:36:39', 6, 'given to the vishal'),
(140, 'jnmashtmi ', '', 6, 4, 75, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-06 06:17:00', NULL, NULL, NULL, 0, '2026-09-03 00:47:37', '2026-09-03 00:47:37', NULL, NULL, NULL),
(141, 'Teachers day', '', 6, 4, 75, NULL, 0, NULL, 'medium', 0, 'on_hold', 'normal', NULL, '2026-09-06 06:17:00', '2026-09-04 11:07:52', NULL, NULL, 0, '2026-09-03 00:48:13', '2026-09-04 05:38:31', NULL, NULL, NULL),
(142, 'Teachers Day ', '', 6, 4, 90, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-09-06 06:19:00', '2026-09-03 08:16:16', NULL, NULL, 0, '2026-09-03 00:50:41', '2026-09-03 02:46:26', NULL, NULL, NULL),
(143, 'Teachers day', '', 6, 4, 98, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-06 06:20:00', NULL, NULL, NULL, 0, '2026-09-03 00:51:35', '2026-09-03 03:38:25', '2026-09-03 03:38:25', 6, 'done buy rohan'),
(144, 'teachers day ', '', 6, 4, 105, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-09-06 06:22:00', '2026-09-03 17:41:42', '2026-09-04 10:42:54', NULL, 0, '2026-09-03 00:53:13', '2026-09-04 05:12:54', NULL, NULL, NULL),
(145, 'Jnmashtmi', '', 6, 4, 77, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-09-06 06:38:00', '2026-09-03 10:42:33', NULL, NULL, 0, '2026-09-03 01:08:55', '2026-09-03 05:12:35', NULL, NULL, NULL),
(146, 'Jnmashtmi', '', 6, 4, 80, NULL, 0, NULL, 'medium', 0, 'in_progress', 'normal', NULL, '2026-09-06 06:38:00', '2026-09-03 10:42:48', NULL, NULL, 0, '2026-09-03 01:10:28', '2026-09-03 05:12:48', NULL, NULL, NULL),
(147, 'Exibition Reel', '', 6, 4, 92, NULL, 0, NULL, 'medium', 0, 'pending_review', 'normal', NULL, '2026-09-06 11:38:00', '2026-09-03 17:37:08', NULL, NULL, 0, '2026-09-03 06:08:46', '2026-09-04 07:34:35', NULL, NULL, NULL),
(148, 'Jnmashtmi', '', 6, 4, 105, NULL, 0, NULL, 'medium', 0, 'completed', 'normal', NULL, '2026-09-06 17:54:00', '2026-09-03 18:23:19', '2026-09-04 10:42:30', NULL, 0, '2026-09-03 12:24:19', '2026-09-04 05:12:30', NULL, NULL, NULL),
(149, 'Jnmashtmi', '', 6, 4, 94, NULL, 0, NULL, 'critical', 0, 'completed', 'normal', NULL, '2026-09-06 18:37:00', '2026-09-04 10:49:24', '2026-09-04 11:04:43', NULL, 0, '2026-09-03 13:07:18', '2026-09-04 05:34:43', NULL, NULL, NULL),
(150, 'Jnmashtmi', '', 6, 4, 93, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 10:48:00', NULL, NULL, NULL, 0, '2026-09-04 05:19:14', '2026-09-04 05:51:21', '2026-09-04 05:51:21', 6, 'given to rohan '),
(151, 'Story Video ', 'Raw Video is given', 6, 4, 105, NULL, 0, NULL, 'medium', 0, 'in_progress', 'normal', NULL, '2026-09-07 10:49:00', '2026-09-04 14:56:21', NULL, NULL, 0, '2026-09-04 05:20:29', '2026-09-04 09:26:21', NULL, NULL, NULL),
(152, 'reel', 'Raw Video is given', 6, 4, 106, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 10:50:00', NULL, NULL, NULL, 0, '2026-09-04 05:21:05', '2026-09-04 05:21:05', NULL, NULL, NULL),
(153, 'Teachers Day', '', 6, 4, 76, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 10:53:00', NULL, NULL, NULL, 0, '2026-09-04 05:24:17', '2026-09-04 05:24:17', NULL, NULL, NULL),
(154, 'Teachers day', '', 6, 4, 80, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 10:54:00', NULL, NULL, NULL, 0, '2026-09-04 05:25:45', '2026-09-04 05:25:45', NULL, NULL, NULL),
(155, 'Teachers day', '', 6, 4, 77, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 10:55:00', NULL, NULL, NULL, 0, '2026-09-04 05:26:22', '2026-09-04 05:26:22', NULL, NULL, NULL),
(156, 'Teachers day-midbrains', '', 6, 4, NULL, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 10:56:00', NULL, NULL, NULL, 0, '2026-09-04 05:27:03', '2026-09-04 05:27:34', NULL, NULL, NULL),
(157, 'Perl Resort-creative', '', 6, 4, NULL, NULL, 0, NULL, 'critical', 0, 'completed', 'normal', NULL, '2026-09-07 10:59:00', '2026-09-04 12:52:13', '2026-09-04 13:06:19', NULL, 0, '2026-09-04 05:30:17', '2026-09-04 07:36:19', NULL, NULL, NULL),
(158, 'GopalKala', '', 6, 4, 105, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 11:03:00', NULL, NULL, NULL, 0, '2026-09-04 05:33:17', '2026-09-04 05:33:17', NULL, NULL, NULL),
(159, 'Teachers Day', '', 6, 4, 94, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 11:05:00', NULL, NULL, NULL, 0, '2026-09-04 05:35:23', '2026-09-04 05:35:23', NULL, NULL, NULL),
(160, 'Teachers Day', '', 6, 4, 106, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 11:06:00', NULL, NULL, NULL, 0, '2026-09-04 05:36:50', '2026-09-04 07:15:10', '2026-09-04 07:15:10', 6, 'given to the rani'),
(161, 'Jnmashtmi', '', 6, 4, 93, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 11:21:00', NULL, NULL, NULL, 0, '2026-09-04 05:51:36', '2026-09-04 05:51:36', NULL, NULL, NULL),
(162, 'GIF-jnmashtmi', '', 6, 4, 95, NULL, 0, NULL, 'critical', 0, 'pending_review', 'normal', NULL, '2026-09-07 12:43:00', '2026-09-04 13:04:33', NULL, NULL, 0, '2026-09-04 07:14:15', '2026-09-04 09:20:28', NULL, NULL, NULL),
(163, 'Teachers Day', '', 6, 4, 95, NULL, 0, NULL, 'high', 0, 'pending_review', 'normal', NULL, '2026-09-07 12:44:00', '2026-09-04 14:50:30', NULL, NULL, 0, '2026-09-04 07:14:35', '2026-09-04 09:21:18', NULL, NULL, NULL),
(164, 'Glory-teachers day', '', 6, 4, 106, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 12:44:00', NULL, NULL, NULL, 0, '2026-09-04 07:14:53', '2026-09-04 07:14:53', NULL, NULL, NULL),
(165, 'Reel Thumbnail-podcaste TREX', '', 6, 4, NULL, NULL, 0, NULL, 'medium', 0, 'pending', 'normal', NULL, '2026-09-07 12:45:00', NULL, NULL, NULL, 0, '2026-09-04 07:16:07', '2026-09-04 07:16:07', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `task_assignments`
--

CREATE TABLE `task_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_to` bigint(20) UNSIGNED NOT NULL,
  `assigned_by` bigint(20) UNSIGNED NOT NULL,
  `assigned_at` datetime NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '1',
  `unassigned_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task_assignments`
--

INSERT INTO `task_assignments` (`id`, `task_id`, `assigned_to`, `assigned_by`, `assigned_at`, `is_current`, `unassigned_at`, `created_at`, `updated_at`) VALUES
(26, 23, 8, 1, '2026-08-17 06:23:15', 1, NULL, '2026-08-17 00:53:15', '2026-08-17 00:53:15'),
(27, 24, 8, 1, '2026-08-17 06:56:47', 1, NULL, '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(28, 25, 8, 1, '2026-08-17 06:56:47', 1, NULL, '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(29, 26, 8, 1, '2026-08-17 06:56:47', 1, NULL, '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(30, 27, 8, 1, '2026-08-17 06:56:47', 1, NULL, '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(31, 28, 8, 1, '2026-08-17 06:56:47', 1, NULL, '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(32, 29, 8, 1, '2026-08-17 06:56:47', 1, NULL, '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(33, 30, 8, 1, '2026-08-17 06:56:47', 1, NULL, '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(34, 31, 8, 1, '2026-08-17 06:56:47', 1, NULL, '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(35, 32, 8, 1, '2026-08-18 07:05:54', 1, NULL, '2026-08-18 01:35:54', '2026-08-18 01:35:54'),
(36, 33, 9, 6, '2026-08-19 05:01:49', 1, NULL, '2026-08-18 23:31:49', '2026-08-18 23:31:49'),
(37, 34, 9, 6, '2026-08-19 05:03:48', 1, NULL, '2026-08-18 23:33:48', '2026-08-18 23:33:48'),
(38, 35, 9, 6, '2026-08-19 05:04:52', 1, NULL, '2026-08-18 23:34:52', '2026-08-18 23:34:52'),
(39, 36, 9, 6, '2026-08-19 05:05:56', 1, NULL, '2026-08-18 23:35:56', '2026-08-18 23:35:56'),
(40, 37, 9, 6, '2026-08-19 05:08:08', 1, NULL, '2026-08-18 23:38:08', '2026-08-18 23:38:08'),
(41, 38, 10, 6, '2026-08-19 05:10:52', 1, NULL, '2026-08-18 23:40:52', '2026-08-18 23:40:52'),
(42, 39, 10, 6, '2026-08-19 05:13:20', 1, NULL, '2026-08-18 23:43:20', '2026-08-18 23:43:20'),
(43, 40, 10, 6, '2026-08-19 05:16:08', 1, NULL, '2026-08-18 23:46:08', '2026-08-18 23:46:08'),
(44, 41, 10, 6, '2026-08-19 05:18:13', 1, NULL, '2026-08-18 23:48:13', '2026-08-18 23:48:13'),
(45, 42, 10, 6, '2026-08-19 05:19:00', 1, NULL, '2026-08-18 23:49:00', '2026-08-18 23:49:00'),
(46, 43, 8, 6, '2026-08-19 05:19:38', 1, NULL, '2026-08-18 23:49:38', '2026-08-18 23:49:38'),
(47, 44, 8, 6, '2026-08-19 05:21:13', 1, NULL, '2026-08-18 23:51:13', '2026-08-18 23:51:13'),
(48, 45, 8, 6, '2026-08-19 05:22:04', 1, NULL, '2026-08-18 23:52:04', '2026-08-18 23:52:04'),
(49, 46, 8, 6, '2026-08-19 05:23:17', 1, NULL, '2026-08-18 23:53:17', '2026-08-18 23:53:17'),
(50, 47, 8, 6, '2026-08-19 05:24:49', 1, NULL, '2026-08-18 23:54:49', '2026-08-18 23:54:49'),
(51, 48, 8, 6, '2026-08-19 05:26:37', 1, NULL, '2026-08-18 23:56:37', '2026-08-18 23:56:37'),
(52, 49, 8, 6, '2026-08-19 05:39:56', 1, NULL, '2026-08-19 00:09:56', '2026-08-19 00:09:56'),
(53, 50, 9, 6, '2026-08-20 04:32:24', 1, NULL, '2026-08-19 23:02:24', '2026-08-19 23:02:24'),
(54, 51, 9, 6, '2026-08-20 04:33:02', 1, NULL, '2026-08-19 23:03:02', '2026-08-19 23:03:02'),
(55, 52, 9, 6, '2026-08-20 04:33:39', 1, NULL, '2026-08-19 23:03:39', '2026-08-19 23:03:39'),
(56, 53, 10, 6, '2026-08-20 04:35:23', 1, NULL, '2026-08-19 23:05:23', '2026-08-19 23:05:23'),
(57, 54, 8, 6, '2026-08-20 07:32:37', 1, NULL, '2026-08-20 02:02:37', '2026-08-20 02:02:37'),
(58, 55, 8, 6, '2026-08-20 10:23:44', 1, NULL, '2026-08-20 04:53:44', '2026-08-20 04:53:44'),
(59, 56, 9, 6, '2026-08-20 11:04:03', 1, NULL, '2026-08-20 05:34:03', '2026-08-20 05:34:03'),
(60, 57, 9, 6, '2026-08-20 11:04:53', 1, NULL, '2026-08-20 05:34:53', '2026-08-20 05:34:53'),
(61, 58, 9, 6, '2026-08-20 11:05:27', 1, NULL, '2026-08-20 05:35:27', '2026-08-20 05:35:27'),
(62, 59, 8, 6, '2026-08-20 11:34:57', 1, NULL, '2026-08-20 06:04:57', '2026-08-20 06:04:57'),
(63, 60, 8, 6, '2026-08-20 11:36:00', 1, NULL, '2026-08-20 06:06:00', '2026-08-20 06:06:00'),
(64, 61, 9, 6, '2026-08-20 11:39:49', 1, NULL, '2026-08-20 06:09:49', '2026-08-20 06:09:49'),
(65, 62, 10, 6, '2026-08-20 12:56:07', 1, NULL, '2026-08-20 07:26:07', '2026-08-20 07:26:07'),
(66, 63, 9, 6, '2026-08-21 04:39:30', 1, NULL, '2026-08-20 23:09:30', '2026-08-20 23:09:30'),
(67, 64, 8, 6, '2026-08-21 04:41:12', 1, NULL, '2026-08-20 23:11:12', '2026-08-20 23:11:12'),
(68, 65, 10, 6, '2026-08-21 08:56:02', 1, NULL, '2026-08-21 03:26:02', '2026-08-21 03:26:02'),
(69, 66, 10, 6, '2026-08-21 09:40:40', 1, NULL, '2026-08-21 04:10:40', '2026-08-21 04:10:40'),
(70, 67, 9, 6, '2026-08-21 10:38:28', 1, NULL, '2026-08-21 05:08:28', '2026-08-21 05:08:28'),
(71, 68, 9, 6, '2026-08-21 13:08:52', 1, NULL, '2026-08-21 07:38:52', '2026-08-21 07:38:52'),
(72, 69, 7, 6, '2026-08-22 05:29:20', 1, NULL, '2026-08-21 23:59:20', '2026-08-21 23:59:20'),
(73, 70, 7, 6, '2026-08-22 05:30:19', 1, NULL, '2026-08-22 00:00:19', '2026-08-22 00:00:19'),
(74, 71, 9, 6, '2026-08-22 06:48:11', 1, NULL, '2026-08-22 01:18:11', '2026-08-22 01:18:11'),
(75, 72, 9, 6, '2026-08-22 07:29:55', 1, NULL, '2026-08-22 01:59:55', '2026-08-22 01:59:55'),
(76, 73, 10, 6, '2026-08-22 07:33:04', 1, NULL, '2026-08-22 02:03:04', '2026-08-22 02:03:04'),
(77, 74, 10, 6, '2026-08-22 07:45:38', 1, NULL, '2026-08-22 02:15:38', '2026-08-22 02:15:38'),
(78, 75, 9, 6, '2026-08-22 07:52:13', 1, NULL, '2026-08-22 02:22:13', '2026-08-22 02:22:13'),
(79, 76, 7, 6, '2026-08-24 05:03:35', 1, NULL, '2026-08-23 23:33:35', '2026-08-23 23:33:35'),
(80, 77, 10, 6, '2026-08-24 05:10:24', 1, NULL, '2026-08-23 23:40:24', '2026-08-23 23:40:24'),
(81, 78, 10, 6, '2026-08-24 05:11:44', 1, NULL, '2026-08-23 23:41:44', '2026-08-23 23:41:44'),
(82, 79, 8, 6, '2026-08-24 05:12:38', 1, NULL, '2026-08-23 23:42:38', '2026-08-23 23:42:38'),
(83, 80, 10, 6, '2026-08-24 05:30:51', 1, NULL, '2026-08-24 00:00:51', '2026-08-24 00:00:51'),
(84, 81, 10, 6, '2026-08-24 05:32:09', 1, NULL, '2026-08-24 00:02:09', '2026-08-24 00:02:09'),
(85, 82, 10, 6, '2026-08-24 05:32:27', 1, NULL, '2026-08-24 00:02:27', '2026-08-24 00:02:27'),
(86, 83, 9, 6, '2026-08-24 05:35:32', 1, NULL, '2026-08-24 00:05:32', '2026-08-24 00:05:32'),
(87, 84, 8, 6, '2026-08-24 05:37:21', 1, NULL, '2026-08-24 00:07:21', '2026-08-24 00:07:21'),
(88, 85, 9, 6, '2026-08-24 05:38:38', 1, NULL, '2026-08-24 00:08:38', '2026-08-24 00:08:38'),
(89, 86, 8, 6, '2026-08-24 06:32:32', 1, NULL, '2026-08-24 01:02:32', '2026-08-24 01:02:32'),
(90, 87, 9, 6, '2026-08-24 07:52:37', 1, NULL, '2026-08-24 02:22:37', '2026-08-24 02:22:37'),
(91, 88, 9, 6, '2026-08-24 10:19:16', 1, NULL, '2026-08-24 04:49:16', '2026-08-24 04:49:16'),
(92, 89, 9, 6, '2026-08-25 05:14:27', 1, NULL, '2026-08-24 23:44:27', '2026-08-24 23:44:27'),
(93, 90, 10, 6, '2026-08-25 05:20:51', 1, NULL, '2026-08-24 23:50:51', '2026-08-24 23:50:51'),
(94, 91, 10, 6, '2026-08-25 05:26:31', 1, NULL, '2026-08-24 23:56:31', '2026-08-24 23:56:31'),
(95, 92, 10, 6, '2026-08-25 05:37:17', 1, NULL, '2026-08-25 00:07:17', '2026-08-25 00:07:17'),
(96, 93, 10, 6, '2026-08-25 05:40:41', 1, NULL, '2026-08-25 00:10:41', '2026-08-25 00:10:41'),
(97, 94, 10, 6, '2026-08-25 05:41:06', 1, NULL, '2026-08-25 00:11:06', '2026-08-25 00:11:06'),
(98, 95, 9, 6, '2026-08-25 05:59:21', 1, NULL, '2026-08-25 00:29:21', '2026-08-25 00:29:21'),
(99, 96, 9, 6, '2026-08-25 06:00:48', 1, NULL, '2026-08-25 00:30:48', '2026-08-25 00:30:48'),
(100, 97, 8, 6, '2026-08-25 06:55:13', 1, NULL, '2026-08-25 01:25:13', '2026-08-25 01:25:13'),
(101, 98, 8, 6, '2026-08-25 06:55:37', 1, NULL, '2026-08-25 01:25:37', '2026-08-25 01:25:37'),
(102, 99, 8, 6, '2026-08-25 06:56:00', 1, NULL, '2026-08-25 01:26:00', '2026-08-25 01:26:00'),
(103, 100, 8, 6, '2026-08-25 06:56:48', 1, NULL, '2026-08-25 01:26:48', '2026-08-25 01:26:48'),
(104, 101, 8, 6, '2026-08-25 06:58:04', 1, NULL, '2026-08-25 01:28:04', '2026-08-25 01:28:04'),
(105, 102, 8, 6, '2026-08-25 06:59:09', 1, NULL, '2026-08-25 01:29:09', '2026-08-25 01:29:09'),
(106, 103, 9, 6, '2026-08-25 10:21:55', 1, NULL, '2026-08-25 04:51:55', '2026-08-25 04:51:55'),
(107, 104, 9, 6, '2026-08-25 13:15:43', 1, NULL, '2026-08-25 07:45:43', '2026-08-25 07:45:43'),
(108, 105, 8, 6, '2026-08-25 13:17:44', 1, NULL, '2026-08-25 07:47:44', '2026-08-25 07:47:44'),
(109, 106, 10, 6, '2026-08-25 13:19:20', 1, NULL, '2026-08-25 07:49:20', '2026-08-25 07:49:20'),
(110, 107, 8, 6, '2026-08-25 13:19:53', 1, NULL, '2026-08-25 07:49:53', '2026-08-25 07:49:53'),
(111, 108, 10, 6, '2026-08-25 13:20:23', 1, NULL, '2026-08-25 07:50:23', '2026-08-25 07:50:23'),
(112, 109, 8, 6, '2026-08-25 13:27:00', 1, NULL, '2026-08-25 07:57:00', '2026-08-25 07:57:00'),
(113, 110, 8, 6, '2026-08-25 13:27:26', 1, NULL, '2026-08-25 07:57:26', '2026-08-25 07:57:26'),
(114, 111, 9, 6, '2026-08-26 06:45:36', 1, NULL, '2026-08-26 01:15:36', '2026-08-26 01:15:36'),
(115, 112, 8, 6, '2026-08-26 11:34:30', 1, NULL, '2026-08-26 06:04:30', '2026-08-26 06:04:30'),
(116, 113, 8, 6, '2026-08-26 11:35:24', 1, NULL, '2026-08-26 06:05:24', '2026-08-26 06:05:24'),
(117, 114, 9, 6, '2026-08-26 12:30:02', 1, NULL, '2026-08-26 07:00:02', '2026-08-26 07:00:02'),
(118, 115, 10, 6, '2026-08-26 13:21:09', 1, NULL, '2026-08-26 07:51:09', '2026-08-26 07:51:09'),
(119, 116, 10, 6, '2026-08-26 13:21:24', 1, NULL, '2026-08-26 07:51:24', '2026-08-26 07:51:24'),
(120, 117, 10, 6, '2026-08-26 13:22:06', 1, NULL, '2026-08-26 07:52:06', '2026-08-26 07:52:06'),
(121, 118, 9, 5, '2026-09-01 05:27:22', 1, NULL, '2026-08-31 23:57:22', '2026-08-31 23:57:22'),
(122, 119, 9, 5, '2026-09-01 05:33:25', 1, NULL, '2026-09-01 00:03:25', '2026-09-01 00:03:25'),
(123, 120, 9, 5, '2026-09-01 05:36:20', 1, NULL, '2026-09-01 00:06:20', '2026-09-01 00:06:20'),
(124, 121, 8, 6, '2026-09-01 06:29:39', 1, NULL, '2026-09-01 00:59:39', '2026-09-01 00:59:39'),
(125, 122, 8, 6, '2026-09-01 06:32:17', 1, NULL, '2026-09-01 01:02:17', '2026-09-01 01:02:17'),
(126, 123, 8, 6, '2026-09-01 06:37:56', 1, NULL, '2026-09-01 01:07:56', '2026-09-01 01:07:56'),
(127, 124, 8, 6, '2026-09-01 06:39:42', 1, NULL, '2026-09-01 01:09:42', '2026-09-01 01:09:42'),
(128, 125, 8, 6, '2026-09-01 06:44:57', 1, NULL, '2026-09-01 01:14:57', '2026-09-01 01:14:57'),
(129, 126, 8, 6, '2026-09-01 06:52:35', 1, NULL, '2026-09-01 01:22:35', '2026-09-01 01:22:35'),
(130, 127, 8, 6, '2026-09-01 06:54:03', 1, NULL, '2026-09-01 01:24:03', '2026-09-01 01:24:03'),
(131, 128, 10, 6, '2026-09-01 09:20:38', 1, NULL, '2026-09-01 03:50:38', '2026-09-01 03:50:38'),
(132, 129, 8, 6, '2026-09-01 10:58:44', 1, NULL, '2026-09-01 05:28:44', '2026-09-01 05:28:44'),
(133, 130, 9, 6, '2026-09-01 13:21:21', 1, NULL, '2026-09-01 07:51:21', '2026-09-01 07:51:21'),
(134, 131, 10, 5, '2026-09-02 11:03:19', 1, NULL, '2026-09-02 05:33:19', '2026-09-02 05:33:19'),
(135, 132, 10, 5, '2026-09-02 11:26:37', 1, NULL, '2026-09-02 05:56:37', '2026-09-02 05:56:37'),
(136, 133, 9, 6, '2026-09-03 05:20:43', 1, NULL, '2026-09-02 23:50:43', '2026-09-02 23:50:43'),
(137, 134, 9, 6, '2026-09-03 05:21:51', 1, NULL, '2026-09-02 23:51:51', '2026-09-02 23:51:51'),
(138, 135, 8, 6, '2026-09-03 05:26:36', 1, NULL, '2026-09-02 23:56:36', '2026-09-02 23:56:36'),
(139, 136, 10, 6, '2026-09-03 05:27:13', 1, NULL, '2026-09-02 23:57:13', '2026-09-02 23:57:13'),
(140, 137, 8, 6, '2026-09-03 05:30:14', 1, NULL, '2026-09-03 00:00:14', '2026-09-03 00:00:14'),
(141, 138, 10, 6, '2026-09-03 05:56:58', 1, NULL, '2026-09-03 00:26:58', '2026-09-03 00:26:58'),
(142, 139, 10, 6, '2026-09-03 06:04:43', 1, NULL, '2026-09-03 00:34:43', '2026-09-03 00:34:43'),
(143, 140, 8, 6, '2026-09-03 06:17:37', 1, NULL, '2026-09-03 00:47:37', '2026-09-03 00:47:37'),
(144, 141, 8, 6, '2026-09-03 06:18:13', 1, NULL, '2026-09-03 00:48:13', '2026-09-03 00:48:13'),
(145, 142, 8, 6, '2026-09-03 06:20:41', 1, NULL, '2026-09-03 00:50:41', '2026-09-03 00:50:41'),
(146, 143, 10, 6, '2026-09-03 06:21:35', 1, NULL, '2026-09-03 00:51:35', '2026-09-03 00:51:35'),
(147, 144, 10, 6, '2026-09-03 06:23:13', 1, NULL, '2026-09-03 00:53:13', '2026-09-03 00:53:13'),
(148, 145, 8, 6, '2026-09-03 06:38:55', 1, NULL, '2026-09-03 01:08:55', '2026-09-03 01:08:55'),
(149, 146, 8, 6, '2026-09-03 06:40:28', 1, NULL, '2026-09-03 01:10:28', '2026-09-03 01:10:28'),
(150, 147, 9, 6, '2026-09-03 11:38:46', 1, NULL, '2026-09-03 06:08:46', '2026-09-03 06:08:46'),
(151, 148, 10, 6, '2026-09-03 17:54:19', 1, NULL, '2026-09-03 12:24:19', '2026-09-03 12:24:19'),
(152, 149, 10, 6, '2026-09-03 18:37:18', 1, NULL, '2026-09-03 13:07:18', '2026-09-03 13:07:18'),
(153, 150, 10, 6, '2026-09-04 10:49:14', 1, NULL, '2026-09-04 05:19:14', '2026-09-04 05:19:14'),
(154, 151, 9, 6, '2026-09-04 10:50:29', 1, NULL, '2026-09-04 05:20:29', '2026-09-04 05:20:29'),
(155, 152, 9, 6, '2026-09-04 10:51:05', 1, NULL, '2026-09-04 05:21:05', '2026-09-04 05:21:05'),
(156, 153, 8, 6, '2026-09-04 10:54:17', 1, NULL, '2026-09-04 05:24:17', '2026-09-04 05:24:17'),
(157, 154, 8, 6, '2026-09-04 10:55:45', 1, NULL, '2026-09-04 05:25:45', '2026-09-04 05:25:45'),
(158, 155, 8, 6, '2026-09-04 10:56:22', 1, NULL, '2026-09-04 05:26:22', '2026-09-04 05:26:22'),
(159, 156, 8, 6, '2026-09-04 10:57:03', 1, NULL, '2026-09-04 05:27:03', '2026-09-04 05:27:03'),
(160, 157, 10, 6, '2026-09-04 11:00:17', 1, NULL, '2026-09-04 05:30:17', '2026-09-04 05:30:17'),
(161, 158, 10, 6, '2026-09-04 11:03:17', 1, NULL, '2026-09-04 05:33:17', '2026-09-04 05:33:17'),
(162, 159, 10, 6, '2026-09-04 11:05:23', 1, NULL, '2026-09-04 05:35:23', '2026-09-04 05:35:23'),
(163, 160, 8, 6, '2026-09-04 11:06:50', 1, NULL, '2026-09-04 05:36:50', '2026-09-04 05:36:50'),
(164, 161, 8, 6, '2026-09-04 11:21:36', 1, NULL, '2026-09-04 05:51:36', '2026-09-04 05:51:36'),
(165, 162, 9, 6, '2026-09-04 12:44:15', 1, NULL, '2026-09-04 07:14:15', '2026-09-04 07:14:15'),
(166, 163, 9, 6, '2026-09-04 12:44:35', 1, NULL, '2026-09-04 07:14:35', '2026-09-04 07:14:35'),
(167, 164, 10, 6, '2026-09-04 12:44:53', 1, NULL, '2026-09-04 07:14:53', '2026-09-04 07:14:53'),
(168, 165, 10, 6, '2026-09-04 12:46:07', 1, NULL, '2026-09-04 07:16:07', '2026-09-04 07:16:07');

-- --------------------------------------------------------

--
-- Table structure for table `task_attachments`
--

CREATE TABLE `task_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `comment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(191) NOT NULL,
  `file_name` varchar(191) NOT NULL,
  `file_size` bigint(20) UNSIGNED NOT NULL COMMENT 'bytes',
  `mime_type` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `task_comments`
--

CREATE TABLE `task_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `is_internal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `task_platforms`
--

CREATE TABLE `task_platforms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `platform` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task_platforms`
--

INSERT INTO `task_platforms` (`id`, `task_id`, `platform`, `created_at`) VALUES
(20, 24, 'Facebook', NULL),
(21, 25, 'Facebook', NULL),
(22, 26, 'Facebook', NULL),
(23, 27, 'Facebook', NULL),
(24, 28, 'Instagram', NULL),
(25, 29, 'Instagram', NULL),
(26, 30, 'Instagram', NULL),
(27, 31, 'Instagram', NULL),
(28, 37, 'Facebook', NULL),
(29, 37, 'Instagram', NULL),
(30, 37, 'LinkedIn', NULL),
(31, 37, 'YouTube', NULL),
(32, 52, 'Facebook', NULL),
(33, 52, 'Instagram', NULL),
(34, 52, 'LinkedIn', NULL),
(35, 52, 'YouTube', NULL),
(36, 56, 'Facebook', NULL),
(37, 56, 'Instagram', NULL),
(38, 56, 'LinkedIn', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `task_status_history`
--

CREATE TABLE `task_status_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `task_id` bigint(20) UNSIGNED NOT NULL,
  `old_status` varchar(191) DEFAULT NULL,
  `new_status` varchar(191) NOT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` text,
  `changed_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `task_status_history`
--

INSERT INTO `task_status_history` (`id`, `task_id`, `old_status`, `new_status`, `changed_by`, `reason`, `changed_at`, `created_at`, `updated_at`) VALUES
(105, 23, NULL, 'pending', 1, 'Task created.', '2026-08-17 06:23:17', '2026-08-17 00:53:17', '2026-08-17 00:53:17'),
(106, 24, NULL, 'pending', 1, 'Auto-generated from delivery plan.', '2026-08-17 06:56:47', '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(107, 25, NULL, 'pending', 1, 'Auto-generated from delivery plan.', '2026-08-17 06:56:47', '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(108, 26, NULL, 'pending', 1, 'Auto-generated from delivery plan.', '2026-08-17 06:56:47', '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(109, 27, NULL, 'pending', 1, 'Auto-generated from delivery plan.', '2026-08-17 06:56:47', '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(110, 28, NULL, 'pending', 1, 'Auto-generated from delivery plan.', '2026-08-17 06:56:47', '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(111, 29, NULL, 'pending', 1, 'Auto-generated from delivery plan.', '2026-08-17 06:56:47', '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(112, 30, NULL, 'pending', 1, 'Auto-generated from delivery plan.', '2026-08-17 06:56:47', '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(113, 31, NULL, 'pending', 1, 'Auto-generated from delivery plan.', '2026-08-17 06:56:47', '2026-08-17 01:26:47', '2026-08-17 01:26:47'),
(114, 23, 'pending', 'in_progress', 1, 'Manually overridden by Super Admin.', '2026-08-17 06:59:36', '2026-08-17 01:29:36', '2026-08-17 01:29:36'),
(115, 23, 'in_progress', 'pending_review', 1, 'Manually overridden by Super Admin.', '2026-08-17 06:59:40', '2026-08-17 01:29:40', '2026-08-17 01:29:40'),
(116, 23, 'pending_review', 'in_progress', 1, 'Changes', '2026-08-17 06:59:51', '2026-08-17 01:29:51', '2026-08-17 01:29:51'),
(117, 23, 'in_progress', 'pending_review', 1, 'Manually overridden by Super Admin.', '2026-08-17 07:12:40', '2026-08-17 01:42:40', '2026-08-17 01:42:40'),
(118, 23, 'pending_review', 'in_progress', 1, 'Missing Information: Check changes on Whatsapp', '2026-08-17 07:17:20', '2026-08-17 01:47:20', '2026-08-17 01:47:20'),
(119, 23, 'in_progress', 'pending_review', 8, NULL, '2026-08-17 07:18:28', '2026-08-17 01:48:28', '2026-08-17 01:48:28'),
(120, 23, 'pending_review', 'completed', 1, 'Manually overridden by Super Admin.', '2026-08-17 07:18:38', '2026-08-17 01:48:38', '2026-08-17 01:48:38'),
(121, 32, NULL, 'pending', 1, 'Task created.', '2026-08-18 07:05:56', '2026-08-18 01:35:56', '2026-08-18 01:35:56'),
(122, 32, 'pending', 'in_progress', 8, NULL, '2026-08-18 07:06:40', '2026-08-18 01:36:40', '2026-08-18 01:36:40'),
(123, 32, 'in_progress', 'pending_review', 8, NULL, '2026-08-18 07:06:50', '2026-08-18 01:36:50', '2026-08-18 01:36:50'),
(124, 32, 'pending_review', 'in_progress', 6, 'Changes from client', '2026-08-18 07:12:43', '2026-08-18 01:42:43', '2026-08-18 01:42:43'),
(125, 32, 'in_progress', 'pending_review', 8, NULL, '2026-08-18 07:13:04', '2026-08-18 01:43:04', '2026-08-18 01:43:04'),
(126, 32, 'pending_review', 'in_progress', 6, 'Incorrect Content: Check wp', '2026-08-18 07:18:23', '2026-08-18 01:48:23', '2026-08-18 01:48:23'),
(127, 33, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:01:49', '2026-08-18 23:31:49', '2026-08-18 23:31:49'),
(128, 34, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:03:48', '2026-08-18 23:33:48', '2026-08-18 23:33:48'),
(129, 35, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:04:52', '2026-08-18 23:34:52', '2026-08-18 23:34:52'),
(130, 36, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:05:56', '2026-08-18 23:35:56', '2026-08-18 23:35:56'),
(131, 37, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:08:08', '2026-08-18 23:38:08', '2026-08-18 23:38:08'),
(132, 38, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:10:53', '2026-08-18 23:40:53', '2026-08-18 23:40:53'),
(133, 39, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:13:20', '2026-08-18 23:43:20', '2026-08-18 23:43:20'),
(134, 40, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:16:08', '2026-08-18 23:46:08', '2026-08-18 23:46:08'),
(135, 41, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:18:13', '2026-08-18 23:48:13', '2026-08-18 23:48:13'),
(136, 42, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:19:00', '2026-08-18 23:49:00', '2026-08-18 23:49:00'),
(137, 43, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:19:38', '2026-08-18 23:49:38', '2026-08-18 23:49:38'),
(138, 44, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:21:13', '2026-08-18 23:51:13', '2026-08-18 23:51:13'),
(139, 45, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:22:04', '2026-08-18 23:52:04', '2026-08-18 23:52:04'),
(140, 46, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:23:17', '2026-08-18 23:53:17', '2026-08-18 23:53:17'),
(141, 47, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:24:49', '2026-08-18 23:54:49', '2026-08-18 23:54:49'),
(142, 48, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:26:37', '2026-08-18 23:56:37', '2026-08-18 23:56:37'),
(143, 43, 'pending', 'in_progress', 8, NULL, '2026-08-19 05:29:17', '2026-08-18 23:59:17', '2026-08-18 23:59:17'),
(144, 43, 'in_progress', 'delayed', 8, NULL, '2026-08-19 05:29:42', '2026-08-18 23:59:42', '2026-08-18 23:59:42'),
(145, 43, 'delayed', 'in_progress', 6, NULL, '2026-08-19 05:31:39', '2026-08-19 00:01:39', '2026-08-19 00:01:39'),
(146, 49, NULL, 'pending', 6, 'Task created.', '2026-08-19 05:39:56', '2026-08-19 00:09:56', '2026-08-19 00:09:56'),
(147, 43, 'in_progress', 'pending_review', 8, NULL, '2026-08-19 06:26:41', '2026-08-19 00:56:41', '2026-08-19 00:56:41'),
(148, 36, 'pending', 'in_progress', 9, NULL, '2026-08-19 06:57:30', '2026-08-19 01:27:30', '2026-08-19 01:27:30'),
(149, 34, 'pending', 'in_progress', 9, NULL, '2026-08-19 06:57:43', '2026-08-19 01:27:43', '2026-08-19 01:27:43'),
(150, 34, 'in_progress', 'pending_review', 9, NULL, '2026-08-19 06:57:56', '2026-08-19 01:27:56', '2026-08-19 01:27:56'),
(151, 36, 'in_progress', 'pending_review', 9, NULL, '2026-08-19 06:58:10', '2026-08-19 01:28:10', '2026-08-19 01:28:10'),
(152, 46, 'pending', 'in_progress', 8, NULL, '2026-08-19 06:58:44', '2026-08-19 01:28:44', '2026-08-19 01:28:44'),
(153, 46, 'in_progress', 'pending_review', 8, NULL, '2026-08-19 07:20:46', '2026-08-19 01:50:46', '2026-08-19 01:50:46'),
(154, 33, 'pending', 'in_progress', 9, NULL, '2026-08-19 07:22:51', '2026-08-19 01:52:51', '2026-08-19 01:52:51'),
(155, 47, 'pending', 'in_progress', 8, NULL, '2026-08-19 07:23:50', '2026-08-19 01:53:50', '2026-08-19 01:53:50'),
(156, 47, 'in_progress', 'pending_review', 8, NULL, '2026-08-19 09:45:02', '2026-08-19 04:15:02', '2026-08-19 04:15:02'),
(157, 33, 'in_progress', 'pending_review', 9, NULL, '2026-08-19 10:04:26', '2026-08-19 04:34:26', '2026-08-19 04:34:26'),
(158, 35, 'pending', 'in_progress', 9, NULL, '2026-08-19 10:04:32', '2026-08-19 04:34:32', '2026-08-19 04:34:32'),
(159, 44, 'pending', 'in_progress', 8, NULL, '2026-08-19 10:37:47', '2026-08-19 05:07:47', '2026-08-19 05:07:47'),
(160, 36, 'pending_review', 'completed', 6, NULL, '2026-08-19 11:20:56', '2026-08-19 05:50:56', '2026-08-19 05:50:56'),
(161, 44, 'in_progress', 'pending_review', 8, NULL, '2026-08-19 11:47:54', '2026-08-19 06:17:54', '2026-08-19 06:17:54'),
(162, 48, 'pending', 'in_progress', 8, NULL, '2026-08-19 12:01:51', '2026-08-19 06:31:51', '2026-08-19 06:31:51'),
(163, 35, 'in_progress', 'pending_review', 9, NULL, '2026-08-19 12:19:58', '2026-08-19 06:49:58', '2026-08-19 06:49:58'),
(164, 37, 'pending', 'in_progress', 9, NULL, '2026-08-19 12:20:31', '2026-08-19 06:50:31', '2026-08-19 06:50:31'),
(165, 37, 'in_progress', 'pending_review', 9, NULL, '2026-08-19 12:20:36', '2026-08-19 06:50:36', '2026-08-19 06:50:36'),
(166, 37, 'pending_review', 'in_progress', 6, 'Needs Changes', '2026-08-19 12:22:11', '2026-08-19 06:52:11', '2026-08-19 06:52:11'),
(167, 35, 'pending_review', 'completed', 6, NULL, '2026-08-19 12:49:35', '2026-08-19 07:19:35', '2026-08-19 07:19:35'),
(168, 48, 'in_progress', 'pending_review', 8, NULL, '2026-08-19 12:59:57', '2026-08-19 07:29:57', '2026-08-19 07:29:57'),
(169, 34, 'pending_review', 'completed', 6, NULL, '2026-08-20 04:29:08', '2026-08-19 22:59:08', '2026-08-19 22:59:08'),
(170, 50, NULL, 'pending', 6, 'Task created.', '2026-08-20 04:32:24', '2026-08-19 23:02:24', '2026-08-19 23:02:24'),
(171, 51, NULL, 'pending', 6, 'Task created.', '2026-08-20 04:33:02', '2026-08-19 23:03:02', '2026-08-19 23:03:02'),
(172, 52, NULL, 'pending', 6, 'Task created.', '2026-08-20 04:33:39', '2026-08-19 23:03:39', '2026-08-19 23:03:39'),
(173, 53, NULL, 'pending', 6, 'Task created.', '2026-08-20 04:35:23', '2026-08-19 23:05:23', '2026-08-19 23:05:23'),
(174, 45, 'pending', 'in_progress', 8, NULL, '2026-08-20 05:02:10', '2026-08-19 23:32:10', '2026-08-19 23:32:10'),
(175, 37, 'in_progress', 'pending_review', 9, NULL, '2026-08-20 05:47:06', '2026-08-20 00:17:06', '2026-08-20 00:17:06'),
(176, 45, 'in_progress', 'pending_review', 8, NULL, '2026-08-20 07:04:16', '2026-08-20 01:34:16', '2026-08-20 01:34:16'),
(177, 49, 'pending', 'in_progress', 8, NULL, '2026-08-20 07:10:26', '2026-08-20 01:40:26', '2026-08-20 01:40:26'),
(178, 50, 'pending', 'in_progress', 9, NULL, '2026-08-20 07:21:19', '2026-08-20 01:51:19', '2026-08-20 01:51:19'),
(179, 49, 'in_progress', 'pending_review', 8, NULL, '2026-08-20 07:30:14', '2026-08-20 02:00:14', '2026-08-20 02:00:14'),
(180, 54, NULL, 'pending', 6, 'Task created.', '2026-08-20 07:32:37', '2026-08-20 02:02:37', '2026-08-20 02:02:37'),
(181, 50, 'in_progress', 'pending_review', 9, NULL, '2026-08-20 07:47:30', '2026-08-20 02:17:30', '2026-08-20 02:17:30'),
(182, 51, 'pending', 'in_progress', 9, NULL, '2026-08-20 07:47:39', '2026-08-20 02:17:39', '2026-08-20 02:17:39'),
(183, 55, NULL, 'pending', 6, 'Task created.', '2026-08-20 10:23:44', '2026-08-20 04:53:44', '2026-08-20 04:53:44'),
(184, 37, 'pending_review', 'completed', 6, NULL, '2026-08-20 10:24:05', '2026-08-20 04:54:05', '2026-08-20 04:54:05'),
(185, 47, 'pending_review', 'completed', 6, NULL, '2026-08-20 10:35:10', '2026-08-20 05:05:10', '2026-08-20 05:05:10'),
(186, 49, 'pending_review', 'completed', 6, NULL, '2026-08-20 10:35:21', '2026-08-20 05:05:21', '2026-08-20 05:05:21'),
(187, 56, NULL, 'pending', 6, 'Task created.', '2026-08-20 11:04:03', '2026-08-20 05:34:03', '2026-08-20 05:34:03'),
(188, 57, NULL, 'pending', 6, 'Task created.', '2026-08-20 11:04:53', '2026-08-20 05:34:53', '2026-08-20 05:34:53'),
(189, 58, NULL, 'pending', 6, 'Task created.', '2026-08-20 11:05:27', '2026-08-20 05:35:27', '2026-08-20 05:35:27'),
(190, 59, NULL, 'pending', 6, 'Task created.', '2026-08-20 11:34:57', '2026-08-20 06:04:57', '2026-08-20 06:04:57'),
(191, 60, NULL, 'pending', 6, 'Task created.', '2026-08-20 11:36:00', '2026-08-20 06:06:00', '2026-08-20 06:06:00'),
(192, 61, NULL, 'pending', 6, 'Task created.', '2026-08-20 11:39:49', '2026-08-20 06:09:49', '2026-08-20 06:09:49'),
(193, 55, 'pending', 'in_progress', 8, NULL, '2026-08-20 11:45:21', '2026-08-20 06:15:21', '2026-08-20 06:15:21'),
(194, 51, 'in_progress', 'pending_review', 9, NULL, '2026-08-20 11:46:04', '2026-08-20 06:16:04', '2026-08-20 06:16:04'),
(195, 56, 'pending', 'in_progress', 9, NULL, '2026-08-20 11:46:08', '2026-08-20 06:16:08', '2026-08-20 06:16:08'),
(196, 55, 'in_progress', 'pending_review', 8, NULL, '2026-08-20 12:46:43', '2026-08-20 07:16:43', '2026-08-20 07:16:43'),
(197, 59, 'pending', 'in_progress', 8, NULL, '2026-08-20 12:46:49', '2026-08-20 07:16:49', '2026-08-20 07:16:49'),
(198, 59, 'in_progress', 'pending_review', 8, NULL, '2026-08-20 12:46:53', '2026-08-20 07:16:53', '2026-08-20 07:16:53'),
(199, 62, NULL, 'pending', 6, 'Task created.', '2026-08-20 12:56:07', '2026-08-20 07:26:07', '2026-08-20 07:26:07'),
(200, 33, 'pending_review', 'completed', 6, NULL, '2026-08-21 04:35:37', '2026-08-20 23:05:37', '2026-08-20 23:05:37'),
(201, 55, 'pending_review', 'completed', 6, NULL, '2026-08-21 04:36:02', '2026-08-20 23:06:02', '2026-08-20 23:06:02'),
(202, 63, NULL, 'pending', 6, 'Task created.', '2026-08-21 04:39:30', '2026-08-20 23:09:30', '2026-08-20 23:09:30'),
(203, 45, 'pending_review', 'completed', 6, NULL, '2026-08-21 04:40:12', '2026-08-20 23:10:12', '2026-08-20 23:10:12'),
(204, 59, 'pending_review', 'completed', 6, NULL, '2026-08-21 04:40:41', '2026-08-20 23:10:41', '2026-08-20 23:10:41'),
(205, 64, NULL, 'pending', 6, 'Task created.', '2026-08-21 04:41:12', '2026-08-20 23:11:12', '2026-08-20 23:11:12'),
(206, 56, 'in_progress', 'pending_review', 9, NULL, '2026-08-21 05:00:36', '2026-08-20 23:30:36', '2026-08-20 23:30:36'),
(207, 48, 'pending_review', 'completed', 6, NULL, '2026-08-21 05:17:55', '2026-08-20 23:47:55', '2026-08-20 23:47:55'),
(208, 52, 'pending', 'in_progress', 9, NULL, '2026-08-21 05:27:59', '2026-08-20 23:57:59', '2026-08-20 23:57:59'),
(209, 52, 'in_progress', 'pending_review', 9, NULL, '2026-08-21 05:28:00', '2026-08-20 23:58:00', '2026-08-20 23:58:00'),
(210, 57, 'pending', 'in_progress', 9, NULL, '2026-08-21 05:28:15', '2026-08-20 23:58:15', '2026-08-20 23:58:15'),
(211, 57, 'in_progress', 'pending_review', 9, NULL, '2026-08-21 05:28:18', '2026-08-20 23:58:18', '2026-08-20 23:58:18'),
(212, 52, 'pending_review', 'completed', 6, NULL, '2026-08-21 05:28:30', '2026-08-20 23:58:30', '2026-08-20 23:58:30'),
(213, 58, 'pending', 'in_progress', 9, NULL, '2026-08-21 06:24:38', '2026-08-21 00:54:38', '2026-08-21 00:54:38'),
(214, 64, 'pending', 'in_progress', 8, NULL, '2026-08-21 07:28:04', '2026-08-21 01:58:04', '2026-08-21 01:58:04'),
(215, 64, 'in_progress', 'pending_review', 8, NULL, '2026-08-21 07:28:10', '2026-08-21 01:58:10', '2026-08-21 01:58:10'),
(216, 58, 'in_progress', 'pending_review', 9, NULL, '2026-08-21 08:20:27', '2026-08-21 02:50:27', '2026-08-21 02:50:27'),
(217, 61, 'pending', 'in_progress', 9, NULL, '2026-08-21 08:20:29', '2026-08-21 02:50:29', '2026-08-21 02:50:29'),
(218, 65, NULL, 'pending', 6, 'Task created.', '2026-08-21 08:56:02', '2026-08-21 03:26:02', '2026-08-21 03:26:02'),
(219, 66, NULL, 'pending', 6, 'Task created.', '2026-08-21 09:40:40', '2026-08-21 04:10:40', '2026-08-21 04:10:40'),
(220, 57, 'pending_review', 'completed', 6, NULL, '2026-08-21 09:55:10', '2026-08-21 04:25:10', '2026-08-21 04:25:10'),
(221, 67, NULL, 'pending', 6, 'Task created.', '2026-08-21 10:38:28', '2026-08-21 05:08:28', '2026-08-21 05:08:28'),
(222, 61, 'in_progress', 'pending_review', 9, NULL, '2026-08-21 12:04:47', '2026-08-21 06:34:47', '2026-08-21 06:34:47'),
(223, 68, NULL, 'pending', 6, 'Task created.', '2026-08-21 13:08:52', '2026-08-21 07:38:52', '2026-08-21 07:38:52'),
(224, 68, 'pending', 'in_progress', 9, NULL, '2026-08-21 13:19:32', '2026-08-21 07:49:32', '2026-08-21 07:49:32'),
(225, 68, 'in_progress', 'pending_review', 9, NULL, '2026-08-21 13:19:40', '2026-08-21 07:49:40', '2026-08-21 07:49:40'),
(226, 69, NULL, 'pending', 6, 'Task created.', '2026-08-22 05:29:20', '2026-08-21 23:59:20', '2026-08-21 23:59:20'),
(227, 70, NULL, 'pending', 6, 'Task created.', '2026-08-22 05:30:19', '2026-08-22 00:00:19', '2026-08-22 00:00:19'),
(228, 70, 'pending', 'in_progress', 7, NULL, '2026-08-22 05:32:13', '2026-08-22 00:02:13', '2026-08-22 00:02:13'),
(229, 70, 'in_progress', 'pending_review', 7, NULL, '2026-08-22 05:32:22', '2026-08-22 00:02:22', '2026-08-22 00:02:22'),
(230, 69, 'pending', 'in_progress', 7, NULL, '2026-08-22 05:33:30', '2026-08-22 00:03:30', '2026-08-22 00:03:30'),
(231, 69, 'in_progress', 'pending_review', 7, NULL, '2026-08-22 05:58:31', '2026-08-22 00:28:31', '2026-08-22 00:28:31'),
(232, 66, 'pending', 'in_progress', 10, NULL, '2026-08-22 06:45:31', '2026-08-22 01:15:31', '2026-08-22 01:15:31'),
(233, 66, 'in_progress', 'pending_review', 10, NULL, '2026-08-22 06:45:36', '2026-08-22 01:15:36', '2026-08-22 01:15:36'),
(234, 62, 'pending', 'in_progress', 10, NULL, '2026-08-22 06:45:54', '2026-08-22 01:15:54', '2026-08-22 01:15:54'),
(235, 62, 'in_progress', 'pending_review', 10, NULL, '2026-08-22 06:46:00', '2026-08-22 01:16:00', '2026-08-22 01:16:00'),
(236, 53, 'pending', 'in_progress', 10, NULL, '2026-08-22 06:46:15', '2026-08-22 01:16:15', '2026-08-22 01:16:15'),
(237, 53, 'in_progress', 'pending_review', 10, NULL, '2026-08-22 06:46:18', '2026-08-22 01:16:18', '2026-08-22 01:16:18'),
(238, 66, 'pending_review', 'completed', 6, NULL, '2026-08-22 06:47:24', '2026-08-22 01:17:24', '2026-08-22 01:17:24'),
(239, 62, 'pending_review', 'completed', 6, NULL, '2026-08-22 06:47:29', '2026-08-22 01:17:29', '2026-08-22 01:17:29'),
(240, 53, 'pending_review', 'completed', 6, NULL, '2026-08-22 06:47:31', '2026-08-22 01:17:31', '2026-08-22 01:17:31'),
(241, 71, NULL, 'pending', 6, 'Task created.', '2026-08-22 06:48:11', '2026-08-22 01:18:11', '2026-08-22 01:18:11'),
(242, 72, NULL, 'pending', 6, 'Task created.', '2026-08-22 07:29:55', '2026-08-22 01:59:55', '2026-08-22 01:59:55'),
(243, 73, NULL, 'pending', 6, 'Task created.', '2026-08-22 07:33:04', '2026-08-22 02:03:04', '2026-08-22 02:03:04'),
(244, 64, 'pending_review', 'completed', 6, NULL, '2026-08-22 07:36:20', '2026-08-22 02:06:20', '2026-08-22 02:06:20'),
(245, 74, NULL, 'pending', 6, 'Task created.', '2026-08-22 07:45:38', '2026-08-22 02:15:38', '2026-08-22 02:15:38'),
(246, 75, NULL, 'pending', 6, 'Task created.', '2026-08-22 07:52:13', '2026-08-22 02:22:13', '2026-08-22 02:22:13'),
(247, 67, 'pending', 'in_progress', 9, NULL, '2026-08-22 08:21:39', '2026-08-22 02:51:39', '2026-08-22 02:51:39'),
(248, 74, 'pending', 'in_progress', 10, NULL, '2026-08-22 10:33:16', '2026-08-22 05:03:16', '2026-08-22 05:03:16'),
(249, 74, 'in_progress', 'pending_review', 10, NULL, '2026-08-22 10:33:20', '2026-08-22 05:03:20', '2026-08-22 05:03:20'),
(250, 67, 'in_progress', 'pending_review', 9, NULL, '2026-08-22 10:46:01', '2026-08-22 05:16:01', '2026-08-22 05:16:01'),
(251, 72, 'pending', 'in_progress', 9, NULL, '2026-08-22 10:46:09', '2026-08-22 05:16:09', '2026-08-22 05:16:09'),
(252, 72, 'in_progress', 'pending_review', 9, NULL, '2026-08-22 10:46:11', '2026-08-22 05:16:11', '2026-08-22 05:16:11'),
(253, 71, 'pending', 'in_progress', 9, NULL, '2026-08-22 12:30:26', '2026-08-22 07:00:26', '2026-08-22 07:00:26'),
(254, 63, 'pending', 'in_progress', 9, NULL, '2026-08-22 12:30:28', '2026-08-22 07:00:28', '2026-08-22 07:00:28'),
(255, 63, 'in_progress', 'pending_review', 9, NULL, '2026-08-22 12:33:28', '2026-08-22 07:03:28', '2026-08-22 07:03:28'),
(256, 73, 'pending', 'in_progress', 10, NULL, '2026-08-22 13:29:53', '2026-08-22 07:59:53', '2026-08-22 07:59:53'),
(257, 73, 'in_progress', 'pending_review', 10, NULL, '2026-08-22 13:30:05', '2026-08-22 08:00:05', '2026-08-22 08:00:05'),
(258, 39, 'pending', 'in_progress', 10, NULL, '2026-08-22 13:30:40', '2026-08-22 08:00:40', '2026-08-22 08:00:40'),
(259, 39, 'in_progress', 'pending_review', 10, NULL, '2026-08-22 13:30:48', '2026-08-22 08:00:48', '2026-08-22 08:00:48'),
(260, 65, 'pending', 'in_progress', 10, NULL, '2026-08-22 13:37:10', '2026-08-22 08:07:10', '2026-08-22 08:07:10'),
(261, 65, 'in_progress', 'pending_review', 10, NULL, '2026-08-22 13:37:19', '2026-08-22 08:07:19', '2026-08-22 08:07:19'),
(262, 40, 'pending', 'pending', NULL, 'Priority auto-escalated from low to high — was still pending as of yesterday.', '2026-08-24 04:52:23', '2026-08-23 23:22:23', '2026-08-23 23:22:23'),
(263, 41, 'pending', 'pending', NULL, 'Priority auto-escalated from medium to high — was still pending as of yesterday.', '2026-08-24 04:52:23', '2026-08-23 23:22:23', '2026-08-23 23:22:23'),
(264, 60, 'pending', 'pending', NULL, 'Priority auto-escalated from medium to high — was still pending as of yesterday.', '2026-08-24 04:52:23', '2026-08-23 23:22:23', '2026-08-23 23:22:23'),
(265, 67, 'pending_review', 'completed', 6, NULL, '2026-08-24 04:52:41', '2026-08-23 23:22:41', '2026-08-23 23:22:41'),
(266, 69, 'pending_review', 'completed', 6, NULL, '2026-08-24 04:52:47', '2026-08-23 23:22:47', '2026-08-23 23:22:47'),
(267, 70, 'pending_review', 'completed', 6, NULL, '2026-08-24 04:52:50', '2026-08-23 23:22:50', '2026-08-23 23:22:50'),
(268, 72, 'pending_review', 'completed', 6, NULL, '2026-08-24 04:52:58', '2026-08-23 23:22:58', '2026-08-23 23:22:58'),
(269, 65, 'pending_review', 'completed', 6, NULL, '2026-08-24 04:53:10', '2026-08-23 23:23:10', '2026-08-23 23:23:10'),
(270, 61, 'pending_review', 'completed', 6, NULL, '2026-08-24 04:53:51', '2026-08-23 23:23:51', '2026-08-23 23:23:51'),
(271, 60, 'pending', 'in_progress', 6, NULL, '2026-08-24 04:54:00', '2026-08-23 23:24:00', '2026-08-23 23:24:00'),
(272, 60, 'in_progress', 'pending_review', 6, NULL, '2026-08-24 04:54:05', '2026-08-23 23:24:05', '2026-08-23 23:24:05'),
(273, 39, 'pending_review', 'completed', 6, NULL, '2026-08-24 04:54:39', '2026-08-23 23:24:39', '2026-08-23 23:24:39'),
(274, 54, 'pending', 'in_progress', 6, NULL, '2026-08-24 04:55:25', '2026-08-23 23:25:25', '2026-08-23 23:25:25'),
(275, 54, 'in_progress', 'pending_review', 6, NULL, '2026-08-24 04:55:30', '2026-08-23 23:25:30', '2026-08-23 23:25:30'),
(276, 54, 'pending_review', 'completed', 6, NULL, '2026-08-24 04:55:33', '2026-08-23 23:25:33', '2026-08-23 23:25:33'),
(277, 76, NULL, 'pending', 6, 'Task created.', '2026-08-24 05:03:36', '2026-08-23 23:33:36', '2026-08-23 23:33:36'),
(278, 77, NULL, 'pending', 6, 'Task created.', '2026-08-24 05:10:24', '2026-08-23 23:40:24', '2026-08-23 23:40:24'),
(279, 78, NULL, 'pending', 6, 'Task created.', '2026-08-24 05:11:44', '2026-08-23 23:41:44', '2026-08-23 23:41:44'),
(280, 79, NULL, 'pending', 6, 'Task created.', '2026-08-24 05:12:38', '2026-08-23 23:42:38', '2026-08-23 23:42:38'),
(281, 78, 'pending', 'in_progress', 10, NULL, '2026-08-24 05:18:33', '2026-08-23 23:48:33', '2026-08-23 23:48:33'),
(282, 80, NULL, 'pending', 6, 'Task created.', '2026-08-24 05:30:51', '2026-08-24 00:00:51', '2026-08-24 00:00:51'),
(283, 81, NULL, 'pending', 6, 'Task created.', '2026-08-24 05:32:09', '2026-08-24 00:02:09', '2026-08-24 00:02:09'),
(284, 82, NULL, 'pending', 6, 'Task created.', '2026-08-24 05:32:27', '2026-08-24 00:02:27', '2026-08-24 00:02:27'),
(285, 83, NULL, 'pending', 6, 'Task created.', '2026-08-24 05:35:32', '2026-08-24 00:05:32', '2026-08-24 00:05:32'),
(286, 84, NULL, 'pending', 6, 'Task created.', '2026-08-24 05:37:21', '2026-08-24 00:07:21', '2026-08-24 00:07:21'),
(287, 85, NULL, 'pending', 6, 'Task created.', '2026-08-24 05:38:38', '2026-08-24 00:08:38', '2026-08-24 00:08:38'),
(288, 78, 'in_progress', 'pending_review', 10, NULL, '2026-08-24 06:29:35', '2026-08-24 00:59:35', '2026-08-24 00:59:35'),
(289, 86, NULL, 'pending', 6, 'Task created.', '2026-08-24 06:32:32', '2026-08-24 01:02:32', '2026-08-24 01:02:32'),
(290, 44, 'pending_review', 'in_progress', 6, 'Needs Changes', '2026-08-24 06:51:57', '2026-08-24 01:21:57', '2026-08-24 01:21:57'),
(291, 71, 'in_progress', 'pending_review', 9, NULL, '2026-08-24 07:50:34', '2026-08-24 02:20:34', '2026-08-24 02:20:34'),
(292, 75, 'pending', 'in_progress', 9, NULL, '2026-08-24 07:50:37', '2026-08-24 02:20:37', '2026-08-24 02:20:37'),
(293, 85, 'pending', 'in_progress', 9, NULL, '2026-08-24 07:50:48', '2026-08-24 02:20:48', '2026-08-24 02:20:48'),
(294, 75, 'in_progress', 'pending_review', 9, NULL, '2026-08-24 07:50:52', '2026-08-24 02:20:52', '2026-08-24 02:20:52'),
(295, 87, NULL, 'pending', 6, 'Task created.', '2026-08-24 07:52:38', '2026-08-24 02:22:38', '2026-08-24 02:22:38'),
(296, 76, 'pending', 'in_progress', 7, NULL, '2026-08-24 08:10:16', '2026-08-24 02:40:16', '2026-08-24 02:40:16'),
(297, 76, 'in_progress', 'pending_review', 7, NULL, '2026-08-24 08:10:21', '2026-08-24 02:40:21', '2026-08-24 02:40:21'),
(298, 77, 'pending', 'in_progress', 10, NULL, '2026-08-24 09:03:32', '2026-08-24 03:33:32', '2026-08-24 03:33:32'),
(299, 77, 'in_progress', 'pending_review', 10, NULL, '2026-08-24 09:03:36', '2026-08-24 03:33:36', '2026-08-24 03:33:36'),
(300, 82, 'pending', 'in_progress', 10, NULL, '2026-08-24 10:17:23', '2026-08-24 04:47:23', '2026-08-24 04:47:23'),
(301, 82, 'in_progress', 'pending_review', 10, NULL, '2026-08-24 10:17:27', '2026-08-24 04:47:27', '2026-08-24 04:47:27'),
(302, 88, NULL, 'pending', 6, 'Task created.', '2026-08-24 10:19:16', '2026-08-24 04:49:16', '2026-08-24 04:49:16'),
(303, 87, 'pending', 'in_progress', 9, NULL, '2026-08-24 11:21:57', '2026-08-24 05:51:57', '2026-08-24 05:51:57'),
(304, 81, 'pending', 'in_progress', 10, NULL, '2026-08-24 11:22:16', '2026-08-24 05:52:16', '2026-08-24 05:52:16'),
(305, 81, 'in_progress', 'pending_review', 10, NULL, '2026-08-24 11:22:20', '2026-08-24 05:52:20', '2026-08-24 05:52:20'),
(306, 87, 'in_progress', 'pending_review', 9, NULL, '2026-08-24 12:33:09', '2026-08-24 07:03:09', '2026-08-24 07:03:09'),
(307, 88, 'pending', 'in_progress', 9, NULL, '2026-08-24 12:33:13', '2026-08-24 07:03:13', '2026-08-24 07:03:13'),
(308, 82, 'pending_review', 'completed', 6, NULL, '2026-08-24 12:41:13', '2026-08-24 07:11:13', '2026-08-24 07:11:13'),
(309, 78, 'pending_review', 'completed', 6, NULL, '2026-08-24 12:41:20', '2026-08-24 07:11:20', '2026-08-24 07:11:20'),
(310, 77, 'pending_review', 'completed', 6, NULL, '2026-08-24 12:41:24', '2026-08-24 07:11:24', '2026-08-24 07:11:24'),
(311, 50, 'pending_review', 'completed', 6, NULL, '2026-08-24 12:43:43', '2026-08-24 07:13:43', '2026-08-24 07:13:43'),
(312, 68, 'pending_review', 'completed', 6, NULL, '2026-08-24 12:44:02', '2026-08-24 07:14:02', '2026-08-24 07:14:02'),
(313, 75, 'pending_review', 'completed', 6, NULL, '2026-08-24 12:44:13', '2026-08-24 07:14:13', '2026-08-24 07:14:13'),
(314, 58, 'pending_review', 'completed', 6, NULL, '2026-08-24 12:44:30', '2026-08-24 07:14:30', '2026-08-24 07:14:30'),
(315, 87, 'pending_review', 'completed', 6, NULL, '2026-08-24 12:53:06', '2026-08-24 07:23:06', '2026-08-24 07:23:06'),
(316, 80, 'pending', 'in_progress', 10, NULL, '2026-08-25 04:01:17', '2026-08-24 22:31:17', '2026-08-24 22:31:17'),
(317, 80, 'in_progress', 'pending_review', 10, NULL, '2026-08-25 04:01:21', '2026-08-24 22:31:21', '2026-08-24 22:31:21'),
(318, 89, NULL, 'pending', 6, 'Task created.', '2026-08-25 05:14:27', '2026-08-24 23:44:27', '2026-08-24 23:44:27'),
(319, 90, NULL, 'pending', 6, 'Task created.', '2026-08-25 05:20:51', '2026-08-24 23:50:51', '2026-08-24 23:50:51'),
(320, 80, 'pending_review', 'completed', 6, NULL, '2026-08-25 05:21:14', '2026-08-24 23:51:14', '2026-08-24 23:51:14'),
(321, 91, NULL, 'pending', 6, 'Task created.', '2026-08-25 05:26:31', '2026-08-24 23:56:31', '2026-08-24 23:56:31'),
(322, 92, NULL, 'pending', 6, 'Task created.', '2026-08-25 05:37:17', '2026-08-25 00:07:17', '2026-08-25 00:07:17'),
(323, 93, NULL, 'pending', 6, 'Task created.', '2026-08-25 05:40:41', '2026-08-25 00:10:41', '2026-08-25 00:10:41'),
(324, 94, NULL, 'pending', 6, 'Task created.', '2026-08-25 05:41:06', '2026-08-25 00:11:06', '2026-08-25 00:11:06'),
(325, 88, 'in_progress', 'pending_review', 9, NULL, '2026-08-25 05:46:43', '2026-08-25 00:16:43', '2026-08-25 00:16:43'),
(326, 89, 'pending', 'in_progress', 9, NULL, '2026-08-25 05:46:45', '2026-08-25 00:16:45', '2026-08-25 00:16:45'),
(327, 95, NULL, 'pending', 6, 'Task created.', '2026-08-25 05:59:21', '2026-08-25 00:29:21', '2026-08-25 00:29:21'),
(328, 96, NULL, 'pending', 6, 'Task created.', '2026-08-25 06:00:48', '2026-08-25 00:30:48', '2026-08-25 00:30:48'),
(329, 97, NULL, 'pending', 6, 'Task created.', '2026-08-25 06:55:13', '2026-08-25 01:25:13', '2026-08-25 01:25:13'),
(330, 98, NULL, 'pending', 6, 'Task created.', '2026-08-25 06:55:37', '2026-08-25 01:25:37', '2026-08-25 01:25:37'),
(331, 99, NULL, 'pending', 6, 'Task created.', '2026-08-25 06:56:00', '2026-08-25 01:26:00', '2026-08-25 01:26:00'),
(332, 100, NULL, 'pending', 6, 'Task created.', '2026-08-25 06:56:48', '2026-08-25 01:26:48', '2026-08-25 01:26:48'),
(333, 101, NULL, 'pending', 6, 'Task created.', '2026-08-25 06:58:04', '2026-08-25 01:28:04', '2026-08-25 01:28:04'),
(334, 102, NULL, 'pending', 6, 'Task created.', '2026-08-25 06:59:09', '2026-08-25 01:29:09', '2026-08-25 01:29:09'),
(335, 79, 'pending', 'in_progress', 8, NULL, '2026-08-25 07:00:59', '2026-08-25 01:30:59', '2026-08-25 01:30:59'),
(336, 95, 'pending', 'in_progress', 9, NULL, '2026-08-25 07:27:08', '2026-08-25 01:57:08', '2026-08-25 01:57:08'),
(337, 95, 'in_progress', 'pending_review', 9, NULL, '2026-08-25 07:27:12', '2026-08-25 01:57:12', '2026-08-25 01:57:12'),
(338, 96, 'pending', 'in_progress', 9, NULL, '2026-08-25 07:27:23', '2026-08-25 01:57:23', '2026-08-25 01:57:23'),
(339, 79, 'in_progress', 'pending_review', 8, NULL, '2026-08-25 07:38:14', '2026-08-25 02:08:14', '2026-08-25 02:08:14'),
(340, 44, 'in_progress', 'pending_review', 8, NULL, '2026-08-25 07:38:34', '2026-08-25 02:08:34', '2026-08-25 02:08:34'),
(341, 96, 'in_progress', 'pending_review', 9, NULL, '2026-08-25 08:20:55', '2026-08-25 02:50:55', '2026-08-25 02:50:55'),
(342, 51, 'pending_review', 'completed', 6, NULL, '2026-08-25 09:13:15', '2026-08-25 03:43:15', '2026-08-25 03:43:15'),
(343, 84, 'pending', 'in_progress', 8, NULL, '2026-08-25 09:22:49', '2026-08-25 03:52:49', '2026-08-25 03:52:49'),
(344, 84, 'in_progress', 'pending_review', 8, NULL, '2026-08-25 09:22:52', '2026-08-25 03:52:52', '2026-08-25 03:52:52'),
(345, 88, 'pending_review', 'completed', 6, NULL, '2026-08-25 09:26:49', '2026-08-25 03:56:49', '2026-08-25 03:56:49'),
(346, 86, 'pending', 'in_progress', 8, NULL, '2026-08-25 09:56:00', '2026-08-25 04:26:00', '2026-08-25 04:26:00'),
(347, 96, 'pending_review', 'completed', 6, NULL, '2026-08-25 10:03:48', '2026-08-25 04:33:48', '2026-08-25 04:33:48'),
(348, 94, 'pending', 'in_progress', 10, NULL, '2026-08-25 10:04:59', '2026-08-25 04:34:59', '2026-08-25 04:34:59'),
(349, 94, 'in_progress', 'pending_review', 10, NULL, '2026-08-25 10:05:07', '2026-08-25 04:35:07', '2026-08-25 04:35:07'),
(350, 90, 'pending', 'in_progress', 10, NULL, '2026-08-25 10:06:31', '2026-08-25 04:36:31', '2026-08-25 04:36:31'),
(351, 90, 'in_progress', 'pending_review', 10, NULL, '2026-08-25 10:06:34', '2026-08-25 04:36:34', '2026-08-25 04:36:34'),
(352, 103, NULL, 'pending', 6, 'Task created.', '2026-08-25 10:21:56', '2026-08-25 04:51:56', '2026-08-25 04:51:56'),
(353, 85, 'in_progress', 'pending_review', 9, NULL, '2026-08-25 10:26:38', '2026-08-25 04:56:38', '2026-08-25 04:56:38'),
(354, 86, 'in_progress', 'pending_review', 8, NULL, '2026-08-25 10:29:54', '2026-08-25 04:59:54', '2026-08-25 04:59:54'),
(355, 103, 'pending', 'in_progress', 9, NULL, '2026-08-25 10:35:51', '2026-08-25 05:05:51', '2026-08-25 05:05:51'),
(356, 93, 'pending', 'in_progress', 10, NULL, '2026-08-25 10:53:44', '2026-08-25 05:23:44', '2026-08-25 05:23:44'),
(357, 93, 'in_progress', 'pending_review', 10, NULL, '2026-08-25 10:53:48', '2026-08-25 05:23:48', '2026-08-25 05:23:48'),
(358, 103, 'in_progress', 'pending_review', 9, NULL, '2026-08-25 11:34:41', '2026-08-25 06:04:41', '2026-08-25 06:04:41'),
(359, 83, 'pending', 'in_progress', 9, NULL, '2026-08-25 11:34:44', '2026-08-25 06:04:44', '2026-08-25 06:04:44'),
(360, 97, 'pending', 'in_progress', 8, NULL, '2026-08-25 11:45:08', '2026-08-25 06:15:08', '2026-08-25 06:15:08'),
(361, 97, 'in_progress', 'pending_review', 8, NULL, '2026-08-25 12:04:23', '2026-08-25 06:34:23', '2026-08-25 06:34:23'),
(362, 104, NULL, 'pending', 6, 'Task created.', '2026-08-25 13:15:43', '2026-08-25 07:45:43', '2026-08-25 07:45:43'),
(363, 97, 'pending_review', 'completed', 6, NULL, '2026-08-25 13:16:53', '2026-08-25 07:46:53', '2026-08-25 07:46:53'),
(364, 105, NULL, 'pending', 6, 'Task created.', '2026-08-25 13:17:44', '2026-08-25 07:47:44', '2026-08-25 07:47:44'),
(365, 106, NULL, 'pending', 6, 'Task created.', '2026-08-25 13:19:20', '2026-08-25 07:49:20', '2026-08-25 07:49:20'),
(366, 107, NULL, 'pending', 6, 'Task created.', '2026-08-25 13:19:53', '2026-08-25 07:49:53', '2026-08-25 07:49:53'),
(367, 108, NULL, 'pending', 6, 'Task created.', '2026-08-25 13:20:23', '2026-08-25 07:50:23', '2026-08-25 07:50:23'),
(368, 109, NULL, 'pending', 6, 'Task created.', '2026-08-25 13:27:00', '2026-08-25 07:57:00', '2026-08-25 07:57:00'),
(369, 110, NULL, 'pending', 6, 'Task created.', '2026-08-25 13:27:26', '2026-08-25 07:57:26', '2026-08-25 07:57:26'),
(370, 95, 'pending_review', 'completed', 6, NULL, '2026-08-25 13:29:38', '2026-08-25 07:59:38', '2026-08-25 07:59:38'),
(371, 103, 'pending_review', 'in_progress', 6, 'Needs Changes', '2026-08-25 13:29:46', '2026-08-25 07:59:46', '2026-08-25 07:59:46'),
(372, 92, 'pending', 'in_progress', 10, NULL, '2026-08-26 04:09:54', '2026-08-25 22:39:54', '2026-08-25 22:39:54'),
(373, 92, 'in_progress', 'pending_review', 10, NULL, '2026-08-26 04:09:58', '2026-08-25 22:39:58', '2026-08-25 22:39:58'),
(374, 93, 'pending_review', 'completed', 6, NULL, '2026-08-26 06:33:39', '2026-08-26 01:03:39', '2026-08-26 01:03:39'),
(375, 74, 'pending_review', 'completed', 6, NULL, '2026-08-26 06:33:46', '2026-08-26 01:03:46', '2026-08-26 01:03:46'),
(376, 73, 'pending_review', 'completed', 6, NULL, '2026-08-26 06:34:02', '2026-08-26 01:04:02', '2026-08-26 01:04:02'),
(377, 81, 'pending_review', 'completed', 6, NULL, '2026-08-26 06:34:09', '2026-08-26 01:04:09', '2026-08-26 01:04:09'),
(378, 90, 'pending_review', 'completed', 6, NULL, '2026-08-26 06:36:17', '2026-08-26 01:06:17', '2026-08-26 01:06:17'),
(379, 111, NULL, 'pending', 6, 'Task created.', '2026-08-26 06:45:37', '2026-08-26 01:15:37', '2026-08-26 01:15:37'),
(380, 83, 'in_progress', 'pending_review', 9, NULL, '2026-08-26 06:52:08', '2026-08-26 01:22:08', '2026-08-26 01:22:08'),
(381, 104, 'pending', 'in_progress', 9, NULL, '2026-08-26 06:52:12', '2026-08-26 01:22:12', '2026-08-26 01:22:12'),
(382, 109, 'pending', 'in_progress', 8, NULL, '2026-08-26 06:59:20', '2026-08-26 01:29:20', '2026-08-26 01:29:20'),
(383, 109, 'in_progress', 'pending_review', 8, NULL, '2026-08-26 06:59:25', '2026-08-26 01:29:25', '2026-08-26 01:29:25'),
(384, 102, 'pending', 'in_progress', 8, NULL, '2026-08-26 07:04:40', '2026-08-26 01:34:40', '2026-08-26 01:34:40'),
(385, 102, 'in_progress', 'pending_review', 8, NULL, '2026-08-26 07:05:47', '2026-08-26 01:35:47', '2026-08-26 01:35:47'),
(386, 104, 'in_progress', 'pending_review', 9, NULL, '2026-08-26 07:40:04', '2026-08-26 02:10:04', '2026-08-26 02:10:04'),
(387, 111, 'pending', 'in_progress', 9, NULL, '2026-08-26 07:40:07', '2026-08-26 02:10:07', '2026-08-26 02:10:07'),
(388, 100, 'pending', 'in_progress', 8, NULL, '2026-08-26 07:47:17', '2026-08-26 02:17:17', '2026-08-26 02:17:17'),
(389, 100, 'in_progress', 'pending_review', 8, NULL, '2026-08-26 08:21:45', '2026-08-26 02:51:45', '2026-08-26 02:51:45'),
(390, 101, 'pending', 'in_progress', 8, NULL, '2026-08-26 08:22:18', '2026-08-26 02:52:18', '2026-08-26 02:52:18'),
(391, 101, 'in_progress', 'pending_review', 8, NULL, '2026-08-26 08:54:41', '2026-08-26 03:24:41', '2026-08-26 03:24:41'),
(392, 105, 'pending', 'in_progress', 8, NULL, '2026-08-26 09:11:13', '2026-08-26 03:41:13', '2026-08-26 03:41:13'),
(393, 105, 'in_progress', 'pending_review', 8, NULL, '2026-08-26 09:11:18', '2026-08-26 03:41:18', '2026-08-26 03:41:18'),
(394, 111, 'in_progress', 'pending_review', 9, NULL, '2026-08-26 09:33:47', '2026-08-26 04:03:47', '2026-08-26 04:03:47'),
(395, 112, NULL, 'pending', 6, 'Task created.', '2026-08-26 11:34:31', '2026-08-26 06:04:31', '2026-08-26 06:04:31'),
(396, 113, NULL, 'pending', 6, 'Task created.', '2026-08-26 11:35:24', '2026-08-26 06:05:24', '2026-08-26 06:05:24'),
(397, 114, NULL, 'pending', 6, 'Task created.', '2026-08-26 12:30:02', '2026-08-26 07:00:02', '2026-08-26 07:00:02'),
(398, 114, 'pending', 'in_progress', 9, NULL, '2026-08-26 13:15:31', '2026-08-26 07:45:31', '2026-08-26 07:45:31'),
(399, 114, 'in_progress', 'pending_review', 9, NULL, '2026-08-26 13:15:36', '2026-08-26 07:45:36', '2026-08-26 07:45:36'),
(400, 115, NULL, 'pending', 6, 'Task created.', '2026-08-26 13:21:09', '2026-08-26 07:51:09', '2026-08-26 07:51:09'),
(401, 116, NULL, 'pending', 6, 'Task created.', '2026-08-26 13:21:24', '2026-08-26 07:51:24', '2026-08-26 07:51:24'),
(402, 117, NULL, 'pending', 6, 'Task created.', '2026-08-26 13:22:06', '2026-08-26 07:52:06', '2026-08-26 07:52:06'),
(403, 113, 'pending', 'in_progress', 8, NULL, '2026-08-27 05:31:46', '2026-08-27 00:01:46', '2026-08-27 00:01:46'),
(404, 113, 'in_progress', 'pending_review', 8, NULL, '2026-08-27 05:31:51', '2026-08-27 00:01:51', '2026-08-27 00:01:51'),
(405, 112, 'pending', 'in_progress', 8, NULL, '2026-08-27 07:21:08', '2026-08-27 01:51:08', '2026-08-27 01:51:08'),
(406, 112, 'in_progress', 'pending_review', 8, NULL, '2026-08-27 07:21:12', '2026-08-27 01:51:12', '2026-08-27 01:51:12'),
(407, 107, 'pending', 'in_progress', 8, NULL, '2026-08-27 09:31:48', '2026-08-27 04:01:48', '2026-08-27 04:01:48'),
(408, 107, 'in_progress', 'pending_review', 8, NULL, '2026-08-27 09:32:03', '2026-08-27 04:02:03', '2026-08-27 04:02:03'),
(409, 103, 'in_progress', 'pending_review', 9, NULL, '2026-08-27 11:05:20', '2026-08-27 05:35:20', '2026-08-27 05:35:20'),
(410, 98, 'pending', 'in_progress', 8, NULL, '2026-08-27 11:25:40', '2026-08-27 05:55:40', '2026-08-27 05:55:40'),
(411, 98, 'in_progress', 'pending_review', 8, NULL, '2026-08-27 11:25:45', '2026-08-27 05:55:45', '2026-08-27 05:55:45'),
(412, 99, 'pending', 'in_progress', 8, NULL, '2026-08-27 11:25:50', '2026-08-27 05:55:50', '2026-08-27 05:55:50'),
(413, 99, 'in_progress', 'pending_review', 8, NULL, '2026-08-27 11:25:55', '2026-08-27 05:55:55', '2026-08-27 05:55:55'),
(414, 110, 'pending', 'pending', NULL, 'Priority auto-escalated from medium to high — was still pending as of yesterday.', '2026-08-29 10:09:45', '2026-08-29 04:39:45', '2026-08-29 04:39:45'),
(415, 110, 'pending', 'in_progress', 8, NULL, '2026-08-29 10:09:51', '2026-08-29 04:39:51', '2026-08-29 04:39:51'),
(416, 110, 'in_progress', 'pending_review', 8, NULL, '2026-08-29 10:10:00', '2026-08-29 04:40:00', '2026-08-29 04:40:00'),
(417, 91, 'pending', 'pending', NULL, 'Priority auto-escalated from medium to high — was still pending as of yesterday.', '2026-08-31 05:30:17', '2026-08-31 00:00:17', '2026-08-31 00:00:17'),
(418, 108, 'pending', 'pending', NULL, 'Priority auto-escalated from medium to high — was still pending as of yesterday.', '2026-08-31 05:30:17', '2026-08-31 00:00:17', '2026-08-31 00:00:17'),
(419, 115, 'pending', 'pending', NULL, 'Priority auto-escalated from medium to high — was still pending as of yesterday.', '2026-08-31 05:30:17', '2026-08-31 00:00:17', '2026-08-31 00:00:17'),
(420, 116, 'pending', 'pending', NULL, 'Priority auto-escalated from medium to high — was still pending as of yesterday.', '2026-08-31 05:30:17', '2026-08-31 00:00:17', '2026-08-31 00:00:17'),
(421, 117, 'pending', 'pending', NULL, 'Priority auto-escalated from medium to high — was still pending as of yesterday.', '2026-08-31 05:30:17', '2026-08-31 00:00:17', '2026-08-31 00:00:17'),
(422, 118, NULL, 'pending', 5, 'Task created.', '2026-09-01 05:27:22', '2026-08-31 23:57:22', '2026-08-31 23:57:22'),
(423, 119, NULL, 'pending', 5, 'Task created.', '2026-09-01 05:33:25', '2026-09-01 00:03:25', '2026-09-01 00:03:25'),
(424, 120, NULL, 'pending', 5, 'Task created.', '2026-09-01 05:36:20', '2026-09-01 00:06:20', '2026-09-01 00:06:20'),
(425, 121, NULL, 'pending', 6, 'Task created.', '2026-09-01 06:29:39', '2026-09-01 00:59:39', '2026-09-01 00:59:39'),
(426, 122, NULL, 'pending', 6, 'Task created.', '2026-09-01 06:32:17', '2026-09-01 01:02:17', '2026-09-01 01:02:17'),
(427, 123, NULL, 'pending', 6, 'Task created.', '2026-09-01 06:37:56', '2026-09-01 01:07:56', '2026-09-01 01:07:56'),
(428, 124, NULL, 'pending', 6, 'Task created.', '2026-09-01 06:39:42', '2026-09-01 01:09:42', '2026-09-01 01:09:42'),
(429, 125, NULL, 'pending', 6, 'Task created.', '2026-09-01 06:44:57', '2026-09-01 01:14:57', '2026-09-01 01:14:57'),
(430, 121, 'pending', 'in_progress', 8, NULL, '2026-09-01 06:48:04', '2026-09-01 01:18:04', '2026-09-01 01:18:04'),
(431, 126, NULL, 'pending', 6, 'Task created.', '2026-09-01 06:52:35', '2026-09-01 01:22:35', '2026-09-01 01:22:35'),
(432, 127, NULL, 'pending', 6, 'Task created.', '2026-09-01 06:54:03', '2026-09-01 01:24:03', '2026-09-01 01:24:03'),
(433, 46, 'pending_review', 'completed', 6, NULL, '2026-09-01 06:54:35', '2026-09-01 01:24:35', '2026-09-01 01:24:35'),
(434, 121, 'in_progress', 'pending_review', 8, NULL, '2026-09-01 07:16:17', '2026-09-01 01:46:17', '2026-09-01 01:46:17'),
(435, 122, 'pending', 'in_progress', 8, NULL, '2026-09-01 07:20:27', '2026-09-01 01:50:27', '2026-09-01 01:50:27'),
(436, 122, 'in_progress', 'pending_review', 8, NULL, '2026-09-01 07:44:24', '2026-09-01 02:14:24', '2026-09-01 02:14:24'),
(437, 124, 'pending', 'in_progress', 8, NULL, '2026-09-01 07:47:27', '2026-09-01 02:17:27', '2026-09-01 02:17:27'),
(438, 124, 'in_progress', 'pending_review', 8, NULL, '2026-09-01 08:23:07', '2026-09-01 02:53:07', '2026-09-01 02:53:07'),
(439, 128, NULL, 'pending', 6, 'Task created.', '2026-09-01 09:20:38', '2026-09-01 03:50:38', '2026-09-01 03:50:38'),
(440, 123, 'pending', 'in_progress', 8, NULL, '2026-09-01 09:41:49', '2026-09-01 04:11:49', '2026-09-01 04:11:49'),
(441, 123, 'in_progress', 'pending_review', 8, NULL, '2026-09-01 10:26:14', '2026-09-01 04:56:14', '2026-09-01 04:56:14'),
(442, 129, NULL, 'pending', 6, 'Task created.', '2026-09-01 10:58:44', '2026-09-01 05:28:44', '2026-09-01 05:28:44'),
(443, 120, 'pending', 'in_progress', 9, NULL, '2026-09-01 12:57:05', '2026-09-01 07:27:05', '2026-09-01 07:27:05'),
(444, 120, 'in_progress', 'pending_review', 9, NULL, '2026-09-01 12:57:07', '2026-09-01 07:27:07', '2026-09-01 07:27:07'),
(445, 130, NULL, 'pending', 6, 'Task created.', '2026-09-01 13:21:21', '2026-09-01 07:51:21', '2026-09-01 07:51:21'),
(446, 118, 'pending', 'pending', NULL, 'Priority auto-escalated from medium to high — was still pending as of yesterday.', '2026-09-02 05:09:25', '2026-09-01 23:39:25', '2026-09-01 23:39:25'),
(447, 119, 'pending', 'pending', NULL, 'Priority auto-escalated from medium to high — was still pending as of yesterday.', '2026-09-02 05:09:25', '2026-09-01 23:39:25', '2026-09-01 23:39:25'),
(448, 128, 'pending', 'in_progress', 10, NULL, '2026-09-02 05:41:20', '2026-09-02 00:11:20', '2026-09-02 00:11:20'),
(449, 128, 'in_progress', 'pending_review', 10, NULL, '2026-09-02 05:41:28', '2026-09-02 00:11:28', '2026-09-02 00:11:28'),
(450, 43, 'pending_review', 'completed', 5, NULL, '2026-09-02 06:51:04', '2026-09-02 01:21:04', '2026-09-02 01:21:04'),
(451, 44, 'pending_review', 'completed', 5, NULL, '2026-09-02 07:01:36', '2026-09-02 01:31:36', '2026-09-02 01:31:36'),
(452, 89, 'in_progress', 'pending_review', 9, NULL, '2026-09-02 07:39:46', '2026-09-02 02:09:46', '2026-09-02 02:09:46'),
(453, 131, NULL, 'pending', 5, 'Task created.', '2026-09-02 11:03:19', '2026-09-02 05:33:19', '2026-09-02 05:33:19'),
(454, 132, NULL, 'pending', 5, 'Task created.', '2026-09-02 11:26:37', '2026-09-02 05:56:37', '2026-09-02 05:56:37'),
(455, 132, 'pending', 'in_progress', 10, NULL, '2026-09-02 12:47:58', '2026-09-02 07:17:58', '2026-09-02 07:17:58'),
(456, 132, 'in_progress', 'pending_review', 10, NULL, '2026-09-02 12:48:03', '2026-09-02 07:18:03', '2026-09-02 07:18:03'),
(457, 131, 'pending', 'in_progress', 10, NULL, '2026-09-02 12:48:22', '2026-09-02 07:18:22', '2026-09-02 07:18:22'),
(458, 56, 'pending_review', 'completed', 5, NULL, '2026-09-03 05:12:35', '2026-09-02 23:42:35', '2026-09-02 23:42:35'),
(459, 63, 'pending_review', 'completed', 5, NULL, '2026-09-03 05:12:52', '2026-09-02 23:42:52', '2026-09-02 23:42:52'),
(460, 71, 'pending_review', 'completed', 5, NULL, '2026-09-03 05:16:05', '2026-09-02 23:46:05', '2026-09-02 23:46:05'),
(461, 133, NULL, 'pending', 6, 'Task created.', '2026-09-03 05:20:43', '2026-09-02 23:50:43', '2026-09-02 23:50:43'),
(462, 134, NULL, 'pending', 6, 'Task created.', '2026-09-03 05:21:51', '2026-09-02 23:51:51', '2026-09-02 23:51:51'),
(463, 135, NULL, 'pending', 6, 'Task created.', '2026-09-03 05:26:36', '2026-09-02 23:56:36', '2026-09-02 23:56:36'),
(464, 136, NULL, 'pending', 6, 'Task created.', '2026-09-03 05:27:13', '2026-09-02 23:57:13', '2026-09-02 23:57:13'),
(465, 137, NULL, 'pending', 6, 'Task created.', '2026-09-03 05:30:14', '2026-09-03 00:00:14', '2026-09-03 00:00:14'),
(466, 79, 'pending_review', 'completed', 6, NULL, '2026-09-03 05:30:40', '2026-09-03 00:00:40', '2026-09-03 00:00:40'),
(467, 108, 'pending', 'in_progress', 6, NULL, '2026-09-03 05:31:44', '2026-09-03 00:01:44', '2026-09-03 00:01:44'),
(468, 108, 'in_progress', 'pending_review', 6, NULL, '2026-09-03 05:31:46', '2026-09-03 00:01:46', '2026-09-03 00:01:46'),
(469, 115, 'pending', 'in_progress', 6, NULL, '2026-09-03 05:55:27', '2026-09-03 00:25:27', '2026-09-03 00:25:27'),
(470, 115, 'in_progress', 'pending_review', 6, NULL, '2026-09-03 05:55:30', '2026-09-03 00:25:30', '2026-09-03 00:25:30'),
(471, 115, 'pending_review', 'completed', 6, NULL, '2026-09-03 05:55:33', '2026-09-03 00:25:33', '2026-09-03 00:25:33'),
(472, 138, NULL, 'pending', 6, 'Task created.', '2026-09-03 05:56:58', '2026-09-03 00:26:58', '2026-09-03 00:26:58'),
(473, 139, NULL, 'pending', 6, 'Task created.', '2026-09-03 06:04:43', '2026-09-03 00:34:43', '2026-09-03 00:34:43'),
(474, 140, NULL, 'pending', 6, 'Task created.', '2026-09-03 06:17:37', '2026-09-03 00:47:37', '2026-09-03 00:47:37'),
(475, 141, NULL, 'pending', 6, 'Task created.', '2026-09-03 06:18:13', '2026-09-03 00:48:13', '2026-09-03 00:48:13'),
(476, 142, NULL, 'pending', 6, 'Task created.', '2026-09-03 06:20:41', '2026-09-03 00:50:41', '2026-09-03 00:50:41'),
(477, 143, NULL, 'pending', 6, 'Task created.', '2026-09-03 06:21:35', '2026-09-03 00:51:35', '2026-09-03 00:51:35'),
(478, 144, NULL, 'pending', 6, 'Task created.', '2026-09-03 06:23:13', '2026-09-03 00:53:13', '2026-09-03 00:53:13'),
(479, 145, NULL, 'pending', 6, 'Task created.', '2026-09-03 06:38:55', '2026-09-03 01:08:55', '2026-09-03 01:08:55'),
(480, 146, NULL, 'pending', 6, 'Task created.', '2026-09-03 06:40:28', '2026-09-03 01:10:28', '2026-09-03 01:10:28'),
(481, 142, 'pending', 'in_progress', 8, NULL, '2026-09-03 08:16:16', '2026-09-03 02:46:16', '2026-09-03 02:46:16'),
(482, 142, 'in_progress', 'pending_review', 8, NULL, '2026-09-03 08:16:26', '2026-09-03 02:46:26', '2026-09-03 02:46:26'),
(483, 131, 'in_progress', 'pending_review', 10, NULL, '2026-09-03 10:37:39', '2026-09-03 05:07:39', '2026-09-03 05:07:39'),
(484, 145, 'pending', 'in_progress', 8, NULL, '2026-09-03 10:42:33', '2026-09-03 05:12:33', '2026-09-03 05:12:33'),
(485, 145, 'in_progress', 'pending_review', 8, NULL, '2026-09-03 10:42:35', '2026-09-03 05:12:35', '2026-09-03 05:12:35'),
(486, 146, 'pending', 'in_progress', 8, NULL, '2026-09-03 10:42:48', '2026-09-03 05:12:48', '2026-09-03 05:12:48'),
(487, 136, 'pending', 'in_progress', 10, NULL, '2026-09-03 11:06:23', '2026-09-03 05:36:23', '2026-09-03 05:36:23'),
(488, 136, 'in_progress', 'pending_review', 10, NULL, '2026-09-03 11:06:27', '2026-09-03 05:36:27', '2026-09-03 05:36:27'),
(489, 139, 'pending', 'in_progress', 10, NULL, '2026-09-03 11:06:45', '2026-09-03 05:36:45', '2026-09-03 05:36:45'),
(490, 139, 'in_progress', 'pending_review', 10, NULL, '2026-09-03 11:06:49', '2026-09-03 05:36:49', '2026-09-03 05:36:49'),
(491, 147, NULL, 'pending', 6, 'Task created.', '2026-09-03 11:38:46', '2026-09-03 06:08:46', '2026-09-03 06:08:46'),
(492, 83, 'pending_review', 'completed', 6, NULL, '2026-09-03 11:39:00', '2026-09-03 06:09:00', '2026-09-03 06:09:00'),
(493, 89, 'pending_review', 'completed', 6, NULL, '2026-09-03 11:39:10', '2026-09-03 06:09:10', '2026-09-03 06:09:10'),
(494, 104, 'pending_review', 'completed', 6, NULL, '2026-09-03 11:39:19', '2026-09-03 06:09:19', '2026-09-03 06:09:19'),
(495, 114, 'pending_review', 'completed', 6, NULL, '2026-09-03 11:39:31', '2026-09-03 06:09:31', '2026-09-03 06:09:31'),
(496, 120, 'pending_review', 'completed', 6, NULL, '2026-09-03 11:39:42', '2026-09-03 06:09:42', '2026-09-03 06:09:42'),
(497, 133, 'pending', 'in_progress', 9, NULL, '2026-09-03 17:36:49', '2026-09-03 12:06:49', '2026-09-03 12:06:49'),
(498, 133, 'in_progress', 'pending_review', 9, NULL, '2026-09-03 17:36:51', '2026-09-03 12:06:51', '2026-09-03 12:06:51'),
(499, 134, 'pending', 'in_progress', 9, NULL, '2026-09-03 17:36:53', '2026-09-03 12:06:53', '2026-09-03 12:06:53'),
(500, 134, 'in_progress', 'pending_review', 9, NULL, '2026-09-03 17:36:54', '2026-09-03 12:06:54', '2026-09-03 12:06:54'),
(501, 118, 'pending', 'in_progress', 9, NULL, '2026-09-03 17:37:01', '2026-09-03 12:07:01', '2026-09-03 12:07:01'),
(502, 118, 'in_progress', 'pending_review', 9, NULL, '2026-09-03 17:37:03', '2026-09-03 12:07:03', '2026-09-03 12:07:03'),
(503, 119, 'pending', 'in_progress', 9, NULL, '2026-09-03 17:37:05', '2026-09-03 12:07:05', '2026-09-03 12:07:05'),
(504, 119, 'in_progress', 'pending_review', 9, NULL, '2026-09-03 17:37:07', '2026-09-03 12:07:07', '2026-09-03 12:07:07'),
(505, 147, 'pending', 'in_progress', 9, NULL, '2026-09-03 17:37:08', '2026-09-03 12:07:08', '2026-09-03 12:07:08'),
(506, 130, 'pending', 'in_progress', 9, NULL, '2026-09-03 17:38:40', '2026-09-03 12:08:40', '2026-09-03 12:08:40'),
(507, 130, 'in_progress', 'pending_review', 9, NULL, '2026-09-03 17:38:50', '2026-09-03 12:08:50', '2026-09-03 12:08:50'),
(508, 144, 'pending', 'in_progress', 10, NULL, '2026-09-03 17:41:42', '2026-09-03 12:11:42', '2026-09-03 12:11:42'),
(509, 144, 'in_progress', 'pending_review', 10, NULL, '2026-09-03 17:41:46', '2026-09-03 12:11:46', '2026-09-03 12:11:46'),
(510, 148, NULL, 'pending', 6, 'Task created.', '2026-09-03 17:54:19', '2026-09-03 12:24:19', '2026-09-03 12:24:19'),
(511, 148, 'pending', 'in_progress', 10, NULL, '2026-09-03 18:23:19', '2026-09-03 12:53:19', '2026-09-03 12:53:19'),
(512, 148, 'in_progress', 'pending_review', 10, NULL, '2026-09-03 18:23:23', '2026-09-03 12:53:23', '2026-09-03 12:53:23'),
(513, 149, NULL, 'pending', 6, 'Task created.', '2026-09-03 18:37:18', '2026-09-03 13:07:18', '2026-09-03 13:07:18'),
(514, 92, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:40:55', '2026-09-04 05:10:55', '2026-09-04 05:10:55'),
(515, 94, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:41:00', '2026-09-04 05:11:00', '2026-09-04 05:11:00'),
(516, 108, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:41:04', '2026-09-04 05:11:04', '2026-09-04 05:11:04'),
(517, 131, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:41:11', '2026-09-04 05:11:11', '2026-09-04 05:11:11'),
(518, 136, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:41:43', '2026-09-04 05:11:43', '2026-09-04 05:11:43'),
(519, 128, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:42:03', '2026-09-04 05:12:03', '2026-09-04 05:12:03'),
(520, 148, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:42:30', '2026-09-04 05:12:30', '2026-09-04 05:12:30');
INSERT INTO `task_status_history` (`id`, `task_id`, `old_status`, `new_status`, `changed_by`, `reason`, `changed_at`, `created_at`, `updated_at`) VALUES
(521, 144, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:42:54', '2026-09-04 05:12:54', '2026-09-04 05:12:54'),
(522, 134, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:43:16', '2026-09-04 05:13:16', '2026-09-04 05:13:16'),
(523, 118, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:43:44', '2026-09-04 05:13:44', '2026-09-04 05:13:44'),
(524, 103, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:44:08', '2026-09-04 05:14:08', '2026-09-04 05:14:08'),
(525, 130, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:46:00', '2026-09-04 05:16:00', '2026-09-04 05:16:00'),
(526, 84, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:46:15', '2026-09-04 05:16:15', '2026-09-04 05:16:15'),
(527, 98, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:46:31', '2026-09-04 05:16:31', '2026-09-04 05:16:31'),
(528, 99, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:46:34', '2026-09-04 05:16:34', '2026-09-04 05:16:34'),
(529, 100, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:46:39', '2026-09-04 05:16:39', '2026-09-04 05:16:39'),
(530, 101, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:46:43', '2026-09-04 05:16:43', '2026-09-04 05:16:43'),
(531, 102, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:46:47', '2026-09-04 05:16:47', '2026-09-04 05:16:47'),
(532, 105, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:47:00', '2026-09-04 05:17:00', '2026-09-04 05:17:00'),
(533, 107, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:47:04', '2026-09-04 05:17:04', '2026-09-04 05:17:04'),
(534, 109, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:47:09', '2026-09-04 05:17:09', '2026-09-04 05:17:09'),
(535, 112, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:47:16', '2026-09-04 05:17:16', '2026-09-04 05:17:16'),
(536, 113, 'pending_review', 'in_progress', 6, 'Needs Changes', '2026-09-04 10:47:25', '2026-09-04 05:17:25', '2026-09-04 05:17:25'),
(537, 121, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:47:32', '2026-09-04 05:17:32', '2026-09-04 05:17:32'),
(538, 150, NULL, 'pending', 6, 'Task created.', '2026-09-04 10:49:15', '2026-09-04 05:19:15', '2026-09-04 05:19:15'),
(539, 149, 'pending', 'in_progress', 6, NULL, '2026-09-04 10:49:24', '2026-09-04 05:19:24', '2026-09-04 05:19:24'),
(540, 149, 'in_progress', 'pending_review', 6, NULL, '2026-09-04 10:49:27', '2026-09-04 05:19:27', '2026-09-04 05:19:27'),
(541, 151, NULL, 'pending', 6, 'Task created.', '2026-09-04 10:50:29', '2026-09-04 05:20:29', '2026-09-04 05:20:29'),
(542, 152, NULL, 'pending', 6, 'Task created.', '2026-09-04 10:51:05', '2026-09-04 05:21:05', '2026-09-04 05:21:05'),
(543, 124, 'pending_review', 'completed', 6, NULL, '2026-09-04 10:53:34', '2026-09-04 05:23:34', '2026-09-04 05:23:34'),
(544, 153, NULL, 'pending', 6, 'Task created.', '2026-09-04 10:54:17', '2026-09-04 05:24:17', '2026-09-04 05:24:17'),
(545, 154, NULL, 'pending', 6, 'Task created.', '2026-09-04 10:55:45', '2026-09-04 05:25:45', '2026-09-04 05:25:45'),
(546, 155, NULL, 'pending', 6, 'Task created.', '2026-09-04 10:56:22', '2026-09-04 05:26:22', '2026-09-04 05:26:22'),
(547, 156, NULL, 'pending', 6, 'Task created.', '2026-09-04 10:57:03', '2026-09-04 05:27:03', '2026-09-04 05:27:03'),
(548, 157, NULL, 'pending', 6, 'Task created.', '2026-09-04 11:00:17', '2026-09-04 05:30:17', '2026-09-04 05:30:17'),
(549, 158, NULL, 'pending', 6, 'Task created.', '2026-09-04 11:03:17', '2026-09-04 05:33:17', '2026-09-04 05:33:17'),
(550, 149, 'pending_review', 'completed', 6, NULL, '2026-09-04 11:04:43', '2026-09-04 05:34:43', '2026-09-04 05:34:43'),
(551, 159, NULL, 'pending', 6, 'Task created.', '2026-09-04 11:05:23', '2026-09-04 05:35:23', '2026-09-04 05:35:23'),
(552, 160, NULL, 'pending', 6, 'Task created.', '2026-09-04 11:06:50', '2026-09-04 05:36:50', '2026-09-04 05:36:50'),
(553, 141, 'pending', 'in_progress', 6, NULL, '2026-09-04 11:07:52', '2026-09-04 05:37:52', '2026-09-04 05:37:52'),
(554, 141, 'in_progress', 'on_hold', 6, 'As discussed with sakshi ma\'am', '2026-09-04 11:08:31', '2026-09-04 05:38:31', '2026-09-04 05:38:31'),
(555, 161, NULL, 'pending', 6, 'Task created.', '2026-09-04 11:21:36', '2026-09-04 05:51:36', '2026-09-04 05:51:36'),
(556, 162, NULL, 'pending', 6, 'Task created.', '2026-09-04 12:44:15', '2026-09-04 07:14:15', '2026-09-04 07:14:15'),
(557, 163, NULL, 'pending', 6, 'Task created.', '2026-09-04 12:44:35', '2026-09-04 07:14:35', '2026-09-04 07:14:35'),
(558, 164, NULL, 'pending', 6, 'Task created.', '2026-09-04 12:44:53', '2026-09-04 07:14:53', '2026-09-04 07:14:53'),
(559, 165, NULL, 'pending', 6, 'Task created.', '2026-09-04 12:46:07', '2026-09-04 07:16:07', '2026-09-04 07:16:07'),
(560, 157, 'pending', 'in_progress', 10, NULL, '2026-09-04 12:52:13', '2026-09-04 07:22:13', '2026-09-04 07:22:13'),
(561, 157, 'in_progress', 'pending_review', 10, NULL, '2026-09-04 12:52:16', '2026-09-04 07:22:16', '2026-09-04 07:22:16'),
(562, 162, 'pending', 'in_progress', 9, NULL, '2026-09-04 13:04:33', '2026-09-04 07:34:33', '2026-09-04 07:34:33'),
(563, 147, 'in_progress', 'pending_review', 9, NULL, '2026-09-04 13:04:35', '2026-09-04 07:34:35', '2026-09-04 07:34:35'),
(564, 157, 'pending_review', 'completed', 6, NULL, '2026-09-04 13:06:19', '2026-09-04 07:36:19', '2026-09-04 07:36:19'),
(565, 162, 'in_progress', 'pending_review', 9, NULL, '2026-09-04 14:50:28', '2026-09-04 09:20:28', '2026-09-04 09:20:28'),
(566, 163, 'pending', 'in_progress', 9, NULL, '2026-09-04 14:50:30', '2026-09-04 09:20:30', '2026-09-04 09:20:30'),
(567, 163, 'in_progress', 'pending_review', 9, NULL, '2026-09-04 14:51:18', '2026-09-04 09:21:18', '2026-09-04 09:21:18'),
(568, 151, 'pending', 'in_progress', 9, NULL, '2026-09-04 14:56:21', '2026-09-04 09:26:21', '2026-09-04 09:26:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_code` varchar(191) DEFAULT NULL,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `work_log_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `auto_approve_tasks` tinyint(1) NOT NULL DEFAULT '0',
  `can_add_work_log_clients` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_code`, `name`, `email`, `phone`, `department_id`, `manager_id`, `is_active`, `work_log_enabled`, `auto_approve_tasks`, `can_add_work_log_clients`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'EMP-0001', 'Midbrains Superadmin', 'developer.midbrains@gmail.com', NULL, 4, NULL, 1, 0, 0, 0, NULL, '$2y$12$wXKIjWgFn5mZnV7ATVrSDuKOghgfjxTa887S.FySJxBUCRAII2voy', '23s5GOpJ1fBzimYj3kNK41dOLAaG94oW6BUD7OeNctWVF4kQ15bk6ZsghjiX', '2026-07-27 02:52:17', '2026-07-27 02:52:17', NULL),
(4, 'EMP-0002', 'Superadmin', 'superadmin@midbrains.in', NULL, 2, NULL, 1, 0, 0, 0, NULL, '$2y$12$L1dqBJ0Lx6G5CVAinXgGPuofbisJREgVDsm.zHlksfGE/EPMrzDu6', NULL, '2026-07-28 00:32:55', '2026-07-28 00:40:21', NULL),
(5, 'EMP-0003', 'Sakshi', 'sakshi@midbrains.in', NULL, 4, NULL, 1, 0, 0, 0, NULL, '$2y$12$elx.4oH34G2hmRH1hO.cIODAHWr10Wcm9pw/jUUjYdcLLuytrRqm2', 'LGglQmlp63o2pDlZ5ydF91Xv56JHTaQybBYuYxyZE10MQb15d8xiFnBHOXij', '2026-07-28 00:32:55', '2026-08-25 04:28:37', NULL),
(6, 'EMP-0004', 'Anjali', 'anjali@midbrains.in', NULL, 4, 5, 1, 0, 0, 0, NULL, '$2y$12$SYhTTxawxqLPN2F.14Z5M.uJ6fInjN4SDMKhPNFteoLVvcDL6dGSG', 'IGnYnLION3a4zQt4mCAhVndMOUczfH9tlFrcvEi16VMJL4fAeT96ScDikU8R', '2026-07-28 00:32:56', '2026-08-10 05:04:17', NULL),
(7, 'EMP-0005', 'Bharat Shinde', 'bharat@midbrains.in', NULL, 4, 5, 1, 0, 0, 0, NULL, '$2y$12$MQ7FYiCza/SL5F7EfqofRuIZGelmgcAKeKRJk5LKP4iRcWnjHkcV.', 'FAGJNxfOA9JPKsEpWMt4NGJEHaeqgkVLOyd1qQTlxSZEzzJvYe1TxaNX1kU5', '2026-07-28 00:32:56', '2026-08-22 00:00:02', NULL),
(8, 'EMP-0006', 'Rohan', 'rohan@midbrains.in', '9175044996', 4, 5, 1, 0, 0, 0, NULL, '$2y$12$5Zt6ykAudjAntYN7VTLA6.z3DXBWPYEmye8fBda3vbihr1RkUN4Ae', 'odcQndunavhGNK4T9B7Wi2HTRCgBWEAhrZ4ZNRRKawM4vykhCz2SGPo3zoZn', '2026-07-28 00:40:22', '2026-08-18 23:30:52', NULL),
(9, 'EMP-0007', 'Vishal', 'vishal@midbrains.in', NULL, 4, NULL, 1, 0, 0, 0, NULL, '$2y$12$ibTQ3RkxGZdz3qZIcCS.0e3EtuSQcDGk79KXZ7GZT9fWQKtUQ4ClK', 'ze6UpHSJGvIJ3dmYy7ExyphGkJIrh9flnNyRoaHBdQvn6mHnbEwyJuzdNTgU', '2026-08-07 04:29:53', '2026-08-18 07:20:08', NULL),
(10, 'EMP-0008', 'Rani', 'rani@midbrains.in', NULL, 4, NULL, 1, 0, 0, 0, NULL, '$2y$12$l1ZUC/ZClDy9DbgDm0c1G.2/fEj8yX4bdHKhApJL2oLmulrus0JfK', 'MeLcV47IVv5Qn8Eyr0xB8GnI2UJlvF7e0Lattu5nqSRcWp9FK5LFYXzZWlBw', '2026-08-18 07:25:49', '2026-08-18 07:25:49', NULL),
(11, 'EMP-0009', 'Yash', 'yash@midbrains.in', '1236549877', 7, NULL, 1, 0, 0, 1, NULL, '$2y$12$tnsD.gHP4TzggPzs5KBXVOukKR5t/SP5Wg/dbqu2cp/QEPhe0CGPu', NULL, '2026-08-24 21:20:14', '2026-09-04 06:39:44', NULL),
(12, 'EMP-0010', 'Vineet', 'vineet@midbrains.in', '3263263263', 6, NULL, 1, 0, 0, 1, NULL, '$2y$12$5l/Yhj6rPqULComxWYFuIuopzoqzEZ.hyWq1hi72ms80hI0kHkWmW', NULL, '2026-08-25 21:26:00', '2026-09-04 06:39:40', NULL),
(13, 'EMP-0011', 'Khadija', 'khadija@midbrains.in', '5868566585', 7, NULL, 1, 0, 0, 1, NULL, '$2y$12$O8rNFaMchCZxDC36gg3YIu2KDDtRArn8hnWOoAms2.FGUjeqBlk76', NULL, '2026-09-01 07:34:34', '2026-09-04 06:39:37', NULL),
(14, 'EMP-0012', 'Sapna', 'sapna@midbrains.in', '4574574577', 7, NULL, 1, 0, 0, 0, NULL, '$2y$12$vFP6o9ysPeIvuMi2WLQaZ.FMJ1R/DtqG.zq7rs/HFpMxWVxZIJkLm', NULL, '2026-09-01 07:36:12', '2026-09-01 07:36:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `work_logs`
--

CREATE TABLE `work_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `work_log_client_id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `logged_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `work_logs`
--

INSERT INTO `work_logs` (`id`, `user_id`, `work_log_client_id`, `description`, `logged_at`, `created_at`, `updated_at`) VALUES
(1, 11, 1, '- Completed home page\n- Completed new changes from client\n- database setup\n- ui changes', '2026-08-26 06:43:41', '2026-08-25 19:43:41', '2026-08-25 19:43:41'),
(2, 11, 2, '- Completed contact page\n- Completed about page\n- new entry\n', '2026-08-26 00:00:00', '2026-08-25 21:21:13', '2026-08-26 02:24:32'),
(3, 12, 3, '12 new ads\n2 ad changes', '2026-08-26 08:26:55', '2026-08-25 21:26:55', '2026-08-25 21:26:55'),
(4, 12, 3, '2 ads \n4 posts', '2026-08-25 00:00:00', '2026-08-26 01:13:40', '2026-08-26 01:13:40'),
(5, 11, 2, 'client changes\nnew pages\n', '2026-08-26 00:00:00', '2026-08-26 01:32:00', '2026-08-26 01:32:00'),
(6, 11, 2, 'trey\noktry', '2026-08-25 00:00:00', '2026-08-26 01:34:25', '2026-08-26 01:34:25'),
(7, 11, 4, 'OMS\n- new page\n- old changes', '2026-09-04 00:00:00', '2026-09-04 07:09:29', '2026-09-04 07:09:29'),
(8, 11, 4, '<p><strong><em>OMS</em></strong></p><ul><li>new page</li><li>refined filters</li></ul><ul><li>added buttons</li></ul>', '2026-09-04 00:00:00', '2026-09-04 07:17:36', '2026-09-04 07:17:36');

-- --------------------------------------------------------

--
-- Table structure for table `work_log_clients`
--

CREATE TABLE `work_log_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `company` varchar(191) DEFAULT NULL,
  `notes` text,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `work_log_clients`
--

INSERT INTO `work_log_clients` (`id`, `name`, `company`, `notes`, `department_id`, `created_by`, `deleted_by`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Renuka Hydraulics', 'Renuka Hydraulics', NULL, 7, 1, NULL, 1, '2026-08-25 19:32:04', '2026-08-25 19:32:04', NULL),
(2, 'Unique Clininc', 'Unique Clininc', NULL, 7, 1, NULL, 1, '2026-08-25 21:20:43', '2026-08-27 01:10:41', NULL),
(3, 'Anandteerth', 'Anandteerth', NULL, 6, 1, NULL, 1, '2026-08-25 21:24:34', '2026-08-25 21:24:34', NULL),
(4, 'Midbrains Technologies', NULL, NULL, 7, 11, NULL, 1, '2026-09-04 07:08:41', '2026-09-04 07:08:41', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_on_tasks`
--
ALTER TABLE `add_on_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `add_on_tasks_interrupted_task_id_resumed_at_index` (`interrupted_task_id`,`resumed_at`),
  ADD KEY `add_on_tasks_employee_id_index` (`employee_id`),
  ADD KEY `add_on_tasks_add_on_task_id_foreign` (`add_on_task_id`),
  ADD KEY `add_on_tasks_created_by_foreign` (`created_by`);

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `audit_logs_user_id_index` (`user_id`),
  ADD KEY `audit_logs_action_index` (`action`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_status_index` (`status`),
  ADD KEY `clients_department_id_foreign` (`department_id`),
  ADD KEY `clients_created_by_foreign` (`created_by`);

--
-- Indexes for table `client_plan_items`
--
ALTER TABLE `client_plan_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_plan_items_unique` (`client_id`,`platform`,`content_type_id`),
  ADD KEY `client_plan_items_content_type_id_foreign` (`content_type_id`);

--
-- Indexes for table `client_service`
--
ALTER TABLE `client_service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_service_unique` (`client_id`,`service_id`),
  ADD KEY `client_service_service_id_foreign` (`service_id`);

--
-- Indexes for table `content_types`
--
ALTER TABLE `content_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `content_types_name_unique` (`name`),
  ADD KEY `content_types_created_by_foreign` (`created_by`);

--
-- Indexes for table `delay_requests`
--
ALTER TABLE `delay_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delay_requests_task_id_status_index` (`task_id`,`status`),
  ADD KEY `delay_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `delay_requests_reviewed_by_foreign` (`reviewed_by`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_code_unique` (`code`),
  ADD KEY `departments_parent_department_id_foreign` (`parent_department_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_permissions`
--
ALTER TABLE `menu_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `menu_permissions_scoped_unique` (`module_key`,`role_name`,`department_id`),
  ADD KEY `menu_permissions_department_id_foreign` (`department_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_name_unique` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasks_status_priority_index` (`status`,`priority`),
  ADD KEY `tasks_due_date_index` (`due_date`),
  ADD KEY `tasks_created_by_foreign` (`created_by`),
  ADD KEY `tasks_department_id_foreign` (`department_id`),
  ADD KEY `tasks_parent_interrupted_task_id_foreign` (`parent_interrupted_task_id`),
  ADD KEY `tasks_client_id_foreign` (`client_id`),
  ADD KEY `tasks_deleted_by_foreign` (`deleted_by`),
  ADD KEY `tasks_content_type_id_foreign` (`content_type_id`);

--
-- Indexes for table `task_assignments`
--
ALTER TABLE `task_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_assignments_task_id_is_current_index` (`task_id`,`is_current`),
  ADD KEY `task_assignments_assigned_to_foreign` (`assigned_to`),
  ADD KEY `task_assignments_assigned_by_foreign` (`assigned_by`);

--
-- Indexes for table `task_attachments`
--
ALTER TABLE `task_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_attachments_task_id_index` (`task_id`),
  ADD KEY `task_attachments_comment_id_foreign` (`comment_id`),
  ADD KEY `task_attachments_uploaded_by_foreign` (`uploaded_by`);

--
-- Indexes for table `task_comments`
--
ALTER TABLE `task_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_comments_task_id_index` (`task_id`),
  ADD KEY `task_comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `task_platforms`
--
ALTER TABLE `task_platforms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_platforms_task_id_platform_index` (`task_id`,`platform`);

--
-- Indexes for table `task_status_history`
--
ALTER TABLE `task_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_status_history_task_id_index` (`task_id`),
  ADD KEY `task_status_history_changed_by_fk` (`changed_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_employee_code_unique` (`employee_code`),
  ADD KEY `users_department_id_foreign` (`department_id`),
  ADD KEY `users_manager_id_foreign` (`manager_id`);

--
-- Indexes for table `work_logs`
--
ALTER TABLE `work_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_logs_user_id_foreign` (`user_id`),
  ADD KEY `work_logs_work_log_client_id_foreign` (`work_log_client_id`);

--
-- Indexes for table `work_log_clients`
--
ALTER TABLE `work_log_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_log_clients_department_id_foreign` (`department_id`),
  ADD KEY `work_log_clients_created_by_foreign` (`created_by`),
  ADD KEY `work_log_clients_deleted_by_foreign` (`deleted_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_on_tasks`
--
ALTER TABLE `add_on_tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `client_plan_items`
--
ALTER TABLE `client_plan_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=324;

--
-- AUTO_INCREMENT for table `client_service`
--
ALTER TABLE `client_service`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `content_types`
--
ALTER TABLE `content_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `delay_requests`
--
ALTER TABLE `delay_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu_permissions`
--
ALTER TABLE `menu_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `task_assignments`
--
ALTER TABLE `task_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `task_attachments`
--
ALTER TABLE `task_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_comments`
--
ALTER TABLE `task_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_platforms`
--
ALTER TABLE `task_platforms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `task_status_history`
--
ALTER TABLE `task_status_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=569;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `work_logs`
--
ALTER TABLE `work_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `work_log_clients`
--
ALTER TABLE `work_log_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `add_on_tasks`
--
ALTER TABLE `add_on_tasks`
  ADD CONSTRAINT `add_on_tasks_add_on_task_id_foreign` FOREIGN KEY (`add_on_task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `add_on_tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `add_on_tasks_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `add_on_tasks_interrupted_task_id_foreign` FOREIGN KEY (`interrupted_task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `clients_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `client_plan_items`
--
ALTER TABLE `client_plan_items`
  ADD CONSTRAINT `client_plan_items_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_plan_items_content_type_id_foreign` FOREIGN KEY (`content_type_id`) REFERENCES `content_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_service`
--
ALTER TABLE `client_service`
  ADD CONSTRAINT `client_service_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_service_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `content_types`
--
ALTER TABLE `content_types`
  ADD CONSTRAINT `content_types_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `delay_requests`
--
ALTER TABLE `delay_requests`
  ADD CONSTRAINT `delay_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `delay_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `delay_requests_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_parent_department_id_foreign` FOREIGN KEY (`parent_department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `menu_permissions`
--
ALTER TABLE `menu_permissions`
  ADD CONSTRAINT `menu_permissions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_content_type_id_foreign` FOREIGN KEY (`content_type_id`) REFERENCES `content_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tasks_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tasks_parent_interrupted_task_id_foreign` FOREIGN KEY (`parent_interrupted_task_id`) REFERENCES `tasks` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `task_assignments`
--
ALTER TABLE `task_assignments`
  ADD CONSTRAINT `task_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `task_assignments_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `task_assignments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_attachments`
--
ALTER TABLE `task_attachments`
  ADD CONSTRAINT `task_attachments_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `task_comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_attachments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_attachments_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `task_comments`
--
ALTER TABLE `task_comments`
  ADD CONSTRAINT `task_comments_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `task_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `task_platforms`
--
ALTER TABLE `task_platforms`
  ADD CONSTRAINT `task_platforms_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `task_status_history`
--
ALTER TABLE `task_status_history`
  ADD CONSTRAINT `task_status_history_changed_by_fk` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `task_status_history_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `work_logs`
--
ALTER TABLE `work_logs`
  ADD CONSTRAINT `work_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `work_logs_work_log_client_id_foreign` FOREIGN KEY (`work_log_client_id`) REFERENCES `work_log_clients` (`id`);

--
-- Constraints for table `work_log_clients`
--
ALTER TABLE `work_log_clients`
  ADD CONSTRAINT `work_log_clients_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `work_log_clients_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `work_log_clients_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
