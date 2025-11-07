-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2025 at 01:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffeedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `sur_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `loyalty_points` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `users_id`, `first_name`, `sur_name`, `email`, `phone`, `loyalty_points`, `created_at`) VALUES
(6, 14, 'test2', 'test2', 'test2@gmail.com', NULL, 0, '2025-10-23 04:53:28'),
(7, 15, 'ew', 'ewew', 'crcfr@gmail.com', NULL, 0, '2025-11-06 13:51:15'),
(8, 16, 'test', 'est', 'romelduran@gmail.com', NULL, 0, '2025-11-07 00:19:43');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `sur_name` varchar(100) DEFAULT NULL,
  `hourly_rate` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `users_id`, `first_name`, `sur_name`, `hourly_rate`, `created_at`, `email`) VALUES
(1, NULL, NULL, NULL, NULL, '2025-10-20 11:12:44', NULL),
(5, 13, 'test1', 'test1', NULL, '2025-10-23 04:22:28', 'test1@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `inventory_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity_in_stock` int(11) DEFAULT 0,
  `reorder_lvl` int(11) DEFAULT 0,
  `last_update` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `availability` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`inventory_id`, `product_id`, `quantity_in_stock`, `reorder_lvl`, `last_update`, `availability`) VALUES
(28, 30, 200, 200, '2025-11-07 08:21:33', 1),
(29, 31, 200, 200, '2025-11-07 08:21:43', 1),
(30, 32, 100, 100, '2025-11-07 08:21:14', 1),
(31, 33, 200, 200, '2025-11-07 08:21:25', 1),
(32, 34, 200, 200, '2025-11-07 08:21:38', 1),
(33, 35, 100, 200, '2025-11-07 08:21:19', 1),
(34, 36, 200, 200, '2025-11-07 08:21:29', 1);

--
-- Triggers `inventory`
--
DELIMITER $$
CREATE TRIGGER `update_availability` BEFORE UPDATE ON `inventory` FOR EACH ROW SET NEW.availability = (NEW.quantity_in_stock > 0),
    NEW.last_update  = NOW()
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `menu_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `availability` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orders_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orders_id`, `customer_id`, `employee_id`, `order_date`, `total_amount`, `status`) VALUES
(1, NULL, 1, '2025-10-23 23:17:22', 200.00, 'Pending'),
(2, 6, NULL, '2025-10-23 23:19:08', 2400.00, 'Completed'),
(3, 6, NULL, '2025-10-24 00:46:27', 12312.00, 'Pending'),
(4, 6, NULL, '2025-10-24 00:47:20', 1200.00, 'Pending'),
(5, 6, NULL, '2025-10-24 00:49:40', 12312.00, 'Pending'),
(6, 6, NULL, '2025-10-24 00:52:41', 12312.00, 'Pending'),
(7, 6, NULL, '2025-10-24 00:53:57', 12312.00, 'Pending'),
(8, 6, NULL, '2025-10-24 00:55:02', 13512.00, 'Pending'),
(9, 6, NULL, '2025-10-24 00:55:55', 98496.00, 'Pending'),
(12, NULL, 5, '2025-10-24 08:22:13', 218.00, 'Pending'),
(23, 6, NULL, '2025-11-06 20:56:22', 300.00, 'Pending'),
(24, 6, NULL, '2025-11-06 20:58:28', 200.00, 'Paid'),
(25, 6, NULL, '2025-11-06 20:58:40', 100.00, 'Paid'),
(26, 6, NULL, '2025-11-06 21:07:17', 100.00, 'Paid'),
(27, 6, NULL, '2025-11-06 21:07:59', 100.00, 'Paid'),
(28, 6, NULL, '2025-11-06 21:13:14', 100.00, 'Paid'),
(29, 6, NULL, '2025-11-06 21:14:33', 100.00, 'Paid'),
(30, 6, NULL, '2025-11-06 21:14:54', 200.00, 'Paid'),
(31, 6, NULL, '2025-11-06 21:15:42', 100.00, 'Paid'),
(32, 6, NULL, '2025-11-06 21:16:43', 100.00, 'Paid'),
(33, 6, NULL, '2025-11-06 21:23:19', 100.00, 'Paid'),
(34, 7, NULL, '2025-11-06 21:54:37', 200.00, 'Paid'),
(35, 8, NULL, '2025-11-07 08:22:22', 255.00, 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_items_id` int(11) NOT NULL,
  `orders_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `item_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_items_id`, `orders_id`, `product_id`, `quantity`, `item_price`) VALUES
(1, 1, NULL, 2, 100.00),
(2, 2, NULL, 2, 1200.00),
(3, 3, NULL, 1, 12312.00),
(4, 4, NULL, 1, 1200.00),
(5, 5, NULL, 1, 12312.00),
(6, 6, NULL, 1, 12312.00),
(7, 7, NULL, 1, 12312.00),
(8, 8, NULL, 1, 12312.00),
(9, 8, NULL, 1, 1200.00),
(10, 9, NULL, 8, 12312.00),
(11, 12, NULL, 2, 109.00),
(22, 23, NULL, 3, 100.00),
(23, 24, NULL, 2, 100.00),
(24, 25, NULL, 1, 100.00),
(25, 26, NULL, 1, 100.00),
(26, 27, NULL, 1, 100.00),
(27, 28, NULL, 1, 100.00),
(28, 29, NULL, 1, 100.00),
(29, 30, NULL, 2, 100.00),
(30, 31, NULL, 1, 100.00),
(31, 32, NULL, 1, 100.00),
(32, 33, NULL, 1, 100.00),
(33, 34, NULL, 1, 100.00),
(34, 34, NULL, 1, 100.00),
(35, 35, 34, 1, 135.00),
(36, 35, 35, 1, 120.00);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `orders_id` int(11) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`payment_id`, `orders_id`, `payment_date`, `payment_method`, `amount`, `payment_status`) VALUES
(15, 29, '2025-11-06 21:14:36', 'Cash', 100.00, 'Completed'),
(16, 30, '2025-11-06 21:14:57', 'Cash', 200.00, 'Completed'),
(17, 31, '2025-11-06 21:15:44', 'Cash', 100.00, 'Completed'),
(18, 32, '2025-11-06 21:16:46', 'Cash', 100.00, 'Completed'),
(19, 33, '2025-11-06 21:23:21', 'Cash', 100.00, 'Completed'),
(20, 33, '2025-11-06 21:24:22', 'Cash', 100.00, 'Completed'),
(21, 34, '2025-11-06 21:56:43', 'Cash', 200.00, 'Completed'),
(22, 35, '2025-11-07 08:22:28', 'Cash', 255.00, 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `availability` tinyint(1) DEFAULT 1,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `supplier_id`, `name`, `price`, `description`, `availability`, `category`, `created_at`) VALUES
(30, 1, 'Latte', 99.00, 'Espresso and milk', 1, 'Beverage', '2025-11-07 00:07:01'),
(31, 1, 'spanish latte', 115.00, 'Latte with condense', 1, 'Beverage', '2025-11-07 00:07:33'),
(32, 1, 'Americano', 90.00, 'espresso with water', 1, 'Beverage', '2025-11-07 00:07:54'),
(33, 1, 'Clubhouse', 130.00, 'sandwich', 1, 'Sandwich', '2025-11-07 00:09:03'),
(34, 1, 'Pesto oil', 135.00, 'pasta', 1, 'Pastry', '2025-11-07 00:09:42'),
(35, 1, 'cheesecake', 120.00, 'cream', 1, 'Dessert', '2025-11-07 00:10:08'),
(36, 1, 'egg sandwich', 120.00, 'tinapay', 1, 'Sandwich', '2025-11-07 00:10:34');

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `after_product_insert` AFTER INSERT ON `products` FOR EACH ROW BEGIN
    INSERT INTO inventory (product_id, quantity_in_stock, reorder_lvl, availability, last_update)
    VALUES (NEW.product_id, 0, 0, 0, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `product_inventory_status`
-- (See below for the actual view)
--
CREATE TABLE `product_inventory_status` (
`product_id` int(11)
,`name` varchar(255)
,`description` text
,`price` decimal(10,2)
,`category` varchar(100)
,`quantity_in_stock` int(11)
,`reorder_lvl` int(11)
,`availability` int(4)
,`last_update` datetime
);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `name`, `contact_number`, `contact_email`, `company`, `created_at`) VALUES
(1, 'Juan Delacruz', '09123456789', 'supplier@gmail.com', 'Supply Company', '2025-10-22 15:38:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','employee','customer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(13, 'test1', 'test1@gmail.com', '$2y$10$i3v19mSDA93xgyzjqK2hre1XifFrkpPyvzzcjzem9aogmzfUDFZhS', 'employee', '2025-10-23 04:22:28'),
(14, 'test2', 'test2@gmail.com', '$2y$10$roaSon/.0zAh2TUojccIBeRceUAdoRzaP/aObDPuuKpLjyNOM3R0a', 'customer', '2025-10-23 04:53:28'),
(15, 'testc', 'crcfr@gmail.com', '$2y$10$CqXozqjRqBTaJd23DHNVEeHRjKxxpaoFODsCPv2A.m1yDXyRfeYly', 'customer', '2025-11-06 13:51:15'),
(16, 'test3', 'romelduran@gmail.com', '$2y$10$mh3dvtasxt/hC.3skmpm9.pURJO6T.cBuvgRJde7pWppzxlgIHkk.', 'customer', '2025-11-07 00:19:43');

-- --------------------------------------------------------

--
-- Structure for view `product_inventory_status`
--
DROP TABLE IF EXISTS `product_inventory_status`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `product_inventory_status`  AS SELECT `p`.`product_id` AS `product_id`, `p`.`name` AS `name`, `p`.`description` AS `description`, `p`.`price` AS `price`, `p`.`category` AS `category`, coalesce(`i`.`quantity_in_stock`,0) AS `quantity_in_stock`, coalesce(`i`.`reorder_lvl`,0) AS `reorder_lvl`, coalesce(`i`.`availability`,0) AS `availability`, `i`.`last_update` AS `last_update` FROM (`products` `p` left join `inventory` `i` on(`p`.`product_id` = `i`.`product_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`),
  ADD UNIQUE KEY `users_id` (`users_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD UNIQUE KEY `users_id` (`users_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orders_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_items_id`),
  ADD KEY `orders_id` (`orders_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `orders_id` (`orders_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `orders_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_items_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`orders_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`orders_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
