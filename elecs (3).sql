-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2024 at 04:01 PM
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
-- Database: `elecs`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `product_id`, `quantity`, `added_at`) VALUES
(1, 1, 3, 2, '2024-11-10 03:00:00'),
(2, 2, 5, 1, '2024-11-10 03:05:00'),
(3, 3, 1, 1, '2024-11-10 03:10:00'),
(4, 4, 7, 1, '2024-11-10 03:15:00'),
(5, 5, 4, 2, '2024-11-10 03:20:00'),
(6, 6, 8, 1, '2024-11-10 03:25:00'),
(7, 7, 10, 1, '2024-11-10 03:30:00'),
(8, 8, 9, 1, '2024-11-10 03:35:00'),
(9, 9, 6, 3, '2024-11-10 03:40:00'),
(10, 10, 2, 1, '2024-11-10 03:45:00'),
(11, 15, 4, 1, '2024-11-25 05:37:24');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(1, 'Máy tính xách tay'),
(2, 'Máy tính để bàn'),
(3, 'Máy tính bảng'),
(4, 'Điện thoại'),
(5, 'Phụ kiện'),
(6, 'Màn hình'),
(7, 'Máy in'),
(8, 'Thiết bị mạng'),
(9, 'Phần mềm'),
(10, 'Thiết bị chơi game');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parent_comment_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 19990000.00),
(2, 2, 2, 1, 59990000.00),
(3, 3, 4, 1, 14990000.00),
(4, 4, 5, 1, 24990000.00),
(5, 5, 8, 1, 3490000.00),
(6, 6, 7, 1, 8990000.00),
(7, 7, 3, 1, 28990000.00),
(8, 8, 6, 1, 599000.00),
(9, 9, 9, 1, 4799000.00),
(10, 10, 10, 1, 13990000.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','Shipped','Completed','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `total_amount`, `status`) VALUES
(1, 1, '2024-11-10 03:00:00', 19990000.00, ''),
(2, 2, '2024-11-10 04:00:00', 59990000.00, ''),
(3, 3, '2024-11-10 05:00:00', 14990000.00, ''),
(4, 4, '2024-11-10 06:00:00', 24990000.00, ''),
(5, 5, '2024-11-10 07:00:00', 3490000.00, ''),
(6, 6, '2024-11-10 08:00:00', 8990000.00, ''),
(7, 7, '2024-11-10 09:00:00', 28990000.00, ''),
(8, 8, '2024-11-10 10:00:00', 599000.00, ''),
(9, 9, '2024-11-10 11:00:00', 4799000.00, ''),
(10, 10, '2024-11-10 12:00:00', 13990000.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `image_url`, `stock`, `created_at`, `category_id`) VALUES
(1, 'Laptop Dell XPS 13', 'Laptop hiệu suất cao với màn hình 13 inch', 19990000.00, 'images/dell_xps_13.jpg', 10, '2024-11-11 03:00:00', 1),
(2, 'MacBook Pro 16', 'Máy tính Apple với chip M1', 59990000.00, 'images/macbook_pro_16.jpg', 5, '2024-11-11 04:00:00', 1),
(3, 'HP Spectre x360', 'Laptop chuyển đổi với màn hình cảm ứng', 28990000.00, 'images/hp_spectre_x360.jpg', 8, '2024-11-11 05:00:00', 1),
(4, 'Samsung Galaxy Tab S7', 'Máy tính bảng Android cao cấp', 14990000.00, 'images/galaxy_tab_s7.jpg', 15, '2024-11-11 06:00:00', 3),
(5, 'iPhone 13', 'Điện thoại mới nhất của Apple', 24990000.00, 'images/iphone_13.jpg', 20, '2024-11-11 07:00:00', 4),
(6, 'Chuột Logitech', 'Chuột không dây', 599000.00, 'images/logitech_mouse.jpg', 50, '2024-11-11 08:00:00', 5),
(7, 'Màn hình Samsung', 'Màn hình 27 inch 4K', 8990000.00, 'images/samsung_monitor.jpg', 12, '2024-11-11 09:00:00', 6),
(8, 'Máy in Canon', 'Máy in không dây', 3490000.00, 'images/canon_printer.jpg', 9, '2024-11-11 10:00:00', 7),
(9, 'Adobe Photoshop', 'Phần mềm chỉnh sửa ảnh', 4799000.00, 'images/adobe_photoshop.jpg', 30, '2024-11-11 11:00:00', 9),
(10, 'PlayStation 5', 'Máy chơi game của Sony', 13990000.00, 'images/ps5.jpg', 7, '2024-11-11 12:00:00', 10);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `comment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`, `comment_id`) VALUES
(1, 3, NULL, 1, 'Rất tốt', '2024-11-12 03:52:06', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'nguoidung1', '123456', 'nguoidung1@example.com', '2024-11-11 03:00:00'),
(2, 'nguoidung2', '123456', 'nguoidung2@example.com', '2024-11-11 04:00:00'),
(3, 'nguoidung3', '123456', 'nguoidung3@example.com', '2024-11-11 05:00:00'),
(4, 'nguoidung4', '123456', 'nguoidung4@example.com', '2024-11-11 06:00:00'),
(5, 'nguoidung5', '123456', 'nguoidung5@example.com', '2024-11-11 07:00:00'),
(6, 'nguoidung6', '123456', 'nguoidung6@example.com', '2024-11-11 08:00:00'),
(7, 'nguoidung7', '123456', 'nguoidung7@example.com', '2024-11-11 09:00:00'),
(8, 'nguoidung8', '123456', 'nguoidung8@example.com', '2024-11-11 10:00:00'),
(9, 'nguoidung9', '123456', 'nguoidung9@example.com', '2024-11-11 11:00:00'),
(10, 'nguoidung10', '123456', 'nguoidung10@example.com', '2024-11-11 12:00:00'),
(11, 'ducthnah', '$2y$10$eYhDqBPaTR.UJ2oD4D3.meryouwo/Y5dlb3zD/P5.CJX87BxYP1Rq', 'thanhnguyen.tnnn25@gmail.com', '2024-11-12 07:20:15'),
(13, 'dmm', '$2y$10$O8CkVKLa47NlEt1q41ZLFuPe9X9wYpX8/lFFyX04oMmWw/.0nJPOi', 'okaybanoi@dmm.com', '2024-11-12 15:19:54'),
(15, 'admin', '$2y$10$ZkOaBmBR7E0FOojLh3oYWuSm09mla.ZVRjZ4rWVQgXPCSex65.a7S', 'ducthanh@hvnh.com', '2024-11-25 04:10:55'),
(16, 'ducthanh', '$2y$10$NTvGCX7pR30MCKiuVcUhtuvJXPA/YupfkzOGCyIqflcEJBEProtV.', 'hvnh@hvnh.edu.com', '2024-11-25 08:41:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_comment_id` (`parent_comment_id`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `comment_id` (`comment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`parent_comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE;

--
-- Constraints for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
