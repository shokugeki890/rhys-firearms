-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2025 at 05:34 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
-- Dumping database structure for database: `rhys_firearms`

--
-- Database: `rhys_firearms`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sort_order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `created_at`, `sort_order`) VALUES
(1, 'firearms', '2025-10-19 04:54:42', 0),
(2, 'courses', '2025-10-19 04:54:42', 0),
(3, 'subscriptions', '2025-10-19 04:54:42', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','completed','cancelled') DEFAULT 'pending',
  `shipping_name` varchar(255) DEFAULT NULL,
  `shipping_email` varchar(255) DEFAULT NULL,
  `shipping_address` text DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_state` varchar(100) DEFAULT NULL,
  `shipping_zip` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `shipping_name`, `shipping_email`, `shipping_address`, `shipping_city`, `shipping_state`, `shipping_zip`, `created_at`) VALUES
(1, 4, 1403.99, 'completed', 'james doakes', 'jgj@gmail.com', 'Jl. Batu Tumbuh no 27A Pondok Gede Bekasi', 'Bekasi', 'Jawa Barat', '17411', '2025-11-04 03:43:44');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`) VALUES
(1, 1, 1, 1, 1299.99, '2025-11-04 03:43:44');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('subscription','course','firearms') NOT NULL,
  `category_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `type`, `category_id`, `team_id`, `price`, `description`, `created_at`) VALUES
(1, 'R-15 Tactical Rifle', 'firearms', 1, 1, 1299.99, 'Semi-automatic .223/5.56 rifle with tactical features', '2025-10-19 06:03:24'),
(2, 'RP9 9mm Pistol', 'firearms', 1, 2, 599.99, 'Compact 9mm handgun with exceptional accuracy', '2025-10-19 06:03:24'),
(3, 'R-700 Bolt Action', 'firearms', 1, 1, 899.99, 'Precision bolt-action rifle for hunting and long-range', '2025-10-19 06:03:24'),
(4, 'RS12 Tactical Shotgun', 'firearms', 1, 3, 749.99, 'Semi-automatic tactical shotgun for defense', '2025-10-19 06:03:24'),
(5, 'R-10 Compact Rifle', 'firearms', 1, 2, 1099.99, 'Lightweight compact rifle for versatile use', '2025-10-19 06:03:24'),
(6, 'RP45 .45 ACP Pistol', 'firearms', 1, 3, 649.99, 'Full-size .45 ACP with exceptional stopping power', '2025-10-19 06:03:24'),
(7, 'Basic Firearm Safety Course', 'course', 2, 2, 99.99, 'Comprehensive beginner firearm safety training', '2025-10-19 06:03:24'),
(8, 'Concealed Carry Certification', 'course', 2, 3, 199.99, 'Complete CCW certification course with range time', '2025-10-19 06:03:24'),
(9, 'Advanced Tactical Training', 'course', 2, 1, 349.99, 'Advanced marksmanship and tactical training', '2025-10-19 06:03:24'),
(10, 'Annual Range Membership', 'subscription', 3, 2, 299.99, 'Unlimited range access and training sessions', '2025-10-19 06:03:24'),
(11, 'Premium Gunsmith Service', 'subscription', 3, 1, 149.99, 'Priority gunsmithing and maintenance services', '2025-10-19 06:03:24');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `created_at`) VALUES
(1, 'John Rhys', '2025-10-19 04:54:42'),
(2, 'Sarah Chen', '2025-10-19 04:54:42'),
(3, 'Michael Rodriguez', '2025-10-19 04:54:42');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `status` enum('cart','purchased') DEFAULT 'cart',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `user_id`, `product_id`, `quantity`, `status`, `created_at`) VALUES
(1, 2, 1, 1, 'purchased', '2025-10-19 06:16:45'),
(3, 2, 5, 1, 'purchased', '2025-10-21 06:47:48'),
(4, 4, 1, 1, 'purchased', '2025-10-21 08:18:17'),
(5, 4, 2, 1, 'purchased', '2025-10-21 08:18:23'),
(6, 1, 1, 2, 'purchased', '2025-10-21 08:53:49'),
(7, 1, 3, 3, 'purchased', '2025-10-21 08:54:09'),
(8, 4, 1, 1, 'purchased', '2025-11-04 03:31:02'),
(9, 4, 1, 1, 'purchased', '2025-11-04 03:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` varchar(50) DEFAULT 'credit_card',
  `status` enum('pending','completed','refunded','failed') DEFAULT 'completed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `order_id`, `amount`, `method`, `status`, `created_at`) VALUES
(1, 4, 1, 1403.99, 'credit_card', 'completed', '2025-11-04 03:43:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@rhysfirearms.com', '$2y$10$LbvBmPWDS7/ezbZn34lsUejXxn1g47vDr9I.9gJqerE8MZxdQSXU6', 'admin', '2025-10-18 08:47:25'),
(2, 'rifqi1', 'rifqi.hafidhs@gmail.com', '$2y$10$S9dHUvhdhKy.JWqYUmugE.ULAKrMIL0n5mgRm8lRtg7gnPkrTxySO', 'user', '2025-10-18 08:48:39'),
(4, 'james doakes', 'jgj@gmail.com', '$2y$10$p5fjIsDot7gpVAGv1ae/au6ZOpwNzfFRpkwBLZ73hXQ3dAN5wir..', 'user', '2025-10-21 08:16:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transaction_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
