-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2025 at 04:02 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `milktea_nexus_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(3, 'admin', 'admin@gmail.com', '$2y$10$Uei.T3MIlMbEQDBm5AtKP.u8/f15cQQIGFtnWzx55V6LGA/oFs1K.', '2025-05-14 16:53:55');

-- --------------------------------------------------------

--
-- Table structure for table `incoming_products`
--

CREATE TABLE `incoming_products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incoming_products`
--

INSERT INTO `incoming_products` (`id`, `product_name`, `image_url`) VALUES
(2, 'latte', 'https://www.kawalingpinoy.com/wp-content/uploads/2019/07/kwek-kwek-14-1152x1536.jpg'),
(3, 'Kwek-Kwek', 'https://www.kawalingpinoy.com/wp-content/uploads/2019/07/kwek-kwek-14-1152x1536.jpg'),
(4, 'ddwadwad', 'https://i.pinimg.com/736x/c0/7f/60/c07f60bbd7accf7bae89a5167c636b21.jpg'),
(5, 'lala', 'https://www.kawalingpinoy.com/wp-content/uploads/2019/07/kwek-kwek-14-1152x1536.jpg'),
(11, 'Cappucino Coffee', 'https://i.pinimg.com/736x/33/44/2e/33442e58a74503c7cef4fc437a4ebc8e.jpg'),
(14, 'chocolate smoothie', 'https://i.pinimg.com/736x/db/dc/14/dbdc14256adf60ca6295876c91e82ac5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients_inventory`
--

CREATE TABLE `ingredients_inventory` (
  `id` int(11) NOT NULL,
  `ingredient_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients_stock`
--

CREATE TABLE `ingredients_stock` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `milk_tea_cup` int(11) DEFAULT 0,
  `powder` int(11) DEFAULT 0,
  `milk_tea_powder` int(11) DEFAULT 0,
  `pearl` int(11) DEFAULT 0,
  `milk` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients_stock`
--

INSERT INTO `ingredients_stock` (`id`, `product_id`, `milk_tea_cup`, `powder`, `milk_tea_powder`, `pearl`, `milk`, `created_at`, `product_name`) VALUES
(21, 35, 21, 0, 20, 12, 12, '2025-05-18 13:31:00', NULL),
(22, 37, 21, 0, 2112, 21, 21, '2025-05-18 13:32:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `branch` varchar(100) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `customer_name`, `order_date`, `total_amount`, `status_id`, `product_id`, `quantity`, `branch`, `location`, `status`) VALUES
(135, 5, NULL, '2025-05-16 06:25:43', 100.00, NULL, 35, 1, NULL, NULL, 'Pending'),
(136, 5, NULL, '2025-05-18 14:33:05', 1200.00, NULL, 35, 12, NULL, NULL, 'Pending'),
(137, 5, NULL, '2025-05-18 14:33:21', 1500.00, NULL, 35, 15, NULL, NULL, 'Pending'),
(138, 5, NULL, '2025-05-18 14:34:20', 100.00, NULL, 35, 1, NULL, NULL, 'Pending'),
(139, 5, NULL, '2025-05-18 15:16:23', 100.00, NULL, 35, 1, NULL, NULL, 'Pending'),
(140, 5, NULL, '2025-05-18 15:43:59', 1000.00, NULL, 35, 10, NULL, NULL, 'Pending'),
(141, 5, NULL, '2025-05-18 15:46:00', 990.00, NULL, 37, 10, NULL, NULL, 'Pending'),
(142, 5, NULL, '2025-05-18 15:46:13', 1188.00, NULL, 37, 12, NULL, NULL, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `detail_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `order_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`detail_id`, `order_id`, `product_id`, `quantity`, `price`, `order_date`) VALUES
(87, 135, 35, 1, 100.00, '2025-05-16 06:25:43'),
(88, 136, 35, 12, 100.00, '2025-05-18 14:33:05'),
(89, 137, 35, 15, 100.00, '2025-05-18 14:33:21'),
(90, 138, 35, 1, 100.00, '2025-05-18 14:34:20'),
(91, 139, 35, 1, 100.00, '2025-05-18 15:16:23'),
(92, 140, 35, 10, 100.00, '2025-05-18 15:43:59'),
(93, 141, 37, 10, 99.00, '2025-05-18 15:46:00'),
(94, 142, 37, 12, 99.00, '2025-05-18 15:46:13');

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

CREATE TABLE `order_status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`status_id`, `status_name`) VALUES
(1, 'Pending'),
(2, 'Processing'),
(3, 'Completed'),
(4, 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_url` varchar(255) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `cup_stock` int(11) DEFAULT 0,
  `powder_stock` int(11) DEFAULT 0,
  `milktea_powder_stock` int(11) DEFAULT 0,
  `pearl_stock` int(11) DEFAULT 0,
  `milk_stock` int(11) DEFAULT 0,
  `stock_quantity` int(11) DEFAULT 0,
  `milk_tea_cup` int(11) DEFAULT 0,
  `powder` int(11) DEFAULT 0,
  `milk_tea_powder` int(11) DEFAULT 0,
  `pearl` int(11) DEFAULT 0,
  `milk` int(11) DEFAULT 0,
  `cup_deduction` int(10) UNSIGNED DEFAULT 1,
  `powder_deduction` int(10) UNSIGNED DEFAULT 1,
  `pearl_deduction` int(10) UNSIGNED DEFAULT 1,
  `milk_deduction` int(10) UNSIGNED DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `price`, `created_at`, `image_url`, `product_image`, `stock`, `cup_stock`, `powder_stock`, `milktea_powder_stock`, `pearl_stock`, `milk_stock`, `stock_quantity`, `milk_tea_cup`, `powder`, `milk_tea_powder`, `pearl`, `milk`, `cup_deduction`, `powder_deduction`, `pearl_deduction`, `milk_deduction`) VALUES
(35, 'Chocolate Milk Tea', 'Milk Tea', 100.00, '2025-05-16 04:06:09', 'https://i.pinimg.com/736x/bf/82/5d/bf825d56daf06fd8c231e2464b3e5d7d.jpg', NULL, 0, 0, 0, 0, 0, 0, 60, 0, 0, 0, 0, 0, 1, 1, 1, 1),
(37, 'Formosa Taiwan Milk Tea', 'Milk Tea', 99.00, '2025-05-18 12:17:30', 'https://assets.epicurious.com/photos/629f98926e3960ec24778116/1:1/w_1920,c_limit/BubbleTea_RECIPE_052522_34811.jpg', NULL, 0, 0, 0, 0, 0, 0, 178, 0, 0, 0, 0, 0, 1, 1, 1, 1),
(38, 'Boba Milktea', 'Milk Tea', 100.00, '2025-05-18 13:05:20', 'https://i.pinimg.com/736x/03/f6/fb/03f6fb4d51076c1d5b29657c5053907b.jpg', NULL, 0, 0, 0, 0, 0, 0, 200, 0, 0, 0, 0, 0, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_ingredients`
--

CREATE TABLE `product_ingredients` (
  `product_id` int(11) DEFAULT NULL,
  `ingredient_name` varchar(50) DEFAULT NULL,
  `quantity_per_unit` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `username`, `email`, `password`, `created_at`) VALUES
(4, 'staff', 'staff', 'staff@gmail.com', '$2y$10$GFMikKrVD75YDoOrq9umuOjGgs1Z4sSeIZgtgNutRGC7YZgwvMi9m', '2025-05-14 21:31:20');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(5, 'user', 'user@gmail.com', '$2y$10$jFTwnmBvVuZnK44HAsdSH.i/UC4sj8GOjXqYjnWKJgHonXbbv6RTC', '2025-05-12 15:07:30', '2025-05-12 15:07:30'),
(6, 'shein', 'shein@gmail.com', '$2y$10$ClK7XskuC0lmv9XLLzPHWeZd0PCN0het/yPpR4BKbfCF8eLV0.kwa', '2025-05-12 15:16:20', '2025-05-12 15:16:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `incoming_products`
--
ALTER TABLE `incoming_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredients_inventory`
--
ALTER TABLE `ingredients_inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ingredients_stock`
--
ALTER TABLE `ingredients_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_product` (`product_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `fk_product_order_details_1` (`product_id`);

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `incoming_products`
--
ALTER TABLE `incoming_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ingredients_inventory`
--
ALTER TABLE `ingredients_inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredients_stock`
--
ALTER TABLE `ingredients_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_product_order_details_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_fk_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
