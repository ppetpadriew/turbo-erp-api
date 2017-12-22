-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2017 at 12:31 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `turbo_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `code` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`code`, `description`, `activity_type_id`) VALUES
  ('OUT01', 'Warehouse Inspection', 1),
  ('REC01', 'Print Goods Received Note', 2),
  ('REC02', 'Warehouse Receipt', 2),
  ('REC03', 'Confirm Receipt', 2),
  ('SHP01', 'Confirm Shipment', 4),
  ('SHP02', 'Print Bill Of Lading', 4),
  ('SHP03', 'Print Packing Slips', 4),
  ('SHP04', 'Print Delivery Notes', 4);

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `house_number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_district` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_partner`
--

CREATE TABLE `business_partner` (
  `id` int(11) NOT NULL,
  `title` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rounding_factor` double(10,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enum_activity_type`
--

CREATE TABLE `enum_activity_type` (
  `id` int(11) NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enum_activity_type`
--

INSERT INTO `enum_activity_type` (`id`, `description`) VALUES
  (1, 'Outbound'),
  (2, 'Receipt'),
  (4, 'Shipment');

-- --------------------------------------------------------

--
-- Table structure for table `enum_inventory_transaction_type`
--

CREATE TABLE `enum_inventory_transaction_type` (
  `id` int(11) NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enum_inventory_transaction_type`
--

INSERT INTO `enum_inventory_transaction_type` (`id`, `description`) VALUES
  (1, 'Receipt'),
  (2, 'Issue'),
  (3, 'Transfer');

-- --------------------------------------------------------

--
-- Table structure for table `enum_procedure_type`
--

CREATE TABLE `enum_procedure_type` (
  `id` int(11) NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ean` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('Purchased','Manufactured') COLLATE utf8mb4_unicode_ci NOT NULL,
  `inventory_unit` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` double(19,4) NOT NULL,
  `weight_unit` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lot_controlled` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `item_data_by_warehouse`
--

CREATE TABLE `item_data_by_warehouse` (
  `warehouse` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reorder_point` double(19,4) NOT NULL,
  `negative_inventory_allowed` tinyint(1) NOT NULL,
  `obsolete` tinyint(1) NOT NULL,
  `exclude_from_cycle_counting` tinyint(1) NOT NULL,
  `valuation_method` enum('Standard Cost','MAUC','FIFO','LIFO','Lot Price') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_image`
--

CREATE TABLE `item_image` (
  `item` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `item_inventory_by_warehouse`
--

CREATE TABLE `item_inventory_by_warehouse` (
  `warehouse` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `on_hand` double(19,4) NOT NULL,
  `on_blocked` double(19,4) NOT NULL,
  `on_order` double(19,4) NOT NULL,
  `on_allocated` double(19,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_warehouse`
--

CREATE TABLE `item_warehouse` (
  `item` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `outbound_method` enum('LIFO','FIFO') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `negative_inventory_allowed` tinyint(1) NOT NULL,
  `manual_adjustment_allowed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehousing_order_type`
--

CREATE TABLE `warehousing_order_type` (
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inventory_transaction_type_id` int(11) NOT NULL,
  `receipt_procedure` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inspection_procedure` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipment_procedure` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generate_lots_automatically` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehousing_procedure`
--

CREATE TABLE `warehousing_procedure` (
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `procedure_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`code`),
  ADD KEY `activity_type_id` (`activity_type_id`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_partner`
--
ALTER TABLE `business_partner`
  ADD PRIMARY KEY (`id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `enum_activity_type`
--
ALTER TABLE `enum_activity_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enum_inventory_transaction_type`
--
ALTER TABLE `enum_inventory_transaction_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enum_procedure_type`
--
ALTER TABLE `enum_procedure_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`code`),
  ADD UNIQUE KEY `ean` (`ean`),
  ADD KEY `inventory_unit` (`inventory_unit`),
  ADD KEY `weight_unit` (`weight_unit`);

--
-- Indexes for table `item_data_by_warehouse`
--
ALTER TABLE `item_data_by_warehouse`
  ADD PRIMARY KEY (`warehouse`,`item`),
  ADD KEY `item` (`item`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `item_image`
--
ALTER TABLE `item_image`
  ADD PRIMARY KEY (`item`);

--
-- Indexes for table `item_inventory_by_warehouse`
--
ALTER TABLE `item_inventory_by_warehouse`
  ADD PRIMARY KEY (`warehouse`,`item`),
  ADD KEY `item` (`item`),
  ADD KEY `warehouse` (`warehouse`);

--
-- Indexes for table `item_warehouse`
--
ALTER TABLE `item_warehouse`
  ADD PRIMARY KEY (`item`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `warehousing_order_type`
--
ALTER TABLE `warehousing_order_type`
  ADD PRIMARY KEY (`code`),
  ADD KEY `inventory_transaction_type_id` (`inventory_transaction_type_id`);

--
-- Indexes for table `warehousing_procedure`
--
ALTER TABLE `warehousing_procedure`
  ADD PRIMARY KEY (`code`),
  ADD KEY `procedure_type_id` (`procedure_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `business_partner`
--
ALTER TABLE `business_partner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `enum_activity_type`
--
ALTER TABLE `enum_activity_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `enum_inventory_transaction_type`
--
ALTER TABLE `enum_inventory_transaction_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `enum_procedure_type`
--
ALTER TABLE `enum_procedure_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`activity_type_id`) REFERENCES `enum_activity_type` (`id`);

--
-- Constraints for table `business_partner`
--
ALTER TABLE `business_partner`
  ADD CONSTRAINT `business_partner_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`inventory_unit`) REFERENCES `unit` (`code`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`weight_unit`) REFERENCES `unit` (`code`);

--
-- Constraints for table `item_data_by_warehouse`
--
ALTER TABLE `item_data_by_warehouse`
  ADD CONSTRAINT `item_data_by_warehouse_ibfk_1` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`code`),
  ADD CONSTRAINT `item_data_by_warehouse_ibfk_2` FOREIGN KEY (`item`) REFERENCES `item` (`code`);

--
-- Constraints for table `item_image`
--
ALTER TABLE `item_image`
  ADD CONSTRAINT `item_image_ibfk_1` FOREIGN KEY (`item`) REFERENCES `item` (`code`);

--
-- Constraints for table `item_inventory_by_warehouse`
--
ALTER TABLE `item_inventory_by_warehouse`
  ADD CONSTRAINT `item_inventory_by_warehouse_ibfk_1` FOREIGN KEY (`warehouse`) REFERENCES `warehouse` (`code`),
  ADD CONSTRAINT `item_inventory_by_warehouse_ibfk_2` FOREIGN KEY (`item`) REFERENCES `item` (`code`);

--
-- Constraints for table `item_warehouse`
--
ALTER TABLE `item_warehouse`
  ADD CONSTRAINT `item_warehouse_ibfk_1` FOREIGN KEY (`item`) REFERENCES `item` (`code`);

--
-- Constraints for table `warehousing_order_type`
--
ALTER TABLE `warehousing_order_type`
  ADD CONSTRAINT `warehousing_order_type_ibfk_1` FOREIGN KEY (`inventory_transaction_type_id`) REFERENCES `enum_inventory_transaction_type` (`id`);

--
-- Constraints for table `warehousing_procedure`
--
ALTER TABLE `warehousing_procedure`
  ADD CONSTRAINT `warehousing_procedure_ibfk_1` FOREIGN KEY (`procedure_type_id`) REFERENCES `enum_procedure_type` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
