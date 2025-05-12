-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2025 at 02:28 PM
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
(1, 'admin', 'admin@gmail.com', '$2y$10$HqUtxhD8RVyT1c7v/nF2pa5wFh9HleA8U1LZuy2qlKzQkT5PRjBtu', '2025-05-12 07:05:22');

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
(1, 'latte', 'https://www.kawalingpinoy.com/wp-content/uploads/2019/07/kwek-kwek-14-1152x1536.jpg'),
(2, 'latte', 'https://www.kawalingpinoy.com/wp-content/uploads/2019/07/kwek-kwek-14-1152x1536.jpg'),
(3, 'Kwek-Kwek', 'https://www.kawalingpinoy.com/wp-content/uploads/2019/07/kwek-kwek-14-1152x1536.jpg'),
(4, 'ddwadwad', 'https://i.pinimg.com/736x/c0/7f/60/c07f60bbd7accf7bae89a5167c636b21.jpg'),
(5, 'wadaw', 'https://www.kawalingpinoy.com/wp-content/uploads/2019/07/kwek-kwek-14-1152x1536.jpg'),
(6, 'wadaw', 'https://www.kawalingpinoy.com/wp-content/uploads/2019/07/kwek-kwek-14-1152x1536.jpg');

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
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `customer_name`, `order_date`, `total_amount`, `status_id`, `product_id`, `quantity`) VALUES
(54, 1, NULL, '2025-05-12 12:14:47', 2375.00, NULL, 9, 25),
(55, 1, NULL, '2025-05-12 12:55:31', 1900.00, NULL, 11, 20),
(56, 1, NULL, '2025-05-12 12:58:30', 140.00, NULL, 14, 2),
(57, 1, NULL, '2025-05-12 13:28:25', 2500.00, NULL, 6, 25);

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
(6, 54, 9, 25, 95.00, '2025-05-12 12:14:47'),
(7, 55, 11, 20, 95.00, '2025-05-12 12:55:31'),
(8, 56, 14, 2, 70.00, '2025-05-12 12:58:30'),
(9, 57, 6, 25, 100.00, '2025-05-12 13:28:25');

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
  `stock_quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `price`, `stock_quantity`, `created_at`, `image_url`) VALUES
(6, 'chocolate Bubble Tea', 'Milk Tea', 100.00, 99, '2025-05-12 09:43:11', 'https://i.pinimg.com/736x/c0/7f/60/c07f60bbd7accf7bae89a5167c636b21.jpg'),
(9, 'Babo Milk Tea', 'Milk Tea', 95.00, 40, '2025-05-12 09:45:08', 'https://www.onebabo.com/wp-content/uploads/go-x/u/2e5dbb97-dbc7-43cd-bbd7-7355b41e8d7b/l0,t259,w1133,h755/image-768x512.jpg'),
(10, 'Purple Boba Milk Tea', 'Milk Tea', 90.00, 50, '2025-05-12 09:45:59', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTE0ZuSiF1HxidtSZ-klAAyjEGmkR-JDAiQIw&s'),
(11, 'Taro Milk Tea', 'Milk Tea', 95.00, 29, '2025-05-12 09:46:38', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSGueJoTv7m3RejdSYcWzPuLo7DeDh0B0St_g&s'),
(12, 'Strawberry Milk Tea', 'Milk Tea', 100.00, 32, '2025-05-12 09:48:13', 'https://static.wixstatic.com/media/649ddb_4f87ec2a78aa4208b0ac46e3b5916b5d~mv2.jpg/v1/fill/w_642,h_502,al_c,q_80,usm_0.66_1.00_0.01,enc_avif,quality_auto/Image-empty-state.jpg'),
(13, 'Match Milk Tea', 'Milk Tea', 95.00, 42, '2025-05-12 09:49:20', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSGbPlDTcwTQAHpO0bkdiGGAQMJhewdle_tAg&s'),
(14, 'bubble Tea', 'Milk Tea', 70.00, 20, '2025-05-12 10:58:07', 'https://img.buzzfeed.com/buzzfeed-static/static/2022-11/8/0/asset/71ba74fbe820/sub-buzz-1560-1667865696-1.jpg?downsize=700%3A%2A&output-quality=auto&output-format=auto');

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
(1, 'user', 'user@gmail.com', '$2y$10$OEX9nzC8gjZq37BxoNdGgOj4lz.MeM9u2nGSng4bm4fWqF8D5GXau', '2025-05-12 07:05:22', '2025-05-12 07:05:22');

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
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `incoming_products`
--
ALTER TABLE `incoming_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_status`
--
ALTER TABLE `order_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
