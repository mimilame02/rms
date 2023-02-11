-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2023 at 04:47 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `email`, `password`, `type`, `created_at`, `updated_at`) VALUES
(1, 'admin@gmail.com', 'admin', 'admin', '2023-02-10 03:07:28', '2023-02-10 03:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` int(11) NOT NULL,
  `feature_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `feature_name`) VALUES
(1, 'car parking'),
(2, 'motorcycle parking'),
(3, 'balcony'),
(4, 'gym'),
(5, 'internet'),
(6, 'garden'),
(7, 'alarm'),
(8, 'doorbell'),
(9, 'common bathroom'),
(10, 'laundry'),
(11, 'allow pets'),
(12, 'allow smoking');

-- --------------------------------------------------------

--
-- Table structure for table `landlord`
--

CREATE TABLE `landlord` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `city` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `identification_document` varchar(100) NOT NULL,
  `emergency_contact_person` varchar(100) NOT NULL,
  `emergency_contact_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `landlord`
--

INSERT INTO `landlord` (`id`, `first_name`, `last_name`, `email`, `contact_no`, `address`, `city`, `province`, `zip_code`, `identification_document`, `emergency_contact_person`, `emergency_contact_number`) VALUES
(1, 'Pink', 'Salahuddin', 'pink.s@gmail.com', '09123456789', 'Baliwasan', 'Zamboanga', 'Zamboanga Del Sur', '7000', '', 'Fatima Dhoevia Racquiza Abdulhammid', '09123456789');

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE `property` (
  `id` int(11) NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `landlord_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `property_description` text NOT NULL,
  `features_description` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`id`, `property_name`, `landlord_id`, `address`, `city`, `province`, `zip_code`, `property_description`, `features_description`, `image_path`) VALUES
(1, 'Apartment1', 1, 'Baliwasan', 'Zamboanga', 'Zamboanga Del Sur', '7000', 'Two-stories apartment with 5 doors,', 'Accommodates: \r\nGarage for Car Parking,\r\nBalcony,\r\nGarden,\r\nLaundry,\r\nCommon Bathroom,\r\nAllow Pets,\r\nand\r\nAllow Smoking', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `property_features`
--

CREATE TABLE `property_features` (
  `id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `property_features`
--

INSERT INTO `property_features` (`id`, `property_id`, `feature_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_no` varchar(255) NOT NULL,
  `relationship_status` enum('single','in a relationship','married') NOT NULL,
  `type_of_household` enum('one person','couple','single parent','family','extended family') NOT NULL,
  `previous_address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `provinces` varchar(255) NOT NULL,
  `zip_code` varchar(255) NOT NULL,
  `sex` enum('Male','Female') NOT NULL,
  `date_of_birth` date NOT NULL,
  `has_pet` enum('yes','no') NOT NULL,
  `number_of_pets` int(11) DEFAULT NULL,
  `type_of_pet` varchar(255) DEFAULT NULL,
  `is_smoking` enum('yes','no') NOT NULL,
  `has_vehicle` enum('car','motorcycle','others','none') NOT NULL,
  `vehicle_specification` varchar(255) DEFAULT NULL,
  `occupants` varchar(100) NOT NULL,
  `co_applicant_first_name` varchar(255) DEFAULT NULL,
  `co_applicant_last_name` varchar(255) DEFAULT NULL,
  `co_applicant_email` varchar(255) DEFAULT NULL,
  `co_applicant_contact_no` varchar(255) DEFAULT NULL,
  `status` enum('Primary','Co-Applicant') NOT NULL DEFAULT 'Primary',
  `emergency_contact_person` varchar(100) DEFAULT NULL,
  `emergency_contact_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `first_name`, `last_name`, `email`, `contact_no`, `relationship_status`, `type_of_household`, `previous_address`, `city`, `provinces`, `zip_code`, `sex`, `date_of_birth`, `has_pet`, `number_of_pets`, `type_of_pet`, `is_smoking`, `has_vehicle`, `vehicle_specification`, `occupants`, `co_applicant_first_name`, `co_applicant_last_name`, `co_applicant_email`, `co_applicant_contact_no`, `status`, `emergency_contact_person`, `emergency_contact_number`) VALUES
(1, 'Roselyn', 'Tarroza', 'tenant@gmail.com', '09123456789', 'single', 'one person', 'Lunzuran', 'Zamboanga', 'Zamboanga Del Sur', '7000', 'Female', '2023-02-10', 'yes', 1, 'dog', 'no', 'car', NULL, '', NULL, NULL, NULL, NULL, 'Primary', 'Mother', '09123456789');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landlord`
--
ALTER TABLE `landlord`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
  ADD PRIMARY KEY (`id`),
  ADD KEY `landlord_id` (`landlord_id`);

--
-- Indexes for table `property_features`
--
ALTER TABLE `property_features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `property_id` (`property_id`),
  ADD KEY `feature_id` (`feature_id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `landlord`
--
ALTER TABLE `landlord`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `property_features`
--
ALTER TABLE `property_features`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `property`
--
ALTER TABLE `property`
  ADD CONSTRAINT `property_ibfk_1` FOREIGN KEY (`landlord_id`) REFERENCES `landlord` (`id`);

--
-- Constraints for table `property_features`
--
ALTER TABLE `property_features`
  ADD CONSTRAINT `property_features_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `property` (`id`),
  ADD CONSTRAINT `property_features_ibfk_2` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
