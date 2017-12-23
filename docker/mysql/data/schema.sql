-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2017 at 06:47 PM
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
-- Table structure for table `activities_by_warehousing_procedure`
--

CREATE TABLE `activities_by_warehousing_procedure` (
  `id` int(11) NOT NULL,
  `warehousing_procedure_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `activity_number` int(11) NOT NULL,
  `applicable` tinyint(1) NOT NULL,
  `automatic` tinyint(1) NOT NULL,
  `label_printing` tinyint(1) NOT NULL,
  `label_copies` int(11) NOT NULL,
  `label_layout_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `id` int(11) NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`id`, `description`, `activity_type_id`) VALUES
  (1, 'Warehouse Inspection', 1),
  (2, 'Print Goods Received Note', 2),
  (3, 'Warehouse Receipt', 2),
  (4, 'Confirm Receipt', 2),
  (5, 'Confirm Shipment', 4),
  (6, 'Print Bill Of Lading', 4),
  (7, 'Print Packing Slips', 4),
  (8, 'Print Delivery Notes', 4);

-- --------------------------------------------------------

--
-- Table structure for table `activity_type`
--

CREATE TABLE `activity_type` (
  `id` int(11) NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `activity_type`
--

INSERT INTO `activity_type` (`id`, `description`) VALUES
  (1, 'Outbound'),
  (2, 'Receipt'),
  (4, 'Shipment');

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
  `id` int(11) NOT NULL,
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rounding_factor` double(10,6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `first_free_number`
--

CREATE TABLE `first_free_number` (
  `id` int(11) NOT NULL,
  `number_group_id` int(11) NOT NULL,
  `series` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_free_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transaction_type`
--

CREATE TABLE `inventory_transaction_type` (
  `id` int(11) NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `inventory_transaction_type`
--

INSERT INTO `inventory_transaction_type` (`id`, `description`) VALUES
  (1, 'Receipt'),
  (2, 'Issue'),
  (3, 'Transfer');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_valuation_of_positive_adjustment`
--

CREATE TABLE `inventory_valuation_of_positive_adjustment` (
  `id` int(11) NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `inventory_valuation_of_positive_adjustment`
--

INSERT INTO `inventory_valuation_of_positive_adjustment` (`id`, `description`) VALUES
  (1, 'Standard Cost'),
  (2, 'Inventory Value');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ean` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_type_id` int(11) NOT NULL,
  `inventory_unit_id` int(11) NOT NULL,
  `weight` double(19,4) NOT NULL,
  `weight_unit_id` int(11) NOT NULL,
  `lot_controlled` tinyint(1) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `updated_datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `item_data_by_warehouse`
--

CREATE TABLE `item_data_by_warehouse` (
  `id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `reorder_point` double(19,4) NOT NULL,
  `negative_inventory_allowed` tinyint(1) NOT NULL,
  `obsolete` tinyint(1) NOT NULL,
  `exclude_from_cycle_counting` tinyint(1) NOT NULL,
  `valuation_method_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_image`
--

CREATE TABLE `item_image` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `path` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `item_inventory_by_warehouse`
--

CREATE TABLE `item_inventory_by_warehouse` (
  `id` int(11) NOT NULL,
  `warehouse_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `on_hand` double(19,4) NOT NULL,
  `on_blocked` double(19,4) NOT NULL,
  `on_order` double(19,4) NOT NULL,
  `on_allocated` double(19,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_type`
--

CREATE TABLE `item_type` (
  `id` int(11) NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_type`
--

INSERT INTO `item_type` (`id`, `description`) VALUES
  (1, 'Purchased'),
  (2, 'Manufactured');

-- --------------------------------------------------------

--
-- Table structure for table `item_warehouse`
--

CREATE TABLE `item_warehouse` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `outbound_method_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `label_layout`
--

CREATE TABLE `label_layout` (
  `id` int(11) NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `number_group`
--

CREATE TABLE `number_group` (
  `id` int(11) NOT NULL,
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `series_length` int(11) NOT NULL,
  `in_use` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `outbound_method`
--

CREATE TABLE `outbound_method` (
  `id` int(11) NOT NULL,
  `description` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `outbound_method`
--

INSERT INTO `outbound_method` (`id`, `description`) VALUES
  (1, 'LIFO'),
  (2, 'FIFO');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_parameter`
--

CREATE TABLE `receipt_parameter` (
  `id` int(11) NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_datetime` datetime NOT NULL,
  `first_free_number_id` int(11) NOT NULL,
  `line_step_size` int(11) NOT NULL,
  `enable_receipt_history` tinyint(1) NOT NULL,
  `inventory_valuation_of_positive_adjustment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `valuation_method`
--

CREATE TABLE `valuation_method` (
  `id` int(11) NOT NULL,
  `description` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Dumping data for table `valuation_method`
--

INSERT INTO `valuation_method` (`id`, `description`) VALUES
  (1, 'Standard Cost'),
  (2, 'MAUC'),
  (3, 'FIFO'),
  (4, 'LIFO'),
  (5, 'Lot Price');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse`
--

CREATE TABLE `warehouse` (
  `id` int(11) NOT NULL,
  `code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `negative_inventory_allowed` tinyint(1) NOT NULL,
  `manual_adjustment_allowed` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_master_data_parameter`
--

CREATE TABLE `warehouse_master_data_parameter` (
  `id` int(11) NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_datetime` datetime NOT NULL,
  `lot_control_in_use` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehousing_order_parameter`
--

CREATE TABLE `warehousing_order_parameter` (
  `id` int(11) NOT NULL,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_datetime` datetime NOT NULL,
  `first_free_number_id` int(11) NOT NULL,
  `line_step_size` int(11) NOT NULL,
  `set_step_size` int(11) NOT NULL,
  `enable_warehouse_order_history` tinyint(1) NOT NULL,
  `enable_inbound_line_lots_history` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehousing_order_type`
--

CREATE TABLE `warehousing_order_type` (
  `id` int(11) NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inventory_transaction_type_id` int(11) NOT NULL,
  `receipt_procedure_id` int(11) DEFAULT NULL,
  `inspection_procedure_id` int(11) DEFAULT NULL,
  `shipment_procedure_id` int(11) DEFAULT NULL,
  `generate_lots_automatically` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehousing_procedure`
--

CREATE TABLE `warehousing_procedure` (
  `id` int(11) NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `procedure_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehousing_procedure_type`
--

CREATE TABLE `warehousing_procedure_type` (
  `id` int(11) NOT NULL,
  `description` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=COMPACT;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities_by_warehousing_procedure`
--
ALTER TABLE `activities_by_warehousing_procedure`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `combined_1` (`warehousing_procedure_id`,`activity_id`) USING BTREE,
  ADD KEY `label_layout_id` (`label_layout_id`),
  ADD KEY `warehousing_procedure_id` (`warehousing_procedure_id`) USING BTREE,
  ADD KEY `activity_id` (`activity_id`) USING BTREE;

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_type_id` (`activity_type_id`);

--
-- Indexes for table `activity_type`
--
ALTER TABLE `activity_type`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `first_free_number`
--
ALTER TABLE `first_free_number`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `combined_1` (`number_group_id`,`series`) USING BTREE;

--
-- Indexes for table `inventory_transaction_type`
--
ALTER TABLE `inventory_transaction_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_valuation_of_positive_adjustment`
--
ALTER TABLE `inventory_valuation_of_positive_adjustment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD UNIQUE KEY `ean` (`ean`),
  ADD KEY `item_type_id` (`item_type_id`),
  ADD KEY `inventory_unit_id` (`inventory_unit_id`) USING BTREE,
  ADD KEY `weight_unit_id` (`weight_unit_id`) USING BTREE;

--
-- Indexes for table `item_data_by_warehouse`
--
ALTER TABLE `item_data_by_warehouse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `combined_1` (`warehouse_id`,`item_id`) USING BTREE,
  ADD KEY `warehouse_id` (`warehouse_id`) USING BTREE,
  ADD KEY `item_id` (`item_id`) USING BTREE;

--
-- Indexes for table `item_image`
--
ALTER TABLE `item_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `item_inventory_by_warehouse`
--
ALTER TABLE `item_inventory_by_warehouse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `combined_1` (`warehouse_id`,`item_id`) USING BTREE,
  ADD KEY `item_id` (`item_id`) USING BTREE,
  ADD KEY `warehouse_id` (`warehouse_id`) USING BTREE;

--
-- Indexes for table `item_type`
--
ALTER TABLE `item_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_warehouse`
--
ALTER TABLE `item_warehouse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_id` (`item_id`),
  ADD KEY `outbound_method_id` (`outbound_method_id`);

--
-- Indexes for table `label_layout`
--
ALTER TABLE `label_layout`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `number_group`
--
ALTER TABLE `number_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `outbound_method`
--
ALTER TABLE `outbound_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_parameter`
--
ALTER TABLE `receipt_parameter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_valuation_of_positive_adjustment_id` (`inventory_valuation_of_positive_adjustment_id`),
  ADD KEY `first_free_number_id` (`first_free_number_id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `valuation_method`
--
ALTER TABLE `valuation_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse`
--
ALTER TABLE `warehouse`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `warehouse_master_data_parameter`
--
ALTER TABLE `warehouse_master_data_parameter`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehousing_order_parameter`
--
ALTER TABLE `warehousing_order_parameter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `number_group_id` (`first_free_number_id`);

--
-- Indexes for table `warehousing_order_type`
--
ALTER TABLE `warehousing_order_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_transaction_type_id` (`inventory_transaction_type_id`),
  ADD KEY `combined_1` (`receipt_procedure_id`,`inspection_procedure_id`,`shipment_procedure_id`) USING BTREE,
  ADD KEY `inspection_procedure_id` (`inspection_procedure_id`),
  ADD KEY `shipment_procedure_id` (`shipment_procedure_id`),
  ADD KEY `receipt_procedure_id` (`receipt_procedure_id`);

--
-- Indexes for table `warehousing_procedure`
--
ALTER TABLE `warehousing_procedure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `procedure_type_id` (`procedure_type_id`);

--
-- Indexes for table `warehousing_procedure_type`
--
ALTER TABLE `warehousing_procedure_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities_by_warehousing_procedure`
--
ALTER TABLE `activities_by_warehousing_procedure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `activity_type`
--
ALTER TABLE `activity_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
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
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `first_free_number`
--
ALTER TABLE `first_free_number`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inventory_transaction_type`
--
ALTER TABLE `inventory_transaction_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `inventory_valuation_of_positive_adjustment`
--
ALTER TABLE `inventory_valuation_of_positive_adjustment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_data_by_warehouse`
--
ALTER TABLE `item_data_by_warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_image`
--
ALTER TABLE `item_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_inventory_by_warehouse`
--
ALTER TABLE `item_inventory_by_warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `item_type`
--
ALTER TABLE `item_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `item_warehouse`
--
ALTER TABLE `item_warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `label_layout`
--
ALTER TABLE `label_layout`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `number_group`
--
ALTER TABLE `number_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `outbound_method`
--
ALTER TABLE `outbound_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `receipt_parameter`
--
ALTER TABLE `receipt_parameter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `valuation_method`
--
ALTER TABLE `valuation_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `warehouse`
--
ALTER TABLE `warehouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `warehouse_master_data_parameter`
--
ALTER TABLE `warehouse_master_data_parameter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `warehousing_order_parameter`
--
ALTER TABLE `warehousing_order_parameter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `warehousing_order_type`
--
ALTER TABLE `warehousing_order_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `warehousing_procedure`
--
ALTER TABLE `warehousing_procedure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `warehousing_procedure_type`
--
ALTER TABLE `warehousing_procedure_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities_by_warehousing_procedure`
--
ALTER TABLE `activities_by_warehousing_procedure`
  ADD CONSTRAINT `activities_by_warehousing_procedure_ibfk_1` FOREIGN KEY (`warehousing_procedure_id`) REFERENCES `warehousing_procedure` (`id`),
  ADD CONSTRAINT `activities_by_warehousing_procedure_ibfk_2` FOREIGN KEY (`label_layout_id`) REFERENCES `label_layout` (`id`),
  ADD CONSTRAINT `activities_by_warehousing_procedure_ibfk_3` FOREIGN KEY (`activity_id`) REFERENCES `activity` (`id`);

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`activity_type_id`) REFERENCES `activity_type` (`id`);

--
-- Constraints for table `first_free_number`
--
ALTER TABLE `first_free_number`
  ADD CONSTRAINT `first_free_number_ibfk_1` FOREIGN KEY (`number_group_id`) REFERENCES `number_group` (`id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`item_type_id`) REFERENCES `item_type` (`id`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`inventory_unit_id`) REFERENCES `unit` (`id`),
  ADD CONSTRAINT `item_ibfk_3` FOREIGN KEY (`weight_unit_id`) REFERENCES `unit` (`id`);

--
-- Constraints for table `item_data_by_warehouse`
--
ALTER TABLE `item_data_by_warehouse`
  ADD CONSTRAINT `item_data_by_warehouse_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`id`),
  ADD CONSTRAINT `item_data_by_warehouse_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Constraints for table `item_image`
--
ALTER TABLE `item_image`
  ADD CONSTRAINT `item_image_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Constraints for table `item_inventory_by_warehouse`
--
ALTER TABLE `item_inventory_by_warehouse`
  ADD CONSTRAINT `item_inventory_by_warehouse_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouse` (`id`),
  ADD CONSTRAINT `item_inventory_by_warehouse_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`);

--
-- Constraints for table `item_warehouse`
--
ALTER TABLE `item_warehouse`
  ADD CONSTRAINT `item_warehouse_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `item` (`id`),
  ADD CONSTRAINT `item_warehouse_ibfk_2` FOREIGN KEY (`outbound_method_id`) REFERENCES `outbound_method` (`id`);

--
-- Constraints for table `receipt_parameter`
--
ALTER TABLE `receipt_parameter`
  ADD CONSTRAINT `receipt_parameter_ibfk_1` FOREIGN KEY (`first_free_number_id`) REFERENCES `first_free_number` (`id`),
  ADD CONSTRAINT `receipt_parameter_ibfk_2` FOREIGN KEY (`inventory_valuation_of_positive_adjustment_id`) REFERENCES `inventory_valuation_of_positive_adjustment` (`id`);

--
-- Constraints for table `warehousing_order_parameter`
--
ALTER TABLE `warehousing_order_parameter`
  ADD CONSTRAINT `warehousing_order_parameter_ibfk_1` FOREIGN KEY (`first_free_number_id`) REFERENCES `first_free_number` (`id`);

--
-- Constraints for table `warehousing_order_type`
--
ALTER TABLE `warehousing_order_type`
  ADD CONSTRAINT `warehousing_order_type_ibfk_1` FOREIGN KEY (`inventory_transaction_type_id`) REFERENCES `inventory_transaction_type` (`id`),
  ADD CONSTRAINT `warehousing_order_type_ibfk_2` FOREIGN KEY (`receipt_procedure_id`) REFERENCES `warehousing_procedure` (`id`),
  ADD CONSTRAINT `warehousing_order_type_ibfk_3` FOREIGN KEY (`inspection_procedure_id`) REFERENCES `warehousing_procedure` (`id`),
  ADD CONSTRAINT `warehousing_order_type_ibfk_4` FOREIGN KEY (`shipment_procedure_id`) REFERENCES `warehousing_procedure` (`id`);

--
-- Constraints for table `warehousing_procedure`
--
ALTER TABLE `warehousing_procedure`
  ADD CONSTRAINT `warehousing_procedure_ibfk_1` FOREIGN KEY (`procedure_type_id`) REFERENCES `warehousing_procedure_type` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
