-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2023 at 08:46 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `diagnostice`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `diagnostic_centers`
--

CREATE TABLE `diagnostic_centers` (
  `center_id` int(11) NOT NULL,
  `center_name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `contact_details` varchar(255) NOT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diagnostic_centers`
--

INSERT INTO `diagnostic_centers` (`center_id`, `center_name`, `location`, `contact_details`, `manager_id`, `created_at`, `updated_at`) VALUES
(2, 'Jampur Centers', 'Jampur', '0235', 1, '2023-10-10 04:34:52', '2023-10-10 04:44:43'),
(3, 'Lahore Center', 'Near Multan Road', '234432', 2, '2023-10-10 05:00:16', '2023-10-10 05:00:16');

-- --------------------------------------------------------

--
-- Table structure for table `managers`
--

CREATE TABLE `managers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managers`
--

INSERT INTO `managers` (`id`, `name`, `email`, `password`, `status`) VALUES
(1, 'NASIR ABBAS', 'manager1@gmail.com', '123', 'approved'),
(2, 'NASIR ABBAS', 'manager@gmail.com', '123', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `contactno` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `Username`, `Email`, `contactno`, `Password`) VALUES
(5, 'nasiryt8207gmailcom', 'nasiryt.827@gmail.com', '03176526827', '123'),
(6, 'ds3', 'd2@gmail.com', '11112321566789', '123'),
(7, 'Haniya', 'haniya@gmail.com', '6852316845', '123');

-- --------------------------------------------------------

--
-- Table structure for table `resets`
--

CREATE TABLE `resets` (
  `id` int(11) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Code` varchar(10) NOT NULL,
  `Expire` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resets`
--

INSERT INTO `resets` (`id`, `Email`, `Code`, `Expire`) VALUES
(1, 'nasiryt.827@gmail.com', '18626', 1689773257);

-- --------------------------------------------------------

--
-- Table structure for table `test_categories`
--

CREATE TABLE `test_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `center_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_categories`
--

INSERT INTO `test_categories` (`category_id`, `category_name`, `center_id`) VALUES
(9, 'Urine Test', 2),
(11, 'Lahre', 3),
(12, 'Old Parents', 2);

-- --------------------------------------------------------

--
-- Table structure for table `test_details`
--

CREATE TABLE `test_details` (
  `test_id` int(11) NOT NULL,
  `test_name` varchar(100) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `reporting_time` varchar(50) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_details`
--

INSERT INTO `test_details` (`test_id`, `test_name`, `category_id`, `cost`, `reporting_time`, `category_name`) VALUES
(8, 'New test 1', 8, 25.00, '35 Hours', 'New Category'),
(9, 'Test 1', 9, 200.00, '2332', 'Urine Test'),
(10, 'Urdu Test', 11, 400.00, '2 hours', 'Lahre');

-- --------------------------------------------------------

--
-- Table structure for table `test_feedback`
--

CREATE TABLE `test_feedback` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `test_id` int(11) NOT NULL,
  `feedback` varchar(255) NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_feedback`
--

INSERT INTO `test_feedback` (`id`, `patient_id`, `test_id`, `feedback`, `rating`, `created_at`) VALUES
(2, 5, 8, 'NEw Test', 5, '2023-10-10 06:07:49');

-- --------------------------------------------------------

--
-- Table structure for table `test_requests`
--

CREATE TABLE `test_requests` (
  `request_id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `test_id` int(11) NOT NULL,
  `patient_email` varchar(255) NOT NULL,
  `patient_contactno` varchar(15) NOT NULL,
  `status` varchar(20) NOT NULL,
  `date_requested` date NOT NULL,
  `test_name` varchar(255) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `reporting_time` varchar(50) NOT NULL,
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `center_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_requests`
--

INSERT INTO `test_requests` (`request_id`, `patient_name`, `test_id`, `patient_email`, `patient_contactno`, `status`, `date_requested`, `test_name`, `cost`, `reporting_time`, `category_id`, `category_name`, `patient_id`, `center_id`) VALUES
(10, 'nasiryt8207gmailcom', 10, 'nasiryt.827@gmail.com', '03176526827', 'Approved', '2023-10-10', 'Urdu Test', 400.00, '2 hours', 11, 'Lahre', 5, 2),
(11, 'nasiryt8207gmailcom', 9, 'nasiryt.827@gmail.com', '03176526827', 'Approved', '2023-10-10', 'Test 1', 200.00, '2332', 9, 'Urine Test', 8, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diagnostic_centers`
--
ALTER TABLE `diagnostic_centers`
  ADD PRIMARY KEY (`center_id`),
  ADD KEY `manager_id` (`manager_id`);

--
-- Indexes for table `managers`
--
ALTER TABLE `managers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resets`
--
ALTER TABLE `resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_categories`
--
ALTER TABLE `test_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD KEY `center_id` (`center_id`);

--
-- Indexes for table `test_details`
--
ALTER TABLE `test_details`
  ADD PRIMARY KEY (`test_id`);

--
-- Indexes for table `test_feedback`
--
ALTER TABLE `test_feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_requests`
--
ALTER TABLE `test_requests`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `test_id` (`test_id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `diagnostic_centers`
--
ALTER TABLE `diagnostic_centers`
  MODIFY `center_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `managers`
--
ALTER TABLE `managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `resets`
--
ALTER TABLE `resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test_categories`
--
ALTER TABLE `test_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `test_details`
--
ALTER TABLE `test_details`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `test_feedback`
--
ALTER TABLE `test_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `test_requests`
--
ALTER TABLE `test_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diagnostic_centers`
--
ALTER TABLE `diagnostic_centers`
  ADD CONSTRAINT `diagnostic_centers_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `managers` (`id`);

--
-- Constraints for table `test_categories`
--
ALTER TABLE `test_categories`
  ADD CONSTRAINT `test_categories_ibfk_1` FOREIGN KEY (`center_id`) REFERENCES `diagnostic_centers` (`center_id`);

--
-- Constraints for table `test_requests`
--
ALTER TABLE `test_requests`
  ADD CONSTRAINT `test_requests_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `test_details` (`test_id`),
  ADD CONSTRAINT `test_requests_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `test_categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
