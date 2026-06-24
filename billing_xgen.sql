-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2026 at 01:10 PM
-- Server version: 8.0.46
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billing_xgen`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_settings`
--

CREATE TABLE `company_settings` (
  `id` int NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `address` text,
  `email` varchar(255) DEFAULT NULL,
  `gst_no` varchar(50) DEFAULT NULL,
  `pan_no` varchar(50) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_no` varchar(100) DEFAULT NULL,
  `ifsc` varchar(50) DEFAULT NULL,
  `uam_no` varchar(50) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ifsc_code` varchar(50) DEFAULT NULL,
  `logo_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `company_settings`
--

INSERT INTO `company_settings` (`id`, `company_name`, `address`, `email`, `gst_no`, `pan_no`, `bank_name`, `account_no`, `ifsc`, `uam_no`, `logo`, `created_at`, `ifsc_code`, `logo_url`) VALUES
(1, 'XGEN LOGISTICS PRIVATE LIMITED', 'Gurugram, Haryana', 'accounts@xgen.in', '06XXXXXXXXXXXX', NULL, 'HDFC BANK', '1234567890', NULL, NULL, '', '2026-06-11 10:00:28', 'HDFC000123', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `billing_address` text,
  `gst_number` varchar(50) DEFAULT NULL,
  `state_name` varchar(100) DEFAULT NULL,
  `state_code` varchar(20) DEFAULT NULL,
  `po_number` varchar(100) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `created_at` date DEFAULT NULL,
  `client_gst_no` varchar(50) DEFAULT NULL,
  `pincode` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_name`, `billing_address`, `gst_number`, `state_name`, `state_code`, `po_number`, `contact_person`, `email`, `phone`, `status`, `created_at`, `client_gst_no`, `pincode`) VALUES
(3, 'Raghav', 'Sector 33, Gurugram ', '12776275318781', 'Haryana', '06', '1311', 'Amit', 'amitrao@1234gmail.com', '9876543106', 1, '2026-06-12', NULL, NULL),
(4, 'Sagar', 'Dharuhera, Rewari', '133344323566', 'Haryana', '06', '11199', 'Monu', 'sagarsain@1876gmail.com', '9866755688', 1, '2026-06-12', NULL, NULL),
(5, 'Zaheed Khan', 'Jhunjhunu, Neemrana, Rajasthan, 133242', '16677726711', 'Rajasthan', '09', '15565', 'Ramesh', 'zaheedkhn@1455gmail.com', '9877665544', 1, '2026-06-13', NULL, NULL),
(6, 'Roshan', 'Sec 32, Near Golf Road , Gurugram ,122502', '2238784782787', 'Haryana', '06', '22231', 'Suman', 'roshan3433@gmail.com', '9088877666', 1, '2026-06-16', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int NOT NULL,
  `invoice_no` varchar(100) DEFAULT NULL,
  `customer_id` int DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `period_from` date DEFAULT NULL,
  `period_to` date DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `fov_amount` decimal(12,2) DEFAULT NULL,
  `other_charge` decimal(12,2) DEFAULT NULL,
  `fuel_percent` decimal(5,2) DEFAULT NULL,
  `fuel_charge` decimal(12,2) DEFAULT NULL,
  `taxable_value` decimal(12,2) DEFAULT NULL,
  `cgst_rate` decimal(5,2) DEFAULT NULL,
  `sgst_rate` decimal(5,2) DEFAULT NULL,
  `igst_rate` decimal(5,2) DEFAULT NULL,
  `grand_total` decimal(12,2) DEFAULT NULL,
  `amount_words` text,
  `payment_due_date` date DEFAULT NULL,
  `remarks` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `pdf_file` varchar(255) DEFAULT NULL,
  `pdf_created_at` datetime DEFAULT NULL,
  `reverse_charge` enum('YES','NO') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cgst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sgst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `igst_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('Active','Inactive') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_no`, `customer_id`, `invoice_date`, `period_from`, `period_to`, `amount`, `fov_amount`, `other_charge`, `fuel_percent`, `fuel_charge`, `taxable_value`, `cgst_rate`, `sgst_rate`, `igst_rate`, `grand_total`, `amount_words`, `payment_due_date`, `remarks`, `created_at`, `pdf_file`, `pdf_created_at`, `reverse_charge`, `cgst_amount`, `sgst_amount`, `igst_amount`, `status`) VALUES
(4, 'Sales/26-27/0004', 4, '2026-06-01', '2026-03-12', '2026-06-06', 887878.00, 1.00, 1.10, 19.98, 177398.44, 1065278.54, 4.00, 5.00, 0.00, 1257028.68, NULL, '2026-07-01', NULL, '2026-06-13 04:25:12', NULL, NULL, 'YES', 0.00, 0.00, 0.00, 'Active'),
(5, 'Sales/26-27/0005', 3, '2026-06-13', '2026-06-01', '2026-06-12', 1129997.00, 1.00, 12.00, 19.98, 226002.00, 1356012.00, 9.00, 9.00, 0.00, 1600094.16, NULL, '2026-07-13', NULL, '2026-06-13 09:02:12', NULL, NULL, 'NO', 0.00, 0.00, 0.00, 'Active'),
(6, 'Sales/26-27/0006', 5, '2026-06-20', '2026-06-01', '2026-06-10', 1034455.00, 11.00, 9.00, 20.00, 206891.00, 1241366.00, 9.00, 9.00, 0.00, 1464811.88, NULL, '2026-07-20', NULL, '2026-06-13 09:39:18', NULL, NULL, 'NO', 111722.94, 111722.94, 0.00, 'Active'),
(14, 'Sales/26-27/0011', 4, '2026-06-15', '2026-05-31', '2026-06-20', 7606.00, 9780.00, 0.00, 35.00, 2662.10, 20048.10, 9.00, 9.00, 0.00, 23656.76, NULL, '2026-07-15', NULL, '2026-06-15 10:16:12', NULL, NULL, 'NO', 1804.33, 1804.33, 0.00, 'Inactive'),
(16, 'Sales/26-27/0015', 3, '2026-06-06', '2026-01-06', '2026-06-06', 1000.00, 100.00, 0.00, 35.00, 350.00, 1450.00, 0.00, 0.00, 18.00, 1711.00, NULL, '2026-07-06', NULL, '2026-06-15 11:27:50', NULL, NULL, 'NO', 0.00, 0.00, 261.00, 'Inactive'),
(17, 'Sales/26-27/0007', 6, '2026-06-16', '2025-08-01', '2026-06-19', 10000.00, 2200.00, 0.00, 25.00, 2500.00, 14700.00, 9.00, 9.00, 0.00, 17346.00, NULL, '2026-06-21', NULL, '2026-06-16 08:33:04', NULL, NULL, 'NO', 1323.00, 1323.00, 0.00, 'Active'),
(20, 'Sales/26-27/0018', 5, '2026-06-05', '2026-02-16', '2026-06-20', 111010.00, 1113.00, 0.00, 20.00, 22202.00, 134325.00, 9.00, 9.00, 0.00, 158503.50, NULL, '2026-07-05', NULL, '2026-06-16 08:45:04', NULL, NULL, 'NO', 12089.25, 12089.25, 0.00, 'Inactive'),
(21, 'Sales/26-27/0021', 6, '2026-05-31', '2026-06-02', '2026-06-19', 10000.00, 1234.00, 0.00, 20.00, 2000.00, 13234.00, 9.00, 9.00, 0.00, 15616.12, NULL, '2026-06-05', NULL, '2026-06-23 08:25:17', NULL, NULL, 'NO', 1191.06, 1191.06, 0.00, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_files`
--

CREATE TABLE `invoice_files` (
  `id` int NOT NULL,
  `invoice_id` int DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_mode` varchar(50) DEFAULT NULL,
  `reference_no` varchar(100) DEFAULT NULL,
  `remarks` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `balance_amount` decimal(12,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `payment_date`, `amount`, `payment_mode`, `reference_no`, `remarks`, `created_at`, `balance_amount`) VALUES
(1, 17, '2026-06-16', 10000.00, 'Cash', '12323448762', 'Recieved', '2026-06-16 11:21:21', 0.00),
(2, 14, '2026-06-10', 23656.76, 'UPI', '12332312344', 'Recieved ', '2026-06-16 11:22:40', 0.00),
(3, 20, '2026-06-16', 100000.00, 'Cash', '77656356263726', 'Make Pending amount also as early as possible\r\n', '2026-06-16 11:58:22', 0.00),
(4, 20, '2026-06-19', 58503.50, 'Cash', '12453526266', 'ok', '2026-06-19 10:32:25', 0.00),
(5, 16, '2026-05-31', 1711.00, 'Cash', '3342243553', 'ok', '2026-06-20 11:33:26', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Super Admin','Admin','Accountant') NOT NULL DEFAULT 'Accountant',
  `status` tinyint DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modules` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `last_login`, `created_at`, `modules`) VALUES
(3, 'Administrator', 'admin@xgen.com', '$2y$10$CIhTJb4mzxR98diqdEy4B.UZY.L.gz0VqYpSQh8VKr.znPh5I3xkK', 'Admin', 1, '2026-06-18 13:54:15', '2026-06-11 08:16:46', NULL),
(19, 'Sachin', 'sachin4444@gmail.com', '$2y$10$jI15vfESxlMu0VdbbxBWqeVzFVqkBcnUa1NmNPwqswBVhDmKAI8M6', 'Accountant', 1, NULL, '2026-06-20 07:25:22', 'invoice,payments,revenue'),
(20, 'Shekhar', 'shekhar1212@gmail.com', '$2y$10$2xrSiTvyFeliuu8NbtXz0uhbYMZIpw6efb4WBYLgsO3vGwglcldSW', 'Accountant', 1, NULL, '2026-06-20 08:11:10', 'customers,recieve_amount,outstanding');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_settings`
--
ALTER TABLE `company_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_files`
--
ALTER TABLE `invoice_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_settings`
--
ALTER TABLE `company_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `invoice_files`
--
ALTER TABLE `invoice_files`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
