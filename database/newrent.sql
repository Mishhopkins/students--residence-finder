-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2024 at 04:49 PM
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
-- Database: `newrent`
--

-- --------------------------------------------------------

--
-- Table structure for table `cmps`
--

CREATE TABLE `cmps` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `cmp` varchar(200) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `fullname` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cmps`
--

INSERT INTO `cmps` (`id`, `name`, `cmp`, `username`, `fullname`) VALUES
(1, 'f', 'f', 'admin', 'Mahantesh Kumbar');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `student_id`, `amount_paid`, `sender`, `balance`, `status`) VALUES
(13, 12, 10000.00, 'eudia', -10000.00, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `room_rental_registrations`
--

CREATE TABLE `room_rental_registrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `fullname` varchar(191) NOT NULL,
  `mobile` varchar(191) NOT NULL,
  `alternat_mobile` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `country` varchar(191) NOT NULL,
  `location` varchar(191) NOT NULL,
  `city` varchar(191) NOT NULL,
  `landmark` varchar(191) NOT NULL,
  `property_type` varchar(50) DEFAULT NULL,
  `rent` varchar(191) NOT NULL,
  `sale` varchar(190) DEFAULT NULL,
  `deposit` varchar(191) NOT NULL,
  `plot_number` varchar(191) NOT NULL,
  `rooms` varchar(100) DEFAULT NULL,
  `address` varchar(191) NOT NULL,
  `accommodation` varchar(191) NOT NULL,
  `description` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `open_for_sharing` varchar(191) DEFAULT NULL,
  `other` varchar(191) DEFAULT NULL,
  `vacant` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `user_id` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_rental_registrations`
--

INSERT INTO `room_rental_registrations` (`id`, `fullname`, `mobile`, `alternat_mobile`, `email`, `country`, `location`, `city`, `landmark`, `property_type`, `rent`, `sale`, `deposit`, `plot_number`, `rooms`, `address`, `accommodation`, `description`, `image`, `open_for_sharing`, `other`, `vacant`, `created_at`, `updated_at`, `user_id`) VALUES
(17, 'Mishael Plots', '0178565489', '1245789621', 'whitehouses@gmail.com', 'Kenya', 'mombasa', 'Mombasa', 'Mshomoroni Police station', 'bedsitter', '5000', '1000000', '10000', '202', '1B/2k', 'PO. Box 801, 80100', 'kitchen shelf, dish washing sink', 'Wifi installed payable per Month', 'uploads/white house.jpg', NULL, NULL, 0, '2024-02-19 09:28:06', '2024-02-19 09:28:06', 7),
(16, 'eudia moraa', '0233330220', '', 'eudiamomanyi@gmail.com', 'Kenya', 'Chepilat', 'Nairobi', 'times towers ', 'bedsitter', '20000', '1000000', '10000', '001', '1B', 'PO. Box 801, 20406', 'kitchen shelf, dish washing sink', 'free Wifi', 'uploads/IMG-20211116-WA0015.jpg', NULL, NULL, 0, '2024-02-12 12:53:43', '2024-02-12 12:53:43', 8);

-- --------------------------------------------------------

--
-- Table structure for table `room_rental_registrations_apartment`
--

CREATE TABLE `room_rental_registrations_apartment` (
  `id` int(10) UNSIGNED NOT NULL,
  `fullname` varchar(191) NOT NULL,
  `mobile` varchar(191) NOT NULL,
  `alternat_mobile` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `country` varchar(191) NOT NULL,
  `location` varchar(191) NOT NULL,
  `city` varchar(191) NOT NULL,
  `landmark` varchar(191) NOT NULL,
  `property_type` varchar(50) DEFAULT NULL,
  `rent` varchar(191) NOT NULL,
  `deposit` varchar(191) NOT NULL,
  `plot_number` varchar(191) NOT NULL,
  `apartment_name` varchar(100) DEFAULT NULL,
  `ap_number_of_plats` varchar(100) DEFAULT NULL,
  `rooms` varchar(100) DEFAULT NULL,
  `floor` varchar(100) DEFAULT NULL,
  `purpose` varchar(100) DEFAULT NULL,
  `own` varchar(100) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `address` varchar(191) NOT NULL,
  `accommodation` varchar(191) NOT NULL,
  `description` varchar(191) NOT NULL,
  `image` varchar(191) DEFAULT NULL,
  `open_for_sharing` varchar(191) DEFAULT NULL,
  `other` varchar(191) DEFAULT NULL,
  `vacant` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `user_id` int(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_rental_registrations_apartment`
--

INSERT INTO `room_rental_registrations_apartment` (`id`, `fullname`, `mobile`, `alternat_mobile`, `email`, `country`, `location`, `city`, `landmark`, `property_type`, `rent`, `deposit`, `plot_number`, `apartment_name`, `ap_number_of_plats`, `rooms`, `floor`, `purpose`, `own`, `area`, `address`, `accommodation`, `description`, `image`, `open_for_sharing`, `other`, `vacant`, `created_at`, `updated_at`, `user_id`) VALUES
(3, 'baroda 1', '0718045860', '0718045860', 'barodarentals@gmail.com', 'Kenya', 'Chepilat', 'Nairobi', 'near Paramount Building', '5_bedroom', '60,000', '20222', '78 nh', 'Main apartment', '101', '2bhk', '5th', 'Residential', 'owner', '1sqr feet', '20406,Sotik', 'wifi', 'well ', 'uploads/Jellyfish.jpg', NULL, NULL, 1, '2018-04-04 11:20:56', '2018-04-04 11:20:56', 1),
(4, 'EUNICE TOWERS', '0233330222', '0718045860', 'eudiamomanyi@gmail.com', 'Kenya', 'kamataka', 'Nairobi', 'times towers ', '4_bedroom', '20000', '10000', '002', 'EUNICE ', '001', '2BK', 'Ground Floor', 'Residential', 'owner', 'SOTIK', 'PO. Box 801, 20406', 'BALCONY', 'SO COOL', 'uploads/254708695285_status_182b21d4740c46049aaf40c2546dd69e.jpg', NULL, NULL, 1, '2024-02-12 19:53:40', '2024-02-12 19:53:40', 8),
(5, 'eddy Buildings', '0714578922', '0718045860', 'eudiamomanyi@gmail.com', 'Kenya', 'Chepilat', 'Nairobi', 'gorofa refu', '2_bedroom', '20000', '10000', '002', 'EUNICE ', '001', 'f24', '2nd', 'Commercial', 'rented', 'SOTIK', 'PO. Box 801, 20406', 'BALCONY', 'SO COOL', 'uploads/white house.jpg', NULL, NULL, 0, '2024-02-22 13:40:02', '2024-02-22 13:40:02', 7),
(6, 'baroda2', '0214567890', '0123456789', 'barodarentals@gmail.com', 'Kenya', 'Chepilat', 'Nairobi', 'chepilat highway', '2_bedroom', '20000', '10000', '002', 'baroda', '002', '24', '3rd', 'Commercial', 'owner', 'SOTIK', 'Sotik', 'BALCONY', 'SO COOL', 'uploads/white house.jpg', NULL, NULL, 1, '2024-02-24 15:02:47', '2024-02-24 15:02:47', 8);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `telephone_number` varchar(20) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `property_id`, `telephone_number`, `first_name`, `last_name`, `gender`, `email`) VALUES
(12, 16, '0718045860', 'Mishael', 'Momanyi', 'male', 'mishaelmorara@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `fullname` varchar(191) NOT NULL,
  `mobile` varchar(191) NOT NULL,
  `username` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `role` varchar(100) DEFAULT 'user',
  `status` int(1) NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `mobile`, `username`, `email`, `password`, `created_at`, `updated_at`, `role`, `status`) VALUES
(1, 'Msavi Hellen', '9879879787', 'admin', 'admin@admin.com', 'admin', NULL, NULL, 'admin', 1),
(2, 'Karenny Jebet', '56456565', 'Karen', 'karen@gmail.com', 'karen', '2024-02-08 06:53:53', '2024-02-06 05:53:53', 'user', 1),
(7, 'Mishael Momanyi', '0718045860', 'mishael', 'mishaelmorara@gmail.com', 'mishael20', '2024-02-10 16:56:45', '2024-02-10 16:56:45', 'user', 1),
(8, 'eudia moraa', '0233330220', 'eduiam', 'eudiamomanyi@gmail.com', 'eudia20', '2024-02-12 11:26:26', '2024-02-12 11:26:26', 'user', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cmps`
--
ALTER TABLE `cmps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_rental_registrations`
--
ALTER TABLE `room_rental_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_rental_registrations_mobile_unique` (`mobile`),
  ADD UNIQUE KEY `room_rental_registrations_email_unique` (`email`);

--
-- Indexes for table `room_rental_registrations_apartment`
--
ALTER TABLE `room_rental_registrations_apartment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_mobile_unique` (`mobile`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cmps`
--
ALTER TABLE `cmps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `room_rental_registrations`
--
ALTER TABLE `room_rental_registrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `room_rental_registrations_apartment`
--
ALTER TABLE `room_rental_registrations_apartment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
