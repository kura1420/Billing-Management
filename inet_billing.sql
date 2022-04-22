-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 23, 2022 at 02:58 AM
-- Server version: 10.3.34-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inet_billing`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_menus`
--

CREATE TABLE `app_menus` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_menus`
--

INSERT INTO `app_menus` (`id`, `name`, `title`, `url`, `parent`, `active`, `created_at`, `updated_at`) VALUES
('680b2945-61af-4dfa-91a3-848bcacef140', 'Provinsi', 'Provinsi', 'region/provinsi', 'ec485612-4577-4278-bb9f-6be75463fefd', 1, '2022-04-08 21:43:28', '2022-04-08 21:43:28'),
('ec485612-4577-4278-bb9f-6be75463fefd', 'region', NULL, NULL, NULL, 1, '2022-04-08 21:43:00', '2022-04-08 21:43:00');

-- --------------------------------------------------------

--
-- Table structure for table `app_profiles`
--

CREATE TABLE `app_profiles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortname` char(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fax` char(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` char(50) COLLATE utf8mb4_unicode_ci DEFAULT 'blank.png',
  `website` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_profiles`
--

INSERT INTO `app_profiles` (`id`, `name`, `shortname`, `telp`, `email`, `fax`, `address`, `logo`, `website`, `created_at`, `updated_at`) VALUES
('d07f0f13-6271-4f51-98d8-7eb5950623f1', 'indonesianet', 'INET', '(021) 1234-5678', 'info@inet.com', '123', 'kemang', '3u517Txey0.png', 'https://inet.com', '2022-04-08 20:54:52', '2022-04-08 20:56:44');

-- --------------------------------------------------------

--
-- Table structure for table `app_roles`
--

CREATE TABLE `app_roles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_roles`
--

INSERT INTO `app_roles` (`id`, `name`, `desc`, `active`, `created_at`, `updated_at`) VALUES
('13884fb6-5172-42cc-90b9-a1c764420c15', 'office', NULL, 1, '2022-04-08 21:14:52', '2022-04-08 21:14:52'),
('46fb9969-4625-44e9-8eae-e8883afb5abf', 'administrator', NULL, 1, '2022-04-08 21:14:43', '2022-04-08 21:14:43');

-- --------------------------------------------------------

--
-- Table structure for table `app_role_menus`
--

CREATE TABLE `app_role_menus` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_role_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_menu_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full` tinyint(1) NOT NULL DEFAULT 0,
  `create` tinyint(1) NOT NULL DEFAULT 0,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `update` tinyint(1) NOT NULL DEFAULT 0,
  `delete` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `ppn_tax_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `code`, `name`, `desc`, `active`, `ppn_tax_id`, `created_at`, `updated_at`) VALUES
('1581e1d8-7b13-4eb0-83d5-807ac5e59f22', 'AR2', 'area 2', NULL, 1, '31420733-0d15-47ac-8b55-cf10ad91ea67', '2022-04-22 12:57:36', '2022-04-22 12:57:36'),
('e869a4b7-4ce5-4612-a5c9-440d2bd17000', 'AR1', 'Area 1', NULL, 1, '31420733-0d15-47ac-8b55-cf10ad91ea67', '2022-04-22 12:55:52', '2022-04-22 12:55:52');

-- --------------------------------------------------------

--
-- Table structure for table `area_products`
--

CREATE TABLE `area_products` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provinsi_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_type_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_service_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `area_product_customers`
--

CREATE TABLE `area_product_customers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provinsi_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_type_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_segment_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_type_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_service_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `price_sub` decimal(12,2) NOT NULL,
  `price_ppn` decimal(10,2) NOT NULL,
  `price_total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `area_product_promos`
--

CREATE TABLE `area_product_promos` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provinsi_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_promo_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billing_invoices`
--

CREATE TABLE `billing_invoices` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_type_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_data_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_notif` date DEFAULT NULL,
  `notif_at` datetime NOT NULL,
  `suspend_at` datetime NOT NULL,
  `terminate_at` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `verif_payment_at` datetime DEFAULT NULL,
  `verif_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file` char(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_payment` char(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_ppn` decimal(12,2) NOT NULL,
  `price_sub` decimal(12,2) NOT NULL,
  `price_total` decimal(12,2) NOT NULL,
  `price_discount` decimal(12,2) NOT NULL,
  `product_type_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_service_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `billing_templates`
--

CREATE TABLE `billing_templates` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender` enum('email','sms','msgr') COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('notif','suspend','terminated','closed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `billing_templates`
--

INSERT INTO `billing_templates` (`id`, `name`, `sender`, `content`, `type`, `created_at`, `updated_at`) VALUES
('0226a5ed-8da2-43d1-9f37-2a7382b25530', 'notif email', 'email', 'Dear _name_, notif<br>', 'notif', '2022-04-22 11:58:12', '2022-04-22 12:04:39'),
('33e0fcbf-783b-4f93-8480-4697184ab299', 'terminate email', 'email', 'Dear _name_, notif terminate<br>', 'notif', '2022-04-22 12:06:20', '2022-04-22 12:06:20'),
('624a9e1b-4cb3-4ca2-806d-80aab7089c5e', 'suspend email', 'email', '<div>Dear _name_, <b><br></b></div><div><b><br></b></div><div><b>u di suspend</b></div>', 'suspend', '2022-04-22 12:04:24', '2022-04-22 12:22:25');

-- --------------------------------------------------------

--
-- Table structure for table `billing_types`
--

CREATE TABLE `billing_types` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `notif` int(11) NOT NULL DEFAULT 7,
  `suspend` int(11) NOT NULL DEFAULT 3,
  `terminated` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `billing_types`
--

INSERT INTO `billing_types` (`id`, `code`, `name`, `desc`, `active`, `notif`, `suspend`, `terminated`, `created_at`, `updated_at`) VALUES
('72ed143a-5f7c-4e25-9dde-c4fa4439ae73', 'BILMAIN1', 'billing maintenance 1', NULL, 1, 5, 0, 0, '2022-04-22 00:44:19', '2022-04-22 00:44:19'),
('a16d7605-a88d-469d-8999-bef45dd68bf7', 'BILBLN', 'billing bulanan', NULL, 1, 7, 3, 1, '2022-04-22 00:41:58', '2022-04-22 00:42:38'),
('dc0f7987-2528-4a89-86be-106bb6aaa4d1', 'BILMAIN', 'billing maintenance', NULL, 1, 10, 0, 0, '2022-04-22 00:43:19', '2022-04-22 00:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provinsi_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `provinsi_id`, `created_at`, `updated_at`) VALUES
('d3175c7d-e95c-4722-bc41-dfafb3a3213e', 'city 11', '440e52e0-c5fc-4ac8-8b5b-82a9012498c0', '2022-04-07 12:31:17', '2022-04-07 12:34:21'),
('e3e790f3-4dc6-426d-bf86-57f47c4c6994', 'city 2', '38bc73b9-90e7-4194-b4d2-ca55bbc7d11e', '2022-04-07 12:34:10', '2022-04-07 12:34:10');

-- --------------------------------------------------------

--
-- Table structure for table `customer_contacts`
--

CREATE TABLE `customer_contacts` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_profile_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_data_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('l','p') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telp` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handphone` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_data`
--

CREATE TABLE `customer_data` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `member_at` datetime DEFAULT NULL,
  `terminate_at` datetime DEFAULT NULL,
  `dismantle_at` datetime DEFAULT NULL,
  `customer_type_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_segment_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provinsi_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_product_customer_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_type_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_service_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_sub` decimal(12,2) NOT NULL,
  `price_ppn` decimal(8,2) NOT NULL,
  `price_total` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_documents`
--

CREATE TABLE `customer_documents` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` char(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity_number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_data_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_contact_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_profiles`
--

CREATE TABLE `customer_profiles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('l','p') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handphone` char(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` char(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `picture` char(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'blank.png',
  `birthdate` date DEFAULT NULL,
  `marital_status` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `child` int(11) NOT NULL DEFAULT 0,
  `customer_data_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_promos`
--

CREATE TABLE `customer_promos` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_data_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_promo_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_segments`
--

CREATE TABLE `customer_segments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_segments`
--

INSERT INTO `customer_segments` (`id`, `code`, `name`, `desc`, `active`, `created_at`, `updated_at`) VALUES
('4afd5d55-11f0-469e-b389-4e80a846a6c4', 'CS3', 'Customer Segment 3', NULL, 1, '2022-04-08 16:10:07', '2022-04-08 16:10:07'),
('b13ffc45-83fb-45af-9d68-b2c8f7bf0856', 'CS1', 'Customer Segment 1', NULL, 1, '2022-04-08 16:09:42', '2022-04-08 16:10:13'),
('ef5eb52e-94c2-467b-afd8-7cd40e470a0e', 'CS2', 'Customer Segment 2', NULL, 1, '2022-04-08 16:09:55', '2022-04-08 16:09:55');

-- --------------------------------------------------------

--
-- Table structure for table `customer_types`
--

CREATE TABLE `customer_types` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_types`
--

INSERT INTO `customer_types` (`id`, `code`, `name`, `desc`, `active`, `created_at`, `updated_at`) VALUES
('1ade04da-822f-4048-99b0-ecf35a62db29', 'CT2', 'Customer Type 2', NULL, 1, '2022-04-08 15:10:07', '2022-04-08 15:10:21'),
('71ac449b-62ee-43a6-8458-e5d638d15f1c', 'CT1', 'Customer Type 1', NULL, 1, '2022-04-08 15:09:58', '2022-04-08 15:09:58');

-- --------------------------------------------------------

--
-- Table structure for table `departements`
--

CREATE TABLE `departements` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departements`
--

INSERT INTO `departements` (`id`, `code`, `name`, `desc`, `active`, `created_at`, `updated_at`) VALUES
('0c548f9a-f82d-427f-87d6-02076f86673c', 'D1', 'd satu', NULL, 1, '2022-04-08 00:38:30', '2022-04-08 00:39:25'),
('c9e34f62-723c-4a97-bf71-51500a52aec5', 'D2', 'D Dua', NULL, 1, '2022-04-08 00:39:35', '2022-04-08 00:39:35');

-- --------------------------------------------------------

--
-- Table structure for table `departement_roles`
--

CREATE TABLE `departement_roles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `app_role_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departement_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_03_23_060749_create_app_profiles_table', 1),
(6, '2022_03_23_060759_create_app_roles_table', 1),
(7, '2022_03_23_060807_create_app_menus_table', 1),
(8, '2022_03_23_060817_create_app_role_menus_table', 1),
(9, '2022_03_23_061547_create_sessions_table', 2),
(10, '2022_03_28_222801_create_user_profiles_table', 3),
(11, '2022_03_28_222832_create_departements_table', 3),
(12, '2022_03_28_223140_create_departement_roles_table', 3),
(13, '2022_03_28_223228_create_customer_types_table', 3),
(14, '2022_03_28_223253_create_customer_segments_table', 3),
(15, '2022_03_28_223349_create_customer_data_table', 3),
(16, '2022_03_28_223400_create_customer_profiles_table', 3),
(17, '2022_03_28_223430_create_customer_contacts_table', 3),
(18, '2022_03_28_223441_create_customer_documents_table', 3),
(19, '2022_03_28_223453_create_customer_promos_table', 3),
(20, '2022_03_28_223519_create_provinsis_table', 3),
(21, '2022_03_28_223614_create_cities_table', 3),
(22, '2022_03_28_223623_create_areas_table', 3),
(23, '2022_03_28_223641_create_product_promos_table', 3),
(24, '2022_03_28_223715_create_product_promo_discounts_table', 3),
(25, '2022_03_28_223729_create_area_products_table', 3),
(26, '2022_03_28_223744_create_area_product_promos_table', 3),
(27, '2022_03_28_223757_create_area_product_customers_table', 3),
(28, '2022_03_28_223812_create_product_types_table', 3),
(29, '2022_03_28_223826_create_product_prices_table', 3),
(30, '2022_03_28_223837_create_taxes_table', 3),
(31, '2022_03_28_223936_create_billing_types_table', 3),
(32, '2022_03_28_224004_create_billing_profiles_table', 3),
(33, '2022_03_28_224019_create_billing_templates_table', 3),
(34, '2022_03_28_224033_create_vouchers_table', 3),
(35, '2022_03_28_224048_create_skp_billings_table', 3),
(36, '2022_03_29_105051_create_billing_invoices_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_promos`
--

CREATE TABLE `product_promos` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `image` char(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_promos`
--

INSERT INTO `product_promos` (`id`, `code`, `name`, `desc`, `active`, `start`, `end`, `image`, `created_at`, `updated_at`) VALUES
('f9523043-8041-47d5-be66-ecef121bb92a', 'PR1', 'Promo 1', 'Desc Promo 1', 1, '2022-04-11', '2022-04-15', 'ZOZ70KkQgF.png', '2022-04-11 20:43:41', '2022-04-11 20:43:41');

-- --------------------------------------------------------

--
-- Table structure for table `product_promo_services`
--

CREATE TABLE `product_promo_services` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` char(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'payment, service',
  `discount` int(11) NOT NULL COMMENT 'value with percent',
  `until_payment` int(11) NOT NULL DEFAULT 0,
  `product_promo_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_promo_services`
--

INSERT INTO `product_promo_services` (`id`, `type`, `discount`, `until_payment`, `product_promo_id`, `product_service_id`, `created_at`, `updated_at`) VALUES
('0dbf8f98-ca2c-440f-8cad-7901bd87f995', 'PAY', 50, 1, 'f9523043-8041-47d5-be66-ecef121bb92a', NULL, '2022-04-11 20:43:41', '2022-04-11 20:43:41');

-- --------------------------------------------------------

--
-- Table structure for table `product_services`
--

CREATE TABLE `product_services` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `price` decimal(12,2) NOT NULL,
  `product_type_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_services`
--

INSERT INTO `product_services` (`id`, `code`, `name`, `desc`, `active`, `price`, `product_type_id`, `created_at`, `updated_at`) VALUES
('25de3779-0ac9-40a8-9ca0-706e3b831f63', 'PR', '1 Mbps', NULL, 1, '300000.00', '48cae2cf-1c1c-469f-8fcf-ab93532e45f0', '2022-04-10 23:34:48', '2022-04-11 00:42:57'),
('5c230164-e19f-425e-a38a-94c17e1a721b', 'PR2', '3 Mbps', NULL, 1, '500000.00', '48cae2cf-1c1c-469f-8fcf-ab93532e45f0', '2022-04-10 23:49:32', '2022-04-11 00:43:20'),
('74625a22-c148-4a0a-9f89-331916c6abc1', 'PR3', 'Internet 3 mb', NULL, 1, '30000.00', 'ad68d5a5-d6cb-48f6-b50e-1b95b660ee84', '2022-04-10 23:39:43', '2022-04-10 23:39:43');

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

CREATE TABLE `product_types` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`id`, `code`, `name`, `desc`, `active`, `created_at`, `updated_at`) VALUES
('48cae2cf-1c1c-469f-8fcf-ab93532e45f0', 'PY1', 'Internet Dedicated', NULL, 1, '2022-04-08 15:00:41', '2022-04-11 00:42:10'),
('ad68d5a5-d6cb-48f6-b50e-1b95b660ee84', 'PY2', 'Internet Broadband', NULL, 1, '2022-04-08 15:01:15', '2022-04-11 00:42:21');

-- --------------------------------------------------------

--
-- Table structure for table `provinsis`
--

CREATE TABLE `provinsis` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinsis`
--

INSERT INTO `provinsis` (`id`, `name`, `created_at`, `updated_at`) VALUES
('38bc73b9-90e7-4194-b4d2-ca55bbc7d11e', 'test', '2022-04-06 06:48:22', '2022-04-06 06:53:22'),
('440e52e0-c5fc-4ac8-8b5b-82a9012498c0', 'zxc', '2022-04-06 06:53:45', '2022-04-06 06:53:45');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('336NzxQj8fvXS0mpFVB2qkROnUaZKRAMUvjmvcA5', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:99.0) Gecko/20100101 Firefox/99.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkVwOFZrZExLd3c3YzVsdXlXMHh6bklkN3U1M1h2RkRYd1ZLdE1ZcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9pbmV0LWJpbGxpbmcubG9jYWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1650657460),
('EWafJiF8y1whOgYX6BdxXHP2Cn7c6CHy4815zNnq', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:99.0) Gecko/20100101 Firefox/99.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVWl6aEdTajQzUEsxc0hzbjJxYXBsM3hMbnVvY0FFNDFxYzdBdVFteSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9pbmV0LWJpbGxpbmcubG9jYWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1650653789),
('LxnewRMbfFEq86kQPNOoiA8P83M4jVUMC0HTf4Q1', NULL, '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:99.0) Gecko/20100101 Firefox/99.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTZCZ01qNlBlYlRQVHg1c25kd3JKNEFwMlpSMjR3TlBRYm84akl0TiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9pbmV0LWJpbGxpbmcubG9jYWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1650652489);

-- --------------------------------------------------------

--
-- Table structure for table `skp_billings`
--

CREATE TABLE `skp_billings` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `price_ppn` decimal(12,2) NOT NULL,
  `price_pph` decimal(12,2) NOT NULL,
  `price_sub` decimal(12,2) NOT NULL,
  `price_total` decimal(12,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `notif_at` datetime NOT NULL,
  `suspend_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` int(11) NOT NULL,
  `desc` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `name`, `value`, `desc`, `type`, `created_at`, `updated_at`) VALUES
('31420733-0d15-47ac-8b55-cf10ad91ea67', 'ppn 11%', 11, NULL, 'IN', '2022-04-08 01:01:35', '2022-04-08 01:01:35'),
('50498d6e-39ec-4d69-95ac-cd41ce860100', 'pph 10%', 10, NULL, 'OUT', '2022-04-08 01:01:51', '2022-04-08 01:01:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` timestamp NULL DEFAULT NULL,
  `token` char(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `active`, `last_login`, `token`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'user 1', 'user1@domain.com', NULL, '$2y$10$cgPKNPTQMXmQWq4/oy4bnujXcmfUR7P6Wg.W2icXOYwprEfmOC0Qy', 0, NULL, NULL, NULL, '2022-04-08 23:51:12', '2022-04-09 00:01:39'),
(6, 'user 2', 'user2@domain.com', NULL, '$2y$10$EjJiwWLH9MSzlSx1HHbIXON6FrcMUXkhOhsi4BJlibY1PkJ12Iui6', 1, NULL, NULL, NULL, '2022-04-09 00:05:24', '2022-04-09 00:05:24');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `handphone` char(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `departement_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `fullname`, `email`, `telp`, `handphone`, `user_id`, `departement_id`, `created_at`, `updated_at`) VALUES
('4e56a482-75dd-42e0-8931-ef857b46d968', 'user 1', 'user1@domain.com', '(021) 1234-5678', '(0812) 1392-4663', 3, '0c548f9a-f82d-427f-87d6-02076f86673c', '2022-04-08 23:51:12', '2022-04-08 23:51:12'),
('a573040d-758c-4b57-a00e-f617346b2b25', 'user 2', 'user2@domain.com', NULL, NULL, 6, 'c9e34f62-723c-4a97-bf71-51500a52aec5', '2022-04-09 00:05:24', '2022-04-09 00:05:24');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` char(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bandwidth_speed` char(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bandwidth_type` char(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `create_at` datetime NOT NULL,
  `create_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `update_at` datetime NOT NULL,
  `update_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `active_at` datetime NOT NULL,
  `deactive_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_menus`
--
ALTER TABLE `app_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_profiles`
--
ALTER TABLE `app_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_profiles_name_unique` (`name`),
  ADD UNIQUE KEY `app_profiles_telp_unique` (`telp`),
  ADD UNIQUE KEY `app_profiles_email_unique` (`email`);

--
-- Indexes for table `app_roles`
--
ALTER TABLE `app_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_roles_name_unique` (`name`);

--
-- Indexes for table `app_role_menus`
--
ALTER TABLE `app_role_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `areas_code_unique` (`code`);

--
-- Indexes for table `area_products`
--
ALTER TABLE `area_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area_product_customers`
--
ALTER TABLE `area_product_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area_product_promos`
--
ALTER TABLE `area_product_promos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billing_invoices`
--
ALTER TABLE `billing_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billing_templates`
--
ALTER TABLE `billing_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `billing_types`
--
ALTER TABLE `billing_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `billing_types_code_unique` (`code`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_contacts`
--
ALTER TABLE `customer_contacts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_contacts_handphone_unique` (`handphone`),
  ADD UNIQUE KEY `customer_contacts_email_unique` (`email`);

--
-- Indexes for table `customer_data`
--
ALTER TABLE `customer_data`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_data_code_unique` (`code`);

--
-- Indexes for table `customer_documents`
--
ALTER TABLE `customer_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_profiles`
--
ALTER TABLE `customer_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_profiles_email_unique` (`email`);

--
-- Indexes for table `customer_promos`
--
ALTER TABLE `customer_promos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_segments`
--
ALTER TABLE `customer_segments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_segments_code_unique` (`code`);

--
-- Indexes for table `customer_types`
--
ALTER TABLE `customer_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_types_code_unique` (`code`);

--
-- Indexes for table `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departements_code_unique` (`code`);

--
-- Indexes for table `departement_roles`
--
ALTER TABLE `departement_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `product_promos`
--
ALTER TABLE `product_promos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_promos_code_unique` (`code`);

--
-- Indexes for table `product_promo_services`
--
ALTER TABLE `product_promo_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_services`
--
ALTER TABLE `product_services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_prices_code_unique` (`code`);

--
-- Indexes for table `product_types`
--
ALTER TABLE `product_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_types_code_unique` (`code`);

--
-- Indexes for table `provinsis`
--
ALTER TABLE `provinsis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `skp_billings`
--
ALTER TABLE `skp_billings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_profiles_email_unique` (`email`),
  ADD UNIQUE KEY `user_profiles_handphone_unique` (`handphone`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
