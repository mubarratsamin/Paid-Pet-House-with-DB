-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Sep 29, 2024 at 10:00 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paidpethouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `petName` varchar(100) DEFAULT NULL,
  `petType` varchar(50) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `recipient_number` varchar(20) DEFAULT NULL,
  `payment_method` text DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `sender_number` varchar(20) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `billing`
--

INSERT INTO `billing` (`id`, `name`, `petName`, `petType`, `duration`, `total_amount`, `recipient_number`, `payment_method`, `transaction_id`, `sender_number`, `payment_date`) VALUES
(17, 'Mubarrat Muammar', 'Mustakim', 'dog', 10, '2000.00', '01321868891', '0', 'o7qwed23', '0125543655', '2024-09-28 13:20:00'),
(18, 'Mubarrat Muammar', 'Siri', 'dog', 220, '44000.00', '01321868891', '0', '7834oe2d', '0125543655', '2024-09-29 06:28:38');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `petType` enum('dog','cat') NOT NULL,
  `breed` varchar(100) NOT NULL,
  `duration` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `petPhoto` blob DEFAULT NULL,
  `petName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `name`, `address`, `phone`, `petType`, `breed`, `duration`, `created_at`, `petPhoto`, `petName`) VALUES
(14, 'Mubarrat Muammar', 'Akubdandi, Boalkhali, Chattogram', '01321868891', 'dog', 'Husky', 220, '2024-09-29 06:28:26', 0x2e2f75706c6f6164732f6875732e6a706567, 'Siri');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(15) NOT NULL,
  `location` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `number`, `location`, `start_time`, `end_time`) VALUES
(1, 'Samin', '01321868891', 'Ctg', '08:30:00', '09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('Accessory','Food') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `image` blob NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `type`, `price`, `description`, `image`, `created_at`) VALUES
(1, 'Collar Belt for Dogs', 'Accessory', '1500.00', 'Trendy dog collar with studs!', 0x75706c6f6164732f636f6c6c61722062656c7420666f7220646f672e6a7067, '2024-09-28 19:39:08'),
(5, 'Whiskas Adult (1+ Years) Dry Cat Food', 'Food', '999.00', 'Ocean Fish Flavour, 3 kg, Contains 41 Essential Nutrients, Complete & Balanced Nutrition for Adult Cats', 0x75706c6f6164732f63617420666f6f642e6a7067, '2024-09-29 07:36:38'),
(6, 'ZOIVANE Brush for Hair', 'Accessory', '299.00', 'Dog & Cat Accessories, Cat Brush, Dog Hair Brush, Dog Bathing Brush, Pet Brush, Dog Brush for Labrador, German Shepherd, Golden Retriever - Any Color (Pack of 1)', 0x75706c6f6164732f62727573682e6a7067, '2024-09-29 07:39:18'),
(9, '2 Pcs Cat Collar Belt', 'Accessory', '249.00', 'Cute Pink Flower Cat Belt with Bell Indoor & Outdoor Use Cat Collar with Name Tag Adjustable Kitten Collar Cat Neck Band Accessories for Cats', 0x75706c6f6164732f6361742062656c742e6a7067, '2024-09-29 07:54:03'),
(10, '2 Pcs Cat Collar Belt', 'Accessory', '249.00', 'Cute Pink Flower Cat Belt with Bell Indoor & Outdoor Use Cat Collar with Name Tag Adjustable Kitten Collar Cat Neck Band Accessories for Cats', 0x75706c6f6164732f6361742062656c742e6a7067, '2024-09-29 07:55:59');

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `payment_method` enum('bkash','nagad','rocket') NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `sender_number` varchar(20) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `registrants`
--

CREATE TABLE `registrants` (
  `id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` text NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `registrants`
--

INSERT INTO `registrants` (`id`, `Name`, `Email`, `Password`, `registration_date`) VALUES
(0, 'Muammar Mubarrat Samin', 'mubarratsam@gmail.com', 'hhhh', '2024-09-28 13:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `petName` varchar(100) NOT NULL,
  `review` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
